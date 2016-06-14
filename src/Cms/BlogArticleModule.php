<?php

namespace Dms\Package\Blog\Cms;

use Dms\Common\Structure\DateTime\DateTime;
use Dms\Common\Structure\Field;
use Dms\Core\Auth\IAuthSystem;
use Dms\Core\Common\Crud\CrudModule;
use Dms\Core\Common\Crud\Definition\CrudModuleDefinition;
use Dms\Core\Common\Crud\Definition\Form\CrudFormDefinition;
use Dms\Core\Common\Crud\Definition\Table\SummaryTableDefinition;
use Dms\Core\Util\IClock;
use Dms\Package\Blog\Core\BlogArticle;
use Dms\Package\Blog\Core\BlogCategory;
use Dms\Package\Blog\Core\IBlogArticleRepository;
use Dms\Package\Blog\Core\IBlogCategoryRepository;

class BlogArticleModule extends CrudModule
{
    /**
     * @var IClock
     */
    protected $clock;

    /**
     * @var IBlogCategoryRepository
     */
    private $blogCategoryRepository;

    /**
     * ResourceCategoriesModule constructor.
     * @param IBlogArticleRepository $dataSource
     * @param IAuthSystem $authSystem
     * @param IBlogCategoryRepository $blogCategoryRepository
     * @param IClock $clock
     */
    public function __construct(IBlogArticleRepository $dataSource, IAuthSystem $authSystem, IBlogCategoryRepository $blogCategoryRepository, ICLock $clock) {
        $this->blogCategoryRepository = $blogCategoryRepository;

        parent::__construct($dataSource, $authSystem);

        $this->clock = $clock;
    }

    /**
     * @var boolean
     */
    public $allowSharing;

    /**
     * @var boolean
     */
    public $allowCommenting;

    /**
     * @var integer|null
     */
    public $categoryId;


    /**
     * Defines the structure of this module.
     *
     * @param CrudModuleDefinition $module
     */
    protected function defineCrudModule(CrudModuleDefinition $module)
    {
        $module->name('blog-articles');

        $module->metadata([
            'icon' => 'newspaper-o'
        ]);

        $module->labelObjects()->fromProperty(BlogArticle::TITLE);

        $module->crudForm(function (CrudFormDefinition $form) {
            $form->section('Details', [
                $form->field(
                    Field::create('blog_category_id', 'Category')->entityIdFrom($this->blogCategoryRepository)->labelledBy(BlogCategory::NAME)->required()
                )->bindToProperty(BlogArticle::CATEGORY_ID),
                //
                $form->field(
                    Field::create('title', 'Title')->string()->required()
                )->bindToProperty(BlogArticle::TITLE),
                //
                $form->field(
                    Field::create('sub_title', 'Sub Title')->string()
                )->bindToProperty(BlogArticle::SUB_TITLE),
                //
                $form->field(
                    Field::create('date', 'Date')->date()->required()
                )->bindToProperty(BlogArticle::DATE),
                //
                $form->field(
                    Field::create('extract', 'Extract')->string()->multiline()
                )->bindToProperty(BlogArticle::EXTRACT),
                //
                $form->field(
                    Field::create('featured_image', 'Featured Image')->image()->moveToPathWithRandomFileName(public_path('files/blogs/images'))
                )->bindToProperty(BlogArticle::FEATURED_IMAGE),
                //
                $form->field(
                    Field::create('article_content', 'Article Content')->html()->required()
                )->bindToProperty(BlogArticle::ARTICLE_CONTENT),
            ]);

            $form->section('Author', [
                $form->field(
                    Field::create('author_name', 'Author Name')->string()
                )->bindToProperty(BlogArticle::AUTHOR_NAME),
                //
                $form->field(
                    Field::create('author_role', 'Author Role')->string()
                )->bindToProperty(BlogArticle::AUTHOR_ROLE),
                //
                $form->field(
                    Field::create('author_link', 'Author Link')->url()
                )->bindToProperty(BlogArticle::AUTHOR_LINK)
            ]);

            $form->section('Publish Settings', [
                $form->field(
                    Field::create('is_active', 'Is Active')->bool()
                )->bindToProperty(BlogArticle::IS_ACTIVE),
                $form->field(
                    Field::create('allow_sharing', 'Allow Sharing')->bool()
                )->bindToProperty(BlogArticle::ALLOW_SHARING),
                $form->field(
                    Field::create('allow_commenting', 'Allow Commenting')->bool()
                )->bindToProperty(BlogArticle::ALLOW_COMMENTING),
            ]);

            if ($form->isCreateForm()) {
                $form->onSubmit(function (BlogArticle $blogArticle) {
                    $blogArticle->createdAt = new DateTime($this->clock->utcNow());
                    $blogArticle->updatedAt = new DateTime($this->clock->utcNow());
                });
            }

            if ($form->isEditForm()) {
                $form->onSubmit(function (BlogArticle $blogArticle) {
                    $blogArticle->updatedAt = new DateTime($this->clock->utcNow());
                });
            }
        });

        $module->removeAction()->deleteFromDataSource();

        $module->summaryTable(function (SummaryTableDefinition $table) {
            $table->mapProperty(BlogArticle::TITLE)->to(Field::create('title', 'Title')->string());

            $table->view('all', 'All')
                ->asDefault()
                ->loadAll();
        });
    }
}