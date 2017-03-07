<?php

/**
 * get province string from code
 *
 * @param int $code province code by GB2260-2007
 * @return string name of the province
 */
function province($code)
{
    $results = [ 11 => "北京", "天津", "河北", "山西", "内蒙古",
        21 => "辽宁", "吉林", "黑龙江",
        31 => "上海", "江苏", "浙江", "安徽", "福建", "江西", "山东",
        41 => "河南", "湖北", "湖南", "广东", "广西", "海南",
        50 => "重庆", "四川", "贵州", "云南", "西藏",
        61 => "陕西", "甘肃", "青海", "宁夏", "新疆",
        71 => "台湾",
        81 => "香港", "澳门",
        99 => "海外" ];
    if (isset($results[$code])) return $results[$code];
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
