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

@WebServlet("/updateTaskServlet")
public class UpdateTaskServlet extends HttpServlet {
    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        // Retrieve parameters from the request
        String idString = request.getParameter("id");
        String dueDate = request.getParameter("dueDate");
        String reviewDate = request.getParameter("reviewDate");
        String status = request.getParameter("status");
        String progress = request.getParameter("progress");
        System.out.println("ID Parameter: " + idString);
        // Check if idString is null or empty before parsing
        int id = -1; // Default value if idString is null or empty
        if (idString != null && !idString.isEmpty()) {
            id = Integer.parseInt(idString);
        }

        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/managementsystem", "root", "");

            String sql = "UPDATE task_assignments SET due_date = ?, review_date = ?, task_status = ?, progress = ? WHERE id = ?";
            PreparedStatement pstmt = conn.prepareStatement(sql);
            pstmt.setString(1, dueDate);
            pstmt.setString(2, reviewDate);
            pstmt.setString(3, status);
            pstmt.setString(4, progress);
            pstmt.setInt(5, id); // Set the ID parameter
            pstmt.executeUpdate();

            conn.close();
            response.sendRedirect("assignedTasks.jsp"); // Redirect to assignedTasks.jsp after updating
        } catch (ClassNotFoundException | SQLException e) {
            e.printStackTrace();
             response.sendRedirect("error.jsp?message=" + e.getMessage()); 
} // Redirect to an error page if an exception occurs
        
    }
}

