<?php
require_once __DIR__ . '/../Src/header.php';

$apiKey = configGet('temperature_api_key');
if (isset($_POST['ApiKey'], $_POST['Temperature']) && $_POST['ApiKey'] === $apiKey) {
    createOrUpdateTemperatureMeasurement($_POST['Temperature'] ?? 0);
}