<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name_en',
        'name_ka'
    ];

    public function getCountry()
    {
        return $this->with('statistic')->get();
    }

    public function statistic(): BelongsTo
    {
        return $this->belongsTo(CountryStatistic::class, 'id', 'country_id');
    }
}
