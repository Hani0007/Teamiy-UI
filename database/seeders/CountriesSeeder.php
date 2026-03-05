<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('countries')->truncate();

        $countries = [

            ['name'=>'Afghanistan','code'=>'AF','iso3'=>'AFG','currency_code'=>'AFN','currency_symbol'=>'؋','currency_name'=>'Afghan Afghani'],
            ['name'=>'Albania','code'=>'AL','iso3'=>'ALB','currency_code'=>'ALL','currency_symbol'=>'L','currency_name'=>'Albanian Lek'],
            ['name'=>'Algeria','code'=>'DZ','iso3'=>'DZA','currency_code'=>'DZD','currency_symbol'=>'د.ج','currency_name'=>'Algerian Dinar'],
            ['name'=>'Andorra','code'=>'AD','iso3'=>'AND','currency_code'=>'EUR','currency_symbol'=>'€','currency_name'=>'Euro'],
            ['name'=>'Angola','code'=>'AO','iso3'=>'AGO','currency_code'=>'AOA','currency_symbol'=>'Kz','currency_name'=>'Angolan Kwanza'],
            ['name'=>'Antigua and Barbuda','code'=>'AG','iso3'=>'ATG','currency_code'=>'XCD','currency_symbol'=>'$','currency_name'=>'East Caribbean Dollar'],
            ['name'=>'Argentina','code'=>'AR','iso3'=>'ARG','currency_code'=>'ARS','currency_symbol'=>'$','currency_name'=>'Argentine Peso'],
            ['name'=>'Armenia','code'=>'AM','iso3'=>'ARM','currency_code'=>'AMD','currency_symbol'=>'֏','currency_name'=>'Armenian Dram'],
            ['name'=>'Australia','code'=>'AU','iso3'=>'AUS','currency_code'=>'AUD','currency_symbol'=>'$','currency_name'=>'Australian Dollar'],
            ['name'=>'Austria','code'=>'AT','iso3'=>'AUT','currency_code'=>'EUR','currency_symbol'=>'€','currency_name'=>'Euro'],
            ['name'=>'Azerbaijan','code'=>'AZ','iso3'=>'AZE','currency_code'=>'AZN','currency_symbol'=>'₼','currency_name'=>'Azerbaijani Manat'],

            ['name'=>'Bahamas','code'=>'BS','iso3'=>'BHS','currency_code'=>'BSD','currency_symbol'=>'$','currency_name'=>'Bahamian Dollar'],
            ['name'=>'Bahrain','code'=>'BH','iso3'=>'BHR','currency_code'=>'BHD','currency_symbol'=>'ب.د','currency_name'=>'Bahraini Dinar'],
            ['name'=>'Bangladesh','code'=>'BD','iso3'=>'BGD','currency_code'=>'BDT','currency_symbol'=>'৳','currency_name'=>'Bangladeshi Taka'],
            ['name'=>'Barbados','code'=>'BB','iso3'=>'BRB','currency_code'=>'BBD','currency_symbol'=>'$','currency_name'=>'Barbadian Dollar'],
            ['name'=>'Belarus','code'=>'BY','iso3'=>'BLR','currency_code'=>'BYN','currency_symbol'=>'Br','currency_name'=>'Belarusian Ruble'],
            ['name'=>'Belgium','code'=>'BE','iso3'=>'BEL','currency_code'=>'EUR','currency_symbol'=>'€','currency_name'=>'Euro'],
            ['name'=>'Belize','code'=>'BZ','iso3'=>'BLZ','currency_code'=>'BZD','currency_symbol'=>'$','currency_name'=>'Belize Dollar'],
            ['name'=>'Benin','code'=>'BJ','iso3'=>'BEN','currency_code'=>'XOF','currency_symbol'=>'CFA','currency_name'=>'West African CFA Franc'],
            ['name'=>'Bhutan','code'=>'BT','iso3'=>'BTN','currency_code'=>'BTN','currency_symbol'=>'Nu.','currency_name'=>'Bhutanese Ngultrum'],
            ['name'=>'Bolivia','code'=>'BO','iso3'=>'BOL','currency_code'=>'BOB','currency_symbol'=>'Bs.','currency_name'=>'Bolivian Boliviano'],

            ['name'=>'Brazil','code'=>'BR','iso3'=>'BRA','currency_code'=>'BRL','currency_symbol'=>'R$','currency_name'=>'Brazilian Real'],
            ['name'=>'Bulgaria','code'=>'BG','iso3'=>'BGR','currency_code'=>'BGN','currency_symbol'=>'лв','currency_name'=>'Bulgarian Lev'],
            ['name'=>'Cambodia','code'=>'KH','iso3'=>'KHM','currency_code'=>'KHR','currency_symbol'=>'៛','currency_name'=>'Cambodian Riel'],
            ['name'=>'Cameroon','code'=>'CM','iso3'=>'CMR','currency_code'=>'XAF','currency_symbol'=>'CFA','currency_name'=>'Central African CFA Franc'],
            ['name'=>'Canada','code'=>'CA','iso3'=>'CAN','currency_code'=>'CAD','currency_symbol'=>'$','currency_name'=>'Canadian Dollar'],
            ['name'=>'Chile','code'=>'CL','iso3'=>'CHL','currency_code'=>'CLP','currency_symbol'=>'$','currency_name'=>'Chilean Peso'],
            ['name'=>'China','code'=>'CN','iso3'=>'CHN','currency_code'=>'CNY','currency_symbol'=>'¥','currency_name'=>'Chinese Yuan'],
            ['name'=>'Colombia','code'=>'CO','iso3'=>'COL','currency_code'=>'COP','currency_symbol'=>'$','currency_name'=>'Colombian Peso'],
            ['name'=>'Costa Rica','code'=>'CR','iso3'=>'CRI','currency_code'=>'CRC','currency_symbol'=>'₡','currency_name'=>'Costa Rican Colón'],
            ['name'=>'Croatia','code'=>'HR','iso3'=>'HRV','currency_code'=>'EUR','currency_symbol'=>'€','currency_name'=>'Euro'],

            ['name'=>'Czech Republic','code'=>'CZ','iso3'=>'CZE','currency_code'=>'CZK','currency_symbol'=>'Kč','currency_name'=>'Czech Koruna'],
            ['name'=>'Denmark','code'=>'DK','iso3'=>'DNK','currency_code'=>'DKK','currency_symbol'=>'kr','currency_name'=>'Danish Krone'],
            ['name'=>'Egypt','code'=>'EG','iso3'=>'EGY','currency_code'=>'EGP','currency_symbol'=>'£','currency_name'=>'Egyptian Pound'],
            ['name'=>'Finland','code'=>'FI','iso3'=>'FIN','currency_code'=>'EUR','currency_symbol'=>'€','currency_name'=>'Euro'],
            ['name'=>'France','code'=>'FR','iso3'=>'FRA','currency_code'=>'EUR','currency_symbol'=>'€','currency_name'=>'Euro'],
            ['name'=>'Germany','code'=>'DE','iso3'=>'DEU','currency_code'=>'EUR','currency_symbol'=>'€','currency_name'=>'Euro'],
            ['name'=>'Greece','code'=>'GR','iso3'=>'GRC','currency_code'=>'EUR','currency_symbol'=>'€','currency_name'=>'Euro'],
            ['name'=>'Hungary','code'=>'HU','iso3'=>'HUN','currency_code'=>'HUF','currency_symbol'=>'Ft','currency_name'=>'Hungarian Forint'],
            ['name'=>'Iceland','code'=>'IS','iso3'=>'ISL','currency_code'=>'ISK','currency_symbol'=>'kr','currency_name'=>'Icelandic Króna'],
            ['name'=>'India','code'=>'IN','iso3'=>'IND','currency_code'=>'INR','currency_symbol'=>'₹','currency_name'=>'Indian Rupee'],

            ['name'=>'Indonesia','code'=>'ID','iso3'=>'IDN','currency_code'=>'IDR','currency_symbol'=>'Rp','currency_name'=>'Indonesian Rupiah'],
            ['name'=>'Iran','code'=>'IR','iso3'=>'IRN','currency_code'=>'IRR','currency_symbol'=>'﷼','currency_name'=>'Iranian Rial'],
            ['name'=>'Iraq','code'=>'IQ','iso3'=>'IRQ','currency_code'=>'IQD','currency_symbol'=>'ع.د','currency_name'=>'Iraqi Dinar'],
            ['name'=>'Ireland','code'=>'IE','iso3'=>'IRL','currency_code'=>'EUR','currency_symbol'=>'€','currency_name'=>'Euro'],
            ['name'=>'Israel','code'=>'IL','iso3'=>'ISR','currency_code'=>'ILS','currency_symbol'=>'₪','currency_name'=>'Israeli New Shekel'],
            ['name'=>'Italy','code'=>'IT','iso3'=>'ITA','currency_code'=>'EUR','currency_symbol'=>'€','currency_name'=>'Euro'],
            ['name'=>'Japan','code'=>'JP','iso3'=>'JPN','currency_code'=>'JPY','currency_symbol'=>'¥','currency_name'=>'Japanese Yen'],
            ['name'=>'Jordan','code'=>'JO','iso3'=>'JOR','currency_code'=>'JOD','currency_symbol'=>'د.ا','currency_name'=>'Jordanian Dinar'],
            ['name'=>'Kenya','code'=>'KE','iso3'=>'KEN','currency_code'=>'KES','currency_symbol'=>'Sh','currency_name'=>'Kenyan Shilling'],
            ['name'=>'Kuwait','code'=>'KW','iso3'=>'KWT','currency_code'=>'KWD','currency_symbol'=>'د.ك','currency_name'=>'Kuwaiti Dinar'],

            ['name'=>'Malaysia','code'=>'MY','iso3'=>'MYS','currency_code'=>'MYR','currency_symbol'=>'RM','currency_name'=>'Malaysian Ringgit'],
            ['name'=>'Mexico','code'=>'MX','iso3'=>'MEX','currency_code'=>'MXN','currency_symbol'=>'$','currency_name'=>'Mexican Peso'],
            ['name'=>'Netherlands','code'=>'NL','iso3'=>'NLD','currency_code'=>'EUR','currency_symbol'=>'€','currency_name'=>'Euro'],
            ['name'=>'New Zealand','code'=>'NZ','iso3'=>'NZL','currency_code'=>'NZD','currency_symbol'=>'$','currency_name'=>'New Zealand Dollar'],
            ['name'=>'Nigeria','code'=>'NG','iso3'=>'NGA','currency_code'=>'NGN','currency_symbol'=>'₦','currency_name'=>'Nigerian Naira'],
            ['name'=>'Norway','code'=>'NO','iso3'=>'NOR','currency_code'=>'NOK','currency_symbol'=>'kr','currency_name'=>'Norwegian Krone'],
            ['name'=>'Pakistan','code'=>'PK','iso3'=>'PAK','currency_code'=>'PKR','currency_symbol'=>'₨','currency_name'=>'Pakistani Rupee'],
            ['name'=>'Philippines','code'=>'PH','iso3'=>'PHL','currency_code'=>'PHP','currency_symbol'=>'₱','currency_name'=>'Philippine Peso'],
            ['name'=>'Poland','code'=>'PL','iso3'=>'POL','currency_code'=>'PLN','currency_symbol'=>'zł','currency_name'=>'Polish Złoty'],
            ['name'=>'Portugal','code'=>'PT','iso3'=>'PRT','currency_code'=>'EUR','currency_symbol'=>'€','currency_name'=>'Euro'],

            ['name'=>'Qatar','code'=>'QA','iso3'=>'QAT','currency_code'=>'QAR','currency_symbol'=>'﷼','currency_name'=>'Qatari Riyal'],
            ['name'=>'Romania','code'=>'RO','iso3'=>'ROU','currency_code'=>'RON','currency_symbol'=>'lei','currency_name'=>'Romanian Leu'],
            ['name'=>'Russia','code'=>'RU','iso3'=>'RUS','currency_code'=>'RUB','currency_symbol'=>'₽','currency_name'=>'Russian Ruble'],
            ['name'=>'Saudi Arabia','code'=>'SA','iso3'=>'SAU','currency_code'=>'SAR','currency_symbol'=>'﷼','currency_name'=>'Saudi Riyal'],
            ['name'=>'Singapore','code'=>'SG','iso3'=>'SGP','currency_code'=>'SGD','currency_symbol'=>'$','currency_name'=>'Singapore Dollar'],
            ['name'=>'South Africa','code'=>'ZA','iso3'=>'ZAF','currency_code'=>'ZAR','currency_symbol'=>'R','currency_name'=>'South African Rand'],
            ['name'=>'South Korea','code'=>'KR','iso3'=>'KOR','currency_code'=>'KRW','currency_symbol'=>'₩','currency_name'=>'South Korean Won'],
            ['name'=>'Spain','code'=>'ES','iso3'=>'ESP','currency_code'=>'EUR','currency_symbol'=>'€','currency_name'=>'Euro'],
            ['name'=>'Sri Lanka','code'=>'LK','iso3'=>'LKA','currency_code'=>'LKR','currency_symbol'=>'Rs','currency_name'=>'Sri Lankan Rupee'],
            ['name'=>'Sweden','code'=>'SE','iso3'=>'SWE','currency_code'=>'SEK','currency_symbol'=>'kr','currency_name'=>'Swedish Krona'],
            ['name'=>'Switzerland','code'=>'CH','iso3'=>'CHE','currency_code'=>'CHF','currency_symbol'=>'CHF','currency_name'=>'Swiss Franc'],

            ['name'=>'Thailand','code'=>'TH','iso3'=>'THA','currency_code'=>'THB','currency_symbol'=>'฿','currency_name'=>'Thai Baht'],
            ['name'=>'Turkey','code'=>'TR','iso3'=>'TUR','currency_code'=>'TRY','currency_symbol'=>'₺','currency_name'=>'Turkish Lira'],
            ['name'=>'Ukraine','code'=>'UA','iso3'=>'UKR','currency_code'=>'UAH','currency_symbol'=>'₴','currency_name'=>'Ukrainian Hryvnia'],
            ['name'=>'United Arab Emirates','code'=>'AE','iso3'=>'ARE','currency_code'=>'AED','currency_symbol'=>'د.إ','currency_name'=>'UAE Dirham'],
            ['name'=>'United Kingdom','code'=>'GB','iso3'=>'GBR','currency_code'=>'GBP','currency_symbol'=>'£','currency_name'=>'British Pound Sterling'],
            ['name'=>'United States','code'=>'US','iso3'=>'USA','currency_code'=>'USD','currency_symbol'=>'$','currency_name'=>'United States Dollar'],
            ['name'=>'Vietnam','code'=>'VN','iso3'=>'VNM','currency_code'=>'VND','currency_symbol'=>'₫','currency_name'=>'Vietnamese Dong'],
            ['name'=>'Zimbabwe','code'=>'ZW','iso3'=>'ZWE','currency_code'=>'ZWL','currency_symbol'=>'$','currency_name'=>'Zimbabwean Dollar'],

        ];

        $callingCodes = [
            'AF' => '+93',
            'AL' => '+355',
            'DZ' => '+213',
            'AD' => '+376',
            'AO' => '+244',
            'AG' => '+1-268',
            'AR' => '+54',
            'AM' => '+374',
            'AU' => '+61',
            'AT' => '+43',
            'AZ' => '+994',

            'BS' => '+1-242',
            'BH' => '+973',
            'BD' => '+880',
            'BB' => '+1-246',
            'BY' => '+375',
            'BE' => '+32',
            'BZ' => '+501',
            'BJ' => '+229',
            'BT' => '+975',
            'BO' => '+591',

            'BR' => '+55',
            'BG' => '+359',
            'KH' => '+855',
            'CM' => '+237',
            'CA' => '+1',
            'CL' => '+56',
            'CN' => '+86',
            'CO' => '+57',
            'CR' => '+506',
            'HR' => '+385',

            'CZ' => '+420',
            'DK' => '+45',
            'EG' => '+20',
            'FI' => '+358',
            'FR' => '+33',
            'DE' => '+49',
            'GR' => '+30',
            'HU' => '+36',
            'IS' => '+354',
            'IN' => '+91',

            'ID' => '+62',
            'IR' => '+98',
            'IQ' => '+964',
            'IE' => '+353',
            'IL' => '+972',
            'IT' => '+39',
            'JP' => '+81',
            'JO' => '+962',
            'KE' => '+254',
            'KW' => '+965',

            'MY' => '+60',
            'MX' => '+52',
            'NL' => '+31',
            'NZ' => '+64',
            'NG' => '+234',
            'NO' => '+47',
            'PK' => '+92',
            'PH' => '+63',
            'PL' => '+48',
            'PT' => '+351',

            'QA' => '+974',
            'RO' => '+40',
            'RU' => '+7',
            'SA' => '+966',
            'SG' => '+65',
            'ZA' => '+27',
            'KR' => '+82',
            'ES' => '+34',
            'LK' => '+94',
            'SE' => '+46',
            'CH' => '+41',

            'TH' => '+66',
            'TR' => '+90',
            'UA' => '+380',
            'AE' => '+971',
            'GB' => '+44',
            'US' => '+1',
            'VN' => '+84',
            'ZW' => '+263',
        ];

        $countries = array_map(function ($country) use ($callingCodes) {
            $country['country_code'] = $callingCodes[$country['code']] ?? null;
            return $country;
        }, $countries);

        DB::table('countries')->insert($countries);
    }
}
