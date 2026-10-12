<?php

namespace Brightforge\ProjectManager\Tests;

use Brightforge\ProjectManager\Support\TextHelper;
use PHPUnit\Framework\TestCase;

class TextHelperTest extends TestCase
{
    public function test_clean_title_removes_outer_whitespace(): void
    {
        $helper = new TextHelper();

        $result = $helper->cleanTitle('  My Project  ');

        $this->assertSame('My Project', $result);
    }
}