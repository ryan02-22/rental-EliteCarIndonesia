<?php
/**
 * ============================================================================
 * WHATSAPP HELPER FUNCTIONS - EliteCar Indonesia
 * ============================================================================
 * 
 * File ini berisi fungsi-fungsi untuk integrasi WhatsApp:
 * - Generate WhatsApp link dengan pesan ter-format
 * - Template pesan untuk booking confirmation
 * - Template pesan untuk customer notification
 * - Template pesan untuk general inquiry
 * 
 * CARA PENGGUNAAN:
 * 
 * 1. Booking Confirmation (Admin ke EliteCar):
 *    $link = getWhatsAppBookingLink($booking);
 *    <a href="<?php echo $link; ?>" target="_blank">Send via WhatsApp</a>
 * 
 * 2. Customer Notification (Admin ke Customer):
 *    $link = getWhatsAppCustomerLink($booking, '081234567890');
 *    <a href="<?php echo $link; ?>" target="_blank">Notify Customer</a>
 * 
 * 3. General Inquiry (Customer ke EliteCar):
 *    $link = getWhatsAppInquiryLink('Toyota Avanza');
 *    <a href="<?php echo $link; ?>" target="_blank">Chat WhatsApp</a>
 * 
 * FITUR:
 * - Auto-format pesan dengan detail lengkap
 * - Support emoji untuk visual appeal
 * - URL encoding otomatis
 * - Professional message templates
 * - Indonesian language
 * 
 * CATATAN:
 * - Nomor WhatsApp EliteCar: +62-823-2864-9895
 * - Pesan otomatis ter-format dengan markdown WhatsApp
 * - Link akan membuka WhatsApp Web/App
 * 
 * ============================================================================
 */


/**
 * Generate WhatsApp link for booking confirmation
 * 
 * @param array $booking Booking data
 * @return string WhatsApp URL
 */
function getWhatsAppBookingLink($booking) {
    $phone = '6282328649895'; // EliteCar WhatsApp number
    
    $message = "Halo EliteCar Indonesia! 🚗\n\n";
    $message .= "*Konfirmasi Booking*\n";
    $message .= "━━━━━━━━━━━━━━━━\n\n";
    $message .= "📋 *Detail Booking:*\n";
    $message .= "• ID Booking: #{$booking['id']}\n";
    $message .= "• Nama: {$booking['renter_name']}\n";
    $message .= "• Email: {$booking['renter_email']}\n\n";
    $message .= "🚗 *Mobil:*\n";
    $message .= "• {$booking['car_name']}\n\n";
    $message .= "📅 *Periode Sewa:*\n";
    $message .= "• Mulai: " . date('d/m/Y', strtotime($booking['start_date'])) . "\n";
    $message .= "• Selesai: " . date('d/m/Y', strtotime($booking['end_date'])) . "\n";
    $message .= "• Durasi: {$booking['total_days']} hari\n\n";
    $message .= "💰 *Total Biaya:*\n";
    $message .= "• Rp " . number_format($booking['total_price'], 0, ',', '.') . "\n\n";
    $message .= "Status: *" . ucfirst($booking['status']) . "*\n\n";
    $message .= "Mohon konfirmasi booking ini. Terima kasih! 🙏";
    
    return 'https://wa.me/' . $phone . '?text=' . urlencode($message);
}

/**
 * Generate WhatsApp link for customer notification
 * 
 * @param array $booking Booking data
 * @param string $customerPhone Customer phone number
 * @return string WhatsApp URL
 */
function getWhatsAppCustomerLink($booking, $customerPhone) {
    // Remove leading 0 and add 62
    $phone = preg_replace('/^0/', '62', $customerPhone);
    
    $message = "Halo {$booking['renter_name']}! 👋\n\n";
    $message .= "Terima kasih telah melakukan booking di *EliteCar Indonesia*\n\n";
    $message .= "📋 *Detail Booking Anda:*\n";
    $message .= "━━━━━━━━━━━━━━━━\n\n";
    $message .= "🚗 Mobil: *{$booking['car_name']}*\n";
    $message .= "📅 Mulai: " . date('d/m/Y', strtotime($booking['start_date'])) . "\n";
    $message .= "📅 Selesai: " . date('d/m/Y', strtotime($booking['end_date'])) . "\n";
    $message .= "⏱️ Durasi: {$booking['total_days']} hari\n";
    $message .= "💰 Total: Rp " . number_format($booking['total_price'], 0, ',', '.') . "\n\n";
    $message .= "Status: *" . ucfirst($booking['status']) . "*\n\n";
    $message .= "Jika ada pertanyaan, silakan hubungi kami.\n\n";
    $message .= "Salam,\n*EliteCar Indonesia Team* 🚗";
    
    return 'https://wa.me/' . $phone . '?text=' . urlencode($message);
}

/**
 * Generate WhatsApp link for general inquiry
 * 
 * @param string $carName Optional car name
 * @return string WhatsApp URL
 */
function getWhatsAppInquiryLink($carName = null) {
    $phone = '6282328649895';
    
    $message = "Halo EliteCar Indonesia! 🚗\n\n";
    if ($carName) {
        $message .= "Saya tertarik dengan mobil *{$carName}*.\n\n";
    }
    $message .= "Saya ingin menanyakan informasi lebih lanjut tentang sewa mobil.\n\n";
    $message .= "Terima kasih! 🙏";
    
    return 'https://wa.me/' . $phone . '?text=' . urlencode($message);
}
