<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Users
        Permission::create(['name' => 'users.index']);
        Permission::create(['name' => 'users.create']);
        Permission::create(['name' => 'users.edit']);
        Permission::create(['name' => 'users.destroy']);

        // Categories
        Permission::create(['name' => 'categories.index']);
        Permission::create(['name' => 'categories.create']);
        Permission::create(['name' => 'categories.edit']);
        Permission::create(['name' => 'categories.destroy']);

        // Tags
        Permission::create(['name' => 'tags.index']);
        Permission::create(['name' => 'tags.create']);
        Permission::create(['name' => 'tags.edit']);
        Permission::create(['name' => 'tags.destroy']);

        // Permissions
        Permission::create(['name' => 'permissions.index']);
        Permission::create(['name' => 'permissions.create']);
        Permission::create(['name' => 'permissions.edit']);
        Permission::create(['name' => 'permissions.delete']);

        // Roles
        Permission::create(['name' => 'roles.index']);
        Permission::create(['name' => 'roles.create']);
        Permission::create(['name' => 'roles.edit']);
        Permission::create(['name' => 'roles.destroy']);

        // Ranks
        Permission::create(['name' => 'ranks.index']);
        Permission::create(['name' => 'ranks.create']);
        Permission::create(['name' => 'ranks.edit']);
        Permission::create(['name' => 'ranks.destroy']);

        Permission::create(['name' => 'user_has_rank.index']);
        Permission::create(['name' => 'user_has_rank.create']);
        Permission::create(['name' => 'user_has_rank.edit']);
        Permission::create(['name' => 'user_has_rank.destroy']);
        

        // Manga
        Permission::create(['name' => 'manga.index']);
        Permission::create(['name' => 'manga.create']);
        Permission::create(['name' => 'manga.edit']);
        Permission::create(['name' => 'manga.destroy']);

        Permission::create(['name' => 'manga_demography.index']);
        Permission::create(['name' => 'manga_demography.create']);
        Permission::create(['name' => 'manga_demography.edit']);
        Permission::create(['name' => 'manga_demography.destroy']);

        Permission::create(['name' => 'manga_types.index']);
        Permission::create(['name' => 'manga_types.create']);
        Permission::create(['name' => 'manga_types.edit']);
        Permission::create(['name' => 'manga_types.destroy']);

        Permission::create(['name' => 'manga_book_status.index']);
        Permission::create(['name' => 'manga_book_status.create']);
        Permission::create(['name' => 'manga_book_status.edit']);
        Permission::create(['name' => 'manga_book_status.destroy']);

        // Chapters
        Permission::create(['name' => 'chapters.index']);
        Permission::create(['name' => 'chapters.create']);
        Permission::create(['name' => 'chapters.edit']);
        Permission::create(['name' => 'chapters.destroy']);

        // Products
        Permission::create(['name' => 'products.index']);
        Permission::create(['name' => 'products.create']);
        Permission::create(['name' => 'products.edit']);
        Permission::create(['name' => 'products.destroy']);

        Permission::create(['name' => 'product_types.index']);
        Permission::create(['name' => 'product_types.create']);
        Permission::create(['name' => 'product_types.edit']);
        Permission::create(['name' => 'product_types.destroy']);

        // Orders
        Permission::create(['name' => 'orders.index']);
        Permission::create(['name' => 'orders.create']);
        Permission::create(['name' => 'orders.edit']);
        Permission::create(['name' => 'orders.destroy']);

        // :SLIDER
        Permission::create(['name' => 'slider.index']);
        Permission::create(['name' => 'slider.create']);
        Permission::create(['name' => 'slider.edit']);
        Permission::create(['name' => 'slider.destroy']);

        // Settings
        Permission::create(['name' => 'settings.index']);
        Permission::create(['name' => 'settings.edit']);

        Permission::create(['name' => 'settings.ads.index']);
        Permission::create(['name' => 'settings.ads.store']);
        Permission::create(['name' => 'settings.ads.edit']);

        Permission::create(['name' => 'settings.seo.index']);
        Permission::create(['name' => 'settings.seo.store']);
        Permission::create(['name' => 'settings.seo.edit']);

        // Administrative roles
        $administrator = Role::create(['name' => 'administrador']);
        $moderador = Role::create(['name' => 'moderador']);
        $moderador->givePermissionTo([
            'slider.index',
            'slider.create',
            'slider.edit',
            'slider.destroy',
            'manga.index',
            'manga.create',
            'manga.edit',
            'manga.destroy',
            'manga_demography.index',
            'manga_demography.create',
            'manga_demography.edit',
            'manga_demography.destroy',
            'manga_types.index',
            'manga_types.create',
            'manga_types.edit',
            'manga_types.destroy',
            'manga_book_status.index',
            'manga_book_status.create',
            'manga_book_status.edit',
            'manga_book_status.destroy',
            'chapters.index',
            'chapters.create',
            'chapters.edit',
            'chapters.destroy',
            'categories.index',
            'categories.create',
            'categories.edit',
            'categories.destroy',
            'tags.index',
            'tags.create',
            'tags.edit',
            'tags.destroy'
        ]);

        // Lector Role
        Role::create(['name' => 'lector']);


        $role = Role::create(['name' => 'developer']);

        // Create first user and gives all permissions
        // $user = \App\Models\User::factory()->create([
        //     'username' => 'vaor',
        //     'email' => 'jonvargasor@gmail.com',
        // ]);
        $user = User::create([
            'username' => 'vaor',
            'email' => 'jonvargasor@gmail.com',
            'password' => Hash::make('password')
        ]);
        $user->assignRole($role);
        event(new Registered($user));

        $profile = new Profile();
        $profile->user_id = $user['id'];
        $profile->avatar = 'avatares/avatar-'.rand(1, 10).'.jpg';
        $profile->save();
    }
}
