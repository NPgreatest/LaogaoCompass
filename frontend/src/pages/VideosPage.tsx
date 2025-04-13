import React, { useEffect, useState } from 'react';

type Video = {
  video_id: string;
  title: string;
  publish_date: string;
  views: number;
  video_url: string;
};

const baseUrl = process.env.REACT_APP_API_URL;
const extractYoutubeId = (url: string) => {
  const match = url.match(/(?:youtube\.com\/.*v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
  return match ? match[1] : null;
};

const PAGE_SIZE = 5;

const VideosPage: React.FC = () => {
  const [videos, setVideos] = useState<Video[]>([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [total, setTotal] = useState(0);

  const fetchVideos = (page: number) => {
    fetch(`${baseUrl}/api/videos?pn=${page}&ps=${PAGE_SIZE}`)
      .then((res) => res.json())
      .then((data) => {
        if (Array.isArray(data.videos)) {
          setVideos(data.videos);
          setTotal(data.total);
        } else {
          console.error("Invalid data from backend:", data);
        }
      })
      .catch((err) => console.error('Error fetching videos:', err));
  };

  useEffect(() => {
    fetchVideos(currentPage);
  }, [currentPage]);

  const totalPages = Math.ceil(total / PAGE_SIZE);

  return (
    <div style={{ padding: '2rem', fontFamily: 'sans-serif' }}>
      <h1 style={{ marginBottom: '2rem' }}>老高与小茉频道视频列表</h1>

      {videos.map((video) => {
        const videoId = extractYoutubeId(video.video_url);
        return (
          <div
            key={video.video_id}
            style={{
              border: '1px solid #ddd',
              borderRadius: '8px',
              padding: '1rem',
              marginBottom: '2rem',
              boxShadow: '0 2px 8px rgba(0, 0, 0, 0.05)',
            }}
          >
            <h2>{video.title}</h2>
            <p>发布日期：{video.publish_date}</p>
            <p>播放量：{video.views.toLocaleString()}</p>
            {videoId ? (
              <div style={{ position: 'relative', paddingBottom: '56.25%', height: 0 }}>
                <iframe
                  src={`https://www.youtube.com/embed/${videoId}`}
                  title={video.title}
                  frameBorder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowFullScreen
                  style={{
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    width: '100%',
                    height: '100%',
                    borderRadius: '6px',
                  }}
                />
              </div>
            ) : (
              <a href={video.video_url} target="_blank" rel="noopener noreferrer">
                点我观看视频
              </a>
            )}
          </div>
        );
      })}

      <div style={{ textAlign: 'center', marginTop: '2rem' }}>
        <button onClick={() => setCurrentPage(p => Math.max(1, p - 1))} disabled={currentPage === 1}>
          上一页
        </button>
        <span style={{ margin: '0 1rem' }}>
          第 {currentPage} / {totalPages} 页
        </span>
        <button onClick={() => setCurrentPage(p => Math.min(totalPages, p + 1))} disabled={currentPage === totalPages}>
          下一页
        </button>
      </div>
    </div>
  );
};

export default VideosPage;
