<?php

use App\Enums\UserRoles;
use App\Enums\UserStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('role')->default(UserRoles::STUDENT)->after('email');
            $table->integer('status')->default(UserStatus::ACTIVE)->after('role');
        });
    }

    public function down(): void
    {
        //
    }
};
