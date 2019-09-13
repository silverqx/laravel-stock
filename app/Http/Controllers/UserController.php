<?php

namespace App\Http\Controllers;

use Hash;
use Route;

use App\Http\Requests\User\ListingRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Modules\User\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ListingRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListingRequest $request)
    {
        $search = $request->query('search');
        $orderBy = $request->query('orderBy', ['id']);
        $direction = $request->query('direction', 'asc');

        $users = User::where('first_name', 'like', "%$search%")
            ->orWhere('last_name', 'like', "%$search%")
            ->tap(function ($query) use ($orderBy, $direction) {
                collect($orderBy)
                    ->each(function ($item) use ($query, $direction) {
                        $query->orderBy($item, $direction);
                    });
            })
            ->paginate(20)
            ->appends(compact('search', 'orderBy', 'direction'));

        $currentRouteName = Route::currentRouteName();
        $currentPage = $users->currentPage();

        return view('user.index',
            compact('users', 'search', 'orderBy', 'direction', 'currentRouteName', 'currentPage')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function store(StoreRequest $request)
    {
        $user = new User($request->all());
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $user->addMediaFromRequest('image')
            ->toMediaCollection('users');

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modules\User\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modules\User\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest          $request
     * @param \App\Modules\User\User $user
     *
     * @return \Illuminate\Http\Response
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function update(UpdateRequest $request, User $user)
    {
        $user->fill($request->all());

        if ($request->has('password'))
            $user->password = Hash::make($request->input('password'));

        $user->save();

        if ($request->has('image'))
            $user->addMediaFromRequest('image')
                ->toMediaCollection('users');

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Modules\User\User $user
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        // Do not delete, because soft delete model enabled
//        \Storage::disk('public')->deleteDirectory(
//            $user->media->first()->getKey()
//        );

        return redirect()->route('users.index');
    }
}
