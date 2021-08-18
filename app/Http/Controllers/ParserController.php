<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ParserController extends Controller
{
    public function index()
    {
        return "Shows Saved news & Log history with Pagination after implementing";
    }

    public function parse()
    {
        $method = 'GET';
        $url = 'http://static.feed.rbc.ru/rbc/logical/footer/news.rss';

        $client = new Client();
        $response = $client->request($method, $url);

        $responseHttpCode = $response->getStatusCode();
        $responseBody = $response->getBody();

        //TODO:: collect request & response data & write to DB as Log;
        $xml = simplexml_load_string($responseBody);

        $newsList = [];

        foreach ($xml->channel->item as $item) {
            $author = (string) $item->author ? (string) $item->author : null;
            $mainImage = $item->enclosure ? (string) $item->enclosure['url'][0] : null;
            $link = (string) $item->link;
            //TODO:: Checking for duplicate by news link & exit if it is;

            $news = [
                'title' => $item->title,
                'link' => $link,
                'published_at' => (string) $item->pubDate,
                'description' => (string) $item->description,
                'author' => $author,
                'image' => $mainImage
            ];

            $newsList[] = $news;
        }

        //TODO::Queue creating DB records;
        return $newsList;
    }
}
