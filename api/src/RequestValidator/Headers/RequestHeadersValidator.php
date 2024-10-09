<?php

namespace App\RequestValidator\Headers;

use Symfony\Component\HttpFoundation\Request;

trait RequestHeadersValidator
{
    public function validateRequest(Request $req)
    {
        $headers = $req->headers;
        $contentTypeHeader = $headers->get('Content-Type');
        $acceptHeader = $headers->get('Accept');

        dd($contentTypeHeader, $acceptHeader);
    }
}
