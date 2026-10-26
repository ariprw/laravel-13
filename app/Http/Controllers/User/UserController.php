<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\User\DeleteRequest;
use App\Http\Requests\User\ProfileUpdateRequest;
use App\Http\Requests\User\PasswordUpdateRequest;
use App\Models\User;
use App\Models\Branches;
use App\Services\User\UserService;
use App\Enums\User\Status;
use App\Enums\User\Role;
use App\Enums\Delete;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {
        $this->title = 'User';
    }

    public function index()
    {
        $this->authorize('viewAny', User::class);

        $user = $this->userService->paginate();
        $title = 'Daftar '.$this->title;

        if (request()->hasAny(['search', 'status', 'role'])) {
            $title = 'Pencarian '.$this->title.' : '.Str::limit(request('search', ''), 50);
        }

        return view('user.index', [
            'title' => $title,
            'user' => $user,
            'status' => Status::values(),
            'role' => Role::values(),
        ]);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        $user->load(User::getWith());

        return view('user.show', [
            'title' => 'Detail '.$this->title,
            'user' => $user,
        ]);
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $user->load(User::getWith());

        return view('user.edit', [
            'title' => 'Edit '.$this->title,
            'user' => $user,
            'status' => Status::values(),
            'role' => Role::values(),
        ]);
    }

    public function update(User $user, UpdateRequest $request)
    {
        $this->authorize('update', $user);

        $this->userService->update(
            $user,
            $request->validated()
        );

        return redirect()
            ->route('user.index')
            ->with('status', 'Tersimpan');
    }

    public function profile()
    {
        $user = auth()->user();
        
        $this->authorize('profile', $user);

        return view('user.profile', [
            'title' => 'Profil',
            'user' => $user,
        ]);
    }

    public function profileUpdate(ProfileUpdateRequest $request)
    {
        $user = auth()->user();

        $this->authorize('profile', $user);

        $this->userService->profileUpdate(
            $user,
            $request->validated()
        );

        return redirect()
            ->route('user.profile')
            ->with('status', 'Tersimpan');
    }

    public function changePassword()
    {
        $user = auth()->user();
        
        $this->authorize('profile', $user);

        return view('user.password', [
            'title' => 'Ubah Password',
            'user' => $user,
        ]);
    }

    public function changePasswordUpdate(PasswordUpdateRequest $request) {
        $user = auth()->user();

        $this->authorize('profile', $user);

        $this->userService->passwordUpdate(
            $user,
            $request->validated()
        );

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', 'Password berhasil diubah. Silakan login kembali menggunakan password baru.');
    }

    public function delete(User $user)
    {
        $this->authorize('delete', $user);

        return view('user.delete', [
            'title' => 'Hapus '.$this->title,
            'user' => $user,
            'delete' => Delete::YES->value,
        ]);
    }

    public function destroy(User $user, DeleteRequest $request)
    {
        $this->authorize('delete', $user);

        $this->userService->delete(
            $user,
            $request->validated()
        );

        return redirect()
            ->route('user.index')
            ->with('status', 'Terhapus');
    }
}
