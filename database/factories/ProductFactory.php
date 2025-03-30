<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $products = [
            ['Cash Transport Box', 'Secure, tamper-proof cash transport containers for microfinance operations.', 50, 5, 100],
            ['Mobile Money Agent Kit', 'POS device, ledger book, and ID scanner for mobile money agents.', 100, 10, 200],
            ['Rural ATM Booth', 'Pre-fabricated ATM kiosks for remote banking access.', 6000, 150, 50],
            ['Loan Application Stationery Kit', 'Printed loan forms, pens, and folders for applications.', 5, 0.5, 1000],
            ['KYC Verification Toolkit', 'Fingerprint scanner, ID card reader, and verification forms.', 250, 20, 800],
            ['Financial Literacy Booklets', 'Printed guides for financial education programs.', 7, 1, 500],
            ['Loan Officer Field Kit', 'Backpack, tablet case, portable printer, and forms.', 120, 5, 150],
            ['Business Startup Kit', 'Basic tools and materials for small businesses.', 60, 5, 300],
            ['Community Meeting Kit', 'PA system, presentation boards, and meeting supplies.', 80, 10, 400],
            ['Farming Essentials Pack', 'Seeds, fertilizers, and basic farming tools.', 30, 8, 600],
            ['Livestock Starter Pack', 'Set of chickens, feed, and vaccination kit.', 180, 40, 70],
            ['Tailoring Business Kit', 'Sewing machine, fabric, and thread set.', 220, 20, 80],
            ['Food Cart Kit', 'Pre-built food stall, cooking utensils, and gas burner.', 350, 50, 90],
            ['Solar Power System', 'Solar panels, battery storage, and LED lights.', 280, 15, 100],
            ['Healthcare Support Pack', 'Basic medical kit with medicines and first aid tools.', 60, 5, 200],
            ['School Supply Set', 'Backpack, notebooks, stationery, and uniforms.', 40, 3, 300],
            ['Mobile Banking Van Equipment', 'ATM, safe, counters, and signage for mobile banking.', 18000, 2500, 10],
            ['POS Terminal Package', 'POS device, receipt printer, and card reader.', 180, 3, 120],
            ['Rural Banking Kiosk Kit', 'Prefabricated kiosk, counter, and signage.', 5500, 600, 30],
            ['Microfinance Transaction Terminal', 'Digital handheld device for tracking loans and payments.', 1200, 10, 40]
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
