<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Entities;

use Dms\Common\Structure\DateTime\Date;
use Dms\Common\Structure\DateTime\DateTime;
use Dms\Common\Structure\FileSystem\Image;
use Dms\Common\Structure\Web\Html;
use Dms\Common\Structure\Web\Url;
use Dms\Core\Model\EntityCollection;
use Dms\Core\Model\Object\ClassDefinition;
use Dms\Core\Model\Object\Entity;

/**
 * The blog author entity
 *
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogAuthor extends Entity
{
    const NAME = 'name';
    const SLUG = 'slug';
    const ROLE = 'role';
    const BIO = 'bio';
    const ARTICLES = 'articles';

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var string
     */
    public $role;

    /**
     * @var Html
     */
    public $bio;

    /**
     * @var EntityCollection|BlogArticle[]
     */
    public $articles;

    /**
     * BlogAuthor constructor.
     *
     * @param string $name
     * @param string $slug
     * @param string $role
     * @param Html   $bio
     */
    public function __construct(string $name, string $slug, string $role, Html $bio)
    {
        parent::__construct();
        $this->name = $name;
        $this->slug = $slug;
        $this->role = $role;
        $this->bio  = $bio;
        $this->articles = BlogArticle::collection();
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
        
        $class->property($this->role)->asString();
        
        $class->property($this->bio)->asObject(Html::class);
        
        $class->property($this->articles)->asType(BlogArticle::collectionType());
    }
}