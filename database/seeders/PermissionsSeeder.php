<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionsSeeder extends Seeder
{
    private array $modules = [
        'permission',
        'project',
        'project status',
        'role',
        'ticket',
        'ticket priority',
        'ticket status',
        'ticket type',
        'user',
        'activity',
        'sprint'
    ];

    private array $pluralActions = [
        'List'
    ];

    private array $singularActions = [
        'View', 'Create', 'Update', 'Delete', 'Mark as completed'
    ];

    private array $extraPermissions = [
        'Manage general settings',
        'Import from Jira',
        'List timesheet data',
        'View timesheet dashboard'
    ];

    private string $defaultRole = 'Default role';
    private string $koorTim = 'Koordinator Tim';
    private string $anggotaTim = 'Anggota Tim';

    private string $koordinatorRole = 'Koordinator Subtim';
    private string $anggotaRole = 'Anggota';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create permissions for each module and action
        foreach ($this->modules as $module) {
            $plural = Str::plural($module);
            $singular = $module;
            foreach ($this->pluralActions as $action) {
                Permission::firstOrCreate([
                    'name' => $action . ' ' . $plural
                ]);
            }
            foreach ($this->singularActions as $action) {
                Permission::firstOrCreate([
                    'name' => $action . ' ' . $singular
                ]);
            }
        }

        foreach ($this->extraPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }

        // Ensure "Mark as completed" permission exists
        Permission::firstOrCreate([
            'name' => 'Mark as completed'
        ]);

        // Create and assign permissions to default role
        $role = Role::firstOrCreate([
            'name' => $this->defaultRole
        ]);
        $settings = app(GeneralSettings::class);
        $settings->default_role = $role->id;
        $settings->save();

        // Add all permissions to default role
        $role->syncPermissions(Permission::all()->pluck('name')->toArray());

        // Assign default role to the first user in the database
        if ($user = User::first()) {
            $user->syncRoles([$this->defaultRole]);
        }

        // Create default role
        $koordinator = Role::firstOrCreate([
            'name' => $this->koordinatorRole
        ]);

        $koordinator->syncPermissions([
            Permission::findByName('List projects'),
            Permission::findByName('Mark as completed'), // Include "Mark as completed"
            Permission::findByName('View project'),
            Permission::findByName('Update project'),
            Permission::findByName('List tickets'),
            Permission::findByName('View ticket'),
            Permission::findByName('Create ticket'),
            Permission::findByName('Update ticket'),
            Permission::findByName('Delete ticket'),
            Permission::findByName('List timesheet data'),
            Permission::findByName('View timesheet dashboard')
        ]);

        // Create and assign permissions to Anggota
        $anggota = Role::firstOrCreate([
            'name' => $this->anggotaRole
        ]);

        $anggota->syncPermissions([
            Permission::findByName('List projects'),
            Permission::findByName('View project'),
            Permission::findByName('List tickets'),
            Permission::findByName('View ticket'),
            Permission::findByName('List timesheet data'),
            Permission::findByName('View timesheet dashboard')
        ]);
    }
}
