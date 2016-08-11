<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Services\Loader;

use Dms\Core\Model\EntityNotFoundException;
use Dms\Package\Blog\Domain\Entities\BlogAuthor;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogAuthorRepository;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogAuthorLoader
{
    /**
     * @var IBlogAuthorRepository
     */
    protected $blogAuthorRepo;

    /**
     * BlogAuthorLoader constructor.
     *
     * @param IBlogAuthorRepository $blogAuthorRepo
     */
    public function __construct(IBlogAuthorRepository $blogAuthorRepo)
    {
        $this->blogAuthorRepo = $blogAuthorRepo;
    }

    /**
     * @return BlogAuthor[]
     */
    public function getAll() : array
    {
        return $this->blogAuthorRepo->matching(
            $this->blogAuthorRepo->criteria()
                ->orderByAsc(BlogAuthor::NAME)
        );
    }

    /**
     * @param string $slug
     *
     * @return BlogAuthor
     * @throws EntityNotFoundException
     */
    public function loadFromSlug(string $slug) : BlogAuthor
    {
        $categories = $this->blogAuthorRepo->matching(
            $this->blogAuthorRepo->criteria()
                ->where(BlogAuthor::SLUG, '=', $slug)
        );

        if (!$categories) {
            throw new EntityNotFoundException(BlogAuthor::class, $slug, BlogAuthor::SLUG);
        }

        return reset($categories);
    }
}