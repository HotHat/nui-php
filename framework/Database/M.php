<?php

namespace Niu\Database;

class M
{
    public static function insert($table, $data): int {
        $column = implode(', ', array_map(function ($it) { return "`$it`";},array_keys($data)));
        $mark = implode(', ', array_map(function ($it) { return '?';}, range(1, count($data))));

        $sql = sprintf('INSERT INTO %s (%s) values (%s)', $table, $column, $mark);

        return DB::instance()->insert($sql, array_values($data));
    }

    public static function update($table, $data, $where): int {
        $column = implode(', ', array_map(function ($it) { return "`$it`=?";},array_keys($data)));
        $whereSql = '';
        $bind = array_values($data);
        if ($where) {
            if (is_array($where)) {
                $whereSql = ' WHERE ' .
                    implode(' AND ', array_map(function ($it) { return "`$it`=?";},array_keys($where)));
                $bind = array_merge($bind, array_values($where));
            }
            if (is_string($where)) {
                $whereSql = ' WHERE ' . $where;
            }
        }

        $sql = sprintf('UPDATE %s SET %s %s', $table, $column, $whereSql);

        return DB::instance()->update($sql, $bind);
    }

    public static function delete($table, $where) {
        $bind = [];
        $whereSql = '';
        if ($where) {
            if (is_array($where)) {
                $whereSql = ' WHERE ' .
                    implode(' AND ', array_map(function ($it) { return "`$it`=?";},array_keys($where)));
                $bind = array_merge($bind, array_values($where));
            }
            if (is_string($where)) {
                $whereSql = ' WHERE ' . $where;
            }
        }

        $sql = sprintf('DELETE FROM %s %s', $table, $whereSql);

        return DB::instance()->exec($sql, $bind);
    }


}