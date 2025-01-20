<?php 
include 'core/init.php';

if (!Session::exists('id') && !Session::exists('name')) {
    header('Location: login.php');	
    exit;
}

if (Session::get('role') != 'super_admin') {
    header('Location: index.php');	
    exit;
}

class TossDetails {
    private $val;
    private $teamAid;
    private $teamBid;
    private $teamAname;
    private $teamBname;
    private $tossid;
    private $overs;

    public function toss($val, $overs) {
        $this->val = (int)$val; // Ensure $val is an integer
        $this->overs = (int)$overs; // Ensure $overs is an integer

        // Fetch all teams
        $sql = "SELECT * FROM team";
        $result = DB::getConnection()->select($sql);

        if ($result) {
            foreach ($result as $value) {
                $this->teamBid = (int)$value['team_id']; // Cast to integer
                $this->teamBname = $value['team_name'];
            }
        } else {
            throw new Exception("Error fetching teams.");
        }

        $this->teamAid = $this->teamBid - 1; // Subtract 1 safely

        // Fetch team name for teamAid
        $sql = "SELECT team_name FROM team WHERE team_id = $this->teamAid";
        $result = DB::getConnection()->select($sql);

        if ($result) {
            foreach ($result as $value) {
                $this->teamAname = $value['team_name'];
            }
        } else {
            throw new Exception("Error fetching team name for teamAid.");
        }

        // Determine toss winner
        if ($this->val == 1) {
            $this->tossid = $this->teamAid;
        } else {
            $this->tossid = $this->teamBid;
        }

        // Insert match details into database
        $sql = "INSERT INTO m_atch (team_Aid, team_Bid, team_Aname, team_Bname, toss, overs, isActive) 
                VALUES ('$this->teamAid', '$this->teamBid', '$this->teamAname', '$this->teamBname', '$this->tossid', '$this->overs', 1)";
        $result = DB::getConnection()->insert($sql);

        if (!$result) {
            throw new Exception("Error inserting match details.");
        }

        // Update session data
        $remainingGames = (int)Session::get('ngame');
        Session::set('ngame', $remainingGames - 1);

        // Redirect based on remaining games
        if ($remainingGames - 1 == 0) {
            header("Location: selectadmin.php");
        } else {
            header("Location: creatematch.php");
        }
        exit;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $toss = new TossDetails();
        $toss->toss($_POST["element_1"], $_POST["over"]);
    } catch (Exception $e) {
        // Log error and display a friendly message
        error_log($e->getMessage());
        echo "An error occurred: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Toss Details</title>
<link rel="stylesheet" type="text/css" href="resources/css/view.css" media="all">
<script type="text/javascript" src="resources/js/view.js"></script>
</head>
<body id="main_body">
	<img id="top" src="top.png" alt="">
	<div id="form_container">
		<h1><a>Toss Details</a></h1>
		<form id="index2" class="appnitro" method="post" action="">
			<div class="form_description">
				<h2>Toss Details</h2>
				<p>Please enter the proper info below:</p>
			</div>						
			<ul>
				<li id="li_1">
					<label class="description" for="element_1">Bat</label>
					<div>
						<select class="element select medium" id="element_1" name="element_1" required> 
							<option value="" selected="selected"></option>
							<option value="1">Team A</option>
							<option value="2">Team B</option>
						</select>
					</div>
				</li>
				<li id="li_2">
					<label class="description" for="over">Overs</label>
					<div>
						<input id="over" name="over" class="element text medium" type="number" min="1" max="50" value="" required /> 
					</div> 
				</li>
				<li class="buttons">
					<input type="hidden" name="index2" />
					<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
				</li>
			</ul>
		</form>	
	</div>
	<img id="bottom" src="bottom.png" alt="">
</body>
</html>
