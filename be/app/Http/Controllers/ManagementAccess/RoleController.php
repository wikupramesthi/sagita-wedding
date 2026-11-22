<?php

namespace App\Http\Controllers\ManagementAccess;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementAccess\StoreRoleRequest;
use App\Http\Requests\ManagementAccess\UpdateRoleRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::query()
            ->when(! blank($request->search), function ($query) use ($request) {
                $term = '%'.$request->search.'%';

                return $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', $term)
                        ->orWhere('guard_name', 'like', $term);
                });
            })
            ->with('permissions', function ($query) {
                return $query->select('id', 'name');
            })
            ->orderBy('name')
            ->paginate(10);

        $users = User::query()
            ->when(! blank($request->search), function ($query) use ($request) {
                $term = '%'.$request->search.'%';

                return $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term);
                });
            })
            ->with('roles', function ($query) {
                return $query->select('name');
            })
            ->latest()
            ->paginate(10);

        $permissions = Permission::orderBy('name')
            ->get(['id', 'name'])
            ->groupBy(fn ($p) => Str::before($p->name, '.'));

        $groupLabels = [
            'article' => 'Artikel',
            'audit-log' => 'Log Audit',
            'banner' => 'Media',
            'category' => 'Kategori',
            'class' => 'Kelas',
            'dashboard' => 'Dasbor',
            'email-setting' => 'Pengaturan Email',
            'event' => 'Acara',
            'failed-login' => 'Login Gagal',
            'faq' => 'FAQ',
            'filedownload' => 'Unduhan',
            'konsultasi' => 'Konsultasi',
            'login-activity' => 'Aktivitas Login',
            'login-lockout' => 'Blokir Login',
            'memberships' => 'Paket Pernikahan',
            'menu' => 'Menu',
            'menu-group' => 'Grup Menu',
            'menu-item' => 'Item Menu',
            'notification' => 'Notifikasi',
            'orders' => 'Pesanan',
            'pages' => 'Halaman',
            'permission' => 'Izin',
            'poll' => 'Polling',
            'role' => 'Role',
            'route' => 'Rute',
            'testimonial' => 'Testimoni',
            'user' => 'Pengguna',
            'vendor' => 'Vendor',
            'website-identity' => 'Identitas Website',
        ];

        $actionLabels = [
            'access-management' => 'Manajemen Akses',
            'approve' => 'Setujui',
            'bulk' => 'Aksi Massal',
            'cancel' => 'Batalkan',
            'checkin' => 'Konfirmasi Kehadiran',
            'create' => 'Tambah',
            'delete' => 'Hapus',
            'destroy' => 'Hapus',
            'edit' => 'Ubah',
            'export' => 'Ekspor',
            'import' => 'Impor',
            'index' => 'Lihat',
            'keamanan' => 'Keamanan',
            'main-menu' => 'Menu Utama',
            'merge' => 'Gabung',
            'memberships' => 'Paket Pernikahan',
            'pengaturan' => 'Pengaturan',
            'proof' => 'Bukti',
            'reject' => 'Tolak',
            'role-permission' => 'Izin Role',
            'show' => 'Detail',
            'store' => 'Tambah',
            'test' => 'Uji',
            'update' => 'Ubah',
        ];

        return view('management-access.role.index', compact(
            'roles',
            'users',
            'permissions',
            'groupLabels',
            'actionLabels'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        Role::create($request->validated())
            ?->givePermissionTo(! blank($request->permissions) ? $request->permissions : []);

        return back()->with('success', 'Role berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->validated())
            && $role->syncPermissions(! blank($request->permissions) ? $request->permissions : []);

        return back()->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return back()->with('success', 'Role berhasil dihapus.');
    }
}
