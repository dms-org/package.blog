<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Services;

use Dms\Core\Ioc\IIocContainer;
use Dms\Package\Blog\Domain\Services\Comment\BlogArticleCommentingService;
use Dms\Package\Blog\Domain\Services\Config\BlogConfiguration;
use Dms\Package\Blog\Domain\Services\Loader\BlogArticleLoader;
use Dms\Package\Blog\Domain\Services\Loader\BlogAuthorLoader;
use Dms\Package\Blog\Domain\Services\Loader\BlogCategoryLoader;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogKernel
{
    /**
     * @var IIocContainer
     */
    protected $iocContainer;

    /**
     * BlogKernel constructor.
     *
     * @param IIocContainer $iocContainer
     */
    public function __construct(IIocContainer $iocContainer)
    {
        $this->iocContainer = $iocContainer;
    }

    /**
     * @return BlogConfiguration
     */
    public function configuration(): BlogConfiguration
    {
        return $this->iocContainer->get(BlogConfiguration::class);
    }

    /**
     * @return BlogCategoryLoader
     */
    public function categories(): BlogCategoryLoader
    {
        return $this->iocContainer->get(BlogCategoryLoader::class);
    }

    /**
     * @return BlogAuthorLoader
     */
    public function authors(): BlogAuthorLoader
    {
        return $this->iocContainer->get(BlogAuthorLoader::class);
    }

    /**
     * @return BlogArticleLoader
     */
    public function articles(): BlogArticleLoader
    {
        return $this->iocContainer->get(BlogArticleLoader::class);
    }

    /**
     * @return BlogArticleCommentingService
     */
    public function comments(): BlogArticleCommentingService
    {
        return $this->iocContainer->get(BlogArticleCommentingService::class);
    }
}