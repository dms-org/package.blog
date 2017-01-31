<?php

namespace Dms\Package\Blog\Cms;

use Dms\Common\Structure\DateTime\DateTime;
use Dms\Common\Structure\Field;
use Dms\Core\Auth\IAuthSystem;
use Dms\Core\Common\Crud\CrudModule;
use Dms\Core\Common\Crud\Definition\CrudModuleDefinition;
use Dms\Core\Common\Crud\Definition\Form\CrudFormDefinition;
use Dms\Core\Common\Crud\Definition\Table\SummaryTableDefinition;
use Dms\Core\Common\Crud\ICrudModule;
use Dms\Core\Model\IMutableObjectSet;
use Dms\Core\Util\IClock;
use Dms\Package\Blog\Domain\Entities\BlogArticle;
use Dms\Package\Blog\Domain\Entities\BlogArticleComment;
use Dms\Package\Blog\Domain\Services\Config\BlogConfiguration;

class BlogArticleCommentModule extends CrudModule
{
    /**
     * @var BlogArticle
     */
    protected $blogArticle;

    /**
     * @var IClock
     */
    protected $clock;

    /**
     * @var BlogConfiguration
     */
    private $blogConfiguration;

    /**
     * ResourceCategoriesModule constructor.
     *
     * @param BlogArticle       $blogArticle
     * @param IAuthSystem       $authSystem
     * @param IClock            $clock
     * @param BlogConfiguration $blogConfiguration
     * @param IMutableObjectSet $dataSource
     */
    public function __construct(
        BlogArticle $blogArticle,
        IAuthSystem $authSystem,
        IClock $clock,
        BlogConfiguration $blogConfiguration,
        IMutableObjectSet $dataSource = null
    ) {
        $this->blogArticle       = $blogArticle;
        $this->clock             = $clock;
        $this->blogConfiguration = $blogConfiguration;

        parent::__construct($dataSource ?? $blogArticle->comments, $authSystem);
    }

    protected function loadCrudModuleWithDataSource(IMutableObjectSet $dataSource): ICrudModule
    {
        return new self($this->blogArticle, $this->authSystem, $this->clock, $this->blogConfiguration, $dataSource);
    }

    /**
     * Defines the structure of this module.
     *
     * @param CrudModuleDefinition $module
     */
    protected function defineCrudModule(CrudModuleDefinition $module)
    {
        $module->name('articles');

        $module->labelObjects()->fromProperty(BlogArticleComment::AUTHOR_NAME);

        $module->crudForm(function (CrudFormDefinition $form) {
            $form->section('Details', [
                $form->field(
                    Field::create('author_name', 'Author Name')->string()->required()
                )->bindToProperty(BlogArticleComment::AUTHOR_NAME),
                //
                $form->field(
                    Field::create('author_email', 'Author Email')->email()->required()
                )->bindToProperty(BlogArticleComment::AUTHOR_EMAIL),
                //
                $form->field(
                    Field::create('comment', 'Comment')->string()->required()->multiline()
                )->bindToProperty(BlogArticleComment::CONTENT),
            ]);

            $form->onSubmit(function (BlogArticleComment $comment) {
                $comment->article = $this->blogArticle;
            });

            if ($form->isCreateForm()) {
                $form->onSubmit(function (BlogArticleComment $comment) {
                    $comment->postedAt = new DateTime($this->clock->utcNow());
                });
            } else {
                $form->continueSection([
                    $form->field(
                        Field::create('posted_at', 'Posted At')->dateTime()->required()->readonly()
                    )->bindToProperty(BlogArticleComment::POSTED_AT),
                ]);
            }
        });

        $module->removeAction()->deleteFromDataSource();

        $module->summaryTable(function (SummaryTableDefinition $table) {
            $table->mapProperty(BlogArticleComment::AUTHOR_NAME)->to( Field::create('author_name', 'Author Name')->string());
            $table->mapProperty(BlogArticleComment::AUTHOR_EMAIL)->to( Field::create('author_email', 'Author Email')->email());
            $table->mapProperty(BlogArticleComment::CONTENT)->to(Field::create('comment', 'Comment')->string());

            $table->view('all', 'All')
                ->asDefault()
                ->loadAll();
        });
    }
}