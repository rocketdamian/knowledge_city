<?php

function getBearerToken()
{
  $headers = isset($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]) ? $_SERVER["REDIRECT_HTTP_AUTHORIZATION"] : null;
  // HEADER: Get the access token from the header
  if (!empty($headers)) {
    if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
      return $matches[1];
    }
  }
  http_response_code(400);
  echo json_encode(['error' => true, 'message' => 'missing auth token']);
  die();
}
