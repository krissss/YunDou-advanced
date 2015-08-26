<?php

namespace common\functions;


class DateFunctions {

    /**
     * 获取当前时间
     * @return bool|string
     */
    public static function getCurrentDate(){
        return date('Y-m-d H:i:s',time());
    }

    /**
     * 读取时间的日期
     * @param $date
     * @return bool|string
     */
    public static function getDate($date){
        return date('Y-m-d',strtotime($date));
    }

    /**
     * 格式化只有日期的时间到一天开始
     * @param $date
     * @return string
     */
    public static function formatDateToStart($date){
        return $date.' 00:00:00';
    }

    /**
     * 格式化只有日期的时间到一天的结束
     * @param $date
     * @return string
     */
    public static function formatDateToEnd($date){
        return $date.' 23:59:59';
    }

    public static function getDateFromDateTime($date){
        return date("Y-m-d", strtotime($date));
    }

    public static function getYearFromDateTime($date){
        return date("Y", strtotime($date));
    }

    public static function getMonthFromDateTime($date){
        return date("m", strtotime($date));
    }

    public static function getDayFromDateTime($date){
        return date("d", strtotime($date));
    }



}