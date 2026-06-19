<?php

declare(strict_types=1);

namespace Marshal\Platform\Web\TemplateRenderer\Twig;

use Laminas\Form\Form;
use Marshal\Database\Schema\Content;
use Marshal\Database\Schema\ContentForm;
use Marshal\Database\Schema\ContentManager;

final class FormExtension
{
    public function contentForm(string $identifier): string
    {
        $content = ContentManager::get($identifier);
        $form = ContentForm::create($content);
        return $this->drawContentForm($content, $form);
    }

    public function form(string $identifier): string
    {
        //
        return "a form";
    }

    private function drawContentForm(Content $content, Form $form): string
    {
        $dom = \Dom\HTMLDocument::createEmpty();
        $formEl = $dom->createElement('form');
        $formAttr = [
            "action" => "",
            "method" => "POST",
        ];
        foreach ($formAttr as $key => $value) {
            $formEl->setAttribute($key, $value);
        }

        foreach ($form->getElements() as $element) {
            // create the input group
            $group = $dom->createElement('div');
            $group->setAttribute('class', 'form-group');

            // create the input label
            $label = $dom->createElement('label');
            $label->textContent = $element->getLabel();

            // create the input
            $input = $dom->createElement('input');

            // set input attributes
            $input->setAttribute('class', 'form-input');
            foreach ($element->getAttributes() as $key => $value) {
                $input->setAttribute($key, $value);
            }
            
            // append the label and input to the group
            $group->appendChild($label);
            $group->appendChild($input);

            // append the group to the form
            $formEl->appendChild($group);
        }
        
        $dom->appendChild($formEl);
        return $dom->saveHtml();
    }
}
