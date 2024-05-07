<?php

namespace App\Http\Controller;

class BaseController
{
    public function paginate($request) {
        $page = intval($request->get('page', 0));
        $page = max($page, 1);
        $pageSize = intval($request->get('pageSize', 0));
        $pageSize = max($pageSize, 1);
        return [
            'page' => $page,
            'pageSize' => $pageSize
        ];
    }

}