<?hh // strict
namespace Pages;
use namespace HH\Lib\Str;

function normalize_title(string $title): string {
  $title = \mb_strtolower($title);
  return \preg_replace('/[\s\p{P}]+/u', '', $title) ?? '';
}

<<__EntryPoint>>
async function import_videos(): Awaitable<void> {
  \header("Access-Control-Allow-Origin: *");
  \header("Access-Control-Allow-Headers: Content-Type");
  \header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  \header("Content-Type: application/json");
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    \http_response_code(200);
    exit();
  }
  $video_url_path = '/var/www/videodata/video_url.json';
  $transcript_dir = '/var/www/transcribe';
  $conn = null;
  try {
    $conn = await \AsyncMysqlClient::connect(
      'mysql',
      3306,
      'insightgao',
      'insight',
      'insight123',
    );
    
    // Read and parse the video URL JSON data
    $video_url_json = \file_get_contents($video_url_path);
    $decoded_data = \json_decode($video_url_json, true);
    
    // Convert the JSON data to the correct type
    $video_url_data = vec[];
    if (\is_array($decoded_data)) {
      foreach ($decoded_data as $item) {
        if (\is_array($item) && isset($item['title']) && isset($item['url'])) {
          $video_url_data[] = dict[
            'title' => (string)$item['title'], 
            'url' => (string)$item['url']
          ];
        }
      }
    } else {
      throw new \Exception("Expected array, got " . \gettype($decoded_data));
    }
    
    if (\count($video_url_data) === 0) {
      throw new \Exception("No valid video data found");
    }
    
    $title_to_url = dict[];
    foreach ($video_url_data as $item) {
      $title_to_url[normalize_title($item['title'])] = $item['url'];
    }
    
    $imported = vec[];
    
    // Use standard PHP directory functions
    $dir_handle = \opendir($transcript_dir);
    $files = vec[];
    if ($dir_handle) {
      while (($file = \readdir($dir_handle)) !== false) {
        if ($file !== '.' && $file !== '..') {
          $files[] = $file;
        }
      }
      \closedir($dir_handle);
    }
    
    foreach ($files as $file) {
      if (!Str\ends_with($file, '.json')) continue;
      $file_path = $transcript_dir . '/' . $file;
      $title_raw = \str_replace('.json', '', $file);
      $title_slug = normalize_title($title_raw);
      
      if (!\array_key_exists($title_slug, $title_to_url)) continue;
      
      // Check if video already exists in database
      $check_query = "SELECT COUNT(*) as count FROM raw_videos WHERE title = %s";
      $check_res = await $conn->queryf($check_query, $title_raw);
      $check_rows = $check_res->mapRowsTyped();
      if ((int)($check_rows[0]['count'] ?? 0) > 0) continue;
      
      // Load transcript data
      $transcript_str = \file_get_contents($file_path);
      $transcript = \json_decode($transcript_str, true);
      
      if (!\is_array($transcript)) {
        continue; // Skip invalid transcript data
      }
      
      // Construct full text from segments - primary method
      $full_text = '';
      if (isset($transcript['segments']) && \is_array($transcript['segments'])) {
        $segment_texts = vec[];
        foreach ($transcript['segments'] as $segment) {
          if (isset($segment['text']) && \is_string($segment['text'])) {
            $segment_texts[] = \trim($segment['text']);
          }
        }
        $full_text = \implode(' ', $segment_texts);
      } else {
        // Fallback to the text field if segments aren't available
        $full_text = (string)($transcript['text'] ?? '');
      }
      
      $video_id = \uniqid('vid_');
      $video_url = $title_to_url[$title_slug];
      
      $insert_sql = "INSERT INTO raw_videos (video_id, title, publish_date, duration, views, video_url, transcript, metadata) VALUES (%s, %s, %s, %d, %d, %s, %s, %s)";
      await $conn->queryf(
        $insert_sql,
        $video_id,
        $title_raw,
        '2024-01-01', 
        0, 
        0, 
        $video_url,
        $full_text,
        '{"source":"imported"}',
      );
      $imported[] = $title_raw;
    }
    
    echo \json_encode(dict['imported' => $imported]);
  } catch (\Exception $e) {
    \http_response_code(500);
    echo \json_encode(dict['error' => $e->getMessage()]);
  } finally {
    if ($conn !== null) {
      $conn->close();
    }
  }
}