<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App\Http\Controllers;

use App\Reg;
use App\Handin;
use App\Form;

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
    public static function render($tableItems, $useParam, $useCompare = null)
    {
        $html = '';
        foreach ($tableItems as $item)
        {
            // 使用 $useParam 比对 $item->{$useCompare} 的值确定下一项是否对用户有效
            // 例：在 regTable 中，检查 $item->uses 是否存在 $regType 的值
            // TODO: 基于委员会的判断时，对父委员会的判断
            if (!empty($useCompare) && !in_array($useParam, $item->{$useCompare})) continue;
            switch ($item->type)
            {
                // 预设的表单项
                case 'preCommittee': $html .= '
                <div class="form-group">
                  <label>委员会 *</label>
                  <select id="" name="committee" class="form-control" data-required="true">
                    <option value="" selected="">请选择</option>';
                foreach (Reg::currentConference()->committees as $committee)
                    $html .= '<option value="'.$committee->id.'">'.$committee->display_name.'</option>';
                $html .= '</select></div>';
                break;
                case 'prePartnerName': $html .='
                <div class="form-group">
                  <label>搭档姓名</label>
                  <input name="partnername" class="form-control" type="text" placeholder="无则空">
                </div>';
                break;
                case 'preIsAccomodate': 
                    if (isset($item->value))
                        $html .= '<input name="accomodate" type="hidden" value="'.$item->value.'">';
                    else
                        $html .='
            <div class="form-group form-inline">
              <label>是否住宿 *</label>
              <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-sm btn-info" onclick="$(\'input#accomodateChoice\').checked = true;">
                  <input name="accomodate" id="accomodateChoice" type="radio" value="1"> <i class="fa fa-check text-active"></i>是
                </label>
                <label class="btn btn-sm btn-success" onclick="$(\'input#noAccomodateChoide\').checked = true;">
                  <input name="accomodate" id="noAccomodateChoice" type="radio" value="0" data-required="true"> <i class="fa fa-check text-active"></i>否
                </label>
              </div>
            </div>';
                break;
                case 'preRoommateName': $html .='
                <div class="form-group">
                  <label>室友姓名</label>
                  <input name="roommatename" class="form-control" type="text" placeholder="无则空">
                </div>';
                break;
                case 'preGroupOptions': $html .='
                <div class="form-group">
                  <label>团队报名选项</label>
                  <div>
                      <input name="groupOption" value="personal" type="radio" checked="checked">
                      我以个人身份报名<br>
                      <input name="groupOption" value="group" type="radio">
                      我跟随团队报名<br>
                      <!--input name="groupOption" value="leader" type="radio">
                      我是团队报名的领队<br-->
                  </div>
                </div>';
                break;
                case 'preRemarks': $html .='
                <div class="form-group">
                  <label>备注</label>
                  <textarea name="remarks" class="form-control" placeholder="任何其他说明" type="text"></textarea>
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
    public static function formAssignment($assignmentID, $tableItems, $formID, $target, $cansave, $handin = null)
    {
        if (!empty($handin)) $content = json_decode($handin->content);
        $html = '<form method="POST" id="assignmentForm" class="m-t-lg m-b">'.csrf_field();
        if (!empty($handin)) $html .= '<input type="hidden" value="'.$handin->id.'" name="handin">';
        $html .= '<input type="hidden" value="'.$formID.'" name="form">';
        $html .= FormController::formAssignmentTableItems($tableItems, $content);
        $html .= '<div class="form-group"><a href="'.mp_url($target.'/confirm').'" class="btn btn-success" data-toggle="ajaxModal">提交作业</button>';
        //if ()
            $html .= '<a href="'.($cansave ? mp_url('assignments') : '').'" class="text-black lter m-l" style="vertical-align: text-top;'.($cansave ? '' : 'display: none').'">保存并离开</a>';
        $html .= '</div></form>';
        return $html;
    }

    /**
     * Render the daisreg assignment form to html
     *
     * @param array $tableItems the table items ($*->items)
     * @param int $formID the ID of form
     * @param int $handinID the ID of handin
     * @return string HTML clip of the table
     */
    public static function daisregformAssignment($tableItems, $formID, $target, $handin)
    {
        if (!empty($handin)) $content = (array)$handin;
        $html = '<form method="POST" id="assignmentForm" action="'.mp_url($target.'/true').'" class="m-t-lg m-b">'.csrf_field();
        $html .= '<input type="hidden" value="'.$formID.'" name="form">';
        $html .= FormController::formAssignmentTableItems($tableItems, $handin);
        $html .= '<div class="form-group"><button type="submit" class="btn btn-success">提交</button></div></form>';
        return $html;
    }

    /**
     * Render the assignment form to html
     *
     * @param array $tableItems the table items ($*->items)
     * @return string HTML clip of the table
     */
    public static function formAssignmentTableItems($tableItems, $handin)
    {
        $html = '';
        $num = 0;
        foreach ($tableItems as $item)
        {
            $html .= '<div class="form-group"><table><tbody><tr><td valign="top"><span class="badge form-assignment">'.++$num.'</span></td><td>&nbsp;</td><td><label>'.$item->title.'</label></td></tr></tbody></table><div class="';
            $html .= $item->type == 'order' ? 'dd ' : '';
            $html .= 'm-l-30">';
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
                    $html .= singleInput('text', $item->id, (isset($handin->{$item->id}) ? $handin->{$item->id} : ''), null, null, false, $placeholder);
                    break;
                case "text_field":
                    $placeholder = isset($item->placeholder) ? $item->placeholder : '';
                    $html .= textField($item->id, (isset($handin->{$item->id}) ? $handin->{$item->id} : ''), false, $placeholder);
                    break;
                case "order":
                    $li = 0;
                    $html .= '<ol class="dd-list">';
                    foreach ($item->options as $option)
                        $html .= '<li class="dd-item"><div class="dd-handle order"><span class="pull-left media-xs"><i class="fa fa-sort text-muted fa-sm"></i>&nbsp;'.++$li.'</span><div class="clear">'.$option->text.'</div><input type="hidden" name="'.$item->id.'[]" value="'.$li.'"></div></li>';
                        // $html .= '<li class="list-group-item" draggable="true"><span class="pull-left media-xs"><i class="fa fa-sort text-muted fa-sm"></i>&nbsp;'.++$li.'</span><div class="clear">'.$option->text.'</div><input type="hidden" name="'.$item->id.'[]" value="'.$li.'"></li>';
                    $html .= '</ol>';
                    break;
            }
            $html .= '</div></div>';
        }
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
     * @param bool $withScore show form answer with score?
     * @return string the table items object ($*->items)
     */
    public static function getMyAnswer($questions, $answers, $withScore = false)
    {
        $html = '';
        $nl = '';
        $i = 0;
        $arr_answers = (array)$answers;
        foreach ($arr_answers as $key => $value)
        {
            // 排除 _token 和 handin
            if (in_array($key, ['_token', 'handin', 'form', 'language'])) continue;
            $item = $questions[$key - 1];
            $thisWithScore = $withScore && isset($item->answer);
            $html .= $nl;
            $html .= '<div class="form-group"><table><tbody><tr><td valign="top"><span class="badge form-assignment text-xs">'.++$i.'</span></td><td>&nbsp;</td><td>'.$item->title.'</td></tr></tbody></table>';
            if (empty($value))
            {
                $html .= '<div class="m-l-lg">未作答</div>';
                $nl = '</div>';
                continue;
            }
            switch ($item->type)
            {
                case 'single_choice':
                    $text = $item->options[$value - 1]->text;
                    $html .= '<div class="m-l-lg'.($thisWithScore ? ($value == $item->answer ? ' text-success' : ' text-danger') : '').'">'.$text.'</div>';
                    if ($thisWithScore && !empty($item->answer) && $value != $item->answer)
                        $html .= '<div class="m-l-lg text-primary">正确答案: ' . $item->options[$item->answer - 1]->text . '</div>';
                break;
                case 'yes_or_no':
                    $html .= '<div class="m-l-lg'.($thisWithScore ? ($value == $item->answer ? ' text-success' : ' text-danger') : '').'">' . ($value == 'true' ? '正确' : '错误') . '</div>';
                    if ($thisWithScore && !empty($item->answer) && $value != $item->answer)
                        $html .= '<div class="m-l-lg text-primary">正确答案: ' . ($item->answer == 'true' ? '正确' : '错误') .  '</div>';
                break;
                case 'mult_choice':
                    $corrected = array_diff($item->answer, $value);
                    foreach ($value as $val)
                    {
                        $text = $item->options[$val - 1]->text;
                        $html .= '<div class="m-l-lg '.($thisWithScore ? (in_array($val, $item->answer) ? ' text-success' : ' text-danger') : '').'">'.$text.'</div>';
                    }
                    if (!empty($corrected))
                    {
                        $html .= '<div class="m-l-lg text-primary">未选的项: ';
                        $split = '';
                        foreach ($corrected as $item1)
                        {
                            $html .= $split . $item->options[$item1 - 1]->text;
                            $split = ', ';
                        }
                        $html .= '</div>';
                    }
                break;
                case 'order':
                    $n = 0;
                    foreach ($value as $val)
                    {
                        $text = $item->options[$val - 1]->text;
                        $html .= '<div class="m-l-lg'.($thisWithScore ? ($val == $item->answer[$n++] ? ' text-success' : ' text-danger') : '').'">'.$text.'</div>';
                    }
                    if ($thisWithScore && !empty($item->answer))
                        $html .= '<div class="m-l-lg text-primary">正确答案: ' .json_encode($item->answer). '</div>';
                break;
                case 'fill_in':
                case 'text_field':
                    $html .= '<div class="m-l-lg"'.(($thisWithScore && !empty($item->answer) && (strcmp($value, $item->answer) == 0)) ? ' text-success' : '').'>' . nl2br($value) . '</div>';
                    if ($thisWithScore && !empty($item->answer))
                        $html .= '<div class="m-l-lg text-primary">正确答案: ' .$item->answer. '</div>';
                break;
            }
            $nl = '</div>';
        }
        return $html;
    }

    /**
     * Calculate score from handins
     *
     * @param array $questions the object of form, from json_decode ($*->items)
     * @param object $answers the object of answer, from json_decode ($*->items)
     * @return [correct, all]
     */
    public static function autoScore($questions, $answers)
    {
        $result = [];
        $result['correct'] = 0;
        $result['all'] = 0;
        $arr_answers = (array)$answers;
        foreach ($arr_answers as $key => $value)
        {
            if (in_array($key, ['_token', 'handin', 'form', 'language'])) continue;
            $item = $questions[$key - 1];
            if (!in_array($item->type, ['single_choice', 'mult_choice', 'yes_or_no', 'order'])) continue;
            if (empty($item->answer)) continue;
            if ($item->answer === $value) $result['correct']++;
            $result['all']++;
        }
        return $result;
    }

    /**
     * Find questions from handin
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
            if (in_array($key, ['_token', 'handin', 'form', 'language'])) continue;
            $item = $questions[$key - 1];
            array_push($result, $item);
        }
        return $result;
    }

    /**
     * phpdoc 以后再写
     *
     * @param int $id
     * @return view
     */
    public function showFormWindow($id)
    {
        $handin = Handin::find($id);
        if (is_null($handin))
            return view('dais.formHandinWindow', ['error' => '错误', 'errmsg' => '该提交不存在！']);
        if (Reg::current()->type != 'dais' && Reg::current()->type != 'interviewer' && Reg::current()->type != 'ot' && $handin->reg_id != Reg::currentID())
            return view('dais.formHandinWindow', ['error' => '错误', 'errmsg' => '无查看权限！']);
        if ($handin->assignment->handin_type != 'form')
            return view('dais.formHandinWindow', ['error' => '错误', 'errmsg' => '该提交对应的学术作业并非表单类型！']);
        $answer = json_decode($handin->content);
        $form = json_decode(Form::findOrFail($answer->form)->content);
        $html = $this->getMyAnswer($form->items, $answer, ($handin->reg_id != Reg::currentID()));
        $args = ['handin' => $handin, 'name' => $handin->reg->user->name, 'formContent' => $html];
        if ($handin->reg_id != Reg::currentID()) $args['score'] = $this->autoScore($form->items, $answer);
        return view('dais.formHandinWindow', $args);
    }
}
