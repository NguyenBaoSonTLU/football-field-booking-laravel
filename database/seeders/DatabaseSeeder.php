<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TimeSlotSeeder::class,
            AdminUserSeeder::class,
            FootballFieldSeeder::class,
        ]);

        $customers = [
            ['Nguyễn Văn An', 'an@example.com', '0901234567'],
            ['Trần Minh Tuấn', 'tuan@example.com', '0901234568'],
            ['Lê Quốc Huy', 'huy@example.com', '0901234569'],
            ['Phạm Minh Anh', 'minhanh@example.com', '0901234570'],
            ['Đỗ Hoàng Nam', 'nam@example.com', '0901234571'],
        ];

        foreach ($customers as [$name, $email, $phone]) {
            User::query()->updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'phone' => $phone,
                    'password' => Hash::make('12345678'),
                    'address' => 'Hà Nội',
                    'role' => UserRole::CUSTOMER,
                    'status' => UserStatus::ACTIVE,
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->call(BookingSeeder::class);
    }
}
