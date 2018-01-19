<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Entities;

use Dms\Common\Structure\DateTime\DateTime;
use Dms\Common\Structure\Web\EmailAddress;
use Dms\Core\Model\Object\ClassDefinition;
use Dms\Core\Model\Object\Entity;
use Dms\Core\Util\IClock;
use Dms\Library\Metadata\Domain\MetadataTrait;
use Dms\Library\Metadata\Domain\ObjectMetadata;

/**
 * The blog article comment entity
 *
 * @author ali Hamza <ali@iddigital.com.au>
 */
class BlogArticleComment extends Entity
{
    use MetadataTrait;

    const ARTICLE = 'article';
    const AUTHOR_NAME = 'authorName';
    const AUTHOR_EMAIL = 'authorEmail';
    const CONTENT = 'content';
    const POSTED_AT = 'postedAt';
    const METADATA = 'metadata';

    /**
     * @var BlogArticle
     */
    public $article;

    /**
     * @var string
     */
    public $authorName;

    /**
     * @var EmailAddress
     */
    public $authorEmail;

    /**
     * @var string
     */
    public $content;

    /**
     * @var DateTime
     */
    public $postedAt;

    /**
     * BlogArticleComment constructor.
     *
     * @param BlogArticle  $article
     * @param string       $authorName
     * @param EmailAddress $authorEmail
     * @param string       $content
     * @param IClock       $clock
     */
    public function __construct(BlogArticle $article, string $authorName, EmailAddress $authorEmail, string $content, IClock $clock)
    {
        parent::__construct();
        $this->article     = $article;
        $this->authorName  = $authorName;
        $this->authorEmail = $authorEmail;
        $this->content     = $content;
        $this->postedAt    = new DateTime($clock->utcNow());
        $this->metadata        = new ObjectMetadata();
    }


    /**
     * Defines the structure of this entity.
     *
     * @param ClassDefinition $class
     */
    protected function defineEntity(ClassDefinition $class)
    {
        $class->property($this->article)->asObject(BlogArticle::class);

        $class->property($this->authorName)->asString();

        $class->property($this->authorEmail)->asObject(EmailAddress::class);

        $class->property($this->content)->asString();

        $class->property($this->postedAt)->asObject(DateTime::class);

        $this->defineMetadata($class);
    }
}