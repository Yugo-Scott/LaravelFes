<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(5)->create();

        Book::factory(27)->create()->each(function (Book $book) {
            $numberOfReviews = random_int(1, 5);
            Review::factory($numberOfReviews)->good()->for($book)->create();
        });

        Book::factory(27)->create()->each(function (Book $book) {
            $numberOfReviews = random_int(1, 5);
            Review::factory($numberOfReviews)->bad()->for($book)->create();
        });

    }
}
