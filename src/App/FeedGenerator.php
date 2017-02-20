<?php

namespace App;

use FeedWriter\ATOM;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class FeedGenerator
{
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function generateFeed(): ATOM
    {
        $body = (string) $this->client->get('http://ibahiyya.e-monsite.com/pages/posez-vos-questions/posez-vos-questions.html')->getBody();
        $crawler = new Crawler($body);

        $comments = $crawler->filter('.comment')->each(function (Crawler $node, $i) {
            return [
                'author' => $node->filter('.comment_author')->text(),
                'comment' => trim($node->filter('.comment_text')->text()),
                'date' => \DateTime::createFromFormat('d/m/Y', $node->filter('.comment_date')->text()),
            ];
        });

        $feed = new ATOM();
        $feed->setTitle('Al ibahiyya - Poser vos questions');

        foreach ($comments as $comment) {
            $item = $feed->createNewItem();

            $item->setAuthor($comment['author']);
            $item->setId(ATOM::uuid(serialize($comment), 'urn:uuid:'));
            $item->setDate($comment['date']);
            $item->setContent($comment['comment']);

            $feed->addItem($item);
        }

        return $feed;
    }
}
