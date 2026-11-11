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
    Schema::table('payments', function (Blueprint $table) {
        $table->decimal('inscription_fee', 8, 2)->default(0)->after('amount');
        $table->boolean('fee_waived')->default(false)->after('inscription_fee');
        $table->string('waived_reason')->nullable()->after('fee_waived');
    });
}

public function down()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->dropColumn(['inscription_fee', 'fee_waived', 'waived_reason']);
    });
}
};
