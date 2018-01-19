<?php declare(strict_types=1);

namespace Dms\Package\Blog\Tests\Infrastructure\Persistence;

use Dms\Common\Structure\Web\Html;
use Dms\Package\Blog\Domain\Entities\BlogAuthor;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogAuthorPersistenceTest extends PersistenceTest
{
    protected function getEntityClass(): string
    {
        return BlogAuthor::class;
    }

    public function testSaveAndLoad()
    {
        $author = new BlogAuthor('test', 'role', 'slug', new Html('bio'));

        $this->repo->save($author);

        $this->assertDatabaseDataSameAs([
            'authors' => [
                [
                    'id'       => 1,
                    'name'     => 'test',
                    'role'     => 'slug',
                    'slug'     => 'role',
                    'bio'      => 'bio',
                    'metadata' => '[]',
                ],
            ],
        ]);

        $this->assertEquals($author, $this->repo->get(1));
    }
}