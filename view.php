<?php
$db = new SQLite3(__DIR__ . '/data/user.db');

// Support older databases that used column name `a` instead of `password`.
$columns = [];
$schemaResult = $db->query("PRAGMA table_info(users)");

if ($schemaResult) {
    while ($column = $schemaResult->fetchArray(SQLITE3_ASSOC)) {
        $columns[] = $column['name'];
    }
}

if (in_array('a', $columns, true) && !in_array('password', $columns, true)) {
    $db->exec("ALTER TABLE users RENAME COLUMN a TO password");
}

$result = $db->query("SELECT name, password FROM users");
$users = [];

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $users[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stored Users</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: #111;
            color: #eee;
            padding: 24px;
        }

        .wrap {
            width: min(900px, 100%);
            margin: 0 auto;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        h1 {
            margin: 0 0 16px;
            font-size: 28px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #1a1a1a;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 12px 14px;
            text-align: left;
        }

        th {
            background: #222;
            color: #fff;
        }

        td {
            background: #181818;
            color: #ddd;
        }

        .empty {
            padding: 14px;
            background: #1a1a1a;
            border: 1px solid #333;
            color: #bbb;
        }

        @media (max-width: 640px) {
            body {
                padding: 14px;
            }

            h1 {
                font-size: 22px;
            }

            table {
                min-width: 420px;
            }

            th,
            td {
                padding: 10px 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <main class="wrap">
        <h1>Stored Users</h1>

        <?php if (count($users) === 0): ?>
            <div class="empty">No users found.</div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['password']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
