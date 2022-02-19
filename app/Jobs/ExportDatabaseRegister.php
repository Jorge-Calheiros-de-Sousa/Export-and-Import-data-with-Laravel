<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File as FacadesFile;

class ExportDatabaseRegister implements ShouldQueue
{
    private $repository;
    private $directory;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($repository, $directory)
    {
        $this->repository = $repository;
        $this->directory = $directory;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = $this->repository->export();

        if (count($users) < 1) {
            return;
        }

        $fileName = $this->generateFileName();
        $filePath = $this->generateDirectory($fileName);
        $headers = $this->getHeaders($users);
        $rows = $this->getRows($users, $headers);
        $this->generateFile($rows, $headers, $filePath);
    }


    private function generateFileName()
    {
        return uniqid("export_") . "_" . $this->repository->getTable()
            . "_" . Carbon::now()->format("y-m-d") . "_" . "csv";
    }

    public function generateDirectory($fileName)
    {
        $fullFileDirectory = storage_path("app/" . $this->directory);
        if (!is_dir($fullFileDirectory)) {
            FacadesFile::makeDirectory($fullFileDirectory, 0755, true);
        }
        return "$fullFileDirectory/$fileName";
    }

    /** @var Model $registers */
    public function getHeaders($registers)
    {
        $attributes = $registers->first()->getAttributes();
        $hidden = $registers->first()->getHidden();
        return array_diff(array_keys($attributes), $hidden);
    }

    public function getRows($registers, $headers)
    {
        $rows = [];
        foreach ($registers as $register) {
            $attributes = $register->getAttributes();
            $rows[] = collect($attributes)->only($headers)->toArray();
        }
        return $rows;
    }
    private function generateFile($rows, $headers, $filePath)
    {
        if (count($rows) < 1) {
            return;
        }

        $file = fopen($filePath, "w");
        fputcsv($file, $headers);

        foreach ($rows as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    }
}
