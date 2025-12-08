<?php

namespace App\Livewire\Admin;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Search & Filter
    public $search = '';

    public $perPage = 10;

    public $filter = 'all';

    // Role Management
    public $roleId;

    public $roleName = '';

    public $selectedPermissions = [];

    public $showRoleModal = false;

    public $roleModalTitle = 'Create Role';

    public $deleteRoleId;

    public $showDeleteRoleModal = false;

    // Permission Management
    public $permissionId;

    public $permissionName = '';

    public $permissionGuard = 'web';

    public $permissionGroup = '';

    public $showPermissionModal = false;

    public $permissionModalTitle = 'Create Permission';

    public $deletePermissionId;

    public $showDeletePermissionModal = false;

    // User Assignment
    public $selectedUser;

    public $assignedRoles = [];

    public $showUserAssignmentModal = false;

    public $userSearch = '';

    // Available permission groups
    public $permissionGroups = [
        'user' => 'User Management',
        'role' => 'Role Management',
        'permission' => 'Permission Management',
        'content' => 'Content Management',
        'settings' => 'Settings',
        'reports' => 'Reports',
        'others' => 'Others',
    ];

    protected $listeners = [
        'refreshComponent' => '$refresh',
    ];

    public function mount()
    {
        $this->loadPermissionGroups();
    }

    public function loadPermissionGroups()
    {
        // Extract groups from existing permission names
        $permissions = Permission::all();
        $groups = [];

        foreach ($permissions as $permission) {
            // Extract group from permission name (e.g., "user.create" -> "user")
            $parts = explode('.', $permission->name);
            if (count($parts) > 1) {
                $group = $parts[0];
                if (! isset($this->permissionGroups[$group])) {
                    $this->permissionGroups[$group] = ucfirst(str_replace(['_', '-'], ' ', $group));
                }
            }
        }
    }

    private function extractGroupFromPermission($permissionName)
    {
        $parts = explode('.', $permissionName);

        return count($parts) > 1 ? $parts[0] : 'others';
    }

    // Role Methods
    public function createRole()
    {
        $this->resetRoleForm();
        $this->roleModalTitle = 'Create Role';
        $this->dispatch('open-modal', id: 'role-modal');
    }

    public function editRole($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->roleId = $role->id;
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        $this->roleModalTitle = 'Edit Role';
        $this->dispatch('open-modal', id: 'role-modal');
    }

    public function saveRole()
    {
        $validated = $this->validate([
            'roleName' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Role::class, 'name')->ignore($this->roleId),
            ],
            'selectedPermissions' => 'nullable|array',
            'selectedPermissions.*' => 'exists:permissions,id',
        ]);

        DB::beginTransaction();
        try {
            if ($this->roleId) {
                $role = Role::findOrFail($this->roleId);
                $role->update(['name' => $validated['roleName']]);
                // session()->flash('success', 'Role updated successfully!');
                Notification::make()
                    ->title('Role updated successfully!')
                    ->success()
                    ->send();
            } else {
                $role = Role::create(['name' => $validated['roleName'], 'guard_name' => 'web']);
                // session()->flash('success', 'Role created successfully!');
                Notification::make()
                    ->title('Role created successfully!')
                    ->success()
                    ->send();
            }

            // Method 1: Use direct relationship sync (recommended)
            $role->permissions()->sync($this->selectedPermissions);

            // OR Method 2: Convert IDs to names for syncPermissions()
            // if (!empty($this->selectedPermissions)) {
            //     $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
            //     $permissionNames = $permissions->pluck('name')->toArray();
            //     $role->syncPermissions($permissionNames);
            // } else {
            //     $role->syncPermissions([]);
            // }

            DB::commit();
            $this->resetRoleForm();
            $this->dispatch('close-modal', id: 'role-modal');
            // $this->emit('refreshComponent');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to save role: '.$e->getMessage());

        }
    }

    public function confirmDeleteRole($id)
    {
        $this->deleteRoleId = $id;
        $this->dispatch('open-modal', id: 'delete-role-modal');
    }

    public function deleteRole()
    {
        try {
            $role = Role::findOrFail($this->deleteRoleId);

            // Check if role is assigned to users
            if ($role->users()->count() > 0) {
                session()->flash('error', 'Cannot delete role. It is assigned to users.');
                Notification::make()
                    ->title('Cannot delete role. It is assigned to users.')
                    ->danger()
                    ->send();

                return;
            }

            $role->delete();
            // session()->flash('success', 'Role deleted successfully!');
            Notification::make()
                ->title('Role deleted successfully!')
                ->success()
                ->send();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete role: '.$e->getMessage());
        }

        $this->dispatch('close-modal', id: 'delete-role-modal');
        $this->deleteRoleId = null;
    }

    // Permission Methods
    public function createPermission()
    {
        $this->resetPermissionForm();
        $this->permissionModalTitle = 'Create Permission';
        $this->dispatch('open-modal', id: 'permission-modal');
    }

    public function editPermission($id)
    {
        $permission = Permission::findOrFail($id);
        $this->permissionId = $permission->id;
        $this->permissionName = $permission->name;
        $this->permissionGroup = $permission->group ?? '';
        $this->permissionModalTitle = 'Edit Permission';
        $this->dispatch('open-modal', id: 'permission-modal');
    }

    public function savePermission()
    {
        $validated = $this->validate([
            'permissionName' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Permission::class, 'name')->ignore($this->permissionId),
            ],
            'permissionGroup' => 'nullable|string|max:255',
        ]);

        try {
            $data = [
                'name' => $validated['permissionName'],
                'guard_name' => 'web',
            ];

            // If using database group column
            // $data['group'] = $validated['permissionGroup'];

            if ($this->permissionId) {
                $permission = Permission::findOrFail($this->permissionId);
                $permission->update($data);
                // session()->flash('success', 'Permission updated successfully!');
                Notification::make()
                    ->title('Permission updated successfully!')
                    ->success()
                    ->send();
            } else {
                Permission::create($data);
                session()->flash('success', 'Permission created successfully!');
            }

            $this->loadPermissionGroups(); // Refresh groups
            $this->resetPermissionForm();
            $this->dispatch('close-modal', id: 'permission-modal');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save permission: '.$e->getMessage());
        }
    }

    public function confirmDeletePermission($id)
    {
        $this->deletePermissionId = $id;
        $this->dispatch('open-modal', id: 'delete-permission-modal');
    }

    public function deletePermission()
    {
        try {
            $permission = Permission::findOrFail($this->deletePermissionId);

            // Check if permission is assigned to roles
            if ($permission->roles()->count() > 0) {
                session()->flash('error', 'Cannot delete permission. It is assigned to roles.');
                Notification::make()
                    ->title('Cannot delete permission. It is assigned to roles.')
                    ->danger()
                    ->send();

                return;
            }

            $permission->delete();
            // session()->flash('success', 'Permission deleted successfully!');
            Notification::make()
                ->title('Permission deleted successfully!')
                ->success()
                ->send();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete permission: '.$e->getMessage());
        }

        $this->dispatch('close-modal', id: 'delete-permission-modal');
        $this->deletePermissionId = null;
    }

    // User Assignment Methods
    public function openUserAssignmentModal($userId = null)
    {
        if ($userId) {
            $user = \App\Models\User::findOrFail($userId);
            $this->selectedUser = $user->id;
            $this->assignedRoles = $user->roles->pluck('id')->toArray();
        } else {
            $this->resetUserAssignmentForm();
        }
        $this->dispatch('open-modal', id: 'user-assignment-modal');
    }

    public function assignRolesToUser()
    {
        $this->validate([
            'selectedUser' => 'required|exists:users,id',
        ]);

        try {
            $user = \App\Models\User::findOrFail($this->selectedUser);

            // Convert role IDs to role names for syncRoles
            if (! empty($this->assignedRoles)) {
                $roleNames = Role::whereIn('id', $this->assignedRoles)->pluck('name')->toArray();
                $user->syncRoles($roleNames);
            } else {
                $user->syncRoles([]);
            }

            // session()->flash('success', 'Roles assigned successfully!');
            Notification::make()
                ->title('Roles assigned successfully!')
                ->success()
                ->send();
            $this->dispatch('close-modal', id: 'user-assignment-modal');
            $this->resetUserAssignmentForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to assign roles: '.$e->getMessage());
        }
    }

    // Reset Methods
    private function resetRoleForm()
    {
        $this->reset(['roleId', 'roleName', 'selectedPermissions']);
    }

    private function resetPermissionForm()
    {
        $this->reset(['permissionId', 'permissionName', 'permissionGroup']);
    }

    private function resetUserAssignmentForm()
    {
        $this->reset(['selectedUser', 'assignedRoles', 'userSearch']);
    }

    // Computed Properties
    public function getRolesProperty()
    {
        return Role::with(['permissions', 'users'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->when($this->filter !== 'all', function ($query) {
                if ($this->filter === 'with_users') {
                    $query->has('users');
                } elseif ($this->filter === 'without_users') {
                    $query->doesntHave('users');
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    public function getPermissionsProperty()
    {
        $permissions = Permission::when($this->search, function ($query) {
            $query->where('name', 'like', '%'.$this->search.'%');
        })
            ->orderBy('name')
            ->get();

        // Group permissions by extracted group
        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            $group = $this->extractGroupFromPermission($permission->name);
            $groupedPermissions[$group][] = $permission;
        }

        // If filtered by group, return only that group
        if ($this->filter !== 'all') {
            if (isset($groupedPermissions[$this->filter])) {
                return collect([$this->filter => $groupedPermissions[$this->filter]]);
            }

            return collect([]);
        }

        return collect($groupedPermissions);
    }

    public function getUsersProperty()
    {
        return \App\Models\User::when($this->userSearch, function ($query) {
            $query->where('name', 'like', '%'.$this->userSearch.'%')
                ->orWhere('email', 'like', '%'.$this->userSearch.'%');
        })
            ->with('roles')
            ->orderBy('name')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.role-permission-manager', [
            'roles' => $this->getRolesProperty(),
            'permissions' => $this->getPermissionsProperty(),
            'allPermissions' => Permission::orderBy('name')->get(),
            'allRoles' => Role::orderBy('name')->get(),
            'users' => $this->getUsersProperty(),
        ]);
    }
}
