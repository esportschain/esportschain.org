<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>PUBG Stats</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0,user-scalable=0">
        <meta name="format-detection" content="telephone=no" />
        <link rel="stylesheet" href="/css/app/base.css">
        <link rel="stylesheet" href="/css/app/pubg/pubg_data.css">
    </head>

    <body>
        <div class="container">
            <!-- head start -->
            <div class="est-header pad <?php if($match['queue_size'] == 1):?>one<?php elseif($match['queue_size'] == 2):?>double<?php elseif($match['queue_size'] == 4):?>four<?php else:?>war<?php endif;?>">
                <div class="est-left">
                    <div class="cho-le">
                        <i></i>
                        <span class="<?php if($match['queue_size'] == 1):?>font-green<?php elseif($match['queue_size'] == 2):?>font-orange<?php elseif($match['queue_size'] == 4):?>font-purple<?php else:?>font-blue<?php endif;?>">
                            <?php echo $match['queue_name'];?>
                        </span>
                    </div>
                    <p class="est-p"><?php echo $match['creation'];?> / <?php echo $match['duration'];?></p>
                </div>
                <div class="est-mid">
                    <div class="he-ri">
                        <span>#<?php echo $match['rank'];?></span>
                        <em>/<?php echo $match['total_rank'];?></em>
                    </div>
                </div>
                <div class="est-right">
                    <p><?php echo $hash_rate; ?></p>
                    <div class="est-text">
                        <span>HashRate</span>
                        <em></em>
                    </div>
                </div>
            </div>
            <!-- head end -->

            <?php foreach($players as $player):?>
            <div class="box">
                <div class="player">
                    <div class="p-name">
                        <span class="<?php if($match['queue_size'] == 1):?>font-green<?php elseif($match['queue_size'] == 2):?>font-orange<?php elseif($match['queue_size'] == 4):?>font-purple<?php else:?>font-blue<?php endif;?>"><?php echo $player['nickname'];?></span>
                        <?php if($player['rating_delta'] != 0):?>
                        <div class="change">
                            <div class="change-s">
                                <em class="<?php if($player['rating_delta'] > 0):?>font-green<?php else:?>font-red<?php endif;?>"><?php echo $player['rating_delta']?></em>
                                <i class="<?php if($player['rating_delta'] > 0):?>upge<?php else:?>downre<?php endif;?>"></i>
                            </div>
                            <p>Rating Change</p>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
                <div class="play-data">
                    <ul class="fight">
                        <p>
                            <em class="<?php if($match['queue_size'] == 1):?>green1<?php elseif($match['queue_size'] == 2):?>orange1<?php elseif($match['queue_size'] == 4):?>purple1<?php else:?>blue1<?php endif;?>"></em>
                            <span>Combat</span>
                        </p>
                        <li class="fight-list">
                            <span><?php echo $player['kills']?></span>
                            <i>Kill</i>
                            <?php if($player['kills_rank']):?><em>#<?php echo $player['kills_rank']?></em><?php endif;?>
                        </li>
                        <li class="fight-list">
                            <span><?php echo $player['damage']?></span>
                            <i>Damage</i>
                            <?php if($player['damage_rank']):?><em>#<?php echo $player['damage_rank']?></em><?php endif;?>
                        </li>
                        <li class="fight-list">
                            <span><?php echo $player['headshot']?></span>
                            <i>Headshot</i>
                        </li>
                        <li class="fight-list">
                            <span><?php echo $player['assists']?></span>
                            <i>Assists</i>
                            <?php if($player['assists_rank']):?><em>#<?php echo $player['assists_rank']?></em><?php endif;?>
                        </li>
                        <li class="fight-list">
                            <span><?php echo $player['weapon_acquired']?></span>
                            <i>Weapon Acquired</i>
                        </li>
                        <li class="fight-list">
                            <span><?php echo $player['knock_downs']?></span>
                            <i>DBNO</i>
                            <?php if($player['knock_downs_rank']):?><em>#<?php echo $player['knock_downs_rank']?></em><?php endif;?>
                        </li>
                    </ul>
                    <ul class="fight">
                        <p>
                            <em class="<?php if($match['queue_size'] == 1):?>greenrun<?php elseif($match['queue_size'] == 2):?>orangerun<?php elseif($match['queue_size'] == 4):?>purplerun<?php else:?>bluerun<?php endif;?>"></em>
                            <span>Distance</span>
                        </p>
                        <li class="fight-list">
                            <span><?php echo $player['total_distance']?></span>
                            <i>Distance</i>
                        </li>
                        <li class="fight-list">
                            <span><?php echo $player['walk_distance']?></span>
                            <i>Walk</i>
                            <?php if($player['walk_distance_rank']):?><em>#<?php echo $player['walk_distance_rank']?></em><?php endif;?>
                        </li>
                        <li class="fight-list">
                            <span><?php echo $player['ride_distance']?></span>
                            <i>Ride</i>
                        </li>
                    </ul>
                    <ul class="fight">
                        <p>
                            <em class="<?php if($match['queue_size'] == 1):?>greenjia<?php elseif($match['queue_size'] == 2):?>orangejia<?php elseif($match['queue_size'] == 4):?>purplejia<?php else:?>bluejia<?php endif;?>"></em>
                            <span>Survival</span>
                        </p>
                        <li class="fight-list">
                            <span><?php echo $player['revives']?></span>
                            <i>Revives</i>
                        </li>
                        <li class="fight-list">
                            <span><?php echo $player['heals']?></span>
                            <i>Heals</i>
                        </li>
                        <li class="fight-list">
                            <span><?php echo $player['boosts']?></span>
                            <i>Boosts</i>
                        </li>
                    </ul>
                </div>
            </div>
        	<?php endforeach;?>
            <!-- Rank -->
            <?php if($teams):?>
            <div class="box allrank">
                <h3 class="pubg-title">
                    <p><span></span><em>Rank</em></p>
                </h3>

                <ul class="allrank-list<?php if($match['queue_size'] == 1):?><?php elseif($match['queue_size'] == 2):?> bigdouble<?php elseif($match['queue_size'] == 4):?> bigfour<?php else:?> bigfour<?php endif;?>">
                    <li>
                        <span>Rank</span>
                        <span>Player</span>
                        <span>Kill</span>
                        <span>Damage</span>
                        <span>Distance</span>
                    </li>
                    <?php foreach($teams as $index => $team):?>
                    <li<?php if($team['is_self']):?> class="me"<?php endif;?><?php if($index >= 5):?> style="display: none;"<?php endif;?>>
                        <span><?php echo $team['rank'];?></span>
                        <span>
                            <?php foreach($team['players'] as $k => $pl):?>
                                <?php if($k != 0):?>,<?php endif;?>
                                <i><?php echo $pl?></i>
                            <?php endforeach;?>
                        </span>
                        <span><?php echo $team['kills'];?></span>
                        <span><?php echo $team['damage'];?></span>
                        <span><?php echo $team['walk_distance'];?></span>
                    </li>
                    <?php endforeach;?>
                    <li class="last-more">
                        <i></i>
                        <em>Load More</em>
                    </li>
                </ul>
            </div>
            <?php endif;?>
        </div>
        <script src="/js/lib/jquery.js"></script>
        <script>
            var cHeigh = $(".container").height();
            var wHeigh = $(window).height();
            $(".container").css({
                minHeight: wHeigh
            });

            $('.last-more').click(function(){
                $(this).hide().siblings().show();
            });
        </script>
    </body>

</html>