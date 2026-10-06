<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $groups = [
            'Dashboard' => ['dashboard.view'],
            'Category' => ['category.view', 'category.create', 'category.edit', 'category.delete'],
            'Subcategory' => ['subcategory.view', 'subcategory.create', 'subcategory.edit', 'subcategory.delete'],
            'Menu Item' => ['menu-item.view', 'menu-item.create', 'menu-item.edit', 'menu-item.delete'],
            'Add-On' => ['addon.view', 'addon.create', 'addon.edit', 'addon.delete'],
            'Order' => ['order.view', 'order.create', 'order.edit', 'order.delete'],
            'Client' => ['client.view', 'client.create', 'client.edit', 'client.delete'],
            'Branch' => ['branch.view', 'branch.create', 'branch.edit', 'branch.delete'],
            'User' => ['user.view', 'user.create', 'user.edit', 'user.delete'],
            'Role' => ['role.view', 'role.create', 'role.edit', 'role.delete'],
            'Permission' => ['permission.view', 'permission.create', 'permission.edit', 'permission.delete'],
            'Website Content' => ['website-content.view', 'website-content.edit'],
            'Contact Query' => ['contact-query.view', 'contact-query.edit', 'contact-query.delete'],
            'Setting' => ['setting.view', 'setting.edit'],
        ];

        foreach ($groups as $group => $names) {
            foreach ($names as $name) {
                Permission::query()->updateOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    ['group' => $group],
                );
            }
        }

        $superAdmin = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $allAccessManager = Role::query()->firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $allAccessManager->syncPermissions(Permission::all());

        $manager = Role::query()->firstOrCreate(['name' => 'Branch Manager', 'guard_name' => 'web']);
        $manager->syncPermissions(Permission::whereIn('name', [
            'dashboard.view',
            'category.view', 'category.create', 'category.edit',
            'subcategory.view', 'subcategory.create', 'subcategory.edit',
            'menu-item.view', 'menu-item.create', 'menu-item.edit',
            'addon.view', 'addon.create', 'addon.edit',
            'order.view', 'order.create', 'order.edit',
            'client.view', 'client.create', 'client.edit',
            'branch.view', 'user.view', 'user.create', 'user.edit',
            'website-content.view', 'website-content.edit',
            'contact-query.view', 'contact-query.edit',
            'setting.view',
        ])->get());
    }
}
