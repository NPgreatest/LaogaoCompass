import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import axios from "axios";

const CORE_APP_URL = process.env.REACT_APP_CORE_APP_URL;
const PAGE_SIZE = 3;

function ProjectPage() {
  const { project_name } = useParams();
  const [jsonData, setJsonData] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [currentPage, setCurrentPage] = useState(0);

  useEffect(() => {
    axios.get(`${CORE_APP_URL}/project_json/${project_name}`)
      .then((res) => {
        setJsonData(res.data);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Failed to fetch project JSON:", err);
        setLoading(false);
      });
  }, [project_name]);

  const handleTextChange = (index: number, newText: string) => {
    const newData = { ...jsonData };
    newData.script[index].text = newText;
    setJsonData(newData);
  };

  const handleRegenerate = (blockIndex: number) => {
    const body = {
      project_name,
      block_index: blockIndex,
      reGen_audio: true,
      reGen_video: true,
      theme: jsonData.theme,
    };

    axios.post(`${CORE_APP_URL}/process_block`, body)
      .then((res) => alert(res.data.status))
      .catch((err) => alert("Failed to regenerate block: " + err));
  };

  const handleSave = () => {
    const body = {
      project_name,
      data: jsonData,
    };

    axios.post(`${CORE_APP_URL}/update_project_json`, body)
      .then((res) => alert(res.data.status))
      .catch((err) => alert("Failed to save project JSON: " + err));
  };

  if (loading) return <div className="p-10 text-center">Loading project data...</div>;
  if (!jsonData) return <div className="p-10 text-center text-red-500">Failed to load project data.</div>;

  const totalPages = Math.ceil(jsonData.script.length / PAGE_SIZE);
  const pageStart = currentPage * PAGE_SIZE;
  const currentScript = jsonData.script.slice(pageStart, pageStart + PAGE_SIZE);

  return (
    <div className="p-10 space-y-6">
      {/* Save Button + Header */}
      <div className="flex justify-between items-center">
        <h2 className="text-2xl font-semibold">📝 Project: {project_name}</h2>
        <button
          className="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
          onClick={handleSave}
        >
          💾 Save JSON
        </button>
      </div>

      <p className="text-gray-600">Theme: {jsonData.theme}</p>

      {/* Block Editor + Preview */}
      {currentScript.map((block: any, idx: number) => {
        const globalIndex = pageStart + idx;
        const videoURL = block.video ? `${CORE_APP_URL}/static/${project_name}/${block.video}` : null;
        const audioURLs: string[] = (block.audio || []).map((a: string) =>
          `${CORE_APP_URL}/static/${project_name}/${a}`
        );

        return (
          <div key={globalIndex} className="bg-white p-4 rounded-xl shadow space-y-3">
            <div className="text-sm text-gray-500">🎭 Character: {block.character}</div>

            {/* Textarea for editable content */}
            <textarea
              className="w-full p-2 border rounded resize-y"
              value={block.text}
              onChange={(e) => handleTextChange(globalIndex, e.target.value)}
            />

            {/* Audio preview list */}
            {audioURLs.length > 0 && (
              <div className="space-y-1">
                <div className="text-sm text-gray-500">🎧 Audio Preview</div>
                {audioURLs.map((url, i) => (
                  <audio key={i} controls className="w-full">
                    <source src={url} type="audio/wav" />
                    Your browser does not support audio.
                  </audio>
                ))}
              </div>
            )}

            {/* Video preview */}
            {videoURL && (
              <div>
                <div className="text-sm text-gray-500">🎥 Video Preview</div>
                <video
                  src={videoURL}
                  controls
                  className="w-full max-w-full rounded-lg shadow"
                />
              </div>
            )}

            {/* Regenerate Block */}
            <button
              className="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600"
              onClick={() => handleRegenerate(globalIndex)}
            >
              🔄 Regenerate Block
            </button>
          </div>
        );
      })}

      {/* Pagination */}
      <div className="flex justify-between items-center pt-4">
        <button
          disabled={currentPage === 0}
          className="text-sm bg-gray-200 px-3 py-1 rounded disabled:opacity-50"
          onClick={() => setCurrentPage(p => p - 1)}
        >
          ← Prev
        </button>
        <div className="text-sm text-gray-500">
          Page {currentPage + 1} / {totalPages}
        </div>
        <button
          disabled={currentPage + 1 >= totalPages}
          className="text-sm bg-gray-200 px-3 py-1 rounded disabled:opacity-50"
          onClick={() => setCurrentPage(p => p + 1)}
        >
          Next →
        </button>
      </div>
    </div>
  );
}

export default ProjectPage;
