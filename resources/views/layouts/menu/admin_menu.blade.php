{{-- Administrative Group --}}
@php
    $admin_group = '/'.implode('|', [
        'admin.permissions.*',
        'admin.roles.*',
        'admin.users.*',
        'admin.settings.*',
        'admin.activity_logs.*',
        'admin.docs.*',
    ]).'/';
@endphp
<li>
    <a href="#" data-toggle="collapse" data-target="#admin_group" aria-expanded="{{ preg_match($admin_group, request()->route()->getName())? 'true':'false' }}">
        <i class="fal fa-fw fa-cogs mr-3"></i>Administrative
    </a>
</li>
<ul id="admin_group" class="collapse list-unstyled {{ preg_match($admin_group, request()->route()->getName())? 'show':'' }}">
    @can('Read Permissions')
    <li class="{{ preg_match('/admin.permissions.*/', request()->route()->getName())? 'active':'' }}">
        <a href="{{ route('admin.permissions') }}"><i class="fal fa-fw fa-key mr-3"></i>Permissions</a>
    </li>
    @endcan
    @can('Read Roles')
    <li class="{{ preg_match('/admin.roles.*/', request()->route()->getName())? 'active':'' }}">
        <a href="{{ route('admin.roles') }}"><i class="fal fa-fw fa-shield-alt mr-3"></i>Roles</a>
    </li>
    @endcan
    @can('Read Users')
    <li class="{{ preg_match('/admin.users.*/', request()->route()->getName())? 'active':'' }}">
        <a href="{{ route('admin.users') }}"><i class="fal fa-fw fa-user mr-3"></i>Users</a>
    </li>
    @endcan
    @can('Read Settings')
    <li class="{{ preg_match('/admin.settings.*/', request()->route()->getName())? 'active':'' }}">    
        <a href="{{ route('admin.settings') }}"><i class="fal fa-fw fa-cog mr-3"></i>System Settings</a>
    </li>
    @endcan
    @can('Read Activity Logs')
    <li class="{{ preg_match('/admin.activity_logs.*/', request()->route()->getName())? 'active':'' }}">
        <a href="{{ route('admin.activity_logs') }}"><i class="fal fa-fw fa-file-alt mr-3"></i>Activity Logs</a>
    </li>
    @endcan
    @can('Read Docs')
    <li class="{{ preg_match('/admin.docs.*/', request()->route()->getName())? 'active':'' }}">
        <a href="{{ route('admin.docs') }}"><i class="fal fa-fw fa-book mr-3"></i>Docs</a>
    </li>
    @endcan
    @can('Read Seotools')
        <li class="{{ preg_match('/admin.seotools.*/', request()->route()->getName())? 'active':'' }}">
            <a href="{{ route('admin.seotools') }}"><i class="fab fa-fw fa-searchengin mr-3"></i>Seotools</a>
        </li>
    @endcan
</ul>
{{-- End Administrative Group --}}