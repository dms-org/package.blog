<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Tests\Infrastructure\Persistence;

use Dms\Core\Persistence\Db\Mapping\IOrm;
use Dms\Library\Testing\Mock\MockClock;
use Dms\Library\Testing\TestCase\EntityPersistenceTest;
use Dms\Package\Blog\Infrastructure\Persistence\BlogOrm;
use Dms\Package\Blog\Tests\Helper\BlogIocContainer;

/**
 *
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
abstract class PersistenceTest extends EntityPersistenceTest
{
    /**
     * @return IOrm
     */
    protected function loadOrm()
    {
        $orm = new BlogOrm(new BlogIocContainer($this));

        return $orm;
    }

    protected function mockClock(string $dateTime)
    {
        return new MockClock($dateTime);
    }
}