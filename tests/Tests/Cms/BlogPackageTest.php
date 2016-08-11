<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Tests\Cms;

use Dms\Common\Testing\CmsTestCase;
use Dms\Package\Blog\Cms\BlogPackage;
use Dms\Package\Blog\Tests\Helper\BlogIocContainer;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogPackageTest extends CmsTestCase
{
    public function testNewPackage()
    {
        $package = new BlogPackage(new BlogIocContainer($this));
        $package->loadModules();
    }
}