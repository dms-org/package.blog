<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Infrastructure\Persistence;

use Dms\Common\Structure\Web\Persistence\HtmlMapper;
use Dms\Core\Persistence\Db\Mapping\Definition\MapperDefinition;
use Dms\Core\Persistence\Db\Mapping\EntityMapper;
use Dms\Library\Metadata\Infrastructure\Persistence\MetadataMapper;
use Dms\Package\Blog\Domain\Entities\BlogArticle;
use Dms\Package\Blog\Domain\Entities\BlogAuthor;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogAuthorMapper extends EntityMapper
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
        $map->type(BlogAuthor::class);

        $map->toTable('authors');

        $map->idToPrimaryKey('id');

        $map->property(BlogAuthor::NAME)->to('name')->asVarchar(255);

        $map->property(BlogAuthor::ROLE)->to('role')->asVarchar(255);

        $map->property(BlogAuthor::SLUG)->to('slug')->unique()->asVarchar(255);

        $map->embedded(BlogAuthor::BIO)
            ->using(new HtmlMapper('bio'));

        $map->relation(BlogAuthor::ARTICLES)
            ->to(BlogArticle::class)
            ->toMany()
            ->identifying()
            ->withBidirectionalRelation(BlogArticle::AUTHOR)
            ->withParentIdAs($map->getOrm()->getNamespace() . 'author_id');

        MetadataMapper::mapMetadataToJsonColumn($map, 'metadata');
    }
}