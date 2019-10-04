<?php

return [

    // menu icon (fontawesome class)
    'icon' => 'fa-link',

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
        ],

        /* Add on begins here */

        'content' => [
            'primary' => false,
            'migrations' => [
                'string:content|nullable',
            ],
            'validations' => [
                'create' => 'required',
                'update' => 'required',
            ],
            'datatable' => [
                'title' => 'Content',
                'data' => 'content',
            ],
            'exporttable' => 'content',
            'input' => [
                'type' => 'textarea',
                'class' => 'summernote',
            ],
        ],

        'testdate' => [
            'primary' => false,
            'migrations' => [
                'datetime:testdate|nullable',
            ],
            'validations' => [
                'create' => '',
                'update' => '',
            ],
            'datatable' => [
                'title' => 'date',
                'data' => 'testdate',
            ],
            'exporttable' => 'testdate',
            'input' => [
                'type' => 'text',
                'class' => 'datepicker'
            ],
            'casts' => 'datetime:Y-m-d',
            'mutators' => [
                // 'get' => 'return \Carbon\Carbon::parse($value);',
                'set' => '$this->attributes[\'testdate\'] = \Carbon\Carbon::parse($value);',
            ]
        ],

        'testdaterange_start' => [
            'primary' => false,
            'migrations' => [
                'datetime:testdaterange_start|nullable',
            ],
            'validations' => [
                'create' => '',
                'update' => '',
            ],
            // 'datatable' => [
            //     'title' => 'date',
            //     'data' => 'testdaterange_start',
            // ],
            // 'exporttable' => 'testdaterange_start',
            'input' => [
                'type' => 'text',
                'class' => 'rangedatepicker'
            ],
            'casts' => 'datetime:Y-m-d',
            'mutators' => [
                'get' => 'return $this->attributes[\'testdaterange_start\'] ." - ".$this->attributes[\'testdaterange_end\'];',
                'set' => '
                    list($start,$end) = explode(\' - \',$value);
                    $this->attributes[\'testdaterange_start\'] = \Carbon\Carbon::parse(trim($start));
                    $this->attributes[\'testdaterange_end\'] = \Carbon\Carbon::parse(trim($end));
                ',
            ]
        ],

        'testdaterange_end' => [
            'primary' => false,
            'migrations' => [
                'datetime:testdaterange_end|nullable',
            ],
            // 'validations' => [
            //     'create' => '',
            //     'update' => '',
            // ],
            // 'datatable' => [
            //     'title' => 'date',
            //     'data' => 'testdaterange_end',
            // ],
            // 'exporttable' => 'testdaterange_end',
            // 'input' => [
            //     'type' => 'text',
            //     'class' => 'rangedatepicker'
            // ],
            'casts' => 'datetime:Y-m-d',
            'mutators' => [
                'get' => 'return $this->attributes[\'testdaterange_start\'] ." - ".$this->attributes[\'testdaterange_end\'];',
                'set' => '
                    list($start,$end) = explode(\' - \',$value);
                    $this->attributes[\'testdaterange_start\'] = \Carbon\Carbon::parse(trim($start));
                    $this->attributes[\'testdaterange_end\'] = \Carbon\Carbon::parse(trim($end));
                ',
            ]
        ],

        'testdaterange' => [
            'primary' => false,
            // 'migrations' => [
            //     'datetime:testdaterange_start|nullable',
            //     'datetime:testdaterange_end|nullable',
            // ],
            // 'validations' => [
            //     'create' => '',
            //     'update' => '',
            // ],
            'datatable' => [
                'title' => 'date',
                'data' => 'testdaterange',
            ],
            // 'exporttable' => 'testdaterange',
            // 'input' => [
            //     'type' => 'text',
            //     'class' => 'rangedatepicker'
            // ],
            'appends' => true,
            'mutators' => [
                'get' => 'return $this->attributes[\'testdaterange_start\'] ." - ".$this->attributes[\'testdaterange_end\'];',
                
            ]
        ],

        'metas' => [
            'primary' => false,
            'migrations' => [
                'json:metas|nullable',
            ],
            'validations' => [
                'create' => '',
                'update' => '',
            ],
            'input' => [
                'type' => 'text',
                'tags' => true,
            ],
            'casts' => 'array'
        ],

        /* Add on stop here */

        'status' => [
            'primary' => false,
            'migrations' => [
                'string:status|nullable|default:A',
            ],
            'datatable' => [
                'title' => 'Status',
                'data' => 'status',
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