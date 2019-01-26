<?php
/**
 * Cấu hình cơ bản cho WordPress
 *
 * Trong quá trình cài đặt, file "wp-config.php" sẽ được tạo dựa trên nội dung 
 * mẫu của file này. Bạn không bắt buộc phải sử dụng giao diện web để cài đặt, 
 * chỉ cần lưu file này lại với tên "wp-config.php" và điền các thông tin cần thiết.
 *
 * File này chứa các thiết lập sau:
 *
 * * Thiết lập MySQL
 * * Các khóa bí mật
 * * Tiền tố cho các bảng database
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Thiết lập MySQL - Bạn có thể lấy các thông tin này từ host/server ** //
/** Tên database MySQL */
define('DB_NAME', 'wordpress-camunda');

/** Username của database */
define('DB_USER', 'root');

/** Mật khẩu của database */
define('DB_PASSWORD', '');

/** Hostname của database */
define('DB_HOST', 'localhost:3305');

/** Database charset sử dụng để tạo bảng database. */
define('DB_CHARSET', 'utf8mb4');

/** Kiểu database collate. Đừng thay đổi nếu không hiểu rõ. */
define('DB_COLLATE', '');

/**#@+
 * Khóa xác thực và salt.
 *
 * Thay đổi các giá trị dưới đây thành các khóa không trùng nhau!
 * Bạn có thể tạo ra các khóa này bằng công cụ
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Bạn có thể thay đổi chúng bất cứ lúc nào để vô hiệu hóa tất cả
 * các cookie hiện có. Điều này sẽ buộc tất cả người dùng phải đăng nhập lại.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'cT2P2tkp7pw!D=^U8*?jF|uiF<U>1X0xsRb6XLxU^-`$X?<xH# 8J>SAp1*I4g5U');
define('SECURE_AUTH_KEY',  'g`e2Q%^*]@^vyJUrcMJF5NInrTQga6_} Q<l53K;8{V-BsqXbd^oIeFHqyY;Bkh?');
define('LOGGED_IN_KEY',    'M5{PGTK5SmIOgCuX.tLwf8 ruDStcROpG*9{O{*$.Jlp!6|B~M<OuQ+$~sh)itc#');
define('NONCE_KEY',        'I(^DILwm#];>B(ESLV%A5t-2#(8vO]%p[h[+P=duuF=D>c!nqynTmtuRu(wL)3qH');
define('AUTH_SALT',        'BHX^3ZZW6$/*LZ@D>6E:cOl,+orqlFkEU=|f@ +nd[ksX^^N!s66Vu<lP`55w,aF');
define('SECURE_AUTH_SALT', ',T:pLh~KUB[!>#oUsqJC6AZ6BT4R!M`{Y^RJeE`Bf8j~awk|r+fhp7=e)G#E%r|n');
define('LOGGED_IN_SALT',   'gVPGGcvs@Yv60OS`,|jUzyDTo6;;ms.JZ}N}LKDRoA4GuHPj_9,Mf Zj%PMYXZ,B');
define('NONCE_SALT',       'LtzI*c/(*;N+eLBAJ/WTfG|-*]<Te&f&w;lpk^*$BkHgP%NrE)I|pf|(5}=+HWU`');

/**#@-*/

/**
 * Tiền tố cho bảng database.
 *
 * Đặt tiền tố cho bảng giúp bạn có thể cài nhiều site WordPress vào cùng một database.
 * Chỉ sử dụng số, ký tự và dấu gạch dưới!
 */
$table_prefix  = 'wp_';

/**
 * Dành cho developer: Chế độ debug.
 *
 * Thay đổi hằng số này thành true sẽ làm hiện lên các thông báo trong quá trình phát triển.
 * Chúng tôi khuyến cáo các developer sử dụng WP_DEBUG trong quá trình phát triển plugin và theme.
 *
 * Để có thông tin về các hằng số khác có thể sử dụng khi debug, hãy xem tại Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Đó là tất cả thiết lập, ngưng sửa từ phần này trở xuống. Chúc bạn viết blog vui vẻ. */

/** Đường dẫn tuyệt đối đến thư mục cài đặt WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Thiết lập biến và include file. */
require_once(ABSPATH . 'wp-settings.php');
