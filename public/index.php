<?php

require '../vendor/autoload.php';

use App\Core\Database\DB;

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
$result = DB::table('users')
    ->paginate(5, 1);


 echo "<pre>";

 print_r($result);

 echo "</pre>";

?>