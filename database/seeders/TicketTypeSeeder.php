<?php

namespace Database\Seeders;

use App\Models\TicketType;
use Illuminate\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    private array $data = [
        [
            'name' => 'Protocol',
            'color' => 'rgba(32, 157, 182, 0.9)',
            'is_default' => true
        ],
        [
            'name' => 'Content Creator',
            'color' => 'rgba(136, 84, 208, 0.9)',
            'is_default' => false
        ],
        [
            'name' => 'Event Organizer',
            'color' => 'rgba(255, 178, 0, 0.9)',
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
