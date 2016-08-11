<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Entities;

use Dms\Common\Structure\DateTime\Date;
use Dms\Common\Structure\DateTime\DateTime;
use Dms\Common\Structure\FileSystem\Image;
use Dms\Common\Structure\Web\Html;
use Dms\Core\Model\Object\ClassDefinition;
use Dms\Core\Model\Object\Entity;
use Dms\Core\Util\IClock;

/**
 * The blog article entity
 *
 * @author ali Hamza <ali@iddigital.com.au>
 */
class BlogArticle extends Entity
{
    const AUTHOR = 'author';
    const CATEGORY = 'category';
    const TITLE = 'title';
    const SUB_TITLE = 'subTitle';
    const SLUG = 'slug';
    const EXTRACT = 'extract';
    const FEATURED_IMAGE = 'featuredImage';
    const DATE = 'date';
    const ARTICLE_CONTENT = 'articleContent';
    const ALLOW_SHARING = 'allowSharing';
    const ALLOW_COMMENTING = 'allowCommenting';
    const IS_ACTIVE = 'isActive';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * @var BlogAuthor|null
     */
    public $author;

    /**
     * @var BlogCategory|null
     */
    public $category;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $subTitle;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var string
     */
    public $extract;

    /**
     * @var Image|null
     */
    public $featuredImage;

    /**
     * @var Date
     */
    public $date;

    /**
     * @var Html
     */
    public $articleContent;

    /**
     * @var boolean
     */
    public $allowSharing;

    /**
     * @var boolean
     */
    public $allowCommenting;

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
     * BlogArticle constructor.
     *
     * @param BlogAuthor|null   $author
     * @param BlogCategory|null $category
     * @param string            $title
     * @param null|string       $subTitle
     * @param null|string       $extract
     * @param string            $slug
     * @param Image|null        $featuredImage
     * @param Date|null         $date
     * @param Html              $articleContent
     * @param bool              $allowSharing
     * @param bool              $allowCommenting
     * @param bool              $isActive
     * @param IClock            $clock
     */
    public function __construct(
        BlogAuthor $author,
        BlogCategory $category,
        string $title,
        string $subTitle,
        string $extract,
        string $slug,
        Image $featuredImage,
        Date $date,
        Html $articleContent,
        bool $allowSharing,
        bool $allowCommenting,
        bool $isActive,
        IClock $clock
    ) {
        parent::__construct();
        $this->author          = $author;
        $this->category        = $category;
        $this->title           = $title;
        $this->subTitle        = $subTitle;
        $this->slug            = $slug;
        $this->extract         = $extract;
        $this->featuredImage   = $featuredImage;
        $this->date            = $date;
        $this->articleContent  = $articleContent;
        $this->allowSharing    = $allowSharing;
        $this->allowCommenting = $allowCommenting;
        $this->isActive        = $isActive;
        $this->createdAt       = new DateTime($clock->utcNow());
        $this->updatedAt       = new DateTime($clock->utcNow());
    }


    /**
     * Defines the structure of this entity.
     *
     * @param ClassDefinition $class
     */
    protected function defineEntity(ClassDefinition $class)
    {
        $class->property($this->author)->asObject(BlogAuthor::class);

        $class->property($this->category)->nullable()->asObject(BlogCategory::class);

        $class->property($this->title)->asString();

        $class->property($this->subTitle)->asString();
        
        $class->property($this->slug)->asString();

        $class->property($this->extract)->asString();

        $class->property($this->featuredImage)->nullable()->asObject(Image::class);

        $class->property($this->date)->asObject(Date::class);

        $class->property($this->articleContent)->asObject(Html::class);

        $class->property($this->allowSharing)->asBool();

        $class->property($this->allowCommenting)->asBool();

        $class->property($this->isActive)->asBool();

        $class->property($this->createdAt)->asObject(DateTime::class);

        $class->property($this->updatedAt)->asObject(DateTime::class);
    }
}