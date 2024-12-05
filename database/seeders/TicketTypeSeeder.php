<?php

namespace Database\Seeders;

use App\Models\TicketType;
use Illuminate\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    private array $data = [
        [
            'name' => 'Protocol',
            'icon' => 'heroicon-o-check-circle',
            'color' => '#058A91',
            'is_default' => true
        ],
        [
            'name' => 'Content Creator',
            'icon' => 'heroicon-o-clipboard-list',
            'color' => '#b6c909',
            'is_default' => false
        ],
        [
            'name' => 'Event Organizer',
            'icon' => 'heroicon-o-x',
            'color' => '#DF4001',
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
            TicketType::firstOrCreate($item);
        }
    }
}
