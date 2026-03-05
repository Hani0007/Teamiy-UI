<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustryTypeSeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
             "Information Technology",
    "Software Development",
    "Artificial Intelligence",
    "Cybersecurity",
    "Telecommunications",
    "Finance",
    "Banking",
    "Insurance",
    "FinTech",
    "Healthcare",
    "Pharmaceuticals",
    "Medical Devices",
    "Biotechnology",
    "Education",
    "E-Learning",
    "Training & Coaching",
    "Manufacturing",
    "Automotive",
    "Aerospace",
    "Electronics",
    "Construction",
    "Real Estate",
    "Architecture",
    "Interior Design",
    "Retail",
    "E-Commerce",
    "Wholesale",
    "Consumer Goods",
    "Logistics",
    "Transportation",
    "Supply Chain",
    "Warehousing",
    "Energy",
    "Renewable Energy",
    "Oil & Gas",
    "Utilities",
    "Agriculture",
    "Farming",
    "Food & Beverage",
    "Hospitality",
    "Travel & Tourism",
    "Restaurants",
    "Media & Entertainment",
    "Film Production",
    "Music",
    "Gaming",
    "Advertising",
    "Marketing",
    "Public Relations",
    "Design & Creative",
    "Legal Services",
    "Consulting",
    "Human Resources",
    "Staffing & Recruitment",
    "Government",
    "Public Sector",
    "Non-Profit",
    "NGO",
    "Environmental Services",
    "Waste Management",
    "Recycling",
    "Security Services",
    "Defense",
    "Sports & Fitness",
    "Wellness",
    "Beauty & Cosmetics",
    "Fashion & Apparel",
    "Textiles",
    "Jewelry",
    "Mining",
    "Metals",
    "Chemical Industry",
    "Research & Development",
    "Market Research",
    "Data Analytics",
    "Cloud Services",
    "Blockchain",
    "Cryptocurrency"
        ];

        foreach ($industries as $industry) {
            DB::table('industry_types')->insert([
                'name' => $industry,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
