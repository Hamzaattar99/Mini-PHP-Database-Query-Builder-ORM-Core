<?php

require '../vendor/autoload.php';

use App\Core\Database\DB;
use App\Core\Database\Schema;
use App\Core\Database\Table;

// ****this a test to get all rows from all columns****
//$users = DB::table("users")->get(); 

// ****this a test to get all rows from specific columns****
/* $users = DB::table('users')
    ->select(
        'id',
        'password'
    )
    ->get(); */

// ****this a test to get specific rows from all columns****
   /* $users = DB::table('users')
        ->where('id', 1)
        ->get(); */


// ****this a test to get all rows from specific columns****
/*$users = DB::table('users')
    ->where('password', '1333')
    ->where('id', 2)
    ->get();*/


// ****this a test to get first row from all columns****
/*$users = DB::table('users')
    ->where('id', 1)
    ->first();*/

// ****this a test to insert row with diffrent columns****
/*$result = DB::table('users')->insert([
    'username' => 'HOHO',
    'password' => 'HOh1@gmail.com',
    'description' => 'Hello this just test text!?'
]); 

var_dump($result);*/

// ****this a test to update specific rows with specific columns****
/*$result = DB::table('users')
    ->where('id', 1)
    ->update([
        'username' => 'Updated Name',
        'password' => '1234567890'
    ]);

var_dump($result); */


// ****this a test to delete specific rows ****
/*$result = DB::table('users')
    ->where('id', 1)
    ->delete();

var_dump($result);*/

// ****this a test to insert specific values into another table ****
/*DB::table('products')->insert([
    'title' => 'Laptop',
    'price' => 1500,
    'stock' => 10
]);

print_r(
    DB::table('products')->get()
);*/

//***************************************************************************** */
/*$users = DB::table('users')
    ->orderBy('id', 'DESC')
    ->get();*/

//***************************************************************************** */
/*$users = DB::table('users')
    ->limit(5)
    ->get();*/

//***************************************************************************** */
/*$users = DB::table('products')
    ->where('price', '<', 1000)
    ->get();*/

//***************************************************************************** */
/*$users = DB::table('users')
    ->where('username', 'LIKE', '%a%')
    ->get();*/


//***************************************************************************** */
/*$users = DB::table('products')
    ->where('price', '<', 100)
    ->orWhere('stock', '>', 10)
    ->get();*/

//***************************************************************************** */
/*$users = DB::table('users')
    ->join(
        'posts',
        'users.id',
        '=',
        'posts.user_id'
    )
    ->get();*/

//***************************************************************************** */
/*$users =
DB::table('users')
    ->groupBy('i')
    ->get();*/

//***************************************************************************** */
  /*$users  = DB::table('users')
    ->select('username')
    ->selectRaw('COUNT(id) as total_users')
    ->groupBy('username')
    ->get();*/

//***************************************************************************** */
/*$users  = DB::table('users')
    ->count();*/

//***************************************************************************** */
/*$users = DB::table('users')
    ->where('username', '=', 'HOHO')
    ->where('id', '=', 5)
    ->count(); */

//***************************************************************************** */
/*var_dump(
    DB::table('users')
        ->where(
            'username',
            '=',
            'ali123'
        )
        ->exists()
);*/


//***************************************************************************** */
/*$users = DB::table('users')
    ->enableDebug()
    ->where('id', '=', 2)
    ->get();*/

//***************************************************************************** */
/*DB::clearQueryLog();

DB::table('products')->get();

print_r(DB::getQueryLog()); */

//***************************************************************************** */
/*DB::beginTransaction();

try {

    DB::table('users')->insert([
        'name' => 'Hamza'
    ]);

    DB::table('users')->insert([
        'name' => 'Ali'
    ]);

    DB::commit();

} catch (Exception $e) {

    DB::rollback();
}*/

//***************************************************************************** */
/*$result = DB::table('users')
    ->paginate(5, 1);*/

//***************************************************************************** */
/*Schema::create('user1', function (Table $table) {

    $table->column('id', 'int')
          ->primary()
          ->autoIncrement();

    $table->column('username', 'varchar', 100);

    $table->column('email', 'varchar', 255)
          ->nullable();

    $table->timestamps();

});*/

//***************************************************************************** */
//********
/*Schema::table('user1', function (Table $table) {

     $table->dropColumns(['shail', 'ehail']);
    // $table->column('shail', 'varchar', 255)
    //   ->nullable();
    //   $table->column('ehail', 'varchar', 55)
    //   ->nullable();

});*/

//***************************************************************************** */
 use App\Core\Database\Migration\MigrationRepository;

/*$repo = new MigrationRepository();

echo "Repository Ready";*/

//***************************************************************************** */
//  $repo = new MigrationRepository();

// $repo->log(
//     '2026_06_03_100000_create_users_table',
//     1
// );

// print_r(
//     $repo->getRan()
// );

// echo $repo->getLastBatchNumber();

use App\Core\Database\Migration\MigrationLoader;

// $loader = new MigrationLoader();

// echo '<pre>';

// print_r(
//     $loader->getFiles()
// );

// print_r(
//     $loader->getMigrationNames()
// );

// echo '</pre>';
// use App\Core\Database\Migration\Migrator;
// $repository =
//     new MigrationRepository();

// $loader =
//     new MigrationLoader();

// $migrator =
//     new Migrator(
//         $repository,
//         $loader
//     );

// $migrator->migrate();


use App\Core\Database\Migration\MigrationManager;

// $manager = new MigrationManager();

// $manager->migrate();

// $manager = new MigrationManager();

// $manager->rollback();

// $manager = new MigrationManager();

// echo '<pre>';

// print_r(
//     $manager->getExecuted()
// );

// echo '</pre>';

// $manager = new MigrationManager();

// echo '<pre>';

// print_r(
//     $manager->getPending()
// );

// echo '</pre>';

$manager = new MigrationManager();

// echo '<pre>';

// print_r(
//     $manager->status()
// );

// echo '</pre>';

// if ($manager->hasPending()) {

//     $manager->migrate();
// } 
//***************************************************************************** */
// Schema::table('pooo', function ($table) {

// //   $table->dropForeign('pooo_ibfk_2');
//   $table->foreign('user_id')
//       ->references('id')
//       ->on('users')
//       ->name('fk_posts_users');
// });

//***************************************************************************** */
// Schema::create('pooo', function ($table) {

//     $table->column('id', 'bigint')
//           ->autoIncrement()
//           ->primary();

//     $table->column('user_id', 'int');

//     $table->foreign('user_id')
//           ->references('id')
//           ->on('users');
// });
//***************************************************************************** */
/*echo Schema::create('p3', function ($table) {

    $table->column('id', 'bigint')
          ->autoIncrement()
          ->primary();

    $table->foreignId('user_id');

    $table->foreign('user_id')
          ->references('id')
          ->on('pooo')
          ->name('fk_posts3_users')
          ->onDelete('CASCADE');
}); */
//***************************************************************************** */
//DB::table('users')->get();

//print_r(DB::getQueryLog());



 //echo "<pre>";

 //print_r($result);

 //echo "</pre>";

?>