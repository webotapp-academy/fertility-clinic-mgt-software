<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IvfController;
use App\Http\Controllers\AuthController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']);

// Protected Clinical Workstation Routes (Requires Login)
Route::middleware('auth')->group(function () {
    // Main View & Tabs (Dashboard)
    Route::get('/', [IvfController::class, 'index'])->name('ivf.home');

    // Settings & User Management CRUD
    Route::post('/settings/users', [IvfController::class, 'storeUser'])->name('ivf.users.store');
    Route::post('/settings/users/{id}/update', [IvfController::class, 'updateUser'])->name('ivf.users.update');
    Route::post('/settings/users/{id}/delete', [IvfController::class, 'deleteUser'])->name('ivf.users.delete');
    Route::post('/settings/password', [IvfController::class, 'updatePassword'])->name('ivf.users.password');
    Route::post('/settings/clinic', [IvfController::class, 'updateClinicSettings'])->name('ivf.settings.clinic');

    // Module 01: Leads CRUD
    Route::post('/leads', [IvfController::class, 'storeLead'])->name('ivf.leads.store');
    Route::post('/leads/{id}/update', [IvfController::class, 'updateLead'])->name('ivf.leads.update');
    Route::post('/leads/{id}/delete', [IvfController::class, 'deleteLead'])->name('ivf.leads.delete');

    // Couple & Workup CRUD
    Route::post('/couples', [IvfController::class, 'storeCouple'])->name('ivf.couples.store');
    Route::post('/couples/{id}/update', [IvfController::class, 'updateCoupleWorkup'])->name('ivf.couples.update');
    Route::post('/couples/{id}/delete', [IvfController::class, 'deleteCouple'])->name('ivf.couples.delete');
    Route::post('/couples/{id}/upload-consent', [IvfController::class, 'uploadConsentFile'])->name('ivf.couples.upload_consent');
    Route::get('/couples/{id}/consent-file', [IvfController::class, 'viewConsentFile'])->name('ivf.couples.view_consent_file');
    Route::post('/couples/{id}/remove-consent-file', [IvfController::class, 'removeConsentFile'])->name('ivf.couples.remove_consent_file');

    // Treatment Cycles & Folliculometry CRUD
    Route::post('/cycles', [IvfController::class, 'storeCycle'])->name('ivf.cycles.store');
    Route::post('/cycles/{id}/update', [IvfController::class, 'updateCycle'])->name('ivf.cycles.update');
    Route::post('/cycles/{id}/delete', [IvfController::class, 'deleteCycle'])->name('ivf.cycles.delete');
    Route::post('/scans', [IvfController::class, 'storeFollicularScan'])->name('ivf.scans.store');
    Route::post('/scans/{id}/update', [IvfController::class, 'updateFollicularScan'])->name('ivf.scans.update');
    Route::post('/scans/{id}/delete', [IvfController::class, 'deleteFollicularScan'])->name('ivf.scans.delete');

    // OPU & Embryology Lab CRUD
    Route::post('/lab', [IvfController::class, 'storeLabRecord'])->name('ivf.lab.store');
    Route::post('/lab/{id}/update', [IvfController::class, 'updateLabRecord'])->name('ivf.lab.update');
    Route::post('/lab/{id}/delete', [IvfController::class, 'deleteLabRecord'])->name('ivf.lab.delete');

    // Embryo Transfers CRUD
    Route::post('/transfers', [IvfController::class, 'storeTransfer'])->name('ivf.transfers.store');
    Route::post('/transfers/{id}/update', [IvfController::class, 'updateTransfer'])->name('ivf.transfers.update');
    Route::post('/transfers/{id}/delete', [IvfController::class, 'deleteTransfer'])->name('ivf.transfers.delete');

    // Failure RCA & Re-attempts CRUD
    Route::post('/failures/rca', [IvfController::class, 'storeFailureRca'])->name('ivf.failures.store');
    Route::post('/failures/{id}/delete', [IvfController::class, 'deleteFailureRca'])->name('ivf.failures.delete');
    Route::post('/outcomes/{id}/update', [IvfController::class, 'updateOutcome'])->name('ivf.outcomes.update');
    Route::post('/reattempt', [IvfController::class, 'planReattempt'])->name('ivf.reattempt.store');

    // Antenatal Care (ANC) CRUD
    Route::post('/anc', [IvfController::class, 'storeAnc'])->name('ivf.anc.store');
    Route::post('/anc/{id}/update', [IvfController::class, 'updateAnc'])->name('ivf.anc.update');
    Route::post('/anc/{id}/delete', [IvfController::class, 'deleteAnc'])->name('ivf.anc.delete');

    // Module 11: Labor & Delivery CRUD
    Route::post('/deliveries', [IvfController::class, 'storeDelivery'])->name('ivf.deliveries.store');
    Route::post('/deliveries/{id}/update', [IvfController::class, 'updateDelivery'])->name('ivf.deliveries.update');
    Route::post('/deliveries/{id}/delete', [IvfController::class, 'deleteDelivery'])->name('ivf.deliveries.delete');

    // Module 12: Pediatrics & Vaccinations CRUD
    Route::post('/babies/{id}/update', [IvfController::class, 'updateBaby'])->name('ivf.babies.update');
    Route::post('/vaccines', [IvfController::class, 'storeVaccine'])->name('ivf.vaccines.store');
    Route::post('/vaccines/{id}/update', [IvfController::class, 'updateVaccine'])->name('ivf.vaccines.update_details');
    Route::post('/vaccines/{id}/status', [IvfController::class, 'updateVaccineStatus'])->name('ivf.vaccines.update');
    Route::post('/vaccines/{id}/delete', [IvfController::class, 'deleteVaccine'])->name('ivf.vaccines.delete');

    // Module 13: Cryobank CRUD
    Route::post('/cryo', [IvfController::class, 'storeCryo'])->name('ivf.cryo.store');
    Route::post('/cryo/{id}/update', [IvfController::class, 'updateCryo'])->name('ivf.cryo.update');
    Route::post('/cryo/{id}/delete', [IvfController::class, 'deleteCryo'])->name('ivf.cryo.delete');
});
