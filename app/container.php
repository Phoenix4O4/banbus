<?php

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Twig\Loader\FilesystemLoader;
use Twig\Extra\Markdown\DefaultMarkdown;
use Twig\Extra\Markdown\MarkdownRuntime;
use Twig\RuntimeLoader\RuntimeLoaderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use GuzzleHttp\Client as Guzzle;
use App\Provider\ExternalAuth;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utilities\UrlGenerator;
use App\Factory\SettingsFactory;
use App\Responder\Responder;
use App\Middleware\UserMiddleware;
use App\Repository\ConnectionFactory;

return [
  //Settings
  "settings" => function () {
      return require __DIR__ . "/settings.php";
  },

  // //App
  App::class => function (ContainerInterface $container) {
      AppFactory::setContainer($container);
      $app = AppFactory::create();
      if ($container->get("settings")["basepath"]) {
          $app->setBasePath($container->get("settings")["basepath"]);
      }
      return $app;
  },

  //Response
  ResponseFactoryInterface::class => function (ContainerInterface $container) {
      return $container->get(App::class)->getResponseFactory();
  },

  //Route parser
  RouteParserInterface::class => function (ContainerInterface $container) {
      return $container
      ->get(App::class)
      ->getRouteCollector()
      ->getRouteParser();
  },
  Request::class => function (ContainerInterface $container) {
      return $container->get("Request");
  },

  //Twig middleware
  TwigMiddleware::class => function (ContainerInterface $container) {
      return TwigMiddleware::createFromContainer(
          $container->get(App::class),
          Twig::class
      );
  },

  // Twig templates
  Twig::class => function (ContainerInterface $container) {
      $session = $container->get(Session::class);
      $config = (array) $container->get("settings");
      $settings = $config["twig"];
      $options = $settings["options"];
      $options["cache"] = $options["cache_enabled"]
      ? $options["cache_path"]
      : false;

      $twig = Twig::create($settings["paths"], $options);

      $loader = $twig->getLoader();
      $publicPath = (string) $config["public"];
      if ($loader instanceof FilesystemLoader) {
          $loader->addPath($publicPath, "public");
      }
      $twig->addExtension(new \Twig\Extension\DebugExtension());
      $twig->addExtension(new \buzzingpixel\twigswitch\SwitchTwigExtension());
      $twig->addRuntimeLoader(
          new class implements RuntimeLoaderInterface {
              public function load($class)
              {
                  if (MarkdownRuntime::class === $class) {
                      return new MarkdownRuntime(new DefaultMarkdown());
                  }
              }
          }
      );
      $twig->addExtension(new \Twig\Extra\Markdown\MarkdownExtension());
      $twig->getEnvironment()->addGlobal("debug", $config["debug"]);
      $twig->getEnvironment()->addGlobal("app", $config["app"]);
      $twig->getEnvironment()->addGlobal("modules", $config["modules"]);
      $twig->getEnvironment()->addGlobal("flash", $session->getFlashBag()->all());
      $twig->getEnvironment()->addGlobal("user", $session->get("user"));

      return $twig;
  },
  Session::class => function (ContainerInterface $container) {
      $settings = $container->get("settings")["session"];
      if (PHP_SAPI === "cli") {
          return new Session(new MockArraySessionStorage());
      } else {
          return new Session(new NativeSessionStorage($settings));
      }
  },
  Guzzle::class => function (ContainerInterface $container) {
      return new Guzzle();
  },
  ExternalAuth::class => function (ContainerInterface $container) {
      return new ExternalAuth(
          $container->get(Guzzle::class),
          $container->get(UrlGenerator::class)
      );
  },
  UrlGenerator::class => function () {
      return new UrlGenerator();
  },
  SettingsFactory::class => function (ContainerInterface $container) {
      return new SettingsFactory($container->get("settings"));
  },
  'db' => function (ContainerInterface $container) {
      $config = (array) $container->get("settings")["db"];
      try {
          $db = \ParagonIE\EasyDB\Factory::fromArray([
        $config["method"] .
        ":host=" .
        $config["host"] .
        ";port=" .
        $config["port"] .
        ";dbname=" .
        $config["database"],
        $config["username"],
        $config["password"],
        $config["flags"],
      ]);
      } catch (Exception $e) {
          die($e->getMessage());
      }
      $db->debug = $container->get("settings")["debug"];
      return $db;
  },

  'alt_db' => function (ContainerInterface $container) {
      $config = (array) $container->get("settings")["alt_db"];
      try {
          $db = \ParagonIE\EasyDB\Factory::fromArray([
        $config["method"] .
        ":host=" .
        $config["host"] .
        ";port=" .
        $config["port"] .
        ";dbname=" .
        $config["database"],
        $config["username"],
        $config["password"],
        $config["flags"],
      ]);
      } catch (Exception $e) {
          die($e->getMessage());
      }
      $db->debug = $container->get("settings")["debug"];
      return $db;
  },

  ConnectionFactory::class => function (ContainerInterface $container) {
      return new ConnectionFactory($container->get('db'), $container->get('alt_db'));
  },

  Responder::class => function (ContainerInterface $container) {
      $twig = $container->get(Twig::class);
      $routeParserInterface = $container->get(RouteParserInterface::class);
      $responseFactoryInterface = $container->get(
          ResponseFactoryInterface::class
      );
      return new Responder(
          $twig,
          $routeParserInterface,
          $responseFactoryInterface
      );
  },

  UserMiddleware::class => function (ContainerInterface $container) {
      $user = $container->get(Session::class)->get("user");
      $responder = $container->get(Responder::class);
      $session = $container->get(Session::class);
      $settings = $container->get("settings")["site_perms"];
      return new UserMiddleware($user, $responder, $session, $settings);
  },
];
