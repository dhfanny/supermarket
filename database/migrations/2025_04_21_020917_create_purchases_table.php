<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            // Optional member_id (nullable)
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('set null');
            $table->integer('total_price'); // untuk total harga seluruh produk
            $table->date('purchase_date');
		    $table->unsignedInteger('diskon_poin')->default(0);//unsignedInteger-> hanya bisa menyimpan nilai positif
		    $table->unsignedInteger('total_bayar');
	        $table->unsignedInteger('kembalian')->default(0);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
