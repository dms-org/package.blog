<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Infrastructure\Persistence;

use Dms\Common\Structure\DateTime\Persistence\DateMapper;
use Dms\Common\Structure\DateTime\Persistence\DateTimeMapper;
use Dms\Common\Structure\FileSystem\Persistence\ImageMapper;
use Dms\Common\Structure\Web\Persistence\HtmlMapper;
use Dms\Core\Persistence\Db\Mapping\Definition\MapperDefinition;
use Dms\Core\Persistence\Db\Mapping\EntityMapper;
use Dms\Core\Persistence\Db\Mapping\IOrm;
use Dms\Package\Blog\Domain\Entities\BlogArticle;
use Dms\Package\Blog\Domain\Entities\BlogAuthor;
use Dms\Package\Blog\Domain\Entities\BlogCategory;
use Dms\Package\Blog\Domain\Services\Config\BlogConfiguration;

/**
 * @author Ali Hamza <ali@iddigital.com.au>
 */
class BlogArticleMapper extends EntityMapper
{
    /**
     * @var BlogConfiguration
     */
    private $blogConfiguration;

    public function __construct(IOrm $orm, BlogConfiguration $blogConfiguration)
    {
        $this->blogConfiguration = $blogConfiguration;
        parent::__construct($orm);
    }

    /**
     * Defines the entity mapper
     *
     * @param MapperDefinition $map
     *
     * @return void
     */
    protected function define(MapperDefinition $map)
    {
        $map->type(BlogArticle::class);

        $map->toTable('articles');

        $map->idToPrimaryKey('id');

        $map->column($map->getOrm()->getNamespace() . 'author_id')->asUnsignedInt();
        $map->relation(BlogArticle::AUTHOR)
            ->to(BlogAuthor::class)
            ->manyToOne()
            ->withBidirectionalRelation(BlogAuthor::ARTICLES)
            ->withRelatedIdAs($map->getOrm()->getNamespace() . 'author_id');

        $map->column($map->getOrm()->getNamespace() . 'category_id')->nullable()->asUnsignedInt();
        $map->relation(BlogArticle::CATEGORY)
            ->to(BlogCategory::class)
            ->manyToOne()
            ->withBidirectionalRelation(BlogCategory::ARTICLES)
            ->withRelatedIdAs($map->getOrm()->getNamespace() . 'category_id');

        $map->property(BlogArticle::TITLE)->to('title')->asVarchar(255);

        $map->property(BlogArticle::SUB_TITLE)->to('sub_title')->asVarchar(255);

        $map->property(BlogArticle::SLUG)->to('slug')->unique()->asVarchar(255);

        $map->property(BlogArticle::EXTRACT)->to('extract')->asText();

        $map->embedded(BlogArticle::FEATURED_IMAGE)
            ->withIssetColumn('featured_image')
            ->using(new ImageMapper('featured_image', 'featured_image_file_name', $this->blogConfiguration->getFeaturedImagePath()));

        $map->embedded(BlogArticle::DATE)->using(new DateMapper('date'));

        $map->embedded(BlogArticle::ARTICLE_CONTENT)->withIssetColumn('article_content')->using(new HtmlMapper('article_content'));

        $map->property(BlogArticle::ALLOW_SHARING)->to('allow_sharing')->asBool();

        $map->property(BlogArticle::ALLOW_COMMENTING)->to('allow_commenting')->asBool();

        $map->property(BlogArticle::PUBLISHED)->to('published')->asBool();

        $map->embedded(BlogArticle::CREATED_AT)->using(new DateTimeMapper('created_at'));

        $map->embedded(BlogArticle::UPDATED_AT)->using(new DateTimeMapper('updated_at'));
    }
}