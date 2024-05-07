<?php

namespace Nui\Database;

use PDO;

class PdoDB {
    private PDO $db;
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


    public function select($sql, $bind = [], $onlyOne=false) {
        $stmt = $this->db->prepare($sql);
        foreach ($bind as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue($key+1, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key+1, $value);
            }
        }

        $stmt->execute();
        // just one row
        if ($onlyOne) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function fetchOne($sql, $bind = []) {
        $data = $this->select($sql, $bind, true);
        if (empty($data)) { return null; }
        return $data;
    }

    public function fetchAll($sql, $bind = []) {
        return $this->select($sql, $bind, false);
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

    public function statement($sql, $bind=[]): bool {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($bind);
    }

    public function cursor($sql, $bind=[]) {
        $stmt = $this->db->prepare($sql);
        foreach ($bind as $key => $value) {
            $stmt->bindValue($key+1, $value );
        }
        $stmt->execute();
        while ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {
            yield $record;
        }
    }

    public function exec($sql, $bind=[]): bool {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($bind);
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