<?php

namespace Modules\Component\App\Enums;

enum FieldInputType: string
{
    case TEXT_INPUT = 'text';
    case NUMBER = 'number';
    case TOGGLE = 'toggle';
    case RADIO = 'radio';
    case GROUP_CONTACT = 'group_contact';
    case TEXTAREA = 'textarea';
    case DOMAIN_SELECTION = 'domain_selection';
    case PRICING_GROUP_SELECTION = 'pricing_group_selection';
    case PRICING_CATEGORY_SELECTION = 'pricing_category_selection';
    case FORM_SELECTION = 'form_selection';
    case PRODUCT_SELECTION = 'product_selection';
    case POST_SELECTION = 'post_selection';
    case POST_SELECTION_ONE = 'post_selection_one';
    case CATEGORY_SELECTION_PRODUCT = 'category_selection_product';
    case CATEGORY_SELECTION_POST = 'category_selection_post';
    case CATEGORY_SELECTION_PROCESS_DESIGN = 'category_selection_process_design';
    case IMAGE = "media";
    case IMAGES = "medias";
    case COLOR_PICKER = 'colorpicker';
    case SELECT = 'select';
    case KEY_VALUES = 'key_values';
    case PROCESS_REPEATER = 'process_repeater';
    case BUILDER = 'builder';
}
