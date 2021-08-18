<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ParserLog;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParserController extends Controller
{
    /**
     * Админ-панель для наблюдения результатов;
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $paginatedArticles = Article::orderByDesc('id')->paginate(10);
        $paginatedLogs = ParserLog::orderByDesc('id')->paginate(10);

        return view('welcome', compact('paginatedArticles', 'paginatedLogs'));
    }

    /**
     *  Парсит резульатотов заданного URL в случае ответа 200, Ok;
     *  Генерирует и создает логи;
     *  Отфильровывает данные новостей из RSS seed, создает записей всех уникальных в новостей БД
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function parse()
    {
        //Массив для новых новостей;
        $newsList = [];
        /**  Возвращает ссылку или null для проверки в уникальности; @var null|string $latestNewsLink*/
        $latestNewsLink = Article::getLastItemsLink();

        $method = 'GET';
        $url = 'http://static.feed.rbc.ru/rbc/logical/footer/news.rss';

        /** @var  $client  \GuzzleHttp\Client */
        $client = new Client();

        //Устанавливаем время запроса и сделаем запрос
        $requestTime = now();
        $response = $client->request($method, $url);

        // Параметры для логирования
        $responseHttpCode = $response->getStatusCode();
        $responseBody = $response->getBody();

        //TODO::refractor Logging to midlleware or event class;

        // Логироваемся в БД
        DB::table('parser_logs')->insert([
            [
                'requestTime' => $requestTime,
                'requestMethod' => $method,
                'requestUrl' => $url,
                'responseCode' => $responseHttpCode,
                'responseBody' => json_encode($responseBody)
            ]
        ]);

        // При получении успешного ответа от серсера приступаем прасированию;
        if ($responseHttpCode >= 200 & $responseHttpCode < 300) {
            //Конвертируем данные ответа в ХМЛ;
            $xml = simplexml_load_string($responseBody);

            foreach ($xml->channel->item as $item) {
                $link = (string) $item->link;
                /** Проверяем новость на уникальность по ссылке и останавливаем цикл */
                if ($latestNewsLink === $link) {
                    break;
                }
                $author = (string) $item->author ? (string) $item->author : null;
                $mainImage = $item->enclosure ? (string) $item->enclosure['url'][0] : null;

                $news = [
                    'title' => (string) $item->title,
                    'link' => $link,
                    'published_at' => (string) $item->pubDate,
                    'description' => (string) $item->description,
                    'author' => $author,
                    'image' => $mainImage
                ];

                /** Рекурсивно добавляем отфильтровынных новостей к списке новостей*/
                array_unshift($newsList, $news);
            }
        }

        /** Если списиок новостей не пуст, создаем новые записи в БД */
        if (!empty($newsList)) {
            DB::table('articles')->insert($newsList);
        }
    }
}
