<form action="{{ route('vacations.mail') }}" method="POST">
    @csrf
    <hr class="my-4 border-t border-gray-300">
    <!-- Vacations form -->
    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

    <div>
        <x-input-label for="start_vacation" :value="__('Fecha de inicio')"/>
        <x-text-input wire:model="start_vacation" id="start_vacation" class="block mt-1 w-full" type="date"
                      name="start_vacation" required autofocus autocomplete="start_vacation"/>
        <x-input-error :messages="$errors->get('start_vacation')" class="mt-2"/>
    </div>

    <div>
        <x-input-label for="end_vacation" :value="__('Fecha de termino')"/>
        <x-text-input wire:model="end_vacation" id="end_vacation" class="block mt-1 w-full" type="date"
                      name="end_vacation" required autofocus autocomplete="end_vacation"/>
        <x-input-error :messages="$errors->get('end_vacation')" class="mt-2"/>
    </div>

    <div>
        <x-input-label for="comments" :value="__('Comentarios')"/>
        <textarea name="comments" id="comments" cols="50" rows="10"
                  class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
        <x-input-error :messages="$errors->get('comments')" class="mt-2"/>
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-primary-button class="ml-4">
            {{ __('Solicitar vacaciones') }}
        </x-primary-button>
    </div>
</form>
