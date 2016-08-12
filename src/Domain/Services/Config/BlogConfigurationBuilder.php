<?php declare(strict_types = 1);

namespace Dms\Package\Blog\Domain\Services\Config;

use Dms\Library\Slug\Generator\DashedSlugGenerator;
use Dms\Library\Slug\ISlugGenerator;

/**
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class BlogConfigurationBuilder
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
     * @param string $featuredImagePath
     *
     * @return BlogConfigurationBuilder
     */
    public function setFeaturedImagePath(string $featuredImagePath) : self
    {
        $this->featuredImagePath = $featuredImagePath;

        return $this;
    }

    /**
     * @param ISlugGenerator $slugGenerator
     *
     * @return BlogConfigurationBuilder
     */
    public function setSlugGenerator(ISlugGenerator $slugGenerator) : self
    {
        $this->slugGenerator = $slugGenerator;

        return $this;
    }

    /**
     * @return BlogConfigurationBuilder
     */
    public function useDashedSlugGenerator() : self
    {
        return $this->setSlugGenerator(new DashedSlugGenerator());
    }

    /**
     * @param callable|null $articlePreviewCallback
     *
     * @return BlogConfigurationBuilder
     */
    public function setArticlePreviewCallback(callable $articlePreviewCallback = null) : self
    {
        $this->articlePreviewCallback = $articlePreviewCallback;

        return $this;
    }

    /**
     * @return BlogConfiguration
     */
    public function build() : BlogConfiguration
    {
        return new BlogConfiguration(
            $this->featuredImagePath,
            $this->slugGenerator,
            $this->articlePreviewCallback
        );
    }
}