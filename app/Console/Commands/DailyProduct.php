<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Helper;

class DailyProduct extends Command
{
  
    protected $signature = 'DailyProduct';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        echo "Initiating process @ ".date('Y-m-d H:i:s')."...\n";
        echo "Calling generateDateWiseProduct() from helper to generate report...\n";
        Helper::generateDateWiseProduct();
        echo "Report generated completed @ ".date('Y-m-d H:i:s')."...\n\n\n";
    }
}
