<?php


namespace App\Swep\ViewHelpers;


use App\Swep\Helpers\__sanitize;
use Illuminate\Support\Carbon;

class __form2
{

    public static function textbox($name,$options = [],$value = null){
        $n = new __form2;
        $n->set($options);
        $r_o = '';
        $step = '';
        if(is_object($value)){
            $value = $value->$name;
        }
        $ext = '';
        if($n->is_multiple == 1){
            $ext = '[]';
        }

        if($n->type == 'date'){
            $value = ($value != '') ? Carbon::parse($value)->format('Y-m-d') : '';
        }


        $r_o = ($n->readonly == 'readonly') ? 'readonly' : '';
        $d = ($n->disabled == 'disabled') ? 'disabled' : '';
        $step = ($n->step != '') ? 'step="'.$n->step.'"' : '';
        $id = ($n->id != '') ?  'id="'.$n->id.'"' : '';
        $title = ($n->title != '') ? '<i class="fa fa-question-circle" title="'.$n->title.'"></i>' : '';
        return '<div class="form-group col-md-'.$n->cols.' '.$name.'">
                <label for="'. $name .'">'.$n->label.'</label> '.$title.'
                <input class="form-control '.$n->class.'" '.$id.' name="'. $name .$ext.'" type="'.$n->type.'" value="'.$value.'" placeholder="'. $n->placeholder.'" '. $n->extra_attr .' autocomplete="'.$n->autocomplete.'" '.$r_o.' '.$step.' '.$n->required.' for="'.$n->for.'" '.$d.' >
              </div>';
    }

    public static function textboxOnly($name,$options = [],$value = null,$input_only = false){
        $n = new __form2;
        $n->set($options);
        $r_o = '';
        $step = '';
        if(is_object($value)){
            $value = $value->$name;
        }
        $ext = '';

        if($n->is_multiple == 1){
            $ext = '[]';
        }
        $c_class = '';
        if($n->container_class != ''){
            $c_class = $n->container_class;
        }

        if($n->type == 'date'){
            $value = ($value != '') ? Carbon::parse($value)->format('Y-m-d') : '';
        }

        $r_o = ($n->readonly == 'readonly') ? 'readonly' : '';
        $step = ($n->step != '') ? 'step="'.$n->step.'"' : '';
        $id = ($n->id != '') ?  'id="'.$n->id.'"' : '';
        $tab_index = ($n->tab_index != '') ?  'tabindex="'.$n->tab_index.'"' : '';
        $title = ($n->title != '') ? '<i class="fa fa-question-circle" title="'.$n->title.'"></i>' : '';

        if($input_only == true){
            return '<input class="form-control '.$n->class.'" '.$id.' '.$tab_index.' name="'. $name .$ext.'" type="'.$n->type.'" value="'.$value.'" placeholder="'. $n->placeholder.'" '. $n->extra_attr .' autocomplete="'.$n->autocomplete.'" '.$r_o.' '.$step.' '.$n->required.'>
             ';
        }
        return '<div class=" '.$c_class.' col-md-'.$n->cols.' '.$name.'">
                <input for="'.$n->for.'" class="form-control single '.$n->class.'" '.$id.' '.$tab_index.' name="'. $name .$ext.'" type="'.$n->type.'" value="'.$value.'" placeholder="'. $n->placeholder.'" '. $n->extra_attr .' autocomplete="'.$n->autocomplete.'" '.$r_o.' '.$step.' '.$n->required.'>
              </div>';
    }



