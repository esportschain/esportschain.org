<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Other Login</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0,user-scalable=0">
        <meta name="format-detection" content="telephone=no" />
        <link rel="stylesheet" href="/css/app/base.css">
    </head>

    <body>
        <div id="content-test"></div>
        <script src="/js/lib/zepto.min.js"></script>
        <script src="/js/lib/jquery.js"></script>
        <script src="/js/module/app/utils.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                Utils.callNativeFun('callThirdLogin', {
                    rst: '<?php echo json_encode($rst);?>'
                });
            });
        </script>
    </body>
</html>

