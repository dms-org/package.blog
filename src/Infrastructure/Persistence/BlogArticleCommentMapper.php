<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Infrastructure\Persistence;

use Dms\Common\Structure\DateTime\Persistence\DateTimeMapper;
use Dms\Common\Structure\Web\Persistence\EmailAddressMapper;
use Dms\Core\Persistence\Db\Mapping\Definition\MapperDefinition;
use Dms\Core\Persistence\Db\Mapping\EntityMapper;
use Dms\Core\Persistence\Db\Mapping\IOrm;
use Dms\Package\Blog\Domain\Entities\BlogArticle;
use Dms\Package\Blog\Domain\Entities\BlogArticleComment;
use Dms\Package\Blog\Domain\Services\Config\BlogConfiguration;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogArticleCommentMapper extends EntityMapper
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
        $map->type(BlogArticleComment::class);

        $map->toTable('article_comments');

        $map->idToPrimaryKey('id');

        $map->column($map->getOrm()->getNamespace() . 'article_id')->asUnsignedInt();
        $map->relation(BlogArticleComment::ARTICLE)
            ->to(BlogArticle::class)
            ->manyToOne()
            ->withBidirectionalRelation(BlogArticle::COMMENTS)
            ->withRelatedIdAs($map->getOrm()->getNamespace() . 'article_id');

        $map->property(BlogArticleComment::AUTHOR_NAME)->to('author_name')->asVarchar(255);

        $map->embedded(BlogArticleComment::AUTHOR_EMAIL)
            ->using(new EmailAddressMapper('author_email'));

        $map->property(BlogArticleComment::CONTENT)->to('content')->asText();

        $map->embedded(BlogArticleComment::POSTED_AT)
            ->using(new DateTimeMapper('posted_at'));
    }
}