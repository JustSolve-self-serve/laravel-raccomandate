<?php

use Illuminate\Support\Facades\Route;
use JustSolve\Raccomandate\Http\Controllers\StateController;

Route::post('/state', [StateController::class, 'update']);
