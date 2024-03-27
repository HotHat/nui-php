<?php

namespace Niu;

class Cache
{
    use InstanceTrait;

    private string $dir = '/tmp/niu';

    private function getFile($key) {

        return $this->dir . '/cache_' . $key;
    }
    public function set($key, $value, $ttl=60) {
        $file = $this->getFile($key);

        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0700, true);
        }

        file_put_contents($file, json_encode([
            'expired_at' => time() + $ttl,
            'value' => json_encode($value)
        ]));
    }

    public function get($key, $default=null) {
        $file = $this->getFile($key);

        if (!file_exists($file)) {
            return $default;
        }

        $jsData = file_get_contents($file);
        $data = json_decode($jsData, true);
        if ($data['expired_at'] < time()) {
            unlink($file);
            return $default;
        }

        return json_decode($data['value'], true);
    }


}