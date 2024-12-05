<?php

namespace Database\Seeders;

use App\Models\ProjectStatus;
use Illuminate\Database\Seeder;

class ProjectStatusSeeder extends Seeder
{
    private array $data = [
        [
            'name' => 'On Progress',
            'color' => '#E98127',
            'is_default' => true,
            'order' => 1,
        ],
        [
            'name' => 'Completed',
            'color' => '#00AB00',
            'is_default' => false,
            'order' => 2,
        ],
        [
            'name' => 'Coming Soon',
            'color' => '#767070',
            'is_default' => false,
            'order' => 3,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $item) {
            ProjectStatus::firstOrCreate($item);
        }
    }
}