    public static function select($name,$options = [],$value = null){
        $n = new __form2;
        $n->set($options);
        if(is_object($value)){
            $value = $value->$name;
        }

        $ext = '';
        if($n->is_multiple == 1){
            $ext = '[]';
        }

        if ($options['options'] == 'year'){
            $past = 8;
            $future = 10;
            if(isset($options['past'])){
                $past = $options['past'];
            }
            if(isset($options['future'])){
                $future = $options['future'];
            }
            $options['options'] = self::yearsArray($past, $future);
            if($value == ''){
                $value = Carbon::now()->format('Y');
            }
        }
        $r_o = '';
        $r_o = ($n->readonly == 'readonly') ? 'readonly' : '';
        $id = ($n->id != '') ?  'id="'.$n->id.'"' : '';
        $opt_html = '';
        if(isset($options['options'])){
            if(is_array($options['options'])){
                foreach ($options['options'] as $key => $option){
                    if(is_array($option)){
                        $opt_html = $opt_html.'<optgroup label="'.$key.'">';
                        foreach ($option as $key2 => $option2){
                            $sel = '';
                            if($value == $key2){
                                $sel = 'selected';
                            }
                            $opt_html = $opt_html.'<option value="'.$key2.'" '.$sel.'>'.$option2.'</option>';
                        }
                    }else{
                        $sel = '';
                        if($value == $key){
                            $sel = 'selected';
                        }
                        $opt_html = $opt_html.'<option value="'.$key.'" '.$sel.'>'.$option.'</option>';
                    }
                }
            }
        }
        if($n->select2_preSelected == ''){
            $preSelected = '<option value="" selected>Select</option>';
        }else{
            $preSelected = '<option value="'.$value.'" selected>'.$n->select2_preSelected.'</option>';
        }

        return '<div class="form-group '.$n->container_class.' col-md-'.$n->cols .' '.$name.'">
                  <label for="'. $name .'">'. $n->label .'</label>
                  <select name="'. $name .$ext.'" '. $id .' class="form-control '.$n->class.'" '. $n->extra_attr .' '.$r_o.' '.$n->required.'>
                    '.$preSelected.'
                    '.$opt_html.'
                  </select>
                </div>';
    }


    public static function selectOnly($name,$options = [],$value = null){
        $n = new __form2;
        $n->set($options);
        if(is_object($value)){
            $value = $value->$name;
        }

        $ext = '';
        if($n->is_multiple == 1){
            $ext = '[]';
        }

        if ($options['options'] == 'year'){
            $past = 8;
            $future = 10;
            if(isset($options['past'])){
                $past = $options['past'];
            }
            if(isset($options['future'])){
                $future = $options['future'];
            }
            $options['options'] = self::yearsArray($past, $future);
            if($value == ''){
                $value = Carbon::now()->format('Y');
            }
        }
        $c_class = '';
        if($n->container_class != ''){
            $c_class = $n->container_class;
        }

        $r_o = '';
        $r_o = ($n->readonly == 'readonly') ? 'readonly' : '';
        $id = ($n->id != '') ?  'id="'.$n->id.'"' : '';
        $opt_html = '';


        if(isset($options['options'])){
            if(is_array($options['options'])){
                foreach ($options['options'] as $key => $option){
                    if(is_array($option)){
                        $opt_html = $opt_html.'<optgroup label="'.$key.'">';
                        foreach ($option as $key2 => $option2){
                            $sel = '';
                            if($value == $key2){
                                $sel = 'selected';
                            }
                            $opt_html = $opt_html.'<option value="'.$key2.'" '.$sel.'>'.$option2.'</option>';
                        }
                    }else{
                        $sel = '';
                        if($value == $key){
                            $sel = 'selected';
                        }
                        $opt_html = $opt_html.'<option value="'.$key.'" '.$sel.'>'.$option.'</option>';
                    }
                }
            }
        }

        if($n->select2_preSelected == ''){
            $preSelected = '<option value="" selected>Select</option>';
        }else{
            $preSelected = '<option value="'.$value.'" selected>'.$n->select2_preSelected.'</option>';
        }

        return '<div class=" '.$c_class.' col-md-'.$n->cols .' '.$name.'">
                <select for="'.$n->for.'" name="'. $name .$ext.'" '. $id .' class="form-control single '.$n->class.'" '. $n->extra_attr .' '.$r_o.' '.$n->required.'>
                    '.$preSelected.'
                    '.$opt_html.'
                  </select>
              </div>
                ';
    }

    public static function a_select($name,$options = [],$value = null){
        $n = new __form2;
        $n->set($options);
        $length = $n->cols;
        $label = $n->label;
        $array = (isset($options['options'])) ? $options['options'] : [];
        $attr = $n->extra_attr;
        $class = $n->class;
        if($class != null){
            $fg_class = "fg-".$class;
            $input_class = "input-".$class;
        }else{
            $fg_class = '';
            $input_class = '';
        }

        $options = '';
        foreach ($array as $option => $val) {
            if($value === $val){
                $options = $options.'<option value="'.$val.'" selected>'.$option.'</option>';
            }else{
                $options = $options.'<option value="'.$val.'">'.$option.'</option>';
            }
        }

        return '<div class="form-group col-md-'.$length.' '.$fg_class.'" id="fg-'.$name.'">
        <label for="is_menu">'.$label.'</label>
        <select name="'.$name.'" class="form-control '.$input_class.'" '.$attr.'">
          <option value="">Select</option>
          '.$options.'
        </select>  
      </div>';
    }


