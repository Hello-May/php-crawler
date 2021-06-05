<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;


class Craw extends Command
{
    /** @var Client  */
    // private $client;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'craw:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // $this->client = app(Client::class);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $name = $this->argument('name');
        // $this->info('Hello ' . $name);

        // $latestNews = 'https://crypto.cnyes.com/BTC/24h';
        // $client = new Client();
        // $response = $client->request('GET', $latestNews);

        // $latestNewsString = (string)$response->getBody();

        // $crawler = new Crawler($latestNewsString);

        // $crawler = $crawler
        //     ->filter('title')
        //     ->reduce(function (Crawler $node, $i) {
        //         echo $node->text();
        // });

        // $latestNews = 'https://crypto.cnyes.com/BTC/24h';
        // $client = new Client();
        // $response = $client->request('GET', $latestNews);

        // $courseOutlineString = (string)$response->getBody();

        // $columnStrings = [];

        // $crawler = new Crawler($courseOutlineString);

        // $crawler
        //     ->filter('body')
        //     ->reduce(function (Crawler $node, $i) {
        //         // global $columnStrings;
        //         // $columnStrings[] = $node->text();
        //         echo $node->text() ;
        //     });

        // var_dump($columnStrings);
        $headers = [
            'Content-Type' => 'application/json',
            'AccessToken' => 'key',
            'Authorization' => 'Bearer token',
        ];


        // $page = 0;

        $client = new Client();
        $counter = 0;
        $colName = "";

        for ($page=1 ; $page <=5 ; $page++)
        {

            $url = 'http://ursalary0.com/salaries/salary_lists_tw/page:' . $page . '/q:工程師';

            $response = $client->request('post', $url,
                [
                    'headers' => [
                        'User-Agent' => 'PostmanRuntime/7.26.2',
                        'Accept' => '*/*'
                    ]
                ]
            );

            $body = (string)$response->getBody();
            $crawler = new Crawler($body);

            $columnStrings = [];
            $target = $crawler->filter('td');


            foreach ($target as $domElement) {

                $col = $counter % 6;
                $row = $counter / 6;

                switch ($col)
                {
                    case 0:
                        $colName = "公司名稱";
                        break;
                    case 1:
                        $colName = "產業類別";
                        break;
                    case 2:
                        $colName = "職稱";
                        break;
                    case 3:
                        $colName = "薪資";
                        break;
                    case 4:
                        $colName = "相關經驗";
                        break;
                    case 5:
                        $colName = "瀏覽/回覆";
                        break;
                }


                $columnStrings[$row][$colName] = $domElement->nodeValue;
                $counter += 1;

            }
            var_dump ($columnStrings);
        }
    }


    public function getOriginalData(string $path): Crawler
    {
        $content = $this->client->get($path)->getBody()->getContents();
        $crawler = new Crawler();

        $crawler->addHtmlContent($content);

        return $crawler;
    }

    public function getItemValue(Crawler $crawler)
    {
        $target = $crawler->filterXPath('//div[contains(@class, "newanime")]');

        return $target;
    }

}
