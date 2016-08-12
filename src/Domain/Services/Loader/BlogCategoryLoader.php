<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Services\Loader;

use Dms\Core\Model\EntityNotFoundException;
use Dms\Package\Blog\Domain\Entities\BlogCategory;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogCategoryRepository;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogCategoryLoader
{
    /**
     * @var IBlogCategoryRepository
     */
    protected $blogCategoryRepo;

    /**
     * BlogCategoryLoader constructor.
     *
     * @param IBlogCategoryRepository $blogCategoryRepo
     */
    public function __construct(IBlogCategoryRepository $blogCategoryRepo)
    {
        $this->blogCategoryRepo = $blogCategoryRepo;
    }

    /**
     * @return BlogCategory[]
     */
    public function getAll() : array
    {
        return $this->blogCategoryRepo->matching(
            $this->blogCategoryRepo->criteria()
                ->orderByAsc(BlogCategory::NAME)
        );
    }

    /**
     * @param string $slug
     *
     * @return BlogCategory
     * @throws EntityNotFoundException
     */
    public function loadFromSlug(string $slug) : BlogCategory
    {
        $categories = $this->blogCategoryRepo->matching(
            $this->blogCategoryRepo->criteria()
                ->where(BlogCategory::SLUG, '=', $slug)
                ->where(BlogCategory::IS_ACTIVE, '=', true)
        );

        if (!$categories) {
            throw new EntityNotFoundException(BlogCategory::class, $slug, BlogCategory::SLUG);
        }

        return reset($categories);
    }
}