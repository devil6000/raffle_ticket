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

                    //判断是否是数字
                    for($i = 0; $i <= 6; $i++){
                        if(intval($ball[$i]) <= 0){
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

                //判断是否是数字
                for($i = 0; $i <= 6; $i++){
                    if(intval($ball[$i]) <= 0){
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
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0); //对认证证书来源的检查
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11'); //模拟用户使用的浏览器
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); //使用自动跳转
    //curl_setopt($ch,CURLOPT_AUTOREFERER,1); //自动设置Referer
    //curl_setopt($ch,CURLOPT_PROXY,"http://120.198.248.34:8088"); //代理IP
    curl_setopt($ch,CURLOPT_HTTPHEADER, array('Accept-Encoding:gzip,deflate','X-FORWARDED-FOR:120.198.248.34', 'CLIENT-IP:120.198.248.34'));
    curl_setopt($ch, CURLOPT_ENCODING,'gzip,deflate');
    curl_setopt($ch, CURLOPT_FAILONERROR,true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);  //尝试链接时间
    curl_setopt($ch,CURLOPT_TIMEOUT,6); //链接超时时间

    $html = curl_exec($ch);
    curl_close($ch);

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

/**
 * 判断是否为错误信息
 * @param $data
 * @return bool
 */
function is_error($data){
    if(empty($data) || is_array($data) || array_key_exists('errno', $data) || (array_key_exists('errno', $data) && $data['errno'] == 0)){
        return false;
    }else{
        return true;
    }
}

/**
 * 获取双色球红色球，蓝色球
 * @return array
 */
function get_double_ball(){
    $ball = array();
    $redBall = array();
    $blueBall = array();
    for($i = 1; $i <= 33; $i++){
        $redBall[] = substr('00' . $i, -2);
    }
    for($i = 1; $i <= 16; $i++){
        $blueBall[] = substr('00' . $i, -2);
    }

    $ball['red'] = $redBall;
    $ball['blue'] = $blueBall;

    return $ball;
}

/**
 * 随机数
 * @param $len
 * @return bool|string
 */
function get_random($len){
    return substr(md5(microtime(true)),0,$len);
}

/**
 * 获取AC值
 * 一组号码中所有两个号码相减，然后对所得的差求绝对值，如果有相同的数字，则只保留一个，得到不同差值个数D(t)，然后，用个数值D(t)减去（r-1）（其中r为投注号码数），这个数值就是AC值
 * @param $issue
 * @param int $r
 * @return int
 */
function compute_ac($issue,$r = 7){
    $lottery = \app\model\DoubleModel::where('issue', $issue)->find();
    $balls = explode(' ', $lottery['whole']);
    if($r == 6){    unset($balls[6]);}
    $val = array();
    foreach ($balls as $k => $ball){
        foreach ($balls as $k1 => $b1){
            $v = abs($ball - $b1);
            if($v != 0){
                $val[] = $v;
            }
        }
    }
    $val = array_unique($val);  //去除重复的值
    $dt = count($val);
    return $dt - ($r - 1);
}

/**
 * 号码散度：单注所有号码与当前号码之差（以结果的绝对值为准）的最小值中的最大的一个。
 * @param $issue
 * @return mixed
 */
function get_divergence($issue){
    $lottery = \app\model\DoubleModel::where('issue', $issue)->find();
    $rebBalls = unserialize($lottery['red_ball']);
    $diff = array();
    foreach ($rebBalls as $k => $b){
        $min[$k] = 33;
        foreach ($rebBalls as $k1 => $b1){
            $v = abs($b - $b1);
            if($v != 0){
                $diff[$k][] = $v;
                if($min[$k] > $v){
                    $min[$k] = $v;
                }
            }
        }
    }

    return max($min);
}

/**
 * 号码偏度：单注所有号码与上期开奖号码之差（以结果的绝对值为准）的最小值中的最大的一个
 * @param $currentIssue
 * @param $PreviousIssue
 * @return array|mixed
 */
function get_skewness($currentIssue, $PreviousIssue){
    if(empty($PreviousIssue)){  return array('errno' => 1, 'message' => '没有上期期号');}
    $cur = \app\model\DoubleModel::where('issue', $currentIssue)->find();
    $pre = \app\model\DoubleModel::where('issue', $PreviousIssue)->find();
    $curRedBalls = unserialize($cur['red_ball']);
    $preRedBalls = unserialize($pre['red_ball']);
    $diff = array();
    foreach ($curRedBalls as $k => $curBall){
        $min[$k] = 33;
        foreach ($preRedBalls as $k1 => $preBall){
            $v = abs($curBall - $preBall);
            $diff[$k][] = $v;
            if($min[$k] > $v){
                $min[$k] = $v;
            }
        }
    }

    return max($min);
}

/**
 * 各个球出现的总次数
 * @param $year
 * @return array
 */
 function get_occurrence_count($year){
    if(empty($year)){
        $list = \app\model\DoubleModel::order('year asc, issue_no asc')->select();
    }elseif(is_string($year)){
        $list = \app\model\DoubleModel::where('year', $year)->order('issue_no asc')->select();
    }elseif (is_array($year)){
        $year = import(',', $year);
        $list = \app\model\DoubleModel::where('year', array('in', $year))->order('year asc, issue_no asc')->select();
    }
    $redBalls = Lottery::$redBalls;
    $blueBalls = Lottery::$blueBalls;
    $return = array();
    if(!empty($list)){
        foreach ($list as $item){
            $openRedBalls = unserialize($item['red_ball']);
            //获取红球开出次数
            foreach ($redBalls as $redBall){
                $return[$item['year']]['red'][$redBall] += (in_array($redBall, $openRedBalls) ? 1 : 0);
            }
            //获取蓝球开出次数
            foreach ($blueBalls as $blueBall){
                $return[$item['year']]['blue'][$blueBall] += ($blueBall == $item['blue_ball'] ? 1 : 0);
            }
        }
    }

    return $return;
 }

/**
 * 红球奇偶数
 * @param array $data
 * @return array'
 */
 function get_odd_and_even_rate($data = array()){
     //判断是按期号查询还是年份查询
     if(!empty($data) && array_key_exists('issue', $data) && (array_key_exists('issue', $data) && !empty($data['issue']))){
         $lottery = \app\model\DoubleModel::where('issue', $data['issue'])->find();
         $list = array($lottery);
     }else{
         if(empty($data) || empty($data['year'])){
             $list = \app\model\DoubleModel::order('year asc, issue_no asc')->select();
         }elseif (is_string($data['year'])){
             $list = \app\model\DoubleModel::where('year', $data['year'])->order('issue_no asc')->select();
         }elseif (is_array($data['year'])){
             $year = import(',', $data['year']);
         }
     }

     $return = array();
     foreach ($list as $item){
         $openRedBalls = unserialize($item['red_ball']);
         foreach ($openRedBalls as $ball){
             $odd = intval($ball) % 2 == 0 ? 0 : 1;
             $even = intval($ball) % 2 == 0 ? 1 : 0;
             $return[$item['year']]['odd'] += $odd;
             $return[$item['year']]['even'] += $even;
             $return[$item['year']]['issue'][$item['issue']]['odd'] += $odd;
             $return[$item['year']]['issue'][$item['issue']]['even'] += $even;
         }
     }

     return $return;
 }
