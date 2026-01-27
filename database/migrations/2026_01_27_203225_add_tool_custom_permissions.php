<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // Crear los permisos con el formato de Filament Shield
        $permissions = [
            'Assign:Tool',
            'Return:Tool',
            'ChangeStatus:Tool',
            'ViewAssignmentHistory:Tool',
            'ViewStatusHistory:Tool',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Asignar todos los permisos al super_admin
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }
    }

    public function down(): void
    {
        $permissions = [
            'Assign:Tool',
            'Return:Tool',
            'ChangeStatus:Tool',
            'ViewAssignmentHistory:Tool',
            'ViewStatusHistory:Tool',
        ];

        foreach ($permissions as $permission) {
            Permission::where('name', $permission)->delete();
        }
    }
};
