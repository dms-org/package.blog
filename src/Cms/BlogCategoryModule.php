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
use Dms\Package\Blog\Core\BlogCategory;
use Dms\Package\Blog\Core\IBlogCategoryRepository;

class BlogCategoryModule extends CrudModule
{
    /**
     * @var IClock
     */
    protected $clock;

    /**
     * ResourceCategoriesModule constructor.
     * @param IBlogCategoryRepository $dataSource
     * @param IAuthSystem $authSystem
     * @param IClock $clock
     */
    public function __construct(IBlogCategoryRepository $dataSource, IAuthSystem $authSystem, ICLock $clock)
    {
        parent::__construct($dataSource, $authSystem);

        $this->clock = $clock;
    }

    /**
     * Defines the structure of this module.
     *
     * @param CrudModuleDefinition $module
     */
    protected function defineCrudModule(CrudModuleDefinition $module)
    {
        $module->name('blog-categories');

        $module->metadata([
            'icon' => 'list'
        ]);

        $module->labelObjects()->fromProperty(BlogCategory::NAME);

        $module->crudForm(function (CrudFormDefinition $form) {
            $form->section('Details', [
                $form->field(
                    Field::create('name', 'Name')->string()->required()
                )->bindToProperty(BlogCategory::NAME),
                //
                $form->field(
                    Field::create('is_active', 'Is Active')->bool()
                )->bindToProperty(BlogCategory::IS_ACTIVE),
            ]);

            if ($form->isCreateForm()) {
                $form->onSubmit(function (BlogCategory $blogCategory) {
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

            $table->view('all', 'All')
                ->asDefault()
                ->loadAll();
        });
    }
}