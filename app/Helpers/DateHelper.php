<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

/**
 * 时间格式化
 * @link http://php.net/manual/en/function.time.php
 * @param string $date 时间
 * @param boolean $concise 是否仅显示几天前而不显示具体时间
 * @return string 格式化后的时间（如几天前）
 */
function nicetime($date, $concise = false)
{
        if(empty($date))
        {
                return false;
        }

        $periods = array('秒钟', '分钟', '小时', '天', '周', '月', '年');
        $lengths = array("60", "60", "24", "7", "4.35", "12");

        $now = time();
        $unix_date = strtotime($date);

        if($now > $unix_date)
        {
                $difference = $now - $unix_date;
                $tense = '前';
        }
        else
        {
                $difference = $unix_date - $now;
                $tense = '后';
        }

        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++)
        {
                $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if($concise)
            if ($difference != 0)
                return "$difference$periods[$j]{$tense}";
            else
                return "刚刚";
        if ($difference != 0)
            return $date."（$difference $periods[$j]{$tense}）";
        else
            return $date."（刚刚）";
}

/**
 * Validate if the reg type is open to register
 *
 * @param string $type in ['delegate', 'observer', 'volunteer']
 * @return bool the type of registration is valid at time on request
 */
function validateRegDate($type)
{
    $regDate = json_decode(Reg::currentConference()->option('reg_dates'));
    $result = false;
    foreach ($regDate as $value)
    {
        if ($type != 'unregistered') $result = false;
        if (strtotime(date("y-m-d H:i:s")) < strtotime($value->config->start)) $result |= false;
        elseif ($value->config->end == 'ended') $result |= false;
        elseif (strtotime(date("y-m-d H:i:s")) > strtotime($value->config->end) && $value->config->end != 'manual') $result |= false;
        else $result |= true;
        if ($value->use == $type) break;
    }
    return $result;
}

/**
 * Validate if the reg type is avaliable for registeration
 *
 * @param string $type in ['delegate', 'observer', 'volunteer']
 * @return bool the type of registration is valid at time on request
 */
function validateRegAvaliable($type)
{
    $regDate = json_decode(Reg::currentConference()->option('reg_dates'));
    $result = false;
    foreach ($regDate as $value)
    {
        if ($value->use == $type) 
        {
            $result = true;
            break;
        }
    }
    return $result;
}
