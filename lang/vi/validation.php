<?php

return [
    'required' => 'Trường :attribute là bắt buộc.',
    'email' => ':attribute phải là địa chỉ email hợp lệ.',
    'unique' => ':attribute đã tồn tại.',
    'confirmed' => 'Xác nhận :attribute không khớp.',
    'after' => ':attribute phải sau :date.',
    'after_or_equal' => ':attribute phải từ :date trở đi.',
    'date_format' => ':attribute không đúng định dạng :format.',
    'numeric' => ':attribute phải là số.',
    'min' => ['numeric' => ':attribute phải từ :min trở lên.', 'string' => ':attribute phải có ít nhất :min ký tự.'],
    'max' => ['numeric' => ':attribute không được lớn hơn :max.', 'string' => ':attribute không được vượt quá :max ký tự.', 'file' => ':attribute không được vượt quá :max KB.'],
    'image' => ':attribute phải là tệp hình ảnh.',
    'mimes' => ':attribute phải thuộc định dạng: :values.',
    'attributes' => [
        'name' => 'họ tên', 'email' => 'email', 'phone' => 'số điện thoại', 'password' => 'mật khẩu',
        'booking_date' => 'ngày đặt sân', 'time_slot_id' => 'khung giờ', 'price_per_hour' => 'giá sân',
        'open_time' => 'giờ mở cửa', 'close_time' => 'giờ đóng cửa', 'status' => 'trạng thái',
    ],
];
