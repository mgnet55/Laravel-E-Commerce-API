<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create product']);
        Permission::create(['name' => 'delete product']);
        Permission::create(['name' => 'update product']);
        Permission::create(['name' => 'publish product']);

        //category
        Permission::create(['name' => 'create category']);
        Permission::create(['name' => 'update category']);
        Permission::create(['name' => 'delete category']);
        //order
        Permission::create(['name' => 'index order']);
        Permission::create(['name' => 'create order']);
        Permission::create(['name' => 'show order']);
        Permission::create(['name' => 'delete order']);
        Permission::create(['name' => 'update order']);
        Permission::create(['name' => 'change order status']);
        //cart
        Permission::create(['name' => 'create cart']);
        Permission::create(['name' => 'add-to cart']);
        Permission::create(['name' => 'delete cart']);
        Permission::create(['name' => 'update cart']);
        //Cities and Governorate Management
        Permission::create(['name' => 'create location']);
        Permission::create(['name' => 'update location']);
        Permission::create(['name' => 'delete location']);

        //Permissions and Roles management
        Permission::create(['name' => 'index permission']);
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'delete permission']);
        Permission::create(['name' => 'update permission']);
        Permission::create(['name' => 'assign permission']);
        Permission::create(['name' => 'index role']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'delete role']);
        Permission::create(['name' => 'update role']);
        Permission::create(['name' => 'assign role']);

        //shipping companies management
        Permission::create(['name' => 'create company']);
        Permission::create(['name' => 'delete company']);
        Permission::create(['name' => 'update company']);
        Permission::create(['name' => 'assign company manager']);

        // this can be done as separate statements
        $seller = Role::create(['name' => 'seller']);
        $seller->syncPermissions(['create product', 'publish product', 'update product', 'delete product']);
//
        // or may be done by chaining
        $customer = Role::create(['name' => 'customer']);
        $customer->syncPermissions(['create cart', 'add-to cart', 'delete cart', 'update cart']);

        $shippingCompany = Role::create(['name' => 'shipping_manager']);
        $shippingCompany->syncPermissions(['change order status', 'show order']);

        $userManager = Role::create(['name'=>'user management']);
//        $role = Role::create(['name' => 'super-admin']);

        Role::create(['name' => 'super-admin']);
        //$role->givePermissionTo(Permission::all());


        \App\Models\User::all()->reject(function ($customer) {
            return $customer->id >= 10;
        })->map(function ($user) {
            $user->assignRole('customer');
        });

        \App\Models\User::all()->reject(function ($seller) {
            return $seller->id <= 10;
        })->map(function ($seller) {
            $seller->assignRole('seller');
        });

        //  super-admin create

        $superAdmin = User::create([
            'name' => 'super admin',
            'email' => 'super@admin.com',
            'email_verified_at' => now(),
            'password' =>bcrypt('adminpassword'),
            'remember_token' => Str::random(10),
            'phone' => '0123456',
            'address' => 'Company Center',
        ]);

        $role = Role::where('id', 5)->first(); // super-admin id

        $superAdmin->syncRole($role);
    }


    }
}
