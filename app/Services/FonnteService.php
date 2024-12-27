<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

// require_once 'vendor\autoload.php';

class FonnteService
{
    // protected $client;
    // protected $apiKey;
    // protected $baseUrl;

    public function sendMessage($target, $message)
    {
        // Ambil API Key dari file .env
        $apiKey = env('FONNTE_API_KEY');

        // URL API Fonnte
        $url = 'https://api.fonnte.com/send';

        $data = [
            'target' => $target,  // Nomor penerima
            'message' => $message, // Isi pesan
            // 'countryCode' => '62', // Kode negara, opsional
        ];

        // Kirimkan request POST ke Fonnte API
        $response = Http::withHeaders([
            'Authorization' => $apiKey, // Menambahkan Bearer Token
        ])->post($url, $data);

        // Periksa jika request berhasil atau ada error
        if ($response->successful()) {
            return $response->json();
        } else {
            return [
                'status' => 'error',
                'code' => $response->status(),
                'message' => $response->body()
            ];
        }
    }
}
