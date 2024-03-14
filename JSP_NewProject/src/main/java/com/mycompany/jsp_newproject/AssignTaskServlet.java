package com.mycompany.jsp_newproject;
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.mail.*;
import jakarta.mail.internet.InternetAddress;
import jakarta.mail.internet.MimeMessage;

import java.io.IOException;
import java.util.Properties;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;

@WebServlet("/assignTaskServlet")
public class AssignTaskServlet extends HttpServlet {

    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        String taskname = request.getParameter("taskName");
        String task = request.getParameter("task");
        String dueDate = request.getParameter("dueDate");
        String reviewDate = request.getParameter("reviewDate");
        String assigneeData = request.getParameter("assignee"); // Get the concatenated value
        String[] assigneeParts = assigneeData.split("::"); // Split the value into ID and username
        int assigneeId = Integer.parseInt(assigneeParts[0]); // Parse the ID as integer
        String assigneeUsername = assigneeParts[1]; // Get the username
        
        // Retrieve the email address from the form
        String assigneeEmail = request.getParameter("assigneeEmail");

        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/managementsystem", "root", "");

            String sql = "INSERT INTO task_assignments (task_name, task_description, due_date, review_date, user_id, staff_member, access_level, progress) VALUES (?, ?, ?, ?, ?, ?, 0, ' ')";
            PreparedStatement pstmt = conn.prepareStatement(sql);
            pstmt.setString(1, taskname);            
            pstmt.setString(2, task);
            pstmt.setString(3, dueDate);
            pstmt.setString(4, reviewDate);
            pstmt.setInt(5, assigneeId);
            pstmt.setString(6, assigneeUsername);
            pstmt.executeUpdate();

            conn.close();

            // Send email notification
            sendEmailNotification(assigneeEmail);

            response.sendRedirect("assignTask.jsp?success=true");
        } catch (ClassNotFoundException | SQLException e) {
            e.printStackTrace();
            response.sendRedirect("assignTask.jsp?success=false");
        }
    }

    private void sendEmailNotification(String assigneeEmail) {
        // Email configuration
        String host = "smtp.gmail.com"; // Your SMTP host
        String port = "587"; // Your SMTP port
        String username = "Random"; // Your email username
        String password = "mypw"; // Your email password

        // Sender's email address
        String fromEmail = "acc411188@gmail.com";

        // Email subject
        String subject = "New Task Assigned";

        // Email message
        String messageBody = "A new task has been assigned to you.";

        Properties properties = new Properties();
        properties.put("mail.smtp.host", host);
        properties.put("mail.smtp.port", port);
        properties.put("mail.smtp.auth", "true");
        properties.put("mail.smtp.starttls.enable", "true");

        // Get the Session object
        Session session = Session.getInstance(properties, new Authenticator() {
            @Override
            protected PasswordAuthentication getPasswordAuthentication() {
                return new PasswordAuthentication(username, password);
            }
        });

        try {
            // Create a default MimeMessage object
            Message message = new MimeMessage(session);

            // Set From: header field of the header
            message.setFrom(new InternetAddress(fromEmail));

            // Set To: header field of the header
            message.setRecipients(Message.RecipientType.TO, InternetAddress.parse(assigneeEmail));

            // Set Subject: header field
            message.setSubject(subject);

            // Now set the actual message
            message.setText(messageBody);

            // Send message
            Transport.send(message);
            System.out.println("Email notification sent successfully to " + assigneeEmail);
        } catch (MessagingException e) {
            e.printStackTrace();
            System.out.println("Error sending email notification: " + e.getMessage());
        }
    }
}
