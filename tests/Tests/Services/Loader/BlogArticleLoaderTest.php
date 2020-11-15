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
use Dms\Package\Blog\Domain\Services\Persistence\IBlogArticleRepository;

/**
 *
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogArticleLoaderTest extends CmsTestCase
{
    /**
     * @var BlogArticleLoader
     */
    protected $loader;

    public function setUp(): void
    {
        $articles = [
            new BlogArticle(
                $this->createMock(BlogAuthor::class),
                $this->createMock(BlogCategory::class),
                'title-1',
                'sub-title',
                'extract',
                'some-slug',
                $this->createMock(Image::class),
                new Date(2000, 01, 01),
                new Html('content'),
                true,
                true,
                true,
                new MockClock('2000-01-01 00:00:00')
            ),
            new BlogArticle(
                $this->createMock(BlogAuthor::class),
                $this->createMock(BlogCategory::class),
                'title-2',
                'sub-title',
                'extract',
                'another-slug',
                $this->createMock(Image::class),
                new Date(2000, 01, 01),
                new Html('content'),
                true,
                true,
                true,
                new MockClock('2000-01-01 00:00:00')
            ),
        ];

        $this->loader = new BlogArticleLoader(new class(BlogArticle::collection($articles)) extends ArrayRepository implements IBlogArticleRepository
        {
        });
    }

    public function testGetAll()
    {
        $this->assertCount(2, $this->loader->getAll());
    }

    public function testLoadBySlug()
    {
        $this->assertSame('title-1', $this->loader->loadFromSlug('some-slug')->title);
        $this->assertSame('title-2', $this->loader->loadFromSlug('another-slug')->title);
    }

    public function testInvalidSlug()
    {
        $this->expectException(EntityNotFoundException::class);

        $this->loader->loadFromSlug('invalid-slug');
    }
}