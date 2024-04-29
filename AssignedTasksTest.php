<?php
use PHPUnit\Framework\TestCase;

class AssignedTasksTest extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION["userId"] = 1; 

        
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);

        
        $this->pdo->method('prepare')
                  ->willReturn($this->stmt);

        
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetch')
                   ->willReturnOnConsecutiveCalls(
                       'supervisorName', 
                       null              
                   );

        
        $this->stmt->method('fetchAll')
                   ->willReturn([]);

        
        $GLOBALS['conn'] = $this->pdo; 
    }

    protected function tearDown(): void
    {
        session_destroy();
        unset($_SESSION["userId"]);
        unset($GLOBALS['conn']);
    }

    public function testNoTasksAssigned()
    {
        ob_start();
        include 'no_task_assigned.php'; 
        $output = ob_get_clean();

        $this->assertStringContainsString('You currently have no tasks assigned to you.', $output);
    }

    public function testTasksDisplayed()
    {
        
        $this->stmt->method('fetchAll')
                   ->willReturn([
                       ['task_name' => 'Task 1', 'task_description' => 'Desc 1', 'due_date' => '2021-01-01', 'review_date' => '2021-02-01', 'task_status' => 'Pending', 'progress' => '20', 'id' => 1]
                   ]);

        ob_start();
        include 'assigned_tasks.php'; 
        $output = ob_get_clean();

        $this->assertStringContainsString('Task Name', $output);
    }
}
