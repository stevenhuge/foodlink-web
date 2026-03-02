<?php

// Vercel routes all traffic to api/index.php
// By default, PHP sets SCRIPT_NAME to /api/index.php
// Laravel sees this and strips /api/ from the URL path!
// So a request to /api/produk becomes /produk, which returns 404.
// This line fixes it by pretending the script is at the root.
$_SERVER['SCRIPT_NAME'] = '/index.php';

require __DIR__ . '/../public/index.php';
