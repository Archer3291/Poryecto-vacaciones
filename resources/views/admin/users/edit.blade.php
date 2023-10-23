<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col gap-3">
                    <h1 class="text-2xl">Asigna un rol al usuario:</h1>
                    <x-text-input class="block mt-1 w-full disabled" disabled type="text" value="{{ $user->name }}" />

                    <h2 class="text-xl"> Roles disponibles </h2>
                    {!! Form::model($user, ['route' => ['admin.users.update', $user], 'method' => 'put']) !!}
                    @foreach ($roles as $role)
                        <div>
                            <label>
                                {!! Form::checkbox('roles[]', $role->id, null, [
                                    'class' =>
                                        'w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600',
                                ]) !!}
                                {{ $role->name }}
                            </label>
                        </div>
                    @endforeach
                    <div class="flex items-center gap-3">
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                            href="{{ route('admin.users.index') }}">
                            Regresar
                        </a>
                        <x-primary-button class="mt-4">
                            {{ __('Update') }}
                        </x-primary-button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (session('info'))
        <div class="max-w-7xl bottom-14 absolute sm:px-6 lg:px-8 mt-4 w-auto" id="session-status">
            <div class="bg-green-500 dark:bg-green-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-white">
                    {{ session('info') }}
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
<script>
    setTimeout(function() {
        document.getElementById('session-status').style.display = 'none';
    }, 5000);
</script>
