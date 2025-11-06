import React from 'react'
import '../../styles/organization/topbarOrg.css'
import createnewopportunity from './createNewOpportunity';
import { Bell, Moon } from "lucide-react";
import profileIcon from "../../assets/profile-icon.png";
import { useState } from "react";
import { useNavigate } from "react-router-dom";


export default function TopbarOrg({ onNewOpportunity }) {
  const [darkMode, setDarkMode] = useState(false);
  const navigate = useNavigate();

  const toggleDarkMode = () => {
    setDarkMode(!darkMode);
    document.documentElement.classList.toggle("dark");
  };

  return (
    <header className="topbar">
      <button className="create-btn" onClick={onNewOpportunity}>
        Create New Opportunity
      </button>

      <div className="topbar-right">
        <button className="icon-btn" onClick={toggleDarkMode}>
          <Moon className="icon" />
        </button>

        <button className="notification-btn">
          <Bell className="icon" />
          <span className="notif-dot"></span>
        </button>

        <button
          className="profile-btn"
          onClick={() => navigate("/settings/profile")}
        >
          <img src={profileIcon} alt="Profile" className="profile-img" />
        </button>
      </div>
    </header>
  );
}

