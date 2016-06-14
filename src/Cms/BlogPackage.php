<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Cms;

use Dms\Core\ICms;
use Dms\Core\Ioc\IIocContainer;
use Dms\Core\Package\Definition\PackageDefinition;
use Dms\Core\Package\Package;
use Dms\Package\Blog\Core\IBlogArticleRepository;
use Dms\Package\Blog\Core\IBlogCategoryRepository;
use Dms\Package\Blog\Persistence\DbBlogArticleRepository;
use Dms\Package\Blog\Persistence\DbBlogCategoryRepository;

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
        $cms->getIocContainer()->bind(IIocContainer::SCOPE_SINGLETON, IBlogCategoryRepository::class, DbBlogCategoryRepository::class);
        $cms->getIocContainer()->bind(IIocContainer::SCOPE_SINGLETON, IBlogArticleRepository::class, DbBlogArticleRepository::class);
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
            'blog-categories' => BlogCategoryModule::class,
            'blog-articles' => BlogArticleModule::class,
        ]);
    }
}