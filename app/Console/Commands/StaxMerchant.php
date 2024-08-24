<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Vendor\StaxConnect;

class StaxMerchant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:stax-merchant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Merchants Status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$stax_connect = new StaxConnect();
		$stax_connect->update_merchants_status();
		
        //return Command::SUCCESS;
    }
}
