<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.sql.*" %>
<%@ page import="javax.servlet.http.*" %>
<%@ page import="java.util.*" %>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Assigned Tasks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .update-form {
            margin-top: 20px;
        }
        input[type="text"], input[type="date"], select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-btn {
            text-align: right;
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Assigned Tasks</h2>
        <div class="logout-btn">
        <form action="LogoutServlet" method="post">
            <input type="submit" value="Logout">
        </form>
    </div>
        <table>
            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Task Description</th>
                    <th>Due Date</th>
                    <th>Review Date</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <% 
                    try {
                        Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/managementsystem", "root", "");
                        String query = "SELECT * FROM task_assignments WHERE user_id = ?"; // Assuming the column name for user ID is 'user_id'
                        PreparedStatement pstmt = conn.prepareStatement(query);
                        pstmt.setInt(1, (int) session.getAttribute("userId")); // Assuming the session attribute name for user ID is 'userId'
                        ResultSet rs = pstmt.executeQuery();
                        while (rs.next()) {
                            int id = rs.getInt("id");
                            String taskName = rs.getString("task_name");
                            String taskDesc = rs.getString("task_description");
                            String dueDate = rs.getString("due_date");
                            String reviewDate = rs.getString("review_date");
                            String status = rs.getString("task_status");
                            String progress = rs.getString("progress");
                %>
                <tr>
                    <td><%= taskName %></td>
                    <td><%= taskDesc %></td>
                    <td><%= dueDate %></td>
                    <td><%= reviewDate %></td>
                    <td><%= status %></td>
                    <td><%= progress %></td>
                    <td>
                        <form class="update-form" action="UpdateTaskServlet" method="post">
                            <input type="hidden" name="id" value="<%= id %>"> <!-- Hidden input for ID -->
                            <input type="date" name="dueDate" value="<%= dueDate %>">
                            <input type="date" name="reviewDate" value="<%= reviewDate %>">
                            <select name="status">
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                            <input type="text" name="progress" value="<%= progress %>">
                            <input type="submit" value="Update">
                        </form>
                    </td>
                </tr>
                <% 
                        }
                    } catch (SQLException e) {
                        e.printStackTrace();
                    }
                %>
            </tbody>
        </table>
    </div>
    
  
</body>
</html>
