<?php

namespace Gingdev\Crawler\Parser;

class XmlSitemapParser implements SitemapParserInterface
{
    private \SimpleXMLElement $xml;

    public function __construct(string $data)
    {
        $this->xml = new \SimpleXMLElement($data);
    }

    public function mustIndex(): bool
    {
        return 'sitemapindex' === $this->xml->getName();
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->xml as $child) {
            // @phpstan-ignore-next-line
            yield (array) $child;
        }
    }
}
