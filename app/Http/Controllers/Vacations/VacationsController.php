<?php

namespace App\Http\Controllers\Vacations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Resend\Laravel\Facades\Resend;

class VacationsController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.users.index')->only('show', 'update');
    }

    public function show(User $id)
    {
        return view('details.show', compact('id'));
    }


    public function update(Request $request, User $id)
    {
        $request->validate([
            'start_vacation' => ['required'],
            'end_vacation' => ['required'],
            'comments' => ['required', 'max:255'],
        ]);

        $id->update([
            'start_vacation' => $request->input('start_vacation'),
            'end_vacation' => $request->input('end_vacation'),
            'comments' => $request->comments,
        ]);

        flash()
            ->translate('es')
            ->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-right',
            ])
            ->addSuccess('Vacaciones asignadas correctamente', 'Vacaciones');

        return redirect()->route('detail.user.show', compact('id'));
    }

    public function sendMail(Request $request, User $user)
    {
        Resend::emails()->send([
            'from' => 'Acme <onboarding@resend.dev>',
            'to' => 'rkirisho@gmail.com',
            'subject' => 'Vacaciones para ' . $user->name,
            'html' => $request->comments . '<br>' . 'Fecha de inicio: ' . $request->start_vacation . '<br>' . 'Fecha de fin: ' . $request->end_vacation,
        ]);

        flash()
            ->translate('es')
            ->options([
                'timeout' => 3000, // 3 seconds algo jsjsjs
                'position' => 'bottom-right',
            ])
            ->addSuccess('Correo enviado correctamente', 'Vacaciones');
        return redirect()->route('dashboard');
    }
}
