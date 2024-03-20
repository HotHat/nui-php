<?php

namespace App\Database;

use PDO;

class PdoDB {
    public function __construct($driver, $dbname, $host, $user, $password)
    {
        $this->db = new PDO(
            sprintf('%s:dbname=%s;host=%s', $driver, $dbname, $host),
            $user,
            $password
        );

        $this->db->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_CASE,  PDO::CASE_NATURAL);
    }


    public function query($sql, $bind = [], $onlyOne=false) {
        $data = [];
        $stmt = $this->db->prepare($sql);
        foreach ($bind as $key => $value) {
            $stmt->bindValue($key+1, $value);
        }

        $stmt->execute();
        // just one row
        if ($onlyOne) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }
    public function fetchOne($sql, $bind = []) {
        $data = $this->query($sql, $bind, true);
        if (empty($data)) { return null; }
        return $data;
    }

    public function fetchAll($sql, $bind = []) {
        return $this->query($sql, $bind, false);
    }

    public function insert($sql, $bind) {
        $stmt = $this->db->prepare($sql);
        foreach ($bind as $key => $value) {
            $stmt->bindValue($key+1, $value);
        }
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function update($sql, $bind=[]) {

        $stmt = $this->db->prepare($sql);
        foreach ($bind as $key => $value) {
            $stmt->bindValue($key+1, $value );
        }
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function exec($sql): int {
        return $this->db->exec($sql);
    }

    public function lastError(): string {
        return $this->db->errorCode() . ': ' . implode(';', $this->db->errorInfo());
    }

    public function beginTransaction(): bool {
        return $this->db->beginTransaction();
    }

    public function commit(): bool {
        return $this->db->commit();
    }

    public function rollBack(): bool {
        return $this->db->rollBack();
    }
}