<?php

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use DI\Container;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use GuzzleHttp\Client as Guzzle;
use App\Provider\ExternalAuth;
use App\Service\Service;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utilities\UrlGenerator;
use Cake\Database\Connection;

return [
  //Settings
  'settings' => function () {
      return require __DIR__ . '/settings.php';
  },

  // //App
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },

  // //Response
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getResponseFactory();
    },

  // //Route parser
    RouteParserInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getRouteCollector()->getRouteParser();
    },
    Request::class => function (ContainerInterface $container) {
        return $container->get('Request');
    },

  // //Twig middleware
    TwigMiddleware::class => function (ContainerInterface $container) {
        return TwigMiddleware::createFromContainer($container->get(App::class), Twig::class);
    },

    // Twig templates
    Twig::class => function (ContainerInterface $container) {
        $config = (array)$container->get('settings');
        $session = $container->get(Session::class);
        $settings = $config['twig'];
        $options = $settings['options'];
        $options['cache'] = $options['cache_enabled'] ? $options['cache_path'] : false;

        $twig = Twig::create($settings['paths'], $options);

        $loader = $twig->getLoader();
        $publicPath = (string)$config['public'];
        if ($loader instanceof FilesystemLoader) {
            $loader->addPath($publicPath, 'public');
        }

        $twig->addExtension(new \Twig\Extension\DebugExtension());
        $twig->getEnvironment()->addGlobal('app', $config['app']);
        $twig->getEnvironment()->addGlobal('flash', $session->getFlashBag()->all());
        $twig->getEnvironment()->addGlobal('user', $session->get('user'));

        return $twig;
    },
    Session::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['session'];
        if (PHP_SAPI === 'cli') {
            return new Session(new MockArraySessionStorage());
        } else {
            return new Session(new NativeSessionStorage($settings));
        }
    },
      Guzzle::class => function (ContainerInterface $container) {
          return new Guzzle();
      },
    ExternalAuth::class => function (ContainerInterface $container) {
        return new ExternalAuth($container->get(Guzzle::class), $container->get(UrlGenerator::class));
    },
      UrlGenerator::class => function (ContainerInterface $container) {
          return new UrlGenerator();
      },
    Service::class => function (ContainerInterface $container) {
        return new Service($container->get(Session::class));
    },
    // Database connection
    Connection::class => function (ContainerInterface $container) {
        return new Connection($container->get('settings')['db']);
    }

];
