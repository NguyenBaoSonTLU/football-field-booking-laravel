<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('about-project', function (): void {
    $this->info('PitchPerfect - Hệ thống đặt lịch sân bóng đá trực tuyến.');
})->purpose('Hiển thị thông tin dự án');
