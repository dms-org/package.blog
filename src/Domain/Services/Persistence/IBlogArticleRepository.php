<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Services\Persistence;

use Dms\Core\Exception;
use Dms\Core\Model\ICriteria;
use Dms\Core\Model\ISpecification;
use Dms\Core\Persistence\IRepository;
use Dms\Package\Blog\Domain\Entities\BlogArticle;

/**
 * @author Ali Hamza <ali@iddigital.com.au>
 */
interface IBlogArticleRepository extends IRepository
{
    /**
     * {@inheritDoc}
     *
     * @return BlogArticle[]
     */
    public function getAll() : array;

    /**
     * {@inheritDoc}
     *
     * @return BlogArticle
     */
    public function get($id);

    /**
     * {@inheritDoc}
     *
     * @return BlogArticle[]
     */
    public function getAllById(array $ids) : array;

    /**
     * {@inheritDoc}
     *
     * @return BlogArticle|null
     */
    public function tryGet($id);

    /**
     * {@inheritDoc}
     *
     * @return BlogArticle[]
     */
    public function tryGetAll(array $ids) : array;

    /**
     * {@inheritDoc}
     *
     * @return BlogArticle[]
     */
    public function matching(ICriteria $criteria) : array;

    /**
     * {@inheritDoc}
     *
     * @return BlogArticle[]
     */
    public function satisfying(ISpecification $specification) : array;
}
