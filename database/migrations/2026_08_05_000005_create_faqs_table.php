<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed initial FAQ data from existing static FAQs
        DB::table('faqs')->insert([
            [
                'question' => 'Berapa lama proses pengurusan Surat Keterangan Usaha (SKU) & Domisili?',
                'answer' => 'Proses pengurusan surat keterangan umum dapat diselesaikan pada hari yang sama (1 hari kerja) apabila persyaratan berkas fisik/KTP sudah lengkap diserahkan ke Balai Desa.',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Apakah ada biaya administrasi untuk pembuatan surat di balai desa?',
                'answer' => 'Seluruh pelayanan administrasi kependudukan dan surat pengantar di Balai Desa adalah 100% GRATIS tanpa dipungut biaya apapun.',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Bagaimana cara menyampaikan masukan atau pengaduan warga?',
                'answer' => 'Anda dapat mengisi form Aspirasi & Pengaduan Warga pada bagian bawah website ini. Pesan Anda akan langsung diterima oleh Operator.',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
