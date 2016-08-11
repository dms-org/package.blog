<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Tests\Cms;

use Dms\Common\Structure\Web\Html;
use Dms\Core\Auth\IPermission;
use Dms\Core\Auth\Permission;
use Dms\Core\Common\Crud\CrudModule;
use Dms\Core\Common\Crud\ICrudModule;
use Dms\Core\Model\IMutableObjectSet;
use Dms\Core\Persistence\ArrayRepository;
use Dms\Core\Tests\Common\Crud\Modules\CrudModuleTest;
use Dms\Core\Tests\Module\Mock\MockAuthSystem;
use Dms\Library\Testing\Mock\MockClock;
use Dms\Package\Blog\Cms\BlogAuthorModule;
use Dms\Package\Blog\Domain\Entities\BlogAuthor;
use Dms\Package\Blog\Domain\Entities\BlogCategory;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogAuthorRepository;
use Dms\Package\Blog\Tests\Helper\TestBlogConfiguration;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogAuthorModuleTest extends CrudModuleTest
{
    /**
     * @return IMutableObjectSet
     */
    protected function buildRepositoryDataSource() : IMutableObjectSet
    {
        return new class(BlogAuthor::collection()) extends ArrayRepository implements IBlogAuthorRepository
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
        return new BlogAuthorModule(
            $dataSource,
            $authSystem,
            TestBlogConfiguration::build()
        );
    }

    /**
     * @return string
     */
    protected function expectedName()
    {
        return 'authors';
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
        $author = $this->module->getCreateAction()->run([
            'name'      => 'Name',
            'slug'      => 'slug',
            'role'      => 'role',
            'bio'       => 'bio',
            'is_active' => true,
        ]);

        $expected = new BlogAuthor(
            'Name', 'slug', 'role', new Html('bio')
        );
        $expected->setId(1);

        $this->assertEquals($expected, $author);
    }
}