<?php

declare(strict_types=1);

namespace Marshal\Database\Schema;

use Doctrine\DBAL\Types\Types;
use Laminas\Form\Element;
use Laminas\Form\Form;

final class ContentForm extends Form
{
    public static function create(Content $content): Form
    {
        $form = new self;
        foreach ($content->getProperties() as $property) {
            if ($property->isAutoIncrement()) {
                continue;
            }

            $form->add($form->getPropertyInput($property));
        }

        return $form;
    }

    private function getPropertyInput(Property $property): Element
    {
        $element = new Element($property->getName());
        $element->setLabel($property->getLabel());

        switch ($property->getDatabaseTypeName()) {
            case Types::INTEGER:
            case Types::SMALLINT:
            case Types::BIGINT:
                $element->setAttribute('type', 'number');
                break;
            
            case Types::DATETIME_IMMUTABLE:
            case Types::DATETIME_MUTABLE:
            case Types::DATETIMETZ_IMMUTABLE:
            case Types::DATETIMETZ_MUTABLE:
                $element->setAttribute('type', 'date');
                break;

            case Types::TEXT:
            default:
                $element->setAttribute('type', 'text');
                break;
        }
        $element->setValue($property->getValue());
        return $element;
    }
}
