<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\CountryStatistic;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryStatisticFactory extends Factory
{
    protected $model = CountryStatistic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'country_id' => function(){
                return Country::factory()->create()->id;
            },
            'confirmed' => $this->faker->randomDigit(),
            'recovered' => $this->faker->randomDigit(),
            'critical' => $this->faker->randomDigit(),
            'deaths' => $this->faker->randomDigit(),
        ];
    }
}
