<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Persistence;

use Dms\Core\Persistence\Db\Connection\IConnection;
use Dms\Core\Persistence\Db\Mapping\IOrm;
use Dms\Core\Persistence\DbRepository;
use Dms\Package\Blog\Core\BlogArticle;
use Dms\Package\Blog\Core\IBlogArticleRepository;

/**
 * @author Ali Hamza <ali@iddigital.com.au>
 */
class DbBlogArticleRepository extends DbRepository implements IBlogArticleRepository
{
    public function __construct(IConnection $connection, IOrm $orm)
    {
        parent::__construct($connection, $orm->getEntityMapper(BlogArticle::class));
    }
}