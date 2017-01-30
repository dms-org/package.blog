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
use Dms\Package\Blog\Domain\Services\Persistence\IBlogCategoryRepository;

class BlogCategoryModule extends CrudModule
{
    /**
     * @var IClock
     */
    protected $clock;

    /**
     * @var BlogConfiguration
     */
    private $blogConfiguration;

    /**
     * BlogCategoryModule constructor.
     *
     * @param IBlogCategoryRepository $dataSource
     * @param IAuthSystem             $authSystem
     * @param IClock                  $clock
     * @param BlogConfiguration       $blogConfiguration
     */
    public function __construct(IBlogCategoryRepository $dataSource, IAuthSystem $authSystem, IClock $clock, BlogConfiguration $blogConfiguration)
    {
        $this->clock             = $clock;
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
        $module->name('categories');

        $module->metadata([
            'icon' => 'list',
        ]);

        $module->labelObjects()->fromProperty(BlogCategory::NAME);

        $module->crudForm(function (CrudFormDefinition $form) {
            $form->section('Details', [
                $form->field(
                    Field::create('name', 'Name')->string()->required()
                )->bindToProperty(BlogCategory::NAME),
            ]);

            SlugField::build($form, 'slug', 'URL Friendly Name', $this->dataSource, $this->blogConfiguration->getSlugGenerator(), 'name', BlogAuthor::SLUG);

            $form->continueSection([
                $form->field(
                    Field::create('published', 'Published?')->bool()
                )->bindToProperty(BlogCategory::PUBLISHED),
            ]);

            if ($form->isCreateForm()) {
                $form->onSubmit(function (BlogCategory $blogCategory) {
                    $blogCategory->articles  = BlogArticle::collection();
                    $blogCategory->createdAt = new DateTime($this->clock->utcNow());
                    $blogCategory->updatedAt = new DateTime($this->clock->utcNow());
                });
            }

            if ($form->isEditForm()) {
                $form->onSubmit(function (BlogCategory $blogCategory) {
                    $blogCategory->updatedAt = new DateTime($this->clock->utcNow());
                });
            }
        });

        $module->removeAction()->deleteFromDataSource();

        $module->summaryTable(function (SummaryTableDefinition $table) {
            $table->mapProperty(BlogCategory::NAME)->to(Field::create('name', 'Name')->string());
            $table->mapProperty(BlogCategory::ARTICLES . '.count()')->to(Field::create('articles', '# Articles')->int());
            $table->mapProperty(BlogCategory::UPDATED_AT)->to(Field::create('updated', 'Updated At')->dateTime());

            $table->view('all', 'All')
                ->asDefault()
                ->loadAll();
        });
    }
}