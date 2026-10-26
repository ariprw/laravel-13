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

    'accepted' => 'Harus diterima',
    'accepted_if' => 'Harus diterima ketika :other adalah :value',
    'active_url' => 'Harus merupakan URL yang valid',
    'after' => 'Harus berupa tanggal setelah :date',
    'after_or_equal' => 'Harus berupa tanggal setelah atau sama dengan :date',
    'alpha' => 'Hanya boleh berisi huruf',
    'alpha_dash' => 'Hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah',
    'alpha_num' => 'Hanya boleh berisi huruf dan angka',
    'any_of' => 'Tidak valid',
    'array' => 'Harus berupa array',
    'ascii' => 'Hanya boleh berisi karakter alfanumerik dan simbol satu byte',
    'before' => 'Harus berupa tanggal sebelum :date',
    'before_or_equal' => 'Harus berupa tanggal sebelum atau sama dengan :date',
    'between' => [
        'array' => 'Harus memiliki antara :min dan :max item',
        'file' => 'Harus antara :min dan :max kilobyte',
        'numeric' => 'Harus antara :min dan :max',
        'string' => 'Harus antara :min dan :max karakter',
    ],
    'boolean' => 'Harus berupa true atau false',
    'can' => 'Mengandung nilai yang tidak sah',
    'confirmed' => 'Konfirmasi tidak cocok',
    'contains' => 'Kehilangan nilai yang diperlukan',
    'current_password' => 'Password yang dimasukkan salah',
    'date' => 'Harus berupa tanggal yang valid',
    'date_equals' => 'Harus berupa tanggal yang sama dengan :date',
    'date_format' => 'Harus sesuai dengan format :format',
    'decimal' => 'Harus memiliki :decimal tempat desimal',
    'declined' => 'Harus ditolak',
    'declined_if' => 'Harus ditolak ketika :other adalah :value',
    'different' => 'Dan :other harus berbeda',
    'digits' => 'Harus terdiri dari :digits digit',
    'digits_between' => 'Harus memiliki antara :min dan :max digit',
    'dimensions' => 'Memiliki dimensi gambar yang tidak valid',
    'distinct' => 'Memiliki nilai duplikat',
    'doesnt_end_with' => 'Harus tidak berakhir dengan salah satu dari berikut: :values',
    'doesnt_start_with' => 'Harus tidak dimulai dengan salah satu dari berikut: :values',
    'email' => 'Harus berupa alamat email yang valid',
    'ends_with' => 'Harus diakhiri dengan salah satu dari berikut: :values',
    'enum' => 'Yang dipilih tidak valid',
    'exists' => 'Yang dipilih tidak valid',
    'extensions' => 'Harus memiliki salah satu ekstensi berikut: :values',
    'file' => 'Harus berupa file',
    'filled' => 'Harus memiliki nilai',
    'gt' => [
        'array' => 'Harus memiliki lebih dari :value item',
        'file' => 'Harus lebih besar dari :value kilobyte',
        'numeric' => 'Harus lebih besar dari :value',
        'string' => 'Harus lebih panjang dari :value karakter',
    ],
    'gte' => [
        'array' => 'Harus memiliki :value item atau lebih',
        'file' => 'Harus lebih besar dari atau sama dengan :value kilobyte',
        'numeric' => 'Harus lebih besar dari atau sama dengan :value',
        'string' => 'Harus lebih panjang dari atau sama dengan :value karakter',
    ],
    'hex_color' => 'Harus berupa warna hexadecimal yang valid',
    'image' => 'Harus berupa gambar',
    'in' => 'Tidak valid',
    'in_array' => 'Harus ada dalam :other',
    'integer' => 'Harus berupa angka bulat',
    'ip' => 'Harus berupa alamat IP yang valid',
    'ipv4' => 'Harus berupa alamat IPv4 yang valid',
    'ipv6' => 'Harus berupa alamat IPv6 yang valid',
    'json' => 'Harus berupa string JSON yang valid',
    'list' => 'Harus berupa daftar',
    'lowercase' => 'Harus berupa huruf kecil',
    'lt' => [
        'array' => 'Harus memiliki kurang dari :value item',
        'file' => 'Harus kurang dari :value kilobyte',
        'numeric' => 'Harus kurang dari :value',
        'string' => 'Harus kurang dari :value karakter',
    ],
    'lte' => [
        'array' => 'Tidak boleh lebih dari :value item',
        'file' => 'Tidak boleh lebih dari :value kilobyte',
        'numeric' => 'Tidak boleh lebih dari :value',
        'string' => 'Tidak boleh lebih dari :value karakter',
    ],
    'mac_address' => 'Harus berupa alamat MAC yang valid',
    'max' => [
        'array' => 'Tidak boleh memiliki lebih dari :max item',
        'file' => 'Tidak boleh lebih dari :max kilobyte',
        'numeric' => 'Tidak boleh lebih dari :max',
        'string' => 'Tidak boleh lebih dari :max karakter',
    ],
    'max_digits' => 'Tidak boleh lebih dari :max digit',
    'mimes' => 'Harus berupa file dengan tipe: :values',
    'mimetypes' => 'Harus berupa file dengan tipe: :values',
    'min' => [
        'array' => 'Harus memiliki setidaknya :min item',
        'file' => 'Harus memiliki setidaknya :min kilobyte',
        'numeric' => 'Harus setidaknya :min',
        'string' => 'Harus setidaknya :min karakter',
    ],
    'min_digits' => 'Harus memiliki setidaknya :min digit',
    'missing' => 'Harus hilang',
    'missing_if' => 'Harus hilang ketika :other adalah :value',
    'missing_unless' => 'Harus hilang kecuali :other adalah :value',
    'missing_with' => 'Harus hilang ketika :values ada',
    'missing_with_all' => 'Harus hilang ketika :values ada',
    'multiple_of' => 'Harus merupakan kelipatan dari :value',
    'not_in' => 'Yang dipilih tidak valid',
    'not_regex' => 'Format Tidak valid',
    'numeric' => 'Harus berupa angka',
    'password' => [
        'letters' => 'Harus mengandung setidaknya satu huruf',
        'mixed' => 'Harus mengandung setidaknya satu huruf besar dan satu huruf kecil',
        'numbers' => 'Harus mengandung setidaknya satu angka',
        'symbols' => 'Harus mengandung setidaknya satu simbol',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Pilihlah :attribute yang berbeda',
    ],
    'present' => 'Harus hadir',
    'present_if' => 'Harus hadir ketika :other adalah :value',
    'present_unless' => 'Harus hadir kecuali :other adalah :value',
    'present_with' => 'Harus hadir ketika :values ada',
    'present_with_all' => 'Harus hadir ketika :values ada',
    'prohibited' => 'Dilarang',
    'prohibited_if' => 'Dilarang ketika :other adalah :value',
    'prohibited_if_accepted' => 'Dilarang ketika :other diterima',
    'prohibited_if_declined' => 'Dilarang ketika :other ditolak',
    'prohibited_unless' => 'Dilarang kecuali :other ada di dalam :values',
    'prohibits' => 'Melarang :other untuk hadir',
    'regex' => 'Format Tidak valid',
    'required' => 'Wajib diisi',
    'required_array_keys' => 'Harus mengandung entri untuk: :values',
    'required_if' => 'Wajib diisi ketika :other adalah :value',
    'required_if_accepted' => 'Wajib diisi ketika :other diterima',
    'required_if_declined' => 'Wajib diisi ketika :other ditolak',
    'required_unless' => 'Wajib diisi kecuali :other ada di dalam :values',
    'required_with' => 'Wajib diisi ketika :values ada',
    'required_with_all' => 'Wajib diisi ketika :values ada',
    'required_without' => 'Wajib diisi ketika :values tidak ada',
    'required_without_all' => 'Wajib diisi ketika tidak ada :values yang hadir',
    'same' => 'Harus cocok dengan :other',
    'size' => [
        'array' => 'Harus mengandung :size item',
        'file' => 'Harus berukuran :size kilobyte',
        'numeric' => 'Harus bernilai :size',
        'string' => 'Harus mengandung :size karakter',
    ],
    'starts_with' => 'Harus dimulai dengan salah satu dari berikut: :values',
    'string' => 'Harus berupa string',
    'timezone' => 'Harus berupa zona waktu yang valid',
    'unique' => 'Sudah ada',
    'uploaded' => 'Gagal diunggah',
    'uppercase' => 'Harus berupa huruf kapital',
    'url' => 'Harus berupa URL yang valid',
    'ulid' => 'Harus berupa ULID yang valid',
    'uuid' => 'Harus berupa UUID yang valid',

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
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
