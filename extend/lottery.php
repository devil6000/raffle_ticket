<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/5/9
 * Time: 上午10:56
 */

if(!class_exists('Lottery')){

    class Lottery {
        //红色球
        public static $redBalls = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20',
            '21','22','23','24','25','26','27','28','29','30','31','32','33'];
        //蓝球
        public static $blueBalls = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16'];
        //断区
        public static $faultArea = [
            ['01','02','03','04','05','06','07','08'],
            ['09','10','11','12','13','14','15','16'],
            ['18','19','20','21','22','23','24','25'],
            ['26','27','28','29','30','31','32','33']
        ];
        //质数
        public static $primeMember = ['01','02','03','05','07','11','13','17','19','23','31'];

        //获取质数, 合数
        public static function getPrimeTimes($data = array()){
            $list = analysis_params($data);
            $result = array();
            foreach ($list as $item){
                $redBalls = unserialize($item['red_ball']);
                $blueBall = $item['blue_ball'];
                foreach ($redBalls as $ball){
                    if(in_array($ball, Lottery::$primeMember)){
                        $result[$item['year']]['redball']['prime'] += 1;
                        $result[$item['year']][$item['issue']]['redball']['prime'] += 1;
                    }else{
                        $result[$item['year']]['redball']['composite'] += 1;    //合数
                        $result[$item['year']][$item['issue']]['redball']['composite'] += 1;
                    }
                }
                if(in_array($blueBall, Lottery::$primeMember)){
                    $result[$item['year']]['blueball']['prime'] += 1;
                    $result[$item['year']][$item['issue']]['blueball']['prime'] += 1;
                }else{
                    $result[$item['year']]['blueball']['composite'] += 1;
                    $result[$item['year']][$item['issue']]['blueball']['composite'] += 1;
                }
            }

            return $result;
        }
    }
}
