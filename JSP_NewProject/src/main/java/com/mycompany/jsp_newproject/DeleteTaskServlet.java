package com.mycompany.jsp_newproject;

import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;

import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;

@WebServlet("/deleteTaskServlet")
public class DeleteTaskServlet extends HttpServlet {
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        // Retrieve the task ID parameter from the request
        int taskId = Integer.parseInt(request.getParameter("taskId"));

        // Database connection parameters
        String jdbcUrl = "jdbc:mysql://localhost:3306/managementsystem";
        String username = "root";
        String password = ""; // Update with your MySQL password

        try {
            // Load the MySQL JDBC driver class
            Class.forName("com.mysql.cj.jdbc.Driver");
            
            // Establish a connection to the database
            Connection conn = DriverManager.getConnection(jdbcUrl, username, password);

            // SQL query to delete the task by ID
            String sql = "DELETE FROM task_assignments WHERE id = ?";

            // Prepare the SQL statement
            PreparedStatement pstmt = conn.prepareStatement(sql);

            // Set the task ID parameter in the prepared statement
            pstmt.setInt(1, taskId);

            // Execute the delete query
            int rowsAffected = pstmt.executeUpdate();

            if (rowsAffected > 0) {
                // Task successfully deleted
                response.sendRedirect("AdminDashboard.jsp");
            } else {
                // No task found with the given ID
                response.getWriter().println("Task not found with ID: " + taskId);
            }

            // Close the connection and statement
            pstmt.close();
            conn.close();
        } catch (ClassNotFoundException | SQLException e) {
            e.printStackTrace();
            // Handle database errors
            response.getWriter().println("Error deleting task: " + e.getMessage());
        }
    }
}
