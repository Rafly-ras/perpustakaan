<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Gateway API Configuration (FRD FR-NOTIF-01 & FR-NOTIF-02)
    |--------------------------------------------------------------------------
    |
    | Configuration for sending automated reminder messages & FIFO availability
    | notifications exclusively via WhatsApp Gateway.
    |
    */

    'api_url' => env('WA_GATEWAY_URL', 'https://api.whatsapp-gateway.local/v1/send'),

    'api_key' => env('WA_GATEWAY_API_KEY', ''),

    'timeout' => env('WA_GATEWAY_TIMEOUT', 15),

    'retry_attempts' => 3,

    'templates' => [
        'due_date_reminder' => "Halo *{name}*,\n\nPemutakhiran Sirkulasi Perpustakaan:\nBuku *{book_title}* (Kode: {barcode}) jatuh tempo pada *{due_date}*.\nMohon segera melakukan pengembalian di meja Admin sirkulasi.\n\nTerima kasih.",
        
        'fifo_available' => "Halo *{name}*,\n\nBuku yang Anda antre (*{book_title}*) telah tersedia di meja sirkulasi Perpustakaan!\nStatus reservasi Anda berada pada urutan *#1*.\nSilakan ambil buku tersebut dalam waktu 2x24 jam.\n\nTerima kasih.",
    ],
];
