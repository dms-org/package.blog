<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Infrastructure\Persistence;

use Dms\Core\Persistence\Db\Connection\IConnection;
use Dms\Core\Persistence\Db\Mapping\IOrm;
use Dms\Core\Persistence\DbRepository;
use Dms\Package\Blog\Domain\Entities\BlogArticleComment;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogArticleCommentRepository;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class DbBlogArticleCommentRepository extends DbRepository implements IBlogArticleCommentRepository
{
    public function __construct(IConnection $connection, IOrm $orm)
    {
        parent::__construct($connection, $orm->getEntityMapper(BlogArticleComment::class));
    }
}