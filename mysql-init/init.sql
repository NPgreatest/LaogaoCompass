CREATE DATABASE IF NOT EXISTS insightgao CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE insightgao;


CREATE TABLE IF NOT EXISTS raw_videos (
  video_id VARCHAR(64) PRIMARY KEY,
  title TEXT,
  publish_date DATE,
  duration INT,
  views INT,
  video_url TEXT,
  transcript TEXT,
  metadata JSON,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 创建表：video_metrics
CREATE TABLE IF NOT EXISTS video_metrics (
  video_id VARCHAR(64) PRIMARY KEY,
  summary TEXT,
  main_topics JSON,
  keywords JSON,
  series VARCHAR(128),
  is_pit BOOLEAN,
  highlight_points JSON,
  analyzed_at TIMESTAMP
);

-- 创建表：analysis_tasks
CREATE TABLE IF NOT EXISTS analysis_tasks (
  task_id VARCHAR(64) PRIMARY KEY,
  video_id VARCHAR(64),
  status ENUM('pending','running','done','failed'),
  log TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- 插入 mock 视频
INSERT INTO raw_videos (video_id, title, publish_date, duration, views, video_url, transcript, metadata)
VALUES 
  ('vid_001', '外星人真实存在吗？', '2024-01-15', 1800, 1200000,'https://www.youtube.com/watch?v=oQ3ihvjrfxo&t=982s', '这是关于外星人的一期内容……', JSON_OBJECT('source', 'Bilibili')),
  ('vid_002', '日本科学家研究灵魂出窍', '2024-02-10', 2100, 950000, 'https://www.youtube.com/watch?v=oXaxcSBRWVA&t=5s','这期我们讲灵魂离体的实验……', JSON_OBJECT('source', 'YouTube'));

-- 插入 mock 分析数据
INSERT INTO video_metrics (video_id, summary, main_topics, keywords, series, is_pit, highlight_points, analyzed_at)
VALUES 
  ('vid_001', '探讨外星人是否存在，从历史文献到现代目击事件。', JSON_ARRAY('玄学','科技'), JSON_ARRAY('外星人','51区','目击'), '外星人系列', true, 
    JSON_ARRAY(JSON_OBJECT('time', 120, 'keyword', '飞碟'), JSON_OBJECT('time', 560, 'keyword', '美军基地')), NOW()),

  ('vid_002', '日本科学家的研究显示灵魂可能离开身体。', JSON_ARRAY('实验','灵异'), JSON_ARRAY('灵魂出窍','实验','日本'), '灵魂系列', false, 
    JSON_ARRAY(JSON_OBJECT('time', 300, 'keyword', '量子意识')), NOW());

-- 插入 mock 任务记录
INSERT INTO analysis_tasks (task_id, video_id, status, log)
VALUES 
  ('task_001', 'vid_001', 'done', '分析成功'),
  ('task_002', 'vid_002', 'done', '分析成功');
