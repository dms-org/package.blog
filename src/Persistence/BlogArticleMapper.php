<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Persistence;

use Dms\Common\Structure\DateTime\Persistence\DateMapper;
use Dms\Common\Structure\DateTime\Persistence\DateTimeMapper;
use Dms\Common\Structure\FileSystem\Persistence\ImageMapper;
use Dms\Common\Structure\Web\Persistence\HtmlMapper;
use Dms\Common\Structure\Web\Persistence\UrlMapper;
use Dms\Core\Persistence\Db\Mapping\Definition\MapperDefinition;
use Dms\Core\Persistence\Db\Mapping\EntityMapper;
use Dms\Package\Blog\Core\BlogArticle;
use Dms\Package\Blog\Core\BlogCategory;

/**
 * @author Ali Hamza <ali@iddigital.com.au>
 */
class BlogArticleMapper extends EntityMapper
{
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

        $map->toTable('blog_articles');

        $map->idToPrimaryKey('id');

        $map->property(BlogArticle::TITLE)->to('name')->asVarchar(255);

        $map->property(BlogArticle::SUB_TITLE)->to('sub_title')->nullable()->asVarchar(255);

        $map->property(BlogArticle::EXTRACT)->to('extract')->nullable()->asText();

        $map->embedded(BlogArticle::FEATURED_IMAGE)->withIssetColumn('featured_image')->using(new ImageMapper('featured_image', 'featured_image_file_name', public_path()));

        $map->property(BlogArticle::AUTHOR_NAME)->to('author_name')->nullable()->asText();

        $map->property(BlogArticle::AUTHOR_ROLE)->to('author_role')->nullable()->asText();

        $map->embedded(BlogArticle::AUTHOR_LINK)->withIssetColumn('author_link')->using(new UrlMapper('author_link'));

        $map->embedded(BlogArticle::DATE)->withIssetColumn('date')->using(new DateMapper('date'));

        $map->embedded(BlogArticle::ARTICLE_CONTENT)->withIssetColumn('article_content')->using(new HtmlMapper('article_content'));

        $map->property(BlogArticle::ALLOW_SHARING)->to('allow_sharing')->asBool();

        $map->property(BlogArticle::ALLOW_COMMENTING)->to('allow_commenting')->asBool();

        $map->column('blog_category_id')->nullable()->asUnsignedInt();
        $map->relation(BlogArticle::CATEGORY_ID)->to(BlogCategory::class)->manyToOneId()->onDeleteSetNull()->withRelatedIdAs('blog_category_id');

        $map->property(BlogArticle::IS_ACTIVE)->to('is_active')->asBool();

        $map->embedded(BlogArticle::CREATED_AT)->using(new DateTimeMapper('created_at'));

        $map->embedded(BlogArticle::UPDATED_AT)->using(new DateTimeMapper('updated_at'));
    }
}