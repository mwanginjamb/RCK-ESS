<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/21/2020
 * Time: 2:39 PM
 */

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AdminlteAsset;
use common\widgets\Alert;

AdminlteAsset::register($this);

$webroot = Yii::getAlias(@$webroot);
$absoluteUrl = \yii\helpers\Url::home(true);
$employee = (!Yii::$app->user->isGuest && is_array(Yii::$app->user->identity->employee))?Yii::$app->user->identity->employee[0]:[];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
   
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.75">


     <!-- PWA SHIT -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#e8701f">
    <link rel="apple-touch-icon" href="/images/apple-touch-icon-120-120.png"/>
    <meta name="apple-mobile-web-app-status-bar" content="#37327c">
    
    <!-- / PWA SHIT -->


    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        @viewport {
          zoom: 0.75;
          min-zoom: 0.5;
          max-zoom: 0.9;
        }
    </style>
</head>

<?php $this->beginBody() ?>

<body class="hold-transition sidebar-mini layout-fixed accent-info">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-info">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <?php if(!Yii::$app->user->isGuest): ?>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="<?= $absoluteUrl ?>site" class="nav-link">Home</a>
                    </li>

                <?php if(Yii::$app->controller->id == 'applicantprofile'){ ?>

                    <li class="nav-item d-none d-sm-inline-block">
                        <?= Html::a('My Applications',['recruitment/applications'],['class'=>"nav-link"])?>

                    </li>

                <?php } ?>

                <?php endif; ?>
                <!--<li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>-->
            </ul>

            <!-- SEARCH FORM -->
            <!--<form class="form-inline ml-3">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>-->

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto ">
                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <!--<i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>-->
                    </a>
                    
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-th-large"></i>

                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!--<span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>-->






                        <div class="dropdown-divider"></div>

                        <?= (Yii::$app->user->isGuest)? Html::a('<i class="fas fa-sign-in-alt "></i> Signup','/site/signup/',['class'=> 'dropdown-item']): ''; ?>

                        <div class="dropdown-divider"></div>

                        <?= (Yii::$app->user->isGuest)? Html::a('<i class="fas fa-lock-open"></i> Login','/site/login/',['class'=> 'dropdown-item']): ''; ?>

                        <div class="dropdown-divider"></div>

                        <div class="dropdown-divider"></div>

                        <?= (!Yii::$app->user->isGuest)? Html::a('<i class="fas fa-sign-out-alt"></i> Logout','/site/logout/',['class'=> 'dropdown-item']):''; ?>

                        <div class="dropdown-divider"></div>

                        <?= 
                       
                         Html::a('<i class="fas fa-user"></i> Profile',['./employee'],['class'=> 'dropdown-item'])
                        ; ?>

                        <div class="dropdown-divider"></div>

                        <?php (!Yii::$app->user->isGuest)? Html::a('<i class="fas fa-file-pdf "></i> ESS Manuals','../essfile/index',['class'=> 'dropdown-item']): ''; ?>



                    </div>
                </li>
               <!-- <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="false" href="#">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>-->
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-4 sidebar-light-info">
            <!-- Brand Logo -->
            <a href="<?= $absoluteUrl ?>site" class="brand-link">
                <!--<img src="<?= $webroot ?>/images/Logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                     style="opacity: .8">-->
                <span class="brand-text font-weight-light"><?= Yii::$app->params['ClientCompany']?></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
               <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?/*= $webroot */?>/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="<?/*= $absoluteUrl */?>employee/" class="d-block"><?/*= (!Yii::$app->user->isGuest)? ucwords($employee->First_Name.' '.$employee->Last_Name): ''*/?></a>
                    </div>
                </div>-->

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->


