package com.mycompany.jsp_newproject;

import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;

import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

@WebServlet("/loginServlet")
public class LoginServlet extends HttpServlet {
    private static final long serialVersionUID = 1L;

    // JDBC URL, username, and password
    private static final String JDBC_URL = "jdbc:mysql://localhost:3306/managementsystem";
    private static final String JDBC_USERNAME = "root";
    private static final String JDBC_PASSWORD = "";

    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        String email = request.getParameter("email");
        String password = request.getParameter("password");
    if (email.equals("admin") && password.equals("admin")) {
        // Redirect to admin dashboard
        response.sendRedirect("AdminDashboard.jsp");
        return; // Stop further execution
    }
        boolean isAuthenticated = authenticateUser(email, password);

        if (isAuthenticated) {
            int userId = getUserId(email);
            int accessLevel = retrieveAccessLevel(userId);

        if (accessLevel != -1) {
            HttpSession session = request.getSession();
            session.setAttribute("userId", userId);
            session.setAttribute("accessLevel", accessLevel);

            // Redirect based on the access level
            switch (accessLevel) {
                case 0:
                    response.sendRedirect("assignedTasks.jsp");
                    break;
                case 1:
                    response.sendRedirect("NoTaskAssigned.jsp");
                    break;
                default:
                    response.sendRedirect("otherTasks.jsp");
                    break;
            }
        } else {
            // Handle case where access level is null
            response.sendRedirect("noTasksAssigned.jsp");
        }
        } else {
            // Authentication failed
            request.setAttribute("authFailure", true);
            request.getRequestDispatcher("login.jsp").forward(request, response);
        }
    }

    private boolean authenticateUser(String email, String password) {
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection connection = DriverManager.getConnection(JDBC_URL, JDBC_USERNAME, JDBC_PASSWORD)) {
                String sql = "SELECT * FROM Users WHERE email = ? AND password = ?";
                PreparedStatement statement = connection.prepareStatement(sql);
                statement.setString(1, email);
                statement.setString(2, password);
                ResultSet resultSet = statement.executeQuery();
                return resultSet.next();
            }
        } catch (ClassNotFoundException | SQLException e) {
            e.printStackTrace();
            return false;
        }
    }

    private int getUserId(String email) {
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection connection = DriverManager.getConnection(JDBC_URL, JDBC_USERNAME, JDBC_PASSWORD)) {
                String sql = "SELECT id FROM Users WHERE email = ?";
                PreparedStatement statement = connection.prepareStatement(sql);
                statement.setString(1, email);
                ResultSet resultSet = statement.executeQuery();
                if (resultSet.next()) {
                    return resultSet.getInt("id");
                } else {
                    return -1;
                }
            }
        } catch (ClassNotFoundException | SQLException e) {
            e.printStackTrace();
            return -1;
        }
    }

private int retrieveAccessLevel(int userId) {
    try {
        Class.forName("com.mysql.cj.jdbc.Driver");
        try (Connection connection = DriverManager.getConnection(JDBC_URL, JDBC_USERNAME, JDBC_PASSWORD)) {
            String sql = "SELECT access_level FROM task_assignments WHERE user_id = ?";
            PreparedStatement statement = connection.prepareStatement(sql);
            statement.setInt(1, userId);
            ResultSet resultSet = statement.executeQuery();
            if (resultSet.next()) {
                return resultSet.getInt("access_level");
            } else {
                return -1; // Access level not found for the user
            }
        }
    } catch (ClassNotFoundException | SQLException e) {
        e.printStackTrace();
        return -1; // Error occurred while retrieving access level
    }
}

}

