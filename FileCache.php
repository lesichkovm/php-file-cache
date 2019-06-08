<?php

class FileCache {

    private static $cacheDirectory = 'cache';

    /**
     * Returns the cached data, or null if not set or expired
     * @param string $key
     * @return mixed|null
     */
    public static function get($key) {
        $path = self::getFilePathFromKey($key);
        if (file_exists($path) == true) {
            $cacheSerialized = file_get_contents($path);
            $cache = json_decode($cacheSerialized, true);
            if (is_array($cache) === false) {
                return null;
            }

            $expires = $cache['expires'];
            
            if (time() > $expires) {
                return null;
            }


            return $cache['data'];
        }
        return null;
    }

    /**
     * Caches the data, with the specified key, and sets an expiration date
     * @param string $key
     * @param mixed $data
     * @param int $expires
     * @return bool
     */
    public static function set($key, $data, $expires = 120) {
        $cache = array(
            'key' => $key,
            'data' => $data,
            'expires' => time() + $expires
        );
        $path = self::getFilePathFromKey($key);
        $cacheSerialized = json_encode($cache);
        $result = file_put_contents($path, $cacheSerialized);
        return $result === false ? false : true;
    }

    public static function setCacheDirectory($cacheDirectoryPath) {
        self::$cacheDirectory = $cacheDirectoryPath;
    }

    public static function delete($key) {
        $path = self::getFilePathFromKey($key);
        
        if (file_exists($path) === false) {
            return true;
        }
        
        return unlink($path);
    }

    protected static function getFilePathFromKey($key) {
        return rtrim(self::$cacheDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . md5($key) . '.json';
    }

}
