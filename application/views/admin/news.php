
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>Metronic | Data Tables - Basic Tables</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="/assets/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/admin/css/metro.css" rel="stylesheet" />
    <link href="/assets/admin/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="/assets/admin/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="/assets/admin/css/style.css" rel="stylesheet" />
    <link href="/assets/admin/css/style_responsive.css" rel="stylesheet" />
    <link href="/assets/admin/css/style_default.css" rel="stylesheet" id="style_color" />
    <link href="/assets/admin/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="/assets/admin/uniform/css/uniform.default.css" />
    <link rel="stylesheet" type="text/css" href="/assets/admin/chosen-bootstrap/chosen/chosen.css" />
    <link rel="stylesheet" href="/assets/admin/data-tables/DT_bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/assets/admin/uniform/css/uniform.default.css" />
    <link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse navbar-fixed-top">
    <!-- BEGIN TOP NAVIGATION BAR -->
    <div class="navbar-inner">
        <div class="container-fluid">
            <!-- BEGIN LOGO -->
            <a class="brand" href="index.html">
                <img src="/assets/admin/img/logo.png" alt="logo" />
            </a>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
                <img src="/assets/admin/img/menu-toggler.png" alt="" />
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <ul class="nav pull-right">
                <!-- BEGIN NOTIFICATION DROPDOWN -->
                <li class="dropdown" id="header_notification_bar">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-warning-sign"></i>
                        <span class="badge">6</span>
                    </a>
                    <ul class="dropdown-menu extended notification">
                        <li>
                            <p>You have 14 new notifications</p>
                        </li>
                        <li>
                            <a href="#">
                                <span class="label label-success"><i class="icon-plus"></i></span>
                                New user registered.
                                <span class="time">Just now</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="label label-important"><i class="icon-bolt"></i></span>
                                Server #12 overloaded.
                                <span class="time">15 mins</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="label label-warning"><i class="icon-bell"></i></span>
                                Server #2 not respoding.
                                <span class="time">22 mins</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="label label-info"><i class="icon-bullhorn"></i></span>
                                Application error.
                                <span class="time">40 mins</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="label label-important"><i class="icon-bolt"></i></span>
                                Database overloaded 68%.
                                <span class="time">2 hrs</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="label label-important"><i class="icon-bolt"></i></span>
                                2 user IP blocked.
                                <span class="time">5 hrs</span>
                            </a>
                        </li>
                        <li class="external">
                            <a href="#">See all notifications <i class="m-icon-swapright"></i></a>
                        </li>
                    </ul>
                </li>
                <!-- END NOTIFICATION DROPDOWN -->
                <!-- BEGIN INBOX DROPDOWN -->
                <li class="dropdown" id="header_inbox_bar">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-envelope-alt"></i>
                        <span class="badge">5</span>
                    </a>
                    <ul class="dropdown-menu extended inbox">
                        <li>
                            <p>You have 12 new messages</p>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img src=".//assets/admin/img/avatar2.jpg" alt="" /></span>
                                <span class="subject">
								<span class="from">Lisa Wong</span>
								<span class="time">Just Now</span>
								</span>
                                <span class="message">
								Vivamus sed auctor nibh congue nibh. auctor nibh
								auctor nibh...
								</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img src=".//assets/admin/img/avatar3.jpg" alt="" /></span>
                                <span class="subject">
								<span class="from">Richard Doe</span>
								<span class="time">16 mins</span>
								</span>
                                <span class="message">
								Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh
								auctor nibh...
								</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img src=".//assets/admin/img/avatar1.jpg" alt="" /></span>
                                <span class="subject">
								<span class="from">Bob Nilson</span>
								<span class="time">2 hrs</span>
								</span>
                                <span class="message">
								Vivamus sed nibh auctor nibh congue nibh. auctor nibh
								auctor nibh...
								</span>
                            </a>
                        </li>
                        <li class="external">
                            <a href="#">See all messages <i class="m-icon-swapright"></i></a>
                        </li>
                    </ul>
                </li>
                <!-- END INBOX DROPDOWN -->
                <!-- BEGIN TODO DROPDOWN -->
                <li class="dropdown" id="header_task_bar">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-tasks"></i>
                        <span class="badge">5</span>
                    </a>
                    <ul class="dropdown-menu extended tasks">
                        <li>
                            <p>You have 12 pending tasks</p>
                        </li>
                        <li>
                            <a href="#">
								<span class="task">
								<span class="desc">New release v1.2</span>
								<span class="percent">30%</span>
								</span>
                                <span class="progress progress-success ">
								<span style="width: 30%;" class="bar"></span>
								</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
								<span class="task">
								<span class="desc">Application deployment</span>
								<span class="percent">65%</span>
								</span>
                                <span class="progress progress-danger progress-striped active">
								<span style="width: 65%;" class="bar"></span>
								</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
								<span class="task">
								<span class="desc">Mobile app release</span>
								<span class="percent">98%</span>
								</span>
                                <span class="progress progress-success">
								<span style="width: 98%;" class="bar"></span>
								</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
								<span class="task">
								<span class="desc">Database migration</span>
								<span class="percent">10%</span>
								</span>
                                <span class="progress progress-warning progress-striped">
								<span style="width: 10%;" class="bar"></span>
								</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
								<span class="task">
								<span class="desc">Web server upgrade</span>
								<span class="percent">58%</span>
								</span>
                                <span class="progress progress-info">
								<span style="width: 58%;" class="bar"></span>
								</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
								<span class="task">
								<span class="desc">Mobile development</span>
								<span class="percent">85%</span>
								</span>
                                <span class="progress progress-success">
								<span style="width: 85%;" class="bar"></span>
								</span>
                            </a>
                        </li>
                        <li class="external">
                            <a href="#">See all tasks <i class="m-icon-swapright"></i></a>
                        </li>
                    </ul>
                </li>
                <!-- END TODO DROPDOWN -->
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <li class="dropdown user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img alt="" src="/assets/admin/img/avatar1_small.jpg" />
                        <span class="username">Bob Nilson</span>
                        <i class="icon-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="extra_profile.html"><i class="icon-user"></i> My Profile</a></li>
                        <li><a href="calendar.html"><i class="icon-calendar"></i> My Calendar</a></li>
                        <li><a href="#"><i class="icon-tasks"></i> My Tasks</a></li>
                        <li class="divider"></li>
                        <li><a href="login.html"><i class="icon-key"></i> Log Out</a></li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
            </ul>
            <!-- END TOP NAVIGATION MENU -->
        </div>
    </div>
    <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<div class="page-container row-fluid">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar nav-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <ul>
            <li>
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler hidden-phone"></div>
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            </li>
            <li>
                <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                <form class="sidebar-search">
                    <div class="input-box">
                        <a href="javascript:;" class="remove"></a>
                        <input type="text" placeholder="Search..." />
                        <input type="button" class="submit" value=" " />
                    </div>
                </form>
                <!-- END RESPONSIVE QUICK SEARCH FORM -->
            </li>
            <li class=" start">
                <a href="/index.php?c=admin&m=index">
                    <i class="icon-home"></i>
                    <span class="title">首页</span>
                </a>
            </li>
            <li class="has-sub active">
                <a href="javascript:;">
                    <i class="icon-bookmark-empty"></i>
                    <span class="title">管理中心</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub">
                    <li ><a href="/index.php?c=admin&m=news">新闻列表</a></li>
                </ul>
            </li>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN PAGE -->
    <div class="page-content">
        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <div id="portlet-config" class="modal hide">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button"></button>
                <h3>portlet Settings</h3>
            </div>
            <div class="modal-body">
                <p>Here will be a configuration form</p>
            </div>
        </div>
        <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <!-- BEGIN PAGE CONTAINER-->
        <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN STYLE CUSTOMIZER -->
                    <div class="color-panel hidden-phone">
                        <div class="color-mode-icons icon-color"></div>
                        <div class="color-mode-icons icon-color-close"></div>
                        <div class="color-mode">
                            <p>THEME COLOR</p>
                            <ul class="inline">
                                <li class="color-black current color-default" data-style="default"></li>
                                <li class="color-blue" data-style="blue"></li>
                                <li class="color-brown" data-style="brown"></li>
                                <li class="color-purple" data-style="purple"></li>
                                <li class="color-white color-light" data-style="light"></li>
                            </ul>
                            <label class="hidden-phone">
                                <input type="checkbox" class="header" checked value="" />
                                <span class="color-mode-label">Fixed Header</span>
                            </label>
                        </div>
                    </div>
                    <!-- END BEGIN STYLE CUSTOMIZER -->
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title">
                        Basic Tables				<small>basic table samples</small>
                    </h3>
                    <ul class="breadcrumb">
                        <li>
                            <i class="icon-home"></i>
                            <a href="/index.php?c=admin&m=index">首页</a>
                            <i class="icon-angle-right"></i>
                        </li>
                        <li>
                            <a href="/index.php?c=admin&m=news">新闻列表</a>
                        </li>

                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="">
                <div class="">
                    <!-- BEGIN SAMPLE TABLE PORTLET-->
                    <div class="portlet">
                        <div class="portlet-title">
                            <h4><i class="icon-bell"></i>新闻列表</h4>
                            <div class="tools">
                                <a href="index.php?c=admin&m=addnews" class="">添加新闻</a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-advance table-hover">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>中文标题</th>
                                    <th>英文标题</th>
                                    <th>韩文标题</th>
                                    <th>中文新闻链接</th>
                                    <th>英文新闻链接</th>
                                    <th>韩文新闻链接</th>
                                    <th>排序字段</th>
                                    <th>是否删除</th>
                                    <th>发布时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($list as $k => $v): ?>
                                <tr>
                                    <td>
                                        <a href="#"><?php echo $v['id'];?></a>
                                    </td>
                                    <td><?php echo $v['cn_title'];?></td>
                                    <td><?php echo $v['en_title'];?></td>
                                    <td><?php echo $v['kr_title'];?></td>
                                    <td><?php echo $v['cn_url']?></td>
                                    <td><?php echo $v['en_url']?></td>
                                    <td><?php echo $v['kr_url']?></td>
                                    <td><?php echo $v['sort']?></td>
                                    <td><?php if($v['is_del']){echo '已删除';}else{echo '未删除';} ?></td>
                                    <td><?php echo $v['publish_time'];?></td>
                                    <td><a href="index.php?c=admin&m=editnews&newsid=<?php echo $v['id'];?>" class="btn mini purple"> Edit</a></td>
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END SAMPLE TABLE PORTLET-->
                </div>
            </div>
            <!-- END PAGE CONTENT-->
        </div>
        <!-- END PAGE CONTAINER-->
    </div>
    <!-- END PAGE -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="footer">
    2013 &copy; Metronic by keenthemes.
    <div class="span pull-right">
        <span class="go-top"><i class="icon-angle-up"></i></span>
    </div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS -->
<!-- Load javascripts at bottom, this will reduce page load time -->
<script src="/assets/admin/js/jquery-1.8.3.min.js"></script>
<script src="/assets/admin/breakpoints/breakpoints.js"></script>
<script src="/assets/admin/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/admin/js/jquery.blockui.js"></script>
<script src="/assets/admin/js/jquery.cookie.js"></script>
<!-- ie8 fixes -->
<!--[if lt IE 9]>
<script src="/assets/admin/js/excanvas.js"></script>
<script src="/assets/admin/js/respond.js"></script>
<![endif]-->
<script type="text/javascript" src="/assets/admin/uniform/jquery.uniform.min.js"></script>
<script type="text/javascript" src="/assets/admin/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/assets/admin/data-tables/DT_bootstrap.js"></script>
<script src="/assets/admin/js/app.js"></script>
<script>
    jQuery(document).ready(function() {
        // initiate layout and plugins
        App.init();
    });
</script>
</body>
<!-- END BODY -->
</html>
