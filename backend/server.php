<?php
header("Access-Control-Allow-Origin: http://localhost:5173"); // allow your frontend
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// 2️⃣ Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

// 3️⃣ Existing JSON header
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/handler/auth.php";
require_once __DIR__ . "/handler/haku.php";
require_once __DIR__ . "/handler/kaapit.php";
require_once __DIR__ . "/handler/luokat.php";
require_once __DIR__ . "/handler/tavarat.php";
require_once __DIR__ . "/handler/varasto_rivit.php";
require_once __DIR__ . "/handler/varaukset.php";
require_once __DIR__ . "/middleware/auth.php";

$routes = [
    'GET' => [
        // Käyttäjät
        'hae_kayttajat' => 'hae_kayttajat',
        
        // Luokat
        'hae_luokat' => 'hae_luokat',
        
        // Kaapit
        'hae_kaapit' => 'hae_kaapit',
        'hae_kaappi' => 'hae_kaappi',
        'hae_kaappi_sisalto' => 'hae_kaappi_sisalto',
        
        // Tavarat
        'hae_tavarat' => 'hae_tavarat',
        'hae_tavara' => 'hae_tavara',
        'hae_saatavilla_olevat' => 'hae_saatavilla_olevat',
        'etsi_tavara' => 'etsi_tavara',
        
        // Varasto
        'hae_varasto_rivit' => 'hae_varasto_rivit',
        
        // Varaukset
        'hae_varaukset' => 'hae_varaukset',
        'hae_varaushistoria' => 'hae_varaushistoria',    
    ],
    'POST' => [
        // Autentikointi (ei vaadi tokenia)
        'kirjaudu' => 'kirjaudu',
        'rekisteroidy' => 'rekisteroidy',
        
        // Käyttäjät
        'paivita_kayttaja' => 'paivita_kayttaja',
        
        // Luokat
        'lisaa_luokka' => 'lisaa_luokka',
        'paivita_luokka' => 'paivita_luokka',
        'poista_luokka' => 'poista_luokka',
        
        // Kaapit
        'lisaa_kaappi' => 'lisaa_kaappi',
        'paivita_kaappi' => 'paivita_kaappi',
        'poista_kaappi' => 'poista_kaappi',
        
        // Tavarat
        'lisaa_tavara' => 'lisaa_tavara',
        'paivita_tavara' => 'paivita_tavara',
        'poista_tavara' => 'poista_tavara',
        
        // Varasto
        'lisaa_varasto_rivi' => 'lisaa_varasto_rivi',
        'paivita_varasto_rivi' => 'paivita_varasto_rivi',
        'poista_varasto_rivi' => 'poista_varasto_rivi',
        
        // Varaukset
        'luo_varaus' => 'luo_varaus',
        'peruuta_varaus' => 'peruuta_varaus',
    ]
];



$publicRoutes = ['kirjaudu', 'rekisteroidy'];
$adminRoutes = ['admin-stats', 'hae-kayttajat', 'muokkaa-tavaraa']; 

$method = $_SERVER['REQUEST_METHOD'];
$input = $method === 'POST'
    ? (json_decode(file_get_contents('php://input'), true) ?: [])
    : $_GET;

try {
    if (!isset($routes[$method])) {
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Method not allowed"]);
        exit;
    }

    $handled = false;

    foreach ($routes[$method] as $routeKey => $handler) {
        if (array_key_exists($routeKey, $input)) {
            if (!function_exists($handler)) {
                throw new Exception("Handler $handler not defined");
            }

            $decoded = null;

            // 1. Check for Admin Routes
            if (in_array($routeKey, $adminRoutes)) {
                $decoded = requireAdmin(); // Will exit if not admin
            } 
            // 2. Check for Auth Routes (not public)
            elseif (!in_array($routeKey, $publicRoutes)) {
                $decoded = requireAuth(); // Will exit if no valid token
            }

            // Call handler
            $result = $handler($pdo, $input, $decoded);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            $handled = true;
            break;
        }
    }

    if (!$handled) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Reittiä ei löytynyt"]);
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}