<?php 
class analystratemodel extends Model{
	public $mysqli;			/**********  MYSQL Array **************************/
/******************************** RC ******************************************/
/*----------------------- MAIN CONSTRUCT ---------------------------------------
	@PASSING ATTRIBUTES ARE      :  CONSTRUCT FUNCTION 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
	public function __construct($con){
		parent::__construct();
		$this->mysqli = $con;
	}
/******************************** RC ******************************************/
/*----------------------- MODEL DEBUG ARRAY ------------------------------------
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	@FUNCTION DATE               :  24-04-2019 
------------------------------------------------------------------------------*/ 	
	public function debug($array) {
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}
/******************************** RC ******************************************/
/*----------------------- ANALYST RATE REPORT ----------------------------------
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	@FUNCTION DATE               :  24-04-2019 
------------------------------------------------------------------------------*/ 
	public function	analystreport(){
		$sql		=	"SELECT name,analyst FROM worksheets INNER JOIN users ON users.id=worksheets.analyst WHERE review_user_id!=0 GROUP BY analyst,name ORDER BY analyst";
		$result		=	$this->mysqli->query($sql);
		$i=0;
		while ($row = $result->fetch_assoc()){
			$data[$i]['analyst']	=	$row['analyst'];
			$data[$i]['name']		=	$row['name'];
/***********************************************************************/
############ STAR RATING
/***********************************************************************/			
			$one	=	$this->mysqli->query("SELECT COUNT(analyst) AS onestar FROM worksheets WHERE analyst='".$row['analyst']."' AND second_check_rate=1");
			$oneVal					=	$one->fetch_object();
			$data[$i]['oneStar']	=	$oneVal->onestar;
			$totalOne				=	$oneVal->onestar*1;
			$two	=	$this->mysqli->query("SELECT COUNT(analyst) AS twoStar FROM worksheets WHERE analyst='".$row['analyst']."' AND second_check_rate=2");
			$twoVal					=	$two->fetch_object();
			$data[$i]['twoStar']	=	$twoVal->twoStar;	
			$totalTwo				=	$twoVal->twoStar*2;
			$three	=	$this->mysqli->query("SELECT COUNT(analyst) AS threeStar FROM worksheets WHERE analyst='".$row['analyst']."' AND second_check_rate=3");	
			$threeVal				=	$three->fetch_object();
			$data[$i]['threeStar']	=	$threeVal->threeStar;
			$totalThree				=	$threeVal->threeStar*3;
			$four	=	$this->mysqli->query("SELECT COUNT(analyst) AS fourStar FROM worksheets WHERE analyst='".$row['analyst']."' AND second_check_rate=4");
			$fourVal				=	$four->fetch_object();
			$data[$i]['fourStar']	=	$fourVal->fourStar;
			$totalFour				=	$fourVal->fourStar*4;		
			$five	=	$this->mysqli->query("SELECT COUNT(analyst) AS fiveStar FROM worksheets WHERE analyst='".$row['analyst']."' AND second_check_rate=5");	
			$fiveVal				=	$five->fetch_object();
			$data[$i]['fiveStar']	=	$fiveVal->fiveStar;
			$totalFive				=	$fiveVal->fiveStar*5;
			$totalRateCount			=	$oneVal->onestar+$twoVal->twoStar+$threeVal->threeStar+$fourVal->fourStar+$fiveVal->fiveStar;
			if($totalRateCount>0){
				$avgRate				=	$totalOne+$totalTwo+$totalThree+$totalFour+$totalFive/$totalRateCount;
			}
			else{
				$avgRate				=	0;
			}
			$data[$i]['avgeRate']	=	$avgRate;
/***********************************************************************/
############ STAR RATING COUNT
/***********************************************************************/
			$totalStudySql		=	$this->mysqli->query("SELECT COUNT(analyst) AS totalStudy FROM worksheets WHERE analyst=".$row['analyst']);
			$totalStudyVal		=	$totalStudySql->fetch_object();
			$data[$i]['totalStudy']		=	$totalStudyVal->totalStudy;
			$completedSql		=	$this->mysqli->query("SELECT COUNT(analyst) AS completed FROM worksheets WHERE analyst='".$row['analyst']."' AND status='Completed'");
			$completedVal		=	$completedSql->fetch_object();
			$data[$i]['completed']		=	$completedVal->completed;
			$cancelSql		=	$this->mysqli->query("SELECT COUNT(analyst) AS cancel FROM worksheets WHERE analyst='".$row['analyst']."' AND status='Cancelled'");
			$cancelVal		=	$cancelSql->fetch_object();
			$data[$i]['cancel']		=	$cancelVal->cancel;
			$reviewSql		=	$this->mysqli->query("SELECT COUNT(analyst) AS review FROM worksheets WHERE analyst='".$row['analyst']."' AND status='Under review'");
			$reviewVal		=	$reviewSql->fetch_object();
			$data[$i]['review']		=	$reviewVal->review;
			$holdSql		=	$this->mysqli->query("SELECT COUNT(analyst) AS hold FROM worksheets WHERE analyst='".$row['analyst']."' AND status='On hold'");
			$holdVal		=	$holdSql->fetch_object();
			$data[$i]['hold']		=	$holdVal->hold;
			$progressSql		=	$this->mysqli->query("SELECT COUNT(analyst) AS progress FROM worksheets WHERE analyst='".$row['analyst']."' AND status='In progress'");
			$progressVal		=	$progressSql->fetch_object();
			$data[$i]['progress']		=	$progressVal->progress;
							
			$i++;
		}
		return $data;
	}
/******************************** RC ******************************************/
/*----------------------- ANALYST RATE REPORT DATE RANGE ----------------------
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	@FUNCTION DATE               :  24-04-2019 
------------------------------------------------------------------------------*/ 
	public function	analystreportDate($startDate,$endDate){
		$sql		=	"SELECT name,analyst FROM worksheets INNER JOIN users ON users.id=worksheets.analyst WHERE review_user_id!=0 AND ( `date` BETWEEN '$startDate' AND '$endDate') GROUP BY analyst,name ORDER BY analyst";
		$result		=	$this->mysqli->query($sql);
		$i=0;
		while ($row = $result->fetch_assoc()){
			$data[$i]['sql']		=	$sql;
			$data[$i]['analyst']	=	$row['analyst'];
			$data[$i]['name']		=	$row['name'];
/***********************************************************************/
############ STAR RATING
/***********************************************************************/			
			$one	=	$this->mysqli->query("SELECT COUNT(analyst) AS onestar FROM worksheets WHERE analyst='".$row['analyst']."' AND second_check_rate=1");
			$oneVal					=	$one->fetch_object();
			$data[$i]['oneStar']	=	$oneVal->onestar;
			$totalOne				=	$oneVal->onestar*1;
			$two	=	$this->mysqli->query("SELECT COUNT(analyst) AS twoStar FROM worksheets WHERE analyst='".$row['analyst']."' AND second_check_rate=2");
			$twoVal					=	$two->fetch_object();
			$data[$i]['twoStar']	=	$twoVal->twoStar;	
			$totalTwo				=	$twoVal->twoStar*2;
			$three	=	$this->mysqli->query("SELECT COUNT(analyst) AS threeStar FROM worksheets WHERE analyst='".$row['analyst']."' AND second_check_rate=3");	
			$threeVal				=	$three->fetch_object();
			$data[$i]['threeStar']	=	$threeVal->threeStar;
			$totalThree				=	$threeVal->threeStar*3;
			$four	=	$this->mysqli->query("SELECT COUNT(analyst) AS fourStar FROM worksheets WHERE analyst='".$row['analyst']."' AND second_check_rate=4");
			$fourVal				=	$four->fetch_object();
			$data[$i]['fourStar']	=	$fourVal->fourStar;
			$totalFour				=	$fourVal->fourStar*4;		
			$five	=	$this->mysqli->query("SELECT COUNT(analyst) AS fiveStar FROM worksheets WHERE analyst='".$row['analyst']."' AND second_check_rate=5");	
			$fiveVal				=	$five->fetch_object();
			$data[$i]['fiveStar']	=	$fiveVal->fiveStar;
			$totalFive				=	$fiveVal->fiveStar*5;
			$totalRateCount			=	$oneVal->onestar+$twoVal->twoStar+$threeVal->threeStar+$fourVal->fourStar+$fiveVal->fiveStar;
			$avgRate				=	$totalOne+$totalTwo+$totalThree+$totalFour+$totalFive/$totalRateCount;
			$data[$i]['avgeRate']	=	$avgRate;
/***********************************************************************/
############ STAR RATING COUNT
/***********************************************************************/
			$totalStudySql		=	$this->mysqli->query("SELECT COUNT(analyst) AS totalStudy FROM worksheets WHERE analyst=".$row['analyst']);
			$totalStudyVal		=	$totalStudySql->fetch_object();
			$data[$i]['totalStudy']		=	$totalStudyVal->totalStudy;
			$completedSql		=	$this->mysqli->query("SELECT COUNT(analyst) AS completed FROM worksheets WHERE analyst='".$row['analyst']."' AND status='Completed'");
			$completedVal		=	$completedSql->fetch_object();
			$data[$i]['completed']		=	$completedVal->completed;
			$cancelSql		=	$this->mysqli->query("SELECT COUNT(analyst) AS cancel FROM worksheets WHERE analyst='".$row['analyst']."' AND status='Cancelled'");
			$cancelVal		=	$cancelSql->fetch_object();
			$data[$i]['cancel']		=	$cancelVal->cancel;
			$reviewSql		=	$this->mysqli->query("SELECT COUNT(analyst) AS review FROM worksheets WHERE analyst='".$row['analyst']."' AND status='Under review'");
			$reviewVal		=	$reviewSql->fetch_object();
			$data[$i]['review']		=	$reviewVal->review;
			$holdSql		=	$this->mysqli->query("SELECT COUNT(analyst) AS hold FROM worksheets WHERE analyst='".$row['analyst']."' AND status='On hold'");
			$holdVal		=	$holdSql->fetch_object();
			$data[$i]['hold']		=	$holdVal->hold;
			$progressSql		=	$this->mysqli->query("SELECT COUNT(analyst) AS progress FROM worksheets WHERE analyst='".$row['analyst']."' AND status='In progress'");
			$progressVal		=	$progressSql->fetch_object();
			$data[$i]['progress']		=	$progressVal->progress;
							
			$i++;
		}
		return $data;
	}
/******************************** RC ******************************************/
/*----------------------- ANALYST DETAILS BY RATE -----------------------------
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	@FUNCTION DATE               :  09-05-2019 
------------------------------------------------------------------------------*/ 
	public function getratedetails($reviewid,$rate){
		$sql		=	"SELECT users.name,worksheets.review_user_id,worksheets.second_analyst_hours,worksheets.second_comment,Clario.site,worksheets.second_check_date,Clario.accession,Clario.mrn FROM worksheets INNER JOIN users ON users.id=worksheets.review_user_id LEFT JOIN Clario ON Clario.id=worksheets.clario_id  WHERE analyst='$reviewid'  AND worksheets.second_check_rate='$rate'";
		//return $sql;
		$result		=	$this->mysqli->query($sql);
		while ($row = $result->fetch_assoc()){
			$data[]		=	$row;
		}
		return $data;
	}
	
	
	
	
	public function	analystreportTest(){
		$sql		=	"SELECT name,analyst,customer_id,
						(CASE WHEN second_check_rate=1 THEN COUNT(second_check_rate)END) AS oneStar,
						(CASE WHEN second_check_rate=2 THEN COUNT(second_check_rate)END) AS twoStar,
						(CASE WHEN second_check_rate=3 THEN COUNT(second_check_rate)END) AS threeStar,
						(CASE WHEN second_check_rate=4 THEN COUNT(second_check_rate)END) AS fourStar,
						(CASE WHEN second_check_rate=5 THEN COUNT(second_check_rate)END) AS fiveStar
						FROM worksheets INNER JOIN users ON users.id=worksheets.analyst 
						WHERE review_user_id!=0 GROUP BY analyst,name,customer_id ORDER BY analyst";	
		$result		=	$this->mysqli->query($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()){
				$sqlInner	=	"SELECT 
								(CASE WHEN analyst THEN COUNT(analyst)END) AS totalStudy,
								(CASE WHEN status='Completed' THEN COUNT(status)END) AS completed,
								(CASE WHEN status='Cancelled' THEN COUNT(status)END) AS cancel,
								(CASE WHEN status='Under review' THEN COUNT(status)END) AS 	review,
								(CASE WHEN status='On hold' THEN COUNT(status)END) AS hold,
								(CASE WHEN status='In progress' THEN COUNT(status)END) AS progress
								FROM worksheets
								WHERE analyst=".$row['analyst'];
				$resultNew	=	$this->mysqli->query($sqlInner);
				if ($resultNew->num_rows > 0) {	
					$rowNew 		= 	$resultNew->fetch_row();
					$dataArray		=	array_merge($row,$rowNew);
					$data[] 		= 	$dataArray;	
				}
			}
		}
		return $data;
	}
	
	
