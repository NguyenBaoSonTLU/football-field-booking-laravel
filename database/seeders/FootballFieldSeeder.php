<?php

namespace Database\Seeders;

use App\Enums\FootballFieldStatus;
use App\Models\FootballField;
use Illuminate\Database\Seeder;

class FootballFieldSeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            ['Sân bóng Chu Văn An', 'Tây Hồ, Hà Nội', 380000, '/images/field-1.svg'],
            ['Sân Mini Bách Khoa', 'Hai Bà Trưng, Hà Nội', 400000, '/images/field-2.svg'],
            ['Sân vận động Mỹ Đình II', 'Nam Từ Liêm, Hà Nội', 450000, '/images/field-3.svg'],
            ['Sân bóng Thành Đô', 'Cầu Giấy, Hà Nội', 500000, '/images/field-4.svg'],
            ['Sân bóng Kỳ Hòa 2', 'Thanh Xuân, Hà Nội', 450000, '/images/field-5.svg'],
            ['Sport Plus Center', 'Hoàng Mai, Hà Nội', 600000, '/images/field-6.svg'],
        ];

        foreach ($fields as $index => [$name, $address, $price, $image]) {
            $field = FootballField::query()->updateOrCreate(
                ['name' => $name],
                [
                    'address' => $address,
                    'description' => 'Sân bóng 7 người sử dụng mặt cỏ nhân tạo chất lượng cao, hệ thống chiếu sáng tốt và khu vực nghỉ ngơi thuận tiện. Phù hợp cho các trận giao hữu, luyện tập và giải đấu phong trào.',
                    'amenities' => ['Đèn chiếu sáng', 'Bãi đỗ xe', 'Phòng thay đồ', 'Nước uống'],
                    'price_per_hour' => $price,
                    'open_time' => '06:00:00',
                    'close_time' => '23:00:00',
                    'status' => FootballFieldStatus::ACTIVE,
                ]
            );

            $field->images()->updateOrCreate(
                ['image_url' => $image],
                ['is_main' => true]
            );

            $field->images()->updateOrCreate(
                ['image_url' => '/images/field-detail.svg'],
                ['is_main' => false]
            );
        }
    }
}
