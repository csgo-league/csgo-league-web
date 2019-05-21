<?php

namespace B3none\League\Controllers;

class AssetsController extends BaseController
{
    /**
     * @param string $file
     */
    public function getAsset(string $file): void
    {
        $file = preg_replace('/\.(?=.*\.)/', '', $file);

        echo $file;
        die;

        if (file_exists(__DIR__ . '/../../web/build/' . $file)) {
            include(file_get_contents($file));
        }

        response()->redirect('/home');
    }
}
