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
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number')->unique(); // Document number like 'PER-2/LGL/03/2023'
            $table->string('title', 500); // Document title
            $table->string('type'); // Document type
            $table->date('published_date'); // When document was published
            $table->date('effective_until'); // When document expires
            $table->string('status', 20)->default('Berlaku'); // Status: Berlaku, Tidak Berlaku
            $table->string('confidentiality', 50)->default('Internal Use'); // Public, Internal Use, Confidential
            $table->string('file_path')->nullable(); // Path to PDF file
            $table->string('file_name')->nullable(); // Original file name
            $table->string('revoked_by')->nullable(); // Document number that revoked this document
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->string('deleted_by', 50)->nullable();

            // Indexes for better performance
            $table->index(['status', 'published_date']);
            $table->index('number');
            $table->index('type');
        });

        // Pivot table for document relationships (revokes and changes)
        Schema::create('document_relationships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('source_document_id'); // The document that revokes/changes
            $table->uuid('target_document_id'); // The document being revoked/changed
            $table->string('relationship_type'); // 'revokes' or 'changes'
            $table->timestamps();
            $table->string('created_by', 50)->nullable();

            // Foreign key constraints
            $table->foreign('source_document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->foreign('target_document_id')->references('id')->on('documents')->onDelete('cascade');

            // Indexes
            $table->index(['source_document_id', 'relationship_type']);
            $table->index(['target_document_id', 'relationship_type']);
            
            // Ensure unique relationships
            $table->unique(['source_document_id', 'target_document_id', 'relationship_type'], 'unique_document_relationship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_relationships');
        Schema::dropIfExists('documents');
    }
};
