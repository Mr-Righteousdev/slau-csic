<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class MemberDirectory extends Component
{
    use WithPagination;

    public $search = '';

    public $filter = 'all';

    public $viewMode = 'grid'; // grid or list

    public $yearFilter = '';

    public $programFilter = '';

    public $roleFilter = '';

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => 'all'],
        'viewMode' => ['except' => 'grid'],
        'yearFilter' => ['except' => ''],
        'programFilter' => ['except' => ''],
        'roleFilter' => ['except' => ''],
    ];

    public function getMembersProperty()
    {
        return User::publiclyVisible()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('program', 'like', '%'.$this->search.'%');
            })
            ->when($this->filter !== 'all', function ($query) {
                if ($this->filter === 'active') {
                    $query->where('membership_status', 'active');
                } elseif ($this->filter === 'alumni') {
                    $query->where('membership_type', 'alumni');
                } elseif ($this->filter === 'executive') {
                    $query->executiveBoard();
                }
            })
            ->when($this->yearFilter, function ($query) {
                $query->where('year_of_study', $this->yearFilter);
            })
            ->when($this->programFilter, function ($query) {
                $query->where('program', 'like', '%'.$this->programFilter.'%');
            })
            ->when($this->roleFilter, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->roleFilter);
                });
            })
            ->with(['roles'])
            ->orderBy('name')
            ->paginate(12);
    }

    public function getYearsProperty()
    {
        return User::publiclyVisible()
            ->whereNotNull('year_of_study')
            ->distinct()
            ->pluck('year_of_study')
            ->sort()
            ->mapWithKeys(fn ($year) => [$year => "Year {$year}"]);
    }

    public function getProgramsProperty()
    {
        return User::publiclyVisible()
            ->whereNotNull('program')
            ->distinct()
            ->pluck('program')
            ->sort()
            ->mapWithKeys(fn ($program) => [$program => $program]);
    }

    public function getRolesProperty()
    {
        return \Spatie\Permission\Models\Role::all()
            ->pluck('name', 'name')
            ->sort();
    }

    public function getStatsProperty()
    {
        $total = User::publiclyVisible()->count();
        $active = User::publiclyVisible()->where('membership_status', 'active')->count();
        $alumni = User::publiclyVisible()->where('membership_type', 'alumni')->count();

        return [
            'total' => $total,
            'active' => $active,
            'alumni' => $alumni,
            'active_percentage' => $total > 0 ? round(($active / $total) * 100, 1) : 0,
        ];
    }

    public function render()
    {
        return view('livewire.member-directory', [
            'members' => $this->members,
            'years' => $this->years,
            'programs' => $this->programs,
            'roles' => $this->roles,
            'stats' => $this->stats,
        ]);
    }
}
