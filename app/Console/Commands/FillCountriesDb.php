<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FillCountriesDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db-fill:countries';

    /**
     * countries data resource api.
     *
     * @var string
     */
    const URL = 'https://devtest.ge/countries';

    /**
     * count of inserted data in countries db.
     *
     * @var int
     */
    protected $insertCount = 0;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill Countries DataBase';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try
        {
            $countries = Http::get(self::URL)->json();

            foreach ($countries as $country)
            {
                $test = Country::firstOrCreate([
                    'code' => $country['code']
                ], [
                    'name_en' => $country['name']['en'],
                    'name_ka' => $country['name']['ka']
                ]);

                $this->insertCount += $test->wasRecentlyCreated ? 1 : 0;
            }

            $this->info('Success. Total insert: '. $this->insertCount);
        }
        catch (\Exception $exception)
        {
            $this->error($exception->getMessage());
        }

    }
}
