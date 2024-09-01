<?php

    require_once './inc/functions.php';

    $task = $_GET['task'] ?? 'report';
    $error = $_GET['error'] ?? 0;
    $alert = "";

    if ($task == 'seed') {
        seed();
        $alert = "Seeding is completed";
    }

    // $fname = "";
    // $lname = "";
    // $roll = "";
    if (isset($_POST['submit'])) {
        $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
        $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
        $roll = filter_input(INPUT_POST, 'roll', FILTER_SANITIZE_NUMBER_INT);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($id) {
            // update existing student
            if ($fname != '' && $lname != '' && $roll != '') {
                $result = updateStudent($id, $fname, $lname, $roll);
                if ($result) {
                    header('location: /index.php?task=report');
                } else {
                    $error = 1;
                }
            }
        } else {
            // add a new student
            if ($fname != '' && $lname != '' && $roll != '') {
                $result = addStudent($fname, $lname, $roll);
                if ($result) {
                    header('location: /index.php?task=report');
                } else {
                    $error = 1;
                }
            }
        }
    }

    if ($task == 'delete') {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if ($id > 0) {
            deleteStudent((int) $id);
            header('location: /index.php?task=report');
        }
    }

?>

<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark" />
    <link rel="stylesheet" href="./assets/pico.min.css">
    <link rel="stylesheet" href="./assets/styles.css">
    <title>Crud</title>
</head>

<body>
    <header class="container">
        <nav>
            <ul>
                <li>
                    <details class="dropdown">
                        <summary role="button" class="outline secondary">Theme</summary>
                        <ul>
                            <li><a href="#" data-theme-switcher="auto">Auto</a></li>
                            <li><a href="#" data-theme-switcher="light">Light</a></li>
                            <li><a href="#" data-theme-switcher="dark">Dark</a></li>
                        </ul>
                    </details>
                </li>
            </ul>
        </nav>
    </header>
    <main class="container wrapper">
        <h1>Crud</h1>
        <p class="mb-3">A simple project to perform crud operations using plain file and PHP.</p>

        <?php include_once './inc/nav_links.php'?>
        <hr>

        <?php if ($alert != "") {
                echo "<blockquote>{$alert}</blockquote>";
        }?>

        <?php
            if ($task == 'report') {
                generateReport();
            }
        ?>

        <?php if ($error == 1): ?>
        <blockquote>Duplicate Roll Number.</blockquote>
        <?php endif;?>

        <?php if ($task == 'add'): ?>
        <form action="./index.php?task=add" method="POST">
            <div class="grid">
                <div>
                    <label for="fname">First Name</label>
                    <input type="text" name="fname" id="fname" autocomplete="off" value="<?php echo $fname; ?>">
                </div>
                <div>
                    <label for="lname">Last Name</label>
                    <input type="text" name="lname" id="lname" autocomplete="off" value="<?php echo $lname; ?>">
                </div>
                <div>
                    <label for="roll">Roll</label>
                    <input type="number" name="roll" id="roll" autocomplete="off" value="<?php echo $roll; ?>">
                </div>
            </div>
            <button type="submit" name="submit" class="secondary btn">Submit</button>
        </form>
        <?php endif;?>

        <?php if ($task == 'edit'):
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
                $student = getStudent($id);
                if ($student):
            ?>
	        <form method="POST">
	            <div class="grid">
	                <input type="hidden" name="id" value="<?php echo $id; ?>">
	                <div>
	                    <label for="fname">First Name</label>
	                    <input type="text" name="fname" id="fname" autocomplete="off"
	                        value="<?php echo $student['fname']; ?>">
	                </div>
	                <div>
	                    <label for="lname">Last Name</label>
	                    <input type="text" name="lname" id="lname" autocomplete="off"
	                        value="<?php echo $student['lname']; ?>">
	                </div>
	                <div>
	                    <label for="roll">Roll</label>
	                    <input type="number" name="roll" id="roll" autocomplete="off"
	                        value="<?php echo $student['roll']; ?>">
	                </div>
	            </div>
	            <button type="submit" name="submit" class="secondary btn">Update</button>
	        </form>
	        <?php
                endif;
                endif;
            ?>
    </main>
    <script src="./assets/main.js"></script>
</body>

</html>