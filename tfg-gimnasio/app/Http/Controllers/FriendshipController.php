<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendshipController extends Controller
{
    // Mostrar página de amigos con sugerencias y búsqueda
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Amigos aceptados
        $friends = $user->friends;

        // Solicitudes pendientes enviadas
        $sentRequests = Friendship::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with('friend')
            ->get();

        // Solicitudes pendientes recibidas
        $receivedRequests = Friendship::where('friend_id', $user->id)
            ->where('status', 'pending')
            ->with('user')
            ->get();

        // Sugerencias: usuarios que no son amigos ni tienen solicitudes pendientes
        $friendIds = $friends->pluck('id');
        $suggestedUsers = User::where('id', '!=', $user->id)
            ->whereNotIn('id', $friendIds)
            ->whereDoesntHave('sentFriendRequests', function ($query) use ($user) {
                $query->where('friend_id', $user->id)->where('status', 'pending');
            })
            ->whereDoesntHave('receivedFriendRequests', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('status', 'pending');
            })
            ->limit(10)
            ->get();

        return view('friends.index', compact('friends', 'sentRequests', 'receivedRequests', 'suggestedUsers'));
    }

    // Buscar usuarios por nombre
    public function search(Request $request)
    {
        $query = $request->get('query');
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $users = User::where('name', 'like', '%' . $query . '%')
            ->where('id', '!=', $user->id)
            ->with(['sentFriendRequests' => function ($q) use ($user) {
                $q->where('friend_id', $user->id);
            }, 'receivedFriendRequests' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->get()
            ->map(function ($u) use ($user) {
                $u->is_friend = $user->isFriendWith($u);
                $u->has_pending = $user->hasPendingRequestWith($u);
                return $u;
            });

        return view('friends.search', compact('users', 'query'));
    }

    // Enviar solicitud de amistad
    public function sendRequest(User $user)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->id === $user->id) {
            $message = 'No puedes enviarte solicitud a ti mismo';
            return request()->isMethod('post')
                ? back()->with('error', $message)
                : redirect()->route('friends.index')->with('error', $message);
        }

        if ($currentUser->isFriendWith($user)) {
            $message = 'Ya son amigos';
            return request()->isMethod('post')
                ? back()->with('error', $message)
                : redirect()->route('friends.index')->with('error', $message);
        }

        if ($currentUser->hasPendingRequestWith($user)) {
            $message = 'Ya hay una solicitud pendiente';
            return request()->isMethod('post')
                ? back()->with('error', $message)
                : redirect()->route('friends.index')->with('error', $message);
        }

        Friendship::create([
            'user_id' => $currentUser->id,
            'friend_id' => $user->id,
            'status' => 'pending'
        ]);

        $message = 'Solicitud enviada';
        return request()->isMethod('post')
            ? back()->with('success', $message)
            : redirect()->route('friends.index')->with('success', $message);
    }

    // Aceptar solicitud
   public function acceptRequest(Friendship $friendship)
{
    $currentUser = Auth::user();

    // Verificar que el usuario actual es el destinatario
    if ($friendship->friend_id != $currentUser->id) {
        return back()->with('error', 'No autorizado');
    }

    // Actualizar el estado a aceptado
    $friendship->update(['status' => 'accepted']);

    // Crear la amistad inversa también como aceptada
    // Esto evita que al buscar amistades tengas que comprobar ambas direcciones
    Friendship::updateOrCreate(
        ['user_id' => $friendship->friend_id, 'friend_id' => $friendship->user_id],
        ['status' => 'accepted']
    );

    return back()->with('success', 'Solicitud aceptada');
}

    // Rechazar solicitud
    public function rejectRequest(Friendship $friendship)
    {
        $user = Auth::user();

        if ($friendship->friend_id !== $user->id) {
            return back()->with('error', 'No autorizado');
        }

        $friendship->update(['status' => 'rejected']);

        return back()->with('success', 'Solicitud rechazada');
    }

    // Cancelar solicitud enviada
   public function declineRequest(Friendship $friendship)
{
    $currentUser =  Auth::user();

    if ($friendship->friend_id != $currentUser->id) {
        return back()->with('error', 'No autorizado');
    }

    // Eliminar la solicitud entrante
    $friendship->delete();

    // También eliminar la posible solicitud inversa si existe
    Friendship::where('user_id', $friendship->friend_id)
        ->where('friend_id', $friendship->user_id)
        ->delete();

    return back()->with('success', 'Solicitud rechazada');
}
}
