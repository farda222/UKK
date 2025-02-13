<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Type;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = DB::table('cars')->select('type')->distinct()->pluck('type');

        foreach ($types as $type) {
            Type::create(['name' => $type]);
        }
    }
}
