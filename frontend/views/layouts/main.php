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

                        <?= (!Yii::$app->user->isGuest)? Html::a('<i class="fas fa-file-pdf "></i> ESS Manuals','../essfile/index',['class'=> 'dropdown-item']): ''; ?>



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
                <span class="brand-text font-weight-light"><?= Yii::$app->params['generalTitle']?></span>
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

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leave/create" class="nav-link <?= Yii::$app->recruitment->currentaction('leave','create')?'active':'' ?> ">
                                        <i class="fa fa-running nav-icon"></i>
                                        <p>New Leave Application</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leave/" class="nav-link <?= Yii::$app->recruitment->currentaction('leave','index')?'active':'' ?>">
                                        <i class="fa fa-door-open nav-icon"></i>
                                        <p>Leave List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leavestatement/index" class="nav-link <?= Yii::$app->recruitment->currentaction('leavestatement','index')?'active':'' ?>">
                                        <i class="fa fa-file-pdf nav-icon"></i>
                                        <p>Leave Statement  Report</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leaverecall/create/?create=1" class="nav-link <?= Yii::$app->recruitment->currentaction('leaverecall','create')?'active':'' ?>">
                                        <i class="fa fa-recycle nav-icon"></i>
                                        <p>Recall Leave</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leaverecall/index" class="nav-link <?= Yii::$app->recruitment->currentaction('leaverecall',['index','view'])?'active':'' ?>">
                                        <i class="fa fa-list nav-icon"></i>
                                        <p>Recall Leave List</p>
                                    </a>
                                </li>

                                <!-- Leave Reimbursement -->

                                 <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leave-reimburse/index" class="nav-link <?= Yii::$app->recruitment->currentaction('leave-reimburse',['index','view'])?'active':'' ?>">
                                        <i class="fa fa-list nav-icon"></i>
                                        <p>Leave Reimbursement</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leaveplan/create" class="nav-link <?= Yii::$app->recruitment->currentaction('leaveplan','create')?'active':'' ?>">
                                        <i class="fa fa-directions nav-icon"></i>
                                        <p>New Leave Plan</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leaveplan/index" class="nav-link <?= Yii::$app->recruitment->currentaction('leaveplan','index')?'active':'' ?>">
                                        <i class="fa fa-list nav-icon"></i>
                                        <p>Leave Plan List</p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                       


                       



                        







                       

                        <!--Payroll reports -->
                         <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl(['payslip','p9'])?'menu-open':'' ?>">
                            <a href="#" class="nav-link <?= Yii::$app->recruitment->currentCtrl(['payslip','p9'])?'active':'' ?>">
                                <i class="nav-icon fa fa-file-invoice-dollar"></i>
                                <p>
                                    HR Reports
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



                        

                        




                      

                    





                        <!-- power Bi -->

                        <?php if( 1==1 ) : ?>

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
            <strong>Copyright &copy; <?= Yii::$app->params['generalTitle'] ?> -   2014 - <?= date('Y') ?>   <a href="#"> <?= strtoupper(Yii::$app->params['ClientCompany'])?></a>.</strong>
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
