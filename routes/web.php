<?php

// Removed ProfileController import - no longer used due to route conflict
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\AdmitCardController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeatingPlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard AJAX and API routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/dashboard/stats-api', [DashboardController::class, 'getStatsApi'])->name('dashboard.stats-api');
    Route::get('/dashboard/export-report', [DashboardController::class, 'exportReport'])->name('dashboard.export-report');
});

// Profile routes removed - duplicate route conflict with custom implementation below

// Student Management Routes - Require authentication and admin roles
Route::middleware(['auth', 'role:superadmin,teacher'])->group(function () {
    Route::resource('students', StudentController::class);
    Route::get('/students-export', [StudentController::class, 'export'])->name('students.export');
    Route::post('/students-import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/students/class/{classId}/sections', [StudentController::class, 'getSections'])->name('students.classes.sections');
});

// Class & Section Management Routes - Require authentication and superadmin role
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::resource('classes', ClassController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('subjects', SubjectController::class);
});

// Exam Management Routes - Require authentication and admin roles
Route::middleware(['auth', 'role:superadmin,teacher'])->group(function () {
    Route::resource('exams', ExamController::class);
    Route::get('/exams/{exam}/generate-admit-cards', [ExamController::class, 'generateAdmitCards'])->name('exams.generate-admit-cards');
    Route::get('/exams/class/{classId}/subjects', [ExamController::class, 'getSubjects'])->name('exams.class.subjects');
});

// Admit Card Management Routes - Require authentication
Route::middleware(['auth'])->group(function () {
    Route::resource('admit-cards', AdmitCardController::class);
    Route::get('/admit-cards/{admitCard}/download', [AdmitCardController::class, 'download'])->name('admit-cards.download');
    Route::post('/admit-cards/bulk-generate', [AdmitCardController::class, 'bulkGenerate'])->name('admit-cards.bulk-generate');
    Route::post('/admit-cards/bulk-print', [AdmitCardController::class, 'bulkPrint'])->name('admit-cards.bulk-print');
    Route::get('/admit-cards/get-students', [AdmitCardController::class, 'getStudents'])->name('admit-cards.get-students');
});

// Fee Management Routes - Require authentication and superadmin/accountant roles
Route::middleware(['auth', 'role:superadmin,accountant'])->group(function () {
    Route::resource('fees', FeeController::class);
    Route::patch('/fees/{fee}/mark-as-paid', [FeeController::class, 'markAsPaid'])->name('fees.mark-as-paid');
    Route::patch('/fees/{fee}/mark-as-overdue', [FeeController::class, 'markAsOverdue'])->name('fees.mark-as-overdue');
    Route::get('/fees/{fee}/receipt', [FeeController::class, 'generateReceipt'])->name('fees.receipt');
    Route::post('/fees/create-for-class', [FeeController::class, 'createForClass'])->name('fees.create-for-class');
    Route::get('/fees-monthly-report', [FeeController::class, 'monthlyReport'])->name('fees.monthly-report');
    Route::get('/fees-export-monthly-report', [FeeController::class, 'exportMonthlyReport'])->name('fees.export-monthly-report');
    Route::get('/fees/get-students', [FeeController::class, 'getStudents'])->name('fees.get-students');
});

// Payment Management Routes - Require authentication and superadmin/accountant roles
Route::middleware(['auth', 'role:superadmin,accountant'])->group(function () {
    Route::resource('payments', PaymentController::class);
    Route::post('/payments/process-payment', [PaymentController::class, 'processPayment'])->name('payments.process-payment');
    Route::get('/payments/get-student-fees', [PaymentController::class, 'getStudentFees'])->name('payments.get-student-fees');
    Route::get('/payments/statistics', [PaymentController::class, 'statistics'])->name('payments.statistics');
});

// User Management Routes - Super Admin Only
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/bulk-update-role', [UserController::class, 'bulkUpdateRole'])->name('users.bulk-update-role');
    Route::get('/users/role-stats', [UserController::class, 'getRoleStats'])->name('users.role-stats');
});

// Seating Plan Management Routes - Require authentication and admin roles
Route::middleware(['auth', 'role:superadmin,teacher'])->group(function () {
    Route::get('/seating-plans', [SeatingPlanController::class, 'index'])->name('seating-plans.index');
    Route::get('/exam/{exam}/seating-plan/create', [SeatingPlanController::class, 'create'])->name('exam.seating.create');
    Route::post('/exam/{exam}/seating-plan/generate', [SeatingPlanController::class, 'generate'])->name('exam.seating.generate');
    Route::get('/exam/{exam}/seating-plan', [SeatingPlanController::class, 'show'])->name('exam.seating.show');
    Route::post('/exam/{exam}/seating-plan/swap-students', [SeatingPlanController::class, 'swapStudents'])->name('exam.seating.swap');
    Route::get('/exam/{exam}/seating-plan/regenerate', [SeatingPlanController::class, 'regenerate'])->name('exam.seating.regenerate');
    Route::get('/exam/{exam}/seating-plan/export', [SeatingPlanController::class, 'exportToPdf'])->name('exam.seating.export');
    Route::get('/exam/{exam}/seating-plan/analytics', [SeatingPlanController::class, 'getAnalytics'])->name('exam.seating.analytics');
});

// My Profile Routes - Require authentication (all roles)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.view');
    Route::patch('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

require __DIR__.'/auth.php';
