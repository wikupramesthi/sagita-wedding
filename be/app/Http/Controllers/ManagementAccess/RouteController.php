<?php

namespace App\Http\Controllers\ManagementAccess;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementAccess\StoreRouteRequest;
use App\Http\Requests\ManagementAccess\UpdateRouteRequest;
use App\Models\ManagementAccess\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Spatie\Permission\Models\Permission;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $routes = Route::query()
            ->when(! blank($request->search), function ($query) use ($request) {
                return $query
                    ->where('route', 'like', '%'.$request->search.'%')
                    ->orWhere('permission_name', 'like', '%'.$request->search.'%');
            })
            ->orderBy('route')
            ->get();
        $facadesRoutes = FacadesRoute::getRoutes();

        $permissions = Permission::orderBy('name')->get();

        return view('management-access.route.index', compact('routes', 'permissions', 'facadesRoutes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRouteRequest $request)
    {
        $route = Route::create(array_merge(
            $request->all(),
            ['status' => ! blank($request->status) ? true : false]
        ));

        $this->forgetRoutePermissionCache($route->route);

        return back()->with('success', 'Rute berhasil ditambahkan!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Route $route)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Route $route)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRouteRequest $request, Route $route)
    {
        $oldRouteName = $route->route;

        $route->update(array_merge(
            $request->all(),
            ['status' => ! blank($request->status) ? true : false]
        ));

        $this->forgetRoutePermissionCache($oldRouteName, $route->route);

        return back()->with('success', 'Rute berhasil diperbarui!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        $routeName = $route->route;
        $route->delete();

        $this->forgetRoutePermissionCache($routeName);

        return back()->with('success', 'Rute berhasil dihapus!');

    }

    private function forgetRoutePermissionCache(?string ...$routeNames): void
    {
        foreach (array_unique(array_filter($routeNames)) as $routeName) {
            Cache::forget('route.permission.'.$routeName);
        }
    }
}
