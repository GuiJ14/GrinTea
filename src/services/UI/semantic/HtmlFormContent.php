<?php

namespace grintea\services\UI\semantic;

use Ajax\semantic\html\collections\form\traits\TextFieldsTrait;
use Ajax\semantic\html\elements\HtmlInput;
use Ajax\semantic\html\collections\form\HtmlFormField;

class HtmlFormContent extends HtmlFormField {
    use TextFieldsTrait;

    public function __construct($identifier, $content, $label=NULL,$type="text",$value=NULL,$placeholder=NULL) {
        if(!isset($placeholder) && $type==="text")
            $placeholder=$label;
        parent::__construct("field-".$identifier, $content, $label);
        $this->_identifier=$identifier;
    }

    public function getDataField(){
        $field= $this->getField();
        if($field instanceof HtmlInput)
            $field=$field->getDataField();
        return $field;
    }
}
