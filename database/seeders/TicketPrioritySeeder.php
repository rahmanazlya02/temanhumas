<?php

namespace Database\Seeders;

use App\Models\TicketPriority;
use Illuminate\Database\Seeder;

class TicketPrioritySeeder extends Seeder
{
    private array $data = [
        [
            'name' => 'Low',
            'color' => 'rgba(72, 201, 117, 0.9)',
            'is_default' => false
        ],
        [
            'name' => 'Normal',
            'color' => 'rgba(255, 204, 0, 0.9)',
            'is_default' => true
        ],
        [
            'name' => 'High',
            'color' => 'rgba(204, 36, 41, 0.9)',
            'is_default' => false
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
            TicketPriority::firstOrCreate($item);
        }
    }
}
