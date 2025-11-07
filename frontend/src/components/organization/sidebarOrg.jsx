import React from 'react';
import { NavLink } from 'react-router-dom';
import Logo from "../../assets/logo-dark.png";
import Applications from '../../assets/applications-icon.svg';
import Opportunities from '../../assets/opportunities-icon.svg';
import Messages from '../../assets/messages-icon.svg';
import History from '../../assets/history-icon.svg';
import Settings from '../../assets/settings-icon.svg';
import Dashboard from '../../assets/dashboard-icon.svg';
import '../../styles/organization/sideOrg.css';

function SidebarOrg() {
  return (
    <div className="sidebar-org">
      <img src={Logo} alt="logo" className="logo-org" />
      <div className="org-name">
        <h2>VolunTrack</h2>
      </div>
      
      <NavLink 
        to="/dashboard/organization" 
        className={({ isActive }) => isActive ? "sideElements-org active" : "sideElements-org"}
        end
      >
        <img src={Dashboard} alt="dashboard-icon" className="dash-icon-org" />
        <p>Dashboard</p>
      </NavLink>

      <NavLink 
        to="/dashboard/organization/opportunities" 
        className={({ isActive }) => isActive ? "sideElements-org active" : "sideElements-org"}
      >
        <img src={Opportunities} alt="opportunities-icon" className="dash-icon-org" />
        <p>Opportunities</p>
      </NavLink>

      <NavLink 
        to="/dashboard/organization/applications" 
        className={({ isActive }) => isActive ? "sideElements-org active" : "sideElements-org"}
      >
        <img src={Applications} alt="applications-icon" className="dash-icon-org" />
        <p>Applications</p>
      </NavLink>

      <NavLink 
        to="/dashboard/organization/messages" 
        className={({ isActive }) => isActive ? "sideElements-org active" : "sideElements-org"}
      >
        <img src={Messages} alt="messages-icon" className="dash-icon-org" />
        <p>Messages</p>
      </NavLink>

      <NavLink 
        to="/dashboard/organization/history" 
        className={({ isActive }) => isActive ? "sideElements-org active" : "sideElements-org"}
      >
        <img src={History} alt="history-icon" className="dash-icon-org" />
        <p>History</p>
      </NavLink>

      <NavLink 
        to="/dashboard/organization/settings" 
        className={({ isActive }) => isActive ? "sideElements-org active" : "sideElements-org"}
      >
        <img src={Settings} alt="settings-icon" className="dash-icon-org" />
        <p>Settings</p>
      </NavLink>
    </div>
  );
}

export default SidebarOrg;