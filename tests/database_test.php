<?php declare(strict_types=1);

use Nui\Facade\DB;

// require  __DIR__ . "/../boostrap/autoload.php";
// $app = require  __DIR__ . "/../boostrap/app.php";
require __DIR__ . '/app_load.php';

/*
$cfg = config('database');

// $connection = new PdoDB(
//     $cfg['driver'],
//     $cfg['database'],
//     $cfg['host'] . ':' . $cfg['port'],
//     $cfg['username'],
//     $cfg['password'],
// );
//
$pdo = new PDO(
    sprintf('%s:dbname=%s;host=%s',
        $cfg['driver'],
        $cfg['database'],
        $cfg['host'] . ':' . $cfg['port']),
    $cfg['username'],
    $cfg['password'],
);

$connection = new \Nui\Database\MySqlConnection(
    $pdo,
    $cfg['database'],
    '',
    [
        // 'sticky' => true,
        // PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
);


$grammar = new MySqlGrammar();

$q = new Builder(
    $connection,
    $grammar
);

$data = $q->from('user')
    ->select('*')
    ->where('id', 3)
    ->get();
dump($data);
dump($data->toArray());

*/


// Application::getInstance()->make('http.kernel')->bootstrap();
// $db = Application::getInstance()->make('db.connection');



$data =
    DB::table('user')
    ->select('*')
    // ->where('id', 3)
    ->get();

dump($data);
dump($data->toArray());

