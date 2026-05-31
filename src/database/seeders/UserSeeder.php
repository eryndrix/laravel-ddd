<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds for roles table.
     */
    public function run(): void
    {
        $ownerRole = DB::table(table: 'roles')
            ->where(column: 'slug', operator: '=', value: 'admin')
            ->first();

        $now = Carbon::now();

        DB::table(table: 'users')->insert(values: [
            'avatar' => null,
            'name' => 'Test',
            'email' => 'admin@mail.ru',
            'email_verified_at' => $now,
            'is_active' => true,
            'role_id' => $ownerRole->id,
            'password' => Hash::make(value: '0>pzX;|3&CRoN8*U_'),
            'remember_token' => Str::random(length: 60),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
