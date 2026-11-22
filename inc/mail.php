<?php
/**
 * E-maily: prehľadné HTML s textovou verziou (multipart/alternative).
 * Texty (predmety, úvody) sa upravujú v administrácii: Texty webu → Celý web → E-maily.
 */

/** Adresa koreňa webu (pre logo a odkazy v e-maile). */
function site_url(): string {
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    if (preg_match('~/(api|admin)$~', $dir)) $dir = dirname($dir);
    $dir = ($dir === '/' || $dir === '.' || $dir === '\\') ? '' : rtrim($dir, '/');
    return ($https ? 'https://' : 'http://') . $host . $dir;
}

/** Text bez značkovania (**tučné**, *kurzíva*) – pre textovú verziu e-mailu. */
function mail_plain(string $s): string {
    return preg_replace(['/\*\*(.+?)\*\*/su', '/(?<![*\w])\*([^*\n]+?)\*(?![*\w])/u'], ['$1', '$1'], $s);
}

/** Text z registra → odseky HTML so štýlmi pre e-mail. */
function mail_html_text(string $text): string {
    $h = rich($text);
    return str_replace(['<p>', '<h2>', '</h2>'], ['<p style="margin:0 0 14px;line-height:1.6;color:#1d2f44;font-size:15px">', '<h2 style="margin:18px 0 8px;font-size:18px;color:#12304f">', '</h2>'], $h);
}

/** Karta s riadkami „popis: hodnota“ → [html, text]. */
function mail_card(array $rows): array {
    $html = '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:6px 0 20px;background:#f5f9fd;border:1px solid #d4e5f3;border-radius:10px"><tr><td style="padding:14px 18px">';
    $text = '';
    foreach ($rows as $label => $value) {
        if ($value === '' || $value === null) continue;
        $html .= '<div style="padding:5px 0;font-size:15px;line-height:1.5;color:#1d2f44"><span style="color:#5a6b7d">' . e($label) . ':</span> <b>' . nl2br(e((string)$value)) . '</b></div>';
        $text .= $label . ': ' . $value . "\n";
    }
    $html .= '</td></tr></table>';
    return [$html, $text];
}

/** Tlačidlo (odkaz) pre e-mail. */
function mail_button(string $label, string $url, bool $primary = true): string {
    $bg = $primary ? '#0e6fa3' : '#ffffff';
    $color = $primary ? '#ffffff' : '#0e6fa3';
    return '<a href="' . e($url) . '" style="display:inline-block;margin:0 8px 8px 0;padding:12px 22px;border-radius:8px;background:' . $bg . ';color:' . $color . ';border:1.5px solid #0e6fa3;font-weight:700;font-size:15px;text-decoration:none">' . e($label) . '</a>';
}

/** Obal e-mailu: hlavička s logom, obsah, pätička so salónom. */
function mail_layout(string $inner, string $preheader = ''): string {
    $S = cfg()['salon'];
    $logo = site_url() . '/assets/img/logo.png';
    return '<!DOCTYPE html><html lang="sk"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>'
        . '<body style="margin:0;padding:0;background:#eaf2f9;font-family:Arial,Helvetica,sans-serif">'
        . '<span style="display:none;max-height:0;overflow:hidden;opacity:0">' . e($preheader) . '</span>'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eaf2f9"><tr><td align="center" style="padding:24px 12px">'
        . '<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #d4e5f3">'
        . '<tr><td align="center" style="padding:22px 24px;background:#ffffff;border-bottom:4px solid #0e6fa3"><img src="' . e($logo) . '" alt="' . e($S['name']) . '" height="64" style="height:64px;width:auto;border:0"></td></tr>'
        . '<tr><td style="padding:28px 30px 8px">' . $inner . '</td></tr>'
        . '<tr><td style="padding:18px 30px 26px;border-top:1px solid #eaf2f9;font-size:13px;line-height:1.6;color:#5a6b7d">'
        . '<b style="color:#12304f">' . e($S['name']) . '</b><br>' . e($S['address']) . '<br>Tel.: <a href="tel:' . e($S['phone_raw']) . '" style="color:#0e6fa3">' . e($S['phone']) . '</a> · <a href="mailto:' . e($S['email']) . '" style="color:#0e6fa3">' . e($S['email']) . '</a>'
        . '<br><span style="color:#8a98a6">' . e(mail_text('mail.footer', [])) . '</span></td></tr>'
        . '</table></td></tr></table></body></html>';
}

