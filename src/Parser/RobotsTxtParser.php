<?php

namespace Gingdev\Crawler\Parser;

class RobotsTxtParser implements SitemapParserInterface
{
    public function __construct(private string $data)
    {
    }

    public function isSitemapIndex(): bool
    {
        return true;
    }

    public function getIterator(): \Traversable
    {
        preg_match_all('/^Sitemap:\s*(.+)$/mi', $this->data, $matches);

        foreach ($matches[1] as $loc) {
            yield ['loc' => $loc];
        }
    }
}
