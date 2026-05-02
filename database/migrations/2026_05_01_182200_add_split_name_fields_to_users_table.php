<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_name');
            $table->string('suffix', 50)->nullable()->after('last_name');
        });

        DB::table('users')->orderBy('id')->chunkById(100, function ($users): void {
            foreach ($users as $user) {
                $parts = preg_split('/\s+/', trim((string) $user->name)) ?: [];
                $firstName = $parts[0] ?? null;
                $lastName = count($parts) > 1 ? $parts[count($parts) - 1] : null;
                $middleName = count($parts) > 2 ? implode(' ', array_slice($parts, 1, -1)) : null;

                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'first_name' => $firstName ?: 'Resident',
                        'middle_name' => $middleName,
                        'last_name' => $lastName ?: 'User',
                        'name' => trim(collect([$firstName, $middleName, $lastName])->filter()->implode(' ')),
                    ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->orderBy('id')->chunkById(100, function ($users): void {
            foreach ($users as $user) {
                $fullName = trim(collect([
                    $user->first_name,
                    $user->middle_name,
                    $user->last_name,
                    $user->suffix,
                ])->filter()->implode(' '));

                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'name' => $fullName !== '' ? $fullName : 'Resident User',
                    ]);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'middle_name', 'last_name', 'suffix']);
        });
    }
};
