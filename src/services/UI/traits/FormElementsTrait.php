<?php

namespace grintea\services\UI\traits;

use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\semantic\html\collections\form\HtmlFormField;
use Ajax\semantic\html\collections\form\HtmlFormInput;
use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\semantic\html\elements\HtmlInput;
use grintea\services\semantic\HtmlFormContent;
use grintea\services\semantic\HtmlFormPassword;

trait FormElementsTrait{

    public function passwordInputField(string $idAndName, string $placeholder, array $rules = []){
        $passwordInput = $this->iconInput($idAndName, "key", "password", null, $placeholder);
        $passwordInput->addClass('action');
        $div = new HtmlBsDoubleElement("div-action-$idAndName",'div');
        $div->setClass(["ui","icon","button","toggleInputVisibility"]);
        $div->addContent(new HtmlIcon("toggleIcon-$idAndName", 'eye'));
        $passwordInput->addContent($div);
        return (new HtmlFormContent("$idAndName", $passwordInput, $placeholder))->addRules($rules);
    }

    public function iconInputField(string $idAndName, string $icon, string $type = "text", string $value = null, string $placeholder = "", array $rules = []){
        $input = $this->iconInput($idAndName, $icon, $type, $value, $placeholder);
        return (new HtmlFormContent("$idAndName", $input, $placeholder))->addRules($rules);
    }

    private function iconInput(string $idAndName, string $icon, string $type = "text", string $value = null, string $placeholder = ""){
        $input = new HtmlInput($idAndName, $type, $value, $placeholder);
        $input->setClass(["ui","left","icon","input"]);
        $input->addContent(new HtmlIcon("icon-$idAndName", $icon));
        return $input;
    }

}