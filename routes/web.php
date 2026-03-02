<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ProfileSettingsController;
use App\Http\Controllers\User\PasswordResetController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\WaitlistController;

// ==============================================
// COMMON / PUBLIC ROUTES
// ==============================================
Route::get('/', [CommonController::class, 'index'])->name('invite');
Route::view('/features', 'landing')->name('features');
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::post('/contact/submit', [CommonController::class, 'submitContactForm'])->name('contact.submit');
Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy-policy');
Route::view('/licensing', 'pages.licensing')->name('licensing');

// Invite Code routes
Route::get('/invite-code', [CommonController::class, 'showInviteCodeForm'])->name('invite-code');
Route::post('/invite-code', [CommonController::class, 'processInviteCode'])->name('invite-code.process');

// Waitlist routes
Route::get('/waitlist/apply', [WaitlistController::class, 'showApplication'])->name('waitlist.apply');
Route::post('/waitlist/apply', [WaitlistController::class, 'store'])->name('waitlist.store');
Route::get('/waitlist/thank-you', [WaitlistController::class, 'thankYou'])->name('waitlist.thank-you');

// Quiz routes (accessible to guests only - optional before registration)
Route::middleware('user.guest')->group(function () {
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::get('/quiz/start', [QuizController::class, 'start'])->name('quiz.start');
    Route::post('/quiz/submit', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/proceed', [QuizController::class, 'proceedToRegistration'])->name('quiz.proceed');
    
    // Del Match Code only quiz for signup (old flow - quiz first)
    Route::get('/signup', [QuizController::class, 'signupQuiz'])->name('signup.quiz');
    Route::post('/signup/submit', [QuizController::class, 'submitSignupQuiz'])->name('signup.submit');
});

// Invite code verification (must come first)
Route::middleware('user.guest')->group(function () {
    Route::get('/invite', [RegistrationController::class, 'showInviteCodeForm'])->name('invite.show');
    Route::post('/invite/verify', [RegistrationController::class, 'verifyInviteCode'])->name('invite.verify');
});

// Registration routes (accessible to guests only - new primary flow)
Route::middleware('user.guest')->group(function () {
    Route::get('/register', [RegistrationController::class, 'index'])->name('register');
    Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
    Route::get('/register/quiz', [RegistrationController::class, 'showRegistrationQuiz'])->name('registration.quiz');
    Route::post('/register/complete', [RegistrationController::class, 'completeRegistration'])->name('registration.complete');
    Route::get('/verify-email/{token}', [RegistrationController::class, 'verifyEmail'])->name('email.verify');
});

// Registration results (for newly registered users)
Route::middleware('auth:user')->group(function () {
    Route::get('/register/results', [RegistrationController::class, 'showResults'])->name('registration.results');
});

// Email verification routes (for authenticated users who haven't verified)
Route::middleware('auth')->group(function () {
    Route::post('/resend-verification', [RegistrationController::class, 'resendVerification'])->name('verification.resend');
});


// Guest (only not logged in users can access)
Route::middleware('user.guest')->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
    
    // Password Reset Routes
    Route::get('forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

// Authenticated (logged in users)
Route::middleware('auth')->group(function () {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    
    // Profile completion routes
    Route::get('/complete-profile', [ProfileController::class, 'index'])->name('profile.complete');
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
    Route::post('/profile/update-bio', [ProfileController::class, 'updateBio'])->name('profile.update-bio');
    Route::post('/profile/update-interests', [ProfileController::class, 'updateInterests'])->name('profile.update-interests');
    Route::post('/profile/update-preferences', [ProfileController::class, 'updatePreferences'])->name('profile.update-preferences');
});


// ==============================================
// USER ROUTES
// ==============================================
// Guest self-discovery route (outside user prefix to avoid conflicts)
Route::get('learn', [UserDashboardController::class, 'selfDiscovery'])->middleware('user.guest')->name('guest.self-discovery');

Route::prefix('user')->group(function () {

    Route::middleware('user.guest')->group(function () {
        // (Add other user guest routes if needed)
    });

    // Email verification route (accessible without authentication)
    Route::get('email-verification/verify/{token}', [\App\Http\Controllers\User\EmailVerificationController::class, 'verify'])->name('user.email-verification.verify');

    Route::middleware('user.auth')->group(function () {
        // Email verification routes (for authenticated users)
        Route::get('email-verification/notice', [\App\Http\Controllers\User\EmailVerificationController::class, 'notice'])->name('user.email-verification.notice');
        Route::post('email-verification/resend', [\App\Http\Controllers\User\EmailVerificationController::class, 'resend'])->name('user.email-verification.resend');
        Route::get('email-verification/success', [\App\Http\Controllers\User\EmailVerificationController::class, 'success'])->name('user.email-verification.success');
        
        // Routes that require email verification
        Route::middleware('verified')->group(function () {
            // Onboarding routes (accessible before profile completion)
            Route::get('onboarding', [\App\Http\Controllers\User\OnboardingController::class, 'index'])->name('user.onboarding.index');
            Route::get('onboarding/step-1', [\App\Http\Controllers\User\OnboardingController::class, 'step1'])->name('user.onboarding.step1');
            Route::post('onboarding/step-1', [\App\Http\Controllers\User\OnboardingController::class, 'saveStep1'])->name('user.onboarding.save-step1');
            Route::get('onboarding/step-2', [\App\Http\Controllers\User\OnboardingController::class, 'step2'])->name('user.onboarding.step2');
            Route::post('onboarding/step-2', [\App\Http\Controllers\User\OnboardingController::class, 'saveStep2'])->name('user.onboarding.save-step2');
            Route::get('onboarding/step-3', [\App\Http\Controllers\User\OnboardingController::class, 'step3'])->name('user.onboarding.step3');
            Route::post('onboarding/step-3', [\App\Http\Controllers\User\OnboardingController::class, 'saveStep3'])->name('user.onboarding.save-step3');
            Route::get('onboarding/step-4', [\App\Http\Controllers\User\OnboardingController::class, 'step4'])->name('user.onboarding.step4');
            Route::post('onboarding/step-4', [\App\Http\Controllers\User\OnboardingController::class, 'saveStep4'])->name('user.onboarding.save-step4');
            Route::get('onboarding/complete', [\App\Http\Controllers\User\OnboardingController::class, 'complete'])->name('user.onboarding.complete');
            
            // Routes that require BOTH email verification AND profile completion
            Route::middleware('profile.complete')->group(function () {
                Route::get('dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
                Route::get('discovery', [UserDashboardController::class, 'discovery'])->name('user.discovery');
                Route::get('self-discovery', [UserDashboardController::class, 'selfDiscovery'])->name('user.self-discovery');
                Route::get('continue-quiz', [UserDashboardController::class, 'continueQuiz'])->name('user.continue-quiz');
                Route::post('quiz/submit', [UserDashboardController::class, 'submitQuiz'])->name('user.quiz.submit');
                
                // Circle routes
                Route::get('circle', [UserDashboardController::class, 'circle'])->name('user.circle');
                Route::post('circle/invite', [UserDashboardController::class, 'inviteToCircle'])->name('user.circle.invite');
                Route::get('circle/accept/{token}', [UserDashboardController::class, 'acceptCircleInvite'])->name('user.circle.accept');
                
                // Match routes
                Route::post('flag-interest', [UserDashboardController::class, 'flagInterest'])->name('user.flag-interest');
                Route::post('schedule-date', [UserDashboardController::class, 'scheduleDate'])->name('user.schedule-date');
            });
        });
        
        // Routes that require email verification
        Route::middleware('verified')->group(function () {
            // Post-registration quiz routes
            Route::get('quiz/start', [QuizController::class, 'userQuizStart'])->name('user.quiz.start');
            Route::post('quiz/complete', [QuizController::class, 'userQuizComplete'])->name('user.quiz.complete');
        });
        
        // Profile Settings routes (allow unverified users to update email)
        Route::get('profile-settings', [ProfileSettingsController::class, 'index'])->name('user.profile-settings');
        Route::post('profile-settings/update-profile', [ProfileSettingsController::class, 'updateProfile'])->name('user.profile-settings.update-profile');
        Route::post('profile-settings/update-password', [ProfileSettingsController::class, 'updatePassword'])->name('user.profile-settings.update-password');
        Route::post('profile-settings/upload-profile-image', [ProfileSettingsController::class, 'uploadProfileImage'])->name('user.profile-settings.upload-profile-image');
        Route::post('profile-settings/upload-intro-video', [ProfileSettingsController::class, 'uploadIntroVideo'])->name('user.profile-settings.upload-intro-video');
        Route::post('profile-settings/upload-audio-prompt', [ProfileSettingsController::class, 'uploadAudioPrompt'])->name('user.profile-settings.upload-audio-prompt');
        Route::post('profile-settings/remove-profile-image', [ProfileSettingsController::class, 'removeProfileImage'])->name('user.profile-settings.remove-profile-image');
        Route::post('profile-settings/remove-intro-video', [ProfileSettingsController::class, 'removeIntroVideo'])->name('user.profile-settings.remove-intro-video');
        Route::post('profile-settings/delete-account', [ProfileSettingsController::class, 'deleteAccount'])->name('user.profile-settings.delete-account');
        
        Route::get('logout', [LoginController::class, 'userLogout'])->name('user.logout');
    });
});

// ==============================================
// STORAGE FILE ROUTES
// ==============================================
Route::get('storage/{folder}/{filename}', function ($folder, $filename) {
    // Security: Only allow specific folders
    $allowedFolders = ['profile-images', 'intro-videos', 'audio-prompts', 'avatars'];
    
    if (!in_array($folder, $allowedFolders)) {
        abort(403);
    }
    
    // Security: Prevent directory traversal
    $filename = basename($filename);
    $folder = basename($folder);
    
    $path = storage_path('app/public/' . $folder . '/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    $mimeType = mime_content_type($path);
    
    return response()->file($path, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000',
        'Expires' => gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT',
    ]);
})->name('storage.file');

// ==============================================
// API ROUTES FOR AVATAR
// ==============================================
Route::prefix('api')->middleware('user.auth')->group(function () {
    Route::get('avatar/info', [AvatarController::class, 'info'])->name('api.avatar.info');
    Route::post('avatar/regenerate', [AvatarController::class, 'regenerate'])->name('api.avatar.regenerate');
});


// ==============================================
// MANAGER ROUTES
// ==============================================
Route::prefix('manager')->group(function () {

    Route::middleware('manager.guest')->group(function () {
        // (Add manager guest routes if needed)
    });

    Route::middleware('manager.auth')->group(function () {
        Route::get('dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');
        Route::get('logout', [LoginController::class, 'managerLogout'])->name('manager.logout');
    });
});


// ==============================================
// ADMIN ROUTES
// ==============================================
Route::prefix('admin')->group(function () {

    Route::middleware('admin.guest')->group(function () {
        Route::get('login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::middleware('admin.auth')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('settings', [AdminDashboardController::class, 'adminSettings'])->name('admin.settings');
        
        // Profile Settings Routes
        Route::get('profile/settings', [AdminDashboardController::class, 'profileSettings'])->name('admin.profile.settings');
        Route::put('profile/update', [AdminDashboardController::class, 'updateProfile'])->name('admin.profile.update');
        Route::put('password/update', [AdminDashboardController::class, 'updatePassword'])->name('admin.password.update');
        Route::put('profile/picture/update', [AdminDashboardController::class, 'updateProfilePicture'])->name('admin.profile.picture.update');
        Route::delete('profile/picture/remove', [AdminDashboardController::class, 'removeProfilePicture'])->name('admin.profile.picture.remove');
        
        // Quiz Management Routes
        Route::get('quiz', [\App\Http\Controllers\Admin\QuizController::class, 'index'])->name('admin.quiz');
        Route::get('quiz/questions', [\App\Http\Controllers\Admin\QuizController::class, 'getQuestions'])->name('admin.quiz.questions');
        Route::post('quiz/questions', [\App\Http\Controllers\Admin\QuizController::class, 'store'])->name('admin.quiz.store');
        Route::get('quiz/questions/{id}', [\App\Http\Controllers\Admin\QuizController::class, 'show'])->name('admin.quiz.show');
        Route::put('quiz/questions/{id}', [\App\Http\Controllers\Admin\QuizController::class, 'update'])->name('admin.quiz.update');
        Route::delete('quiz/questions/{id}', [\App\Http\Controllers\Admin\QuizController::class, 'destroy'])->name('admin.quiz.destroy');
        Route::post('quiz/questions/{id}/toggle-status', [\App\Http\Controllers\Admin\QuizController::class, 'toggleStatus'])->name('admin.quiz.toggle');
        
        // Quiz Sections Management Routes
        Route::get('sections', [\App\Http\Controllers\Admin\QuizSectionController::class, 'index'])->name('admin.sections.index');
        Route::post('sections', [\App\Http\Controllers\Admin\QuizSectionController::class, 'store'])->name('admin.sections.store');
        Route::get('sections/{id}', [\App\Http\Controllers\Admin\QuizSectionController::class, 'show'])->name('admin.sections.show');
        Route::put('sections/{id}', [\App\Http\Controllers\Admin\QuizSectionController::class, 'update'])->name('admin.sections.update');
        Route::delete('sections/{id}', [\App\Http\Controllers\Admin\QuizSectionController::class, 'destroy'])->name('admin.sections.destroy');
        Route::post('sections/{id}/toggle-status', [\App\Http\Controllers\Admin\QuizSectionController::class, 'toggleStatus'])->name('admin.sections.toggle');
        Route::get('sections/active/dropdown', [\App\Http\Controllers\Admin\QuizSectionController::class, 'getActiveSections'])->name('admin.sections.active');
        
        // Legacy route (keeping for compatibility)
        Route::get('addquiz', [AdminDashboardController::class, 'addquizManage'])->name('admin.registration.addquiz');
        Route::get('addsection', [AdminDashboardController::class, 'addsectionManage'])->name('admin.registration.addsection');

        // Email Verification Management Routes
        Route::prefix('email-verification')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\EmailVerificationController::class, 'index'])->name('admin.email-verification');
            Route::post('verify/{userId}', [\App\Http\Controllers\Admin\EmailVerificationController::class, 'verifyUser'])->name('admin.email-verification.verify');
            Route::post('resend/{userId}', [\App\Http\Controllers\Admin\EmailVerificationController::class, 'resendVerification'])->name('admin.email-verification.resend');
            Route::post('bulk-verify', [\App\Http\Controllers\Admin\EmailVerificationController::class, 'bulkVerify'])->name('admin.email-verification.bulk-verify');
            Route::post('bulk-resend', [\App\Http\Controllers\Admin\EmailVerificationController::class, 'bulkResend'])->name('admin.email-verification.bulk-resend');
            Route::post('clean-expired', [\App\Http\Controllers\Admin\EmailVerificationController::class, 'cleanExpiredTokens'])->name('admin.email-verification.clean-expired');
        });

        // Quotes Management Routes
        Route::prefix('quotes')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\QuotesController::class, 'index'])->name('admin.quotes.index');
            Route::get('create', [\App\Http\Controllers\Admin\QuotesController::class, 'create'])->name('admin.quotes.create');
            Route::post('/', [\App\Http\Controllers\Admin\QuotesController::class, 'store'])->name('admin.quotes.store');
            Route::get('{quote}/edit', [\App\Http\Controllers\Admin\QuotesController::class, 'edit'])->name('admin.quotes.edit');
            Route::put('{quote}', [\App\Http\Controllers\Admin\QuotesController::class, 'update'])->name('admin.quotes.update');
            Route::delete('{quote}', [\App\Http\Controllers\Admin\QuotesController::class, 'destroy'])->name('admin.quotes.destroy');
            Route::post('{quote}/toggle-active', [\App\Http\Controllers\Admin\QuotesController::class, 'toggleActive'])->name('admin.quotes.toggle-active');
        });

        // Waitlist Management Routes
        Route::prefix('waitlist')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\WaitlistController::class, 'index'])->name('admin.waitlist');
            Route::post('approve/{applicationId}', [\App\Http\Controllers\Admin\WaitlistController::class, 'approve'])->name('admin.waitlist.approve');
            Route::post('reject/{applicationId}', [\App\Http\Controllers\Admin\WaitlistController::class, 'reject'])->name('admin.waitlist.reject');
            Route::post('resend-invite/{applicationId}', [\App\Http\Controllers\Admin\WaitlistController::class, 'resendInvite'])->name('admin.waitlist.resend-invite');
            Route::post('bulk-approve', [\App\Http\Controllers\Admin\WaitlistController::class, 'bulkApprove'])->name('admin.waitlist.bulk-approve');
        });

        // Waitlist Questions Management Routes
        Route::prefix('waitlist-questions')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\WaitlistQuestionController::class, 'index'])->name('admin.waitlist-questions.index');
            Route::get('create', [\App\Http\Controllers\Admin\WaitlistQuestionController::class, 'create'])->name('admin.waitlist-questions.create');
            Route::post('/', [\App\Http\Controllers\Admin\WaitlistQuestionController::class, 'store'])->name('admin.waitlist-questions.store');
            Route::get('{id}', [\App\Http\Controllers\Admin\WaitlistQuestionController::class, 'show'])->name('admin.waitlist-questions.show');
            Route::get('{id}/edit', [\App\Http\Controllers\Admin\WaitlistQuestionController::class, 'edit'])->name('admin.waitlist-questions.edit');
            Route::put('{id}', [\App\Http\Controllers\Admin\WaitlistQuestionController::class, 'update'])->name('admin.waitlist-questions.update');
            Route::delete('{id}', [\App\Http\Controllers\Admin\WaitlistQuestionController::class, 'destroy'])->name('admin.waitlist-questions.destroy');
            Route::post('{id}/toggle-status', [\App\Http\Controllers\Admin\WaitlistQuestionController::class, 'toggleStatus'])->name('admin.waitlist-questions.toggle-status');
        });

        // Contact Messages Management Routes
        Route::prefix('contact-messages')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('admin.contact-messages');
            Route::get('{id}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('admin.contact-messages.show');
            Route::post('{id}/status', [\App\Http\Controllers\Admin\ContactMessageController::class, 'updateStatus'])->name('admin.contact-messages.status');
            Route::post('{id}/notes', [\App\Http\Controllers\Admin\ContactMessageController::class, 'updateNotes'])->name('admin.contact-messages.notes');
            Route::post('{id}/reply', [\App\Http\Controllers\Admin\ContactMessageController::class, 'sendReply'])->name('admin.contact-messages.reply');
            Route::delete('{id}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('admin.contact-messages.destroy');
            Route::post('bulk-delete', [\App\Http\Controllers\Admin\ContactMessageController::class, 'bulkDelete'])->name('admin.contact-messages.bulk-delete');
            Route::post('bulk-update-status', [\App\Http\Controllers\Admin\ContactMessageController::class, 'bulkUpdateStatus'])->name('admin.contact-messages.bulk-update-status');
        });


        // User Management Routes
        Route::prefix('user-management')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('admin.user-management.index');
            Route::get('{id}', [\App\Http\Controllers\Admin\UserManagementController::class, 'show'])->name('admin.user-management.show');
            Route::get('{id}/edit', [\App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('admin.user-management.edit');
            Route::put('{id}', [\App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('admin.user-management.update');
            Route::delete('{id}', [\App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('admin.user-management.destroy');
            Route::post('{id}/restore', [\App\Http\Controllers\Admin\UserManagementController::class, 'restore'])->name('admin.user-management.restore');
            Route::post('{id}/block', [\App\Http\Controllers\Admin\UserManagementController::class, 'block'])->name('admin.user-management.block');
            Route::post('{id}/unblock', [\App\Http\Controllers\Admin\UserManagementController::class, 'unblock'])->name('admin.user-management.unblock');
        });

        // Quiz Results Management Routes
        Route::prefix('quiz-results')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\QuizResultsController::class, 'index'])->name('admin.quiz-results.index');
            Route::get('search', [\App\Http\Controllers\Admin\QuizResultsController::class, 'search'])->name('admin.quiz-results.search');
            Route::get('{id}/history', [\App\Http\Controllers\Admin\QuizResultsController::class, 'history'])->name('admin.quiz-results.history');
            Route::delete('{id}/history/{historyIndex}', [\App\Http\Controllers\Admin\QuizResultsController::class, 'deleteHistory'])->name('admin.quiz-results.delete-history');
            Route::get('{id}', [\App\Http\Controllers\Admin\QuizResultsController::class, 'show'])->name('admin.quiz-results.show');
        });

        Route::get('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    });
});
