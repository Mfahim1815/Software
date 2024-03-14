<%-- 
    Document   : UpdateTask
    Created on : Feb 24, 2024, 9:53:09 PM
    Author     : Itcomplex
--%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Update Task</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        
        form {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            text-align: center;
            color: #333;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        select, input[type="date"], textarea, input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Update Task</h2>
    <form action="UpdateTaskServlet" method="post">
        <!-- Task ID (hidden, if needed) -->
        <input type="hidden" name="taskId" value="<%= request.getParameter("taskId") %>">
        
        <!-- Task Status -->
        <label for="status">Task Status:</label>
        <select name="status" id="status">
            <option value="Pending">Pending</option>
            <option value="InProgress">In Progress</option>
            <option value="Completed">Completed</option>
        </select>
        
        <!-- Due Date -->
        <label for="dueDate">Due Date:</label>
        <input type="date" id="dueDate" name="dueDate">
        
        <!-- Review Date -->
        <label for="reviewDate">Review Date:</label>
        <input type="date" id="reviewDate" name="reviewDate">
        
        <!-- Progress -->
        <label for="progress">Progress:</label>
        <textarea id="progress" name="progress" rows="4" cols="50"></textarea>
        
        <!-- Submit Button -->
        <input type="submit" value="Update Task">
    </form>
</body>
</html>
