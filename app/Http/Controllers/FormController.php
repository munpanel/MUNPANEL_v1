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

namespace App\Http\Controllers;

class FormController extends Controller
{
    /**
     * Render the table to html
     *
     * @param object $tableItems the table items object ($*->items)
     * @param string $useParam 用于判断项目是否对特定用户组有效
     * @param string $useCompare 用于判断上述内容的数据源（item下的变量名）
     * @return string HTML clip of the table
     */
    public static function render($tableItems, $useParam, $useCompare)
    {
        $html = '';
        foreach ($tableItems as $item)
        {
            // 使用 $useParam 比对 $item->{$useCompare} 的值确定下一项是否对用户有效
            // 例：在 regTable 中，检查 $item->uses 是否存在 $regType 的值
            // TODO: 基于委员会的判断时，对父委员会的判断 
            if (!in_array($useParam, $item->{$useCompare})) continue;
            switch ($item->type)
            {
                  // 预设的表单项
                case 'preGroupOptions': $html .='
                <div class="form-group">
                  <label>团队报名选项</label>
                  <div>
                      <input name="groupOption" value="personal" type="radio" checked="checked">
                      我以个人身份报名<br>
                      <input name="groupOption" value="group" type="radio">
                      我跟随团队报名<br>
                      <input name="groupOption" value="leader" type="radio">
                      我是团队报名的领队<br>
                  </div>
                </div>';
                break;
                case 'preRemarks': $html .='
                <div class="form-group">
                  <label>备注</label>
                  <textarea name="others" class="form-control" placeholder="任何其他说明" type="text"></textarea>
                </div>';
                break;
                default:
                    $html .= singleRegItem($item, $useParam, $useCompare);
            }
        }
        return $html;
    }
    
    /**
     * filter form assignments by committee
     *
     * @param object $tableItems the table items object ($*->items)
     * @param int $committeeID ID of committee
     * @return array assignment objects with specific committee
     */
    public static function filterFormAssignmentsByCommittee($tableItems, $committeeID)
    {
        $result = [];
        foreach ($tableItems as $item)
        {
            if (!!array_intersect($committeeID, $item->committee))
                array_push($result, $item);
        }
        return $result;
    }
    
    /**
     * Render the assignment form to html
     *
     * @param object $tableItems the table items object ($*->items)
     * @param int $committeeID ID of committee
     * @param int $maxValue the items will be shown
     * @return string HTML clip of the table
     */
    public static function formAssignment($tableItems, $committeeID, $maxValue)
    {
        $html = '';
        $num = 0;
        if ($maxValue > count($tableItems)) $maxValue = count($tableItems);
        $subItems = array_rand($tableItems, $maxValue);
        foreach ($tableItems as $item)
        {
            $html .= '<div class="form-group"><span class="badge form-assignment m-l-n-xs">'.++$num.'</span>&nbsp;<label>'.$item->title.'</label><div class="m-l-lg">';
            $i = 0;
            switch ($item->type)
            {
                case "single_choice":
                    foreach ($item->options as $option)
                        $html .= singleInput('radio', $item->id, ++$i, $option->text);
                        //$html .= '<div class="radio"><label class="radio-custom"><input type="radio" name="answer['.$item->id.']" value="'.$option->value.'"><i class="fa fa-circle-o"></i>'.$option->text.'</label></div>';
                    break;
                case "mult_choice":
                    foreach ($item->options as $option)
                        $html .= singleInput('checkbox', $item->id, ++$i, $option->text);
                        //$html .= '<div class="radio"><label class="radio-custom"><input type="radio" name="answer['.$item->id.']" value="'.$option->value.'"><i class="fa fa-circle-o"></i>'.$option->text.'</label></div>';
                    break;
                case "yes_or_no":
                    $html .= '
                <div class="btn-group" data-toggle="buttons">
                  <label class="btn btn-sm btn-success">
                    <input name="'.$item->id.'" id="true" type="radio" value="true"> <i class="fa fa-check text-active"></i>正确
                  </label>
                  <label class="btn btn-sm btn-danger">
                    <input name="'.$item->id.'" id="false" type="radio" value="false"> <i class="fa fa-times text-active"></i>错误
                  </label></div>';
                    break;
                case "fill_in":
                    $html .= singleInput('text', $item->id);
            }
            $html .= '</div></div>';
        }
        return $html;
    }
}
