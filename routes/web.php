<?php

/*

Route sequence should be kept in the same order as it is now.

There are four sections of routes

1a : Employer public routes (without login)
1b : Employer inner routes (with login required)

2a : Admin public routes (without login)
2b : Admin inner routes (with login required)

3a : Candidate public routes with subdomain (without login)
3b : Candidate auth routes with subdomain (with login)

4a : Front site routes (all public)
4b : Front site auth routes (for candidate)

3c : Candidate public routes with slug (as folder) (without login)
3d : Candidate auth routes with slug (as folder) (with login)

*/

//Middlewares
$mw = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'admin_auth' => \App\Http\Middleware\IsAdmin::class,
    'employer_auth' => \App\Http\Middleware\IsEmployer::class,
    'candidate_auth' => \App\Http\Middleware\IsCandidate::class,
    'candidate_auth_front' => \App\Http\Middleware\IsCandidateFront::class,
    'essset' => \App\Http\Middleware\EssentialSettings::class,
    'setslug' => \App\Http\Middleware\SetEmployerSlug::class,
    'xss_sanitizer' => \App\Http\Middleware\XssSanitizer::class,        
];

use Illuminate\Support\Facades\Route;

/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*		 1a : Employer outer/public routes 		*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
Route::group(['prefix'=>'employer', 'middleware' => [$mw['essset']]], function() {
	$EmEmpCont = 'App\Http\Controllers\Employer\EmployersController';
	Route::get('', $EmEmpCont.'@loginView')->name('employer-login');
	Route::get('/login', $EmEmpCont.'@loginView')->name('employer-login-with');
	Route::post('/login-post', $EmEmpCont.'@login')->name('employer-login-post');
	Route::get('/forgot-password', $EmEmpCont.'@forgotPasswordView')->name('employer-forgot-password');
	Route::post('/forgot-password-post', $EmEmpCont.'@forgotPassword')->name('employer-forgot-password-post');
	Route::get('/reset-password/{token}', $EmEmpCont.'@resetPasswordView')->name('employer-reset-password');
	Route::post('/reset-password-post', $EmEmpCont.'@resetPassword')->name('employer-reset-password-post');
	Route::get('/activate-account/{token}', $EmEmpCont.'@activateAccount')->name('employer-activate-account');
});

