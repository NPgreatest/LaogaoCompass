<?hh // strict

namespace Pages;

function api_data(): void {
  \header('Content-Type: application/json');

  // Fake data example
  $labels = vec['Red', 'Blue', 'Yellow', 'Green', 'Purple'];
  $values = vec[12, 19, 3, 5, 2];

  echo \json_encode(darray[
    'labels' => $labels,
    'values' => $values,
  ]);
}
