<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Services\Comment;

use Dms\Common\Structure\Web\EmailAddress;
use Dms\Core\Util\IClock;
use Dms\Package\Blog\Domain\Entities\BlogArticleComment;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogArticleRepository;

/**
 * @article Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogArticleCommentingService
{
    /**
     * @var IBlogArticleRepository
     */
    protected $blogArticleRepo;

    /**
     * @var IClock
     */
    protected $clock;

    /**
     * BlogArticleLoader constructor.
     *
     * @param IBlogArticleRepository $blogArticleRepo
     * @param IClock                 $clock
     */
    public function __construct(IBlogArticleRepository $blogArticleRepo, IClock $clock)
    {
        $this->blogArticleRepo = $blogArticleRepo;
        $this->clock           = $clock;
    }

    /**
     * @param int          $blogArticleId
     * @param string       $authorName
     * @param EmailAddress $authorEmail
     * @param string       $comment
     *
     * @return BlogArticleComment
     */
    public function postComment(int $blogArticleId, string $authorName, EmailAddress $authorEmail, string $comment): BlogArticleComment
    {
        $blogArticle = $this->blogArticleRepo->get($blogArticleId);

        $comment = $blogArticle->postComment($authorName, $authorEmail, $comment, $this->clock);

        $this->blogArticleRepo->save($blogArticle);

        return $comment;
    }
}