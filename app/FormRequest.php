<?php

namespace App;

/**
 * Use this by uncommenting \App\Applicaton::fireResolvingCallbacks
 */
class FormRequest extends \Illuminate\Http\FormRequest
{
    use \MacropaySolutions\Framework\Http\RequestTrait;
    use RequestTrait;
}
