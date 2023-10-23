<table class="border-collapse table-auto w-full text-sm">
    <thead
        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 h-11">
    <tr>
        <th scope="col"
            class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
            Nombre
        </th>
        <th scope="col"
            class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
            Correo
        </th>
        <th scope="col"
            class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
            Ingreso
        </th>
        <th scope="col"
            class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
            Ver
        </th>
    </tr>
    </thead>
    @foreach ($users as $user)
        <tbody class="bg-white dark:bg-slate-800">
        <tr>
            <td scope="row"
                class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                {{ $user->name }}
            </td>
            <td
                class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                {{ $user->email }}
            </td>
            <td
                class=" hidden sm:flex flex-col border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                Laptop
            </td>
            <td
                class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                <a href="{{ route('detail.user.show', $user->id) }}"
                   class="font-medium text-blue-600 dark:text-blue-500 hover:underline" wire:navigate>Asignar
                    vacaciones</a>
            </td>
        </tr>
        </tbody>
    @endforeach
</table>
