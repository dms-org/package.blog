<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Persistence;

use Dms\Core\Persistence\Db\Mapping\Definition\Orm\OrmDefinition;
use Dms\Core\Persistence\Db\Mapping\Orm;
use Dms\Package\Blog\Core\BlogArticle;
use Dms\Package\Blog\Core\BlogCategory;

/**
 * @author Ali Hamza <ali@iddigital.com.au>
 */
class BlogOrm extends Orm
{
    /**
     * Defines the object mappers registered in the orm.
     *
     * @param OrmDefinition $orm
     *
     * @return void
     */
    protected function define(OrmDefinition $orm)
    {
        $orm->entities([
            BlogCategory::class => BlogCategoryMapper::class,
            BlogArticle::class => BlogArticleMapper::class
        ]);
    }
}