<?php

/**
 * Created by PhpStorm.
 * User: Liam
 * Date: 4/23/20
 * Time: 12:25 PM
 */


/**
 * Unit tests for the class Cookies
 */
class CookiesTest extends \PHPUnit\Framework\TestCase {
    private static $site;

    public static function setUpBeforeClass() {
        self::$site = new Noir\Site();
        $localize  = require 'localize.inc.php';
        if(is_callable($localize)) {
            $localize(self::$site);
        }
    }

    protected function setUp() {
        $users = new Noir\Cookies(self::$site);
        $tableName = $users->getTableName();

        $sql = <<<SQL
delete from $tableName;
SQL;

        self::$site->pdo()->query($sql);
    }

    /*
     * Simple test that we can create a token
     */
    public function test_create() {
        $cookies = new Noir\Cookies(self::$site);

        $token = $cookies->create("test");
        $this->assertNotNull($token);
        $this->assertEquals(32, strlen($token));
    }

    /*
     * Test that we can create and validate a token
     */
    public function test_validate() {
        $cookies = new Noir\Cookies(self::$site);

        $token = $cookies->create("test");

        $this->assertNotNull($cookies->validate("test", $token));
    }

    /*
     * Test that we can delete a token
     */
    public function test_delete() {
        $cookies = new Noir\Cookies(self::$site);

        $token = $cookies->create("test");
        $hash = $cookies->validate("test", $token);
        $this->assertNotNull($hash);

        $cookies->delete($hash);
        $this->assertNull($cookies->validate("test", $token));
    }

    /*
     * Ensure you can have multiple tokens active for a given user
     */
    public function test_multiples() {
        $cookies = new Noir\Cookies(self::$site);

        $token1 = $cookies->create("test");
        $token2 = $cookies->create("test");
        $token3 = $cookies->create("test");

        $hash1 = $cookies->validate("test", $token1);
        $this->assertNotNull($hash1);
        $hash2 = $cookies->validate("test", $token2);
        $this->assertNotNull($hash2);
        $hash3 = $cookies->validate("test", $token3);
        $this->assertNotNull($hash3);

        $cookies->delete($hash2);
        $this->assertNull($cookies->validate("test", $token2));
        $this->assertNotNull($cookies->validate("test", $token1));
        $this->assertNotNull($cookies->validate("test", $token3));

        $cookies->delete($hash1);
        $this->assertNull($cookies->validate("test", $token1));
        $this->assertNotNull($cookies->validate("test", $token3));

        $cookies->delete($hash3);
        $this->assertNull($cookies->validate("test", $token3));
    }

}