<?php
require 'vendor/autoload.php';
use App\Manager;

$manager = new Manager("tasks.json");
while (true) {
    echo "[1] Create
[2] Display
[3] Mark as completed
[4] Delete
[Any key] Exit \n";
    $select = (int)readline("Enter choice: ");
    switch ($select) {
        case 1:
            $manager->create();
            break;
        case 2:
            $manager->display();
            break;
        case 3:
            $manager->mark();
            break;
        case 4:
            $manager->delete();
            break;
        default:
            exit("Goodbye");
    }
}