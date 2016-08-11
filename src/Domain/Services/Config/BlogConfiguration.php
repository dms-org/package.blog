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
     * BlogConfiguration constructor.
     *
     * @param string         $featuredImagePath
     * @param ISlugGenerator $slugGenerator
     */
    public function __construct(string $featuredImagePath, ISlugGenerator $slugGenerator)
    {
        $this->featuredImagePath = $featuredImagePath;
        $this->slugGenerator     = $slugGenerator;
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
}