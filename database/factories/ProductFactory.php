<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $products = [
            ['Money Transport Service', 'Secure transport of cash for loan disbursements and collections.', 0, 0, 100],
            ['Mobile Money Service', 'Digital transactions via M-Pesa, bKash, etc.', 0, 0, 200],
            ['ATM Deployment', 'Installation of ATMs in rural areas.', 5000, 100, 50],
            ['Loan Application Documents', 'Printed forms for microfinance applications.', 2, 0.1, 1000],
            ['KYC Verification Documents', 'ID verification forms and reports.', 3, 0.2, 800],
            ['Financial Literacy Training Materials', 'Booklets and guides for financial education.', 5, 0.5, 500],
            ['Loan Officer Travel Kit', 'Essentials for field officers (bag, tablet, forms).', 100, 3, 150],
            ['Business Education Sessions', 'Consulting and training for small businesses.', 50, 0, 300],
            ['Community Meeting Facilitation', 'Resources for group financial literacy meetings.', 30, 0, 400],
            ['Seeds and Fertilizers', 'Agricultural inputs for farmer microloans.', 20, 5, 600],
            ['Livestock for Farmers', 'Chickens, goats, or cows for agricultural projects.', 150, 30, 70],
            ['Sewing Machine for Entrepreneurs', 'Support for tailoring microbusinesses.', 200, 15, 80],
            ['Food Cart for Small Vendors', 'Mobile food stall setup.', 300, 40, 90],
            ['Solar Panel Kits', 'Affordable energy solutions for small businesses.', 250, 10, 100],
            ['Medical Support Package', 'Medicines and basic health devices.', 50, 3, 200],
            ['School Supplies Loan Package', 'Notebooks, bags, and uniforms for students.', 30, 2, 300],
            ['Mobile Banking Van', 'Vehicle setup for rural banking.', 15000, 2000, 10],
            ['POS Machine for Merchants', 'Point-of-sale devices for digital payments.', 150, 1.5, 120],
            ['Banking Kiosk Setup', 'Mini banking stations for rural areas.', 5000, 500, 30],
            ['Loan Management Software', 'Digital tools for tracking microfinance transactions.', 1000, 0, 40]
        ];

        $product = $this->faker->randomElement($products);

        return [
            'name' => $product[0],
            'description' => $product[1],
            'price' => $product[2],
            'weight' => $product[3],
            'stock' => $product[4]
        ];
    }
}
