<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class ProductBadge extends ObjectModel
{
    public $id_badge;
    public $bg_color;
    public $text_color;
    public $position;
    public $active;
    public $label;

    public static $definition = [
        'table' => 'productbadge',
        'primary' => 'id_badge',
        'multilang' => true,
        'fields' => [
            'bg_color' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isColor',
                'required' => true,
                'size' => 7,
            ],
            'text_color' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isColor',
                'required' => true,
                'size' => 7,
            ],
            'position' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
            ],
            'active' => [
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool',
            ],
            'label' => [
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'required' => true,
                'size' => 50,
            ],
        ],
    ];
}