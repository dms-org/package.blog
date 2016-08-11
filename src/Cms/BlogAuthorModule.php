<?php

namespace Dms\Package\Blog\Cms;

use Dms\Common\Structure\Field;
use Dms\Core\Auth\IAuthSystem;
use Dms\Core\Common\Crud\CrudModule;
use Dms\Core\Common\Crud\Definition\CrudModuleDefinition;
use Dms\Core\Common\Crud\Definition\Form\CrudFormDefinition;
use Dms\Core\Common\Crud\Definition\Table\SummaryTableDefinition;
use Dms\Library\Slug\Cms\SlugField;
use Dms\Package\Blog\Domain\Entities\BlogArticle;
use Dms\Package\Blog\Domain\Entities\BlogAuthor;
use Dms\Package\Blog\Domain\Services\Config\BlogConfiguration;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogAuthorRepository;

class BlogAuthorModule extends CrudModule
{
    /**
     * @var BlogConfiguration
     */
    private $blogConfiguration;

    /**
     * BlogAuthorModule constructor.
     *
     * @param IBlogAuthorRepository $dataSource
     * @param IAuthSystem           $authSystem
     * @param BlogConfiguration     $blogConfiguration
     */
    public function __construct(IBlogAuthorRepository $dataSource, IAuthSystem $authSystem, BlogConfiguration $blogConfiguration)
    {
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
        $module->name('authors');

        $module->metadata([
            'icon' => 'user',
        ]);

        $module->labelObjects()->fromProperty(BlogAuthor::NAME);

        $module->crudForm(function (CrudFormDefinition $form) {
            $form->section('Details', [
                $form->field(
                    Field::create('name', 'Name')->string()->required()
                )->bindToProperty(BlogAuthor::NAME),
            ]);

            SlugField::build($form, 'slug', 'URL Friendly Name', $this->dataSource, $this->blogConfiguration->getSlugGenerator(), 'name', BlogAuthor::SLUG);

            $form->continueSection([
                $form->field(
                    Field::create('role', 'Role')->string()->defaultTo('')
                )->bindToProperty(BlogAuthor::ROLE),
                //
                $form->field(
                    Field::create('bio', 'Bio')->html()->required()
                )->bindToProperty(BlogAuthor::BIO),
            ]);

            if ($form->isCreateForm()) {
                $form->onSubmit(function (BlogAuthor $blogAuthor) {
                    $blogAuthor->articles = BlogArticle::collection();
                });
            }
        });

        $module->removeAction()->deleteFromDataSource();

        $module->summaryTable(function (SummaryTableDefinition $table) {
            $table->mapProperty(BlogAuthor::NAME)->to(Field::create('name', 'Name')->string());

            $table->view('all', 'All')
                ->asDefault()
                ->loadAll();
        });
    }
}