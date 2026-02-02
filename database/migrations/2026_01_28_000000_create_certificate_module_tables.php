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
        // 1. Update Events Table
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('has_certificate')->default(false)->after('status');
        });

        // 2. Update Participant Types Table
        Schema::table('participant_types', function (Blueprint $table) {
            $table->text('certificate_text')->nullable()->after('name');
        });

        // 3. Create Event Templates Table
        Schema::create('event_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('template_image'); // Path to background image
            $table->boolean('use_event_logo')->default(false);
            $table->timestamps();
        });

        // 4. Create Signatures Table
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('nip')->nullable();
            $table->string('jabatan');
            $table->string('signature_image')->nullable(); // Path to signature image
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 5. Create Certificate Reports Table
        Schema::create('certificate_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_participant_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->enum('status', ['pending', 'resolved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_reports');
        Schema::dropIfExists('signatures');
        Schema::dropIfExists('event_templates');

        Schema::table('participant_types', function (Blueprint $table) {
            $table->dropColumn('certificate_text');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('has_certificate');
        });
    }
};
