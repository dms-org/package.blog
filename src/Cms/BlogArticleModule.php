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
use Dms\Library\Slug\Cms\SlugField;
use Dms\Package\Blog\Domain\Entities\BlogArticle;
use Dms\Package\Blog\Domain\Entities\BlogAuthor;
use Dms\Package\Blog\Domain\Entities\BlogCategory;
use Dms\Package\Blog\Domain\Services\Config\BlogConfiguration;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogArticleRepository;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogAuthorRepository;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogCategoryRepository;

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
     * @var IBlogAuthorRepository
     */
    private $blogAuthorRepository;

    /**
     * @var BlogConfiguration
     */
    private $blogConfiguration;

    /**
     * ResourceCategoriesModule constructor.
     *
     * @param IBlogArticleRepository  $dataSource
     * @param IAuthSystem             $authSystem
     * @param IBlogCategoryRepository $blogCategoryRepository
     * @param IBlogAuthorRepository   $blogAuthorRepository
     * @param IClock                  $clock
     * @param BlogConfiguration       $blogConfiguration
     */
    public function __construct(
        IBlogArticleRepository $dataSource,
        IAuthSystem $authSystem,
        IBlogCategoryRepository $blogCategoryRepository,
        IBlogAuthorRepository $blogAuthorRepository,
        IClock $clock,
    BlogConfiguration $blogConfiguration
    ) {
        $this->blogCategoryRepository = $blogCategoryRepository;
        $this->clock                  = $clock;
        $this->blogAuthorRepository = $blogAuthorRepository;
        $this->blogConfiguration = $blogConfiguration;

        parent::__construct($dataSource, $authSystem);
    }

    /**
     * Defines the structure of this module.
     *
     * @param CrudModuleDefinition $module
     */
    protected function defineCrudModule(CrudModuleDefinition $module)
    {
        $module->name('articles');

        $module->metadata([
            'icon' => 'newspaper-o',
        ]);

        $module->labelObjects()->fromProperty(BlogArticle::TITLE);

        $module->crudForm(function (CrudFormDefinition $form) {
            $form->section('Details', [
                $form->field(
                    Field::create('category', 'Category')
                        ->entityFrom($this->blogCategoryRepository)
                        ->labelledBy(BlogCategory::NAME)
                )->bindToProperty(BlogArticle::CATEGORY),
                //
                $form->field(
                    Field::create('author', 'Author')
                        ->entityFrom($this->blogAuthorRepository)
                        ->labelledBy(BlogAuthor::NAME)
                        ->required()
                )->bindToProperty(BlogArticle::AUTHOR),
                //
                $form->field(
                    Field::create('title', 'Title')->string()->required()
                )->bindToProperty(BlogArticle::TITLE),
            ]);
            
            SlugField::build($form, 'slug', 'URL Friendly Name', $this->dataSource, $this->blogConfiguration->getSlugGenerator(), 'title', BlogArticle::SLUG);
            
            $form->continueSection([
                $form->field(
                    Field::create('sub_title', 'Sub Title')->string()->defaultTo('')
                )->bindToProperty(BlogArticle::SUB_TITLE),
                //
                $form->field(
                    Field::create('date', 'Date')->date()->required()
                )->bindToProperty(BlogArticle::DATE),
                //
                $form->field(
                    Field::create('extract', 'Extract')->string()->multiline()->defaultTo('')
                )->bindToProperty(BlogArticle::EXTRACT),
                //
                $form->field(
                    Field::create('featured_image', 'Featured Image')
                        ->image()
                        ->moveToPathWithRandomFileName($this->blogConfiguration->getFeaturedImagePath())
                )->bindToProperty(BlogArticle::FEATURED_IMAGE),
                //
                $form->field(
                    Field::create('article_content', 'Article Content')->html()->required()
                )->bindToProperty(BlogArticle::ARTICLE_CONTENT),
            ]);

            $form->section('Publish Settings', [
                $form->field(
                    Field::create('is_active', 'Is Active')->bool()
                )->bindToProperty(BlogArticle::IS_ACTIVE),
                //
                $form->field(
                    Field::create('allow_sharing', 'Allow Sharing')->bool()
                )->bindToProperty(BlogArticle::ALLOW_SHARING),
                //
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