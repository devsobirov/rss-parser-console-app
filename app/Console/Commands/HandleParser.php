<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class HandleParser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rss-parser:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Инициализирует класс Parser и запускает главный метод';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $parser = new \App\Http\Controllers\ParserController();
        //$parser->parse();

        $this->call('schedule:work');
    }

}
