/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/Servlet.java to edit this template
 */
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
import java.sql.ResultSet;

@WebServlet("/userRegisterServlet")
public class UserRegisterServlet extends HttpServlet {
    private static final long serialVersionUID = 1L;

    // JDBC URL, username, and password
    private static final String JDBC_URL = "jdbc:mysql://localhost:3306/managementsystem";
    private static final String JDBC_USERNAME = "root";
    private static final String JDBC_PASSWORD = "";

protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
    String username = request.getParameter("username");
    String email = request.getParameter("email");
    String password = request.getParameter("password");

    // Database connection and insertion logic
    try {
        Class.forName("com.mysql.cj.jdbc.Driver");
        Connection connection = DriverManager.getConnection(JDBC_URL, JDBC_USERNAME, JDBC_PASSWORD);

        // Insert user into Users table
        String insertUserSql = "INSERT INTO Users (username, email, password) VALUES (?, ?, ?)";
        PreparedStatement userStatement = connection.prepareStatement(insertUserSql);
        userStatement.setString(1, username);
        userStatement.setString(2, email);
        userStatement.setString(3, password);

        int rowsInserted = userStatement.executeUpdate();

        if (rowsInserted > 0) {
            // Get the user ID of the newly inserted user
            String getUserIdSql = "SELECT id FROM Users WHERE username = ?";
            PreparedStatement getUserIdStatement = connection.prepareStatement(getUserIdSql);
            getUserIdStatement.setString(1, username);
            ResultSet resultSet = getUserIdStatement.executeQuery();
            if (resultSet.next()) {
                int userId = resultSet.getInt("id");
                // Insert the user ID and access level into task_assignments table
                String insertAssignmentSql = "INSERT INTO task_assignments (user_id, access_level) VALUES (?, ?)";
                PreparedStatement assignmentStatement = connection.prepareStatement(insertAssignmentSql);
                assignmentStatement.setInt(1, userId);
                assignmentStatement.setInt(2, 1); // Setting access_level to 1
                int assignmentRowsInserted = assignmentStatement.executeUpdate();

                if (assignmentRowsInserted > 0) {
                    response.sendRedirect("registration_success.jsp");
                } else {
                    response.sendRedirect("registration_failure.jsp");
                }
            } else {
                response.sendRedirect("registration_failure.jsp");
            }
        } else {
            response.sendRedirect("registration_failure.jsp");
        }
    } catch (ClassNotFoundException | SQLException e) {
        e.printStackTrace();
        response.sendRedirect("registration_failure.jsp");
    }
}
}
