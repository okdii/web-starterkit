<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user           = auth()->user();
        $menu_service   = new \App\Services\Shared\Menu\MenuService;
        $dataArr    = [
            ...parent::share($request),
            'app' => [
                'app_name' => config('app.name'),
            ],
            'flash' => [
                'severity' => fn () => $request->session()->get('severity'),
                'summary' => fn () => $request->session()->get('summary'),
                'detail' => fn () => $request->session()->get('detail'),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];

        if($user) {
            $authData   = [
                'auth' => [
                    'user' => [
                        'name'      => $user->name,
                        'isTenant'  => tenancy()->initialized,
                    ],
                    'menu' => $menu_service->getMenu()
                ],
            ];
            $dataArr = array_merge($dataArr, $authData);
        }

        return $dataArr;
    }
}
