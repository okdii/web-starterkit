<?php

namespace App\Models\BaseModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class BaseModel extends Model
{
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            /**
             * Get list of attribute
             */
            $attributeArr = count($model->fillable) > 0 ? $model->fillable : $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());

            // Checking field is exist in fillable array & no value passed from controller
            if (in_array("created_by", $attributeArr) && !$model->isDirty("created_by")) {
                $model->created_by = Auth::check() ? Auth::user()->id : null;
            }

            // Checking field is exist in fillable array & no value passed from controller
            if (in_array("created_at", $attributeArr) && !$model->isDirty("created_at")) {
                $model->created_at = Carbon::now();
            }
        });

        self::updating(function ($model) {
            /**
             * Get list of attribute
             */
            $attributeArr = count($model->fillable) > 0 ? $model->fillable : $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());

            // Checking field is exist in fillable array & no value passed from controller
            if (in_array("updated_by", $attributeArr) && !$model->isDirty("updated_by")) {
                $model->updated_by = Auth::check() ? Auth::user()->id : null;
            }

            // Checking field is exist in fillable array & no value passed from controller
            if (in_array("updated_at", $attributeArr) && !$model->isDirty("updated_at")) {
                $model->updated_at = Carbon::now();
            }
        });

        self::deleting(function ($model) {
            /**
             * Get list of attribute
             */
            // $attributeArr = count($model->fillable) > 0 ? $model->fillable : $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
            $columnArr = $model->getConnection()
                                ->getSchemaBuilder()
                                ->getColumnListing($model->getTable());
                
            // Checking field is exist in fillable array & no value passed from controller
            if (in_array("deleted_by", $columnArr) && !$model->isDirty('deleted_by')) {
                try {
                    $model->deleted_by = Auth::check() ? Auth::user()->id : null;
                } catch (\Throwable $th) {
                    //throw $th;
                    Log::error($th->getMessage());
                }
            }

            // Checking field is exist in fillable array & no value passed from controller
            if (in_array("deleted_at", $columnArr) && !$model->isDirty("deleted_at")) {
                try {
                    $model->deleted_at = Carbon::now();
                } catch (\Throwable $th) {
                    //throw $th;
                    Log::error($th->getMessage());
                }
            }

            $model->save();
        });
    }
}
