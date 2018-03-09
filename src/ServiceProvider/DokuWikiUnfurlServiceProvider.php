<?php

namespace DokuWikiSlackUnfurl\ServiceProvider;

use DokuWikiSlackUnfurl\Event\Subscriber\DokuWikiUnfurler;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DokuWikiUnfurlServiceProvider implements ServiceProviderInterface, EventListenerProviderInterface
{
    public function register(Container $app)
    {
        $app['dokuwiki.url'] = getenv('DOKUWIKI_URL');
        $app['dokuwiki.username'] = getenv('DOKUWIKI_USERNAME');
        $app['dokuwiki.password'] = getenv('DOKUWIKI_PASSWORD');

        $app[DokuWikiUnfurler::class] = function ($app) {
            $domain = parse_url($app['dokuwiki.url'], PHP_URL_HOST);

            return new DokuWikiUnfurler(
                $domain,
                $app['logger']
            );
        };
    }

    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addSubscriber($app[DokuWikiUnfurler::class]);
    }
}
