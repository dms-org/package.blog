<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Tests\Infrastructure\Persistence;

use Dms\Common\Structure\DateTime\Date;
use Dms\Common\Structure\FileSystem\Image;
use Dms\Common\Structure\Web\Html;
use Dms\Package\Blog\Domain\Entities\BlogArticle;
use Dms\Package\Blog\Domain\Entities\BlogAuthor;
use Dms\Package\Blog\Domain\Entities\BlogCategory;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogArticlePersistenceTest extends PersistenceTest
{
    protected function getEntityClass() : string
    {
        return BlogArticle::class;
    }

    public function testSaveAndLoad()
    {
        $article              = new BlogArticle(
            $author = new BlogAuthor('test', 'role', 'slug', new Html('bio')),
            $category = new BlogCategory('test', 'slug', true, $this->mockClock('2000-01-01 00:00:00')),
            'title',
            'sub-title',
            'extract',
            'slug',
            new Image(__FILE__),
            new Date(2000, 01, 01),
            new Html('content'),
            true,
            true,
            true,
            $this->mockClock('2000-01-01 00:00:00')
        );
        $author->articles[]   = $article;
        $category->articles[] = $article;

        $this->repo->save($article);

        $this->assertDatabaseDataSameAs([
            'articles'   => [
                [
                    'id'                       => 1,
                    'author_id'                => 1,
                    'category_id'              => 1,
                    'title'                    => 'title',
                    'sub_title'                => 'sub-title',
                    'slug'                     => 'slug',
                    'extract'                  => 'extract',
                    'featured_image'           => 'Infrastructure' . DIRECTORY_SEPARATOR . 'Persistence' . DIRECTORY_SEPARATOR . basename(__FILE__),
                    'featured_image_file_name' => null,
                    'date'                     => '2000-01-01',
                    'article_content'          => 'content',
                    'allow_sharing'            => true,
                    'allow_commenting'         => true,
                    'is_active'                => true,
                    'created_at'               => '2000-01-01 00:00:00',
                    'updated_at'               => '2000-01-01 00:00:00',
                ],
            ],
            'authors'    => [
                [
                    'id'   => 1,
                    'name' => 'test',
                    'role' => 'slug',
                    'slug' => 'role',
                    'bio'  => 'bio',
                ],
            ],
            'categories' => [
                [
                    'id'         => 1,
                    'name'       => 'test',
                    'slug'       => 'slug',
                    'is_active'  => true,
                    'created_at' => '2000-01-01 00:00:00',
                    'updated_at' => '2000-01-01 00:00:00',
                ],
            ],
        ]);

        $this->assertEquals($article, $this->repo->get(1));
    }
}