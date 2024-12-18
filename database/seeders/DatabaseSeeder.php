<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       Category::factory(10)->create();
       $products=Product::factory(20)->create();
       Tag::factory(20)->create();
       $products->each(function ($product) {
           $product->tags()->attach([rand(1,20), rand(1,20)]);
       });

       /*$user=User::factory()->create();
       $user->createToken('developer-access')->plainTextToken;
       echo"{$token}"
       */

    }
}
