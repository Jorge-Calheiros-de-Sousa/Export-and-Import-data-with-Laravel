<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Usuários</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
   <section class="antialiased bg-gray-100 text-gray-600 h-screen px-4">
    <div class="flex flex-col justify-center h-full ">
        <!-- Table -->
        <div class="w-full max-w-2xl mx-auto bg-white shadow-lg rounded-sm border border-gray-200">
            <x-system.messege />
            <header class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Exportar e Importar Usuários</h2>
            </header>
           <div class="flex justify-center m-2 p-5 box-border">
            <div class="w-2/4 border-2 rounded-md p-2">
                <form method="POST" action="{{ route("setting.importUsers") }}" enctype="multipart/form-data" class="w-full flex flex-col justify-end">
                    @csrf
                    <label for="users-file">Import users</label>
                    <input type="file" name="users-file">
                    <button class="bg-green-300 p-3 rounded-md mt-5">Import</button>
                </form>
            </div>
            <div class="w-2/4 border-2 rounded-md ml-5 p-2 ">
                <form method="GET" action="{{ route("setting.exportUsers") }}" class="w-full flex flex-col h-full justify-end">
                    <label for="">Export users</label>
                    <button class="bg-green-300 p-3 rounded-md mt-5">Export</button>
                </form>
            </div>
           </div>
            <header class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Usuários</h2>
            </header>
            <div class="p-3">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                            <tr>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Name</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Email</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-center"></div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            @foreach ($users as $user)
                            <tr>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $user->email }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div>
                    {{ $users->links("pagination::tailwind") }}
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>
