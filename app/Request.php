<?php

namespace App;

/**
 * @method getFiltered(string $key, mixed $default = null, string|int $filter = FILTER_DEFAULT, int|array $options = [])
 * @method Request forceReplace(array $data)
 * @method Request forceOffsetUnset(string $offset)
 */
class Request extends \MacropaySolutions\Framework\Http\Request
{
    use RequestTrait;
}
