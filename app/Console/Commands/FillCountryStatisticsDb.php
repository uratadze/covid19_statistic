<?php

namespace App\Console\Commands;
use App\Models\Country;
use App\Models\CountryStatistic;
use \GuzzleHttp\Client;
use Illuminate\Console\Command;

class FillCountryStatisticsDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'db-fill:country-statistics';

    const URL = 'https://devtest.ge/get-country-statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var Client
     */
    protected $client;

    /**
     * for development testing.
     *
     * @var int
     */
    private $updatedCount = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
    }

    /**
     * Execute the console command.
     *
     * @return int|void
     */
    public function handle()
    {
        try
        {
            $countries = Country::select(['id','code'])->get();
            if ($countries->isEmpty()){
                $this->warn('Fill countries table.');
                return 0;
            }
            foreach ($countries as $country)
            {
                $freshCountryStatistic = $this->statisticByCode($country->code);
                $countryStatistic = CountryStatistic::updateOrCreate([
                    'country_id' => $country->id
                ],[
                    'confirmed' => $freshCountryStatistic['confirmed'],
                    'recovered' => $freshCountryStatistic['recovered'],
                    'critical'  => $freshCountryStatistic['critical'],
                    'deaths'    => $freshCountryStatistic['deaths']
                ]);

                $this->updatedCount += $countryStatistic->wasRecentlyCreated || $countryStatistic->wasChanged() ? 1 : 0;
            }

            $this->info('Success. Update count: ' . $this->updatedCount);
        }
        catch (\Exception $exception)
        {
            $this->error($exception->getMessage());
        }
    }

    /**
     * Call api for fresh data.
     *
     * @param $code
     * @return int|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function statisticByCode($code)
    {
        try {
            $statisticsResource = $this->client->post(self::URL, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'code' => $code,
                ],
            ])->getBody()->getContents();

        }
        catch (\Exception $exception)
        {
            $this->error($exception->getMessage() . "\nCode: $code");
            return 0;
        }

        return json_decode($statisticsResource, true);
    }
}



