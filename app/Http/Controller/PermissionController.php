<?php

namespace App\Http\Controller;

use Nui\Facade\DB;
use Nui\Request;

class PermissionController extends BaseController
{
    public function index(Request $request) {

        $list = DB::table('permission')
            ->orderBy('order')
            ->get()
            ->toArray(); // ( 'select * from permission order by id, `order`');
        $tree = makeTree($list);

        $data = $this->treeRender($tree);

        return respSuccess($data);
    }

    private function treeRender($tree) {
        $lst = [];
        foreach ($tree as $dt) {
            $it = (array)$dt['data'];
            $item = [
                'id' => $it['id'],
                'parentId' => $it['parent_id'],
                'name' => $it['name'],
                'code' => $it['code'],
                'icon' => $it['icon'],
                'type' => $it['type'],
                'redirect' => $it['redirect'],
                'component' => $it['component'],
                'path' => $it['path'],
                'layout' => $it['layout'],
                'keepAlive' => $it['keep_alive'] == 1,
                'method' => $it['method'],
                'description' => $it['description'],
                'show' => $it['show'] == 1,
                'order' => intval($it['order']),
                'enable' => $it['status'] == 1,
            ];

            if ($dt['children']) {
                $item['children'] = $this->treeRender($dt['children']);
            }
            $lst[] = $item;
        }

        return $lst;
    }

    public function add(Request $request) {
        $data = $request->post();

        DB::table('permission')->insert([
            'code' =>  $data['code'] ?? '',
            'status' =>  ($data['enable'] ?? 0) ? 1 : 0,
            'icon' =>  $data['icon'] ?? '',
            'name' =>  $data['name'] ?? '',
            'order' =>  intval($data['order'] ?? 0),
            'parent_id' =>  intval($data['parentId'] ?? 0),
            'show' =>  $data['show'] ?? '',
            'type' =>  $data['type'] ?? '',
            'component' =>  $data['component'] ?? '',
            'layout' =>  $data['layout'] ?? '',
            'path' =>  $data['path'] ?? '',
            'keep_alive' =>  $data['keepAlive'] ?? false ? 1 : 0,
            'created_at' =>  now(),
            'updated_at' =>  now(),
        ]);

        return respSuccess();
    }

    public function update(Request $request) {
        $data = $request->post();

        DB::table('permission')
            ->where('id', intval($data['id'] ?? 0))
            ->update([
            'code' => $data['code'] ?? '',
            'status' =>   $data['enable'] ?? false ? 1 : 0,
            'icon' => $data['icon'] ?? '',
            'name' => $data['name'] ?? '',
            'order' => intval($data['order'] ?? 0),
            'parent_id' => $data['parentId'] ?? 0,
            'show' => $data['show'] ?? false ? 1 : 0,
            'type' => $data['type'] ?? '',
            'layout' =>  $data['layout'] ?? '',
            'component' => $data['component'] ?? '',
            'keep_alive' =>  $data['keepAlive'] ?? false ? 1 : 0,
            'path' => $data['path'] ?? '',
            'redirect' => $data['redirect'] ?? '',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return respSuccess();
    }
    public function delete(Request $request) {
        $id = intval($request->get('id', 0));
        DB::table('permission')->delete($id);

        return respSuccess();
    }
}