<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetear caché de roles y permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            // Permisos de Items
            'view items',
            'create items',
            'edit items',
            'delete items',
            'upload items',
            
            // Permisos de Categorías
            'manage categories',
            
            // Permisos de Colecciones
            'view collections',
            'create collections',
            'edit collections',
            'delete collections',
            
            // Permisos de Usuarios
            'manage users',
            'view users',
            
            // Permisos de Ratings
            'create ratings',
            'edit own ratings',
            'delete own ratings',
            'moderate ratings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles y asignar permisos

        // Rol: Admin - Tiene todos los permisos
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Rol: Moderador - Puede gestionar contenido pero no usuarios
        $moderatorRole = Role::create(['name' => 'moderator']);
        $moderatorRole->givePermissionTo([
            'view items',
            'create items',
            'edit items',
            'delete items',
            'upload items',
            'manage categories',
            'view collections',
            'create collections',
            'edit collections',
            'delete collections',
            'moderate ratings',
        ]);

        // Rol: Usuario - Permisos básicos
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view items',
            'view collections',
            'create collections',
            'edit collections',
            'delete collections',
            'create ratings',
            'edit own ratings',
            'delete own ratings',
        ]);

        // Rol: Guest - Solo lectura
        $guestRole = Role::create(['name' => 'guest']);
        $guestRole->givePermissionTo([
            'view items',
            'view collections',
        ]);

        $this->command->info('Roles y permisos creados exitosamente!');
    }
}
