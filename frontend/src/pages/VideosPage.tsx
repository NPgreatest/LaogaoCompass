import React, { useEffect, useState } from 'react';

type Video = {
  video_id: string;
  title: string;
  publish_date: string;
  views: number;
  video_url: string;
};


const baseUrl = process.env.REACT_APP_API_URL;


const VideosPage: React.FC = () => {
  const [videos, setVideos] = useState<Video[]>([]);
  useEffect(() => {
    fetch(`${baseUrl}/api/videos`)
      .then((res) => res.json())
      .then((data) => {
        if (Array.isArray(data)) {
          setVideos(data);
        } else {
          console.error("Invalid data from backend:", data);
        }
      })
      .catch((err) => console.error('Error fetching videos:', err));
  }, []);
  

  return (
    <div style={{ padding: '2rem' }}>
      <h1>老高与小茉频道视频列表</h1>
      <ul>
        {videos.map((video) => (
          <li key={video.video_id} style={{ marginBottom: '1rem' }}>
            <strong>{video.title}</strong><br />
            发布日期：{video.publish_date} <br />
            播放量：{video.views.toLocaleString()} <br />
            <a href={video.video_url} target="_blank" rel="noopener noreferrer">
              点我观看视频
            </a>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default VideosPage;
