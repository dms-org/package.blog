<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Infrastructure\Persistence;

use Dms\Common\Structure\DateTime\Persistence\DateTimeMapper;
use Dms\Core\Persistence\Db\Mapping\Definition\MapperDefinition;
use Dms\Core\Persistence\Db\Mapping\EntityMapper;
use Dms\Library\Metadata\Infrastructure\Persistence\MetadataMapper;
use Dms\Package\Blog\Domain\Entities\BlogArticle;
use Dms\Package\Blog\Domain\Entities\BlogCategory;

/**
 * @author Ali Hamza <ali@iddigital.com.au>
 */
class BlogCategoryMapper extends EntityMapper
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
        $map->type(BlogCategory::class);

        $map->toTable('categories');

        $map->idToPrimaryKey('id');

        $map->property(BlogCategory::NAME)->to('name')->asVarchar(255);

        $map->property(BlogCategory::SLUG)->to('slug')->unique()->asVarchar(255);

        $map->property(BlogCategory::PUBLISHED)->to('published')->asBool();

        $map->embedded(BlogCategory::CREATED_AT)->using(new DateTimeMapper('created_at'));

        $map->embedded(BlogCategory::UPDATED_AT)->using(new DateTimeMapper('updated_at'));

        $map->relation(BlogCategory::ARTICLES)
            ->to(BlogArticle::class)
            ->toMany()
            ->withBidirectionalRelation(BlogArticle::AUTHOR)
            ->withParentIdAs($map->getOrm()->getNamespace() . 'category_id');

        MetadataMapper::mapMetadataToJsonColumn($map, 'metadata');
    }
}