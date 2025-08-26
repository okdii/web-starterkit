<?php

namespace App\Services\Central\Menu;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    public function flattenTree(array $tree, $parentId = null, $depth = 0, &$flat = [])
    {
        foreach ($tree as $node) {
            $children = $node['children'] ?? [];

            $flat[] = [
                'slug'      => $node['slug'],
                'name'      => $node['name'],
                'parent_id' => $parentId,
                'depth'     => $depth,
            ];

            if (! empty($children)) {
                $this->flattenTree($children, $node['slug'], $depth + 1, $flat);
            }
        }

        return $flat;
    }

    function flattenTreeWithOrder(array $tree, $parentId = null, $depth = 0, &$flat = [])
    {
        foreach ($tree as $index => $node) {
            $children = $node['children'] ?? [];

            $flat[] = [
                'slug'      => $node['slug'],
                'name'      => $node['name'],
                'parent_id' => $parentId,
                'depth'     => $depth,
                'order'     => $index,
            ];

            if (! empty($children)) {
                $this->flattenTreeWithOrder($children, $node['slug'], $depth + 1, $flat);
            }
        }

        return $flat;
    }

    function buildTree(array $flat, $parentId = null): array
    {
        $tree = [];

        foreach ($flat as $item) {
            if ($item['parentSlug'] === $parentId) {
                $children = $this->buildTree($flat, $item['slug']);
                if ($children) {
                    $item['children'] = $children;
                }
                else {
                    $item['children'] = [];
                }
                $tree[] = $item;
            }
        }

        return $tree;
    }

    public function getMenu($user = null)
    {
        $user = $user ?? \Auth::user();

        $roleKey = 'menu_tree_role_'.$user->roles->pluck('name')->sort()->join('_');

        return Cache::remember($roleKey, now()->addMinutes(10), function () use ($user) {
            $menus = \App\Models\Menu::whereNull('parent_id')
                                    ->with([
                                        'relationChildren.relationPermission',
                                        'relationChildren.relationChildren.relationPermission'
                                    ])
                                    ->get();

            $service = new \App\Services\Central\Menu\MenuService;
            $filtered = $service->filterMenuTreeWithPermissions($menus, $user);

            return $filtered->toArray();
        });
    }

    public function filterMenuTreeWithPermissions(Collection $menus, $user): Collection
    {
        return $menus->map(function ($menu) use ($user) {
            // Recursively filter children
            $children = $this->filterMenuTreeWithPermissions($menu->relationChildren, $user);

            // Check permission: on self or children
            $hasPermission = $user->can($menu->permission_id)
                || $user->hasPermissionTo($menu->permission_id)
                || $children->isNotEmpty();

            if (! $hasPermission) {
                return null;
            }

            return [
                'slug'          => $menu->slug,
                'name'          => $menu->name,
                'icon'          => $menu->icon,
                'permission'    => $menu->relationPermission?->name ?? null,
                'children'      => $children->toArray(),
            ];
        })->filter()->values();
    }
}