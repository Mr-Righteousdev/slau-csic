@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Role-Based Sidebar Demo</h1>
        
        <div class="space-y-4">
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <h2 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">How it works:</h2>
                <p class="text-blue-800 dark:text-blue-200">
                    The sidebar now uses Spatie's @hasrole() directive instead of the MenuHelper array. 
                    Each menu item is wrapped in @hasrole('role-name') blocks.
                </p>
            </div>

            <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <h2 class="font-semibold text-green-900 dark:text-green-100 mb-2">Current User Role:</h2>
                <p class="text-green-800 dark:text-green-200">
                    @if(auth()->user())
                        Your roles: {{ auth()->user()->roles->pluck('name')->join(', ') ?: 'No roles assigned' }}
                    @else
                        Not authenticated
                    @endif
                </p>
            </div>

            <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                <h2 class="font-semibold text-yellow-900 dark:text-yellow-100 mb-2">Available Menu Items:</h2>
                <ul class="list-disc list-inside text-yellow-800 dark:text-yellow-200 space-y-1">
                    <li><strong>Dashboard:</strong> Available to all authenticated users</li>
                    <li><strong>Calendar:</strong> Only users with 'secretary' role</li>
                    <li><strong>User Management & Settings:</strong> Only users with 'admin' role</li>
                    <li><strong>Projects & Reports:</strong> Only users with 'manager' role</li>
                </ul>
            </div>

            <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                <h2 class="font-semibold text-purple-900 dark:text-purple-100 mb-2">To add new menu items:</h2>
                <pre class="bg-purple-100 dark:bg-purple-800 p-3 rounded text-sm overflow-x-auto"><code>@hasrole('your-role-name')
    &lt;li&gt;
        &lt;a href="{{ route('your.route') }}" class="menu-item group"&gt;
            &lt;!-- Your menu item here --&gt;
        &lt;/a&gt;
    &lt;/li&gt;
@endhasrole</code></pre>
            </div>
        </div>
    </div>
</div>
@endsection