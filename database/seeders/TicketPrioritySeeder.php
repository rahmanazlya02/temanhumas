<?php

namespace Database\Seeders;

use App\Models\TicketPriority;
use Illuminate\Database\Seeder;

class TicketPrioritySeeder extends Seeder
{
    private array $data = [
        [
            'name' => 'Low',
            'color' => '#009C00',
            'is_default' => false
        ],
        [
            'name' => 'Normal',
            'color' => '#D4BA10',
            'is_default' => true
        ],
        [
            'name' => 'High',
            'color' => '#DB000F',
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
