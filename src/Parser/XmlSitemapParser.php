<?php

namespace Gingdev\Crawler\Parser;

class XmlSitemapParser implements SitemapParserInterface
{
    private \SimpleXMLElement $xml;

    public function __construct(string $data)
    {
        $this->xml = new \SimpleXMLElement($data);
    }

    public function isSitemapIndex(): bool
    {
        return 'sitemapindex' === $this->xml->getName();
    }

    public function parse(): iterable
    {
        foreach ($this->xml as $child) {
            // @phpstan-ignore-next-line
            yield (array) $child;
        }
    }
}
