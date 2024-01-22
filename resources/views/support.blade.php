<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Support') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col gap-4">
                    <p>
                        Si se presenta algun error con la funcionalidad del sistema favor de contactar al soporte
                        tecnico.
                    </p>

                    @foreach($users as $user)
                        <p>
                            {{ ucfirst($user->name) }} -
                            <a class="text-black dark:text-white hover:text-blue-600" href="mailto:{{ $user->email }}"
                               target="_blank">{{ $user->email }}</a>
                        </p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
