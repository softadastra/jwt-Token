<?php
const TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxMjMsInJvbGVzIjpbIlJPTEVfQURNSU4iLCJST0xFX1VTRVIiXSwiZW1haWwiOiJ0ZXN0QGdtYWlsLmNvbSIsImlhdCI6MTcxOTg2MTI5NywiZXhwIjoxNzE5ODYxMzU3fQ.jck6uQwaPI0arvgHFClAJ1Lki9EXVudalgVxmFR6lMM';

require_once 'includes/config.php';
require_once 'classes/Jwt.php';

$jwt = new JWT();
var_dump($jwt->isValid(TOKEN));
