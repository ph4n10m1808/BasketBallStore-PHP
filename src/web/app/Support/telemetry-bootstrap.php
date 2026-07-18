<?php

require_once __DIR__ . '/RequestTelemetry.php';

RequestTelemetry::start($_SERVER, $_GET, $_POST);
