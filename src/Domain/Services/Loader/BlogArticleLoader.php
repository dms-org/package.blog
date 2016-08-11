<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Services\Loader;

use Dms\Core\Model\EntityNotFoundException;
use Dms\Package\Blog\Domain\Entities\BlogArticle;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogArticleRepository;

/**
 * @article Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogArticleLoader
{
    /**
     * @var IBlogArticleRepository
     */
    protected $blogArticleRepo;

    /**
     * BlogArticleLoader constructor.
     *
     * @param IBlogArticleRepository $blogArticleRepo
     */
    public function __construct(IBlogArticleRepository $blogArticleRepo)
    {
        $this->blogArticleRepo = $blogArticleRepo;
    }
    
    /**
     * @return BlogArticle[]
     */
    public function getAll() : array
    {
        return $this->blogArticleRepo->getAll();
    }

    /**
     * @param string $slug
     *
     * @return BlogArticle
     * @throws EntityNotFoundException
     */
    public function loadFromSlug(string $slug) : BlogArticle
    {
        $categories = $this->blogArticleRepo->matching(
            $this->blogArticleRepo->criteria()
                ->where(BlogArticle::SLUG, '=', $slug)
                ->where(BlogArticle::IS_ACTIVE, '=', true)
        );

        if (!$categories) {
            throw new EntityNotFoundException(BlogArticle::class, $slug, BlogArticle::SLUG);
        }

        return reset($categories);
    }
}