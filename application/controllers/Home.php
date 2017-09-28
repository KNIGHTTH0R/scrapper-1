<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use Raulr\GooglePlayScraper\Scraper;

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('home');
	}


	public function process(){
		if($this->input->post("txtUrl")){
			$url = trim($this->input->post("txtUrl",TRUE));


			

			if(strpos($url, 'https://itunes.apple.com/') !== FALSE){

				// itunes

				// get the id from the url
				$start = strpos($url,"/id");

				$end = strpos($url,"?");

				$id = substr($url, $start + 3, 9);

				$api = "https://itunes.apple.com/rss/customerreviews/id=".$id."/json";
				

				$res = $this->fetchUrl($api);

				if($res["status"]){
					$r = json_decode($res["response"],TRUE);

					if(strpos($url, 'https://itunes.apple.com/') !== FALSE){
						$reviews = [];

						$list = $r["feed"]["entry"];

						foreach ($list as $row) {
							if(isset($row["author"])){

								$new = array(
									"username" => $row["author"]["name"]["label"],
									"date" => null,
									"rating" => $row["im:rating"]["label"],
									"comment" => $row["content"]["label"],
									"link" => $row["link"]["attributes"]["href"]
								);
								array_push($reviews, $new);
							}
						}

						// $data["reviews"] = $reviews;
						// //$this->load->view("results",$data);

						$this->generateCsv($reviews);

					}

					
				}
				else{
					show_error($res["response"]);
				}
			}
			else if(strpos($url, "https://play.google.com/") !== FALSE){

				// google play

				$id = substr($url, strpos($url, "?id=") + 4);

				$scraper = new Scraper();
				$app = $scraper->getApp($id);

				if(empty($app)){
					show_error("Something unexpected happened. Please try again");
				}
				else{
					$x = $app["reviews"];

					$newArray = [];


					foreach ($x as $key => $v) {
						$y = json_decode($v,TRUE);

						$z = array(
							"username" => trim($y["username"]),
							"date" => trim($y["date"]),
							"rating" => substr(trim($y["rating"]),6,1),
							"comment" => substr(trim($y["comment"]),0,strlen(trim($y["comment"])) - 12),
							"link" => trim($y["link"])
						);

						array_push($newArray, $z);
					}

					$this->generateCsv($newArray);
				}
			}
			else{
				show_error("Sorry url not supported");
			}
		}
		else{
			redirect(base_url());
		}
	}


	public function test(){
		
		$scraper = new Scraper();
		$app = $scraper->getApp('com.eero.android');

		$x = $app["reviews"];

		$newArray = [];


		foreach ($x as $key => $v) {
			$y = json_decode($v,TRUE);

			$z = array(
				"username" => trim($y["username"]),
				"date" => trim($y["date"]),
				"rating" => substr(trim($y["rating"]),6,1),
				"comment" => substr(trim($y["comment"]),0,strlen(trim($y["comment"])) - 12),
				"link" => trim($y["link"])
			);

			array_push($newArray, $z);
		}

		$data["reviews"] = $newArray;
		$this->load->view("results",$data);
	}


	private function fetchUrl($url){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		   CURLOPT_HTTPGET => 1,
		  CURLOPT_RETURNTRANSFER => TRUE
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return  array("status"=>FALSE,"response"=>"cURL Error #:" . $err);
		} else {
		  return array("status"=>TRUE,"response"=>$response);
		}
	}

	private function generateCsv($data){
		$this->load->library('excel');

		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);

		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('Reviews');

		$this->excel->getActiveSheet()->setCellValue('a1','Username');
		$this->excel->getActiveSheet()->setCellValue('b1','Date');
		$this->excel->getActiveSheet()->setCellValue('c1','Star Rating');
		$this->excel->getActiveSheet()->setCellValue('d1','Review Comment');
		$this->excel->getActiveSheet()->setCellValue('e1','Link');

		// read data to active sheet
		$this->excel->getActiveSheet()->fromArray($data,'N/A','A2');

		$filename='reviews.csv'; //save our workbook as this file name

	    header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
 
        header('Cache-Control: max-age=0'); //no cache
                    
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
 
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'CSV'); 
 
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
	}


	// CURLOPT_SSL_VERIFYHOST => 0,
	// 	  CURLOPT_SSL_VERIFYPEER => 0
}
