<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Request;

class ControllerHelpers
{
    /**
     * Gets params for Book class
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array<string> $params
     * @return string[]
     */
    public function getParamBag(
        Request $request,
        array $params
    ): array {
        $formData = [];
        foreach ($params as $param) {
            $formData[$param] = strval($request->request->get($param, 'Ej angett'));
        }
        return $formData;
    }

}
