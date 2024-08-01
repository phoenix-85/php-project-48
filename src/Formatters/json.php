<?php

namespace Differ\Formatters\json;

function formatJSON(array $data): string
{
    return json_encode($data);
}
