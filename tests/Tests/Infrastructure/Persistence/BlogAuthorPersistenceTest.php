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
class BlogAuthorPersistenceTest extends PersistenceTest
{
    protected function getEntityClass() : string
    {
        return BlogAuthor::class;
    }

    public function testSaveAndLoad()
    {
        $author = new BlogAuthor('test', 'role', 'slug', new Html('bio'));

        $this->repo->save($author);

        $this->assertDatabaseDataSameAs([
            'authors'    => [
                [
                    'id'   => 1,
                    'name' => 'test',
                    'role' => 'slug',
                    'slug' => 'role',
                    'bio'  => 'bio',
                ],
            ],
        ]);

        $this->assertEquals($author, $this->repo->get(1));
    }
}