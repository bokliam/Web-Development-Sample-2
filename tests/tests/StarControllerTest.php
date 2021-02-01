<?php

/**
 * Created by PhpStorm.
 * User: Liam
 * Date: 4/20/20
 * Time: 7:59 PM
 */


/**
 * Unit tests for the class StarController
 */
class StarControllerTest extends \PHPUnit\Framework\TestCase {
    private static $site;

    public static function setUpBeforeClass() {
        self::$site = new Noir\Site();
        $localize  = require 'localize.inc.php';
        if(is_callable($localize)) {
            $localize(self::$site);
        }
    }

    protected function setUp() {
        $users = new Noir\Movies(self::$site);
        $tableName = $users->getTableName();

        $sql = <<<SQL
delete from $tableName;
insert into $tableName(id, user, title, year, rating)
values 
    (12, "test", "The Maltese Falcon", "1941", "10"),
    (14, "test", "Felis Noir", "2016", "8"),
    (15, "other", "The Maltese Falcon", "1941", "10")
SQL;

        self::$site->pdo()->query($sql);
    }

    public function test_construct() {
        $movies = new Noir\Movies(self::$site);

        // Successful update
        $post = array('id' => "12", 'rating' => 7);
        $controller = new \Noir\StarController(self::$site, "test", $post);
        $ret = json_decode($controller->getResult(), true);
        $this->assertTrue($ret['ok']);

        $movie = $movies->get("test", 12);
        $this->assertEquals(7, $movie->getRating());

        // Unsuccessful update
        $post = array('id' => "99", 'rating' => 7);
        $controller = new \Noir\StarController(self::$site, "test", $post);
        $ret = json_decode($controller->getResult(), true);
        $this->assertFalse($ret['ok']);
        $this->assertEquals("Failed to update database!", $ret['message']);
    }

}