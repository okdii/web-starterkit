<?php

namespace App\Services\Admin\Menu;

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
                $tree[] = $item;
            }
        }

        return $tree;
    }
}