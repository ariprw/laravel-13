<?php

namespace App\Http\Controllers\Branches;

use App\Http\Controllers\Controller;
use App\Http\Requests\Branches\CreateRequest;
use App\Http\Requests\Branches\UpdateRequest;
use App\Http\Requests\Branches\DeleteRequest;
use App\Models\Branches;
use App\Services\Branches\BranchesService;
use App\Enums\Branches\Status;
use App\Enums\Delete;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BranchesController extends Controller
{
    public function __construct(
        protected BranchesService $branchesService
    ) {
        $this->title = 'Cabang';
    }

    public function index()
    {
        $this->authorize('viewAny', Branches::class);

        $branches = $this->branchesService->paginate();
        $title = 'Daftar '.$this->title;

        if (request()->hasAny(['search', 'status'])) {
            $title = 'Pencarian '.$this->title.' : '.Str::limit(request('search', ''), 50);
        }

        return view('branches.index', [
            'title' => $title,
            'branches' => $branches,
            'status' => Status::options(),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Branches::class);

        return view('branches.create', [
            'title' => 'Buat '.$this->title,
            'status' => Status::options(),
        ]);
    }

    public function store(CreateRequest $request)
    {
        $this->authorize('create', Branches::class);

        $this->branchesService->create(
            $request->validated()
        );

        return redirect()
            ->route('branches.index')
            ->with('status', 'Tersimpan');
    }

    public function show(Branches $branches)
    {
        $this->authorize('view', $branches);

        $branches->loadCount('users');

        return view('branches.show', [
            'title' => 'Detail '.$this->title,
            'status' => Status::options(),
            'branches' => $branches,
        ]);
    }

    public function edit(Branches $branches)
    {
        $this->authorize('update', $branches);

        return view('branches.edit', [
            'title' => 'Edit '.$this->title,
            'status' => Status::options(),
            'branches' => $branches,
        ]);
    }

    public function update(Branches $branches, UpdateRequest $request)
    {
        $this->authorize('update', $branches);

        $this->branchesService->update(
            $branches,
            $request->validated()
        );

        return redirect()
            ->route('branches.index')
            ->with('status', 'Tersimpan');
    }

    public function delete(Branches $branches)
    {
        $this->authorize('delete', $branches);

        return view('branches.delete', [
            'title' => 'Hapus '.$this->title,
            'status' => Status::options(),
            'branches' => $branches,
            'delete' => Delete::YES->value,
        ]);
    }

    public function destroy(Branches $branches, DeleteRequest $request)
    {
        $this->authorize('delete', $branches);

        $this->branchesService->delete(
            $branches,
            $request->validated()
        );

        return redirect()
            ->route('branches.index')
            ->with('status', 'Terhapus');
    }

    public function search(Request $request)
    {
        $search = trim($request->input('q', ''));

        $query = Branches::query()->select(['id', 'name', 'is_active']);

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('id', $search)
                ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $branches = $query
        ->orderBy('name')
        ->limit(20)
        ->get();

        return response()->json([
            'data' => $branches->map(fn ($branch) => [
                'id' => $branch->id,
                'name' => $branch->name,
                'status' => (int) $branch->is_active,
                'statusLabel' => Status::tryFrom((int) $branch->is_active)?->label() ?? '',
            ])->values(),
        ]);
    }
}
