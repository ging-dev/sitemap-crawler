<?php

namespace Gingdev\Crawler\Parser;

/**
 * @phpstan-type ChangeFreqType = 'always'|'hourly'|'daily'|'weekly'|'monthly'|'yearly'|'never'
 * @phpstan-type UrlType = array{loc: string, lastmod?: string, changefreq?: ChangeFreqType, priority?: string}
 *
 * @extends \IteratorAggregate<UrlType>
 */
interface SitemapParserInterface extends \IteratorAggregate
{
    public function isSitemapIndex(): bool;
}
