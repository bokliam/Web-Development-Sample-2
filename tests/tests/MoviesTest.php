<?php

/** @file
 * @brief Unit tests for the class Movies
 * @cond 
 */
class MoviesTest extends \PHPUnit\Framework\TestCase {
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

	public function test_getAll() {
		$movies = new Noir\Movies(self::$site);

		$all = $movies->getAll("test");
		$this->assertEquals(2, count($all));
	}

	public function test_updateRating() {
		$movies = new Noir\Movies(self::$site);

		$movie = $movies->get("test", 12);
		$this->assertEquals(10, $movie->getRating());

		$this->assertTrue($movies->updateRating("test", 12, 7));
		$movie = $movies->get("test", 12);
		$this->assertEquals(7, $movie->getRating());

		// Invalid ID
		$this->assertFalse($movies->updateRating("test", 22, 9));

		// Invalid user
		$this->assertFalse($movies->updateRating("test", 15, 9));
	}
}

/// @endcond
