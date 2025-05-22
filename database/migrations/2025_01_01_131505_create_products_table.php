<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method creates the 'products' table with the specified columns and constraints.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(false);
            $table->bigInteger('price')->nullable(false)->default(0);
            $table->integer('stock')->nullable(false)->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('category_id')->nullable(false);
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('category_id') // Menentukan kolom yang akan dijadikan foreign key
                ->on('categories')       // Menentukan tabel referensi
                ->references('id')       // Menentukan kolom di tabel referensi (primary key)
                ->onDelete('restrict')    // Menentukan aksi saat data referensi dihapus
                ->onUpdate('cascade');   // Menentukan aksi saat data referensi diperbarui

        });

        /**
            Perbandingan Opsi
            Aksi	    onDelete/onUpdate       Efek
            cascade	    Menghapus/memperbarui	Data terkait ikut dihapus atau diperbarui secara otomatis.
            restrict	Mencegah	            Operasi dihentikan jika ada data terkait.
            set null	Menghapus/memperbarui	Foreign key di tabel anak diatur menjadi NULL.
            no action	Tidak ada aksi	Operasi dihentikan jika melanggar aturan referensial.
         */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
