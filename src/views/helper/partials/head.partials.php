<?php
use lib\Config;
use lib\Router\Express;

$config = Express::getInstance()->getConfig();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">


<head>
    <base href="/src" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $config["app"]["name"] ?></title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">


    <!-- Daisy UI CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind setup -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        "50": "#f2f4f5",
                        "100": "#dde1e2",
                        "200": "#c3c9ca",
                        "300": "#a1aaac",
                        "400": "#7f8c8d",
                        "500": "#6b7879",
                        "600": "#565f60",
                        "700": "#43494a",
                        "800": "#2f3434",
                        "900": "#1c1e1f",
                        "950": "#0d0f10"
                    }
                },
                fontFamily: {
                    'body': [
                        'Inter',
                        'ui-sans-serif',
                        'system-ui',
                        '-apple-system',
                        'system-ui',
                        'Segoe UI',
                        'Roboto',
                        'Helvetica Neue',
                        'Arial',
                        'Noto Sans',
                        'sans-serif',
                        'Apple Color Emoji',
                        'Segoe UI Emoji',
                        'Segoe UI Symbol',
                        'Noto Color Emoji'
                    ],
                    'sans': [
                        'Inter',
                        'ui-sans-serif',
                        'system-ui',
                        '-apple-system',
                        'system-ui',
                        'Segoe UI',
                        'Roboto',
                        'Helvetica Neue',
                        'Arial',
                        'Noto Sans',
                        'sans-serif',
                        'Apple Color Emoji',
                        'Segoe UI Emoji',
                        'Segoe UI Symbol',
                        'Noto Color Emoji'
                    ]
                }
            }
        }
    </script>

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- TOASTIFY -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js" defer></script>


    <script src="https://unpkg.com/html5-qrcode"></script>
</head>

<body>