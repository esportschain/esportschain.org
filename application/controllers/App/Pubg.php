<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* pubg data
*/
class Pubg extends EST_Controller
{

    /**
     * [detail account detail]
     * @DateTime 2018-06-08
     * @return   [type]     [description]
     */
    public function detail()
    {

        if(!$this->beforeAction()) {
            return $this->showMessage('Unauthorized', [], 401, 1);
        }

        if(!$this->uid) {
            return $this->showMessage('please log in first', [], 400, 1);
        }

        $accountname = trim($this->input->get('accountname'));
        $this->load->model('member/gameUserAccount');
        $account = $this->gameUserAccount->getByUidAndName($this->uid, 9, $accountname);
        if(empty($account)) {
            return $this->showMessage('account does not exist', [], 400, 2);
        }
        $account['icon'] = $this->getAvatar($this->uid);

        $fields = 'id,season,mode,server,queue_size';
        $this->load->model('pubg/playerStats');
        $statsList = $this->playerStats->listByAccountname($accountname, $fields);
        if(empty($statsList)) {
            return $this->showMessage('No records', [], 400, 3);
        }

        array_multisort(array_column($statsList, 'server'), SORT_ASC, array_column($statsList, 'season'), SORT_DESC, $statsList);

        $data = [];
        $this->load->model('pubg/playerData');
        $regions = $this->playerData->getServerList();
        foreach ($statsList as $key => $value) {
            if($value['queue_size'] != 0 || empty($value['server'])) {
                continue;
            }
            $server = $value['server'];
            $season = $value['season'];

            empty($data[$server]) && $data[$server] = ['name' => $regions[$server], 'seasons' => []];
            empty($data[$server]['seasons'][$season]) && $data[$server]['seasons'][$season] = ['name' => $this->playerData->formatSeason($season)];
        }
        ksort($data);
        foreach ($data as &$seasons) {
            krsort($seasons['seasons']);
        }

        $this->load->view('app/pubg/report', [
            'data'    => $data,
            'account' => $account,
        ]);
    }

    /**
     * [record match record]
     * @DateTime 2018-06-08
     * @return   [type]     [description]
     */
    public function record()
    {

        $server = trim($this->input->get('server'));
        $season = trim($this->input->get('season'));
        $accountname = trim($this->input->get('accountname'));
        $page = intval($this->input->get('page'));
        empty($page) && $page = 1;

        $result = array('list' => array(), 'isEnd' => true, 'stats' => []);

        $this->load->model('pubg/playerData');
        $filters = [
            'server'   => $server,
            'season'   => $season,
            'nickname' => $accountname,
        ];
        $total_rows = $this->playerData->countByFilter($filters);
        $pageOptions = array('perPage' => 20, 'currentPage' => $page, 'totalItems' => $total_rows);
        $fields = 'match_id,server,account_id,nickname,start_time,time_survived,rank,total_rank,kills,damage_dealt,walk_distance,ride_distance,queue_size,rating_delta,participant_id,calculation_force';
        $pDataList = $this->playerData->listByFilter($filters, $pageOptions, $fields, 'start_time DESC');
        if(empty($pDataList)) {
            return $this->showMessage('success', $result);
        }

        $ratings = [];
        $list = [];
        foreach ($pDataList as $pd) {
            $tmp = [
                'cpmatchid'  => $pd['match_id'],
                'platformid' => $pd['server'],
                'partid'     => $pd['participant_id'],
                'nickname'   => $pd['nickname'],
                'queue_size' => $pd['queue_size'],
                'creation'   => $this->utc2Local($pd['start_time'], 'Y.m.d'),
                'duration'   => floor($pd['time_survived']/60).':'.sprintf('%02d', $pd['time_survived']%60),
                'rank'       => $pd['rank'],
                'total_rank' => $pd['total_rank'],
                'kills'        => $pd['kills'],
                'damage'       => $pd['damage_dealt'],
                'move'         => sprintf('%.2f', ($pd['walk_distance'] + $pd['ride_distance'])/1000),
                'hash_rate'    => sprintf('%d', $pd['calculation_force']),
            ];
            $list[] = $tmp;
            $ratings[$pd['queue_size']][] = $pd['rating_delta'];
        }

        if($page == 1) {
            $queues = [];
            $ranks = array_column($list, 'rank');
            $qs = [1, 2, 4];
            foreach ($qs as $v) {
                if(empty($ratings[$v])) {
                    $queues[] = [
                        'queue_size' => $v,
                        'num'    => 0,
                        'change' => 0,
                        'changeabs' => 0,
                    ];
                }else {
                    $queues[] = [
                        'queue_size' => $v,
                        'num'    => count($ratings[$v]),
                        'change' => round(array_sum($ratings[$v])),
                        'changeabs' => abs(round(array_sum($ratings[$v]))),
                    ];
                }
            }
            $result['stats'] = [
                'match_list' => $ranks,
                'avg_rank'   => sprintf('%.1f', array_sum($ranks) / count($list)),
                'queues'     => $queues,
            ];
        }

        $result['list'] = $list;
        $result['isEnd'] = $pageOptions['perPage'] * $pageOptions['currentPage'] >= $pageOptions['totalItems'] ? true : false;
        return $this->showMessage('success', $result);
    }

