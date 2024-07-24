<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siteUrl = filter_input(INPUT_POST, 'siteUrl', FILTER_SANITIZE_URL);
    $appTitle = filter_input(INPUT_POST, 'appTitle', FILTER_SANITIZE_STRING);
    $appDescription = filter_input(INPUT_POST, 'appDescription', FILTER_SANITIZE_STRING);
    $icon192 = filter_input(INPUT_POST, 'icon192', FILTER_SANITIZE_URL);
    $icon512 = filter_input(INPUT_POST, 'icon512', FILTER_SANITIZE_URL);
    $backgroundColor = filter_input(INPUT_POST, 'backgroundColor', FILTER_SANITIZE_STRING);
    $themeColor = filter_input(INPUT_POST, 'themeColor', FILTER_SANITIZE_STRING);

    $manifestJson = [
        "name" => $appTitle,
        "short_name" => $appTitle,
        "description" => $appDescription,
        "start_url" => $siteUrl,
        "display" => "standalone",
        "background_color" => $backgroundColor,
        "theme_color" => $themeColor,
        "icons" => [
            [
                "src" => $icon192,
                "sizes" => "192x192",
                "type" => "image/png"
            ],
            [
                "src" => $icon512,
                "sizes" => "512x512",
                "type" => "image/png"
            ]
        ]
    ];

    $swCode = "
self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open('pwa-cache').then(function(cache) {
            return cache.addAll([
                '/',
                '{$siteUrl}/index.html',
                '{$siteUrl}/manifest.json',
                '{$icon192}',
                '{$icon512}'
            ]);
        })
    );
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request).then(function(response) {
            return response || fetch(event.request);
        })
    );
});
    ";

    $instructions = "
<h4>1. Adicione o Manifesto ao seu HTML</h4>
<pre>&lt;link rel=\"manifest\" href=\"/manifest.json\"&gt;</pre>

<h4>2. Registre o Service Worker</h4>
<pre>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
            console.log('Service Worker registrado com sucesso:', registration);
        }, function(err) {
            console.log('Falha ao registrar o Service Worker:', err);
        });
    });
}
</pre>

<h4>3. Adicione os arquivos <code>manifest.json</code> e <code>service-worker.js</code> ao seu servidor</h4>
<pre>Coloque <code>manifest.json</code> na raiz do seu projeto web e <code>service-worker.js</code> no mesmo diretório.</pre>
    ";

    echo json_encode([
        'manifest' => json_encode($manifestJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        'serviceWorker' => trim($swCode),
        'instructions' => $instructions
    ]);
    exit;
}
?>
