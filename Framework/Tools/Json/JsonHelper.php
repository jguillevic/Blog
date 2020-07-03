<?php

namespace Framework\Tools\Json;

class JsonHelper
{
    public static function SerializeArrayToJson($array)
    {
        $json = "[";

        $i = 0;
        foreach ($array as $value)
        {
            if ($i > 0)
                $json .= ",";
            
            $json .= $value->SerializeToJson();

            $i++;
        }

        $json .= "]";

        return $json;
    }
}