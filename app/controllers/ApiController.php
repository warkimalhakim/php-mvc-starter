<?php

namespace Warkim\controllers;

use GuzzleHttp\Client;
use Warkim\core\BaseController;

class ApiController extends BaseController
{

    public function get()
    {

        $client = new Client();
        $req = $client->request('GET', 'https://dummyjson.com/users', [
            'query' => [
                'limit' => 15
            ]
        ]);

        $data = [];
        if ($req->getStatusCode() == 200) {
            $data = json_decode($req->getBody()->getContents(), false);
            $data = $data->users ?? $data['users'];
        }

        return view('api', [
            'title' => 'Contoh Get API dengan Guzzle',
            'users' => $data
        ]);
    }
}
