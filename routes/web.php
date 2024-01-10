<?php

use App\Http\Controllers\ReportsController;
use App\Models\Subject;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\StudentsReportsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode1 = Artisan::call('config:cache');
    // return what you want
    return [$exitCode,$exitCode1];
});

Auth::routes();
Route::group(['middleware' => 'auth'], function () {

        Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
        //Route::get('/student/edit', [DashboardController::class, 'tableEdit'])->name('student.edit');
        Route::delete('student.destroy/{id}', [DashboardController::class, 'studentDestroy'])->name('student.destroy');
        ####################### Start Site Configuration ####################################
        Route::get('/departments', [DashboardController::class, 'Departments'])->name('Departments');
        Route::get('/add-department', [DashboardController::class, 'CreateDepartment'])->name('add.Department');
        Route::post('/store-department', [DashboardController::class, 'storeDepartment'])->name('store.Department');
        Route::delete('department.destroy/{id}', [DashboardController::class, 'DestroyDepartment'])->name('department.destroy');

        Route::get('/add-specialize', [DashboardController::class, 'CreateSpecialize'])->name('add.specialize');
        Route::post('/store-specialize', [DashboardController::class, 'storeSpecialize'])->name('store.Specialize');
        Route::get('/add-group-department-specialize', [DashboardController::class, 'createGrDepSp'])->name('add.GrDepSp');
        Route::post('/store-group-department-specialize', [DashboardController::class, 'storeGrDepSp'])->name('store.GrDepSp');
        ############################################################
        Route::get('/subjects', [DashboardController::class, 'Subjects'])->name('Subjects');
        Route::get('/add-subjects', [DashboardController::class, 'CreateSubjects'])->name('add.Subjects');
        Route::post('/store-subjects', [DashboardController::class, 'storeSubjects'])->name('store.Subjects');
        Route::delete('/subjects.destroy/{id}', [DashboardController::class, 'DestroySubjects'])->name('subjects.destroy');
        ##########################################################################
        Route::get('/students', [StudentController::class, 'Students'])->name('Students');
        Route::get('/add-students', [StudentController::class, 'CreateStudents'])->name('add.Students');
        Route::post('/store-students', [StudentController::class, 'storeStudents'])->name('store.Students');
        Route::delete('/students.destroy/{id}', [StudentController::class, 'DestroyStudents'])->name('Students.destroy');
        Route::get('/update-students', [StudentController::class, 'updateStudents'])->name('update.students');
        Route::post('/store-updated-students', [StudentController::class, 'storeUpdatedStudents'])->name('store.updated.students');
        Route::get('/update-students-seat-number', [StudentController::class, 'updateStudentsSeatNo'])->name('update.students.seatNo');
        Route::post('/store-updated-students-seat-number', [StudentController::class, 'storeUpdatedStudentsSeatNo'])->name('store.updated.students.seatNo');

        Route::post('get-department-by-group', [StudentController::class, 'getDepartment']);
        Route::post('get-subject-by-department', [StudentController::class, 'getsubject']);
        Route::post('get-specializes-by-department', [StudentController::class, 'getSpecializes']);
        ##########################################################################
        Route::get('/bonus-degree/{id}', [DashboardController::class, 'bonusDegree'])->name('bonusDegree');
        Route::post('/store-bonus-degree/{id}', [DashboardController::class, 'StoreBonusDegree'])->name('StoreBonusDegree');

        ################################## RESULTS ########################################################
        Route::get('get-results-students', [ResultController::class, 'getResults'])->name('getResults');
        Route::get('results-transfer-students', [ResultController::class, 'TransferStudentResults'])->name('TransferStudentResults');
        Route::get('get-results-transfer-students', [ResultController::class, 'getTransferStudentResults'])->name('getTransferStudentResults');
        Route::post('store-results-transfer-students', [ResultController::class, 'storeTransferStudentResults'])->name('storeTransferStudentResults');
        Route::get('show-results-student', [ResultController::class, 'showResults'])->name('show.Results');
        Route::get('upload-results-students', [ResultController::class, 'uploadResults'])->name('uploadResults');
        Route::post('store-results-students', [ResultController::class, 'storeResults'])->name('storeResults');
        Route::get('table-results-students', [ResultController::class, 'tableResults'])->name('tableResults');
        Route::get('data-table-results-students', [ResultController::class, 'dataTableResultsStudents'])->name('dataTableResultsStudents');

        ################################## END RESULTS ########################################################
        Route::get('create-year', [ResultController::class, 'createYear'])->name('createYear');
        Route::Post('store-year', [ResultController::class, 'storeYear'])->name('storeYear');

           ################################ Start Upgrade results ##########################
        Route::get('add-bonus',[ResultController::class,'addBonus'])->name('add.bonus');
        Route::post('upgrade-bonus',[ResultController::class,'upgradeBonus'])->name('upgrade.bonus');
        Route::post('upgrade-groups',[ResultController::class,'upgradeGroups'])->name('upgrade.groups');

        ################################ End Upgrade results ##########################

        ################################ Start Reports ##########################
        Route::get('reports',[ReportsController::class,'chooseReports'])->name('choose.reports');
        Route::get('download-reports', [ReportsController::class,'reports'])->name('reports');
        ################################ END Reports ##########################
        Route::get('students/reports', [StudentsReportsController::class, 'show'])->name('student-reports');
        Route::post('students/reports/filter-students', [StudentsReportsController::class, 'dataTableResultsStudents'])->name('filter-students');
        Route::get('absent-students' , [StudentsReportsController::class , 'AbsentStudents'])->name('absent-students');
        Route::get('/search-students', [StudentsReportsController::class , 'searchStudentsBySiteNum'])->name('searchStudentsBySiteNum');
        Route::post('export-absent-students', [StudentsReportsController::class, 'ExportAbsentStudents'])->name('exportAbsentStudents');

####################### End Site Configuration ####################################
});
