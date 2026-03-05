<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('currencies')->truncate();

        $currencies = [
            ['name' => 'Euro', 'symbol' => '€', 'code' => 'EUR'],
            ['name' => 'Złoty', 'symbol' => 'Zł', 'code' => 'PLN'],
            ['name' => 'Franc', 'symbol' => 'CHF', 'code' => 'CHF'],
            ['name' => 'Pound Sterling', 'symbol' => '£', 'code' => 'GBP'],
            ['name' => 'Dollar', 'symbol' => '$', 'code' => 'USD'],
            ['name' => 'Koruna', 'symbol' => 'Kč', 'code' => 'CZK'],
            ['name' => 'Krone', 'symbol' => 'kr', 'code' => 'NOK'], // Norwegian Krone
            ['name' => 'Lari', 'symbol' => '₾', 'code' => 'GEL'],
            ['name' => 'Forint', 'symbol' => 'Ft', 'code' => 'HUF'],
            ['name' => 'Denar', 'symbol' => 'ден', 'code' => 'MKD'],
            ['name' => 'Leu', 'symbol' => 'lei', 'code' => 'RON'],
            ['name' => 'Ruble', 'symbol' => '₽', 'code' => 'RUB'],
            ['name' => 'Dinar', 'symbol' => 'din.', 'code' => 'RSD'], // Serbian Dinar
            ['name' => 'Krona', 'symbol' => 'kr', 'code' => 'SEK'], // Swedish Krona
            ['name' => 'Lek', 'symbol' => 'L', 'code' => 'ALL'],
            ['name' => 'Dram', 'symbol' => '֏', 'code' => 'AMD'],
            ['name' => 'Belarusian Ruble', 'symbol' => 'Br', 'code' => 'BYN'],
            ['name' => 'Convertible Mark', 'symbol' => 'KM', 'code' => 'BAM'],
        ];

        DB::table('currencies')->insert($currencies);
    }
}
