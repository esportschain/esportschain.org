<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>PUBG Stats</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0,user-scalable=0">
        <meta name="format-detection" content="telephone=no" />
        <link rel="stylesheet" href="/css/app/base.css">
        <link rel="stylesheet" href="/css/app/pubg/pubg_data_all.css">
    </head>

    <body>
        <div id="test"></div>
        <div class="container">
            <!-- head start -->
            <div class="header pad">
                <img src="<?php echo $account['icon'];?>" class="he-img">
                <div class="he-text">
                    <p><?php echo $account['accountname'];?></p>
                    <span class="select-region">
                        <?php foreach($data as $k => $region):?>
                        <a href="javascript:;" data-region="<?php echo $k;?>"><?php echo $region['name'];?></a>
                        <?php endforeach;?>
                    </span>
                </div>
                <!-- <div class="header-ri">
                    <a href="javascript:;" class="select-season"><span></span><i></i></a>
                </div> -->
            </div>
            <!-- head end -->

            <div class="box-parent">

                <div class="data-all">
                    <div id="match-stats"></div>
                    <div class="nonedata" style="display: none;">
                        <i></i>
                        <p>No match data was obtained.</p>
                    </div>
                </div>
                
            </div>
        </div>
        <script type="text/html" id="match_stats_tpl">
            
            <div class="box">
                <h3 class="pubg-title">
                    <p><span></span><em>Recent Rank</em></p>
                </h3>
                <div class="datamore">
                    <ul class="dm-top">
                        {{each stats.queues as queue}}
                        <li class="{{if queue.queue_size == 1}}one{{else if queue.queue_size == 2}}double{{else if queue.queue_size == 4}}four{{else}}five{{/if}}">
                            <div class="dmt-top">
                                <em>{{queue.num}} Match</em>
                                <span>
                                    <em class="{{if queue.change>0}}font-green{{else}}font-red{{/if}}">{{queue.changeabs}}</em>
                                    {{if queue.change}}<i class="{{if queue.change>0}}upge{{else}}downre{{/if}}"></i>{{/if}}
                                </span> 
                            </div>
                            <p class="cho-le">
                                <span class="{{if queue.queue_size==1}}font-green{{else if queue.queue_size==2}}font-orange{{else if queue.queue_size == 4}}font-purple{{else}}font-purple{{/if}}">{{if queue.queue_size==1}}Solo{{else if queue.queue_size==2}}Duo{{else if queue.queue_size==4}}Squad{{else}}Warmode{{/if}}</span>
                            </p>
                        </li>
                        {{/each}}
                    </ul>
                    <div class="dm-bot">
                        <div class="dm-bot-le">
                            <p>{{stats.avg_rank}}</p>
                            <span>avg rank</span>
                        </div>
                        <ul class="dm-bot-list">
                            {{each stats.match_list as rank}}
                            <li>
                                <em{{if rank == 1}} class="bg-orange"{{else if rank <= 10}} class="bg-blue"{{/if}}>{{rank}}</em>
                            </li>
                            {{/each}}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="box">
                <h3 class="pubg-title">
                    <p><span></span><em>Recent Matches</em></p>
                </h3>
                <ul class="newmatch" id="match-list">
                </ul>
            </div>
        </script>
        <script type="text/html" id="match_list_tpl">
            {{each list as match}}
            <li class="nm-list">
                <a href="/app.php?d=App&c=Pubg&m=matchDetail&cpmatchid={{match.cpmatchid}}&partid={{match.partid}}&nickname={{match.nickname}}&hash_rate={{match.hash_rate}}">
                    <div class="nm-top">
                        <p class="cho-le">
                            <i></i>
                            <span>{{match.creation}} / survive {{match.duration}} / {{match.queue_name}}</span>
                        </p>
                    </div>
                    <ul class="nm-bot">
                        <li>
                            <span><em>#{{match.rank}}</em>/{{match.total_rank}}</span>
                        </li>
                        <li>
                            <span>{{match.kills}}</span>  
                            <p>Kills</p>
                        </li>
                        <li>
                            <span>{{match.damage}}</span>   
                            <p>Damage</p>
                        </li>
                        <li>
                            <span>{{match.move}}</span>   
                            <p>Distance/km</p>
                        </li>
                    </ul>
                    <i class="r-more"></i>
                </a>
            </li>
            {{/each}}
        </script>
        <script src="/js/lib/zepto.min.js"></script>
        <script src="/js/lib/jquery.js"></script>
        <script src="/js/module/app/utils.js"></script>
        <script src="/js/lib/template.js"></script>
        <script src="/js/lib/layer/layer/layer.js"></script>
        <script>
            var regions = JSON.parse('<?php echo json_encode($data)?>');
            var region = '';
            var seasons = [];
            var season = '';
            var accountname = '<?php echo $account['accountname'];?>';
            var page = 1;
            var isEnd = false;
            var loading = false;
            var loadimg = '';


            var htmlinit = function() {
                page = 1;
                isEnd = false;
                loading = false;
                loadimg = '';
                $('#match-stats').empty();
            };


            var init = function(){
                var cHeigh = $(".container").height();
                var wHeigh = $(window).height();
                $(".container").css({
                    minHeight: wHeigh
                });
            };
            init();


            var showMoreAction = function() {
                if(isEnd || loading) {
                    return false;
                }
                loading = true;

                if(page != 1) {
                    loadimg = layer.load(2);
                }
                $.ajax({
                    type:"GET",
                    url: '/app.php?d=App&c=Pubg&m=record&accountname='+accountname+'&server='+region+'&season='+season+'&page=' + page,
                    dataType: 'JSON',
                    success: function(ret){
                        if(ret.ret == 0) {
                            loading = false;
                         
                            if(page == 1) {
                                var html = template('match_stats_tpl', {stats: ret.data.stats});
                                $('#match-stats').html(html);
                                if(ret.data.list.length == 0) {
                                    $('.nonedata').show();
                                }
                            }

                       
                            if(ret.data.list.length != 0) {
                                var listHtml = template('match_list_tpl', {list: ret.data.list});
                                $('#match-list').append(listHtml);
                                page += 1;
                            }

                            if(ret.data.isEnd) {
                                isEnd = true;
                            }
                        }
                        layer.close(loadimg);
                    }
                });
            };

           
            var scrollAction = function(){
                var scrollT = document.body.scrollTop || document.documentElement.scrollTop;
                if($(window).height() + scrollT + 450 >= document.body.offsetHeight) {
                    showMoreAction();
                }
            };
            $(window).on('scroll', scrollAction);

           
            var selectAfterAction = function(type, id) {
                htmlinit();
                if(type == 1) {
                    $('.select-season span').text(regions[region]['seasons'][id]['name']);
                    season = id;
                    showMoreAction();
                }
            }

         
            $('.select-region a').click(function(){
                if($(this).hasClass('aclick')) {
                    return false;
                }
                $(this).addClass('aclick').siblings().removeClass('aclick');

                htmlinit();

                seasons = [];
                region = $(this).attr('data-region');
                $.each(regions[region]['seasons'], function(k, v){
                    seasons.push({"id": k, "name": v['name']});
                });
               
                selectAfterAction(1, seasons[0]['id']);
            }).first().click();

            
            $('.select-season').click(function(){
                Utils.callNativeFun('callSelect', {
                    type: 1,
                    arr: JSON.stringify(seasons)
                });
            });

            $.ajax({
                type: "GET",
                url: '/app.php?d=App&c=Pubg&m=ajaxEnqeue&accountname='+accountname,
                dataType: 'JSON',
                success: function(ret){}
            });
        </script>
    </body>

</html>
