<?php
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthRecordController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GrowthMonitoringController;
use App\Http\Controllers\GrowthChartController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/users', [AdminController::class, 'users'])
            ->name('users');

    });
/*
|--------------------------------------------------------------------------
| PUBLIC AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
|
| These routes must remain accessible without being logged in.
|
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.submit');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| PROTECTED APPLICATION ROUTES
|--------------------------------------------------------------------------
|
| Everything inside this group requires the user to be logged in.
|
*/

Route::middleware('auth')->group(function () {


    /*
    |--------------------------------------------------------------------------
    | CHILD HEALTH RECORDS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/children/{child}/health-records',
        [HealthRecordController::class, 'index']
    )->name('health-records.index');

    Route::get(
        '/children/{child}/health-records/create',
        [HealthRecordController::class, 'create']
    )->name('health-records.create');

    Route::post(
        '/children/{child}/health-records',
        [HealthRecordController::class, 'store']
    )->name('health-records.store');


    /*
    |--------------------------------------------------------------------------
    | HEALTH RECORD REPORT
    |--------------------------------------------------------------------------
    |
    | Keep this BEFORE the {healthRecord} show route so "report"
    | is not interpreted as a health-record ID.
    |
    */

    Route::get(
        '/children/{child}/health-records/report',
        [ReportController::class, 'childReport']
    )->name('health-records.report');


    /*
    |--------------------------------------------------------------------------
    | HEALTH RECORD CRUD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/children/{child}/health-records/{healthRecord}',
        [HealthRecordController::class, 'show']
    )->name('health-records.show');

    Route::get(
        '/children/{child}/health-records/{healthRecord}/edit',
        [HealthRecordController::class, 'edit']
    )->name('health-records.edit');

    Route::put(
        '/children/{child}/health-records/{healthRecord}',
        [HealthRecordController::class, 'update']
    )->name('health-records.update');

    Route::delete(
        '/children/{child}/health-records/{healthRecord}',
        [HealthRecordController::class, 'destroy']
    )->name('health-records.destroy');


    /*
    |--------------------------------------------------------------------------
    | SHARE HEALTH REPORT
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/children/{child}/health-records/{healthRecord}/share',
        [HealthRecordController::class, 'shareReport']
    )->name('health-records.share');


    /*
    |--------------------------------------------------------------------------
    | SEND FOLLOW-UP REMINDER
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/children/{child}/health-records/{healthRecord}/send-reminder',
        [HealthRecordController::class, 'sendReminder']
    )->name('health-records.send-reminder');


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    
Route::get('/dashboard', function () {

    /*
    |--------------------------------------------------------------------------
    | CHILD STATISTICS
    |--------------------------------------------------------------------------
    */

    $totalChildren = \App\Models\Child::count();

    $maleChildren = \App\Models\Child::where('sex', 'Male')->count();

    $femaleChildren = \App\Models\Child::where('sex', 'Female')->count();


    /*
    |--------------------------------------------------------------------------
    | RECENT CHILDREN
    |--------------------------------------------------------------------------
    */

    $recentChildren = \App\Models\Child::latest()
        ->take(5)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | USER STATISTICS
    |--------------------------------------------------------------------------
    */

    $totalUsers = \App\Models\User::count();

    $pendingUsers = \App\Models\User::where(
        'status',
        'pending'
    )->count();

    $approvedUsers = \App\Models\User::where(
        'status',
        'approved'
    )->count();

    $suspendedUsers = \App\Models\User::where(
        'status',
        'suspended'
    )->count();


    /*
    |--------------------------------------------------------------------------
    | RECENT USER REGISTRATIONS
    |--------------------------------------------------------------------------
    */

    $recentUsers = \App\Models\User::latest()
        ->take(5)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | RETURN DASHBOARD WITH ALL REQUIRED DATA
    |--------------------------------------------------------------------------
    */

    return view('dashboard', [
        'totalChildren' => $totalChildren,
        'maleChildren' => $maleChildren,
        'femaleChildren' => $femaleChildren,
        'recentChildren' => $recentChildren,
        'totalUsers' => $totalUsers,
        'pendingUsers' => $pendingUsers,
        'approvedUsers' => $approvedUsers,
        'suspendedUsers' => $suspendedUsers,
        'recentUsers' => $recentUsers,
    ]);

})->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | GROWTH MONITORING
    |--------------------------------------------------------------------------
    |
    | Main growth monitoring page.
    |
    */

    Route::get(
        '/growth-monitoring',
        [GrowthMonitoringController::class, 'index']
    )->name('growth-monitoring.index');


    /*
    |--------------------------------------------------------------------------
    | CHILDREN
    |--------------------------------------------------------------------------
    |
    | Standard CRUD routes:
    |
    | GET       /children              -> index
    | GET       /children/create       -> create
    | POST      /children              -> store
    | GET       /children/{child}      -> show
    | GET       /children/{child}/edit -> edit
    | PUT/PATCH /children/{child}      -> update
    | DELETE    /children/{child}      -> destroy
    |
    */

    Route::resource(
        'children',
        ChildController::class
    );


    /*
    |--------------------------------------------------------------------------
    | INDIVIDUAL CHILD GROWTH CHARTS
    |--------------------------------------------------------------------------
    |
    | Route:
    | /children/{child}/growth-charts
    |
    */

    Route::get(
        '/children/{child}/growth-charts',
        [GrowthChartController::class, 'show']
    )->name('children.growth-charts');


    /*
    |--------------------------------------------------------------------------
    | CHILD HEALTH REPORT
    |--------------------------------------------------------------------------
    |
    | Main child health report page.
    |
    | URL:
    | /reports/child/{child}
    |
    */

    Route::get(
        '/reports/child/{child}',
        [ReportController::class, 'childReport']
    )->name('reports.child');


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD CHILD HEALTH REPORT PDF
    |--------------------------------------------------------------------------
    |
    | URL:
    | /children/{child}/report/download
    |
    */

    Route::get(
        '/children/{child}/report/download',
        [ReportController::class, 'downloadChildReport']
    )->name('reports.child.download');


    /*
    |--------------------------------------------------------------------------
    | SHARE CHILD HEALTH REPORT
    |--------------------------------------------------------------------------
    |
    | URL:
    | /children/{child}/report/share
    |
    */

    Route::post(
        '/children/{child}/report/share',
        [ReportController::class, 'shareChildReport']
    )->name('children.report.share');


    /*
    |--------------------------------------------------------------------------
    | REPORTS DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports',
        [ReportController::class, 'index']
    )->name('reports.index');


    /*
    |--------------------------------------------------------------------------
    | GENERATE SYSTEM MONITORING REPORT
    |--------------------------------------------------------------------------
    */

    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD SYSTEM MONITORING REPORT
    |--------------------------------------------------------------------------
    */

    Route::post('/reports/download', [ReportController::class, 'download'])
    ->name('reports.download');

    /*
    |--------------------------------------------------------------------------
    | NUTRITION REPORTS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports/nutrition',
        [ReportController::class, 'nutritionReport']
    )->name('reports.nutrition');


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD NUTRITION REPORT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports/nutrition/download',
        [ReportController::class, 'downloadNutritionReport']
    )->name('reports.nutrition.download');

});

    /*
    |--------------------------------------------------------------------------
    | Administrator Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/users', [AdminController::class, 'users'])
                ->name('users');

            Route::post('/users/{user}/approve', [AdminController::class, 'approve'])
                ->name('users.approve');

            Route::post('/users/{user}/reject', [AdminController::class, 'reject'])
                ->name('users.reject');

            Route::post('/users/{user}/suspend', [AdminController::class, 'suspend'])
                ->name('users.suspend');

            Route::post('/users/{user}/activate', [AdminController::class, 'activate'])
                ->name('users.activate');

            Route::put('/users/{user}/role', [AdminController::class, 'updateRole'])
                ->name('users.role');
        });

        Route::get('/weight-records', [
    HealthRecordController::class,
    'all'
])->name('weight-records.index');


Route::get(
    '/growth-charts',
    [\App\Http\Controllers\GrowthChartController::class, 'listChildren']
)->name('growth-charts.index');