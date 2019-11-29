### Sample CRUD CONFIG

    <?php

    return [

        // menu icon (fontawesome class) Icon link -> https://fontawesome.com/v4.7.0/icons/
        'icon' => 'fa-link',

        'need_seo' => false,

        // model attributes
        'attributes' => [

            'seq' => [
                'primary' => false,
                'migrations' => [
                    'integer:seq|nullable|default:1',
                ],
                'validations' => [
                    'create' => '',
                ],
                'datatable' => [
                    'title' => 'Order',
                    'data' => 'seq',
                ],
            ],

            'name' => [
                'primary' => false,
                'migrations' => [
                    'string:name|nullable',
                ],
                'validations' => [
                    'create' => 'required',
                    'update' => 'required',
                ],
                'datatable' => [
                    'title' => 'Title',
                    'data' => 'name',
                ],
                'exporttable' => 'name',
                'input' => [
                    'type' => 'text',
                ],
                'filter' => [
                    'type' => 'text',
                ],
            ],

            'image' => [
                'primary' => false,
                'migrations' => [
                    'text:image|nullable',
                ],
                'validations' => [
                    'create' => 'required',
                    'update' => 'required',
                ],
                'exporttable' => 'image',
                'input' => [
                    'type' => 'file',
                ],
            ],

            'images' => [
                'primary' => false,
                'migrations' => [
                    'json:images|nullable',
                ],
                'validations' => [
                    'create' => 'required',
                    'update' => 'required',
                ],
                'exporttable' => 'images',
                'casts' => 'array',
                'input' => [
                    'type' => 'file',
                    'multiple' => true,
                ],
            ],

            'tags' => [
                'primary' => false,
                'migrations' => [
                    'json:tags|nullable',
                ],
                'validations' => [
                    'create' => 'required',
                    'update' => 'required',
                ],
                'casts' => 'array',
                'input' => [
                    'tags' => true,
                    'type' => 'text',
                ],
            ],

            'datepicker' => [
                'primary' => false,
                'migrations' => [
                    'date:datepicker|nullable',
                ],
                'validations' => [
                    'create' => '',
                    'update' => '',
                ],
                'datatable' => [
                    'title' => 'datepicker',
                    'data' => 'datepicker',
                ],
                'exporttable' => 'datepicker',
                'input' => [
                    'type' => 'text',
                    'class' => 'datepicker'
                ],
                'filter' => [
                    'type' => 'date',
                ]
            ],

            'datetimepicker' => [
                'primary' => false,
                'migrations' => [
                    'datetime:datetimepicker|nullable',
                ],
                'validations' => [
                    'create' => '',
                    'update' => '',
                ],
                'datatable' => [
                    'title' => 'datetimepicker',
                    'data' => 'datetimepicker',
                ],
                'exporttable' => 'datetimepicker',
                'input' => [
                    'type' => 'text',
                    'class' => 'datetimepicker'
                ],
                'mutators' => [
                    // 'get' => 'return \Carbon\Carbon::parse($value);',
                    'set' => '$this->attributes[\'datetimepicker\'] = \Carbon\Carbon::parse($value);',
                ],
                'filter' => [
                    'type' => 'date_range',
                ]
            ],

            // daterangepicker daterangepicker_start everything to be done here
            //  mutator set to make sure value seperated to both daterangepicker_start & daterangepicker_end
            'daterangepicker_start' => [
                'primary' => false,
                'migrations' => [
                    'date:daterangepicker_start|nullable',
                ],
                'validations' => [
                    'create' => 'required',
                    'update' => 'required',
                ],
                'input' => [
                    'type' => 'text',
                    'class' => 'rangedatepicker'
                ],
                'mutators' => [
                    'set' => '
                        list($start,$end) = explode(\' - \',$value);
                        $this->attributes[\'daterangepicker_start\'] = \Carbon\Carbon::parse(trim($start));
                        $this->attributes[\'daterangepicker_end\'] = \Carbon\Carbon::parse(trim($end));
                    ',
                ]
            ],
            // daterangepicker end just to capture in db
            'daterangepicker_end' => [
                'primary' => false,
                'migrations' => [
                    'date:daterangepicker_end|nullable',
                ],
            ],
            // append only to mutating the combine of daterangepicker_start - daterangepicker_end 
            'daterangepicker' => [
                'primary' => false,
                'datatable' => [
                    'title' => 'daterangepicker',
                    'data' => 'daterangepicker',
                ],
                'exporttable' => 'daterangepicker',
                'appends' => 'daterangepicker',
                'mutators' => [
                    'get' => 'return $this->attributes[\'daterangepicker_start\'] ." - ".$this->attributes[\'daterangepicker_end\'];',
                ]
            ],

            'color' => [
                'primary' => false,
                'migrations' => [
                    'string:colors|nullable|default:A',
                ],
                'datatable' => [
                    'title' => 'Color',
                    'data' => 'color',
                ],
                'exporttable' => 'color',
                'input' => [
                    'type' => 'select',
                    'option_return' => 'array', // array / object
                    'options' => [
                        'Red' => 'Red',
                        'Green' => 'Green',
                        'Blue' => 'Blue',
                    ],
                    'multiple' => true,
                ],
                'filter' => [
                    'type' => 'select',
                ]
            ],

            'status' => [
                'primary' => false,
                'migrations' => [
                    'string:status|nullable|default:A',
                ],
                'datatable' => [
                    'title' => 'Status',
                    'data' => 'status_name',
                ],
                'exporttable' => 'status',
                'input' => [
                    'type' => 'select',
                    'option_return' => 'array', // array / object
                    'options' => [
                        'settings:{model_variable}_status' => [
                            'key' => 'val',
                        ],
                    ],
                ],
                'appends' => 'status_name',
                'mutators' => [
                    'get' => 'return settings(\'{model_variable}_status\')[$this->attributes[\'status\']];',
                ]
            ],

            'created_by' => [
                'primary' => false,
                'migrations' => [
                    'string:created_by|default:1|nullable',
                ],
                'datatable' => [
                    'title' => 'Created By',
                    'data' => 'creator.name',
                ],
                'exporttable' => 'created_by',
                'relationship' => [
                    'creator' => 'belongsTo:App\User,created_by,id',
                ],
            ],
            'updated_by' => [
                'primary' => false,
                'migrations' => [
                    'string:updated_by|default:1|nullable',
                ],
                'exporttable' => 'updated_by',
                'relationship' => [
                    'modifier' => 'belongsTo:App\User,updated_by,id',
                ],
            ],

        ],

    ];
