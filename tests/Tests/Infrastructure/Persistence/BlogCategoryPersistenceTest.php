<?php declare(strict_types=1);

namespace Dms\Package\Blog\Tests\Infrastructure\Persistence;

use Dms\Package\Blog\Domain\Entities\BlogCategory;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogCategoryPersistenceTest extends PersistenceTest
{
    protected function getEntityClass(): string
    {
        return BlogCategory::class;
    }

    public function testSaveAndLoad()
    {
        $category = new BlogCategory(
            'test',
            'slug',
            true,
            $this->mockClock('2000-01-01 00:00:00')
        );

        $this->repo->save($category);

        $this->assertDatabaseDataSameAs([
            'categories' => [
                [
                    'id'         => 1,
                    'name'       => 'test',
                    'slug'       => 'slug',
                    'published'  => true,
                    'created_at' => '2000-01-01 00:00:00',
                    'updated_at' => '2000-01-01 00:00:00',
                    'metadata'   => '[]',
                ],
            ],
        ]);

        $this->assertEquals($category, $this->repo->get(1));
    }
}