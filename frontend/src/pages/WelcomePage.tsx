import React, { useEffect, useState } from "react";
import { BrowserRouter as Router, Routes, Route, Link, useNavigate } from "react-router-dom";

// Welcome Page
function WelcomePage() {
  const navigate = useNavigate();
  return (
    <div className="p-10 text-center">
      <h1 className="text-4xl font-bold mb-4">🎥 VideoAutoMaker</h1>
      <p className="mb-6 text-lg">Automated video generation with AI — audio, visuals, and metadata in one pipeline.</p>
      <button onClick={() => navigate("/projects")} className="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-xl shadow">
        Enter Project Workspace
      </button>
    </div>
  );
}



export default WelcomePage;
