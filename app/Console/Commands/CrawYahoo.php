<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;


class CrawYahoo extends Command
{
    /** @var Client  */
    // private $client;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'craw:yahoo';

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

        $headers = [
            'Content-Type' => 'application/json',
            'AccessToken' => 'key',
            'Authorization' => 'Bearer token',
        ];

        $client = new Client();
        $counter = 0;
        $colName = "";

        for ($page=1 ; $page <=5 ; $page++)
        {

            $url = 'https://finance.yahoo.com/quote/BTC-USD/';

            $response = $client->request('get', $url,
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
            $target = $crawler->filter('span[data-reactid="32"]');

            foreach ($target as $domElement) {
                $columnStrings[$counter] = $domElement->nodeValue;
                $counter += 1;
            }
            // var_dump ($columnStrings[0]);

            // - 名稱
            // - 種類(虛擬幣/ETF)
            // - 台幣價格
            // - 美金價格
            // - 時間
            $data = array(
                'BTC', 'bitcoin', null, $columnStrings[0], date("Y-m-d")
            );

            $path ="testfile.csv";
            $fp = fopen($path, 'a+');
            fputcsv($fp, $data);
            fclose($fp);
        }
    }

}
