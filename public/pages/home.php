<?hh // strict

namespace Pages;

function render_home(): void {
  \header('Content-Type: text/html');
  echo '<h1>Welcome!</h1>';
  echo '<p><a href="/chart">See Chart</a></p>';
}
