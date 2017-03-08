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
            if (in_array($useParam, $item->{$useCompare}))
            {
                if (isset($item->title))
                    $html .= '<label>'.$item->title.'</label>';
                switch ($item->type)
                {
                // 自定义的表单项
                    case 'select': $html .= '
                      <select name="'.$item->name.'" class="form-control m-b"';
                       if (!empty($item->data_required)) $html .= ' data-required="true"';
                    $html .= '>
                        <option value="" selected="">请选择</option>';
                        foreach ($item->options as $option)
                          $html .= '<option value="'.$option->value.'">'.$option->text.'</option>';
                    $html .= '</select> ';
                    break;
                    case 'checkbox': $html .= '<br><input name="'.$item->name.'" type="checkbox"';
                        if (!empty($item->data_required)) $html .= ' data-required="true"';
                        $html .= '>'.$item->text;
                    break;
                    case 'text': $html .= '<input name="'.$item->name.'" class="form-control m-b" type="text" value=""';
                        if (!empty($item->data_required)) $html .= ' data-required="true"';
                        $html .= '>';
                    break;
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
                }
            }
        }
        return $html;
    }
}
