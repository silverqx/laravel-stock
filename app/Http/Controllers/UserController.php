<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;
use Route;

use App\Modules\User\User;

class UserController extends Controller
{
    /**
     * Validation rules for a Products listing.
     *
     * @var array
     */
    private $indexRules = [
        // array to support multi sort
        'orderBy'   => 'required_with:direction|array|in:id,first_name,last_name,email',
        'direction' => 'in:asc,desc',
    ];

    /**
     * Validation rules for create a User.
     *
     * @var array
     */
    private $storeRules = [
        'first_name' => ['required', 'min:2', 'max:255', 'string', 'regex:/^[\pL\-_\ ]+$/u'],
        'last_name'  => ['required', 'min:2', 'max:255', 'string', 'regex:/^[\pL\-_\ ]+$/u'],
        'email'      => 'required|email|max:255|unique:users,email',
        // Also check config/medialibrary.php max_file_size
        'image'      => 'required|image|mimes:jpeg,png,gif|max:2048',
        'password'   => [
            'required',
            'confirmed',
            'min:9',
            'max:255',
            'regex:/^(?=.*[a-z|A-Z])(?=.*[A-Z])(?=.*\d).+$/'
        ],
    ];

    /**
     * Validation rules for update a User.
     *
     * @var array
     */
    private $updateRules = [
        'first_name' => ['required', 'min:2', 'max:255', 'string', 'regex:/^[\pL\-_\ ]+$/u'],
        'last_name'  => ['required', 'min:2', 'max:255', 'string', 'regex:/^[\pL\-_\ ]+$/u'],
        'email'      => 'required|email|max:255|unique:users,email',
        // Also check config/medialibrary.php max_file_size
        'image'      => 'image|mimes:jpeg,png,gif|max:2048',
        'password'   => [
            'nullable',
            'confirmed',
            'min:9',
            'max:255',
            'regex:/^(?=.*[a-z|A-Z])(?=.*[A-Z])(?=.*\d).+$/'
        ],
    ];

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request)
    {
        $this->validate($request, $this->indexRules);

        $search = $request->query('search');
        $orderBy = $request->query('orderBy', 'id');
        $direction = $request->query('direction', 'asc');

        $users = User::where('first_name', 'like', "%$search%")
            ->orWhere('last_name', 'like', "%$search%")
            ->tap(function ($query) use ($orderBy, $direction) {
                collect($orderBy)
                    ->each(function ($item) use ($query, $direction) {
                        $query->orderBy($item, $direction);
                    });
            })
            ->paginate(2)
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->storeRules);

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
     * @param \Illuminate\Http\Request $request
     * @param \App\Modules\User\User   $user
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user)
    {
        $this->updateRules['email'] .= ",$user->id";
        $this->validate($request, $this->updateRules);

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

        \Storage::disk('public')->deleteDirectory($user->id);

        return redirect()->route('users.index');
    }
}
