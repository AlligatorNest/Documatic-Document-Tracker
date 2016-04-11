<?php
require_once ("MysqliDb.php");
error_reporting(E_ALL);
$prefix = 'tbl';
$db = new Mysqlidb('localhost', 'root', '', 'documents');
if(!$db) die("Database error");
$mysqli = new mysqli ('localhost', 'root', '', 'documents');
$db = new Mysqlidb($mysqli);
$db = new Mysqlidb(Array (
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'db' => 'documents',
                'prefix' => $prefix,
                'charset' => null));
if(!$db) die("Database error");
$db->setTrace(true);
$tables = Array (
    'users' => Array (
        'userId' => 'int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (userId)',
        'username' => 'char(10) NOT NULL ',
        'password' => 'text NOT NULL ',
        'adddate' => 'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP'

    ),
    'userCategoryXref' => Array (
        'userCategoryId' => 'int(11) not null AUTO_INCREMENT, PRIMARY KEY (userCategoryId)',
        'userId' => 'int(10) NOT NULL ',
        'categoryId' => 'int(10) NOT NULL ',
        'adddate' => 'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP'
    ),
    'documentCategoryXref' => Array (
        'documentCategoryId' => 'int(11) not null AUTO_INCREMENT, PRIMARY KEY (documentCategoryId)',
        'documentId' => 'int(10) NOT NULL',
        'categoryId' => 'int(10) NOT NULL ',
        'adddate' => 'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP'
    ),
    'documentUserXref' => Array (
        'documentUserId' => 'int(11) not null AUTO_INCREMENT, PRIMARY KEY (documentUserId)',
        'documentId' => 'int(10) NOT NULL',
        'userId' => 'int(10) NOT NULL ',
        'adddate' => 'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP'
    ),
    'documentUserAccess' => Array (
        'documentUserAccessId' => 'int(11) not null AUTO_INCREMENT, PRIMARY KEY (documentUserAccessId)',
        'documentId' => 'int(10) NOT NULL',
        'userId' => 'int(10) NOT NULL',
        'accessDate' => 'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP',
        'adddate' => 'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP'
    ),
    'category' => Array (
        'categoryId' => 'int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (categoryId)',
        'category' => 'char(50) NOT NULL',
        'adddate' => 'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP'
    ),
    'document' => Array (
        'documentId' => 'int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (documentId)',
        'documentName' => 'char(50) NOT NULL',
        'documentPath' => 'char(255) NULL',
        'adddate' => 'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP'
    )
);
$data = Array (
    'users' => Array (
        Array ('username' => 'Dr. John',
               'password' => 'John',
               'adddate' => $db->now()
        ),
        Array ('username' => 'Dr. Bill',
               'password' => 'Bill',
               'adddate' => $db->now()
        ),
        Array ('username' => 'Dr. Eric',
               'password' => 'Eric',
               'adddate' => $db->now()
        )
    ),
    'userCategoryXref' => Array (
        Array ('userId' => 1,
               'categoryId' => 1,
               'adddate' => $db->now()
        ),
        Array ('userId' => 1,
               'categoryId' => 2,
               'adddate' => $db->now()
        ),
        Array ('userId' => 2,
               'categoryId' => 1,
               'adddate' => $db->now()
        ),
        Array ('userId' => 2,
               'categoryId' => 3,
               'adddate' => $db->now()
        )
    ),
    'documentCategoryXref' => Array (
        Array ( 'documentId' => 1,
                'categoryId' => 1,
                'adddate' => $db->now()
        ),
        Array ( 'documentId' => 2,
                'categoryId' => 1,
                'adddate' => $db->now()
        ),
        Array ( 'documentId' => 3,
                'categoryId' => 1,
                'adddate' => $db->now()
        ),
        Array ( 'documentId' => 4,
                'categoryId' => 1,
                'adddate' => $db->now()
        )
    ),
    'category' => Array (
        Array ( 'category' => 'General Prac',
                'adddate' => $db->now()
        ),
        Array ( 'category' => 'Neurology',
                'adddate' => $db->now()
        ),
        Array ( 'category' => 'Anesthesiology',
                'adddate' => $db->now()
        ),
        Array ( 'category' => 'Dermatology',
                'adddate' => $db->now()
        )
      ),
      'document' => Array (
          Array ( 'documentName' => 'Diet Guidlines',
                  'adddate' => $db->now()
          ),
          Array ( 'documentName' => 'HIPPA Compliance 2016',
                  'adddate' => $db->now()
          ),
          Array ( 'documentName' => 'Ethics Code',
                  'adddate' => $db->now()
          ),
          Array ( 'documentName' => 'Diagnostic Codes 2016',
                  'adddate' => $db->now()
          )
        )
);
function createTable ($name, $data) {
    global $db;
    $count = 0;
    //$q = "CREATE TABLE $name (id INT(9) UNSIGNED PRIMARY KEY NOT NULL";
    $db->rawQuery("DROP TABLE IF EXISTS $name");
    //$q = "CREATE TABLE $name (id INT(9) UNSIGNED PRIMARY KEY AUTO_INCREMENT";
    $q = "CREATE TABLE $name (";

    foreach ($data as $k => $v) {
        if ($count == 0) {
          $q .= " $k $v";
        } else {
          $q .= ", $k $v";
        };

        $count ++;

    }
    $q .= ");";
    echo $q . '<br>';
    $db->rawQuery($q);
}
// rawQuery test
foreach ($tables as $name => $fields) {
    $db->rawQuery("DROP TABLE ".$prefix.$name);
    createTable ($prefix.$name, $fields);
}
if (!$db->ping()) {
    echo "db is not up";
    exit;
}

// insert test with autoincrement

foreach ($data as $name => $datas) {
    foreach ($datas as $d) {
        $id = $db->insert($name, $d);
        if ($id)
            $d['id'] = $id;
        else {
            echo "failed to insert: ".$db->getLastQuery() ."\n". $db->getLastError();
            exit;
        }
    }
}


/*// insert without autoincrement
$q = "create table {$prefix}test (id int(10), name varchar(10));";
$db->rawQuery($q);
*/

echo "All done\n";
echo "Memory usage: ".memory_get_peak_usage()."\n";
?>