public function	getdatavalue(){
	$sql	=	"SELECT * FROM worksheets
WHERE date(date) >=   '2019-05-09 18:21:20'  && date(date)  <= '2019-05-29 00:00:00' AND status = 'Completed' AND addon_flows <> 'null'";
	$result		=	$this->mysqli->query($sql);	
	while ($row = $result->fetch_assoc()){
			$data[]		=	$row;
		}
		return $data;
}
public function insertdatavalue($worksheet_id,$customer_id,$date,$ans_id,$ans_hr,$qty,$rate){
	$sql_query = "INSERT INTO `worksheet_details` (`worksheet_id`,`customer_id`, `date`, `ans_id`,`ans_hr`, `rate`, `qty`) 
													   VALUES ('$worksheet_id','$customer_id','$date', '$ans_id', '$ans_hr', '$rate', '$qty')";
    $result = $this->mysqli->query($sql_query);
	return $sql_query;
}
    public function getexacttime($analysesid){
        $sql            =   "SELECT analyses.minimum_time FROM analyses WHERE id='$analysesid'";
        $result         =   $this->mysqli->query($sql);
        if ($result->num_rows > 0) {
        while ($row     =   $result->fetch_assoc()){
            $data   =   $row['minimum_time'];
        }
        }
        return $data;
    }    
}