/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*		 1b : Employer inner/auth routes 		*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
Route::group(['prefix'=>'employer','middleware'=>[$mw['essset'], $mw['employer_auth'], $mw['xss_sanitizer']]], function() {

	$EmEmpCont = 'App\Http\Controllers\Employer\EmployersController';
	Route::get('/profile', $EmEmpCont.'@profileView')->name('employer-profile');
	Route::post('/profile-save', $EmEmpCont.'@updateProfile')->name('employer-update-profile');
	Route::get('/password', $EmEmpCont.'@passwordView')->name('employer-password-view');
	Route::post('/password-save', $EmEmpCont.'@updatePassword')->name('employer-update-password');
	Route::get('/logout', $EmEmpCont.'@logout')->name('employer-logout');

	//Employer Users module routes
	Route::get('/team', $EmEmpCont.'@teamListView')->name('employer-team-list');
	Route::post('/team/data', $EmEmpCont.'@teamList')->name('employer-team-data');
	Route::get('/team/create-or-edit/{employer_id?}', $EmEmpCont.'@createOrEdit')->name('employer-team-create-or-edit');
	Route::get('/team/message/{employer_id}', $EmEmpCont.'@message')->name('employer-team-message');
	Route::post('/team/message-save', $EmEmpCont.'@messageSave')->name('employer-team-message-save');
	Route::post('/team/save', $EmEmpCont.'@saveEmployer')->name('employer-team-save');
	Route::post('/team/save-roles', $EmEmpCont.'@saveEmployerRoles')->name('employer-team-save-roles');
	Route::get('/team/status/{employer_id}/{status}', $EmEmpCont.'@changeStatus')->name('employer-team-status');
	Route::get('/team/delete/{employer_id}', $EmEmpCont.'@delete')->name('employer-team-delete');

	//Employerg settings routes
	$EmSetCont = 'App\Http\Controllers\Employer\SettingsController';
	Route::get('/settings/general', $EmSetCont.'@general')->name('employer-settings-general');
	Route::post('/settings/save-general', $EmSetCont.'@updateGeneral')->name('employer-settings-save-general');
	Route::get('/settings/branding', $EmSetCont.'@branding')->name('employer-settings-branding');
	Route::post('/settings/save-branding', $EmSetCont.'@updateBranding')->name('employer-settings-save-branding');
	Route::get('/settings/emails', $EmSetCont.'@emails')->name('employer-settings-emails');
	Route::post('/settings/save-emails', $EmSetCont.'@updateEmails')->name('employer-settings-save-emails');
	Route::get('/settings/css', $EmSetCont.'@css')->name('employer-settings-css');
	Route::post('/settings/save-css', $EmSetCont.'@updateCss')->name('employer-settings-update-css');

	//Employer Packages module routes
	$EmMemCont = 'App\Http\Controllers\Employer\MembershipsController';
	Route::get('/memberships', $EmMemCont.'@membershipsListView')->name('employer-memberships');
	Route::post('/memberships/data', $EmMemCont.'@membershipsList')->name('employer-memberships-data');
	Route::get('/memberships/renew', $EmMemCont.'@renewForm')->name('employer-memberships-create');
	Route::get('/memberships/invoice/{ticket_id}', $EmMemCont.'@invoice')->name('employer-memberships-invoice');
	Route::post('/stripe-payment', $EmMemCont.'@stripePayment')->name('employers-payment-stripe');
	Route::post('/offline-payment', $EmMemCont.'@offlinePayment')->name('employers-payment-offline');
	Route::get('/paypal-payment/{selected?}', $EmMemCont.'@paypalPayment')->name('employers-payment-paypal');

	//Employer Roles module routes
	$EmRolCont = 'App\Http\Controllers\Employer\RolesController';
	Route::get('/roles', $EmRolCont.'@listView')->name('employer-roles');
	Route::get('/role-permissions/{role_id}', $EmRolCont.'@getRolePermissions')->name('employer-roles-permissions');
	Route::post('/roles/save', $EmRolCont.'@saveRole')->name('employer-roles-save');
	Route::get('/roles/delete/{role_id}', $EmRolCont.'@delete')->name('employer-roles-delete');
	Route::post('/roles/add-permissions', $EmRolCont.'@addPermission')->name('employer-roles-add-permission');
	Route::post('/roles/remove-permissions', $EmRolCont.'@removePermission')->name('employer-roles-remove-permission');

	//Employer Candidates module routes
	$EmCanCont = 'App\Http\Controllers\Employer\CandidatesController';
	Route::get('/candidates', $EmCanCont.'@listView')->name('employer-candidates');
	Route::post('/candidates/data', $EmCanCont.'@data')->name('employer-candidates-data');
	Route::post('/candidates/bulk-action', $EmCanCont.'@bulkAction')->name('employer-candidates-bulk-action');
	Route::get('/candidates/resume/{candidate_id}', $EmCanCont.'@resume')->name('employer-candidates-resume');
	Route::post('/candidates/resume-download', $EmCanCont.'@resumeDownload')->name('employer-candidates-resume-download');
	Route::post('/candidates/excel', $EmCanCont.'@candidatesExcel')->name('employer-candidates-excel');
	Route::get('/candidates/message-view', $EmCanCont.'@messageView')->name('employer-candidates-message-view');
	Route::post('/candidates/message', $EmCanCont.'@message')->name('employer-candidates-message');
	Route::get('/candidates/create-or-edit/{candidate_id?}', $EmCanCont.'@createOrEdit')->name('employer-candidates-create-or-edit');
	Route::post('/candidates/save', $EmCanCont.'@save')->name('employer-candidates-save');
	Route::get('/candidates/delete/{candidate_id}', $EmCanCont.'@delete')->name('employer-candidates-delete');

	//Employer Candidate Favorites module routes
	$EmCanFavsCont = 'App\Http\Controllers\Employer\CandidateFavoritesController';
	Route::get('/candidate-favorites', $EmCanFavsCont.'@listView')->name('employer-candidate-favorites');
	Route::post('/candidate-favorites/data', $EmCanFavsCont.'@data')->name('employer-candidate-favorites-data');
	Route::get('/candidate-favorites/toggle/{candidate_id}', $EmCanFavsCont.'@toggle')->name('employer-candidate-favorites-toggle');

	//Employer Candidate Interviews module routes
	$EmCanIntCont = 'App\Http\Controllers\Employer\CandidateInterviewsController';
	Route::get('/candidate-interviews', $EmCanIntCont.'@listView')->name('employer-can-int');
	Route::post('/candidate-interviews/data', $EmCanIntCont.'@data')->name('employer-can-int-data');
	Route::get('/candidate-interviews/view-or-conduct/{interview_id}', $EmCanIntCont.'@viewOrConduct')->name('employer-can-int-view-or-con');
	Route::post('/candidate-interviews/save', $EmCanIntCont.'@save')->name('employer-can-int-save');

	//Employer Jobs module routes
	$EmJobCont = 'App\Http\Controllers\Employer\JobsController';
	Route::get('/jobs', $EmJobCont.'@listView')->name('employer-jobs');
	Route::post('/jobs/data', $EmJobCont.'@data')->name('employer-jobs-data');
	Route::get('/jobs/create-or-edit/{job_id?}', $EmJobCont.'@createOrEdit')->name('employer-jobs-create-or-edit');
	Route::post('/jobs/save', $EmJobCont.'@save')->name('employer-jobs-save');
	Route::get('/jobs/status/{job_id}/{status}', $EmJobCont.'@changeStatus')->name('employer-jobs-status');
	Route::get('/jobs/delete/{job_id}', $EmJobCont.'@delete')->name('employer-jobs-delete');
	Route::post('/jobs/excel', $EmJobCont.'@excel')->name('employer-jobs-excel');
	Route::get('/jobs/add-custom-field', $EmJobCont.'@addCustomField')->name('employer-jobs-add-custom-field');
	Route::get('/jobs/remove-custom-field/{job_id}', $EmJobCont.'@removeCustomField')->name('employer-jobs-remove-custom-field');
	Route::get('/jobs/assign-user-view', $EmJobCont.'@assignJobView')->name('employer-jobs-assign-user-view');
	Route::post('/jobs/assign-user', $EmJobCont.'@assignJob')->name('employer-jobs-assign-user');
	Route::post('/jobs/remove-assigned-user', $EmJobCont.'@removeAssignedUser')->name('employer-jobs-remove-assigned-user');
	Route::get('/jobs/apply-for-candidate/{job_id}', $EmJobCont.'@applyForCandidateView')->name('employer-apply-for-candidate-view');
	Route::post('/jobs/apply-for-candidate', $EmJobCont.'@applyForCandidate')->name('employer-apply-for-candidate');
	Route::get('/jobs/apply-for-candidate-resumes/{job_id}', $EmJobCont.'@applyForCandidateResumesList')->name('employer-apply-for-candidate-resumes');

	//Employer job filter module routes
	$EmJobFilCont = 'App\Http\Controllers\Employer\JobFiltersController';
	Route::get('/job-filters', $EmJobFilCont.'@listView')->name('employer-job-fil');
	Route::post('/job-filters/data', $EmJobFilCont.'@data')->name('employer-job-fil-data');
	Route::get('/job-filters/create-or-edit/{filter_id?}', $EmJobFilCont.'@createOrEdit')->name('employer-job-fil-create-or-edit');
	Route::post('/job-filters/save', $EmJobFilCont.'@save')->name('employer-job-fil-');
	Route::get('/job-filters/update-values/{filter_id}', $EmJobFilCont.'@updateValuesForm')->name('employer-job-fil-values-form');
	Route::post('/job-filters/update-values', $EmJobFilCont.'@updateValues')->name('employer-job-fil-update-values');
	Route::get('/job-filters/new-value', $EmJobFilCont.'@newValue')->name('employer-job-fil-new-value');
	Route::get('/job-filters/status/{filter_id}/{status}', $EmJobFilCont.'@changeStatus')->name('employer-job-fil-change-status');
	Route::get('/job-filters/delete/{filter_id}', $EmJobFilCont.'@delete')->name('employer-job-fil-delete');

	//Employer Traites module routes
	$EmTraitesFilCont = 'App\Http\Controllers\Employer\TraitesController';
	Route::get('/traites', $EmTraitesFilCont.'@listView')->name('employer-traites');
	Route::post('/traites/data', $EmTraitesFilCont.'@data')->name('employer-traites-data');
	Route::get('/traites/create-or-edit/{traite_id?}', $EmTraitesFilCont.'@createOrEdit')->name('employer-traites-create-or-edit');
	Route::post('/traites/save', $EmTraitesFilCont.'@save')->name('employer-traites-save');
	Route::get('/traites/status/{traite_id}/{status}', $EmTraitesFilCont.'@changeStatus')->name('employer-traites-change-status');
	Route::get('/traites/delete/{traite_id}', $EmTraitesFilCont.'@delete')->name('employer-traites-delete');

	//Employer Departments module routes
	$EmDepaFilCont = 'App\Http\Controllers\Employer\DepartmentsController';
	Route::get('/departments', $EmDepaFilCont.'@listView')->name('employer-depa');
	Route::post('/departments/data', $EmDepaFilCont.'@data')->name('employer-depa-data');
	Route::get('/departments/create-or-edit/{department_id?}', $EmDepaFilCont.'@createOrEdit')->name('employer-depa-create-or-edit');
	Route::post('/departments/save', $EmDepaFilCont.'@save')->name('employer-depa-save');
	Route::get('/departments/status/{department_id}/{status}', $EmDepaFilCont.'@changeStatus')->name('employer-depa-status');
	Route::post('/departments/bulk-action', $EmDepaFilCont.'@bulkAction')->name('employer-depa-bulk-action');
	Route::get('/departments/delete/{department_id}', $EmDepaFilCont.'@delete')->name('employer-depa-delete');

	//Employer Question Categories module routes
	$EmQueCatCont = 'App\Http\Controllers\Employer\QuestionCategoriesController';
	Route::get('/question-categories', $EmQueCatCont.'@listView')->name('employer-que-cat');
	Route::post('/question-categories/data', $EmQueCatCont.'@data')->name('employer-que-cat-data');
	Route::get('/question-categories/create-or-edit/{category_id?}', $EmQueCatCont.'@createOrEdit')->name('employer-que-cat-create-or-edit');
	Route::post('/question-categories/save', $EmQueCatCont.'@save')->name('employer-que-cat-save');
	Route::get('/question-categories/status/{category_id}/{status}', $EmQueCatCont.'@changeStatus')->name('employer-que-cat-status');
	Route::post('/question-categories/bulk-action', $EmQueCatCont.'@bulkAction')->name('employer-que-cat-bulk-action');
	Route::get('/question-categories/delete/{category_id}', $EmQueCatCont.'@delete')->name('employer-que-cat-delete');

	//Employer Questions Bank routes
	$EmQuesCont = 'App\Http\Controllers\Employer\QuestionsController';
	Route::post('/questions/save', $EmQuesCont.'@save')->name('employer-questions-save');
	Route::get('/questions/delete/{question_id}', $EmQuesCont.'@delete')->name('employer-questions-delete');
	Route::get('/questions/add-answer/{nature}/{question_id?}', $EmQuesCont.'@addAnswer')->name('employer-questions-add-answer');
	Route::get('/questions/remove-answer/{question_id}', $EmQuesCont.'@removeAnswer')->name('employer-questions-remove-answer');
	Route::post('/questions/remove-image/{question_id}', $EmQuesCont.'@removeImage')->name('employer-questions-remove-image');
	Route::get('/questions/create-or-edit/{nature}/{question_id?}', $EmQuesCont.'@createOrEdit')->name('employer-questions-create-or-edit');
	Route::post('/questions/{nature}', $EmQuesCont.'@index')->name('employer-questions');

	//Employer Quiz Categories module routes
	$EmQuiCatCont = 'App\Http\Controllers\Employer\QuizCategoriesController';
	Route::get('/quiz-categories', $EmQuiCatCont.'@listView')->name('employer-qui-cat');
	Route::post('/quiz-categories/data', $EmQuiCatCont.'@data')->name('employer-qui-cat-data');
	Route::get('/quiz-categories/create-or-edit/{category_id?}', $EmQuiCatCont.'@createOrEdit')->name('employer-qui-cat-create-or-edit');
	Route::post('/quiz-categories/save', $EmQuiCatCont.'@save')->name('employer-qui-cat-save');
	Route::get('/quiz-categories/status/{category_id}/{status}', $EmQuiCatCont.'@changeStatus')->name('employer-qui-cat-status');
	Route::post('/quiz-categories/bulk-action', $EmQuiCatCont.'@bulkAction')->name('employer-qui-cat-bulk-action');
	Route::get('/quiz-categories/delete/{category_id}', $EmQuiCatCont.'@delete')->name('employer-qui-cat-category-id');

	//Employer Quiz routes
	$EmQuizCont = 'App\Http\Controllers\Employer\QuizesController';
	Route::get('/quizes/create-or-edit/{quiz_id?}', $EmQuizCont.'@createOrEdit')->name('employer-quiz-create-or-edit');
	Route::post('/quizes/save', $EmQuizCont.'@save')->name('employer-quiz-save');
	Route::post('/quizes/clone', $EmQuizCont.'@cloneQuiz')->name('employer-quiz-clone');
	Route::get('/quizes/clone/{quiz_id}', $EmQuizCont.'@cloneForm')->name('employer-quiz-clone-form');
	Route::get('/quizes/delete/{quiz_id}', $EmQuizCont.'@delete')->name('employer-quiz-delete');
	Route::get('/quizes/dropdown/{quiz_id?}', $EmQuizCont.'@dropdown')->name('employer-quiz-dropdown');
	Route::get('/quizes/download/{quiz_id}', $EmQuizCont.'@download')->name('employer-quiz-download');

	//Employer Quiz Questions routes
	$EmQuiQueCont = 'App\Http\Controllers\Employer\QuizQuestionsController';
	Route::get('/quiz-questions/add/{question_id}/{quiz_id?}', $EmQuiQueCont.'@add')->name('employer-qui-que-add');
	Route::get('/quiz-questions/edit/{question_id}', $EmQuiQueCont.'@edit')->name('employer-qui-que-edit');
	Route::get('/quiz-questions/delete/{question_id}', $EmQuiQueCont.'@delete')->name('employer-qui-que-delete');
	Route::post('/quiz-questions/order', $EmQuiQueCont.'@order')->name('employer-qui-que-order');
	Route::get('/quiz-questions/add-answer/{question_id}/{quiz_id?}', $EmQuiQueCont.'@addAnswer')->name('employer-qui-que-add-answer');
	Route::get('/quiz-questions/remove-answer/{question_id}', $EmQuiQueCont.'@removeAnswer')->name('employer-qui-que-remove-answer');
	Route::post('/quiz-questions/save', $EmQuiQueCont.'@save')->name('employer-qui-que-save');
	Route::get('/quiz-questions/remove-image/{question_id}', $EmQuiQueCont.'@removeImage')->name('employer-qui-que-remove-image');
	Route::get('/quiz-questions/{quiz_id}', $EmQuiQueCont.'@index')->name('employer-qui-que');

	//Employer Interview Categories module routes
	$EmIntCatCont = 'App\Http\Controllers\Employer\InterviewCategoriesController';
	Route::get('/interview-categories', $EmIntCatCont.'@listView')->name('employer-int-cat');
	Route::post('/interview-categories/data', $EmIntCatCont.'@data')->name('employer-int-cat-data');
	Route::get('/interview-categories/create-or-edit/{category_id?}', $EmIntCatCont.'@createOrEdit')->name('employer-int-cat-create-or-edit');
	Route::post('/interview-categories/save', $EmIntCatCont.'@save')->name('employer-int-cat-save');
	Route::get('/interview-categories/status/{category_id}/{status}', $EmIntCatCont.'@changeStatus')->name('employer-int-cat-status');
	Route::post('/interview-categories/bulk-action', $EmIntCatCont.'@bulkAction')->name('employer-int-cat-bulk-action');
	Route::get('/interview-categories/delete/{category_id}', $EmIntCatCont.'@delete')->name('employer-int-cat-delete');

	//Employer Interview routes
	$EmInterCont = 'App\Http\Controllers\Employer\InterviewsController';
	Route::get('/interviews/create-or-edit/{interview_id?}', $EmInterCont.'@createOrEdit')->name('employer-interviews-create-or-edit');
	Route::post('/interviews/save', $EmInterCont.'@save')->name('employer-interviews-save');
	Route::post('/interviews/clone', $EmInterCont.'@cloneInterview')->name('employer-interviews-clone');
	Route::get('/interviews/clone/{interview_id}', $EmInterCont.'@cloneForm')->name('employer-interviews-clone-form');
	Route::get('/interviews/delete/{interview_id}', $EmInterCont.'@delete')->name('employer-interviews-delete');
	Route::get('/interviews/dropdown/{interview_id?}', $EmInterCont.'@dropdown')->name('employer-interviews-dropdown');
	Route::get('/interviews/download/{interview_id}', $EmInterCont.'@download')->name('employer-interviews-download');

	//Employer Interview Questions routes
	$EmIntQueCont = 'App\Http\Controllers\Employer\InterviewQuestionsController';
	Route::get('/interview-questions/add/{question_id}/{interview_id?}', $EmIntQueCont.'@add')->name('employer-int-que-add');
	Route::get('/interview-questions/edit/{question_id}', $EmIntQueCont.'@edit')->name('employer-int-que-edit');
	Route::get('/interview-questions/delete/{question_id}', $EmIntQueCont.'@delete')->name('employer-int-que-delete');
	Route::post('/interview-questions/order', $EmIntQueCont.'@order')->name('employer-int-que-order');
	Route::get('/interview-questions/add-answer/{question_id}/{interview_id}', $EmIntQueCont.'@addAnswer')->name('employer-int-que-add-answer');
	Route::get('/interview-questions/remove-answer/{question_id}', $EmIntQueCont.'@removeAnswer')->name('employer-int-que-remove-answer');
	Route::post('/interview-questions/save', $EmIntQueCont.'@save')->name('employer-int-que-save');
	Route::get('/interview-questions/{question_id}', $EmIntQueCont.'@index')->name('employer-int-que');

	//Employer Quiz Designer Page route
	$EmQuiDesCont = 'App\Http\Controllers\Employer\QuizDesignerController';
	Route::get('/quiz-designer', $EmQuiDesCont.'@index')->name('employer-quiz-designer');

	//Employer Interview Designer Page route
	$EmIntDesCont = 'App\Http\Controllers\Employer\InterviewDesignerController';
	Route::get('/interview-designer', $EmIntDesCont.'@index')->name('employer-interview-designer');

	//Employer Job Board routes
	$EmJobBoaCont = 'App\Http\Controllers\Employer\JobBoardController';
	Route::get('/job-board', $EmJobBoaCont.'@index')->name('employer-job-boa');
	Route::post('/job-board/jobs-list', $EmJobBoaCont.'@jobsList')->name('employer-job-boa-jobs-list');
	Route::post('/job-board/candidates-list/{job_id?}', $EmJobBoaCont.'@candidatesList')->name('employer-job-boa-candidates-list');
	Route::get('/job-board/assign-view/{type}/{job_id}', $EmJobBoaCont.'@assignView')->name('employer-job-boa-assign-view');
	Route::post('/job-board/assign', $EmJobBoaCont.'@assign')->name('employer-job-boa-assign');
	Route::get('/job-board/delete-interview/{job_id}', $EmJobBoaCont.'@deleteInterview')->name('employer-job-boa-delete-interview');
	Route::get('/job-board/delete-quiz/{job_id}', $EmJobBoaCont.'@deleteQuiz')->name('employer-job-boa-delete-quiz');
	Route::post('/job-board/candidate-status', $EmJobBoaCont.'@candidateStatus')->name('employer-job-boa-candidate-status');
	Route::post('/job-board/delete-app', $EmJobBoaCont.'@deleteApplication')->name('employer-job-boa-delete-app');
	Route::get('/job-board/job/{job_id}', $EmJobBoaCont.'@viewJob')->name('employer-job-boa-job');
	Route::get('/job-board/resume/{job_id}', $EmJobBoaCont.'@viewResume')->name('employer-job-boa-resume');
	Route::post('/job-board/overall-result', $EmJobBoaCont.'@overallResult')->name('employer-job-boa-overall-result');
	Route::post('/job-board/pdf-result', $EmJobBoaCont.'@pdfResult')->name('employer-job-boa-pdf-result');
	Route::get('/job-board/{job_id}', $EmJobBoaCont.'@index')->name('employer-job-boa-para');

	//Employer Dashboard Routes
	$EmDashboardCont = 'App\Http\Controllers\Employer\DashboardController';
	Route::get('/dashboard', $EmDashboardCont.'@index')->name('employer-dashboard');
	Route::post('/dashboard/popular-jobs-data', $EmDashboardCont.'@popularJobsChartData')->name('employer-dashboard-pop-data');
	Route::post('/dashboard/top-candidates-data', $EmDashboardCont.'@topCandidatesChartData')->name('employer-dashboard-top-data');
	Route::post('/dashboard/jobs-list', $EmDashboardCont.'@jobsList')->name('employer-dashboard-jobs-list');
	Route::post('/dashboard/combined', $EmDashboardCont.'@combined')->name('employer-dashboard-list-combined');

	//Employer Dashboard todos Routes
	$EmTodosCont = 'App\Http\Controllers\Employer\TodosController';
	Route::post('/todos/list', $EmTodosCont.'@listView')->name('employer-todos-list');
	Route::get('/todos/create-or-edit/{todo_id?}', $EmTodosCont.'@createOrEditToDo')->name('employer-todos-create-or-edit');
	Route::post('/todos/save', $EmTodosCont.'@save')->name('employer-todos-save');
	Route::get('/todos/delete/{todo_id}', $EmTodosCont.'@delete')->name('employer-todos-delete');
	Route::get('/todo/{todo_id}/{status?}', $EmTodosCont.'@todoStatus')->name('employer-todos');

	//Employer Blog Categories module routes
	$EmBloCatCont = 'App\Http\Controllers\Employer\BlogCategoriesController';
	Route::get('/blog-categories', $EmBloCatCont.'@listView')->name('employer-blo-cat');
	Route::post('/blog-categories/data', $EmBloCatCont.'@data')->name('employer-blo-cat-data');
	Route::get('/blog-categories/create-or-edit/{category_id?}', $EmBloCatCont.'@createOrEdit')->name('employer-blo-cat-create-or-edit');
	Route::post('/blog-categories/save', $EmBloCatCont.'@save')->name('employer-blo-cat-save');
	Route::get('/blog-categories/status/{category_id}/{status}', $EmBloCatCont.'@changeStatus')->name('employer-blo-cat-status');
	Route::post('/blog-categories/bulk-action', $EmBloCatCont.'@bulkAction')->name('employer-blo-cat-bulk-action');
	Route::get('/blog-categories/delete/{category_id}', $EmBloCatCont.'@delete')->name('employer-blo-cat-delete');

	//Employer Blog module routes
	$EmBlogsCont = 'App\Http\Controllers\Employer\BlogsController';
	Route::get('/blogs', $EmBlogsCont.'@listView')->name('employer-blogs');
	Route::post('/blogs/data', $EmBlogsCont.'@data')->name('employer-blogs-data');
	Route::get('/blogs/create-or-edit/{blog_id?}', $EmBlogsCont.'@createOrEdit')->name('employer-blogs-create-or-edit');
	Route::post('/blogs/save', $EmBlogsCont.'@save')->name('employer-blogs-save');
	Route::get('/blogs/status/{blog_id}/{status}', $EmBlogsCont.'@changeStatus')->name('employer-blogs-status');
	Route::post('/blogs/bulk-action', $EmBlogsCont.'@bulkAction')->name('employer-blogs-bulk-action');
	Route::get('/blogs/delete/{blog_id}', $EmBlogsCont.'@delete')->name('employer-blogs-delete');

	//Employer General routes
	$EmPagesCont = 'App\Http\Controllers\Employer\PagesController';
	Route::get('/sidebar-toggle', $EmPagesCont.'@sidebarToggle')->name('employer-sidebar-toggle');
});


