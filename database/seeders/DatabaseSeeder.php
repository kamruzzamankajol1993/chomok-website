<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::query()->firstOrCreate(
            ['code' => 'HO'],
            [
                'name' => 'Head Office',
                'address' => '394 Brothers Mansion, East Rampur, Halishahar, Chittagong.',
                'city' => 'Chittagong',
                'phone' => '+880 XXX-XXXXXX',
            ],
        );

        Setting::current();
        $this->call(RolePermissionSeeder::class);

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@chomok.com'],
            [
                'name' => 'Admin User',
                'phone' => '01700000000',
                'password' => Hash::make('password'),
                'branch_id' => $branch->id,
                'all_branch_access' => true,
                'status' => 'active',
            ],
        );

        $admin->syncRoles(['Super Admin']);

        $this->call(DemoDataSeeder::class);
        $this->call(WebsiteContentSeeder::class);
    }
}
