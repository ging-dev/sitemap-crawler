<?php

namespace Gingdev\Crawler\Parser;

/**
 * @phpstan-type ChangeFreqType = 'always'|'hourly'|'daily'|'weekly'|'monthly'|'yearly'|'never'
 * @phpstan-type UrlType = array{loc: string, lastmod?: string, changefreq?: ChangeFreqType, priority?: string}
 */
interface SitemapParserInterface
{
    /**
     * @return iterable<UrlType>
     */
    public function parse(): iterable;

    public function isSitemapIndex(): bool;
}