    public static function textarea($name, $options = [],$value = null){
        if(is_object($value)){
            $value = $value->$name;
        }


        $n = new __form2;
        $n->set($options);
        $id = ($n->id != '') ?  'id="'.$n->id.'"' : '';
        return '<div class="form-group col-md-'. $n->cols .' '. $name .'">
                <label for="'. $name .'">'. $n->label .'</label>
                <textarea class="form-control '.$n->class.'" '.$id.' name="'. $name .'" rows="'.$n->rows.'" '. $n->extra_attr .'>'. __sanitize::html_encode($value) .'</textarea>
              </div>';
    }

    public static function textareaOnly($name, $options = [],$value = null){
        if(is_object($value)){
            $value = $value->$name;
        }


        $n = new __form2;
        $n->set($options);
        $id = ($n->id != '') ?  'id="'.$n->id.'"' : '';
        return '
                <textarea class="form-control single '.$n->class.'" '.$id.' name="'. $name .'" rows="'.$n->rows.'" '. $n->extra_attr .'>'. __sanitize::html_encode($value) .'</textarea>
          ';
    }

    private static function yearsArray($past = 8, $future = 10){
        $years = [];
        $now_year = Carbon::now()->format('Y');
        for ( $x = $now_year - $past ; $x <= $now_year + $future; $x++){
            $years[$x] = $x;
        }
        return $years;
    }

    public function set($array){

        (!isset($array['class'])) ? $array['class']= '' : false;
        (!isset($array['cols'])) ? $array['cols']= '' : false;
        (!isset($array['label'])) ? $array['label']= '' : false;
        if(isset($array['placeholder'])){
            $array['placeholder'] = $array['placeholder'];
        }else{
            $array['placeholder'] =  str_replace(':','',$array['label']);
            $array['placeholder'] = str_replace('*','',$array['placeholder']);
        }
        (!isset($array['id'])) ? $array['id']= '' : false;
        (!isset($array['tab_index'])) ? $array['tab_index']= '' : false;
        (!isset($array['type'])) ? $array['type']= '' : false;
        (!isset($array['value'])) ? $array['value']= '' : false;
        (!isset($array['placeholder'])) ? $array['placeholder']= '' : false;
        (!isset($array['extra_attr'])) ? $array['extra_attr']= '' : false;
        (!isset($array['rows'])) ? $array['rows']= '' : false;
        (!isset($array['autocomplete'])) ? $array['autocomplete']= '' : false;
        (!isset($array['step'])) ? $array['step']= '' : false;
        (!isset($array['readonly'])) ? $array['readonly']= '' : false;
        (!isset($array['title'])) ? $array['title']= '' : false;
        (!isset($array['is_multiple'])) ? $array['is_multiple']= '' : false;
        (!isset($array['required'])) ? $array['required']= '' : false;
        (!isset($array['for'])) ? $array['for']= '' : false;
        (!isset($array['disabled'])) ? $array['disabled']= '' : false;
        (!isset($array['container_class'])) ? $array['container_class']= '' : false;
        ($array['type'] == '') ?  $array['type'] = 'text' : false;
        (!isset($array['select2_preSelected'])) ? $array['select2_preSelected']= '' : false;

        $this->class = $array['class'];
        $this->cols = $array['cols'];
        $this->label = $array['label'];
        $this->id = $array['id'];
        $this->tab_index = $array['tab_index'];
        $this->type = $array['type'];
        $this->value = $array['value'];
        $this->extra_attr = $array['extra_attr'];
        $this->placeholder = $array['placeholder'];
        $this->rows = $array['rows'];
        $this->autocomplete = $array['autocomplete'];
        $this->readonly = $array['readonly'];
        $this->step = $array['step'];
        $this->title = $array['title'];
        $this->is_multiple = $array['is_multiple'];
        $this->required = $array['required'];
        $this->for = $array['for'];
        $this->disabled = $array['disabled'];
        $this->container_class = $array['container_class'];
        $this->select2_preSelected = $array['select2_preSelected'];
    }
    public function get($array){
        return $this->name.' Hello';
    }
}