<?php

require 'lib/Testify/Testify.php';
require_once '../FileCache.php';

use Testify\Testify;

class TestCache extends FileCache {
    public static function getFilePathFromKey($key) {
        return parent::getFilePathFromKey($key);
    }
}
TestCache::setCacheDirectory('./cache');

$tf = new Testify("Cache Test Suite");

// add a test case
$tf->test("Cache Tests", function($tf) {
    $key = "Key1";
    $data = array('1' => 1, '2' => "2");
    $tf->assertFalse(TestCache::get($key),'Check key does not exist');
    $tf->assert(TestCache::set($key, $data));
    $tf->assert(TestCache::get($key)===$data);
    $tf->assertTrue(TestCache::delete($key));
    $tf->assert(TestCache::get($key)===null);
});

$tf(); // run all tests


