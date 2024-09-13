<?php

namespace Gingdev\Crawler;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @phpstan-type ChangeFreqType = 'always'|'hourly'|'daily'|'weekly'|'monthly'|'yearly'|'never'
 * @phpstan-type UrlType = object{loc: string, lastmod?: string, changefreq?: ChangeFreqType, priority?: string}
 * @phpstan-type SitemapType = array{sitemap?: UrlType[], url?: UrlType[]}
 */
final class SitemapCrawler
{
    private HttpClientInterface $client;

    public function __construct(?HttpClientInterface $client = null)
    {
        $this->client = $client ?: HttpClient::create();
    }

    /**
     * @param callable(UrlType):bool|null $filter
     *
     * @return iterable<UrlType>
     */
    public function index(string $url, ?callable $filter = null): iterable
    {
        $response = $this->client->request('GET', $url);

        $data = $response->getContent();

        // try to decode if data is gz format
        $data = @gzdecode($data) ?: $data;

        /** @var SitemapType */
        $parser = (array) new \SimpleXMLElement($data);

        foreach (array_filter($parser['sitemap'] ?? [], $filter) as $url) {
            yield from $this->index($url->loc, $filter);
        }

        foreach (array_filter($parser['url'] ?? [], $filter) as $url) {
            yield $url;
        }
    }
}
