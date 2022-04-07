<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'add product']);
        Permission::create(['name' => 'delete product']);
        Permission::create(['name' => 'edit product']);
        Permission::create(['name' => 'publish product']);

        Permission::create(['name' => 'add category']);
        Permission::create(['name' => 'delete category']);
        Permission::create(['name' => 'edit category']);
        //order
        Permission::create(['name' => 'index order']);
        Permission::create(['name' => 'add order']);
        Permission::create(['name' => 'show order']);
        Permission::create(['name' => 'delete order']);
        Permission::create(['name' => 'edit order']);
        Permission::create(['name' => 'change order status']);
        //cart
        Permission::create(['name' => 'add cart']);
        Permission::create(['name' => 'delete cart']);
        Permission::create(['name' => 'edit cart']);
        // add city or governorate
        Permission::create(['name' => 'add location']);
        Permission::create(['name' => 'delete location']);
        Permission::create(['name' => 'edit location']);

        Permission::create(['name' => 'index roles']);
        Permission::create(['name' => 'add role']);
        Permission::create(['name' => 'delete role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'assign role']);

        Permission::create(['name' => 'index permission']);
        Permission::create(['name' => 'add permission']);
        Permission::create(['name' => 'delete permission']);
        Permission::create(['name' => 'edit permission']);
        Permission::create(['name' => 'assign permission']);

        Permission::create(['name' => 'add company']);
        Permission::create(['name' => 'delete company']);
        Permission::create(['name' => 'edit company']);
        Permission::create(['name' => 'assign company manager']);



        // this can be done as separate statements
        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo('edit articles');

        // or may be done by chaining
        $role = Role::create(['name' => 'moderator'])
            ->givePermissionTo(['publish articles', 'unpublish articles']);

//        $role = Role::create(['name' => 'super-admin']);
//        $role->givePermissionTo(Permission::all());

        Role::create(['name' => 'super-admin']);
        //$role->givePermissionTo(Permission::all());
    }
}
