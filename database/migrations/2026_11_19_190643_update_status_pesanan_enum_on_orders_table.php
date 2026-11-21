
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE orders
            MODIFY status_pesanan ENUM(
                'menunggu_konfirmasi',
                'diterima',
                'ditolak',
                'diproses',
                'siap_diantar',
                'selesai',
                'dibatalkan'
            ) NOT NULL DEFAULT 'menunggu_konfirmasi'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE orders
            MODIFY status_pesanan ENUM(
                'menunggu_konfirmasi',
                'diterima',
                'ditolak',
                'diproses',
                'siap_diambil',
                'selesai',
                'dibatalkan'
            ) NOT NULL DEFAULT 'menunggu_konfirmasi'
        ");
    }
};
