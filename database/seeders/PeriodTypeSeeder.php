<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PeriodType;

class PeriodTypeSeeder extends Seeder
{
    public function run(): void
    {
        PeriodType::insert([
            [
                'name' => 'Trimestre',
                'period_count' => 3,
            ],
            [
                'name' => 'Semestre',
                'period_count' => 2,
            ],
        ]);
    }
}
