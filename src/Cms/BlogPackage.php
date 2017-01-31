<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Cms;

use Dms\Core\ICms;
use Dms\Core\Ioc\IIocContainer;
use Dms\Core\Package\Definition\PackageDefinition;
use Dms\Core\Package\Package;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogArticleCommentRepository;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogArticleRepository;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogAuthorRepository;
use Dms\Package\Blog\Domain\Services\Persistence\IBlogCategoryRepository;
use Dms\Package\Blog\Infrastructure\Persistence\DbBlogArticleCommentRepository;
use Dms\Package\Blog\Infrastructure\Persistence\DbBlogArticleRepository;
use Dms\Package\Blog\Infrastructure\Persistence\DbBlogAuthorRepository;
use Dms\Package\Blog\Infrastructure\Persistence\DbBlogCategoryRepository;

/**
 * @author Ali Hamza <ali@iddigital.com.au>
 */
class BlogPackage extends Package
{
    /**
     * @param ICms $cms
     *
     * @return void
     */
    public static function boot(ICms $cms)
    {
        $repositories = [
            IBlogCategoryRepository::class       => DbBlogCategoryRepository::class,
            IBlogAuthorRepository::class         => DbBlogAuthorRepository::class,
            IBlogArticleRepository::class        => DbBlogArticleRepository::class,
            IBlogArticleCommentRepository::class => DbBlogArticleCommentRepository::class,
        ];

        foreach ($repositories as $interface => $implementation) {
            $cms->getIocContainer()->bind(IIocContainer::SCOPE_SINGLETON, $interface, $implementation);
        }
    }

    /**
     * Defines the structure of this cms package.
     *
     * @param PackageDefinition $package
     *
     * @return void
     */
    protected function define(PackageDefinition $package)
    {
        $package->name('blog');

        $package->metadata([
            'icon' => 'rss',
        ]);

        $package->modules([
            'categories' => BlogCategoryModule::class,
            'authors'    => BlogAuthorModule::class,
            'articles'   => BlogArticleModule::class,
        ]);
    }
}