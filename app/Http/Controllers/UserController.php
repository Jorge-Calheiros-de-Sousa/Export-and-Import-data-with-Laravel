<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportUserRequest;
use App\Jobs\ExportDatabaseRegister;
use App\Jobs\ImportDatabaseRegister;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Services\Contracts\UploadFileServiceContract;
use Exception;

class UserController extends Controller
{
    private UserRepositoryContract $repository;

    public function __construct(UserRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        if ($users = $this->repository->paginateWithSearch(5)) {
            return view("users", compact("users"));
        }
    }
    public function importUsers(ImportUserRequest $request, UploadFileServiceContract $fileService)
    {
        try {
            $userRepository = app(UserRepositoryContract::class);

            $file = $request->except("_token");

            if (!$filePath = $fileService->run($file["users-file"], "/import/users")) {
                throw new Exception($filePath);
            }
            $job = new ImportDatabaseRegister($userRepository, $filePath);
            $job->onQueue("imports");
            $this->dispatch($job);
            return back()->with(["success-message" => "Sucesso!"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function exportUsers()
    {
        $userRepository = app(UserRepositoryContract::class);
        $job = new ExportDatabaseRegister($userRepository, "/exports/users");
        $job->onQueue("exports");
        $this->dispatch($job);
        return back()->with(["success-messege" => "Sucesso!"]);
    }
}
