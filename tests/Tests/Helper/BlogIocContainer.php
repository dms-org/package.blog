<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Tests\Helper;

use Dms\Core\Auth\IAuthSystem;
use Dms\Core\Cms;
use Dms\Core\CmsDefinition;
use Dms\Core\Event\IEventDispatcher;
use Dms\Core\ICms;
use Dms\Core\Ioc\IIocContainer;
use Dms\Core\Persistence\Db\Connection\Dummy\DummyConnection;
use Dms\Core\Persistence\Db\Connection\IConnection;
use Dms\Core\Persistence\Db\Mapping\IOrm;
use Dms\Core\Tests\Module\Mock\MockAuthSystem;
use Dms\Core\Tests\Module\Mock\MockEventDispatcher;
use Dms\Core\Util\DateTimeClock;
use Dms\Core\Util\IClock;
use Dms\Library\Testing\Helper\TestIocContainer;
use Dms\Package\Blog\Cms\BlogPackage;
use Dms\Package\Blog\Domain\Services\Config\BlogConfiguration;
use Dms\Package\Blog\Infrastructure\Persistence\BlogOrm;
use Dms\Package\Shop\Cms\ShopPackage;
use Dms\Package\Shop\Domain\Services\Config\ShopConfiguration;
use Dms\Package\Shop\Infrastructure\Persistence\ShopOrm;
use Illuminate\Container\Container;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogIocContainer extends TestIocContainer
{
    /**
     * TestIocContainer constructor.
     */
    public function __construct(\PHPUnit_Framework_TestCase $test)
    {
        parent::__construct();

        $this->bindValue(BlogConfiguration::class, TestBlogConfiguration::build());
        $this->bind(self::SCOPE_SINGLETON, IOrm::class, BlogOrm::class);

        $cms = $test->getMockForAbstractClass(ICms::class);
        $cms->method('getIocContainer')->willReturn($this);

        BlogPackage::boot($cms);
    }
}