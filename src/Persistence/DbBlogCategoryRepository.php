<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Persistence;

use Dms\Core\Persistence\Db\Connection\IConnection;
use Dms\Core\Persistence\Db\Mapping\IOrm;
use Dms\Core\Persistence\DbRepository;
use Dms\Package\Blog\Core\BlogCategory;
use Dms\Package\Blog\Core\IBlogCategoryRepository;

/**
 * @author Ali Hamza <ali@iddigital.com.au>
 */
class DbBlogCategoryRepository extends DbRepository implements IBlogCategoryRepository
{
    public function __construct(IConnection $connection, IOrm $orm)
    {
        parent::__construct($connection, $orm->getEntityMapper(BlogCategory::class));
    }
}