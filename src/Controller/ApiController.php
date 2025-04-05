<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController
{

    #[Route("/api/quote", name:"quote")]
    /**
     * Serves a quote from Alfred Hitchcock by converting a JSON file into an associated array,
     * picking a random quote that gets turned back to JSON and presented. 
     * @return JsonResponse
     */
    public function jsonQuotes(): Response
    {
        $number = random_int(0, 52);
        $quotes = json_decode(file_get_contents( 'data/quotes.json'), true);
        // var_dump($quotes['quotes']);
        // var_dump(array_keys($quotes));
        $quote = $quotes['quotes'][$number];

        // Returning JSON data:

        // $response = new Response();
        // $response->setContent(json_encode($data));
        // // Sets type of data
        // $response->headers->set('Content-Type', 'application/json');
        // return $response;

        // This can be simplified by using: JsonResponse
        // "imported" with use at top.


        // RAW JSON DATA TO PRINT
        // return new JsonResponse($quotes);
        
        // Here we first prettify the json data, JSON_PRETTY_PRINT
        
        $response = new JsonResponse($quote);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
