<?php
/**
 * Fungsi untuk mengirim pesan ke Telegram via Bot API
 *
 * @param string $bot_token Token API Bot Anda
 * @param string $chat_id ID Telegram pengguna (penerima)
 * @param string $token Token 6 digit yang akan dikirim
 * @return string Hasil dari eksekusi cURL (bisa untuk debug)
 */
function kirim_token_telegram($bot_token, $chat_id, $token) {
    
    // 1. Siapkan Pesan (Kita gunakan mode HTML untuk formatting)
    $message = "Halo!\n\n";
    $message .= "Kode token Anda untuk login adalah:\n";
    $message .= "<b>" . $token . "</b>\n\n"; // <b>...</b> untuk cetak tebal
    $message .= "Token ini berlaku selama 5 menit. Jangan berikan kode ini kepada siapa pun.";
    
    // 2. Siapkan URL API Telegram
    $api_url = "https://api.telegram.org/bot" . $bot_token . "/sendMessage";

    // 3. Siapkan parameter yang akan dikirim
    $params = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'HTML' // Aktifkan mode HTML
    ];

    // 4. Bangun URL lengkap dengan parameter
    $url = $api_url . '?' . http_build_query($params);

    // 5. Gunakan cURL untuk mengirim request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // (Opsional) Nonaktifkan verifikasi SSL jika di localhost XAMPP
    // Kadang cURL di XAMPP gagal karena masalah sertifikat SSL
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result; // Mengembalikan balasan dari Telegram
}
?>