/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*			2a : Admin outer/public routes 		*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
Route::group(['prefix' => 'admin', 'middleware' => [$mw['essset']]], function () {
	$AdUsersCont = 'App\Http\Controllers\Admin\UsersController';
	Route::get('', $AdUsersCont.'@loginView')->name('admin');
	Route::get('/login', $AdUsersCont.'@loginView')->name('admin-login');
	Route::post('/login-post', $AdUsersCont.'@login')->name('admin-login-post');
	Route::get('/forgot-password', $AdUsersCont.'@forgotPasswordView')->name('admin-forgot-pass');
	Route::post('/forgot-password-post', $AdUsersCont.'@forgotPassword')->name('admin-forgot-pass-post');
	Route::get('/reset-password/{token}', $AdUsersCont.'@resetPasswordView')->name('admin-reset-pass');
	Route::post('/reset-password-post', $AdUsersCont.'@resetPassword')->name('admin-reset-pass-post');
});

/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*		  2b : Admin inner/auth routes 			*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
Route::group(['prefix'=>'admin', 'middleware'=>[$mw['essset'], $mw['admin_auth'], $mw['xss_sanitizer']]], function() {

	$AdDashCont = 'App\Http\Controllers\Admin\DashboardController@';
	Route::get('/dashboard', $AdDashCont.'dashboard')->name('admin-dashboard');
	Route::post('/dashboard/sales-data', $AdDashCont.'salesChartData')->name('employer-dashboard-sales-data');
	Route::post('/dashboard/signups-data', $AdDashCont.'signupsChartData')->name('employer-dashboard-signups-data');

	//Admin User(inner) routes
	$AdUsersCont = 'App\Http\Controllers\Admin\UsersController';
	Route::get('/profile', $AdUsersCont.'@profileView')->name('admin-profile');
	Route::post('/profile-post', $AdUsersCont.'@updateProfile')->name('admin-profile-post');
	Route::get('/password', $AdUsersCont.'@passwordView')->name('admin-password');
	Route::post('/password-post', $AdUsersCont.'@updatePassword')->name('admin-password-post');
	Route::get('/logout', $AdUsersCont.'@logout')->name('admin-logout');

	//Admin Users module routes
	Route::get('/users', $AdUsersCont.'@usersListView')->name('admin-users');
	Route::post('/users/data', $AdUsersCont.'@usersList')->name('admin-users-data');
	Route::get('/users/create-or-edit', $AdUsersCont.'@createOrEdit')->name('admin-users-create');
	Route::get('/users/create-or-edit/{user_id?}', $AdUsersCont.'@createOrEdit')->name('admin-users-edit');
	Route::post('/users/save', $AdUsersCont.'@saveUser')->name('admin-users-save');
	Route::post('/users/save-roles', $AdUsersCont.'@saveUserRoles')->name('admin-users-save-roles');
	Route::get('/users/status/{user_id}/{status}', $AdUsersCont.'@changeStatus')->name('admin-users-status');
	Route::post('/users/bulk-action', $AdUsersCont.'@bulkAction')->name('admin-users-ba');
	Route::get('/users/delete/{user_id}', $AdUsersCont.'@delete')->name('admin-users-delete');
	Route::post('/users/excel', $AdUsersCont.'@usersExcel')->name('admin-users-excel');
	Route::get('/users/message-view', $AdUsersCont.'@messageView')->name('admin-users-message-view');
	Route::post('/users/message', $AdUsersCont.'@message')->name('admin-users-message');

	//Admin Roles module routes
	$AdRolesCont = 'App\Http\Controllers\Admin\RolesController';
	Route::get('/roles', $AdRolesCont.'@listView')->name('admin-roles-list');
	Route::get('/roles/create-or-edit', $AdRolesCont.'@createOrEdit')->name('admin-roles-create');
	Route::get('/roles/create-or-edit/{user_id?}', $AdRolesCont.'@createOrEdit')->name('admin-roles-edit');
	Route::get('/roles/role-permissions/{role_id}', $AdRolesCont.'@getRolePermissions')->name('admin-roles-permission');
	Route::post('/roles/save', $AdRolesCont.'@saveRole')->name('admin-role-save');
	Route::post('/roles/update-permissions', $AdRolesCont.'@updatePermissions')->name('admin-permissions-update');
	Route::get('/roles/delete/{role_id}', $AdRolesCont.'@delete')->name('admin-role-delete');
	Route::get('/roles/rolesAsSelect2/{type?}', $AdRolesCont.'@rolesAsSelect2')->name('admin-role-bulk-assign-view');

	//Admin Roles module routes
	$AdMenusCont = 'App\Http\Controllers\Admin\MenusController';
	Route::get('/menus', $AdMenusCont.'@listView')->name('admin-menus-list');
	Route::get('/menus/list/{alignment?}', $AdMenusCont.'@listData')->name('admin-menus-list-data');
	Route::get('/menus/list-for-delete/{alignment?}', $AdMenusCont.'@listDataForDeleteMenu')->name('admin-menus-list-data-del');
	Route::get('/menus/sub-menu/{menu_item_id}', $AdMenusCont.'@getSubMenu')->name('admin-sub-menu');
	Route::post('/menus/save', $AdMenusCont.'@save')->name('admin-menu-save');
	Route::post('/menus/order-update', $AdMenusCont.'@orderUpdate')->name('admin-menu-order-update');
	Route::get('/menus/delete/{menu_item_id}', $AdMenusCont.'@delete')->name('admin-menu-delete');

	//Admin Employers module routes
	$AdEmpCont = 'App\Http\Controllers\Admin\EmployersController';
	Route::get('/employers', $AdEmpCont.'@employersListView')->name('admin-employers');
	Route::post('/employers/data', $AdEmpCont.'@employersList')->name('admin-employers-data');
	Route::get('/employers/create-or-edit', $AdEmpCont.'@createOrEdit')->name('admin-employers-create');
	Route::get('/employers/create-or-edit/{employer_id?}', $AdEmpCont.'@createOrEdit')->name('admin-employers-edit');
	Route::post('/employers/save', $AdEmpCont.'@saveEmployer')->name('admin-employers-save');
	Route::post('/employers/save-roles', $AdEmpCont.'@saveEmployerRoles')->name('admin-employers-save-roles');
	Route::get('/employers/status/{employer_id}/{status}', $AdEmpCont.'@changeStatus')->name('admin-employers-status');
	Route::post('/employers/bulk-action', $AdEmpCont.'@bulkAction')->name('admin-employers-ba');
	Route::get('/employers/delete/{employer_id}', $AdEmpCont.'@delete')->name('admin-employers-delete');
	Route::post('/employers/excel', $AdEmpCont.'@employersExcel')->name('admin-employers-excel');
	Route::get('/employers/message-view', $AdEmpCont.'@messageView')->name('admin-employers-message-view');
	Route::post('/employers/message', $AdEmpCont.'@message')->name('admin-employers-message');
	Route::get('/employers/login-as/{employer_id}/{user_id}', $AdEmpCont.'@loginAs')->name('admin-employers-loginas');

	//Admin Candidates module routes
	$AdCanCont = 'App\Http\Controllers\Admin\CandidatesController';
	Route::get('/candidates', $AdCanCont.'@candidatesListView')->name('admin-candidates');
	Route::post('/candidates/data', $AdCanCont.'@candidatesList')->name('admin-candidates-data');
	Route::get('/candidates/create-or-edit', $AdCanCont.'@createOrEdit')->name('admin-candidates-create');
	Route::get('/candidates/create-or-edit/{candidate_id?}', $AdCanCont.'@createOrEdit')->name('admin-candidates-edit');
	Route::post('/candidates/save', $AdCanCont.'@save')->name('admin-candidates-save');
	Route::post('/candidates/extract-resume-summary', $AdCanCont.'@extractResumeSummary')->name('admin-candidates-extract-resume-summary');
	Route::get('/candidates/status/{candidate_id}/{status}', $AdCanCont.'@changeStatus')->name('admin-candidates-status');
	Route::post('/candidates/bulk-action', $AdCanCont.'@bulkAction')->name('admin-candidates-ba');
	Route::get('/candidates/delete/{candidate_id}', $AdCanCont.'@delete')->name('admin-candidates-delete');
	Route::post('/candidates/resume-download', $AdCanCont.'@resumeDownload')->name('admin-candidates-resume-pdf');
	Route::post('/candidates/excel', $AdCanCont.'@candidatesExcel')->name('admin-candidates-excel');
	Route::get('/candidates/message-view', $AdCanCont.'@messageView')->name('admin-candidates-message-view');
	Route::post('/candidates/message', $AdCanCont.'@message')->name('admin-candidates-message');
	Route::get('/candidates/upload-view', $AdCanCont.'@uploadView')->name('admin-candidates-upload-view');
	Route::post('/candidates/upload', $AdCanCont.'@upload')->name('admin-candidates-upload');

	//Admin Packages module routes
	$AdPackCont = 'App\Http\Controllers\Admin\PackagesController';
	Route::get('/packages', $AdPackCont.'@packagesListView')->name('admin-packages');
	Route::post('/packages/data', $AdPackCont.'@packagesList')->name('admin-packages-data');
	Route::get('/packages/create-or-edit', $AdPackCont.'@createOrEdit')->name('admin-packages-create');
	Route::get('/packages/create-or-edit/{package_id?}', $AdPackCont.'@createOrEdit')->name('admin-packages-edit');
	Route::post('/packages/save', $AdPackCont.'@savePackage')->name('admin-packages-save');
	Route::get('/packages/status/{package_id}/{status}', $AdPackCont.'@changeStatus')->name('admin-packages-status');
	Route::get('/packages/status-free/{package_id}/{status}', $AdPackCont.'@changeStatusFree')->name('admin-packages-status-free');
	Route::get('/packages/status-top/{package_id}/{status}', $AdPackCont.'@changeStatusTop')->name('admin-packages-status-top');
	Route::post('/packages/bulk-action', $AdPackCont.'@bulkAction')->name('admin-packages-ba');
	Route::get('/packages/delete/{package_id}', $AdPackCont.'@delete')->name('admin-packages-delete');
	Route::post('/packages/excel', $AdPackCont.'@packagesExcel')->name('admin-packages-excel');

	//Admin Packages module routes
	$AdMemCont = 'App\Http\Controllers\Admin\MembershipsController';
	Route::get('/memberships', $AdMemCont.'@membershipsListView')->name('admin-memberships');
	Route::post('/memberships/data', $AdMemCont.'@membershipsList')->name('admin-memberships-data');
	Route::get('/memberships/create-or-edit', $AdMemCont.'@createOrEdit')->name('admin-memberships-create');
	Route::get('/memberships/create-or-edit/{membership_id?}', $AdMemCont.'@createOrEdit')->name('admin-memberships-edit');
	Route::post('/memberships/save', $AdMemCont.'@saveMembership')->name('admin-memberships-save');
	Route::get('/memberships/status/{membership_id}/{status}', $AdMemCont.'@changeStatus')->name('admin-memberships-status');
	Route::post('/memberships/bulk-action', $AdMemCont.'@bulkAction')->name('admin-memberships-ba');
	Route::get('/memberships/delete/{membership_id}', $AdMemCont.'@delete')->name('admin-memberships-delete');
	Route::post('/memberships/excel', $AdMemCont.'@membershipsExcel')->name('admin-memberships-excel');

	//Admin Candidate Packages module routes
	$AdCandPackCont = 'App\Http\Controllers\Admin\CandidatePackagesController';
	Route::get('/candidate-packages', $AdCandPackCont.'@candidatePackagesListView')->name('admin-candidate-packages');
	Route::post('/candidate-packages/data', $AdCandPackCont.'@candidatePackagesList')->name('admin-candidate-packages-data');
	Route::get('/candidate-packages/create-or-edit', $AdCandPackCont.'@createOrEdit')->name('admin-candidate-packages-create');
	Route::get('/candidate-packages/create-or-edit/{package_id?}', $AdCandPackCont.'@createOrEdit')->name('admin-candidate-packages-edit');
	Route::post('/candidate-packages/save', $AdCandPackCont.'@saveCandidatePackage')->name('admin-candidate-packages-save');
	Route::get('/candidate-packages/status/{package_id}/{status}', $AdCandPackCont.'@changeStatus')->name('admin-candidate-packages-status');
	Route::get('/candidate-packages/status-free/{package_id}/{status}', $AdCandPackCont.'@changeStatusFree')->name('admin-candidate-packages-status-free');
	Route::get('/candidate-packages/status-top/{package_id}/{status}', $AdCandPackCont.'@changeStatusTop')->name('admin-candidate-packages-status-top');
	Route::post('/candidate-packages/bulk-action', $AdCandPackCont.'@bulkAction')->name('admin-candidate-packages-ba');
	Route::get('/candidate-packages/delete/{package_id}', $AdCandPackCont.'@delete')->name('admin-candidate-packages-delete');
	Route::post('/candidate-packages/excel', $AdCandPackCont.'@candidatePackagesExcel')->name('admin-candidate-packages-excel');

	//Admin Packages module routes
	$AdCandMemCont = 'App\Http\Controllers\Admin\CandidateMembershipsController';
	Route::get('/candidate-memberships', $AdCandMemCont.'@itemsListView')->name('admin-candidate-memberships');
	Route::post('/candidate-memberships/data', $AdCandMemCont.'@itemsList')->name('admin-candidate-memberships-data');
	Route::get('/candidate-memberships/create-or-edit', $AdCandMemCont.'@createOrEdit')->name('admin-candidate-memberships-create');
	Route::get('/candidate-memberships/create-or-edit/{membership_id?}', $AdCandMemCont.'@createOrEdit')->name('admin-candidate-memberships-edit');
	Route::post('/candidate-memberships/save', $AdCandMemCont.'@saveItem')->name('admin-candidate-memberships-save');
	Route::get('/candidate-memberships/status/{membership_id}/{status}', $AdCandMemCont.'@changeStatus')->name('admin-candidate-memberships-status');
	Route::post('/candidate-memberships/bulk-action', $AdCandMemCont.'@bulkAction')->name('admin-candidate-memberships-ba');
	Route::get('/candidate-memberships/delete/{membership_id}', $AdCandMemCont.'@delete')->name('admin-candidate-memberships-delete');
	Route::post('/candidate-memberships/excel', $AdCandMemCont.'@itemsExcel')->name('admin-candidate-memberships-excel');

	//Admin Industries module routes
	$AdIndCont = 'App\Http\Controllers\Admin\IndustriesController';
	Route::get('/industries', $AdIndCont.'@listView')->name('admin-industries');
	Route::post('/industries/data', $AdIndCont.'@list')->name('admin-industries-data');
	Route::get('/industries/create-or-edit', $AdIndCont.'@createOrEdit')->name('admin-industries-create');
	Route::get('/industries/create-or-edit/{category_id?}', $AdIndCont.'@createOrEdit')->name('admin-industries-edit');
	Route::post('/industries/save', $AdIndCont.'@save')->name('admin-industries-save');
	Route::get('/industries/status/{category_id}/{status}', $AdIndCont.'@changeStatus')->name('admin-industries-status');
	Route::get('/industries/delete/{category_id}', $AdIndCont.'@delete')->name('admin-industries-delete');

	//Admin News Categories module routes
	$AdNewsCont = 'App\Http\Controllers\Admin\NewsCategoriesController';
	Route::get('/news-categories', $AdNewsCont.'@listView')->name('admin-news-categories');
	Route::post('/news-categories/data', $AdNewsCont.'@list')->name('admin-news-categories-data');
	Route::get('/news-categories/create-or-edit', $AdNewsCont.'@createOrEdit')->name('admin-news-categories-create');
	Route::get('/news-categories/create-or-edit/{category_id?}', $AdNewsCont.'@createOrEdit')->name('admin-news-categories-edit');
	Route::post('/news-categories/save', $AdNewsCont.'@save')->name('admin-news-categories-save');
	Route::get('/news-categories/status/{category_id}/{status}', $AdNewsCont.'@changeStatus')->name('admin-news-categories-status');
	Route::get('/news-categories/delete/{category_id}', $AdNewsCont.'@delete')->name('admin-news-categories-delete');

	//Admin News module routes
	$AdNewsCont = 'App\Http\Controllers\Admin\NewsController';
	Route::get('/news', $AdNewsCont.'@newsListView')->name('admin-news');
	Route::post('/news/data', $AdNewsCont.'@newsList')->name('admin-news-data');
	Route::get('/news/create-or-edit', $AdNewsCont.'@createOrEdit')->name('admin-news-create');
	Route::get('/news/create-or-edit/{news_id?}', $AdNewsCont.'@createOrEdit')->name('admin-news-edit');
	Route::post('/news/save', $AdNewsCont.'@saveNews')->name('admin-news-save');
	Route::get('/news/status/{news_id}/{status}', $AdNewsCont.'@changeStatus')->name('admin-news-status');
	Route::post('/news/bulk-action', $AdNewsCont.'@bulkAction')->name('admin-news-ba');
	Route::get('/news/delete/{news_id}', $AdNewsCont.'@delete')->name('admin-news-delete');
	Route::post('/news/excel', $AdNewsCont.'@newsExcel')->name('admin-news-excel');

	//Admin Faqs Categories module routes
	$AdFaqsCont = 'App\Http\Controllers\Admin\FaqsCategoriesController';
	Route::get('/faqs-categories', $AdFaqsCont.'@listView')->name('admin-faqs-categories');
	Route::post('/faqs-categories/data', $AdFaqsCont.'@list')->name('admin-faqs-categories-data');
	Route::get('/faqs-categories/create-or-edit', $AdFaqsCont.'@createOrEdit')->name('admin-faqs-categories-create');
	Route::get('/faqs-categories/create-or-edit/{category_id?}', $AdFaqsCont.'@createOrEdit')->name('admin-faqs-categories-edit');
	Route::post('/faqs-categories/save', $AdFaqsCont.'@save')->name('admin-faqs-categories-save');
	Route::get('/faqs-categories/status/{category_id}/{status}', $AdFaqsCont.'@changeStatus')->name('admin-faqs-categories-status');
	Route::get('/faqs-categories/delete/{category_id}', $AdFaqsCont.'@delete')->name('admin-faqs-categories-delete');

	//Admin Faqs module routes
	$AdFaqsCont = 'App\Http\Controllers\Admin\FaqsController';
	Route::get('/faqs', $AdFaqsCont.'@faqsListView')->name('admin-faqs');
	Route::post('/faqs/data', $AdFaqsCont.'@faqsList')->name('admin-faqs-data');
	Route::get('/faqs/create-or-edit', $AdFaqsCont.'@createOrEdit')->name('admin-faqs-create');
	Route::get('/faqs/create-or-edit/{faqs_id?}', $AdFaqsCont.'@createOrEdit')->name('admin-faqs-edit');
	Route::post('/faqs/save', $AdFaqsCont.'@saveFaqs')->name('admin-faqs-save');
	Route::get('/faqs/status/{faqs_id}/{status}', $AdFaqsCont.'@changeStatus')->name('admin-faqs-status');
	Route::post('/faqs/bulk-action', $AdFaqsCont.'@bulkAction')->name('admin-faqs-ba');
	Route::get('/faqs/delete/{faqs_id}', $AdFaqsCont.'@delete')->name('admin-faqs-delete');
	Route::post('/faqs/excel', $AdFaqsCont.'@faqsExcel')->name('admin-faqs-excel');

	//Admin Testimonials module routes
	$AdTestCont = 'App\Http\Controllers\Admin\TestimonialsController';
	Route::get('/testimonials', $AdTestCont.'@testimonialsListView')->name('admin-testimonials');
	Route::post('/testimonials/data', $AdTestCont.'@testimonialsList')->name('admin-testimonials-data');
	Route::get('/testimonials/create-or-edit', $AdTestCont.'@createOrEdit')->name('admin-testimonials-create');
	Route::get('/testimonials/create-or-edit/{testimonial_id?}', $AdTestCont.'@createOrEdit')->name('admin-testimonials-edit');
	Route::post('/testimonials/save', $AdTestCont.'@saveTestimonial')->name('admin-testimonials-save');
	Route::get('/testimonials/status/{package_id}/{status}', $AdTestCont.'@changeStatus')->name('admin-testimonials-status');
	Route::post('/testimonials/bulk-action', $AdTestCont.'@bulkAction')->name('admin-testimonials-ba');
	Route::get('/testimonials/delete/{package_id}', $AdTestCont.'@delete')->name('admin-testimonials-delete');
	Route::post('/testimonials/excel', $AdTestCont.'@testimonialsExcel')->name('admin-testimonials-excel');

	//Admin Pages module routes
	$AdPageCont = 'App\Http\Controllers\Admin\PagesController';
	Route::get('/pages', $AdPageCont.'@pagesListView')->name('admin-pages');
	Route::post('/pages/data', $AdPageCont.'@pagesList')->name('admin-pages-data');
	Route::get('/pages/create-or-edit', $AdPageCont.'@createOrEdit')->name('admin-pages-create');
	Route::get('/pages/create-or-edit/{page_id?}', $AdPageCont.'@createOrEdit')->name('admin-pages-edit');
	Route::post('/pages/save', $AdPageCont.'@savePage')->name('admin-pages-save');
	Route::get('/pages/status/{package_id}/{status}', $AdPageCont.'@changeStatus')->name('admin-pages-status');
	Route::post('/pages/bulk-action', $AdPageCont.'@bulkAction')->name('admin-pages-ba');
	Route::get('/pages/delete/{package_id}', $AdPageCont.'@delete')->name('admin-pages-delete');
	Route::post('/pages/excel', $AdPageCont.'@pagesExcel')->name('admin-pages-excel');

	//Admin Messages module routes
	$AdMesCont = 'App\Http\Controllers\Admin\MessagesController';
	Route::get('/messages', $AdMesCont.'@messagesListView')->name('admin-messages');
	Route::post('/messages/data', $AdMesCont.'@messagesList')->name('admin-messages-data');
	Route::get('/messages/delete/{package_id}', $AdMesCont.'@delete')->name('admin-messages-delete');
	Route::get('/messages/message-view', $AdMesCont.'@messageView')->name('admin-messages-message-view');
	Route::post('/messages/message', $AdMesCont.'@message')->name('admin-messages-message');
	Route::post('/messages/excel', $AdMesCont.'@messagesExcel')->name('admin-messages-excel');

	//Admin Departments module routes
	$AdDepaFilCont = 'App\Http\Controllers\Admin\DepartmentsController';
	Route::get('/departments', $AdDepaFilCont.'@listView')->name('admin-depa');
	Route::post('/departments/data', $AdDepaFilCont.'@data')->name('admin-depa-data');
	Route::get('/departments/create-or-edit/{department_id?}', $AdDepaFilCont.'@createOrEdit')->name('admin-depa-create-or-edit');
	Route::post('/departments/save', $AdDepaFilCont.'@save')->name('admin-depa-save');
	Route::get('/departments/status/{department_id}/{status}', $AdDepaFilCont.'@changeStatus')->name('admin-depa-status');
	Route::post('/departments/bulk-action', $AdDepaFilCont.'@bulkAction')->name('admin-depa-bulk-action');
	Route::get('/departments/delete/{department_id}', $AdDepaFilCont.'@delete')->name('admin-depa-delete');

	//Admin Jobs module routes
	$AdJobCont = 'App\Http\Controllers\Admin\JobsController';
	Route::get('/jobs', $AdJobCont.'@listView')->name('admin-jobs');
	Route::post('/jobs/data', $AdJobCont.'@data')->name('admin-jobs-data');
	Route::get('/jobs/create-or-edit/{job_id?}', $AdJobCont.'@createOrEdit')->name('admin-jobs-create-or-edit');
	Route::post('/jobs/save', $AdJobCont.'@save')->name('admin-jobs-save');
	Route::get('/jobs/status/{job_id}/{status}', $AdJobCont.'@changeStatus')->name('admin-jobs-status');
	Route::get('/jobs/delete/{job_id}', $AdJobCont.'@delete')->name('admin-jobs-delete');
	Route::post('/jobs/bulk-action', $AdJobCont.'@bulkAction')->name('admin-jobs-ba');
	Route::post('/jobs/excel', $AdJobCont.'@excel')->name('admin-jobs-excel');

	//Employer job filter module routes
	$AdJobFilCont = 'App\Http\Controllers\Admin\JobFiltersController';
	Route::get('/job-filters', $AdJobFilCont.'@listView')->name('admin-job-fil');
	Route::post('/job-filters/data', $AdJobFilCont.'@data')->name('admin-job-fil-data');
	Route::get('/job-filters/create-or-edit/{filter_id?}', $AdJobFilCont.'@createOrEdit')->name('admin-job-fil-create-or-edit');
	Route::post('/job-filters/save', $AdJobFilCont.'@save')->name('admin-job-fil-');
	Route::get('/job-filters/update-values/{filter_id}', $AdJobFilCont.'@updateValuesForm')->name('admin-job-fil-values-form');
	Route::post('/job-filters/update-values', $AdJobFilCont.'@updateValues')->name('admin-job-fil-update-values');
	Route::get('/job-filters/new-value', $AdJobFilCont.'@newValue')->name('admin-job-fil-new-value');
	Route::get('/job-filters/status/{filter_id}/{status}', $AdJobFilCont.'@changeStatus')->name('admin-job-fil-change-status');
	Route::get('/job-filters/delete/{filter_id}', $AdJobFilCont.'@delete')->name('admin-job-fil-delete');

	//Admin Settings module routes
	$AdSetCont = 'App\Http\Controllers\Admin\SettingsController';
	Route::get('/settings/general', $AdSetCont.'@general')->name('admin-settings-general');
	Route::post('/settings/general', $AdSetCont.'@updateGeneral')->name('admin-settings-general-post');
	Route::get('/settings/display', $AdSetCont.'@display')->name('admin-settings-display');
	Route::post('/settings/display', $AdSetCont.'@updateDisplay')->name('admin-settings-display-post');
	Route::get('/settings/general-images', $AdSetCont.'@generalImages')->name('admin-settings-general-images');
	Route::post('/settings/general-images', $AdSetCont.'@updateGeneralImages')->name('admin-settings-general-images-post');
	Route::get('/settings/email', $AdSetCont.'@email')->name('admin-settings-email');
	Route::post('/settings/email', $AdSetCont.'@updateEmail')->name('admin-settings-email-post');
	Route::get('/settings/templates', $AdSetCont.'@emailTemplates')->name('admin-settings-templates');
	Route::post('/settings/save-templates', $AdSetCont.'@updateEmailTemplates')->name('admin-settings-save-templates');	
	Route::get('/settings/apis', $AdSetCont.'@apis')->name('admin-settings-apis');
	Route::post('/settings/apis', $AdSetCont.'@updateApis')->name('admin-settings-apis-post');
	Route::get('/settings/home', $AdSetCont.'@home')->name('admin-settings-home');
	Route::post('/settings/home', $AdSetCont.'@updateHomeSettings')->name('admin-settings-home-post');
	Route::get('/settings/employer', $AdSetCont.'@employerSettings')->name('admin-settings-employer');
	Route::post('/settings/employer', $AdSetCont.'@updateEmployerSettings')->name('admin-settings-employer-post');
	Route::get('/settings/job-portal-vs-saas', $AdSetCont.'@jobPortalVsSaasSettings')->name('admin-settings-jpvssaas');
	Route::post('/settings/job-portal-vs-saas', $AdSetCont.'@updateJobPortalVsSaasSettings')->name('admin-settings-jpvssaas-post');

	//Admin Languages module routes
	$AdLangCont = 'App\Http\Controllers\Admin\LanguagesController';
	Route::get('/languages', $AdLangCont.'@listView')->name('admin-languages');
	Route::post('/languages/data', $AdLangCont.'@data')->name('admin-languages-data');
	Route::get('/languages/create-or-edit/{language_id?}', $AdLangCont.'@createOrEdit')->name('admin-languages-create-or-edit');
	Route::get('/languages/edit-messages/{language_id}', $AdLangCont.'@editMessages')->name('admin-languages-messages-edit');
	Route::get('/languages/edit-validations/{language_id}', $AdLangCont.'@editValidations')->name('admin-languages-validations-edit');
	Route::post('/languages/save', $AdLangCont.'@save')->name('admin-languages-save');
	Route::post('/languages/update-messages', $AdLangCont.'@updateMessages')->name('admin-languages-messages-update');
	Route::post('/languages/update-validations', $AdLangCont.'@updateValidations')->name('admin-languages-validations-update');
	Route::get('/languages/status/{language_id}/{status}', $AdLangCont.'@changeStatus')->name('admin-languages-status');
	Route::get('/languages/selected/{language_id}', $AdLangCont.'@changeSelected')->name('admin-languages-selected');
	Route::get('/languages/default/{language_id}', $AdLangCont.'@changeDefault')->name('admin-languages-default');
	Route::post('/languages/bulk-action', $AdLangCont.'@bulkAction')->name('admin-languages-bul-action');
	Route::get('/languages/delete/{language_id}', $AdLangCont.'@delete')->name('admin-languages-delete');

	$AdCanCont = 'App\Http\Controllers\Admin\CandidatesController';
	$AdNotCont = 'App\Http\Controllers\Admin\NotificationsController';
	Route::get('/can-login-as/{candidate_id}/{user_id}', $AdCanCont.'@loginAs')->name('admin-candidates-loginas');	
	Route::get('/notifications', $AdNotCont.'@listView')->name('admin-notifications-list-view');

	$EssentialsController = 'App\Http\Controllers\EssentialsController@';
	Route::get('/generate-storage-link', $EssentialsController.'generateStorageLink')->name('generate-storage-link');

});


