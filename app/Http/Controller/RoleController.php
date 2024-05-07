<?php

namespace App\Http\Controller;


use Nui\Facade\DB;
use Nui\Request;

class RoleController extends BaseController
{
    public function index(Request $request) {

        $p = $this->paginate($request);

        $query = DB::table('role');

        if (!is_null($request->get('enable'))) {
            $status  = intval($request->get('enable', '0'));
            $query->where('status', $status);
        }

        $list = $query->paginate($p['pageSize'], $p['page']);

        $page = array_map(function ($it) {
            $it = (array)$it;
            return [
                'id' => $it['id'],
                'code' => $it['code'],
                'name' => $it['name'],
                'enable' => $it['status'] == 1,
                'permissionIds' => array_map(function ($it) {
                    return intval($it);
                },
                    explode(',', $it['permission_id'])
                ),
            ];
        }, $list['list']);

        return respSuccess([
            'pageData' => $page,
            'total' => $list['totalPage']
        ]);
    }

    public function add(Request $request) {
        $data = $request->post();
        DB::table('role')->insert([
            'name' => $data['name'] ?? '',
            'code' => $data['code'] ?? '',
            'status' => $data['enable'] ? 1 : 0,
            'permission_id' => implode(',', $data['permissionIds'])
        ]);

        return respSuccess();
    }

    public function addUser(Request $request, $id) {
        $data = $request->post();
        if (empty($data['userIds'])) {
            return respFail('请选择分配用户');
        }
        if (empty($id)) {
            return respFail('请选择分配角色');
        }

        DB::beginTransaction();
        try {
            DB::table('user_role')
                ->where('role_id', $id)
                ->whereIn('user', implode(',', $data['userIds']))
                ->delete();
            DB::table('user_role')
                ->insert(
                    array_map(function ($it) use ($id) {
                        return ['role_id' => $id, 'user_id' => $it, 'created_at' => now(), 'updated_at' => now()];
                    }, $data['userIds'])
                );

            DB::commit();
            return respSuccess();
        } catch (\Exception $e) {
            DB::rollBack();
            return respFail($e->getMessage());
        }
    }

    public function update(Request $request, $id) {
        $data = $request->post();
        DB::table('role')
            ->where('id', $id)
            ->update([
                'name' => $data['name'] ?? '',
                'code' => $data['code'] ?? '',
                'status' => $data['enable'] ? 1 : 0,
                'permission_id' => implode(',', $data['permissionIds'])
            ]);
        return respSuccess();
    }
    public function delete(Request $request, $id) {
        DB::table('role')->delete(intval($id));
        return respSuccess();
    }
}