<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionsSeeder extends Seeder
{
    private array $modules = [
        'permission', 'project', 'project status', 'role', 'ticket',
        'ticket priority', 'ticket status', 'ticket type', 'user',
        'activity', 'sprint'
    ];

    private array $pluralActions = [
        'List'
    ];

    private array $singularActions = [
        'View', 'Create', 'Update', 'Delete'
    ];

    private array $extraPermissions = [
        'Manage general settings', 'Import from Jira',
        'List timesheet data', 'View timesheet dashboard'
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
        // Create profiles
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

        // Create default role
        $role = Role::firstOrCreate([
            'name' => $this->defaultRole
        ]);
        $settings = app(GeneralSettings::class);
        $settings->default_role = $role->id;
        $settings->save();

        // Add all permissions to default role
        $role->syncPermissions(Permission::all()->pluck('name')->toArray());

        // Assign default role to first database user
        if ($user = User::first()) {
            $user->syncRoles([$this->defaultRole]);
        }


        // Create default role
        $koordinator = Role::firstOrCreate([
            'name' => $this->koordinatorRole
        ]);

        $koordinator->syncPermissions([
            Permission::findByName('List projects'),
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

        // Create default role
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
