<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AgreementTemplate;

class AgreementTemplateSeeder extends Seeder
{
    public function run(): void
    {
        AgreementTemplate::create([
            'name' => 'Standard Services Agreement',
            'content' => '<h2 class="text-2xl font-bold text-center mb-6">SERVICES AGREEMENT</h2>

<p class="mb-4">This Services Agreement (the "Agreement") is entered into on <strong>{{start_date}}</strong>, by and between:</p>

<div class="mb-4">
    <p><strong>Service Provider:</strong></p>
    <p>Admin / Current System User</p>
</div>

<div class="mb-4">
    <p><strong>Client:</strong></p>
    <p>Name: {{client_name}}</p>
    <p>Company: {{company_name}}</p>
    <p>Email: {{client_email}}</p>
</div>

<h3 class="text-xl font-bold mt-6 mb-2">1. Scope of Services</h3>
<p class="whitespace-pre-wrap">{{scope_of_work}}</p>

<h3 class="text-xl font-bold mt-6 mb-2">2. Term</h3>
<p>This Agreement shall commence on <strong>{{start_date}}</strong> and shall continue until <strong>{{end_date}}</strong>, unless earlier terminated.</p>

<h3 class="text-xl font-bold mt-6 mb-2">3. Compensation</h3>
<p>For the services rendered, the Client shall pay the Service Provider the total sum of <strong>{{formatted_price}}</strong>.</p>
<p><strong>Payment Terms:</strong> {{payment_terms}}</p>

<h3 class="text-xl font-bold mt-6 mb-2">4. Miscellaneous</h3>
<p>This Agreement contains the entire agreement between the parties relating to the subject matter hereof and supersedes any and all prior agreements.</p>'
        ]);
    }
}
