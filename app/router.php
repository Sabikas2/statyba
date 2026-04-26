<?php
declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\ClientController;
use App\Controllers\ContractorController;
use App\Controllers\AdminController;
use App\Controllers\ProjectController;
use App\Controllers\BidController;
use App\Controllers\NotificationController;
use App\Controllers\PaymentController;

$route = $_GET['r'] ?? 'home';

$map = [
 'home' => [ClientController::class,'home'],
 'login' => [AuthController::class,'login'],
 'register' => [AuthController::class,'register'],
 'logout' => [AuthController::class,'logout'],
 'client.dashboard' => [ClientController::class,'dashboard'],
 'project.new' => [ProjectController::class,'new'],
 'project.create' => [ProjectController::class,'create'],
 'project.view' => [ProjectController::class,'view'],
 'project.select_contractor' => [ProjectController::class,'selectContractor'],
 'notifications.send_invites' => [NotificationController::class,'sendInvites'],
 'contractor.dashboard' => [ContractorController::class,'dashboard'],
 'contractor.profile' => [ContractorController::class,'profile'],
 'contractor.optout' => [ContractorController::class,'optOut'],
 'invite.view' => [ContractorController::class,'inviteView'],
 'bid.submit' => [BidController::class,'submit'],
 'admin.dashboard' => [AdminController::class,'dashboard'],
 'admin.users' => [AdminController::class,'users'],
 'admin.contractors' => [AdminController::class,'contractors'],
 'admin.projects' => [AdminController::class,'projects'],
 'admin.import_contractors' => [AdminController::class,'importContractors'],
 'admin.settings' => [AdminController::class,'settings'],
 'payments' => [PaymentController::class,'index'],
];

if (!isset($map[$route])) { http_response_code(404); echo '404'; exit; }

call_user_func($map[$route]);
