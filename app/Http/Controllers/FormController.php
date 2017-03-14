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
     * @param array $tableItems the table items ($*->items)
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
     * @param int $assignmentID the id of form Assignment
     * @param array $tableItems the table items ($*->items)
     * @param int $formID the ID of form
     * @param int $handinID the ID of handin
     * @return string HTML clip of the table
     */
    public static function formAssignment($assignmentID, $tableItems, $formID, $handinID = 0)
    {
        $html = '<form method="POST" action="'.mp_url('/assignment/'.$assignmentID.'/formSubmit').'" class="m-t-lg m-b">'.csrf_field();
        $num = 0;
        if (!empty($handinID)) $html .= '<input type="hidden" value="'.$handinID.'" name="handin">';
        $html .= '<input type="hidden" value="'.$formID.'" name="form">';
        foreach ($tableItems as $item)
        {
            $html .= '<div class="form-group"><span class="badge form-assignment">'.++$num.'</span>&nbsp;<label>'.$item->title.'</label><div class="m-l-30">';
            $i = 0;
            switch ($item->type)
            {
                case "single_choice":
                    $html .= '<input type="hidden" name="'.$item->id.'" value="">';
                    foreach ($item->options as $option)
                        $html .= singleInput('radio',$item->id, ++$i, $option->text);
                        //$html .= '<div class="radio"><label class="radio-custom"><input type="radio" name="answer['.$item->id.']" value="'.$option->value.'"><i class="fa fa-circle-o"></i>'.$option->text.'</label></div>';
                    break;
                case "mult_choice":
                    $html .= '<input type="hidden" name="'.$item->id.'" value="">';
                    foreach ($item->options as $option)
                        $html .= singleInput('checkbox',$item->id.'[]', ++$i, $option->text, false, true);
                        //$html .= '<div class="radio"><label class="radio-custom"><input type="radio" name="answer['.$item->id.']" value="'.$option->value.'"><i class="fa fa-circle-o"></i>'.$option->text.'</label></div>';
                    break;
                case "yes_or_no":
                    $html .= '<input type="hidden" name="'.$item->id.'" value="">';
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
                    $placeholder = isset($item->placeholder) ? $item->placeholder : '';
                    $html .= singleInput('text',$item->id,'',null,null,false,$placeholder);
                    break;
                case "order":
                    $li = 0;
                    $html .= '<ul class="list-group gutter list-group-sp sortable">';
                    foreach ($item->options as $option)
                        $html .= '<li class="list-group-item" draggable="true"><span class="pull-left media-xs"><i class="fa fa-sort text-muted fa-sm"></i>&nbsp;'.++$li.'</span><div class="clear">'.$option->text.'</div><input type="hidden" name="'.$item->id.'[]" value="'.$li.'"></li>';
                    $html .= '</ul>';
                    break;
            }
            $html .= '</div></div>';
        }
        $html .= '<div class="form-group"><button type="submit" class="btn btn-success">提交作业</button></div></form>';
        return $html;
    }

    /**
     * Get the items of the form assignment
     *
     * @param object $object the object of form, from json_decode ($*)
     * @return array the table items object ($*->items)
     */
    public static function getQuestions($object)
    {
        if ($object->config->use == "all")
            return $object->items;
        // if ($object->config->use == "random")
        $total = $object->config->total;
        if (empty($object->config->by_level))
        {
            $res_keys = array_rand($object->items, $total);
            $result = [];
            foreach ($res_keys as $key)
                array_push($result, $object->items[$key]);
            return $result;
        }
        $result = $split = [];
        foreach ((array)$object->config->by_level as $level => $qty)
            $split[$level] = [];
        foreach ($object->items as $item)
            array_push($split[$item->level], $item);
        foreach ((array)$object->config->by_level as $level => $qty)
        {
            $singlesplit = $split[$level];
            $res_keys = array_rand($singlesplit, $qty);
            foreach ($res_keys as $key)
                array_push($result, $singlesplit[$key]);
        }
        return $result;
    }

    /**
     * Get the items of the form assignment
     *
     * @param array $questions the object of form, from json_decode ($*->items)
     * @param object $answers the object of answer, from json_decode ($*->items)
     * @return string the table items object ($*->items)
     */
    public static function getMyAnswer($questions, $answers)
    {
        $html = '';
        $i = 0;
        $arr_answers = (array)$answers;
        foreach ($arr_answers as $key => $value)
        {
            // 排除 _token 和 handin
            if (in_array($key, ['_token', 'handin', 'form'])) continue;
            $item = $questions[$key - 1];
            $html .= '<div class="form-group"><span class="badge form-assignment text-xs">'.++$i.'</span>&nbsp;'.$item->title;
            switch ($item->type)
            {
                case 'single_choice':
                    $text = $item->options[$value - 1]->text;
                    $html .= '<div class="m-l-lg">'.$text.'</div>';
                break;
                case 'yes_or_no':
                    $html .= '<div class="m-l-lg">' . ($value == 'true' ? '正确' : '错误') . '</div>';
                break;
                case 'mult_choice':
                case 'order':
                    foreach ($value as $val)
                    {
                        $text = $item->options[$val - 1]->text;
                        $html .= '<div class="m-l-lg">'.$text.'</div>';
                    }
                break;
                case 'fill_in':
                    $html .= '<div class="m-l-lg">' . $value . '</div>';
                break;
            }
            $html .= '</div>';
        }
        return $html;
    }

    /**
     * Get the items of the form assignment
     *
     * @param array $questions the object of form, from json_decode ($*->items)
     * @param object $answers the object of answer, from json_decode ($*->items)
     * @return string the table items object ($*->items)
     */
    public static function restoreQuestions($questions, $answers)
    {
        $result = [];
        $i = 0;
        $arr_answers = (array)$answers;
        foreach ($arr_answers as $key => $value)
        {
            // 排除 _token 和 handin
            if (in_array($key, ['_token', 'handin', 'form'])) continue;
            $item = $questions[$key - 1];
            array_push($result, $item);
        }
        return $result;
    }
}
