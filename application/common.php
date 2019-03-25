<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function grash_raffle_ticket(){
    set_time_limit(0);
    $config = \think\Config::get('double_ball');
    $issue  = $config['issue']; //期号
    $particular = $config['particular_year']; //年
    $url = $config['url'];
    $suffix = $config['suffix'];

    $curYear = date('y', time());
    if(empty($issue) || empty($particular)){
        //没有期号或年份，从第一期开始获取
        for($particular = 0; $particular <= $curYear; $particular++){
            $issue = 1; //默认从本年第一期开始
            while (true){
                $tmpIssue = create_raffle_format_issue($particular, $issue);
                $val = \app\model\DoubleModel::where('issue', $tmpIssue)->value('id');
                if(empty($val)){
                    $ball = grash_double_curl($url . $tmpIssue . $suffix);
                    if(empty($ball)){
                        break;
                    }

                    $redBall = array($ball[0],$ball[1],$ball[2],$ball[3],$ball[4],$ball[5]);
                    $blueBall = $ball[6];

                    $insertData = [
                        'issue'     => $tmpIssue,
                        'year'      => $particular,
                        'issue_no'  => $issue,
                        'red_ball'  => serialize($redBall),
                        'blue_ball' => $blueBall,
                        'whole'     => implode(' ', $ball)
                    ];

                    \app\model\DoubleModel::create($insertData);

                }
                ++$issue;
            }
        }

        save_config(APP_PATH . 'lottery.php', array('issue' => $issue, 'particular_year' => $curYear));
    }else{
        $curYear = date('y', time());
        while (true){
            $tmpIssue = create_raffle_format_issue($particular, $issue);
            $val = \app\model\DoubleModel::where('issue', $tmpIssue)->value('id');
            if(empty($val)){
                $ball = grash_double_curl($url . $tmpIssue . $suffix);
                if(empty($ball)){
                    //不同年份，初始化期号和年份，继续获取,直到无法得到数据为止
                    if($particular != $curYear){
                        $particular += 1;
                        $issue = 1;
                        continue;
                    }else{
                        break;
                    }
                }

                $redBall = array($ball[0],$ball[1],$ball[2],$ball[3],$ball[4],$ball[5]);
                $blueBall = $ball[6];

                $insertData = [
                    'issue'     => $tmpIssue,
                    'year'      => $particular,
                    'issue_no'  => $issue,
                    'red_ball'  => serialize($redBall),
                    'blue_ball' => $blueBall,
                    'whole'     => implode(' ', $ball)
                ];

                \app\model\DoubleModel::create($insertData);

            }
            ++$issue;
        }

        save_config(APP_PATH . 'lottery.php', array('issue' => $issue, 'particular_year' => $particular));
    }
}

/**
 * 获取彩票期号
 * @param $year
 * @param int $issue
 * @return string
 */
function create_raffle_format_issue($year, $issue = 0){
    if(empty($issue)){  $issue = 1;}
    return substr('0' . $year,-2) . substr('000' . $issue, -3);
}

/**
 * 获取双色球号码
 * @param $url
 * @return array|bool
 */
function grash_double_curl($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_HTTPHEADER, array('Accept-Encoding:gzip,deflate'));
    curl_setopt($ch, CURLOPT_ENCODING,'gzip,deflate');
    //curl_setopt($ch, CURLOPT_FAILONERROR,true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);  //尝试链接时间
    //curl_setopt($ch,CURLOPT_TIMEOUT,6); //链接超时时间

    $html = curl_exec($ch);
    curl_close($ch);

    var_dump($url);die();

    if(!empty($html)){
        $ball = array();
        preg_match_all('/<li[^>]*class="ball_red".*?>.*?<\/li>/ism', $html, $red);
        preg_match('/<li[^>]*class="ball_blue".*?>.*?<\/li>/ism', $html, $blue);

        $tmp = array_merge($red, $blue);
        foreach ($tmp as $item){
            $item = preg_replace('/<(\/?li.*?)>/si',"",$item);
            if(!is_array($item)){
                $item = array($item);
            }
            $ball = array_merge($ball, $item);
        }

        unset($html);
        return $ball;
    }
    return false;
}

/**
 * 保存设置到配置文件中
 * @param $path
 * @param $params
 * @return bool
 */
function save_config($path,$params){
    if(empty($path)){   return false; }
    if(is_array($params)){
        $keys = $values = array();
        foreach ($params as $key => $value){
            $keys[] = '/\'' . $key . '\'(.*?),/';
            $values[] = "'" . $key . "' => '" . $value . "',";
        }

        $stream = file_get_contents($path);
        $stream = preg_replace($keys,$values,$stream);
        file_put_contents($path, $stream);
        return true;
    }
    return false;
}