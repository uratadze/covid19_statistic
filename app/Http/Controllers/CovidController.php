<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatisticSummaryRequest;
use App\Http\Resources\CountryResource;
use App\Http\Resources\StatisticResource;
use App\Models\Country;
use App\Models\CountryStatistic;
use Illuminate\Http\JsonResponse;

class CovidController extends Controller
{
    /**
     * Get country data with statistics.
     *
     * @param Country $country
     * @return JsonResponse
     */
    public function countryData(Country $country): JsonResponse
    {
        return response()->json([
            'status' => 200,
            'success' => true,
            'data' => CountryResource::collection($country->getCountry())
        ]);
    }

    /**
     * Get statistics by "codes" param and summarise
     *
     * @param StatisticSummaryRequest $request
     * @param CountryStatistic $countryStatistic
     * @return JsonResponse
     */
    public function statisticSummary(StatisticSummaryRequest $request, CountryStatistic $countryStatistic): JsonResponse
    {
        $statistics = $countryStatistic->getStatisticSummary($request->codes);

        return response()->json([
            'status' => 200,
            'success' => true,
            'data' => new StatisticResource($statistics)
        ]);
    }
}
