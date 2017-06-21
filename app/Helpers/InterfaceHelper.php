<?php
/**
 * Copyright (C) Console iT
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

/**
 * make single item of register stat user interface
 *
 * @param Committee $committee single committee item
 * @return string HTML of regstat user interface item
 */
function regStatSingle($committee)
{
    $capacity = $committee->capacity;
    $counts = $committee->allDelegates()->count();
    $counts_real = $committee->allDelegates()->whereIn('status', ['sVerified', 'oVerified', 'paid'])->count();
    $result = '<li class="dd-item" data-id="10"><div class="dd-handle">' . $committee->name . '<span class="pull-right">定员: ' . $capacity . '&emsp;';
    if ($counts > $capacity) $result .= '<strong class="text-danger">';
    $result .= '报名人数: ' . $counts_real . '('.$counts.')';
    if ($counts > $capacity) $result .= '</strong>';
    $result .= '</span></div>';
    if ($committee->childCommittees->count() > 0)
    {
        $result .= '<ol class="dd-list">';
        foreach ($committee->childCommittees as $cc)
            $result .= regStatSingle($cc);
        $result .= '</ol>';
    }
    $result .= '</li>';
    return $result;
}

/**
 * make register stat user interface
 *
 * @param Collection $committees all committees
 * @param int $obs qty of observers
 * @param int $vol qty of volunteers
 * @return string HTML of regstat user interface
 */
function regStat($committees, $obs, $vol)
{
    $result = '<div class="dd" id="nestable2"><ol class="dd-list">';
    if ($committees->count() > 0)
    foreach ($committees as $committee)
    {
        if (empty($committee->parentCommittee))
        {
            $resultSingle = regStatSingle($committee);
            $result .= $resultSingle;
        }
    }
    $result .= '<li class="dd-item" data-id="98"><div class="dd-handle">观察员<span class="pull-right">报名人数: ' . $obs . '</span></div></li>';
    $result .= '<li class="dd-item" data-id="99"><div class="dd-handle">志愿者<span class="pull-right">报名人数: ' . $vol . '</span></div></li>';
    $result .= '</ol></div>';
    return $result;
}

/**
 * make single input item HTML DOM
 *
 * @param string $type type of input
 * @param string $name name of HTTP POST request
 * @param string $value value of HTTP POST request
 * @param string $text text of the input
 * @param string $id id of the DOM element
 * @param bool $required 'data_required' for parsley
 * @return string HTML DOM element <input ...>...
 */
function singleInput($type, $name, $value = '', $text = null, $id = null, $required = false, $placeholder = '')
{
    $html = '<input ';
    if (isset($id)) $html .= 'id="'.$id.'"';
    $html .= ' type="'.$type.'" name="'.$name.'" value="'.$value.'"';
    if ($required) $html .= ' data-required="true"';
    if (!empty($placeholder) && $type == 'text') $html .= ' placeholder="'.$placeholder.'"';
    $html .= $type == 'text' ? ' class="form-control">' : '>';
    if (isset($text)) $html .= '&nbsp;' . $text;
    if ($type != 'text') $html .= '<br>';
    return $html;
}

/**
 * make single textarea item HTML DOM
 *
 * @param string $name name of HTTP POST request
 * @param string $text text of the input
 * @param bool $required 'data_required' for parsley
 * @return string HTML DOM element <input ...>...
 */
function textField($name, $text, $required = false, $placeholder = '')
{
    $html = '<textarea name="'.$name.'" class="form-control assignment" placeholder="'.$placeholder.'" type="text">';
    $html .= str_replace(["\r\n","\\r\\n"],'&#13;&#10;',$text);
    $html .= '</textarea>';
    return $html;
}
/**
 * Render one item of the table to html
 *
 * @param object $item the table item object ($*->items as item)
 * @param string $useParam 用于判断项目是否对特定用户组有效
 * @param string $useCompare 用于判断上述内容的数据源（item下的变量名）
 * @return string HTML clip of the table
 */
function singleRegItem($item, $useParam, $useCompare = null)
{
    $html = '';
    if (isset($item->title) && $item->type != 'group')
        $html .= '<label>'.$item->title.'</label>';
    switch ($item->type)
    {
        // 自定义的表单项
        case 'select': $html .= '<select name="'.$item->name.'" class="form-control m-b"';
            if (!empty($item->data_required)) $html .= ' data-required="true"';
            if (!empty($item->id)) $html .= ' id="'.$item->id.'"';
            $html .= '>
            <option value="" selected="">请选择</option>';
            foreach ($item->options as $option)
                $html .= '<option value="'.$option->value.'">'.$option->text.'</option>';
            $html .= '</select> ';
        break;
        case 'checkbox': 
        case 'text': 
            $id = $text = null;
            if (!empty($item->id)) $id = $item->id;
            if (!empty($item->text)) $text = $item->text;
            $html .= singleInput($item->type, $item->name, '', $text, $id);
        break;
        case 'group': 
            $html .= '<div class="form-group" ';
            if (!empty($item->id)) $html .= ' id="'.$item->id.'"';
            $html .= '>';
            if (isset($item->title))
                $html .= '<label>'.$item->title.'</label>';
            foreach ($item->items as $subitem)
            {
                if (!empty($useCompare) && !in_array($useParam, $item->{$useCompare})) continue;
                $html .= singleRegItem($subitem, $useParam, $useCompare);
            }
            $html .= '</div>';
        break;
    }
    return $html;
}
