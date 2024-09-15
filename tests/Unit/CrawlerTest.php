<?php
use Gingdev\Crawler\SitemapCrawler;

test('sitemap.org', function () {
    $sitemapCrawler = new SitemapCrawler();

    $urls = $sitemapCrawler->index('https://www.sitemaps.org/robots.txt');

    expect($urls->current())->toBeArray();
});
