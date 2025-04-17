<?php

use Yosymfony\Toml\Toml;

/**
 * Summary of SiteConfig
 * @property string $title
 * @property string $startpage Default page tag
 * @property string[] $navigationPages Navigation page tags
 */
class SiteConfig
{
    public string $title = "Edit site.toml to set site title";
    public string $startpage = "";
    public array $navigationPages = [];

    public function __construct(string $tomlPath)
    {
        $data = Toml::ParseFile($tomlPath);
        $this->title = $data['title'] ?: $this->title;
        $this->startpage = $data['startpage'] ?: $this->startpage;
        $this->navigationPages = $data['navigation'] ?: $this->navigationPages;
    }
}

class Page
{
    public string $tag;
    public string $label;

    public array $config = [];

    private string $rawContent = "";

    private const CONFIG_TAG = "```toml";

    public function __construct(string $pagePath)
    {
        $fileContent = file_get_contents($pagePath);

        $configPattern = '/^' . Page::CONFIG_TAG . '\n([\s\S]*?)\n```/';
        if (preg_match($configPattern, $fileContent, $matches)) {
            $this->config = Toml::parse($matches[1]);
        }

        $removalPattern = '/^' . Page::CONFIG_TAG . '\n[\s\S]*?\n```[ \t]*\n?/';
        $this->rawContent = preg_replace($removalPattern, '', $fileContent);

        $filename = "Unknown";
        if (preg_match('/([^\/]+)(?=\.[^\/.]+$)/', $pagePath, $matches)) {
            $filename = $matches[1]; // Outputs: photo
        }

        $this->tag = $this->config['tag'] ?? $filename;
        $this->label = $this->config['label'] ?? $this->tag;
    }

    public function getHTML(): string
    {
        return ParsedownExtra::instance()->text($this->rawContent);
    }
}

/**
 * @property Page[] $pages
 */
class Site
{
    private SiteConfig $config;

    private array $pages = [];
    private Page $currentPage;

    public function __construct()
    {
        $this->config = new SiteConfig("site.toml");

        $page_tag = substr($_SERVER['REQUEST_URI'], 1);
        if (strlen($page_tag) <= 0) {
            $page_tag = $this->config->startpage;
        }

        $pageItereator = new RecursiveDirectoryIterator("./pages");
        foreach ($pageItereator as $file) {
            if ($file->isFile() && strtolower($file->getExtension()) == "md") {
                array_push($this->pages, new Page($file->getPathname()));
            }
        }

        $this->currentPage = $this->pages[0];

        foreach ($this->pages as $page) {
            if ($page->tag == $page_tag) {
                $this->currentPage = $page;
                break;
            }
        }
    }

    public function getCurrentPage(): Page
    {
        return $this->currentPage;
    }

    public function getConfig(): SiteConfig
    {
        return $this->config;
    }

    public function getPageByTag(string $tag): Page|null
    {
        foreach ($this->pages as $page) {
            if ($page->tag == $tag) {
                return $page;
            }
        }
        return null;
    }

    /**
     * @return Page[]
     */
    public function getNavItems(): array
    {
        return array_filter(
            array_map(
                fn(string $pageTag) => $this->getPageByTag($pageTag),
                $this->config->navigationPages
            )
        );
    }
}