<?php

namespace App\Http\Admin\Controllers;

use UniSharp\LaravelFilemanager\Controllers\ItemsController;

class LfmItemsController extends ItemsController
{
    public function getItems()
    {
        $request = \request();

        $currentPage = self::getCurrentPageFromRequest();

        $perPage = $this->helper->getPaginationPerPage();

        $files = $this->lfm->files();
        if($request->sort_type=='time'){
            $files = array_reverse($files);
        }

        $folders = $this->lfm->folders();
        if($request->sort_type=='time'){
            $folders = array_reverse($folders);
        }

        $items = array_merge($folders, $files);

        return [
            'items' => array_map(function ($item) {
                return $item->fill()->attributes;
            }, array_slice($items, ($currentPage - 1) * $perPage, $perPage)),
            'paginator' => [
                'current_page' => $currentPage,
                'total' => count($items),
                'per_page' => $perPage,
            ],
            'display' => $this->helper->getDisplayMode(),
            'working_dir' => $this->lfm->path('working_dir'),
        ];
    }

    private static function getCurrentPageFromRequest()
    {
        $currentPage = (int) request()->get('page', 1);
        $currentPage = $currentPage < 1 ? 1 : $currentPage;

        return $currentPage;
    }
}
