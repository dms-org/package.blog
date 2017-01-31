<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Services\Loader;

use Dms\Core\Model\Criteria\Criteria;
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
     * @return Criteria
     */
    public function criteria() : Criteria
    {
        return $this->blogArticleRepo->criteria()
                ->where(BlogArticle::PUBLISHED, '=', true)
                ->orderByDesc(BlogArticle::DATE);
    }

    /**
     * @param Criteria $criteria
     *
     * @return array|BlogArticle[]
     */
    public function load(Criteria $criteria) : array
    {
        return $this->blogArticleRepo->matching($criteria);
    }

    /**
     * @return BlogArticle[]
     */
    public function getAll() : array
    {
        return $this->load($this->criteria());
    }

    /**
     * @param Criteria $criteria
     *
     * @return int
     */
    public function getAmountOfArticles(Criteria $criteria = null) : int
    {
        return $this->blogArticleRepo->countMatching($criteria ?? $this->criteria());
    }

    /**
     * @return BlogArticle[]
     */
    public function getPage(int $page, int $itemsPerPage) : array
    {
        return $this->load(
            $this->criteria()
                ->skip(($page - 1) * $itemsPerPage)
                ->limit($itemsPerPage)
        );
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
                ->where(BlogArticle::PUBLISHED, '=', true)
        );

        if (!$categories) {
            throw new EntityNotFoundException(BlogArticle::class, $slug, BlogArticle::SLUG);
        }

        return reset($categories);
    }
}