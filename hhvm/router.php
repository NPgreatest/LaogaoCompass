<?hh

<<__EntryPoint>>
function route(): void {
  $path = $_SERVER['REQUEST_URI'];

  switch ($path) {
    case '/':
      require 'pages/home.hack';
      break;
    case '/api/hello':
      require 'pages/api_hello.hack';
      break;
    case '/api/time':
      require 'pages/api_time.hack';
      break;
    default:
      \http_response_code(404);
      echo '404 Not Found';
  }
}
