<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Tests\Cms;

use Dms\Core\Auth\IPermission;
use Dms\Core\Auth\Permission;
use Dms\Core\Common\Crud\CrudModule;
use Dms\Core\Common\Crud\ICrudModule;
use Dms\Core\Model\IMutableObjectSet;
use Dms\Core\Persistence\ArrayRepository;
use Dms\Core\Tests\Common\Crud\Modules\CrudModuleTest;
use Dms\Core\Tests\Module\Mock\MockAuthSystem;
use Dms\Library\Testing\Mock\MockClock;
use Dms\Package\Blog\Cms\BlogCategoryModule;
use Dms\Package\Blog\Domain\Entities\BlogCategory;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogCategoryRepository;
use Dms\Package\Blog\Tests\Helper\TestBlogConfiguration;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogCategoryModuleTest extends CrudModuleTest
{
    /**
     * @return IMutableObjectSet
     */
    protected function buildRepositoryDataSource() : IMutableObjectSet
    {
        return new class(BlogCategory::collection()) extends ArrayRepository implements IBlogCategoryRepository
        {

        };
    }

    /**
     * @param IMutableObjectSet $dataSource
     * @param MockAuthSystem    $authSystem
     *
     * @return ICrudModule
     */
    protected function buildCrudModule(IMutableObjectSet $dataSource, MockAuthSystem $authSystem) : ICrudModule
    {
        return new BlogCategoryModule(
            $dataSource,
            $authSystem,
            new MockClock('2000-01-01 00:00:00'),
            TestBlogConfiguration::build()
        );
    }

    /**
     * @return string
     */
    protected function expectedName()
    {
        return 'categories';
    }

    /**
     * @return IPermission[]
     */
    protected function expectedReadModulePermissions()
    {
        return [
            Permission::named(CrudModule::EDIT_PERMISSION),
            Permission::named(CrudModule::CREATE_PERMISSION),
            Permission::named(CrudModule::REMOVE_PERMISSION),
        ];
    }

    /**
     * @return IPermission[]
     */
    protected function expectedReadModuleRequiredPermissions()
    {
        return [
            Permission::named(CrudModule::VIEW_PERMISSION),
        ];
    }

    public function testCreate()
    {
        /** @var BlogCategory $category */
        $category = $this->module->getCreateAction()->run([
            'name'      => 'Category',
            'slug'      => 'category',
            'info'      => 'info',
            'published' => true,
        ]);

        $expected = new BlogCategory(
            'Category', 'category', true, new MockClock('2000-01-01 00:00:00')
        );
        $expected->setId(1);

        $this->assertEquals($expected, $category);
    }
}