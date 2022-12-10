<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;
use Dashboard\Http\Controllers\Controller;

class ImageSyncController extends Controller
{
    public function imagesync(){
    	echo exec('whoami');
    	

    	//$connection = '/usr/bin/sshpass -p mehnaz@26 /usr/bin/ssh -p 22 -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null rsync /home/farida/Backend/test.sh tech@172.31.13.56:/home/farida/Backend';
    	//$connection = '/usr/bin/sshpass -p mehnaz@26 /usr/bin/ssh -p 22 -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null rsync /home/farida/Backend/test.sh tech@172.31.13.56:/home/farida/Backend';
    	$connection = 'sshpass -p mehnaz@226 rsync -avz /home/farida/Backend/test.sh tech@172.31.13.56:/home/farida/Backend';
    	
    	//$connection = 'sh /home/farida/Backend/imagesync.sh';

    	exec($connection ." 2>&1", $output);
    	
    	print_r($output);
    }
}
