<?php
namespace App;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Manager
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode(['tasks' => []], JSON_PRETTY_PRINT));
        }
    }

    private function loadTasks(): ?array
    {
        return json_decode(file_get_contents($this->file), true);
    }

    private function saveTask(array $data): void
    {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function create(): void
    {
        $data = $this->loadTasks();
        $text = readline("Enter task ");
        $data["tasks"][] = ["text" => $text, "status" => "X"];
        $this->saveTask($data);
    }

    public function display(): void
    {
        $data = $this->loadTasks();
        if (empty($data["tasks"])) {
            echo "No tasks found." . PHP_EOL;
            return;
        }
        $output = new ConsoleOutput();
        $completedStyle = new OutputFormatterStyle('green');
        $output->getFormatter()->setStyle('completed', $completedStyle);
        $table = new Table($output);
        $table->setHeaders(['ID', 'Task', 'Status']);
        foreach ($data["tasks"] as $index => $task) {
            $text = $task["text"];
            if ($task["status"] === "+") {
                $text = "<completed>$text</completed>";
            }
            $table->addRow([$index, $text, $task["status"]]);
        }
        $table->render();
    }

    public function mark(): void
    {
        $data = $this->loadTasks();
        if (empty($data["tasks"])) {
            echo "No tasks to mark as completed." . PHP_EOL;
            return;
        }
        $mark = (int)readline("Which task to mark as completed? ");
        if ($mark < 0 || $mark >= count($data["tasks"])) {
            echo "Incorrect " . PHP_EOL;
            return;
        }
        $data["tasks"][$mark]["status"] = "+";
        $this->saveTask($data);
    }

    public function delete(): void
    {
        $data = $this->loadTasks();
        if (empty($data["tasks"])) {
            echo "No tasks to delete." . PHP_EOL;
            return;
        }
        $delete = (int)readline("Which task to delete? ");
        if ($delete < 0 || $delete >= count($data["tasks"])) {
            echo "Incorrect " . PHP_EOL;
            return;
        }
        array_splice($data["tasks"], $delete, 1);
        $this->saveTask($data);
    }
}