<?php

namespace Wikichua\SimpleControlPanel\Traits;

trait Controller
{
    protected function filter($filter, $object)
    {
        if ($filter) {
            foreach ($filter as $key => $value) {
                if ($value != '' && $value != null) {
                    if (is_array($value)) {
                        $object->whereIn($key,array_values($value));
                    } else {
                        if (preg_match('/_id$/i', $key)) {
                            $object->where($key,$value);
                        } elseif (preg_match('/_at$/i', $key)) {
                            $object->whereBetween($key,[Carbon::parse($value),Carbon::parse($value)->addDay()]);
                        } elseif (preg_match('/_at_range$/i', $key)) {
                            $value = explode(' - ', $value);
                            $object->whereBetween(str_replace('_range', '', $key),[Carbon::parse($value[0]),Carbon::parse($value[1])->addDay()]);
                        } else {
                            $object->where($key,'like','%'.$value.'%');
                        }
                    }
                }
            }
            request()->flashOnly('filter');
            session()->put(compact('filter'));
        }
        return $object;
    }
    protected function exporting(Array $array,$filename = '')
    {
        $filename = $filename == ''? time():$filename;
        return \Excel::create($filename, function($excel) use($array, $filename) {
            $excel->sheet($filename, function($sheet)  use($array) {
                $sheet->fromArray($array);
            });
        })->download('xls');
    }
}