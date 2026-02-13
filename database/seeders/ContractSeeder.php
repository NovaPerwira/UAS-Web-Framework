<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Contract;
use App\Models\Client;
use Carbon\Carbon;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::take(3)->get();

        if ($clients->count() < 3) {
            // Ensure we have at least 3 clients if testing on fresh db
            Client::factory(3)->create();
            $clients = Client::take(3)->get();
        }

        // Contract 1: Web Development
        Contract::create([
            'client_id' => $clients[0]->id,
            'title' => 'E-Commerce Website Development Agreement',
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths(3),
            'contract_value' => 15000000,
            'status' => 'active',
            'scope_of_work' => "1. UI/UX Design for 5 main pages.\n2. Frontend development using React/Vue.\n3. Backend API integration.\n4. Payment gateway setup (Midtrans).",
            'timeline' => "Week 1-2: Design Phase\nWeek 3-6: Development\nWeek 7: Testing & QA\nWeek 8: Deployment",
            'payment_terms' => "DP 30% upon signing.\n30% after design approval.\n40% upon completion.",
            'revisions' => "Maximum of 3 major revision rounds during the design phase. Additional revisions charged at hourly rate.",
            'ownership_rights' => "Client owns full IP rights upon full payment. Provider retains right to showcase work in portfolio.",
            'warranty' => "30-day bug fix warranty post-launch.",
            'general_terms' => "This agreement is governed by the laws of Indonesia.",
            'content' => "This agreement outlines the terms for the development of an e-commerce platform..."
        ]);

        // Contract 2: Social Media Management
        Contract::create([
            'client_id' => $clients[1]->id,
            'title' => 'Social Media Management Retainer',
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => Carbon::now()->addMonths(5),
            'contract_value' => 5000000,
            'status' => 'active',
            'scope_of_work' => "1. 12 Instagram posts per month.\n2. Community management.\n3. Monthly analytics report.",
            'timeline' => "Ongoing monthly deliverables.",
            'payment_terms' => "Monthly invoice sent on the 1st of each month. Net 7 terms.",
            'revisions' => "1 round of edits per content batch.",
            'ownership_rights' => "Client owns all creative assets produced.",
            'warranty' => "N/A",
            'general_terms' => "Either party may terminate with 30 days written notice.",
            'content' => "Retainer agreement for social media marketing services..."
        ]);

        // Contract 3: SEO Optimization
        Contract::create([
            'client_id' => $clients[2]->id,
            'title' => 'SEO Optimization Project',
            'start_date' => Carbon::now()->addDays(5),
            'end_date' => Carbon::now()->addMonths(2),
            'contract_value' => 8500000,
            'status' => 'draft',
            'scope_of_work' => "1. Technical SEO Audit.\n2. On-page optimization for 20 pages.\n3. Backlink strategy.",
            'timeline' => "Month 1: Audit & Fixes\nMonth 2: Content Optimization",
            'payment_terms' => "50% upfront, 50% on completion.",
            'revisions' => "N/A",
            'ownership_rights' => "N/A",
            'warranty' => "N/A",
            'general_terms' => "Results may vary based on search engine algorithm changes.",
            'content' => "Proposal for SEO services to improve organic ranking..."
        ]);

    }
}
