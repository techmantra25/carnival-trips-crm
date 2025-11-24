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
        Schema::table('whatsapp_message_templates', function (Blueprint $table) {

            // Get all columns
            $columns = Schema::getColumnListing('whatsapp_message_templates');

            // Fields you want to keep
            $keep = ['id', 'created_at', 'updated_at'];

            // Drop all other fields
            foreach ($columns as $column) {
                if (!in_array($column, $keep)) {
                    $table->dropColumn($column);
                }
            }
        });

        // Re-add new fields
        Schema::table('whatsapp_message_templates', function (Blueprint $table) {

            $table->string('name')->nullable()->after('id');
            $table->string('language')->nullable()->after('name');
            $table->string('category')->nullable()->after('language');
            $table->string('temp_Id')->nullable()->after('category');
            $table->boolean('allow_category_change')->default(false)->after('temp_Id');
            $table->string('Status')->nullable()->after('allow_category_change');
            $table->longText('components')->nullable()->after('Status');
            $table->string('Sno')->nullable()->after('components');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse: simply drop newly added fields
        Schema::table('whatsapp_message_templates', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'language',
                'category',
                'temp_Id',
                'allow_category_change',
                'Status',
                'components',
                'Sno',
            ]);
        });
    }
};
