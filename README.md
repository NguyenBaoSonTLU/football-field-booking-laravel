# PitchPerfect - Hệ thống đặt lịch sân bóng đá trực tuyến

Dự án đồ án tốt nghiệp của Nguyễn Bảo Sơn, xây dựng bằng Laravel 13, PHP 8.3+, MariaDB/MySQL, Blade, Bootstrap 5 và JavaScript.

## Chức năng chính

### Khách hàng
- Đăng ký, đăng nhập bằng email hoặc số điện thoại, quên mật khẩu.
- Xem danh sách và chi tiết sân bóng.
- Tìm kiếm theo tên, địa chỉ, khoảng giá, ngày và khung giờ.
- Tra cứu lịch trống và đặt một sân trong một khung giờ.
- Xem lịch sử, chi tiết đơn, đặt lại và hủy đơn hợp lệ.
- Cập nhật hồ sơ, ảnh đại diện, mật khẩu và xem thông báo.

### Quản trị viên
- Dashboard tổng quan sân, đơn đặt, người dùng và doanh thu.
- CRUD sân bóng, tải nhiều ảnh và chọn ảnh đại diện.
- Quản lý khung giờ.
- Xác nhận, hủy hoặc hoàn thành đơn đặt sân.
- Khóa/mở khóa tài khoản.
- Gửi thông báo và xem báo cáo theo khoảng ngày.

## Yêu cầu môi trường

- Windows + XAMPP.
- PHP 8.3 trở lên.
- MariaDB 10.4.32 hoặc MySQL tương thích.
- Composer 2.
- Node.js 20+ và NPM.

## Cài đặt nhanh

```bash
cd C:\xampp\htdocs
# Giải nén dự án thành thư mục san_bong
cd san_bong
composer install
copy .env.example .env
php artisan key:generate
```

Tạo database trong phpMyAdmin:

```sql
CREATE DATABASE san_bong CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Kiểm tra `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=san_bong
DB_USERNAME=root
DB_PASSWORD=
```

Khởi tạo dữ liệu:

```bash
php artisan migrate:fresh --seed
php artisan storage:link
npm install
npm run build
php artisan optimize:clear
```

Truy cập qua XAMPP:

```text
http://localhost/san_bong/public
```

Hoặc chạy máy chủ phát triển:

```bash
php artisan serve
npm run dev
```

## Tài khoản mẫu

- Admin: `admin@pitchperfect.vn` / `12345678`
- Customer: `an@example.com` / `12345678`

## Chống trùng lịch

Mỗi booking đang hiệu lực (`pending` hoặc `confirmed`) có `slot_lock_key` theo cấu trúc:

```text
football_field_id|booking_date|time_slot_id
```

Cột này có UNIQUE index. Việc tạo booking chạy trong transaction và bắt lỗi khóa duy nhất, vì vậy hai yêu cầu đồng thời cho cùng sân/ngày/khung giờ chỉ có tối đa một yêu cầu thành công.

## Kiểm tra dự án

```bash
php artisan route:list
php artisan migrate:fresh --seed
php artisan test
npm run build
```

## Giả định nghiệp vụ

- Hệ thống có hai vai trò: `customer` và `admin`.
- Mỗi đơn đặt đúng một sân, một ngày và một khung giờ.
- Không triển khai thanh toán trực tuyến; tổng tiền được thanh toán tại sân.
- Khách hàng chỉ tự hủy đơn `pending` trước giờ thi đấu ít nhất 24 giờ.
- Sân đã có lịch sử booking không bị xóa cứng mà chuyển sang `inactive`.
