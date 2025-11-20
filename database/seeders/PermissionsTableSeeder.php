<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $readId = DB::table('permissions')->insertGetId([ 'name' => 'read_roles' ]);

        $writeId = DB::table('permissions')->insertGetId([ 'name' => 'write_roles', 'description' => 'Allows the creation, update, exclusion of a role and assignment to a user' ]);

        $adminId = Role::where('name', 'admin')->first()->id;

        DB::table('role_permissions')->insert([
            [
                'role_id' => $adminId,
                'permission_id' => $readId,
            ],
            [
                'role_id' => $adminId,
                'permission_id' => $writeId,
            ],
        ]);

    }
}
