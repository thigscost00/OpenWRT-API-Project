<?php

use Illuminate\Database\Seeder;
use App\Configuration;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $files = Storage::disk('local')->get('seed/config_files.json');
        $files = json_decode($files);

        foreach ($files as $fn) {
            Configuration::create([
                'name' => $fn
            ]);
        }
    }
}
