<?php

namespace Brightforge\ProjectManager\Tests;

use Brightforge\ProjectManager\Support\ProjectQuery;
use PHPUnit\Framework\TestCase;

class ProjectQueryTest extends TestCase
{
    public function test_industry_and_technology_become_slugs(): void
    {
        $filters = ProjectQuery::sanitizeFilters([
            'industry'   => 'Life Sciences!',
            'technology' => 'PHP',
        ]);

        $this->assertSame('life-sciences', $filters['industry']);
        $this->assertSame('php', $filters['technology']);
    }

    public function test_known_status_is_kept(): void
    {
        $filters = ProjectQuery::sanitizeFilters(['status' => 'In Progress']);

        $this->assertSame('In Progress', $filters['status']);
    }

    public function test_unknown_status_is_dropped(): void
    {
        $filters = ProjectQuery::sanitizeFilters(['status' => 'Hacked']);

        $this->assertSame('', $filters['status']);
    }

    /**
     * @dataProvider perPageProvider
     */
    public function test_per_page_stays_between_1_and_50(mixed $input, int $expected): void
    {
        $filters = ProjectQuery::sanitizeFilters(['per_page' => $input]);

        $this->assertSame($expected, $filters['per_page']);
    }

    public static function perPageProvider(): array
    {
        return [
            'normal value'    => ['12', 12],
            'too large'       => ['9999', 50],
            'zero uses 6'     => ['0', 6],
            'missing uses 6'  => ['', 6],
            'text uses 6'     => ['abc', 6],
        ];
    }

    public function test_page_is_never_below_one(): void
    {
        $this->assertSame(1, ProjectQuery::sanitizeFilters(['page' => '0'])['page']);
        $this->assertSame(1, ProjectQuery::sanitizeFilters([])['page']);
        $this->assertSame(3, ProjectQuery::sanitizeFilters(['page' => '3'])['page']);
    }

    public function test_ids_become_positive_integers(): void
    {
        $filters = ProjectQuery::sanitizeFilters(['ids' => '12, 15,abc,0']);

        $this->assertSame([12, 15], $filters['ids']);
    }

    public function test_arrays_in_the_input_are_ignored(): void
    {
        // For example a visitor typing ?industry[]=x in the address bar.
        $filters = ProjectQuery::sanitizeFilters([
            'industry' => ['x'],
            'status'   => ['Completed'],
            'search'   => ['a'],
        ]);

        $this->assertSame('', $filters['industry']);
        $this->assertSame('', $filters['status']);
        $this->assertSame('', $filters['search']);
    }

    public function test_search_text_has_tags_removed(): void
    {
        $filters = ProjectQuery::sanitizeFilters(['search' => '<b>bank</b> app']);

        $this->assertSame('bank app', $filters['search']);
    }

    public function test_query_is_always_limited_to_published_projects(): void
    {
        $args = ProjectQuery::buildArgs(ProjectQuery::sanitizeFilters([]));

        $this->assertSame('project', $args['post_type']);
        $this->assertSame('publish', $args['post_status']);
    }

    public function test_no_filters_means_no_extra_conditions(): void
    {
        $args = ProjectQuery::buildArgs(ProjectQuery::sanitizeFilters([]));

        $this->assertArrayNotHasKey('tax_query', $args);
        $this->assertArrayNotHasKey('meta_query', $args);
        $this->assertArrayNotHasKey('s', $args);
        $this->assertArrayNotHasKey('post__in', $args);
    }

    public function test_industry_and_technology_are_combined_with_and(): void
    {
        $args = ProjectQuery::buildArgs(ProjectQuery::sanitizeFilters([
            'industry'   => 'fintech',
            'technology' => 'php',
        ]));

        $this->assertSame('AND', $args['tax_query']['relation']);
        $this->assertSame('project_type', $args['tax_query'][0]['taxonomy']);
        $this->assertSame('technologies', $args['tax_query'][1]['taxonomy']);
    }

    public function test_status_is_matched_with_a_meta_query(): void
    {
        $args = ProjectQuery::buildArgs(ProjectQuery::sanitizeFilters(['status' => 'Completed']));

        $this->assertSame('_brightforge_project_status', $args['meta_query'][0]['key']);
        $this->assertSame('Completed', $args['meta_query'][0]['value']);
    }

    public function test_selected_projects_keep_the_order_given(): void
    {
        $args = ProjectQuery::buildArgs(ProjectQuery::sanitizeFilters(['ids' => '9,3']));

        $this->assertSame([9, 3], $args['post__in']);
        $this->assertSame('post__in', $args['orderby']);
    }
}
