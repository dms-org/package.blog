<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Services\Config;

use Dms\Library\Slug\ISlugGenerator;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogConfiguration
{
    /**
     * @var string
     */
    protected $featuredImagePath;

    /**
     * @var ISlugGenerator
     */
    protected $slugGenerator;

    /**
     * @var callable|null
     */
    protected $articlePreviewCallback;

    /**
     * BlogConfiguration constructor.
     *
     * @param string         $featuredImagePath
     * @param ISlugGenerator $slugGenerator
     * @param callable       $articlePreviewCallback
     */
    public function __construct(string $featuredImagePath, ISlugGenerator $slugGenerator, callable $articlePreviewCallback = null)
    {
        $this->featuredImagePath      = $featuredImagePath;
        $this->slugGenerator          = $slugGenerator;
        $this->articlePreviewCallback = $articlePreviewCallback;
    }

    /**
     * @return BlogConfigurationBuilder
     */
    public static function builder() : BlogConfigurationBuilder
    {
        return new BlogConfigurationBuilder();
    }

    /**
     * @return string
     */
    public function getFeaturedImagePath() : string
    {
        return $this->featuredImagePath;
    }

    /**
     * @return ISlugGenerator
     */
    public function getSlugGenerator() : ISlugGenerator
    {
        return $this->slugGenerator;
    }

    /**
     * @return callable|null
     */
    public function getArticlePreviewCallback()
    {
        return $this->articlePreviewCallback;
    }
}