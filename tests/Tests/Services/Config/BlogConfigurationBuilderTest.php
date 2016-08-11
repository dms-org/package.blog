<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Tests\Services\Config;

use Dms\Common\Testing\CmsTestCase;
use Dms\Package\Blog\Domain\Services\Config\BlogConfiguration;

/**
 *
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogConfigurationBuilderTest extends CmsTestCase
{
    public function testBuilder()
    {
        BlogConfiguration::builder()
            ->setFeaturedImagePath(__DIR__)
            ->useDashedSlugGenerator()
            ->build();
    }
}