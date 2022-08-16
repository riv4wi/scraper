<?php

namespace App\Http\Controllers;

use Exception;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RobotsTxtParser\RobotsTxtParser;
use RobotsTxtParser\RobotsTxtValidator;
use TwoCaptcha\TwoCaptcha;

class ScraperController extends Controller
{
    private array $results = [];

    private array $settings;

    private string $selectedConfig;

    const ALLOW_SCRAPING_MODE = 1;
    const DENY_SCRAPING_MODE = 2;
    const CUSTOM_SCRAPING_MODE = 3;

    const SETTINGS = [
        'configuration_bcra' => [
          'url'     => 'https://www.bcra.gob.ar/BCRAyVos/Situacion_Crediticia.asp',
          'selector' => 'h2'
        ],
        'configuration_test' => [
            'url'     => 'https://artisansweb.net/how-to-log-query-in-laravel/',
            'selector' => 'h2'
        ],
        'configuration_corona' => [
            'url'     => 'https://www.worldometers.info/coronavirus/',
            'selector' => '#maincounter-wrap'
        ],
    ];

    public function __construct(Request $request )
    {
        $this->selectedConfig = $request->route('config');
        $this->settings = match ($this->selectedConfig) {
            'bcra' => self::SETTINGS['configuration_bcra'],
            'test' => self::SETTINGS['configuration_test'],
            'corona' => self::SETTINGS['configuration_corona'],
        };
    }

    /**
     * @throws Exception
     */
    public function __invoke(): View
    {
        $parser = $this->parseRobotTxt(self::ALLOW_SCRAPING_MODE);
        $validator = new RobotsTxtValidator($parser->getRules());
        $url = '/';
        $userAgent = 'MyAwesomeBot';

        if ($validator->isUrlAllow($url, $userAgent)) {
            print_r('It can be scratched'); echo '<br>';

            $client = new Client();
            $crawler = $client->request('GET', $this->settings['url']);
            dd($crawler->filter($this->settings['selector'])); // todo:  I'm going to stop here
//            $data = $crawler->filter('body')->children();
            $crawler->filter($this->settings['selector'])->each(function ($node) {
                print $node->text().'<br><br>';
            });
//            $crawler->filter('#f5_cspm')->children()->each(function ($node) {
//                print "<p>".$node->text()."</p><br>";
//            });
        }
        else {
            print_r("It can't be scratched"); echo '<br>';
        }
//        if ($this->selectedConfig = 'configuration_corona') {
//            $crawler->filter($this->settings['selector'])->each(function ($item) {
//                $this->results[str_replace(':', '', $item->filter('h1')->text())] = $item->filter('.maincounter-number')->text();
//            });
//        }
//        return view('scraper', ['data'=>$this->results]);
    }

    /**
     * @param int $mode
     * @return RobotsTxtParser
     * @throws Exception
     */
    private function parseRobotTxt(int $mode): RobotsTxtParser
    {
        return match ($mode) {
            self::ALLOW_SCRAPING_MODE => new RobotsTxtParser("
                    User-Agent: *
                    Disallow:
                "),
            self::DENY_SCRAPING_MODE => new RobotsTxtParser("
                    User-Agent: *
                    Disallow: /
                "),
            self::CUSTOM_SCRAPING_MODE => new RobotsTxtParser(file_get_contents($this->getUrlRobotsTxt($this->settings['url']))),
            default => throw new Exception('Unexpected match value'),
        };
    }

    private function getUrlRobotsTxt($url): string
    {
        $url = explode('/', $url);
        return $url[0].'//'.$url[2].'/robots.txt';
    }
}
