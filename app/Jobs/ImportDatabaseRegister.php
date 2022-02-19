<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportDatabaseRegister implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $repository;
    private $filePath;
    private const NAME_COL = 0;
    private const EMAIL_COL = 1;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($repository, string $filePath)
    {
        $this->repository = $repository;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = storage_path("app/" . $this->filePath);
        $users = [];
        $row = 1;
        $password = [];

        try {
            if (($handle = fopen($file, "r")) !== false) {
                fgetcsv($handle, 0, ",");
                while (($dataCols = fgetcsv($handle, 0, ",")) !== false) {
                    if ($this->repository->findValue("email", $dataCols[self::EMAIL_COL])) {
                        $row++;
                        continue;
                    }
                    $pass = Str::random(22);
                    if ($row != 0) {
                        $users[] = [
                            "name" => $dataCols[self::NAME_COL],
                            "email" => $dataCols[self::EMAIL_COL],
                            "password" => Hash::make($pass),
                            "created_at" => Carbon::now(),
                            "updated_at" => Carbon::now(),
                        ];
                        $password[] = [
                            "password" => $pass,
                        ];
                    }
                    $row++;
                }
            }
            if (count($users) > 0) {
                $this->repository->import($users);
                return true;
            }
        } catch (\Throwable $th) {
            Log::error('ImportUsers@handle --- ' . $th->getMessage() . PHP_EOL . $th->getTraceAsString());
            return false;
        }
    }
}