    /**
     * [matchDetail game detail]
     * @DateTime 2018-06-08
     * @return   [type]     [description]
     */
    public function matchDetail()
    {

        $cpmatchid = $this->input->get('cpmatchid');
        $partid = strval($this->input->get('partid'));
        $nickname = strval($this->input->get('nickname'));
        $hash_rate = strval($this->input->get('hash_rate'));
        if(empty($partid) || empty($cpmatchid)) {
            return $this->showMessage('Parameter error', [], 400, 1);
        }

        $this->load->model('pubg/matchDetail');
        $match = $this->matchDetail->getMatch($cpmatchid, 'base_stats_json');
        if(empty($match)) {
            return $this->showMessage('No detailed data yet', [], 400, 2);
        }

        $teams = [];

        $statscache = json_decode($match['base_stats_json'], true);
    
        $statscache = $this->matchDetail->structureMatch($statscache);


        foreach ($statscache['teams'] as $team) {
            $tmp = ['kills' => 0, 'damage' => 0, 'walk_distance' => 0, 'players' => []];
            $partids = [];
            foreach ($team['participants'] as $partner) {
                $tmp['kills'] += $partner['kills'];
                $tmp['damage'] += $partner['damage_dealt'];
                $tmp['walk_distance'] += $partner['walk_distance'];
                $tmp['players'][] = $partner['nickname'];
                $partids[$partner['_id']] = $partner['nickname'];

                $order['kills'][] = $partner['kills'];
                $order['assists'][] = $partner['assists'];
                $order['damage_dealt'][] = $partner['damage_dealt'];
                $order['knock_downs'][] = $partner['knock_downs'];
                $order['walk_distance'][] = round($partner['walk_distance']);
            }

            !empty($partids[$partid]) && $self_team_playerids = $partids;
            $tmp['is_self'] = !empty($partids[$partid]) ? 1 : 0;
            $tmp['walk_distance'] = sprintf('%.2f', $tmp['walk_distance'] / 1000).' km';
            $tmp['damage'] = sprintf('%d', $tmp['damage']);
            $tmp['rank'] = $team['rank'];
            $teams[] = $tmp;
        }
        array_multisort(array_column($teams, 'rank'), SORT_ASC, $teams);
        rsort($order['kills']);
        rsort($order['assists']);
        rsort($order['damage_dealt']);
        rsort($order['knock_downs']);
        rsort($order['walk_distance']);

        empty($self_team_playerids) && $self_team_playerids = [$partid => $nickname];
        $players = [];
        $data = [];
        foreach ($self_team_playerids as $k => $nickname) {
            if(empty($statscache['players'][$k])) {
                continue;
            }
            $pd = $statscache['players'][$k];

            $isself = $k == $partid ? 1 : 0;
            if($isself) {
                $data = [
                    'creation'   => $this->utc2Local($pd['start_time'], 'Y.m.d'),
                    'queue_size' => $pd['queue_size'],
                    'total_rank' => $pd['total_rank'],
                    'duration'   => floor($pd['time_survived']/60).':'.sprintf('%02d', $pd['time_survived']%60),
                    'rank'       => $pd['rank'],
                ];
            }
            $players[] = [
                'is_self'      => $isself,
                'nickname'     => $nickname,
                'rating_delta' => round($pd['rating_delta']),
                'kills'        => $pd['kills'],
                'damage'       => $pd['damage_dealt'],
                'headshot'     => $pd['headshot_kills'],
                'assists'      => $pd['assists'],
                'weapon_acquired' => $pd['weapon_acquired'],
                'knock_downs'     => $pd['knock_downs'],
                'total_distance'  => sprintf('%.1f', ($pd['walk_distance'] + $pd['ride_distance'])/1000) . 'km',
                'walk_distance'   => sprintf('%.1f', $pd['walk_distance']/1000) . 'km',
                'ride_distance'   => sprintf('%.1f', $pd['ride_distance']/1000) . 'km',
                'revives'         => $pd['revives'],
                'heals'           => $pd['heals'],
                'boosts'          => $pd['boosts'],
                'kills_rank'      => !empty($order['kills']) ? intval(array_search($pd['kills'], $order['kills'])) + 1 : '',
                'assists_rank'    => !empty($order['assists']) ? intval(array_search($pd['assists'], $order['assists'])) + 1 : '',
                'damage_rank'     => !empty($order['damage_dealt']) ? intval(array_search($pd['damage_dealt'], $order['damage_dealt'])) + 1 : '',
                'knock_downs_rank'   => !empty($order['knock_downs']) ? intval(array_search($pd['knock_downs'], $order['knock_downs'])) + 1 : '',
                'walk_distance_rank' => !empty($order['walk_distance']) ? intval(array_search(round($pd['walk_distance']), $order['walk_distance'])) + 1 : '',
            ];
        }

        $rst = [
            'match'   => $data,
            'players' => $players,
            'teams'   => $teams,
            'hash_rate' => $hash_rate,
        ];

        $this->load->view('app/pubg/match_detail', $rst);
    }

    /**
     * [ajaxEnqeue pull game data queue]
     * @Author   hq
     * @DateTime 2018-07-12
     * @return   [type]     [description]
     */
    public function ajaxEnqeue()
    {
        $accountname = $this->input->get('accountname');
        if(empty($accountname)) {
            return $this->showMessage('Please fill in the game nickname', [], 400, 1);
        }

        $this->load->model('member/gameUserAccount');
        $this->gameUserAccount->pubgQueue($accountname);

        return $this->showMessage('success');
    }
}