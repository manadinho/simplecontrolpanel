<?php

namespace Wikichua\Simplecontrolpanel\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use L5Swagger\Exceptions\L5SwaggerException;
use L5Swagger\Generator;

class SwaggerController extends \L5Swagger\Http\Controllers\SwaggerController
{
    public function docs(string $file = null)
    {
        $extension = 'json';
        $targetFile = config('l5-swagger.paths.docs_json', 'api-docs.json');

        if (! is_null($file)) {
            $targetFile = $file;
            $extension = explode('.', $file)[1];
        }

        $filePath = config('l5-swagger.paths.docs').'/'.$targetFile;

        if (!File::exists($filePath)) {
            abort(
                404,
                sprintf(
                    'Unable to locate "%s"',
                    $filePath
                )
            );
        }

        $content = File::get($filePath);

        if ($extension === 'yaml') {
            return Response::make($content, 200, [
                'Content-Type' => 'application/yaml',
                'Content-Disposition' => 'inline',
            ]);
        }

        return Response::make($content, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}