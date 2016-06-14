<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Core;

use Dms\Common\Structure\DateTime\DateTime;
use Dms\Core\Model\Object\ClassDefinition;
use Dms\Core\Model\Object\Entity;

/**
 * The blog category entity
 *
 * @author ali Hamza <ali@iddigital.com.au>
 */
class BlogCategory extends Entity
{
    const NAME = 'name';
    const IS_ACTIVE = 'isActive';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * @var string
     */
    public $name;

    /**
     * @var boolean
     */
    public $isActive;

    /**
     * @var DateTime
     */
    public $createdAt;

    /**
     * @var DateTime
     */
    public $updatedAt;

    /**
     * BlogCategory constructor.
     * @param string $name
     * @param bool $isActive
     * @param DateTime $createdAt
     * @param DateTime $updatedAt
     */
    public function __construct($name, $isActive, DateTime $createdAt, DateTime $updatedAt)
    {
        parent::__construct();

        $this->name = $name;
        $this->isActive = $isActive;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * Defines the structure of this entity.
     *
     * @param ClassDefinition $class
     */
    protected function defineEntity(ClassDefinition $class)
    {
        $class->property($this->name)->asString();

        $class->property($this->isActive)->asBool();

        $class->property($this->createdAt)->asObject(DateTime::class);

        $class->property($this->updatedAt)->asObject(DateTime::class);
    }
}