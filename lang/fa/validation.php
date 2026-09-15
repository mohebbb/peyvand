<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines (Persian)
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used
    | by the validator class. Missing messages fall back to English.
    |
    */

    'accepted' => 'فیلد :attribute باید پذیرفته شده باشد.',
    'accepted_if' => 'فیلد :attribute باید پذیرفته شود وقتی که :other مقدارش :value است.',
    'active_url' => 'فیلد :attribute باید یک نشانی اینترنتی معتبر باشد.',
    'after' => 'تاریخ :attribute باید بعد از :date باشد.',
    'after_or_equal' => 'تاریخ :attribute باید برابر یا بعد از :date باشد.',
    'alpha' => 'فیلد :attribute فقط می‌تواند حروف انگلیسی را شامل شود.',
    'alpha_dash' => 'فیلد :attribute فقط می‌تواند شامل حروف انگلیسی، اعداد، خط تیره و زیرخط باشد.',
    'alpha_num' => 'فیلد :attribute فقط می‌تواند شامل حروف انگلیسی و اعداد باشد.',
    'array' => 'فیلد :attribute باید یک آرایه باشد.',
    'ascii' => 'فیلد :attribute فقط می‌تواند شامل کاراکترهای تک‌بایتی الفبایی-عددی باشد.',
    'before' => 'تاریخ :attribute باید قبل از :date باشد.',
    'before_or_equal' => 'تاریخ :attribute باید برابر یا قبل از :date باشد.',
    'between' => [
        'array' => 'تعداد آیتم‌های :attribute باید بین :min و :max باشد.',
        'file' => 'اندازه فایل :attribute باید بین :min و :max کیلوبایت باشد.',
        'numeric' => 'مقدار :attribute باید بین :min و :max باشد.',
        'string' => 'طول رشته :attribute باید بین :min و :max کاراکتر باشد.',
    ],
    'boolean' => 'فیلد :attribute فقط می‌تواند درست یا نادرست باشد.',
    'can' => 'فیلد :attribute شامل یک مقدار غیرمجاز است.',
    'confirmed' => 'تکرار :attribute با اصل آن مطابقت ندارد.',
    'contains' => 'فیلد :attribute باید شامل مقدار :values باشد.',
    'current_password' => 'گذرواژه فعلی نادرست است.',
    'date' => 'فیلد :attribute باید یک تاریخ معتبر باشد.',
    'date_equals' => 'تاریخ :attribute باید برابر :date باشد.',
    'date_format' => 'قالب تاریخ :attribute با :format مطابقت ندارد.',
    'decimal' => 'فیلد :attribute باید :decimal رقم اعشار داشته باشد.',
    'declined' => 'فیلد :attribute باید رد شود.',
    'declined_if' => 'فیلد :attribute باید رد شود وقتی که :other مقدارش :value است.',
    'different' => 'مقادیر :attribute و :other باید متفاوت باشند.',
    'digits' => 'طول فیلد :attribute باید :digits رقم باشد.',
    'digits_between' => 'طول فیلد :attribute باید بین :min و :max رقم باشد.',
    'dimensions' => 'ابعاد تصویر :attribute نامعتبر است.',
    'distinct' => 'فیلد :attribute مقدار تکراری دارد.',
    'doesnt_end_with' => 'رشته :attribute نباید با یکی از مقادیر زیر پایان یابد: :values.',
    'doesnt_start_with' => 'رشته :attribute نباید با یکی از مقادیر زیر آغاز شود: :values.',
    'email' => 'فیلد :attribute باید یک ایمیل معتبر باشد.',
    'ends_with' => 'رشته :attribute باید با یکی از مقادیر زیر پایان یابد: :values.',
    'enum' => 'مقدار انتخاب‌شده برای :attribute نامعتبر است.',
    'exists' => 'مقدار انتخاب‌شده برای :attribute نامعتبر است.',
    'extensions' => 'فیلد :attribute باید یکی از قالب‌های زیر را داشته باشد: :values.',
    'file' => 'فیلد :attribute باید یک فایل باشد.',
    'filled' => 'فیلد :attribute باید مقدار داشته باشد.',
    'gt' => [
        'array' => 'تعداد آیتم‌های :attribute باید بیشتر از :value باشد.',
        'file' => 'اندازه فایل :attribute باید بیشتر از :value کیلوبایت باشد.',
        'numeric' => 'مقدار :attribute باید بیشتر از :value باشد.',
        'string' => 'طول رشته :attribute باید بیشتر از :value کاراکتر باشد.',
    ],
    'gte' => [
        'array' => 'تعداد آیتم‌های :attribute باید :value یا بیشتر باشد.',
        'file' => 'اندازه فایل :attribute باید :value کیلوبایت یا بیشتر باشد.',
        'numeric' => 'مقدار :attribute باید :value یا بیشتر باشد.',
        'string' => 'طول رشته :attribute باید :value کاراکتر یا بیشتر باشد.',
    ],
    'hex_color' => 'فیلد :attribute باید یک رنگ هگز معتبر باشد.',
    'in' => 'مقدار انتخاب‌شده برای :attribute نامعتبر است.',
    'in_array' => 'فیلد :attribute باید در :other وجود داشته باشد.',
    'integer' => 'فیلد :attribute باید یک عدد صحیح باشد.',
    'ip' => 'فیلد :attribute باید یک نشانی IP معتبر باشد.',
    'ipv4' => 'فیلد :attribute باید یک نشانی IPv4 معتبر باشد.',
    'ipv6' => 'فیلد :attribute باید یک نشانی IPv6 معتبر باشد.',
    'json' => 'فیلد :attribute باید یک رشته JSON معتبر باشد.',
    'list' => 'فیلد :attribute باید یک فهرست باشد.',
    'lowercase' => 'فیلد :attribute باید فقط حروف کوچک باشد.',

    'lt' => [
        'array' => 'تعداد آیتم‌های :attribute باید کمتر از :value باشد.',
        'file' => 'اندازه فایل :attribute باید کمتر از :value کیلوبایت باشد.',
        'numeric' => 'مقدار :attribute باید کمتر از :value باشد.',
        'string' => 'طول رشته :attribute باید کمتر از :value کاراکتر باشد.',
    ],
    'lte' => [
        'array' => 'تعداد آیتم‌های :attribute باید :value یا کمتر باشد.',
        'file' => 'اندازه فایل :attribute باید :value کیلوبایت یا کمتر باشد.',
        'numeric' => 'مقدار :attribute باید :value یا کمتر باشد.',
        'string' => 'طول رشته :attribute باید :value کاراکتر یا کمتر باشد.',
    ],
    'mac_address' => 'فیلد :attribute باید یک نشانی MAC معتبر باشد.',
    'max' => [
        'array' => 'تعداد آیتم‌های :attribute نباید بیشتر از :max باشد.',
        'file' => 'اندازه فایل :attribute نباید بیشتر از :max کیلوبایت باشد.',
        'numeric' => 'مقدار :attribute نباید بیشتر از :max باشد.',
        'string' => 'طول رشته :attribute نباید بیشتر از :max کاراکتر باشد.',
    ],
    'max_digits' => 'فیلد :attribute نباید بیشتر از :max رقم داشته باشد.',
    'mimes' => 'فیلد :attribute باید یک فایل از نوع :values باشد.',
    'mimetypes' => 'فیلد :attribute باید یک فایل از نوع :values باشد.',
    'min' => [
        'array' => 'تعداد آیتم‌های :attribute نباید کمتر از :min باشد.',
        'file' => 'اندازه فایل :attribute نباید کمتر از :min کیلوبایت باشد.',
        'numeric' => 'مقدار :attribute نباید کمتر از :min باشد.',
        'string' => 'طول رشته :attribute نباید کمتر از :min کاراکتر باشد.',
    ],
    'min_digits' => 'فیلد :attribute باید حداقل :min رقم داشته باشد.',
    'missing' => 'فیلد :attribute باید وجود نداشته باشد.',
    'missing_if' => 'فیلد :attribute باید وجود نداشته باشد وقتی که :other مقدارش :value است.',
    'missing_unless' => 'فیلد :attribute باید وجود نداشته باشد مگر آنکه :other مقدارش :value باشد.',
    'missing_with' => 'فیلد :attribute باید وجود نداشته باشد وقتی که :values موجود است.',
    'missing_with_all' => 'فیلد :attribute باید وجود نداشته باشد وقتی که همه :values موجود هستند.',
    'multiple_of' => 'مقدار :attribute باید مضربی از :value باشد.',
    'not_in' => 'مقدار انتخاب‌شده برای :attribute نامعتبر است.',
    'not_regex' => 'قالب فیلد :attribute نامعتبر است.',
    'numeric' => 'فیلد :attribute باید یک عدد باشد.',
    'password' => [
        'letters' => 'فیلد :attribute باید حداقل یک حرف داشته باشد.',
        'mixed' => 'فیلد :attribute باید حداقل یک حرف بزرگ و یک حرف کوچک داشته باشد.',
        'numbers' => 'فیلد :attribute باید حداقل یک عدد داشته باشد.',
        'symbols' => 'فیلد :attribute باید حداقل یک نماد داشته باشد.',
        'uncompromised' => 'گذرواژه :attribute در نشت‌های داده ظاهر شده است. لطفاً گذرواژه متفاوتی انتخاب کنید.',
    ],
    'present' => 'فیلد :attribute باید موجود باشد.',
    'present_if' => 'فیلد :attribute باید موجود باشد وقتی که :other مقدارش :value است.',
    'present_unless' => 'فیلد :attribute باید موجود باشد مگر آنکه :other مقدارش :value باشد.',
    'present_with' => 'فیلد :attribute باید موجود باشد وقتی که :values موجود است.',
    'present_with_all' => 'فیلد :attribute باید موجود باشد وقتی که همه :values موجود هستند.',
    'prohibited' => 'فیلد :attribute باید مقدار نداشته باشد.',
    'prohibited_if' => 'فیلد :attribute باید مقدار نداشته باشد وقتی که :other مقدارش :value است.',
    'prohibited_if_accepted' => 'فیلد :attribute باید مقدار نداشته باشد وقتی که :other پذیرفته شده است.',
    'prohibited_if_declined' => 'فیلد :attribute باید مقدار نداشته باشد وقتی که :other رد شده است.',
    'prohibited_unless' => 'فیلد :attribute باید مقدار نداشته باشد مگر آنکه :other یکی از مقادیر :values باشد.',
    'prohibits' => 'فیلد :attribute اجازه وجود فیلد :other را نمی‌دهد.',
    'regex' => 'قالب فیلد :attribute نامعتبر است.',
    'required' => 'فیلد :attribute الزامی است.',
    'required_array_keys' => 'فیلد :attribute باید شامل کلیدهای زیر باشد: :values.',
    'required_if' => 'فیلد :attribute الزامی است وقتی که :other مقدارش :value است.',
    'required_if_accepted' => 'فیلد :attribute الزامی است وقتی که :other پذیرفته شده است.',
    'required_if_declined' => 'فیلد :attribute الزامی است وقتی که :other رد شده است.',
    'required_unless' => 'فیلد :attribute الزامی است مگر آنکه :other یکی از مقادیر :values باشد.',
    'required_with' => 'فیلد :attribute الزامی است وقتی که :values موجود است.',
    'required_with_all' => 'فیلد :attribute الزامی است وقتی که همه :values موجود هستند.',
    'required_without' => 'فیلد :attribute الزامی است وقتی که :values موجود نیست.',
    'required_without_all' => 'فیلد :attribute الزامی است وقتی که هیچ‌کدام از :values موجود نیستند.',
    'same' => 'مقادیر :attribute و :other باید یکسان باشند.',
    'size' => [
        'array' => 'تعداد آیتم‌های :attribute باید :size باشد.',
        'file' => 'اندازه فایل :attribute باید :size کیلوبایت باشد.',
        'numeric' => 'مقدار :attribute باید :size باشد.',
        'string' => 'طول رشته :attribute باید :size کاراکتر باشد.',
    ],
    'starts_with' => 'رشته :attribute باید با یکی از مقادیر زیر آغاز شود: :values.',
    'string' => 'فیلد :attribute باید یک رشته متنی باشد.',
    'timezone' => 'فیلد :attribute باید یک منطقه زمانی معتبر باشد.',
    'unique' => 'این :attribute قبلاً ثبت شده است.',
    'uploaded' => 'بارگذاری فایل :attribute ناموفق بود.',
    'uppercase' => 'فیلد :attribute باید فقط حروف بزرگ باشد.',
    'url' => 'قالب نشانی :attribute نامعتبر است.',
    'ulid' => 'فیلد :attribute باید یک ULID معتبر باشد.',
    'uuid' => 'فیلد :attribute باید یک UUID معتبر باشد.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Human-friendly names shown instead of raw database column names in
    | validation messages, for both the admin panel forms and the login
    | form.
    |
    */

    'attributes' => [
        'title' => 'عنوان',
        'destination_url' => 'نشانی مقصد',
        'is_active' => 'فعال',
        'short_code' => 'کد',
        'preview_url' => 'نشانی پیش‌نمایش',
        'format' => 'قالب',
        'error_correction' => 'تصحیح خطا',
        'size' => 'اندازه',
        'margin' => 'حاشیه امن',
        'foreground_color' => 'رنگ اصلی',
        'background_color' => 'رنگ پس‌زمینه',
        'transparent_background' => 'پس‌زمینه شفاف',
        'logo_enabled' => 'لوگو',
        'logo_path' => 'تصویر لوگو',
        'logo_size_percent' => 'اندازه لوگو',
        'save_as_default' => 'ذخیره به‌عنوان پیش‌فرض',
        'email' => 'ایمیل',
        'password' => 'گذرواژه',
        'name' => 'نام',
    ],

];