/** Odošle e-mail (HTML + text). */
function send_html_mail(string $to, string $subject, string $html, string $plain, string $replyTo = ''): bool {
    $c = cfg()['salon'];
    $host = preg_replace(['/^www\./', '/:\d+$/'], '', $_SERVER['HTTP_HOST'] ?? 'localhost');
    $from = 'rezervacie@' . $host;
    $b = 'pz_' . bin2hex(random_bytes(8));
    $headers = [
        'From: =?UTF-8?B?' . base64_encode($c['name']) . "?= <$from>",
        'MIME-Version: 1.0',
        'Content-Type: multipart/alternative; boundary="' . $b . '"',
    ];
    if ($replyTo && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) $headers[] = 'Reply-To: ' . $replyTo;
    $enc = fn(string $s) => chunk_split(base64_encode($s));
    $body = "--$b\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n" . $enc($plain)
          . "--$b\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n" . $enc($html)
          . "--$b--";
    return @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));
}

/** Premenné pre texty e-mailov z rezervácie. */
function booking_vars(array $b, string $status = ''): array {
    $S = cfg()['salon'];
    return ['{client}' => $b['name'], '{service}' => $b['service_name'], '{when}' => sk_date($b['date']) . ' o ' . $b['start'],
            '{address}' => $S['address'], '{phone}' => $S['phone'], '{name}' => $S['name'], '{status}' => $status,
            '{price}' => money($b['price'] ?? 0), '{date}' => sk_date($b['date']), '{time}' => $b['start']];
}

/** E-mail pre klienta: received | confirmed | cancelled | reminder. */
function send_client_mail(string $kind, array $b): bool {
    if (empty($b['email']) || !filter_var($b['email'], FILTER_VALIDATE_EMAIL)) return false;
    $S = cfg()['salon'];
    $status = '';
    if ($kind === 'received') $status = tx(($b['status'] ?? 'pending') === 'confirmed' ? 'mail.status.confirmed' : 'mail.status.pending');
    $v = booking_vars($b, $status);
    $subject = strtr(tx('mail.subject.' . $kind), $v);
    $intro = mail_text('mail.' . $kind, $v);
    $end = min_to_hm(hm_to_min($b['start']) + (int)($b['duration'] ?? 0));
    [$cardHtml, $cardText] = mail_card([
        'Služba' => $b['service_name'], 'Termín' => $v['{when}'] . ($b['duration'] ? ' (do ' . $end . ')' : ''), 'Cena' => isset($b['price']) ? money($b['price']) : '', 'Adresa' => $S['address'],
    ]);
    $btns = $kind === 'cancelled' ? mail_button(tx('mail.cta.web'), site_url() . '/rezervacia', true) . mail_button(tx('mail.cta.call') . ' ' . $S['phone'], 'tel:' . $S['phone_raw'], false)
                                  : mail_button(tx('mail.cta.call') . ' ' . $S['phone'], 'tel:' . $S['phone_raw'], true);
    $inner = '<h1 style="margin:0 0 16px;font-size:22px;color:#12304f;font-family:Arial,Helvetica,sans-serif">' . e($subject) . '</h1>' . mail_html_text($intro) . $cardHtml . '<div style="margin:4px 0 10px">' . $btns . '</div>';
    $plain = mail_plain($subject) . "\n\n" . mail_plain($intro) . "\n\n" . $cardText . "\n" . $S['name'] . ' · ' . $S['phone'] . "\n" . $S['address'] . "\n";
    return send_html_mail($b['email'], $subject, mail_layout($inner, $v['{service}'] . ', ' . $v['{when}']), $plain, $S['email']);
}

