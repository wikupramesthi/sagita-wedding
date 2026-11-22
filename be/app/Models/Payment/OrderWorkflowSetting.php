<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;

class OrderWorkflowSetting extends Model
{
    protected $table = 'order_workflow_settings';

    protected $fillable = [
        'contract_template',
        'checklist_template',
        'updated_by',
    ];

    protected $casts = [
        'checklist_template' => 'array',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'contract_template' => '',
            'checklist_template' => [],
        ]);
    }
}
