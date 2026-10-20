<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'summary_points')) {
                $table->json('summary_points')->nullable()->after('excerpt');
            }
            if (! Schema::hasColumn('posts', 'featured_image_credit')) {
                $table->string('featured_image_credit', 255)->nullable()->after('featured_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'summary_points')) {
                $table->dropColumn('summary_points');
            }
            if (Schema::hasColumn('posts', 'featured_image_credit')) {
                $table->dropColumn('featured_image_credit');
            }
        });
    }
};
