<?php
	header("Content-type: text/html; charset=utf-8");
	/**
	 * [http 调用接口函数]
	 * @Date   2016-07-11
	 * @Author GeorgeHao
	 * @param  string       $url     [接口地址]
	 * @param  array        $params  [数组]
	 * @param  string       $method  [GET\POST\DELETE\PUT]
	 * @param  array        $header  [HTTP头信息]
	 * @param  integer      $timeout [超时时间]
	 * @return [type]                [接口返回数据]
	 */
	 date_default_timezone_set('PRC');
	function http($url, $params, $method = 'GET', $header = array(), $timeout = 5)
	{
	    // POST 提交方式的传入 $set_params 必须是字符串形式
	    $opts = array(
	        CURLOPT_TIMEOUT => $timeout,
	        CURLOPT_RETURNTRANSFER => 1,
	        CURLOPT_SSL_VERIFYPEER => false,
	        CURLOPT_SSL_VERIFYHOST => false,
	        CURLOPT_HTTPHEADER => $header
	    );

	    /* 根据请求类型设置特定参数 */
	    switch (strtoupper($method)) {
	        case 'GET':
	            $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
	            break;
	        case 'POST':
	            $params = http_build_query($params);
	            $opts[CURLOPT_URL] = $url;
	            $opts[CURLOPT_POST] = 1;
	            $opts[CURLOPT_POSTFIELDS] = $params;
	            break;
	        case 'DELETE':
	            $opts[CURLOPT_URL] = $url;
	            $opts[CURLOPT_HTTPHEADER] = array("X-HTTP-Method-Override: DELETE");
	            $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
	            $opts[CURLOPT_POSTFIELDS] = $params;
	            break;
	        case 'PUT':
	            $opts[CURLOPT_URL] = $url;
	            $opts[CURLOPT_POST] = 0;
	            $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
	            $opts[CURLOPT_POSTFIELDS] = $params;
	            break;
	        default:
	            throw new Exception('不支持的请求方式！');
	    }
	  
	    /* 初始化并执行curl请求 */
	    $ch = curl_init();
	    curl_setopt_array($ch, $opts);
	    $data = curl_exec($ch);
	    $error = curl_error($ch);
	    return $data;
	}
	//初始化请求参数//'name'		=>	'roadname',
		//'adcode'	   =>	'440600',//'http://restapi.amap.com/v3/traffic/status/road'
	$data = [
	    'rectangle'		=>	'113.108078,23.073092;113.197116,23.052876',
		'key'			=>	'a0e8201f84716690ae7c07155273d03a',
		'level'         =>  '5',
		'extensions'      =>        'all',
	];
	//文件读写开啥会
	$file1 = 'C:amap\\guichengp1.txt';
    $fp = fopen($file1, 'a+');
	//循环获取数据
	ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
    set_time_limit(0);// 通过set_time_limit(0)可以让程序无限制的执行下去
    $interval=180;// 每隔180s运行
     $str1="\r\n";
    do{
		$res = http('http://restapi.amap.com/v3/traffic/status/rectangle', $data, 'GET');
	    var_dump($res);
	    $content = "保存文件内容成功";
		$datenow=date("Y-m-d H:i");
		fwrite($fp, $datenow);
		fwrite($fp,$str1);
        fwrite($fp, $res);
		fwrite($fp,$str1);
       // echo '测试'.time().'<br/>'; 
        sleep($interval);// 等待5s    
      }while(true);
	//发送HTTP请求，并获取返回值
	//$res = http('http://restapi.amap.com/v3/traffic/status/road', $data, 'GET');
	//var_dump($res);
	//$content = "保存文件内容成功";
//fwrite($fp, $res);
fclose($fp);
	
?>
