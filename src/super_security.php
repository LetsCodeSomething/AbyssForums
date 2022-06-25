<?php

function compare_hashs(string $hash1, string $hash2): bool
{
    $result = true;

    for($i = 0; $i < 32; $i++)
    {
        if($hash1[$i] != $hash2[$i])
        {
            $result = false;
        }
    }

    $randInt = rand(10000, 90000);
    usleep($randInt);

    return $result;
}