<!--Approval Management -->
                        <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isApprover()): ?>
                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('approvals')?'menu-open':'' ?>">

                            <a href="#" class="nav-link <?= Yii::$app->recruitment->currentCtrl('approvals')?'active':'' ?>">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Approval Management
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>approvals" class="nav-link <?= Yii::$app->recruitment->currentaction('approvals','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>Approval Requests</p>
                                    </a>
                                </li>


                            </ul>
                        </li>
                        <?php endif; ?>
<!--end Aprroval Management-->


                        <li class="nav-item has-treeview  <?= Yii::$app->recruitment->currentCtrl(['leave','leavestatement','leaverecall','leaveplan','leave-reimburse'])?'menu-open':'' ?>">
                            <a href="#" class="nav-link <?= Yii::$app->recruitment->currentCtrl('leave')?'active':'' ?>">
                                <i class="nav-icon fas fa-paper-plane"></i>
                                <p>
                                    Leave Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <!--<li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leave/create" class="nav-link <?= Yii::$app->recruitment->currentaction('leave','create')?'active':'' ?> ">
                                        <i class="fa fa-running nav-icon"></i>
                                        <p>New Leave Application</p>
                                    </a>
                                </li>-->
                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leave/" class="nav-link <?= Yii::$app->recruitment->currentaction('leave','index')?'active':'' ?>">
                                        <i class="fa fa-door-open nav-icon"></i>
                                        <p>Leave Applications</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leavestatement/index" class="nav-link <?= Yii::$app->recruitment->currentaction('leavestatement','index')?'active':'' ?>">
                                        <i class="fa fa-file-pdf nav-icon"></i>
                                        <p>Leave Statement  Report</p>
                                    </a>
                                </li>

                               <!-- <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leaverecall/create/?create=1" class="nav-link <?= Yii::$app->recruitment->currentaction('leaverecall','create')?'active':'' ?>">
                                        <i class="fa fa-recycle nav-icon"></i>
                                        <p>Recall Leave</p>
                                    </a>
                                </li>-->

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leaverecall/index" class="nav-link <?= Yii::$app->recruitment->currentaction('leaverecall',['index','view'])?'active':'' ?>">
                                        <i class="fa fa-list nav-icon"></i>
                                        <p>Recall Leave List</p>
                                    </a>
                                </li>

                                <!-- Leave Reimbursement -->

                                 <!--<li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leave-reimburse/index" class="nav-link <?= Yii::$app->recruitment->currentaction('leave-reimburse',['index','view'])?'active':'' ?>">
                                        <i class="fa fa-list nav-icon"></i>
                                        <p>Leave Reimbursement</p>
                                    </a>
                                </li>-->

                                <!--<li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leaveplan/create" class="nav-link <?= Yii::$app->recruitment->currentaction('leaveplan','create')?'active':'' ?>">
                                        <i class="fa fa-directions nav-icon"></i>
                                        <p>New Leave Plan</p>
                                    </a>
                                </li>-->

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leaveplan/index" class="nav-link <?= Yii::$app->recruitment->currentaction('leaveplan','index')?'active':'' ?>">
                                        <i class="fa fa-list nav-icon"></i>
                                        <p>Leave Plan List</p>
                                    </a>
                                </li>
                                 <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leaveplan/departmental-leave-plan" class="nav-link <?= Yii::$app->recruitment->currentaction('leaveplan','departmental-leave-plan')?'active':'' ?>">
                                        <i class="fa fa-list nav-icon"></i>
                                        <p>Departmental Leave Plan </p>
                                    </a>
                                </li>

                            </ul>
                        </li>


                         <!-- Imprest management --->

                         <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl(['imprest','surrender','claim','safari'])?'menu-open':'menu-close' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl(['imprest','surrender','claim'])?'active':'' ?>">
                                <i class="nav-icon fa fa-coins"></i>
                                <p>
                                    Imprest Management
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>imprest" class="nav-link <?= Yii::$app->recruitment->currentaction('imprest','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Imprest List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>imprest/surrenderlist" class="nav-link <?= Yii::$app->recruitment->currentaction('imprest','surrenderlist')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Imprest Surrender</p>
                                    </a>
                                </li>



                            </ul>

                        </li>


                        <!-- Fund Requisition -->
                        
                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl(['fund-requisition'])?'menu-open':'menu-close' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl(['fund-requisition'])?'active':'' ?>">
                                <i class="nav-icon fa fa-coins"></i>
                                <p>
                                    Allowance Requisition
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>fund-requisition" class="nav-link <?= Yii::$app->recruitment->currentaction('fund-requisition','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Allowance List</p>
                                    </a>
                                </li>

                            </ul>

                        </li>


                        <!-- Salary Advance -->


                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('salaryadvance')?'menu-open':'' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl('salaryadvance')?'active':'' ?>">
                                <i class="nav-icon fa fa-money-check"></i>
                                <p>
                                    Salary Advance
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>salaryadvance" class="nav-link <?= Yii::$app->recruitment->currentaction('salaryadvance','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Salary Advance List</p>
                                    </a>
                                </li>


                            </ul>

                        </li>

                        <!--/Salary Advance -->

                       


                       


                        <!--Payroll reports -->
                         <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl(['payslip','p9'])?'menu-open':'' ?>">
                            <a href="#" class="nav-link <?= Yii::$app->recruitment->currentCtrl(['payslip','p9'])?'active':'' ?>">
                                <i class="nav-icon fa fa-file-invoice-dollar"></i>
                                <p>
                                    Payroll Reports
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>payslip" class="nav-link <?= Yii::$app->recruitment->currentaction('payslip','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>Generate Payslip</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>p9" class="nav-link <?= Yii::$app->recruitment->currentaction('p9','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>Generate P9 </p>
                                    </a>
                                </li>

                                <!--<li class="nav-item">
                                    <a href="<?/*= $absoluteUrl */?>medical" class="nav-link <?/*= Yii::$app->recruitment->currentaction('p9','index')?'active':'' */?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>Medical Claim </p>
                                    </a>
                                </li>-->

                            </ul>
                        </li>
                        <!--payroll reports-->

                         <!--Timesheets -->

                         <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl(['timesheet'])?'menu-open':'' ?>">
                            <a href="#" class="nav-link <?= Yii::$app->recruitment->currentCtrl(['timesheet'])?'active':'' ?>">
                                <i class="nav-icon fa fa-clock"></i>
                                <p>
                                    Timesheets
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>timesheet" class="nav-link <?= Yii::$app->recruitment->currentaction('timesheet','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>New Timesheets</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>timesheet/pending" class="nav-link <?= Yii::$app->recruitment->currentaction('timesheet','pending')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>Pending Approval</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>timesheet/approved" class="nav-link <?= Yii::$app->recruitment->currentaction('timesheet','approved')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>Approved Timesheets</p>
                                    </a>
                                </li>

                               

                                

                            </ul>
                        </li>
                        <!--Timesheets-->



                        <!--Fleet Mgt-->

                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl(['vehiclerequisition','fuel','work-ticket','repair-requisition','taxie'])?'menu-open':'' ?>">
                            <a href="#" title="Fleet Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl(['vehiclerequisition','fuel','work-ticket','repair-requisition','taxie'])?'active':'' ?>">
                                <i class="nav-icon fa fa-truck-moving"></i>
                                <p>
                                    Travel Management
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>vehiclerequisition/vehicle-availability" class="nav-link <?= Yii::$app->recruitment->currentaction('vehiclerequisition','vehicle-availability')?'active':'' ?>">
                                        <i class="fa fa-key nav-icon"></i>
                                        <p> Vehicle Availability List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>taxie" class="nav-link <?= Yii::$app->recruitment->currentaction('taxie','index')?'active':'' ?>">
                                        <i class="fa fa-plane nav-icon"></i>
                                        <p> Travel Req. List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>vehiclerequisition" class="nav-link <?= Yii::$app->recruitment->currentaction('vehiclerequisition','index')?'active':'' ?>">
                                        <i class="fa fa-truck-pickup nav-icon"></i>
                                        <p> Booking Req. List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>vehiclerequisition/approved-requisitions" class="nav-link <?= Yii::$app->recruitment->currentaction('vehiclerequisition','approved-requisitions')?'active':'' ?>">
                                        <i class="fa fa-check nav-icon"></i>
                                        <p> Approved Bookings List</p>
                                    </a>
                                </li>

                               <!-- <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>fuel/create" class="nav-link <?= Yii::$app->recruitment->currentaction('fuel','create')?'active':'' ?>">
                                        <i class="fa fa-fire nav-icon"></i>
                                        <p> New Fuel Req.</p>
                                    </a>
                                </li>-->

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>fuel" class="nav-link <?= Yii::$app->recruitment->currentaction('fuel','index')?'active':'' ?>">
                                        <i class=" fa fa-dumpster-fire nav-icon"></i>
                                        <p> Fuel Req. List</p>
                                    </a>
                                </li>


                                <!--<li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>work-ticket/create" class="nav-link <?= Yii::$app->recruitment->currentaction('work-ticket','create')?'active':'' ?>">
                                        <i class="fa fa-ticket-alt nav-icon"></i>
                                        <p> New Work Ticket</p>
                                    </a>
                                </li>-->

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>work-ticket" class="nav-link <?= Yii::$app->recruitment->currentaction('work-ticket','index')?'active':'' ?>">
                                        <i class=" fa fa-ticket-alt nav-icon"></i>
                                        <p> Work Ticket List</p>
                                    </a>
                                </li>


                                <!--<li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>repair-requisition/create" class="nav-link <?= Yii::$app->recruitment->currentaction('repair-requisition','create')?'active':'' ?>">
                                        <i class="fa fa-wrench nav-icon"></i>
                                        <p> New Repair Requisition</p>
                                    </a>
                                </li>-->

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>repair-requisition" class="nav-link <?= Yii::$app->recruitment->currentaction('repair-requisition','index')?'active':'' ?>">
                                        <i class=" fa fa-wrench nav-icon"></i>
                                        <p> Repair Req. List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>repair-requisition/monitoring" class="nav-link <?= Yii::$app->recruitment->currentaction('repair-requisition','monitoring')?'active':'' ?>">
                                        <i class=" fa fa-wrench nav-icon"></i>
                                        <p> Repair Status Monitoring</p>
                                    </a>
                                </li>


                            </ul>

                        </li>


                        <!--/Fleet Mgt--> 

                        <!---Store Requisition--->


                        
                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl(['storerequisition'])?'menu-open':'' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl('storerequisition')?'active':'' ?>">
                                <i class="nav-icon fa fa-store"></i>
                                <p>
                                    Store Requisition
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                            <!-- Open -->
                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>storerequisition" class="nav-link <?= Yii::$app->recruitment->currentaction('storerequisition','index')?'active':'' ?>">
                                        <i class="fa fa-store-alt nav-icon"></i>
                                        <p> Store Req. List</p>
                                    </a>
                                </li>

                                <!-- Pending -->

                               <!-- <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>storerequisition/pending" class="nav-link <?= Yii::$app->recruitment->currentaction('storerequisition','pending')?'active':'' ?>">
                                        <i class="fa fa-store-alt nav-icon"></i>
                                        <p> Store Req. Pending List</p>
                                    </a>
                                </li>-->

                                <!-- Approved -->

                                <!--<li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>storerequisition/approved" class="nav-link <?= Yii::$app->recruitment->currentaction('storerequisition','approved')?'active':'' ?>">
                                        <i class="fa fa-store-alt nav-icon"></i>
                                        <p> Store Req. Approved List</p>
                                    </a>
                                </li>-->

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>storerequisition/posted" class="nav-link <?= Yii::$app->recruitment->currentaction('storerequisition','posted')?'active':'' ?>">
                                        <i class="fa fa-store-alt nav-icon"></i>
                                        <p> Posted Store Req. List</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                         


                        <!---/Store Requisition--->
                        
                        
                        <!--Procurement-->

                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl(['purchase-requisition','rfq'])?'menu-open':'' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl(['purchase-requisition','rfq'])?'active':'' ?>">
                                <i class="nav-icon fa fa-truck-loading"></i>
                                <p>
                                    Procurement
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                          



                            <!--Purchase Requisition -->


                            <ul class="nav nav-treeview">

                            <!-- Open Requests -->
                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>purchase-requisition" class="nav-link <?= Yii::$app->recruitment->currentaction('purchase-requisition','index')?'active':'' ?>">
                                        <i class="fa fa-truck-loading nav-icon"></i>
                                        <p> Purchase Req. List</p>
                                    </a>
                                </li>

                                <!-- Pending List -->

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>purchase-requisition/pending" class="nav-link <?= Yii::$app->recruitment->currentaction('purchase-requisition','pending')?'active':'' ?>">
                                        <i class="fa fa-truck-loading nav-icon"></i>
                                        <p> Purchase Req. Pending List</p>
                                    </a>
                                </li>

                                <!-- Approved List -->

                                <!--<li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>purchase-requisition/approved" class="nav-link <?= Yii::$app->recruitment->currentaction('purchase-requisition','approved')?'active':'' ?>">
                                        <i class="fa fa-truck-loading nav-icon"></i>
                                        <p> Purchase Req. Approved List</p>
                                    </a>
                                </li>-->

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>purchase-requisition/initiated" class="nav-link <?= Yii::$app->recruitment->currentaction('purchase-requisition','initiated')?'active':'' ?>">
                                        <i class="fa fa-truck-loading nav-icon"></i>
                                        <p> Procurement Initiated List</p>
                                    </a>
                                </li>


                            </ul>


                            <!-- RFQ Comitte Evaluation -->


                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>rfq" class="nav-link <?= Yii::$app->recruitment->currentaction('rfq','index')?'active':'' ?>">
                                        <i class="fa fa-truck-loading nav-icon"></i>
                                        <p> RFQ Evaluation</p>
                                    </a>
                                </li>


                            </ul>



                        </li>


                        <!--/Procurement-->

                        




                      

                    





                        <!-- power Bi -->

                        <?php if( 1==0 ) : ?>

                            <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('powerbi')?'menu-open':'' ?>">
                                <a href="#" class="nav-link <?= Yii::$app->recruitment->currentCtrl('powerbi')?'active':'' ?>" title="Power BI Reports">
                                    <i class="nav-icon fa fa-chart-bar" ></i>
                                    <p>
                                        Power Bi Reports
                                        <i class="fas fa-angle-left right"></i>
                                        <!--<span class="badge badge-info right">6</span>-->
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">

                                    <li class="nav-item">
                                        <a href="<?= $absoluteUrl ?>powerbi" class="nav-link <?= Yii::$app->recruitment->currentaction('powerbi','index')?'active':'' ?>">
                                            <i class="fa fa-chart-line nav-icon"></i>
                                            <p>Bi Reports </p>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                        <?php endif;  ?>






                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"></h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <!--<li class="breadcrumb-item"><a href="site">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard v1</li>-->
                                <?=
                                Breadcrumbs::widget([
                                'itemTemplate' => "<li class=\"breadcrumb-item\"><i>{link}</i></li>\n", // template for all links
                                'homeLink' => [
                                'label' => Yii::t('yii', 'Home'),
                                'url' => Yii::$app->homeUrl,
                                ],
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                ])
                                ?>
                            </ol>

                        </div><!-- /.col-6 bread ish -->

                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <?= $content ?>
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->

        </div>
        <!-- /.content-wrapper -->


        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; <?= Yii::$app->params['generalTitle'] ?>  <?= date('Y') ?>   <a href="#"> <?= strtoupper(Yii::$app->params['ClientCompany'])?></a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b><?= Yii::signature() ?></b>
            </div>
        </footer>


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-light">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->




    </div>

</body>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();

/*function currentCtrl($ctrl){
    $controller = Yii::$app->controller->id;

    if(is_array($ctrl)){
        if(in_array($controller,$ctrl)){
            return true;
        }
    }
    if($controller == $ctrl ){
        return true;
    }else{
        return false;
    }
}*/

/*function currentaction($ctrl,$actn){//modify it to accept an array of controllers as an argument--> later please
    $controller = Yii::$app->controller->id;
    $action = Yii::$app->controller->action->id;
    if($controller == $ctrl && $action == $actn){
        return true;
    }else{
        return false;
    }
}*/

?>
