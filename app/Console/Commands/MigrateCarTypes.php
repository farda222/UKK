<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Car;
use App\Models\Type;

class MigrateCarTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:car-types';



    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cars = Car::all();

        foreach ($cars as $car) {
            $type = Type::where('name', $car->type)->first();

            if ($type) {
                $car->type_id = $type->id;
                $car->save();
            }
        }

        $this->info('Car types migrated successfully.');
    }
}
