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
        'activity'
        //'sprint'
    ];

    private array $pluralActions = [
        'List'
    ];

    private array $singularActions = [
        'View', 'Create', 'Update', 'Delete'
    ];

    private array $extraPermissions = [
        'Manage general settings',
        //'Import from Jira',
        'List timesheet data',
        'View timesheet dashboard',
        'Mark as completed',
        'Mark as approved' // Ditambahkan di sini
    ];

    private string $defaultRole = 'Ketua Tim Humas';

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

        // Create and assign permissions to default role
        $role = Role::firstOrCreate([
            'name' => $this->defaultRole
        ]);
        $settings = app(GeneralSettings::class);
        $settings->default_role = $role->id;
        $settings->save();

        // Assign permissions to default role (Ketua Tim Humas)
        $role->syncPermissions(array_merge(
            Permission::whereIn('name', [
                'Mark as approved', // Only this permission is included
                'List projects',
                'View project',
                'Create project',
                'Update project',
                'Delete project'
            ])->pluck('name')->toArray(),
            Permission::all()->pluck('name')->diff([
                'Mark as completed', // Exclude this permission
            ])->toArray()
        ));

        // Assign default role to the first user in the database
        if ($user = User::first()) {
            $user->syncRoles([$this->defaultRole]);
        }

        // Create and assign permissions to Koordinator Subtim
        $koordinator = Role::firstOrCreate([
            'name' => $this->koordinatorRole
        ]);

        $koordinator->syncPermissions([
            Permission::findByName('Mark as completed'), // Include "Mark as completed"
            Permission::findByName('List projects'),
            Permission::findByName('View project'),
            Permission::findByName('Update project'),
            Permission::findByName('List tickets'),
            Permission::findByName('View ticket'),
            Permission::findByName('Create ticket'),
            Permission::findByName('Update ticket'),
            Permission::findByName('Delete ticket'),
            Permission::findByName('List users'),
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
