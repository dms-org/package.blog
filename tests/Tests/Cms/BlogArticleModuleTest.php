<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Tests\Cms;

use Dms\Common\Structure\DateTime\Date;
use Dms\Common\Structure\FileSystem\Image;
use Dms\Common\Structure\FileSystem\UploadAction;
use Dms\Common\Structure\Web\Html;
use Dms\Core\Auth\IPermission;
use Dms\Core\Auth\Permission;
use Dms\Core\Common\Crud\CrudModule;
use Dms\Core\Common\Crud\ICrudModule;
use Dms\Core\File\UploadedImageProxy;
use Dms\Core\Model\IMutableObjectSet;
use Dms\Core\Persistence\ArrayRepository;
use Dms\Core\Tests\Common\Crud\Modules\CrudModuleTest;
use Dms\Core\Tests\Module\Mock\MockAuthSystem;
use Dms\Library\Testing\Mock\MockClock;
use Dms\Package\Blog\Cms\BlogArticleModule;
use Dms\Package\Blog\Domain\Entities\BlogArticle;
use Dms\Package\Blog\Domain\Entities\BlogAuthor;
use Dms\Package\Blog\Domain\Entities\BlogCategory;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogArticleRepository;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogAuthorRepository;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogCategoryRepository;
use Dms\Package\Blog\Tests\Helper\TestBlogConfiguration;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogArticleModuleTest extends CrudModuleTest
{
    /**
     * @return IMutableObjectSet
     */
    protected function buildRepositoryDataSource() : IMutableObjectSet
    {
        return new class(BlogArticle::collection()) extends ArrayRepository implements IBlogArticleRepository
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
        return new BlogArticleModule(
            $dataSource,
            $authSystem,
            $this->buildCategoryRepo(),
            $this->buildAuthorRepo(),
            new MockClock('2000-01-01 00:00:00'),
            TestBlogConfiguration::build()
        );
    }

    protected function buildCategoryRepo()
    {
        $category = new BlogCategory(
            'Category', 'category', true, new MockClock('2000-01-01 00:00:00')
        );
        $category->setId(1);

        return new class(BlogCategory::collection([$category])) extends ArrayRepository implements IBlogCategoryRepository
        {

        };
    }

    protected function buildAuthorRepo()
    {
        $author = new BlogAuthor(
            'Name', 'slug', 'role', new Html('bio')
        );
        $author->setId(1);

        return new class(BlogAuthor::collection([$author])) extends ArrayRepository implements IBlogAuthorRepository
        {

        };
    }

    /**
     * @return string
     */
    protected function expectedName()
    {
        return 'articles';
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
        $article = $this->module->getCreateAction()->run([
            'author'           => 1,
            'category'         => 1,
            'title'            => 'title',
            'sub_title'        => 'sub-title',
            'slug'             => 'slug',
            'extract'          => 'extract',
            'featured_image'   => [
                'action' => UploadAction::STORE_NEW,
                'file'   => new UploadedImageProxy(new Image(__DIR__ . '/storage/placeholder.png')),
            ],
            'date'             => 'Jan 1st 2000',
            'article_content'  => 'content',
            'allow_sharing'    => true,
            'allow_commenting' => true,
            'is_active'        => true,
        ]);

        $expected              = new BlogArticle(
            $author = new BlogAuthor('Name', 'slug', 'role', new Html('bio')),
            $category = new BlogCategory('Category', 'category', true, new MockClock('2000-01-01 00:00:00')),
            'title',
            'sub-title',
            'extract',
            'slug',
            $image = new Image(__DIR__ . '/storage/placeholder.png'),
            new Date(2000, 01, 01),
            new Html('content'),
            true,
            true,
            true,
            new MockClock('2000-01-01 00:00:00')
        );
        $image->getWidth();
        $expected->setId(1);
        $author->setId(1);
        $category->setId(1);

        $this->assertEquals($expected, $article);
    }
}