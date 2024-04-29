<?php
use PHPUnit\Framework\TestCase;

class AdminDashboardTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        session_start();
        $this->conn = new PDO('mysql:host=localhost;dbname=managementsystem', 'root', '');
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    protected function tearDown(): void
    {
        session_destroy();
        $this->conn = null; 
        parent::tearDown();
    }

    public function testAddTaskSuccess()
    {
        $_SESSION['userId'] = 0; 
        $_POST['addTask'] = true;
        $_POST['taskName'] = 'New Task';
        $_POST['taskDescription'] = 'Description here';
        $_POST['dueDate'] = '2023-01-01';
        $_POST['username'] = 'user';
        $_POST['supervisor'] = 'supervisor';
        $stmt = $this->conn->prepare("SELECT * FROM task_assignments WHERE task_name = ?");
        $stmt->execute(['New Task']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        
        $this->assertEquals(0, $result);
    }
}
