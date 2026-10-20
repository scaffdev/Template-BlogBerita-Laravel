<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kolom maksimal di posts (seri, sponsor, liveblog, co-author)
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'series_name')) {
                $table->string('series_name')->nullable()->after('slug');
            }
            if (! Schema::hasColumn('posts', 'series_order')) {
                $table->unsignedInteger('series_order')->nullable()->after('series_name');
            }
            if (! Schema::hasColumn('posts', 'co_authors')) {
                $table->json('co_authors')->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('posts', 'is_sponsored')) {
                $table->boolean('is_sponsored')->default(false)->after('is_headline');
            }
            if (! Schema::hasColumn('posts', 'sponsored_label')) {
                $table->string('sponsored_label')->nullable()->after('is_sponsored');
            }
            if (! Schema::hasColumn('posts', 'is_liveblog')) {
                $table->boolean('is_liveblog')->default(false)->after('is_sponsored');
            }
            if (! Schema::hasColumn('posts', 'likes_count')) {
                $table->unsignedBigInteger('likes_count')->default(0)->after('views_count');
            }
            if (! Schema::hasColumn('posts', 'bookmarks_count')) {
                $table->unsignedBigInteger('bookmarks_count')->default(0)->after('likes_count');
            }
        });

        // 2. Live updates untuk liveblog
        if (! Schema::hasTable('live_updates')) {
            Schema::create('live_updates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->text('content');
                $table->boolean('is_key_moment')->default(false);
                $table->timestamps();
                $table->index('post_id');
            });
        }

        // 3. Bookmark (simpan artikel)
        if (! Schema::hasTable('bookmarks')) {
            Schema::create('bookmarks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('post_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['user_id', 'post_id']);
            });
        }

        // 4. Like / suka artikel
        if (! Schema::hasTable('likes')) {
            Schema::create('likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('post_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['user_id', 'post_id']);
            });
        }

        // 5. Riwayat newsletter broadcast
        if (! Schema::hasTable('newsletter_logs')) {
            Schema::create('newsletter_logs', function (Blueprint $table) {
                $table->id();
                $table->string('subject');
                $table->text('body')->nullable();
                $table->unsignedInteger('recipients_count')->default(0);
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // 6. Laporan komentar (alasan lapor)
        if (! Schema::hasColumn('comments', 'report_count')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->unsignedInteger('report_count')->default(0)->after('status');
                $table->text('report_reason')->nullable()->after('report_count');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
        Schema::dropIfExists('bookmarks');
        Schema::dropIfExists('live_updates');
        Schema::dropIfExists('newsletter_logs');
        Schema::table('comments', function (Blueprint $table) {
            if (Schema::hasColumn('comments', 'report_count')) {
                $table->dropColumn(['report_count', 'report_reason']);
            }
        });
        Schema::table('posts', function (Blueprint $table) {
            foreach (['series_name', 'series_order', 'co_authors', 'is_sponsored', 'sponsored_label', 'is_liveblog', 'likes_count', 'bookmarks_count'] as $col) {
                if (Schema::hasColumn('posts', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
