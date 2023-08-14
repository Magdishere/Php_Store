<?php


/*
	** Get AllCategories Function v2.0
	** Function To Get All data from any Database table
*/

function getAllFrom($field, $table, $where, $and, $orderfield , $ordering = "DESC"){

    global $con;

    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");

    $getAll->execute();

    $all = $getAll->fetchAll();

    return $all;
}

/*
Check if user is not activate
Checks regstatus of the user
*/ 
function checkUserStatus($user){

    global $con;

    $stmtx = $con->prepare("SELECT 
                                Username, RegStatus 
                            FROM 
                                users 
                            WHERE 
                                Username = ? 
                            AND 
                                RegStatus = 0");

    $stmtx->execute(array($user));
    $status = $stmtx->rowCount();

    return $status;
}

/*
	** Check Items Function v1.0
	** Function to Check Item In Database [ Function Accept Parameters ]
	** $select = The Item To Select [ Example: user, item, category ]
	** $from = The Table To Select From [ Example: users, items, categories ]
	** $value = The Value Of Select [ Example: Osama, Box, Electronics ]
	*/

	function checkItem($select, $from, $value) {

		global $con;

		$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

		$statement->execute(array($value));

		$count = $statement->rowCount();

		return $count;
    }









/*
Title Function that Echo the page title in case the page has the variable $pageTitle,
and Echo Default otherwise
*/

function getTitle(){
    global $pageTitle;
    if(isset($pageTitle)){
        echo $pageTitle;
    }else{
        echo 'Default';
    }
}

/* Redirect function v2.0 
    - This function accepts parameters
    - $theMsg = Echo the message [Error | Success | Warning]
    - $url = the link you want to redirect to
    - $seconds = Seconds before redirecting
*/
function redirectHome($theMsg, $url = null, $seconds = 3){

    if ($url === null) {

        $url = 'index.php';

        $link = 'Homepage';

    } else {

        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previous Page';

        } else {

            $url = 'index.php';
            $link = 'Homepage';

        }

    }

    echo $theMsg;
    echo "<div class='alert alert-info'>You Will Be Redirected to $link After $seconds Seconds.</div>";
    header("refresh:$seconds;url=$url");
    exit();

}

/* 
**  Check items function v1.0
**  Function to check items in Database [Accepts parameters]
**  $select = the item to select [user, item, category,...]  
**  $from = the table to select from [users, ite,s, categories,...] 
**  $value = the value of select 
*/



    /**
     * Count Number Of Items function v1.0
     * $item = the item to count
     * $table = the table we are choosing from
     */
    function countItems($item, $table){

        global $con;

        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
        $stmt2->execute();
        return $stmt2->fetchColumn();
    }

/*
	** Get Latest Records Function v1.0
	** Function To Get Latest Items From Database [ Users, Items, Comments ]
	** $select = Field To Select
	** $table = The Table To Choose From
	** $order = The Desc Ordering
	** $limit = Number Of Records To Get
*/

    function getLatest($select, $table, $order,  $limit = 5){

        global $con;

        $getStmt = $con->prepare("SELECT * FROM $table ORDER BY $order DESC LIMIT $limit");

        $getStmt->execute();

        $rows = $getStmt->fetchAll();

        return $rows;
    }