if (env('CFSAAS_ROUTE_SLUG') == 'subdomain_slug') {
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/* 3a : Candidate Public Routes With Subdomain 	*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
Route::group(['domain' => '{subdomain_slug}.'.dyanmicSubdomainUrl(), 'middleware' => [$mw['setslug'], $mw['essset']]], function () {
	$sd_route = '-sd';
	include base_path().'/routes/candidate-public-routes.php'; //To avoid repition, separated in a file
});
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/* 3b : Candidate Auth Routes With Subdomain 	*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
Route::group([
	'domain' => '{subdomain_slug}.'.dyanmicSubdomainUrl(), 
	'middleware' => [$mw['essset'], $mw['setslug'], $mw['candidate_auth'], $mw['xss_sanitizer']]], function () {
	$sd_route = '-sd';
	include base_path().'/routes/candidate-auth-routes.php'; //To avoid repition, separated in a file
});
}

/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*		4 : Front site (public) routes 			*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
$EssentialsController = 'App\Http\Controllers\EssentialsController@';
Route::get('/uploads/{part1?}/{part2?}/{part3?}/{part4?}', $EssentialsController.'uploadsView')->name('uploads-view');
Route::get('/set-color-theme/{color?}', $EssentialsController.'setColorTheme')->name('set-color-theme');
Route::get('/set-front-language/{id?}', $EssentialsController.'setFrontLanguage')->name('set-front-language');
Route::get('/set-employer-language/{id?}', $EssentialsController.'setEmployerLanguage')->name('set-employer-language');
Route::group(['middleware' => [$mw['essset']]], function () {

	$GeneralsController = 'App\Http\Controllers\Front\GeneralsController@';
	$FrontCanController = 'App\Http\Controllers\Front\CandidatesController@';
	$FrontNewsController = 'App\Http\Controllers\Front\NewsController@';
	$EssentialsController = 'App\Http\Controllers\EssentialsController@';
	Route::get('/', $GeneralsController.'index')->name('home');
	Route::get('/register', $GeneralsController.'registerView')->name('front-register');
	Route::get('/register-form', $GeneralsController.'registerViewForm')->name('front-register-form');
	Route::post('/employer-register-free', $GeneralsController.'registerFreeFormSubmit')->middleware('throttle:5,1')->name('register-free-form-submit');
	Route::post('/candidate-register', $FrontCanController.'register')->middleware('throttle:5,1')->name('front-candidate-register');
	Route::get('/activate-account/{token}', $FrontCanController.'activateAccount')->name('front-activate-account');		
	Route::post('/register-paid', $GeneralsController.'registerPaidFormSubmit')->name('register-paid-form-submit');
	Route::get('/forgot-password', $GeneralsController.'showForgotPassword')->name('front-forgot-view');
	Route::get('/forgot-password-form', $GeneralsController.'forgotPasswordViewForm')->name('front-forgot-form');
	Route::post('/send-password-link', $GeneralsController.'sendPasswordLink')->name('front-send-link');
	Route::get('/reset-password/{token}', $GeneralsController.'resetPassword')->name('front-reset-password-view');
	Route::post('/reset-password', $GeneralsController.'updatePasswordByForgot')->name('front-reset-password-post');

	Route::get('/features', $GeneralsController.'features')->name('features');
	Route::get('/contact', $GeneralsController.'contact')->name('contact');
	Route::get('/pricing', $GeneralsController.'pricing')->name('pricing');
	Route::get('/faqs', $GeneralsController.'faqs')->name('faqs');
	Route::get('/news', $FrontNewsController.'news')->name('front-news-list');
	Route::get('/news/{slug}', $FrontNewsController.'newsDetail')->name('front-news-detail');
	Route::get('/companies', $GeneralsController.'companies')->name('companies');
	Route::get('/company/{slug}', $GeneralsController.'companyDetail')->name('company-detail');
	Route::get('/candidates', $FrontCanController.'candidates')->name('front-candidates');
	Route::get('/candidate/{slug}', $FrontCanController.'candidatesDetail')->name('front-candidate-detail');
	Route::get('/pages/{slug}', $GeneralsController.'page')->name('page-detail');
	Route::post('/contact-form-submit', $GeneralsController.'contactFormSubmit')->name('contact-form-submit');
	Route::get('/schema', $EssentialsController.'schema')->name('schema');
	Route::get('/data/{employer_id?}', $EssentialsController.'data')->name('data-import');
	Route::get('/refresh-memberships', $EssentialsController.'refreshMemberships')->name('refresh-memberships');
	Route::post('/ckeditor/image', $EssentialsController.'uploadCkEditorImage')->name('ckeditor-image');
	Route::get('/login', $GeneralsController.'loginView')->name('front-login-form');
	Route::get('/login-register-modal/{type}', $GeneralsController.'loginRegisterModal')->name('front-login-register-modal');
	Route::post('/login-post', $GeneralsController.'login')->middleware('throttle:8,1')->name('front-login-post');
	Route::get('/logout', $GeneralsController.'logout')->name('front-logout');

	$FrontJobsController = 'App\Http\Controllers\Front\JobsController@';
	Route::get('/jobs', $FrontJobsController.'list')->name('front-jobs-list');
	Route::get('/job/{slug}', $FrontJobsController.'detail')->name('front-jobs-detail');

	Route::get('mark-candidate-favorite/{job_id}', $FrontCanController.'markFavorite')->name('front-mark-candidate-favorite');
	Route::get('unmark-candidate-favorite/{job_id}', $FrontCanController.'unmarkFavorite')->name('front-unmark-candidate-favorite');
	Route::get('mark-favorite/{job_id}', $FrontJobsController.'markFavorite')->name('front-mark-favourite');
	Route::get('unmark-favorite/{job_id}', $FrontJobsController.'unmarkFavorite')->name('front-unmark-favourite');
	Route::get('refer-job-view', $FrontJobsController.'referJobView')->name('front-refer-job-view');
	Route::post('refer-job', $FrontJobsController.'referJob')->name('front-refer-job');

});

/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*	4 : Front site (auth/candidate) routes 		*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
Route::group(['prefix'=>'account', 'middleware' => [$mw['essset'], $mw['candidate_auth_front']]], function () {
	$FrontCandCont = 'App\Http\Controllers\Front\CandidatesController@';
	$FrontJobsCont = 'App\Http\Controllers\Front\JobsController';
	$FrontQuizesCont = 'App\Http\Controllers\Front\QuizesController';
	$FrontResumesCont = 'App\Http\Controllers\Front\ResumesController';
	$FrontMembershipsCont = 'App\Http\Controllers\Front\MembershipsController';

	Route::get('profile', $FrontCandCont.'updateProfileView')->name('front-profile');
	Route::post('profile-update', $FrontCandCont.'updateProfile')->name('front-profile-update');
	Route::get('password', $FrontCandCont.'updatePasswordView')->name('front-password');
	Route::post('password-update', $FrontCandCont.'updatePassword')->name('front-pass-update');
	Route::get('settings', $FrontCandCont.'updateSettingsView')->name('front-settings');
	Route::post('settings-update', $FrontCandCont.'updateSettings')->name('front-settings-update');

	//Account ares job routes
	Route::get('job-applications', $FrontJobsCont.'@jobApplicationsView')->name('front-acc-job-apps');
	Route::get('job-application-delete/{job_application_id}', $FrontJobsCont.'@jobApplicationDelete')->name('front-acc-job-app-delete');
	Route::get('job-favorites', $FrontJobsCont.'@jobFavoritesView')->name('front-acc-job-favs');
	Route::get('job-referred', $FrontJobsCont.'@jobReferredView')->name('front-acc-job-referred');

	//Account area Front Resumes Routes (inner)
	Route::get('resume', $FrontResumesCont.'@listing')->name('front-acc-resume-listing');
	Route::post('create-resume', $FrontResumesCont.'@create')->name('front-acc-create-resume');
	Route::get('resume/{resume_id?}/{section?}', $FrontResumesCont.'@detailView')->name('front-acc-resume-view');
	Route::post('resume-save-general', $FrontResumesCont.'@updateGeneral')->name('front-acc-res-save-gen');
	Route::post('resume-save-experience', $FrontResumesCont.'@updateExperience')->name('front-acc-res-save-exp');
	Route::post('resume-save-qualification', $FrontResumesCont.'@updateQualification')->name('front-acc-res-save-qua');
	Route::post('resume-save-language', $FrontResumesCont.'@updateLanguage')->name('front-acc-res-save-lan');
	Route::post('resume-save-skill', $FrontResumesCont.'@updateSkill')->name('front-acc-res-save-skill');
	Route::post('resume-save-achievement', $FrontResumesCont.'@updateAchievement')->name('front-acc-res-save-ach');
	Route::post('resume-save-reference', $FrontResumesCont.'@updateReference')->name('front-acc-res-save-ref');
	Route::get('resume-add-section/{resume_id}/{type}', $FrontResumesCont.'@addSection')->name('front-acc-res-acc-sec');
	Route::get('resume-remove-section/{section_id}/{type}', $FrontResumesCont.'@removeSection')->name('front-acc-res-remo-sec');
	Route::post('resume-update-doc', $FrontResumesCont.'@updateDocResume')->name('front-acc-res-update-doc');
	
	Route::get('memberships', $FrontMembershipsCont.'@listing')->name('front-acc-memberships-view');
	Route::get('membership/renew', $FrontMembershipsCont.'@renewForm')->name('front-acc-renew-membership');
	Route::post('membership/save', $FrontMembershipsCont.'@save')->name('front-acc-membership-save');
	Route::post('stripe-payment', $FrontMembershipsCont.'@stripePayment')->name('front-acc-membership-payment-stripe');
	Route::post('offline-payment', $FrontMembershipsCont.'@offlinePayment')->name('front-acc-membership-payment-offline');
	Route::get('paypal-payment/{selected?}', $FrontMembershipsCont.'@paypalPayment')->name('front-acc-membership-payment-paypal');

	//Account Area Quizes routes
	Route::get('quizes', $FrontQuizesCont.'@listView')->name('front-acc-quizes');
	Route::get('quiz/{quiz_id}', $FrontQuizesCont.'@attemptView')->name('front-acc-quiz-single');
	Route::post('quiz-attempt', $FrontQuizesCont.'@attempt')->name('front-acc-quiz-attempt');
	Route::post('apply-job', $FrontJobsCont.'@applyJob')->name('front-apply-job');
});

/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*			Google and Linkedin routes 			*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
$GeneralsController = 'App\Http\Controllers\Front\GeneralsController';
Route::get('google-redirect', $GeneralsController.'@googleRedirect')->name('candidate-google-redirect');
Route::get('linkedin-redirect', $GeneralsController.'@linkedinRedirect')->name('candidate-linkedin-redirect');
Route::get('emails-cron', 'App\Http\Controllers\EssentialsController@emailsCron')->name('emails-cron');

/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*			Installation & PG routes 			*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
$EmMemCont = 'App\Http\Controllers\Employer\MembershipsController';
$FrMemCont = 'App\Http\Controllers\Front\MembershipsController';
Route::post('/paypal-payment-ipn', $EmMemCont.'@paypalPaymentIpn')->name('employers-payment-paypal-ipn');
Route::post('/paystack-payment', $EmMemCont.'@paystackPayment')->name('employers-payment-paystack');
Route::post('/paystack-webhook', $EmMemCont.'@paystackWebhook')->name('employers-payment-paystack-webhook');
Route::post('/paystack-callback', $EmMemCont.'@paystackCallback')->name('employers-payment-paystack-callback');
Route::post('/razorpay-verify', $EmMemCont.'@razorpayVerify')->name('employers-payment-razorpay-verify');
Route::post('/razorpay-order', $EmMemCont.'@razorpayOrder')->name('employers-payment-razorpay-order');
Route::get('/flutterwave-payment/{id?}', $EmMemCont.'@flutterwavePayment')->name('employer-payment-flutterwave');
Route::get('/flutterwave-process', $EmMemCont.'@flutterwavePaymentProcess')->name('employer-payment-flutterwave-process');
Route::get('/mollie-payment/{id?}', $EmMemCont.'@molliePayment')->name('employer-payment-mollie');
Route::get('/mollie-success', $EmMemCont.'@mollieSuccess')->name('employer-payment-mollie-success');
Route::get('/mollie-cancel', $EmMemCont.'@mollieCancel')->name('employer-payment-mollie-cancel');
Route::get('/tap-payment/{id?}', $EmMemCont.'@tapPayment')->middleware([$mw['essset'], $mw['employer_auth']])->name('employer-payment-tap');
Route::get('/tap-payment-callback', $EmMemCont.'@tapPaymentCallback')->middleware([$mw['essset'], $mw['employer_auth']])->name('employers-payment-tap-callback');

Route::post('/candidate-paypal-payment-ipn', $FrMemCont.'@paypalPaymentIpn')->name('candidate-payment-paypal-ipn');
Route::post('/candidate-paystack-payment', $FrMemCont.'@paystackPayment')->name('candidate-payment-paystack');
Route::post('/candidate-paystack-webhook', $FrMemCont.'@paystackWebhook')->name('candidate-payment-paystack-webhook');
Route::post('/candidate-paystack-callback', $FrMemCont.'@paystackCallback')->name('candidate-payment-paystack-callback');
Route::post('/candidate-razorpay-verify', $FrMemCont.'@razorpayVerify')->name('candidate-payment-razorpay-verify');
Route::post('/candidate-razorpay-order', $FrMemCont.'@razorpayOrder')->name('candidate-payment-razorpay-order');
Route::get('/candidate-flutterwave-payment/{id?}', $FrMemCont.'@flutterwavePayment')->name('candidate-payment-flutterwave');
Route::get('/candidate-flutterwave-process', $FrMemCont.'@flutterwavePaymentProcess')->name('candidate-payment-flutterwave-process');
Route::get('/candidate-mollie-payment/{id?}', $FrMemCont.'@molliePayment')->name('candidate-payment-mollie');
Route::get('/candidate-mollie-success', $FrMemCont.'@mollieSuccess')->name('candidate-payment-mollie-success');
Route::get('/candidate-mollie-cancel', $FrMemCont.'@mollieCancel')->name('candidate-payment-mollie-cancel');
Route::get('/candidate-tap-payment/{id?}', $FrMemCont.'@tapPayment')->name('candidate-payment-tap');
Route::get('/candidate-tap-payment-callback', $FrMemCont.'@tapPaymentCallback')->name('candidate-payment-tap-callback');

Route::post('/install/setupenv', 'App\Http\Controllers\EssentialsController@setupEnv')->name('install-app-env');
Route::get('/install/setupdb', 'App\Http\Controllers\EssentialsController@setupDatabase')->name('install-app-db');
Route::post('/install/setupuser', 'App\Http\Controllers\EssentialsController@setupAdminUser')->name('install-app-user');
Route::get('/install/{step?}', 'App\Http\Controllers\EssentialsController@install')->name('install-app');

if (env('CFSAAS_ROUTE_SLUG') == 'slug' && env('DB_DATABASE') != '') {
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/* 3c : Candidate Public Routes With Folder/Slug*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
Route::group(['prefix' => '{slug}', 'middleware'=>[$mw['setslug'], $mw['essset']]], function () {
	$sd_route = '';
	require base_path().'/routes/candidate-public-routes.php'; //To avoid repition, separated in a file
});
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/* 3d : Candidate Auth Routes With Folder/Slug 	*/
/*----------------------------------------------*/
/*----------------------------------------------*/
/*----------------------------------------------*/
Route::group(['prefix'=>'{slug}', 'middleware'=>[$mw['essset'], $mw['setslug'], $mw['candidate_auth'], $mw['xss_sanitizer']]], function() {
	$sd_route = '';
	include base_path().'/routes/candidate-auth-routes.php'; //To avoid repition, separated in a file
});
}
