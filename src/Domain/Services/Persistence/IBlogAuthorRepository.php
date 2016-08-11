<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Services\Persistence;

use Dms\Core\Exception;
use Dms\Core\Model\ICriteria;
use Dms\Core\Model\ISpecification;
use Dms\Core\Persistence\IRepository;
use Dms\Package\Blog\Domain\Entities\BlogAuthor;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
interface IBlogAuthorRepository extends IRepository
{
    /**
     * {@inheritDoc}
     *
     * @return BlogAuthor[]
     */
    public function getAll() : array;

    /**
     * {@inheritDoc}
     *
     * @return BlogAuthor
     */
    public function get($id);

    /**
     * {@inheritDoc}
     *
     * @return BlogAuthor[]
     */
    public function getAllById(array $ids) : array;

    /**
     * {@inheritDoc}
     *
     * @return BlogAuthor|null
     */
    public function tryGet($id);

    /**
     * {@inheritDoc}
     *
     * @return BlogAuthor[]
     */
    public function tryGetAll(array $ids) : array;

    /**
     * {@inheritDoc}
     *
     * @return BlogAuthor[]
     */
    public function matching(ICriteria $criteria) : array;

    /**
     * {@inheritDoc}
     *
     * @return BlogAuthor[]
     */
    public function satisfying(ISpecification $specification) : array;
}
