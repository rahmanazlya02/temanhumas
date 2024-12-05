<?php

namespace Database\Seeders;

use App\Models\TicketStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketStatusSeeder extends Seeder
{
    private array $data = [
        [
            'name' => 'Todo',
            'color' => '#1bb4e7',
            'is_default' => true,
            'order' => 1
        ],
        [
            'name' => 'On progress',
            'color' => '#da7923',
            'is_default' => false,
            'order' => 2
        ],
        [
            'name' => 'Done',
            'color' => '#00c100',
            'is_default' => false,
            'order' => 3
        ],
        [
            'name' => 'Approved',
            'color' => '#058a91',
            'is_default' => false,
            'order' => 4
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
            TicketStatus::firstOrCreate($item);
        }
    }
}
