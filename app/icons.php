<?php
declare(strict_types=1);

function icon(string $name, string $class = ''): string
{
    $paths = [
        'paw' => '<ellipse cx="7" cy="7" rx="2.1" ry="2.8" transform="rotate(-25 7 7)"/><ellipse cx="17" cy="7" rx="2.1" ry="2.8" transform="rotate(25 17 7)"/><ellipse cx="3.5" cy="12" rx="1.8" ry="2.5" transform="rotate(-25 3.5 12)"/><ellipse cx="20.5" cy="12" rx="1.8" ry="2.5" transform="rotate(25 20.5 12)"/><path d="M6.8 20.3c-2-1.4-.6-4.1 1.1-5.8 1.6-1.6 2.5-3 4.1-3s2.5 1.4 4.1 3c1.7 1.7 3.1 4.4 1.1 5.8-1.6 1.2-3.4-.5-5.2-.5s-3.6 1.7-5.2.5Z"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="3"/><path d="m4 7 8 6 8-6"/>',
        'lock' => '<rect x="5" y="10" width="14" height="11" rx="3"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/><path d="M12 14v3"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/>',
        'eye' => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>',
        'eye-off' => '<path d="m3 3 18 18M10.6 5.1c.5-.1.9-.1 1.4-.1 6.5 0 10 7 10 7a19 19 0 0 1-3.1 3.9M6.5 6.5A19 19 0 0 0 2 12s3.5 7 10 7a12 12 0 0 0 5.5-1.5M10 10a3 3 0 0 0 4 4"/>',
        'arrow' => '<path d="M4 12h16m-6-6 6 6-6 6"/>',
        'arrow-up' => '<path d="M6 18 18 6M6 6h12v12"/>',
        'heart' => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/>',
        'check' => '<path d="m5 12 4 4L19 6"/>',
        'shield' => '<path d="m12 3 8 3v6c0 5-8 9-8 9s-8-4-8-9V6l8-3Z"/><path d="m8 12 3 3 5-5"/>',
        'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v6m0-10v.1"/>',
    ];
    $fill = $name === 'paw' ? 'currentColor' : 'none';
    $stroke = $name === 'paw' ? 'none' : 'currentColor';
    return '<svg class="icon ' . escape($class) . '" viewBox="0 0 24 24" fill="' . $fill . '" stroke="' . $stroke . '" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($paths[$name] ?? '') . '</svg>';
}

