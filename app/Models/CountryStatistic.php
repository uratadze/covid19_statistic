<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CountryStatistic extends Model
{
    use HasFactory;

    protected $table = 'country_statistics';

    protected $fillable = [
        'country_id',
        'confirmed',
        'recovered',
        'critical',
        'deaths',
    ];

    public function getStatisticSummary($codes): array
    {
        $statistics = $this
            ->with('country')
            ->whereHas('country', function ($country) use ($codes){
                $country->whereIn('code', $codes);
            })
            ->get();

        return [
            'confirmed' => $statistics->pluck('confirmed')->sum(),
            'recovered' => $statistics->pluck('recovered')->sum(),
            'critical' => $statistics->pluck('critical')->sum(),
            'deaths' => $statistics->pluck('deaths')->sum(),
            'created_at' => $statistics->first()->created_at ?? null,
            'updated_at' => $statistics->first()->updated_at ?? null
        ];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
