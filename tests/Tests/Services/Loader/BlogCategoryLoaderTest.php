<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Tests\Services\Loader;

use Dms\Common\Structure\DateTime\Date;
use Dms\Common\Structure\FileSystem\Image;
use Dms\Common\Structure\Web\Html;
use Dms\Common\Testing\CmsTestCase;
use Dms\Core\Model\EntityNotFoundException;
use Dms\Core\Persistence\ArrayRepository;
use Dms\Library\Testing\Mock\MockClock;
use Dms\Package\Blog\Domain\Entities\BlogArticle;
use Dms\Package\Blog\Domain\Entities\BlogAuthor;
use Dms\Package\Blog\Domain\Entities\BlogCategory;
use Dms\Package\Blog\Domain\Services\Loader\BlogArticleLoader;
use Dms\Package\Blog\Domain\Services\Loader\BlogAuthorLoader;
use Dms\Package\Blog\Domain\Services\Loader\BlogCategoryLoader;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogArticleRepository;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogAuthorRepository;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogCategoryRepository;

/**
 *
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogCategoryLoaderTest extends CmsTestCase
{
    /**
     * @var BlogCategoryLoader
     */
    protected $loader;

    public function setUp(): void
    {
        $categories = [
            new BlogCategory('1', 'some-slug', true, new MockClock('2000-01-01 00:00:00')),
            new BlogCategory('2', 'another-slug', true, new MockClock('2000-01-01 00:00:00')),
        ];

        $this->loader = new BlogCategoryLoader(new class(BlogCategory::collection($categories)) extends ArrayRepository implements IBlogCategoryRepository
        {
        });
    }

    public function testGetAll()
    {
        $this->assertCount(2, $this->loader->getAll());
    }

    public function testLoadBySlug()
    {
        $this->assertSame('1', $this->loader->loadFromSlug('some-slug')->name);
        $this->assertSame('2', $this->loader->loadFromSlug('another-slug')->name);
    }

    public function testInvalidSlug()
    {
        $this->expectException(EntityNotFoundException::class);

        $this->loader->loadFromSlug('invalid-slug');
    }
}