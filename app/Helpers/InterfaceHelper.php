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
    $result = '<li class="dd-item" data-id="10"><div class="dd-handle">' . $committee->name . '<span class="pull-right">定员: ' . $capacity . '&emsp;';
    if ($counts > $capacity) $result .= '<strong class="text-danger">';
    $result .= '报名人数: ' . $counts;
    $result .= '</span></div>';
    if ($counts > $capacity) $result .= '</strong>';
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

    foreach ($committees as $committee)
    {
        if (empty($committee->parentCommittee))
        {
            $resultSingle = regStatSingle($committee);
            $result .= $resultSingle;
        }
    }
    $result .= '<li class="dd-item" data-id="98"><div class="dd-handle">观察员<span class="pull-right">报名人数: ' . $obs . '</span></li>';
    $result .= '<li class="dd-item" data-id="99"><div class="dd-handle">志愿者<span class="pull-right">报名人数: ' . $vol . '</span></li>';
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
 * @return string HTML DOM element <input ...>...
 */
function singleInput($type, $name, $value = '', $text = null, $id = null)
{
    $html = '<input ';
    if (isset($id)) $html .= 'id="'.$id.'"';
    $html .= ' type="'.$type.'" name="'.$name.'" value="'.$value.'">';
    if (isset($text)) $html .= $text;
    return $html;
}
