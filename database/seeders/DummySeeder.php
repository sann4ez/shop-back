<?php

namespace Database\Seeders;

use App\Models\User;
//use App\Models\Attribute;
//use App\Models\Page;
//use App\Models\Post;
//use App\Models\Order;
//use App\Models\Product;
//use App\Models\Term;
use Illuminate\Database\Seeder;
use Exception;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(TaxonomySeeder::class);
//        foreach (Term::whereIn('vocabulary', [Term::VOCABULARY_PRODUCT_CATEGORIES, Term::VOCABULARY_BRANDS])->get() as $term) {
//            try {
//                $term->addMediaFromUrl('https://picsum.photos/200/240')->toMediaCollection('logo');
//                $term->addMediaFromUrl('https://picsum.photos/460/460')->toMediaCollection('image');
//            } catch (Exception $e) {
//                //...
//            }
//        }
//
//        $this->call(EavSeeder::class);
//
//        Term::where('vocabulary', Term::VOCABULARY_PRODUCT_CATEGORIES)->get()->each(function (Term $term) {
//            $term->attrs()->sync(Attribute::all()->pluck('id')->toArray());
//        });
//
//        $this->command->warn('Products start...');
//        Product::factory()->count(10)->create();

        $this->command->warn('Users start...');
        User::factory()->count(20)->create();

//        $this->command->warn('Orders start...');
//        Order::factory()->count(100)->create();
//
//        $this->command->warn('Pages start...');
//        Page::factory()->count(5)->create();

        $this->command->info('DummySeeder all done!');
    }
}
