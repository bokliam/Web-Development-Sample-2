<?php

/**
 * Created by PhpStorm.
 * User: Liam
 * Date: 4/23/20
 * Time: 12:24 PM
 */


namespace Noir;


class Cookies extends Table
{

    /**
     * Constructor
     * @param Site $site The Site object
     */
    public function __construct(Site $site) {
        parent::__construct($site, "cookie");
    }

    /**
     * Create a new cookie token
     * @param $user User to create token for
     * @return string New 32 character random string
     */
    public function create($user) {
        $validator = $this->createValidator();
        $salt = self::randomSalt();
        $hash = hash("sha256", $validator . $salt);

        // Write to the table
        $sql = <<<SQL
INSERT INTO $this->tableName(user, salt, hash)
values(?, ?, ?)
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        try {
            if($statement->execute([$user, $salt, $hash]) === false) {
                return null;
            }
        } catch(\PDOException $e) {
            return null;
        }

        return $validator;
    }


    /**
     * Validate a cookie token
     * @param $user User ID
     * @param $token Token
     * @return null|string If successful, return the actual
     *   hash as stored in the database.
     */
    public function validate($user, $token) {
        $sql =<<<SQL
SELECT * from $this->tableName
where user=?
SQL;
        $pdo = $this->pdo();
        // PDO object failed
        if ($pdo == null){
            return null;
        }

        $statement = $pdo->prepare($sql);

        // If execution failed
        if ($statement->execute(array($user)) == false){
            return null;
        }
        if($statement->rowCount() === 0) {
            return null;
        }

        $row = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $res = null;
        foreach($row as $user) {
            $actualHash = $user['hash'];
            $salt = $user['salt'];
            $tokenHash = hash("sha256", $token . $salt);

            if ($tokenHash == $actualHash) {
                return $actualHash;
            }
        }

        return $res;
    }


    /**
     * Delete a hash from the database
     * @param $hash Hash to delete
     * @return bool True if successful
     */
    public function delete($hash) {
        $sql =<<<SQL
DELETE from $this->tableName
where hash=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $res = $statement->execute(array($hash));

        if($res){
            return true;
        }
        else{
            return false;
        }

    }

    /**
     * Generate a random validator string of characters
     * @param int $len Length to generate, default is 32
     * @return string Validator string
     */
    public function createValidator($len = 32) {
        $bytes = openssl_random_pseudo_bytes($len / 2);
        return bin2hex($bytes);
    }

    /**
     * Generate a random salt string of characters for password salting
     * @param int $len Length to generate, default is 16
     * @return string Salt string
     */
    public static function randomSalt($len = 16) {
        $bytes = openssl_random_pseudo_bytes($len / 2);
        return bin2hex($bytes);
    }

}