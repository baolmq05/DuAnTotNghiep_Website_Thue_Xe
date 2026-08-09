<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Các dòng ngôn ngữ sau chứa các thông báo lỗi mặc định được sử dụng bởi
    | lớp kiểm tra dữ liệu (validation).
    |
    */

    'accepted'             => 'Trường :attribute phải được chấp nhận.',
    'accepted_if'          => 'Trường :attribute phải được chấp nhận khi :other là :value.',
    'active_url'           => 'Trường :attribute không phải là một URL hợp lệ.',
    'after'                => 'Trường :attribute phải là một ngày sau ngày :date.',
    'after_or_equal'       => 'Trường :attribute phải là một ngày sau hoặc bằng ngày :date.',
    'alpha'                => 'Trường :attribute chỉ có thể chứa các chữ cái.',
    'alpha_dash'           => 'Trường :attribute chỉ có thể chứa chữ cái, số, dấu gạch ngang và dấu gạch dưới.',
    'alpha_num'            => 'Trường :attribute chỉ có thể chứa chữ cái và số.',
    'array'                => 'Trường :attribute phải là một mảng.',
    'before'               => 'Trường :attribute phải là một ngày trước ngày :date.',
    'before_or_equal'      => 'Trường :attribute phải là một ngày trước hoặc bằng ngày :date.',
    'between'              => [
        'numeric' => 'Trường :attribute phải nằm trong khoảng :min đến :max.',
        'file'    => 'Dung lượng tệp :attribute phải từ :min đến :max kilobytes.',
        'string'  => 'Trường :attribute phải chứa từ :min đến :max ký tự.',
        'array'   => 'Trường :attribute phải có từ :min đến :max phần tử.',
    ],
    'boolean'              => 'Trường :attribute phải là true hoặc false.',
    'confirmed'            => 'Giá trị xác nhận trường :attribute không khớp.',
    'current_password'     => 'Mật khẩu hiện tại không chính xác.',
    'date'                 => 'Trường :attribute không phải là định dạng ngày hợp lệ.',
    'date_equals'          => 'Trường :attribute phải là một ngày bằng với :date.',
    'date_format'          => 'Trường :attribute không khớp với định dạng :format.',
    'declined'             => 'Trường :attribute phải bị từ chối.',
    'different'            => 'Trường :attribute và :other phải khác nhau.',
    'digits'               => 'Trường :attribute phải gồm :digits chữ số.',
    'digits_between'       => 'Trường :attribute phải nằm trong khoảng :min đến :max chữ số.',
    'dimensions'           => 'Tệp :attribute có kích thước hình ảnh không hợp lệ.',
    'distinct'             => 'Trường :attribute có giá trị trùng lặp.',
    'email'                => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
    'ends_with'            => 'Trường :attribute phải kết thúc bằng một trong các giá trị sau: :values.',
    'enum'                 => 'Giá trị đã chọn cho :attribute không hợp lệ.',
    'exists'               => 'Giá trị đã chọn cho :attribute không hợp lệ.',
    'file'                 => 'Trường :attribute phải là một tệp.',
    'filled'               => 'Trường :attribute không được để trống.',
    'gt'                   => [
        'numeric' => 'Trường :attribute phải lớn hơn :value.',
        'file'    => 'Dung lượng tệp :attribute phải lớn hơn :value kilobytes.',
        'string'  => 'Trường :attribute phải có nhiều hơn :value ký tự.',
        'array'   => 'Trường :attribute phải có nhiều hơn :value phần tử.',
    ],
    'gte'                  => [
        'numeric' => 'Trường :attribute phải lớn hơn hoặc bằng :value.',
        'file'    => 'Dung lượng tệp :attribute phải lớn hơn hoặc bằng :value kilobytes.',
        'string'  => 'Trường :attribute phải có ít nhất :value ký tự.',
        'array'   => 'Trường :attribute phải có ít nhất :value phần tử.',
    ],
    'image'                => 'Trường :attribute phải là một hình ảnh (JPEG, PNG, WebP, GIF, SVG).',
    'in'                   => 'Giá trị đã chọn cho :attribute không hợp lệ.',
    'in_array'             => 'Trường :attribute không tồn tại trong :other.',
    'integer'              => 'Trường :attribute phải là một số nguyên.',
    'ip'                   => 'Trường :attribute phải là một địa chỉ IP hợp lệ.',
    'ipv4'                 => 'Trường :attribute phải là một địa chỉ IPv4 hợp lệ.',
    'ipv6'                 => 'Trường :attribute phải là một địa chỉ IPv6 hợp lệ.',
    'json'                 => 'Trường :attribute phải là một chuỗi JSON hợp lệ.',
    'lt'                   => [
        'numeric' => 'Trường :attribute phải nhỏ hơn :value.',
        'file'    => 'Dung lượng tệp :attribute phải nhỏ hơn :value kilobytes.',
        'string'  => 'Trường :attribute phải có ít hơn :value ký tự.',
        'array'   => 'Trường :attribute phải có ít hơn :value phần tử.',
    ],
    'lte'                  => [
        'numeric' => 'Trường :attribute phải nhỏ hơn hoặc bằng :value.',
        'file'    => 'Dung lượng tệp :attribute phải nhỏ hơn hoặc bằng :value kilobytes.',
        'string'  => 'Trường :attribute không được nhiều hơn :value ký tự.',
        'array'   => 'Trường :attribute không được có nhiều hơn :value phần tử.',
    ],
    'max'                  => [
        'numeric' => 'Trường :attribute không được lớn hơn :max.',
        'file'    => 'Dung lượng tệp :attribute không được lớn hơn :max kilobytes.',
        'string'  => 'Trường :attribute không được vượt quá :max ký tự.',
        'array'   => 'Trường :attribute không được có nhiều hơn :max phần tử.',
    ],
    'mimes'                => 'Trường :attribute phải là tệp có định dạng: :values.',
    'mimetype'             => 'Trường :attribute phải là tệp có loại MIME: :values.',
    'mimetypes'            => 'Trường :attribute phải là tệp có định dạng: :values.',
    'min'                  => [
        'numeric' => 'Trường :attribute phải có tối thiểu :min.',
        'file'    => 'Dung lượng tệp :attribute phải tối thiểu :min kilobytes.',
        'string'  => 'Trường :attribute phải chứa ít nhất :min ký tự.',
        'array'   => 'Trường :attribute phải có tối thiểu :min phần tử.',
    ],
    'multiple_of'          => 'Trường :attribute phải là bội số của :value.',
    'not_in'               => 'Giá trị đã chọn cho :attribute không hợp lệ.',
    'not_regex'            => 'Định dạng trường :attribute không hợp lệ.',
    'numeric'              => 'Trường :attribute phải là một số.',
    'password'             => 'Mật khẩu không chính xác.',
    'present'              => 'Trường :attribute phải có mặt.',
    'prohibited'           => 'Trường :attribute bị cấm.',
    'prohibited_if'        => 'Trường :attribute bị cấm khi :other là :value.',
    'prohibited_unless'    => 'Trường :attribute bị cấm trừ khi :other nằm trong :values.',
    'regex'                => 'Định dạng trường :attribute không hợp lệ.',
    'required'             => 'Trường :attribute không được để trống.',
    'required_array_keys'  => 'Trường :attribute phải chứa các khóa: :values.',
    'required_if'          => 'Trường :attribute không được để trống khi :other là :value.',
    'required_unless'      => 'Trường :attribute không được để trống trừ khi :other nằm trong :values.',
    'required_with'        => 'Trường :attribute không được để trống khi một trong :values có mặt.',
    'required_with_all'    => 'Trường :attribute không được để trống khi tất cả :values có mặt.',
    'required_without'     => 'Trường :attribute không được để trống khi một trong :values không có mặt.',
    'required_without_all' => 'Trường :attribute không được để trống khi tất cả :values không có mặt.',
    'same'                 => 'Trường :attribute và :other phải khớp với nhau.',
    'size'                 => [
        'numeric' => 'Trường :attribute phải bằng :size.',
        'file'    => 'Dung lượng tệp :attribute phải bằng :size kilobytes.',
        'string'  => 'Trường :attribute phải chứa :size ký tự.',
        'array'   => 'Trường :attribute phải chứa :size phần tử.',
    ],
    'starts_with'          => 'Trường :attribute phải bắt đầu bằng một trong các giá trị sau: :values.',
    'string'               => 'Trường :attribute phải là một chuỗi ký tự.',
    'timezone'             => 'Trường :attribute phải là một múi giờ hợp lệ.',
    'unique'               => 'Giá trị trường :attribute đã tồn tại trên hệ thống.',
    'uploaded'             => 'Tải tệp :attribute lên thất bại.',
    'url'                  => 'Trường :attribute phải là một đường dẫn URL hợp lệ.',
    'uuid'                 => 'Trường :attribute phải là một mã UUID hợp lệ.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
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
    | Các tên gợi nhớ cho các trường dữ liệu để thông báo lỗi thân thiện hơn.
    |
    */

    'attributes' => [
        'title'            => 'Tiêu đề',
        'slug'             => 'Đường dẫn tĩnh (Slug)',
        'summary'          => 'Mô tả ngắn',
        'excerpt'          => 'Tóm tắt',
        'content'          => 'Nội dung',
        'thumbnail'        => 'Ảnh đại diện',
        'thumbnail_alt'    => 'Thẻ ALT mô tả ảnh đại diện',
        'seo_keywords'     => 'Từ khóa SEO',
        'post_category_id' => 'Danh mục bài viết',
        'status'           => 'Trạng thái',
        'name'             => 'Họ và tên',
        'email'            => 'Địa chỉ email',
        'password'         => 'Mật khẩu',
        'phone'            => 'Số điện thoại',
        'avatar'           => 'Ảnh đại diện',
        'license_plate'    => 'Biển số xe',
        'brand_name'       => 'Tên thương hiệu',
        'type_name'        => 'Tên dòng xe',
        'car_id'           => 'Mã xe',
        'user_id'          => 'Mã người dùng',
        'amount'           => 'Số tiền',
        'rating'           => 'Điểm đánh giá',
        'comment'          => 'Bình luận',
    ],

];
