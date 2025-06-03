<!DOCTYPE html>
<html>
<head>
    <style>
body
{
    background-color: aqua;
}
        table {
            width: 80%;
            border-collapse: collapse;
            padding:10%;
            background-color: beige;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        form {
            display: inline-block;
        }
        
        input[type="text"] {
            margin-right: 5px;
            padding: 5px;
            width: 150px;
        }
        
        button[type="submit"] {
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <?php
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "std_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to add a new club
    function addClub($clubName, $members) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO clubs (club_name, members) VALUES (?, ?)");
        $stmt->bind_param("ss", $clubName, $members);
        $stmt->execute();
        $stmt->close();
    }

    // Function to modify club information
    function modifyClub($clubId, $newClubName, $newMembers) {
        global $conn;
        $stmt = $conn->prepare("UPDATE clubs SET club_name=?, members=? WHERE club_id=?");
        $stmt->bind_param("ssi", $newClubName, $newMembers, $clubId);
        $stmt->execute();
        $stmt->close();
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action === 'add') {
                $clubName = $_POST['club_name'];
                $members = $_POST['members'];

                addClub($clubName, $members);
            } elseif ($action === 'modify') {
                $clubId = $_POST['club_id'];
                $newClubName = $_POST['new_club_name'];
                $newMembers = $_POST['new_members'];

                modifyClub($clubId, $newClubName, $newMembers);
            }
        }
    }

    // Retrieve club data
    $sql = "SELECT * FROM clubs";
    $result = $conn->query($sql);

    // Display clubs in a table
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Club ID</th><th>Club Name</th><th>Members</th><th>Action</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            $clubId = $row['club_id'];
            $clubName = $row['club_name'];
            $members = $row['members'];
            
            echo "<tr>";
            echo "<td>$clubId</td>";
            echo "<td>$clubName</td>";
            echo "<td>$members</td>";
            echo "<td>
                    <form method='post' action=''>
                        <input type='hidden' name='club_id' value='$clubId'>
                        <input type='hidden' name='action' value='modify'>
                        <input type='text' name='new_club_name' placeholder='New Club Name' required>
                        <input type='number' name='new_members' placeholder='Total Members' required>
                        <button type='submit'>Modify</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "No clubs found.";
    }

    $conn->close();
    ?>
<div style="backround:beige">
    <h2>Add a New Club</h2>
    <form method="post" action="">
        <input type="hidden" name="action" value="add">
        <input type="text" name="club_name" placeholder="Club Name" required>
        <input type="number" name="members" placeholder="Total Members" required>
        <button type="submit">Add Club</button>
    </form>
</div>
</body>
</html>
