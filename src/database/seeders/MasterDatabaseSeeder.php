<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Shop;
class MasterDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $registerPermission = Permission::create(['name' => 'register']);
        $shopPermission = Permission::create(['name' => 'shop']);

        $adminRole = Role::create(['name' => 'admin']);
        $shop_repRole = Role::create(['name' => 'shop_rep']);

        $adminRole->givePermissionTo($registerPermission);
        $shop_repRole->givePermissionTo($shopPermission);


        $admin = User::create([
            'name' => '管理者',
            'email' => 'admin@sample.com',
            'password' => bcrypt('password'),
        ]);

        $shop_rep = User::create([
            'name' => '店舗代表',
            'email' => 'shop_rep@sample.com',
            'password' => bcrypt('password'),
        ]);
        
        User::create([
            'name' => 'ユーザー',
            'email' => 'gest@sample.com',
            'password' => bcrypt('password'),
        ]);
        
        
        $admin->assignRole($adminRole);
        $shop_rep->assignRole($shop_repRole);

        $shop = Shop::inRandomOrder()->first();
        $shop_rep->shops()->attach($shop->id);
    }
}
