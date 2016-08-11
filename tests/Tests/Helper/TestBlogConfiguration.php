<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Tests\Helper;

use Dms\Core\Exception;
use Dms\Library\Slug\Generator\DashedSlugGenerator;
use Dms\Package\Blog\Domain\Services\Config\BlogConfiguration;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class TestBlogConfiguration
{
    /**
     * @return BlogConfiguration
     */
    public static function build() : BlogConfiguration
    {
        return new BlogConfiguration(
            dirname(__DIR__),
            new DashedSlugGenerator()
        );
    }
}