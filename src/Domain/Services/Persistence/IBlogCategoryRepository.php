<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Services\Persistence;

use Dms\Core\Exception;
use Dms\Core\Model\ICriteria;
use Dms\Core\Model\ISpecification;
use Dms\Core\Persistence\IRepository;
use Dms\Package\Blog\Domain\Entities\BlogCategory;

/**
 * @author Ali Hamza <ali@iddigital.com.au>
 */
interface IBlogCategoryRepository extends IRepository
{
    /**
     * {@inheritDoc}
     *
     * @return BlogCategory[]
     */
    public function getAll() : array;

    /**
     * {@inheritDoc}
     *
     * @return BlogCategory
     */
    public function get($id);

    /**
     * {@inheritDoc}
     *
     * @return BlogCategory[]
     */
    public function getAllById(array $ids) : array;

    /**
     * {@inheritDoc}
     *
     * @return BlogCategory|null
     */
    public function tryGet($id);

    /**
     * {@inheritDoc}
     *
     * @return BlogCategory[]
     */
    public function tryGetAll(array $ids) : array;

    /**
     * {@inheritDoc}
     *
     * @return BlogCategory[]
     */
    public function matching(ICriteria $criteria) : array;

    /**
     * {@inheritDoc}
     *
     * @return BlogCategory[]
     */
    public function satisfying(ISpecification $specification) : array;
}
