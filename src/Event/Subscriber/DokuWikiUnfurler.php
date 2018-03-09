<?php

namespace DokuWikiSlackUnfurl\Event\Subscriber;

use Psr\Log\LoggerInterface;
use SlackUnfurl\Event\Events;
use SlackUnfurl\Event\UnfurlEvent;
use SlackUnfurl\LoggerTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DokuWikiUnfurler implements EventSubscriberInterface
{
    use LoggerTrait;

    /** @var string */
    private $domain;

    public function __construct(
        string $domain,
        LoggerInterface $logger
    ) {
        $this->domain = $domain;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::SLACK_UNFURL => ['unfurl', 10],
        ];
    }

    public function unfurl(UnfurlEvent $event)
    {
        foreach ($event->getMatchingLinks($this->domain) as $link) {
            $unfurl = [];
            if ($unfurl) {
                $event->addUnfurl($link['url'], $unfurl);
            }
        }
    }
}