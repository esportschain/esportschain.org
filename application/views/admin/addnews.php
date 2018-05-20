
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>Metronic | Form Stuff - Form Layouts</title>
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
    <link rel="stylesheet" type="text/css" href="/assets/admin/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="/assets/admin/chosen-bootstrap/chosen/chosen.css" />
    <link rel="stylesheet" type="text/css" href="/assets/admin/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <link rel="stylesheet" type="text/css" href="/assets/admin/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="/assets/admin/bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="/assets/admin/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" href="/assets/admin/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" />
    <link rel="stylesheet" type="text/css" href="/assets/admin/bootstrap-daterangepicker/daterangepicker.css" />
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
            <!-- BEGIN TOP NAVIGATION MENU -->
            <ul class="nav pull-right">
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <li class="dropdown user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="username">wanplus_esc</span>
                        <i class="icon-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="index.php?c=admin&m=loginout"><i class="icon-key"></i> Log Out</a></li>
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
            <li class="start">
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
                <ul class=" sub">
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
                    <h3 class="page-title">
                        Form Layouts
                        <small>form layouts and more</small>
                    </h3>
                    <ul class="breadcrumb">
                        <li>
                            <i class="icon-home"></i>
                            <a href="index.html">Home</a>
                            <span class="icon-angle-right"></span>
                        </li>
                        <li>
                            <a href="#">Form Stuff</a>
                            <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">Form Layouts</a></li>
                    </ul>
                </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    <div class="portlet box blue tabbable">
                        <div class="portlet-title">
                            <h4>
                                <i class="icon-reorder"></i>
                                <span class="hidden-480">General Layouts</span>
                                &nbsp;
                            </h4>
                        </div>
                        <div class="portlet-body form">
                            <div class="tabbable portlet-tabs">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#portlet_tab1" data-toggle="tab">Default</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="portlet_tab1">
                                        <!-- BEGIN FORM-->
                                        <form action="index.php?c=admin&m=addnews" class="form-horizontal" method="post">
                                            <input type="hidden" name="<?php echo $csrf_name;?>" value="<?php echo $csrf_hash;?>" />
                                            <div class="control-group">
                                                <label class="control-label">中文标题</label>
                                                <div class="controls">
                                                    <input type="text" name='cn_title' placeholder="请填写中文标题" class="m-wrap large" value=""/>
                                                    <span class="help-inline">最少填写一组对应的标题和链接</span>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">英文标题</label>
                                                <div class="controls">
                                                    <input type="text" name='en_title' placeholder="请填写英文标题" class="m-wrap large" value=""/>
                                                    <span class="help-inline">最少填写一组对应的标题和链接</span>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">韩文标题</label>
                                                <div class="controls">
                                                    <input type="text" name='kr_title' placeholder="请填写韩文标题" class="m-wrap large" value=""/>
                                                    <span class="help-inline">最少填写一组对应的标题和链接</span>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">中文新闻链接</label>
                                                <div class="controls">
                                                    <input type="text" name='cn_url' placeholder="请填写链接" class="m-wrap large" value=""/>
                                                    <span class="help-inline">必须为完整url，例：http://www.baidu.com</span>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">英文文新闻链接</label>
                                                <div class="controls">
                                                    <input type="text" name='en_url' placeholder="请填写链接" class="m-wrap large" value=""/>
                                                    <span class="help-inline">必须为完整url，例：http://www.baidu.com</span>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">韩文新闻链接</label>
                                                <div class="controls">
                                                    <input type="text" name='kr_url' placeholder="请填写链接" class="m-wrap large" value=""/>
                                                    <span class="help-inline">必须为完整url，例：http://www.baidu.com</span>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">排序</label>
                                                <div class="controls">
                                                    <input type="text" name='sort' placeholder="请填写排序" class="m-wrap large" value=""/>
                                                    <span class="help-inline">整数，数值越大排序越靠前</span>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">发布时间</label>
                                                <div class="controls">
                                                    <input type="text" name='publish_time' placeholder="请填写发布时间" class="m-wrap large" value=""/>
                                                    <span class="help-inline">首页上展示的时间,此处录入北京时间,格式为：2018-01-01 10:10:10</span>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">是否删除</label>
                                                <div class="controls">
                                                    <select class="small m-wrap" name="is_del" tabindex="1">
                                                        <option value="1" >删除</option>
                                                        <option value="0" selected>未删除</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <button type="submit" class="btn blue">Add</button>
                                            </div>
                                        </form>
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END SAMPLE FORM PORTLET-->
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
<script type="text/javascript" src="/assets/admin/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="/assets/admin/uniform/jquery.uniform.min.js"></script>
<script type="text/javascript" src="/assets/admin/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="/assets/admin/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="/assets/admin/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
<script type="text/javascript" src="/assets/admin/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/assets/admin/bootstrap-daterangepicker/date.js"></script>
<script type="text/javascript" src="/assets/admin/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="/assets/admin/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="/assets/admin/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script src="/assets/admin/js/app.js"></script>
<script>
    jQuery(document).ready(function() {

        // to fix chosen dropdown width in inactive hidden tab content
        $('#advance_form_hor').on('shown', function (e) {
            App.initChosenSelect('#chosen_category');
        });

        // initiate layout and plugins
        App.init();
    });
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>