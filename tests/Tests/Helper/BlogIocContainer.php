<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Tests\Helper;

use Dms\Core\ICms;
use Dms\Core\Persistence\Db\Mapping\IOrm;
use Dms\Library\Testing\Helper\TestIocContainer;
use Dms\Package\Blog\Cms\BlogPackage;
use Dms\Package\Blog\Domain\Services\Config\BlogConfiguration;
use Dms\Package\Blog\Infrastructure\Persistence\BlogOrm;
use PHPUnit\Framework\TestCase;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogIocContainer extends TestIocContainer
{
    /**
     * TestIocContainer constructor.
     */
    public function __construct(TestCase $test)
    {
        parent::__construct();

        $this->bindValue(BlogConfiguration::class, TestBlogConfiguration::build());
        $this->bind(self::SCOPE_SINGLETON, IOrm::class, BlogOrm::class);

        $cms = $test->getMockBuilder(ICms::class)->getMockForAbstractClass();
        $cms->method('getIocContainer')->willReturn($this);

        BlogPackage::boot($cms);
    }
}