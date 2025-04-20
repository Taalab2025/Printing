<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('user_role')->nullable()->after('user_id');
            $table->string('ip_address')->nullable()->after('user_role');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->string('method', 10)->nullable()->after('user_agent');
            $table->string('url')->nullable()->after('method');
            $table->string('route')->nullable()->after('url');
            $table->text('request_data')->nullable()->after('route');
            $table->integer('status_code')->nullable()->after('request_data');
        });
    }

    public function down()
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn([
                'user_role',
                'ip_address',
                'user_agent',
                'method',
                'url',
                'route',
                'request_data',
                'status_code',
            ]);
        });
    }
}
