<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
    Route::get('/', function () {
        $tasks = App\Task::orderBy('created_at', 'asc')->get();
        return view('task', [
            'tasks' => $tasks
        ]);
    });

    Route::post('/task', function (\Illuminate\Http\Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        }
        App\Task::create($request->all());

        return redirect('/');
    });
});


Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('tasks', 'TaskController@index');

    Route::post('task', 'TaskController@store');

    Route::delete('task/{task}', 'TaskController@destroy');

    Route::get('/home', 'HomeController@index');
});
