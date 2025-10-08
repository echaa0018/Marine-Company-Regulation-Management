<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asset', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('holder_type')->default('unit');
            $table->string('holder')->nullable();
            $table->string('name');
            $table->string('status',10)->nullable();
            $table->string('tag')->nullable();
            $table->string('old_tag')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('sap_number')->nullable();
            $table->text('description')->nullable();
            $table->string('bpo_number')->nullable();
            $table->string('contract_number')->nullable();
            $table->string('vendor')->nullable();
            $table->string('location_code')->nullable();
            $table->text('location')->nullable();
            $table->text('loc_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->string('deleted_by', 50)->nullable();
        });

        Schema::create('asset_import', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('notes')->nullable();
            $table->string('status',10)->nullable();
            $table->string('approver')->nullable();
            $table->text('reject_reason')->nullable();
            $table->string('rejected_by', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->string('deleted_by', 50)->nullable();
        });

        Schema::create('asset_import_datatemp', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('import_id');
            $table->string('notes')->nullable();
            $table->integer('row_number');
            $table->string('type');
            $table->string('holder_type')->default('unit');
            $table->string('holder')->nullable();
            $table->string('name');
            $table->string('status',10)->nullable();
            $table->string('tag')->nullable();
            $table->string('old_tag')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('sap_number')->nullable();
            $table->text('description')->nullable();
            $table->string('bpo_number')->nullable();
            $table->string('contract_number')->nullable();
            $table->string('vendor')->nullable();
            $table->string('location_code')->nullable();
            $table->text('location')->nullable();
            $table->text('loc_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->string('deleted_by', 50)->nullable();
        });

        Schema::create('asset_import_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('activity');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by', 50)->nullable();
        });

        Schema::create('location', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('code');
            $table->string('parent_code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('tag')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->string('deleted_by', 50)->nullable();
        });

        Schema::create('asset_holder', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('asset_tag');
            $table->string('holder_type');
            $table->string('holder_code');//unit_id atau nik
            $table->dateTime('begin_date')->default(now());
            $table->dateTime('end_date')->default('9999-12-31');
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->string('deleted_by', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset');
        Schema::dropIfExists('asset_import');
        Schema::dropIfExists('asset_import_datatemp');
        Schema::dropIfExists('location');
        Schema::dropIfExists('asset_holder');
    }
};
