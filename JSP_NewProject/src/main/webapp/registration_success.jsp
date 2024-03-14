<%-- 
    Document   : registration_success.jsp
    Created on : Feb 25, 2024, 12:44:21 AM
    Author     : Itcomplex
--%>

<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Registration Successful</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      text-align: center;
    }

    .container {
      width: 400px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #f5f5f5;
    }

    h1 {
      margin-bottom: 15px;
      color: #2e8b57;
    }

    p {
      font-size: 16px;
    }

    a {
      background-color: #007bff;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
    }

    a:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Congratulations!</h1>
    <p>You have been successfully registered.</p>
    <a href="login.jsp">Proceed to Login</a>
  </div>
</body>
</html>
