<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminProductBadgesController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'productbadge';
        $this->className = 'ProductBadge';
        $this->lang = true;
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->bootstrap = true;

        parent::__construct();

        $this->fields_list = [
            'id_badge' => [
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 25,
            ],
            'label' => [
                'title' => $this->l('Label'),
                'lang' => true,
            ],
            'bg_color' => [
                'title' => $this->l('Background Color'),
            ],
            'text_color' => [
                'title' => $this->l('Text Color'),
            ],
            'position' => [
                'title' => $this->l('Position'),
            ],
            'active' => [
                'title' => $this->l('Active'),
                'active' => 'status',
                'type' => 'bool',
                'align' => 'center',
            ],
        ];
    }

    public function renderForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Badge'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Label'),
                    'name' => 'label',
                    'lang' => true,
                    'required' => true,
                    'maxlength' => 50,
                ],
                [
                    'type' => 'color',
                    'label' => $this->l('Background Color'),
                    'name' => 'bg_color',
                    'required' => true,
                ],
                [
                    'type' => 'color',
                    'label' => $this->l('Text Color'),
                    'name' => 'text_color',
                    'required' => true,
                ],
                [
                    'type' => 'select',
                    'label' => $this->l('Position'),
                    'name' => 'position',
                    'options' => [
                        'query' => [
                            ['id' => 'top-left', 'name' => $this->l('Top Left')],
                            ['id' => 'top-right', 'name' => $this->l('Top Right')],
                        ],
                        'id' => 'id',
                        'name' => 'name',
                    ],
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Active'),
                    'name' => 'active',
                    'values' => [
                        ['id' => 'active_on', 'value' => 1, 'label' => $this->l('Yes')],
                        ['id' => 'active_off', 'value' => 0, 'label' => $this->l('No')],
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];

        return parent::renderForm();
    }
}