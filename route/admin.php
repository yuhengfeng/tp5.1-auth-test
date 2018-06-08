<?php
// +----------------------------------------------------------------------
// | 后台路由
// +----------------------------------------------------------------------

//登陆
Route::get('admin/auth/login', 'admin/Login/showLoginForm')->name('admin.login');
Route::post('admin/auth/login', 'admin/Login/login')->name('admin.postLogin');
Route::get('admin/auth/logout', 'admin/Login/logout')->name('admin.logout');
Route::get('/403', function (){
        return view('admin@error/403');
})->name('admin.403');


Route::group('admin', function () {
    //首页
    Route::get('/', 'admin/Index/index')->name('admin.home');

    //管理员
    Route::get('/administrator/account', 'admin/Administrator/account');
    Route::post('/administrator/account', 'admin/Administrator/account');
    Route::get('/administrator/list', 'admin/Administrator/show')->name('admin.manager.list');
    Route::get('/administrator/create', 'admin/Administrator/create');
    Route::post('/administrator/create', 'admin/Administrator/create');
    Route::get('/administrator/edit/:id', 'admin/Administrator/edit');
    Route::post('/administrator/edit/:id', 'admin/Administrator/edit');
    Route::get('/administrator/delete/:id', 'admin/Administrator/delete');

    Route::post('/manager/save', 'admin/Administrator/save')->name('admin.manager.save');
    Route::get('/manager/lock', 'admin/Administrator/lock')->name('admin.manager.lock');

    //菜单管理
    Route::get('/menus/list', 'admin/Menus/show')->name('admin.menus.list');
    Route::get('/menus/create', 'admin/Menus/create');
    Route::post('/menus/create', 'admin/Menus/create');
    Route::get('/menus/edit/:id', 'admin/Menus/edit');
    Route::post('/menus/edit/:id', 'admin/Menus/edit');
    Route::get('/menus/delete/:id', 'admin/Menus/delete');

    //角色管理
    Route::get('/role/list', 'admin/Role/show')->name('admin.role.list');
    Route::get('/role/create', 'admin/Role/create');
    Route::post('/role/create', 'admin/Role/create');
    Route::get('/role/edit/:id', 'admin/Role/edit');
    Route::post('/role/edit/:id', 'admin/Role/edit');
    Route::get('/role/delete/:id', 'admin/Role/delete');
    //分类管理
    Route::get('/category/list', 'admin/Category/show')->name('admin.category.show');
    Route::get('/category/create', 'admin/Category/create')->name('admin.category.create');

})->middleware('Auth');

