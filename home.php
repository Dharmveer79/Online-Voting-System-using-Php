<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>

    <?php include('db_connect.php'); ?>
    
    <style>
        /* Google Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        /* Reset & Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1e1e2f, #3a0ca3);
            color: white;
            padding: 20px;
            text-align: center;
        }

        /* Online Voting System Heading */
        .main-heading {
            font-size: 3.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 15px rgba(0, 255, 255, 0.8);
            animation: fadeIn 1.5s ease-in-out;
            margin-bottom: 20px;
        }

        .sub-heading {
            font-size: 1.3rem;
            font-weight: 400;
            opacity: 0.8;
            animation: fadeIn 2s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Voting Cards */
        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card h3 {
            font-size: 2rem;
            margin-bottom: 5px;
        }

        .bg-info {
            background: linear-gradient(45deg, #00c6ff, #0072ff);
        }

        .bg-primary {
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
        }

        /* Candidate Styling */
        .candidate {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 15px;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            transition: 0.3s;
        }

        .candidate:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .candidate .img-field {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
        }

        .candidate img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

        .candidate_name {
            font-size: 1.2rem;
            font-weight: bold;
            flex-grow: 1;
        }

        .vote-field {
            font-size: 1.5rem;
            font-weight: bold;
            background: #00ff88;
            padding: 5px 15px;
            border-radius: 10px;
            color: #1e1e2f;
        }

    </style>
</head>

<body>

    <!-- Main Heading -->
    <h1 class="main-heading">Online Voting System</h1>
    <p class="sub-heading">Secure • Transparent • Reliable</p>

    <?php 
    $voting = $conn->query("SELECT * FROM voting_list WHERE is_default = 1 ");
    foreach ($voting->fetch_array() as $key => $value) {
        $$key = $value;
    }
    $votes = $conn->query("SELECT * FROM votes WHERE voting_id = $id ");
    $v_arr = array();
    while ($row = $votes->fetch_assoc()) {
        if (!isset($v_arr[$row['voting_opt_id']])) {
            $v_arr[$row['voting_opt_id']] = 0;
        }
        $v_arr[$row['voting_opt_id']] += 1;
    }
    $opts = $conn->query("SELECT * FROM voting_opt WHERE voting_id=".$id);
    $opt_arr = array();
    while ($row = $opts->fetch_assoc()) {
        $opt_arr[$row['category_id']][] = $row;
    }
    ?>

    <!-- Voters & Voted Cards -->
    <div class="row">
        <div class="col-md-5 mx-auto">
            <div class="card bg-info">
                <h4>Voters</h4>
                <hr>
                <h3><?php echo $conn->query('SELECT * FROM users WHERE type = 2')->num_rows; ?></h3>
            </div>
        </div>

        <div class="col-md-5 mx-auto">
            <div class="card bg-primary">
                <h4>Voted</h4>
                <hr>
                <h3><?php echo $conn->query('SELECT DISTINCT(user_id) FROM votes WHERE voting_id = '.$id)->num_rows; ?></h3>
            </div>
        </div>
    </div>

    <!-- Voting Title -->
    <div class="title-section">
        <h3><?php echo $title; ?></h3>
        <small><?php echo $description; ?></small>
    </div>

    <?php 
    $cats = $conn->query("SELECT * FROM category_list WHERE id IN (SELECT category_id FROM voting_opt WHERE voting_id = '".$id."' )");
    while ($row = $cats->fetch_assoc()): 
    ?>
        <div class="category-title"><?php echo $row['category']; ?></div>

        <div class="row">
            <?php foreach ($opt_arr[$row['id']] as $candidate): ?>
                <div class="col-md-4 mx-auto">
                    <div class="candidate">
                        <div class="img-field">
                            <img src="assets/img/<?php echo $candidate['image_path']; ?>" alt="">
                        </div>
                        <div class="candidate_name"><?php echo $candidate['opt_txt']; ?></div>
                        <div class="vote-field">
                            <?php echo isset($v_arr[$candidate['id']]) ? number_format($v_arr[$candidate['id']]) : 0; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endwhile; ?>
    
   
    <!-- Help Modal -->
    <div class="help-modal" id="helpModal">
        <h3>Need Help?</h3>
        <p>For any issues, contact support at <strong>dharmveergarg9@gmail.com</strong></p>
        <button class="close-btn" onclick="closeHelp()">Close</button>
    </div>

    <script>
        function openHelp() {
            document.getElementById('helpModal').style.display = 'block';
        }

        function closeHelp() {
            document.getElementById('helpModal').style.display = 'none';
        }
    </script>
    

</body>
</html>
