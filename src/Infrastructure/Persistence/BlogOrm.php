<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Infrastructure\Persistence;

use Dms\Core\Ioc\IIocContainer;
use Dms\Core\Persistence\Db\Mapping\Definition\Orm\OrmDefinition;
use Dms\Core\Persistence\Db\Mapping\Orm;
use Dms\Package\Blog\Domain\Entities\BlogArticle;
use Dms\Package\Blog\Domain\Entities\BlogAuthor;
use Dms\Package\Blog\Domain\Entities\BlogCategory;

/**
 * @author Ali Hamza <ali@iddigital.com.au>
 */
class BlogOrm extends Orm
{
    /**
     * BlogOrm constructor.
     *
     * @param IIocContainer $iocContainer
     */
    public function __construct(IIocContainer $iocContainer)
    {
        parent::__construct($iocContainer);
    }

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
            BlogAuthor::class   => BlogAuthorMapper::class,
            BlogArticle::class  => BlogArticleMapper::class,
        ]);
    }
}