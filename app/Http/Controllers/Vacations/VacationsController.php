<?php

namespace App\Http\Controllers\Vacations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        try {
            // Intenta enviar el correo aquí
            Resend::emails()->send([
                'from' => 'Acme <onboarding@resend.dev>',
                'to' => 'rkirisho@gmail.com',
                'subject' => 'Vacaciones para ' . Auth::user()->name,
                'blade' => view('emails.vacations', [
                    'comments' => $request->comments,
                    'start_vacation' => $request->start_vacation,
                    'end_vacation' => $request->end_vacation,
                ])->render(),
            ]);

            // Si el correo se envía correctamente, muestra un mensaje de éxito
            flash()
                ->translate('es')
                ->options([
                    'timeout' => 3000, // 3 seconds
                    'position' => 'bottom-right',
                ])
                ->addSuccess('Correo enviado correctamente, espera una respuesta de tu supervisor', 'Vacaciones');
        } catch (\Exception $e) {
            // En caso de una excepción, muestra un mensaje de error
            flash()
                ->translate('es')
                ->options([
                    'timeout' => 3000, // 3 seconds
                    'position' => 'bottom-right',
                ])
                ->addError('El correo no se pudo enviar. Error: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('dashboard');
    }
}
