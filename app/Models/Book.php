<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log; 
class Book extends Model
{


    use HasFactory;

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'like', '%' . $title . '%');
    }
    
    public function scopeWithReviewsCount(Builder $query, $from = null, $to = null): Builder
    {   
        Log::debug('withReviewsCount scope has been called.');
        $this->dateRangeFilter($query, $from, $to);
        return $query->withCount('reviews');
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder
    {   
        log::debug('popular has been called.'); 
        $this->dateRangeFilter($query, $from, $to);
        return $query->WithReviewsCount()
        ->orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder
    {   
        $this->dateRangeFilter($query, $from, $to);
        return $query->WithAvgRating()->orderBy('reviews_avg_rating', 'desc');
    }
    public function scopeWithAvgRating(Builder $query, $from = null, $to = null): Builder
    {
        $this->dateRangeFilter($query, $from, $to);
        return $query->withAvg(['reviews'], 'rating');
    }

    public function scopeMinReviews(Builder $query, int $minReviews): Builder
    {
        return $query->withCount('reviews')->having('reviews_count', '>=', $minReviews);
    }


    private function dateRangeFilter(Builder $query, $from, $to)
    {
        Log::debug('dateRangeFilter has been called.');
        if($from && !$to){
            $query->where('created_at', '>=', $from);
        } elseif(!$from && $to){
            $query->where('created_at', '<=', $to);
        } elseif($from && $to){
        $query->whereBetween('created_at', [$from, $to]);
        }
    }
    // //scopePopularLastMonth
    function scopePopularLastMonth(Builder $query): Builder
    {
        return $query->popular(now()->subMonth(), now())
        ->highestRated(now()->subMonth(), now());
        // ->minReviews(3);
    }

    function scopePopularLast6Months(Builder $query): Builder
    {
        return $query->Popular(now()->subMonths(6), now())
        ->HighestRated(now()->subMonths(6), now())
        ->minReviews(2);
    }

    public function scopeHighestRatedLastMonth(Builder $query): Builder
    {
        return $query->HighestRated(now()->subMonth(), now())
        ->Popular(now()->subMonth(), now());
        // ->minReviews(1);
    }

    public function scopeHighestRatedLast6Months(Builder $query): Builder
    {
        return $query->HighestRated(now()->subMonths(6), now())
        ->Popular(now()->subMonths(6), now())
        ->minReviews(2);
    }

    protected static function booted()
    {
        static::updated(function(Book $book){
            cache()->forget('book:' . $book->id);
        });
        static::deleted(function(Book $book){
            cache()->forget('book:' . $book->id);
        });
    }

}
