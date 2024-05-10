<?php

use App\Models\Chirp;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Chirp::query()->withoutGlobalScopes()->eachById(function (Chirp $chirp) {
            $chirp->update(['content' => $chirp->message]);
        });
        Schema::dropColumns('chirps', 'message');
    }
};
