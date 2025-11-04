<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PrimarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Artisan::call('storage:link');
        \Artisan::call('lte3:link');

//        $this->call(TimezonesSeeder::class);
//        $this->call(CurrenciesSeeder::class);
//        $this->call(LocalesSeeder::class);
//        $this->call(ContinentsSeeder::class);
//        $this->call(CountriesSeeder::class);
//
//        $this->call(DomainsSeeder::class);
//        $this->call(VariablesSeeder::class);

        $this->call(UserSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
//        $this->call(PagesSeeder::class);

        // $this->call(MenuSeeder::class);
//        $this->call(SuppliersSeeder::class);
//        $this->call(WarehouseSeeder::class);
//        $this->call(ItemsSeeder::class);
//        $this->call(SeoSeeder::class);
    }
}
