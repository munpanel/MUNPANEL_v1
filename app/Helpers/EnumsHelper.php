<?php

/**
 * get province string from code
 *
 * @param int $code province code by GB2260-2007
 * @return string name of the province
 */
function province($code)
{
    if ($code == 11) return "北京";
    if ($code == 12) return "天津";
    if ($code == 13) return "河北";
    if ($code == 14) return "山西";
    if ($code == 15) return "内蒙古";
    if ($code == 21) return "辽宁";
    if ($code == 22) return "吉林";
    if ($code == 23) return "黑龙江";
    if ($code == 31) return "上海";
    if ($code == 32) return "江苏";
    if ($code == 33) return "浙江";
    if ($code == 34) return "安徽";
    if ($code == 35) return "福建";
    if ($code == 36) return "江西";
    if ($code == 37) return "山东";
    if ($code == 41) return "河南";
    if ($code == 42) return "湖北";
    if ($code == 43) return "湖南";
    if ($code == 44) return "广东";
    if ($code == 45) return "广西";
    if ($code == 46) return "海南";
    if ($code == 50) return "重庆";
    if ($code == 51) return "四川";
    if ($code == 52) return "贵州";
    if ($code == 53) return "云南";
    if ($code == 54) return "西藏";
    if ($code == 61) return "陕西";
    if ($code == 62) return "甘肃";
    if ($code == 63) return "青海";
    if ($code == 64) return "宁夏";
    if ($code == 65) return "新疆";
    if ($code == 71) return "台湾";
    if ($code == 81) return "香港";
    if ($code == 82) return "澳门";
    if ($code == 99) return "海外";
    return "undefined";
}

/**
 * make select box for province selection
 *
 * @param int $selected province code selected, if applicable
 * @return string HTML of select box for province
 */
function provinceSelect($selected = null)
{
    $result = '<select name="province" class="form-control" data-required="true">';
    $arr = [11, 12, 13, 14, 15, 21, 22, 23, 31, 32, 33, 34, 35, 36, 37, 41, 42, 43, 44, 45, 46, 50, 51, 52, 53, 54, 61, 62, 63, 64, 65, 71, 81, 82, 99];
    foreach ($arr as $item)
        $result .= '<option value="' . $item . '"' . ($selected == $item ? 'selected=""' : '') . '>' . province($item) . '</option>';
    $result .= '</select>';
    return $result;
}

/**
 * get name of type ID
 *
 * @param int $code 1-nationalID 2-passport 3-HKMacaoPass 4-TaiwanPass
 * @return string name of type ID
 */
function typeID($code)
{
    if ($code == 1) return "居民身份证";
    if ($code == 2) return "护照 (Passport)";
    if ($code == 3) return "港澳回乡证";
    if ($code == 4) return "台胞证";
    return "undefined";
}

/**
 * get string of level of conference
 *
 * @param int $code 1-nationalAndHigher 2-Regional 3-InterScholar 4-SchoolInternal
 * @return string level of conference
 */
function levelOfConfs($code)
{
    if ($code == 1) return "全国及以上级别会议";
    if ($code == 2) return "地区级会议";
    if ($code == 3) return "校际会";
    if ($code == 4) return "校内会";
    return "undefined";
}

/**
 * get string of interview type
 *
 * @param int $code 1-phone 2-qq 3-skype 4-wechat
 * @return string interview type in string
 */
function typeInterview($code)
{
    if ($code == 1) return "电话 (包括备用电话)";
    if ($code == 2) return "QQ";
    if ($code == 3) return "Skype";
    if ($code == 4) return "微信";
    return "undefined";
}

/**
 * get string of group reg option
 *
 * @param string $value 1-phone 2-qq 3-skype 4-wechat
 * @return string group reg option in string
 */
function groupOption($value)
{
    if ($value == "personal") return "我以个人身份报名";
    if ($value == "group") return "我跟随团队报名";
    if ($value == "leader") return "我是团队报名的领队";
    return "undefined";
}
