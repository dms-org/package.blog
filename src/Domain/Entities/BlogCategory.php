<?php declare(strict_types=1);

namespace Dms\Package\Blog\Domain\Entities;

use Dms\Common\Structure\DateTime\DateTime;
use Dms\Core\Model\EntityCollection;
use Dms\Core\Model\Object\ClassDefinition;
use Dms\Core\Model\Object\Entity;
use Dms\Core\Util\IClock;
use Dms\Library\Metadata\Domain\MetadataTrait;
use Dms\Library\Metadata\Domain\ObjectMetadata;

/**
 * The blog category entity
 *
 * @author ali Hamza <ali@iddigital.com.au>
 */
class BlogCategory extends Entity
{
    use MetadataTrait;

    const NAME = 'name';
    const SLUG = 'slug';
    const ARTICLES = 'articles';
    const PUBLISHED = 'published';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const METADATA = 'metadata';

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var EntityCollection|BlogArticle[]
     */
    public $articles;

    /**
     * @var boolean
     */
    public $published;

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
     *
     * @param string $name
     * @param string $slug
     * @param bool   $published
     * @param IClock $clock
     */
    public function __construct(string $name, string $slug, bool $published, IClock $clock)
    {
        parent::__construct();

        $this->name      = $name;
        $this->slug      = $slug;
        $this->published = $published;
        $this->createdAt = new DateTime($clock->utcNow());
        $this->updatedAt = new DateTime($clock->utcNow());
        $this->articles  = BlogArticle::collection();
        $this->metadata  = new ObjectMetadata();
    }

    /**
     * Defines the structure of this entity.
     *
     * @param ClassDefinition $class
     */
    protected function defineEntity(ClassDefinition $class)
    {
        $class->property($this->name)->asString();

        $class->property($this->slug)->asString();

        $class->property($this->articles)->asType(BlogArticle::collectionType());

        $class->property($this->published)->asBool();

        $class->property($this->createdAt)->asObject(DateTime::class);

        $class->property($this->updatedAt)->asObject(DateTime::class);

        $this->defineMetadata($class);
    }
}