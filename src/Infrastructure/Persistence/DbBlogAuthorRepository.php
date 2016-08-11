<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Infrastructure\Persistence;

use Dms\Core\Persistence\Db\Connection\IConnection;
use Dms\Core\Persistence\Db\Mapping\IOrm;
use Dms\Core\Persistence\DbRepository;
use Dms\Package\Blog\Domain\Entities\BlogAuthor;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogAuthorRepository;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class DbBlogAuthorRepository extends DbRepository implements IBlogAuthorRepository
{
    public function __construct(IConnection $connection, IOrm $orm)
    {
        parent::__construct($connection, $orm->getEntityMapper(BlogAuthor::class));
    }
}