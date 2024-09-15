<?php

namespace Gingdev\Crawler;

use Gingdev\Crawler\Parser\RobotsTxtParser;
use Gingdev\Crawler\Parser\SitemapParserInterface;
use Gingdev\Crawler\Parser\XmlSitemapParser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @phpstan-import-type UrlType from SitemapParserInterface
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
     * @return \Generator<int, UrlType, mixed, void>
     */
    public function index(string $url, ?callable $filter = null): \Generator
    {
        $response = $this->client->request('GET', $url);
        $contentType = explode(';', $response->getHeaders()['content-type'][0] ?? '')[0];
        $data = $response->getContent();

        set_error_handler(static fn () => null);
        try {
            /** @var SitemapParserInterface */
            $parser = match ($contentType) {
                'text/plain' => new RobotsTxtParser($data),
                default => new XmlSitemapParser(@gzdecode($data) ?: $data),
            };
        } finally {
            restore_error_handler();
        }

        /** @var UrlType[] */
        $urls = array_filter(iterator_to_array($parser->parse()), $filter);

        if (!$parser->isSitemapIndex()) {
            yield from $urls;

            return;
        }

        foreach ($urls as $sitemap) {
            yield from $this->index($sitemap['loc'], $filter);
        }
    }
}
