<?php

namespace App\Utils\HumClient\Meta;

class Response
{
    public $code;
    public $data;
    public $error;

    public function __construct($data)
    {
        $this->code = $data['code'] ?? -1;
        $this->error = $data['error'] ?? null;
    }
    public static function Any($datakey, $dataClass, $data)
    {
        $res = new Response($data);
        if ($data['data'] !== null && $data['data'][$datakey] !== null) {
            foreach ($data['data'][$datakey] ?? [] as $d) {
                $res->data[] = new $dataClass($d ?? []);
            }
        }
        return $res;
    }

    public static function One($datakey, $dataClass, $data)
    {
        $res = new Response($data);
        if ($data['data'] !== null && $data['data'][$datakey] !== null) {
            $res->data = new $dataClass($data['data'][$datakey] ?? []);
        }
        return $res;
    }
}
