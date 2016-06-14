<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Core;

use Dms\Common\Structure\DateTime\Date;
use Dms\Common\Structure\DateTime\DateTime;
use Dms\Common\Structure\FileSystem\Image;
use Dms\Common\Structure\Web\Html;
use Dms\Common\Structure\Web\Url;
use Dms\Core\Model\Object\ClassDefinition;
use Dms\Core\Model\Object\Entity;

/**
 * The blog article entity
 *
 * @author ali Hamza <ali@iddigital.com.au>
 */
class BlogArticle extends Entity {

    const TITLE = 'title';

    const SUB_TITLE = 'subTitle';

    const EXTRACT = 'extract';

    const FEATURED_IMAGE = 'featuredImage';

    const AUTHOR_NAME = 'authorName';

    const AUTHOR_ROLE = 'authorRole';

    const AUTHOR_LINK = 'authorLink';

    const DATE = 'date';

    const ARTICLE_CONTENT = 'articleContent';

    const ALLOW_SHARING = 'allowSharing';

    const ALLOW_COMMENTING = 'allowCommenting';

    const CATEGORY_ID = 'categoryId';

    const IS_ACTIVE = 'isActive';

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    /**
     * @var string
     */
    public $title;

    /**
     * @var string|null
     */
    public $subTitle;

    /**
     * @var string|null
     */
    public $extract;

    /**
     * @var Image|null
     */
    public $featuredImage;

    /**
     * @var string|null
     */
    public $authorName;

    /**
     * @var string|null
     */
    public $authorRole;

    /**
     * @var Url|null
     */
    public $authorLink;

    /**
     * @var Date|null
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
     * @var integer|null
     */
    public $categoryId;

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
     * Defines the structure of this entity.
     *
     * @param ClassDefinition $class
     */
    protected function defineEntity(ClassDefinition $class)
    {
        $class->property($this->title)->asString();

        $class->property($this->subTitle)->nullable()->asString();

        $class->property($this->extract)->nullable()->asString();

        $class->property($this->featuredImage)->nullable()->asObject(Image::class);

        $class->property($this->authorName)->nullable()->asString();

        $class->property($this->authorRole)->nullable()->asString();

        $class->property($this->authorLink)->nullable()->asObject(Url::class);

        $class->property($this->date)->nullable()->asObject(Date::class);

        $class->property($this->articleContent)->asObject(Html::class);

        $class->property($this->allowSharing)->asBool();

        $class->property($this->allowCommenting)->asBool();

        $class->property($this->categoryId)->nullable()->asInt();

        $class->property($this->isActive)->asBool();

        $class->property($this->createdAt)->asObject(DateTime::class);

        $class->property($this->updatedAt)->asObject(DateTime::class);
    }
}