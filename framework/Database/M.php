<?php

namespace Niu\Database;

class M
{
    public static function insert($table, $data): int {
        $bind = [];
        if (is_array($data)) {
            $column = '';
            $markArr = [];
           foreach ($data as $item) {
               if ($column == '') {
                   $column = implode(', ', array_map(function ($it) { return "`$it`";},array_keys($item)));
               }
               $markArr[] = '(' .
                   implode(', ', array_map(function ($it) { return '?';}, range(1, count($item)))) .
               ')';
               $bind = array_merge($bind, array_values($item));
           }
           $mark = implode(', ', $markArr);
        } else {
            $column = implode(', ', array_map(function ($it) { return "`$it`";},array_keys($data)));
            $mark = '(' .
                implode(', ', array_map(function ($it) { return '?';}, range(1, count($data)))) .
            ')';
            $bind = array_values($data);
        }

        $sql = sprintf('INSERT INTO `%s` (%s) values %s', $table, $column, $mark);

        return DB::instance()->insert($sql, $bind);
    }

    public static function update($table, $data, $where): int {
        $column = implode(', ', array_map(function ($it) { return "`$it`=?";},array_keys($data)));
        $bind = array_values($data);
        [$whereSql, $whereBind] = self::buildWhere($where);

        $bind = array_merge($bind, array_values($whereBind));

        $sql = sprintf('UPDATE `%s` SET %s %s', $table, $column, $whereSql);

        return DB::instance()->update($sql, $bind);
    }

    public static function delete($table, $where) {

        [$whereSql, $bind] = self::buildWhere($where);

        $sql = sprintf('DELETE FROM %s %s', $table, $whereSql);

        return DB::instance()->exec($sql, $bind);
    }


    public static function buildWhere($where): array
    {
        $bind = [];
        $whereSql = '';
        if ($where) {
            if (is_array($where)) {
                $whereSql = ' WHERE ' .
                    implode(' AND ', array_map(function ($it) { return "`$it`=?";},array_keys($where))) . ' ';
                $bind = array_merge($bind, array_values($where));
            }
            if (is_string($where)) {
                $whereSql = ' WHERE ' . $where;
            }
        }
        return [
            $whereSql,
            $bind
        ];
    }
}