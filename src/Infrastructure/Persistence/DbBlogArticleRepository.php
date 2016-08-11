<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Infrastructure\Persistence;

use Dms\Core\Persistence\Db\Connection\IConnection;
use Dms\Core\Persistence\Db\Mapping\IOrm;
use Dms\Core\Persistence\DbRepository;
use Dms\Package\Blog\Domain\Entities\BlogArticle;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogArticleRepository;

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