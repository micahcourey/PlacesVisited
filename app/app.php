<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Place.php";

    session_start();
    if(empty($_SESSION['list_of_places'])) {
        Place::deleteAll();  //start a new empty array
    }

    $app = new Silex\Application();

    //Provide integration with the Twig templete engine
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    //root path
    $app->get("/", function() use ($app) {
        return $app['twig']->render('places.html.twig', array('places' => Place::getAll()));
    });

    $app->post("/places", function() use ($app) {
        $place_and_time = new Place($_POST['place'], $_POST['time_stayed']);
        $place_and_time->save();
        return $app['twig']->render('create_places.html.twig', array('newplace' => $place_and_time));
    });

    $app->post("/delete_places", function() use ($app) {
        Place::deleteAll();
        return $app['twig']->render('delete_places.html.twig');
    });

    return $app;
?>
