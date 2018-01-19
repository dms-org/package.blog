<?php declare(strict_types=1);

namespace Dms\Package\Blog\Domain\Entities;

use Dms\Common\Structure\DateTime\Date;
use Dms\Common\Structure\DateTime\DateTime;
use Dms\Common\Structure\FileSystem\Image;
use Dms\Common\Structure\Web\EmailAddress;
use Dms\Common\Structure\Web\Html;
use Dms\Core\Exception\InvalidOperationException;
use Dms\Core\Model\EntityCollection;
use Dms\Core\Model\Object\ClassDefinition;
use Dms\Core\Model\Object\Entity;
use Dms\Core\Util\IClock;
use Dms\Library\Metadata\Domain\IHasMetadata;
use Dms\Library\Metadata\Domain\MetadataTrait;
use Dms\Library\Metadata\Domain\ObjectMetadata;

/**
 * The blog article entity
 *
 * @author ali Hamza <ali@iddigital.com.au>
 */
class BlogArticle extends Entity implements IHasMetadata
{
    use MetadataTrait;

    const AUTHOR = 'author';
    const CATEGORY = 'category';
    const TITLE = 'title';
    const SUB_TITLE = 'subTitle';
    const SLUG = 'slug';
    const EXTRACT = 'extract';
    const FEATURED_IMAGE = 'featuredImage';
    const DATE = 'date';
    const ARTICLE_CONTENT = 'articleContent';
    const COMMENTS = 'comments';
    const ALLOW_SHARING = 'allowSharing';
    const ALLOW_COMMENTING = 'allowCommenting';
    const PUBLISHED = 'published';
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
     * @var EntityCollection|BlogArticleComment
     */
    public $comments;

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
     * @param bool              $published
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
        bool $published,
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
        $this->published       = $published;
        $this->createdAt       = new DateTime($clock->utcNow());
        $this->updatedAt       = new DateTime($clock->utcNow());
        $this->comments        = BlogArticleComment::collection();
        $this->metadata        = new ObjectMetadata();
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

        $class->property($this->comments)->asType(BlogArticleComment::collectionType());

        $class->property($this->allowSharing)->asBool();

        $class->property($this->allowCommenting)->asBool();

        $class->property($this->published)->asBool();

        $class->property($this->createdAt)->asObject(DateTime::class);

        $class->property($this->updatedAt)->asObject(DateTime::class);

        $this->defineMetadata($class);
    }

    /**
     * @param string       $authorName
     * @param EmailAddress $authorEmail
     * @param string       $content
     * @param IClock       $clock
     *
     * @return BlogArticleComment
     */
    public function postComment(string $authorName, EmailAddress $authorEmail, string $content, IClock $clock): BlogArticleComment
    {
        InvalidOperationException::verify($this->published, 'This blog article is not published');
        InvalidOperationException::verify($this->allowCommenting, 'This blog article does not allow comments');

        $comment = new BlogArticleComment(
            $this,
            $authorName,
            $authorEmail,
            $content,
            $clock
        );

        $this->comments[] = $comment;

        return $comment;
    }
}