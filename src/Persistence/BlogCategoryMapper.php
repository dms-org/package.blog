<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Persistence;

use Dms\Common\Structure\DateTime\Persistence\DateTimeMapper;
use Dms\Core\Persistence\Db\Mapping\Definition\MapperDefinition;
use Dms\Core\Persistence\Db\Mapping\EntityMapper;
use Dms\Package\Blog\Core\BlogCategory;

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

        $map->toTable('blog_categories');

        $map->idToPrimaryKey('id');

        $map->property(BlogCategory::NAME)->to('name')->asVarchar(255);

        $map->property(BlogCategory::IS_ACTIVE)->to('is_active')->asBool();

        $map->embedded(BlogCategory::CREATED_AT)->using(new DateTimeMapper('created_at'));

        $map->embedded(BlogCategory::UPDATED_AT)->using(new DateTimeMapper('updated_at'));
    }
}