<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'El :attribute debe ser aceptado.',
    'active_url'           => 'El :attribute no es una URL válida.',
    'after'                => 'El :attribute debe ser una fecha posterior a: fecha.',
    'after_or_equal'       => 'El :attribute debe ser una fecha posterior o igual a :fecha.',
    'alpha'                => 'El :attribute solo puede contener letra.',
    'alpha_dash'           => 'El :attribute solo puede contener letras, números y guiones.',
    'alpha_num'            => 'El :attribute solo puede contener letras y números.',
    'array'                => 'El :attribute debe ser una matriz.',
    'before'               => 'El :attribute debe ser una fecha anterior a la fecha.',
    'before_or_equal'      => 'El :attribute debe ser una fecha anterior o igual a: fecha.',
    'between'              => [
        'numeric' => 'El :attribute debe estar entre: min y :max.',
        'file'    => 'El :attribute debe estar entre: min y :max kilobytes.',
        'string'  => 'El :attribute debe estar entre: min y :max caracteres.',
        'array'   => 'El :attribute debe tener entre: min y :max elementos.',
    ],
    'boolean'              => 'El :attribute de campo debe ser verdadero o falso.',
    'confirmed'            => 'El :attribute de confirmación no coincide.',
    'date'                 => 'El :attribute no es una fecha válida.',
    'date_format'          => 'El :attribute no coincide con el formato: formato.',
    'different'            => 'El :attribute y :otro deben ser diferentes',
    'digits'               => 'El :attribute debe ser: dígitos dígitos.',
    'digits_between'       => 'El :attribute debe estar entre: min y: max dígitos.',
    'dimensions'           => 'El :attribute tiene dimensiones de imagen no válidas.',
    'distinct'             => 'El :attribute de campo tiene un valor duplicado.',
    'email'                => 'El :attribute debe ser una dirección de correo electrónico válida.',
    'exists'               => 'El :attribute seleccionado es invalido.',
    'file'                 => 'El :attribute debe ser un archivo',
    'filled'               => 'El :attribute de campo es obligatorio.',
    'image'                => 'El :attribute debe ser una imagen.',
    'in'                   => 'El :attribute seleccionado no es válido.',
    'in_array'             => 'El :attribute de campo no existe en: otro.',
    'integer'              => 'El :attribute debe ser un número entero.',
    'ip'                   => 'El :attribute debe ser una dirección IP válida.',
    'json'                 => 'El :attribute debe ser una cadena JSON válida.',
    'max'                  => [
        'numeric' => 'El :attribute no puede ser mayor que :max.',
        'file'    => 'El :attribute no puede ser mayor que :máximo kilobytes.',
        'string'  => 'El :attribute no puede ser mayor que :máximo de caracteres.',
        'array'   => 'El :attribute no puede tener más de :elementos máximos.',
    ],
    'mimes'                => 'El: attribute debe ser un archivo de tipo: :valores.',
    'mimetypes'            => 'El: attribute debe ser un archivo de tipo: :valores.',
    'min'                  => [
        'numeric' => 'El :attribute debe ser al menos: min.',
        'file'    => 'El :attribute debe tener al menos: min kilobytes.',
        'string'  => 'El :attribute debe tener al menos: min caracteres.',
        'array'   => 'El :attribute debe tener al menos: elementos mínimos.',
    ],
    'not_in'               => 'El :attribute seleccionado no es válido.',
    'numeric'              => 'El :attribute debe ser un número.',
    'present'              => 'El :attribute de campo debe estar presente.',
    'regex'                => 'El :attribute de formato no es válido.',
    'required'             => 'El :attribute de campo es obligatorio.',
    'required_if'          => 'El :attribute de campo es obligatorio cuando :otro es :valor.',
    'required_unless'      => 'El :attribute de campo es obligatorio a menos que :otro esté en :valores.',
    'required_with'        => 'El :attribute de campo es obligatorio cuando :valores está presente.',
    'required_with_all'    => 'El :attribute de campo es obligatorio cuando :valores está presente.',
    'required_without'     => 'El :attribute de campo es obligatorio cuando :los valores no están presentes.',
    'required_without_all' => 'El :attribute de campo es obligatorio cuando no hay ninguno de :valores presentes.',
    'same'                 => 'El :attribute y :otro debe coincidir.',
    'size'                 => [
        'numeric' => 'El :attribute debe ser :tamaño.',
        'file'    => 'El :attribute debe ser :tamaño kilobytes.',
        'string'  => 'El :attribute debe ser :tamaño caracteres.',
        'array'   => 'El :attribute debe contener :artículos de tamaño.',
    ],
    'string'               => 'El :attribute debe ser una cadena.',
    'timezone'             => 'El :attribute debe ser una zona válida.',
    'unique'               => 'El :attribute ya se ha tomado.',
    'uploaded'             => 'El :attribute no se pudo cargar.',
    'url'                  => 'El :attribute de formato no es válido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'Mensaje personalizado',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
