import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import axios from "axios";

const baseurl = process.env.REACT_APP_CORE_APP_URL;

function ProjectSelector() {
  const [projects, setProjects] = useState<string[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    console.log(baseurl);
    axios.get(`${baseurl}/projects`)
      .then((res) => {
        setProjects(res.data.projects);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Failed to fetch projects:", err);
        setLoading(false);
      });
  }, []);

  if (loading) return <div className="p-10 text-center">Loading projects...</div>;

  return (
    <div className="p-10">
      <h2 className="text-2xl font-semibold mb-4">🎬 Your Projects</h2>
      <ul className="space-y-2">
        {projects.map((proj, idx) => (
          <li key={idx} className="bg-gray-100 p-4 rounded-xl shadow-md flex justify-between items-center">
            <span>{proj}</span>
            <Link to={`/project/${proj}`} className="text-blue-500 hover:underline">Open</Link>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default ProjectSelector;
