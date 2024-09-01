<?php

    define('DB', __DIR__ . DIRECTORY_SEPARATOR . "../data/db.txt");

    function seed(): void {
        $data = [
            [
                'id'    => 1,
                'fname' => 'R.',
                'lname' => 'Ahmed',
                'roll'  => 4,
            ],
            [
                'id'    => 2,
                'fname' => 'J.',
                'lname' => 'Kareem',
                'roll'  => 5,
            ],
            [
                'id'    => 3,
                'fname' => 'A.',
                'lname' => 'Rehman',
                'roll'  => 6,
            ],
            [
                'id'    => 4,
                'fname' => 'W',
                'lname' => 'Johnson',
                'roll'  => 10,
            ],
        ];

        saveStudents($data);
    }

    function addStudent($fname, $lname, $roll): bool {
        $students = getAllStudents();

        // Check for duplicate roll number
        foreach ($students as $_student) {
            if ($_student['roll'] == $roll) {
                return false;
            }
        }
        $newId = getNewId($students);

        $student = [
            'id'    => $newId,
            'fname' => $fname,
            'lname' => $lname,
            'roll'  => $roll,
        ];

        $students[] = $student;
        saveStudents($students);

        return true;
    }

    function getNewId(array $students): int {
        $maxId = max(array_column($students, 'id'));
        return $maxId + 1;
    }

    function getAllStudents(): array {
        $serializedData = file_get_contents(DB);
        return unserialize($serializedData) ?: [];
    }

    function saveStudents(array $students): void {
        $serializedData = serialize($students);
        file_put_contents(DB, $serializedData, LOCK_EX);
    }

    function getStudent(int $id): ?array {
        $students = getAllStudents();

        foreach ($students as $student) {
            if ($student['id'] == $id) {
                return $student;
            }
        }

        return null;
    }

    function updateStudent(int $id, string $fname, string $lname, int $roll): bool {
        $duplicateRoll = false;
        $students = getAllStudents();

        foreach ($students as $_student) {
            if ($_student['roll'] == $roll && $_student['id'] != $id) {
                $duplicateRoll = true;
                break;
            }
        }

        if (!$duplicateRoll) {
            $students[$id - 1]['fname'] = $fname;
            $students[$id - 1]['lname'] = $lname;
            $students[$id - 1]['roll'] = $roll;
            saveStudents($students);
            return true;
        }

        return false;
    }

    function deleteStudent(int $id): void {
        $students = getAllStudents();

        foreach ($students as $offset => $student) {
            if ($student['id'] == $id) {
                unset($students[$offset]);
            }
        }

        saveStudents($students);
        return;
    }

    function generateReport(): void {
        $students = getAllStudents();
    ?>
<table class="striped">
    <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Roll</th>
            <th scope="col" width="30%">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $student): ?>
        <tr>
            <th scope="row">
                <?php printf('%s %s', $student['fname'], $student['lname'])?>
            </th>
            <td><?php printf('%s', $student['roll'])?></td>
            <td>
                <?php printf('<a href="./index.php?task=edit&id=%s">Edit</a> | <a class="delete" href="./index.php?task=delete&id=%s">Delete</a>', $student['id'], $student['id'])?>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php
}