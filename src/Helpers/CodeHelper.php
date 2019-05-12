<?php

namespace B3none\League\Helpers;


class CodeHelper
{
    /**
     * Generate code.
     *
     * @param int $length
     * @return string
     */
    public function generate(int $length = 5): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);

        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}