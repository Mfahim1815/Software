/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/Servlet.java to edit this template
 */
package com.mycompany.jsp_newproject;

import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

@WebServlet("/exportDataServlet")
public class ExportDataServlet extends HttpServlet {
    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        response.setContentType("text/csv");
        response.setHeader("Content-Disposition", "attachment; filename=\"dashboard_data.csv\"");

        List<DashboardData> dashboardDataList = fetchDashboardDataFromDatabase();

        try (PrintWriter writer = response.getWriter()) {
            writer.println("Task Name, Task Description, Due Date, Review Date, Status, Progress, Staff Member");

            for (DashboardData data : dashboardDataList) {
                writer.println(data.getTaskName() + "," + data.getTaskDescription() + "," + data.getDueDate() + "," + data.getReviewDate() + "," + data.getStatus() + "," + data.getProgress() + "," + data.getStaffMember());
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private List<DashboardData> fetchDashboardDataFromDatabase() {
        List<DashboardData> dashboardDataList = new ArrayList<>();
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/managementsystem", "root", "");
            String query = "SELECT * FROM task_assignments";
            PreparedStatement pstmt = conn.prepareStatement(query);
            ResultSet rs = pstmt.executeQuery();
            while (rs.next()) {
                String taskName = rs.getString("task_name");
                String taskDesc = rs.getString("task_description");
                String dueDate = rs.getString("due_date");
                String reviewDate = rs.getString("review_date");
                String status = rs.getString("task_status");
                String progress = rs.getString("progress");
                String staffMember = rs.getString("staff_member");

                DashboardData data = new DashboardData(taskName, taskDesc, dueDate, reviewDate, status, progress, staffMember);
                dashboardDataList.add(data);
            }
            conn.close();
        } catch (ClassNotFoundException | SQLException e) {
            e.printStackTrace();
        }
        return dashboardDataList;
    }
}

