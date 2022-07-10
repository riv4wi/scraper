<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use phpDocumentor\Reflection\DocBlock\Tags\Deprecated;

class ScraperController extends Controller
{
    private array $results = [];

    public function __invoke(): View
    {
        $client = new Client();
        $url = 'https://www.worldometers.info/coronavirus/';
        $page = $client->request('GET', $url);
        $page->filter('#maincounter-wrap')->each(function ($item) {
            $this->results[str_replace ( ':', '', $item->filter('h1')->text())] = $item->filter('.maincounter-number')->text();
        });

        return view('scraper', ['data'=>$this->results]);
    }
}
