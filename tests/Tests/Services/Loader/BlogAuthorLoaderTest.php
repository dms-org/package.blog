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
use Dms\Package\Blog\Domain\Services\Persistence\IBlogArticleRepository;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogAuthorRepository;

/**
 *
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogAuthorLoaderTest extends CmsTestCase
{
    /**
     * @var BlogAuthorLoader
     */
    protected $loader;

    public function setUp(): void
    {
        $authors = [
            new BlogAuthor('1', 'some-slug', 'role', new Html('bio')),
            new BlogAuthor('2', 'another-slug', 'role', new Html('bio')),
        ];

        $this->loader = new BlogAuthorLoader(new class(BlogAuthor::collection($authors)) extends ArrayRepository implements IBlogAuthorRepository
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