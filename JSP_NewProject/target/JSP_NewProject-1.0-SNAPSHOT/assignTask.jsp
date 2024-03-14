<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.sql.*" %>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Assign Task</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin-top: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            background-color: #ffff;
            padding: 80px;
            border-radius: 7px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }

        .form-group {
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="date"], select {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

            input[type="email"], input[type="email"], select {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        select {
            height: 30px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .success-msg {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            margin-top: 10px;
        }

        .error-msg {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<h2>Assign Task</h2>
<% if ("true".equals(request.getParameter("success"))) { %>
    <div class="success-msg">Task assigned successfully!</div>
<% } else if ("false".equals(request.getParameter("success"))) { %>
    <div class="error-msg">Error assigning task. Please try again!</div>
<% } %>
<form action="AssignTaskServlet" method="post">
    <div class="form-group">
        <label for="taskName">Task Name:</label>
        <input type="text" id="taskName" name="taskName" required>
    </div>
    <div class="form-group">
        <label for="task">Task Description:</label>
        <input type="text" id="task" name="task" required>
    </div>
    <div class="form-group">
        <label for="dueDate">Due Date:</label>
        <input type="date" id="dueDate" name="dueDate" required>
    </div>
    <div class="form-group">
        <label for="reviewDate">Review Date:</label>
        <input type="date" id="reviewDate" name="reviewDate" required>
    </div>
    <div class="form-group">
        <label for="assignee">Assign To:</label>
        <select id="assignee" name="assignee" required>
            <% 
                try {
                    Class.forName("com.mysql.cj.jdbc.Driver");
                    Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/managementsystem", "root", "");
                    Statement stmt = conn.createStatement();
                    ResultSet rs = stmt.executeQuery("SELECT id, username FROM Users");
                    while (rs.next()) {
                        int userId = rs.getInt("id");
                        String username = rs.getString("username");
            %>
            <option value="<%= userId %>::<%= username %>"><%= username %></option>
            <% 
                    }
                    conn.close();
                } catch (Exception e) {
                    out.println(e);
                }
            %>
        </select>
    </div>
    <div class="form-group">
    <label for="assigneeEmail">Assignee Email:</label>
    <input type="email" id="assigneeEmail" name="assigneeEmail" required>
</div>

    <div class="form-group">
        <input type="submit" value="AssignTask">
            <input type="submit" value="Back" onclick="window.location.href='AdminDashboard.jsp'">
        <input type="submit" value="Login" onclick="window.location.href='login.jsp'">
        </div>
</form>
</body>
</html>


