<?php

namespace Dashboard\Classes\Helpers;

class UrlShortener {
	protected $urls = '';

  /*get fg short url */
	public static function get_fg_short_url($url){
 
        $connectURL = 'http://fgurl.in/api/url-short';
        $header = self::get_token();
  //       $data = [
  //           'url' => $url,
		//     'custom_url' => ''
		// ];
		return self::curl_get_result_new($connectURL,$url, '', $header);
        
	}


	public static function modify_fg_short_url( $url, $custom_url ){
		$editCustomUrl = 'http://fgurl.in/api/url-edit';
		$header = self::get_token();
		return self::curl_get_result_new($editCustomUrl,$url,$custom_url,$header);

	} 

	public static function get_url_shorten_log(){
		$customUrl = 'http://fgurl.in/api/url-getLog';
		$header = self::get_token();
		$data = self::curl_get_result_new( $customUrl, '', '', $header );
		return $data;
	}
	public static function curl_get_result_new($connectURL,$url,$updateUrl='',$header) {
		$ch      = curl_init();
		$timeout = 30;
		//$postUrl['url'] = str_replace('\/', '/',  json_encode($lurl));

		curl_setopt($ch, CURLOPT_URL, $connectURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($lurl));

		//curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n\t\n\"url\":\"$url\"\n}");
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n\t\n\"url\":\"$url\"\n,\n\t\n\"custom_url\":\"$updateUrl\"\n}");
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$data = curl_exec($ch);
		curl_close($ch);

		return $data;

	}

	public static function get_token(){
		$header = array(
		    "Accept: application/json",
		    "Accept-Encoding: gzip, deflate",
		    "Content-Type: application/json",
		      "Content-Type: application/json",
		  //  "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjU2YTZiY2E2Nzc0ZTMyMDUxNTc0Mjg3YjQ1OTBiMTJlYjMyOTJiMTZiZDI3MzcwY2U3NGZmZjQ0ODc2OGI1YjI3MmU0YTg0YmFiYjhlOTQyIn0.eyJhdWQiOiI0IiwianRpIjoiNTZhNmJjYTY3NzRlMzIwNTE1NzQyODdiNDU5MGIxMmViMzI5MmIxNmJkMjczNzBjZTc0ZmZmNDQ4NzY4YjViMjcyZTRhODRiYWJiOGU5NDIiLCJpYXQiOjE1ODIwMjQ0OTUsIm5iZiI6MTU4MjAyNDQ5NSwiZXhwIjoxNTgzMzIwNDk1LCJzdWIiOiIiLCJzY29wZXMiOltdfQ.G2Z9GYaG6sBrplCV5V6jLBD4wXrQTVj1rWegCXQ6_sprx1tS8ODXxmYWXBLe2Sf3laau1uURcLKpg7heSmxoj58P0pEmeHc1OAvmWGdn-1SOtZQYu61iJ3vFw9hnVLiQRT5rdkw79_ZoNeWqOD7MBUgofaXTr0BVQ0cnDeNxwRaiRkKkOdy53YkP3CATA5EDckQ62ZjuZ_pZ9PJZJ9HG5MThUbFiRNlkc7hfMhx6Qubqum4v6r3mFTyla2O0uQscDCTz97a5ebFyR21zWs2mu0PMVem_3AQwWPkV5PeMZEaD2_4pflHHPTktMZR2gnckqn-mHXva6W-RQoqkzsDzxDSVZ_5VJw1wCI9AfP4qBuAkg3kRwicK-k4_yk4PJ39EHRmfoOPYrXaRm3i48M_ZreJy1Ck-E2BdjArmEo7iw0RCiTaEv29xe1zzngM7a7pcmyKY7cgAzWwppwDgMgMjZTU25AA-xV9NQ0F6ao8iSDgCdCWLS5LE-vO2i7puVAmkhTPjQwTbp59qj6Zo0_8o3kAOunojJZkhL0hqy3DBTwPI5X7vL-g45caTYxuZBBqbw9NthfEusEwk3WYMWW8e2BxvXFDiK5Th1-DG2LOC-1_U0E2d3Z2CXcMZNM8ouUc0C8C5E3OFGTJKKr8iscpuqDV438pSKvmpIswwkpc4W9o");
		    "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImRhY2MxNzRmZjBkMDhmYWE3NjRiYmNiY2ZhZTFkMGMwNTY0NjNlODE4OWJkZDVkNjg0YzEzODlkN2IxOWU5NWQxOTIyMDY2YzU1YTViY2I2In0.eyJhdWQiOiI0IiwianRpIjoiZGFjYzE3NGZmMGQwOGZhYTc2NGJiY2JjZmFlMWQwYzA1NjQ2M2U4MTg5YmRkNWQ2ODRjMTM4OWQ3YjE5ZTk1ZDE5MjIwNjZjNTVhNWJjYjYiLCJpYXQiOjE1ODM5OTczNDAsIm5iZiI6MTU4Mzk5NzM0MCwiZXhwIjoxNTg1MjkzMzQwLCJzdWIiOiIiLCJzY29wZXMiOltdfQ.nZTJPVeO6Q1tDcRpuqXua-hY0O_unHvWGOwVEqd-ffhtBe7Ug7Qc9hYYpLjVRLCLszceEQwBAYkJ5SPfrMuFofPK6nhW52VWXnFKUyMt7JaaDSfiS2qgdFVO2ivTvwvc1nPOJ2sl-KGjtyalIjdBcZPwQGqVwauRTM4svGPQCSVclTg3cPV02QQKVKoYKuDHAv2FJEyDZebOhidVUAKZ82xdkAAsDmDhE2FndWHbjMzqLHMKI3lhVfW-X8QkrOGTiCI8vYquKpB_B9HE9uxQ_9fGOXxulAlKmHpi80qPv88IJJRTjLDOP8fnNbzKJ-tg2DD-wXeOllF0Ha8BB-6JdWbpGrofDIFvG-RfEVVI-8P7MJ3LHUpy3PWl4ep7bvZs0FqfB8NOGNycmoHHF-PrUs9Ii9w4zXkwGG5MdMvIGZi7ldN8QAMTRLHMONgLNALZcS0tH3GP2wRepMyJIEC2HCCl0gDrMORTIBK5qrO05FB04AbwYuOEcKWhCttH6JoIfjgD-JWhYRhegTQ0AQV5EWzdvBWFKzPmmdMXIa-t7MEmhK07clHV7aAElsrCEUyywRDxdlCusMluu3d9j4bkonFjYnxSYL-WaSoE5jMLtutaKCJDMFN_X_3vONgOaMrCAapXwyQHADZNXrL9X3gPbo64W5Ixq71pznxlnsN_Y8A");

			return $header;
	} 


	
}