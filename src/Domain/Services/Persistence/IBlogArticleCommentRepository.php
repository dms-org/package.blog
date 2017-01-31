<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Services\Persistence;

use Dms\Core\Model\ICriteria;
use Dms\Core\Model\ISpecification;
use Dms\Core\Persistence\IRepository;
use Dms\Package\Blog\Domain\Entities\BlogArticleComment;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
interface IBlogArticleCommentRepository extends IRepository
{
    /**
     * {@inheritDoc}
     *
     * @return BlogArticleComment[]
     */
    public function getAll(): array;

    /**
     * {@inheritDoc}
     *
     * @return BlogArticleComment
     */
    public function get($id);

    /**
     * {@inheritDoc}
     *
     * @return BlogArticleComment[]
     */
    public function getAllById(array $ids): array;

    /**
     * {@inheritDoc}
     *
     * @return BlogArticleComment|null
     */
    public function tryGet($id);

    /**
     * {@inheritDoc}
     *
     * @return BlogArticleComment[]
     */
    public function tryGetAll(array $ids): array;

    /**
     * {@inheritDoc}
     *
     * @return BlogArticleComment[]
     */
    public function matching(ICriteria $criteria): array;

    /**
     * {@inheritDoc}
     *
     * @return BlogArticleComment[]
     */
    public function satisfying(ISpecification $specification): array;
}