/** E-mail pre majiteľku o novej rezervácii. */
function send_admin_booking_mail(array $b): bool {
    $S = cfg()['salon'];
    $statusLabel = ($b['status'] ?? 'pending') === 'confirmed' ? 'potvrdená' : 'čaká na potvrdenie';
    $v = booking_vars($b, $statusLabel);
    $subject = strtr(tx('mail.admin.new.subject'), $v);
    $intro = strtr(tx('mail.admin.new.intro'), $v);
    $end = min_to_hm(hm_to_min($b['start']) + (int)$b['duration']);
    [$cardHtml, $cardText] = mail_card([
        'Služba' => $b['service_name'], 'Termín' => $v['{when}'] . ' (do ' . $end . ')', 'Cena' => money($b['price']),
        'Meno' => $b['name'], 'Telefón' => $b['phone'], 'E-mail' => $b['email'] ?: '–', 'Poznámka' => $b['note'] ?: '–', 'Stav' => $statusLabel,
    ]);
    $btns = mail_button('Otvoriť rezervácie', site_url() . '/admin/rezervacie.php', true) . mail_button('Zavolať klientovi', 'tel:' . preg_replace('/[^\d+]/', '', $b['phone']), false);
    if ($b['email']) $btns .= mail_button('Odpovedať e-mailom', 'mailto:' . $b['email'], false);
    $inner = '<h1 style="margin:0 0 16px;font-size:22px;color:#12304f;font-family:Arial,Helvetica,sans-serif">' . e($subject) . '</h1><p style="margin:0 0 14px;line-height:1.6;color:#1d2f44;font-size:15px">' . e($intro) . '</p>' . $cardHtml . '<div style="margin:4px 0 10px">' . $btns . '</div>';
    $plain = $subject . "\n\n" . $intro . "\n\n" . $cardText . "\nSpravovať: " . site_url() . "/admin/rezervacie.php\n";
    return send_html_mail($S['email'], $subject, mail_layout($inner, $intro), $plain, $b['email'] ?? '');
}

/** E-mail pre majiteľku zo vzkazu z kontaktného formulára. */
function send_admin_contact_mail(string $name, string $email, string $phone, string $msg): bool {
    $S = cfg()['salon'];
    $v = ['{client}' => $name, '{name}' => $S['name']];
    $subject = strtr(tx('mail.admin.contact.subject'), $v);
    $intro = strtr(tx('mail.admin.contact.intro'), $v);
    [$cardHtml, $cardText] = mail_card(['Meno' => $name, 'E-mail' => $email, 'Telefón' => $phone ?: '–']);
    $btns = mail_button('Odpovedať e-mailom', 'mailto:' . $email, true) . ($phone ? mail_button('Zavolať', 'tel:' . preg_replace('/[^\d+]/', '', $phone), false) : '');
    $inner = '<h1 style="margin:0 0 16px;font-size:22px;color:#12304f;font-family:Arial,Helvetica,sans-serif">' . e($subject) . '</h1><p style="margin:0 0 14px;line-height:1.6;color:#1d2f44;font-size:15px">' . e($intro) . '</p>' . $cardHtml
           . '<div style="margin:0 0 16px;padding:14px 18px;border-left:4px solid #0e6fa3;background:#f5f9fd;font-size:15px;line-height:1.6;color:#1d2f44">' . nl2br(e($msg)) . '</div><div style="margin:4px 0 10px">' . $btns . '</div>';
    $plain = $subject . "\n\n" . $intro . "\n\n" . $cardText . "\n" . $msg . "\n";
    return send_html_mail($S['email'], $subject, mail_layout($inner, $intro), $plain, $email);
}

/** Pripomienka termínu deň vopred (len ak je zapnutá v Nastaveniach). Beží najviac raz za hodinu. */
function send_reminders(): void {
    if (empty(cfg()['booking']['reminders'])) return;
    $mark = DATA_DIR . '/.reminded';
    if (is_file($mark) && filemtime($mark) > time() - 3600) return;
    @touch($mark);
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    $due = [];
    store_mutate(BOOKINGS_FILE, function (array $all) use ($tomorrow, &$due) {
        $changed = false;
        foreach ($all as &$b) {
            if (($b['date'] ?? '') === $tomorrow && ($b['status'] ?? '') === 'confirmed' && !empty($b['email']) && empty($b['reminded'])) { $b['reminded'] = true; $due[] = $b; $changed = true; }
        }
        return $changed ? ['data' => $all] : [];
    });
    foreach ($due as $b) send_client_mail('reminder', $b);
}

/** Pravidelná údržba na pozadí: mazanie starých údajov a pripomienky. */
function housekeeping(): void {
    try { purge_old_data(); send_reminders(); } catch (Throwable $e) { /* údržba nesmie rozbiť stránku */ }
}
