import React from "react";
import Logo from "../assets/logo.png";
import { FcGoogle } from "react-icons/fc";
import "../styles/login.css";
import { Link, useNavigate } from "react-router-dom";
import { useState } from "react";
function Login() {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    email: "",
    password: "",
  });
  const [isLoading, setIsLoading] = useState(false);
  const [errors, setErrors] = useState({});

  function handleChange(e) {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
    // Clear error when user starts typing
    if (errors[e.target.name]) {
      setErrors({ ...errors, [e.target.name]: "" });
    }
  }

  const validate = () => {
    let newErrors = {};

    if (!formData.email) {
      newErrors.email = "Email is required";
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = "Email is invalid";
    }

    if (!formData.password) {
      newErrors.password = "Password is required";
    }

    return newErrors;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const validationErrors = validate();

    if (Object.keys(validationErrors).length > 0) {
      setErrors(validationErrors);
      return;
    }

    setIsLoading(true);
    setErrors({});

    try {
      const response = await fetch("http://localhost/IAP_VOLUNTRACK_GRP_A13/backend/login.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      });

      const data = await response.json();

      if (response.ok) {
        if (data.requires_two_factor) {
          // Store user ID for 2FA verification
          sessionStorage.setItem("two_factor_user_id", data.user_id);
          // Redirect to 2FA verification page
          navigate("/two-factor");
        } else {
          // Store auth token
          if (data.token) {
            localStorage.setItem("token", data.token);
          }
          // Redirect to dashboard
          navigate(data.redirect || "/dashboard");
        }
      } else {
        setErrors(data.errors || { general: "Login failed. Please try again." });
      }
    } catch (error) {
      setErrors({ general: "Network error. Please try again." });
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="loginPage">
      <img src={Logo} alt="logo" className="logo" />
      <form action="" className="loginForm" onSubmit={handleSubmit}>
        <h2 className="title">Login</h2>
        
        {errors.general && (
          <div className="error-message">{errors.general}</div>
        )}
        
        <div className="labels">
          <label htmlFor="email">
            Email Address
            <input
              type="email"
              name="email"
              value={formData.email}
              id="email"
              placeholder="Enter your email"
              onChange={handleChange}
            />
            {errors.email && <p className="errors">{errors.email}</p>}
          </label>
          <label htmlFor="password">
            Password
            <input
              type="password"
              name="password"
              value={formData.password}
              id="password"
              placeholder="Enter your password"
              onChange={handleChange}
            />
            {errors.password && <p className="errors">{errors.password}</p>}
          </label>
        </div>

        <button type="submit" className="loginBtn" disabled={isLoading}>
          {isLoading ? "Logging in..." : "Login"}
        </button>
        <div className="or">
          <hr />
          <p>or</p>

          <hr />
        </div>
        <button className="googleBtn">
          <FcGoogle />
          Continue with google
        </button>
        <p className="noAccount">
          Don't Have an Account?
          <Link to="/signup" className="link">
            Register
          </Link>
        </p>
      </form>
    </div>
  );
}

export default Login;
