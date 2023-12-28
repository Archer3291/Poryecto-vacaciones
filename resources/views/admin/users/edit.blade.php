<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col gap-3">
                    <div class="flex">
                        <h1 class="text-2xl">Asigna un rol al usuario:</h1>

                        <!--------------- Boton de ayuda ------------------->
                        <button data-popover-target="popover-description" data-popover-placement="right"
                                type="button">
                            <svg class="w-5 h-5 ms-2 text-white hover:text-gray-500" aria-hidden="true"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Show information</span></button>
                        <div data-popover id="popover-description" role="tooltip"
                             class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                            <div class="p-3 space-y-2">
                                Aqui podras cambiar el rol del usuario seleccionado.
                            </div>
                            <div data-popper-arrow></div>
                        </div>
                        <!------------------------------------>
                    </div>
                    <x-text-input class="block mt-1 w-full disabled" disabled type="text" value="{{ $user->name }}"/>
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
    setTimeout(function () {
        document.getElementById('session-status').style.display = 'none';
    }, 5000);
</script>
