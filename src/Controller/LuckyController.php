<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LuckyController extends AbstractController
{
    #[Route("/lucky", name: "lucky")]
    public function number(): Response
    {
        include 'data/API_KEY.php';
        // Med enviroment variables, best practice. ".env.local" ?
        // $key = $_ENV['API_KEY'];
        // var_dump($key);

        $locations = ['Stockholm', 'Ouagadougou', 'Durban', 'Jönköping', 'Karachi', 'Caracas', 'Canberra', 'Wellington', 'Freetown', 'Manilla'];
        $number = random_int(0, count($locations) - 1);
        
        $location = $locations[$number];
        
        $url = 'http://api.weatherstack.com/current?access_key='.$key.'&query='.$location.'&units=m';

        $res = json_decode(file_get_contents( $url), true);
        // var_dump($res);

        $data = [
            'location' => $location,
            'weather' => $res['current']['weather_descriptions'][0],
            'icon' => $res['current']['weather_icons'][0],
            'temp' => $res['current']['temperature']
        ];

        return $this->render('lucky.html.twig', $data);
    }
}


