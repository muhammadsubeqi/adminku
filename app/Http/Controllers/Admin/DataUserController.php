<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataUser;
use App\Models\User;
use App\Services\BulkData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DataUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataUsers = DataUser::all();

        return view('admin/data-user/index', compact('dataUsers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bulkData = new BulkData();
        return view('admin/data-user/create', compact('bulkData'));  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            
            $dataValidated = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|max:255|email',
                'tempat_lahir' => 'nullable',
                'tanggal_lahir' => 'nullable',
                'jenis_kelamin' => 'nullable',
                'alamat' => 'nullable',
                'phone' => 'nullable'
            ]);

            DB::beginTransaction();

            if ($request->has('photo')) {
                $lokasi = 'foto_user/';
                $photo = $request->file('photo');
                $extensi = $request->file('photo')->extension();
                $new_image_name = 'Photo' . date('YmdHis') . uniqid() . '.' . $extensi;

                $photo->move(public_path($lokasi), $new_image_name);
                User::create([
                    'email' => $dataValidated['email'],
                    'password' => Hash::make('user1234'),
                    'photo' => $new_image_name,
                    'role' => 'user'
                ]);
            } else {
                User::create([
                    'email' => $dataValidated['email'],
                    'password' => Hash::make('user1234'),
                    'role' => 'user'
                ]);
            }

            $birth = $dataValidated['tempat_lahir'] . ", " . $dataValidated['tanggal_lahir'];

            $id = User::latest()->first()->id;
    
            DataUser::create([
                'users_id' => $id,
                'name' => $dataValidated['name'],
                'ttl' => $birth,
                'jenis_kelamin' => $dataValidated['jenis_kelamin'],
                'alamat' => $dataValidated['alamat'],
                'phone' => $dataValidated['phone']
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('admin.data-user')->with('failed', "Gagal menambahkan data " . $th->getMessage());
        }
        return redirect()->route('admin.data-user')->with('message', "Berhasil menambahkan data dengan nama " . "<b>". $dataValidated['name'] . "</b>");
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataUser = DataUser::find($id);
        return view('admin/data-user/show', compact('dataUser'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataUser = DataUser::find($id);

        if ($dataUser->ttl != null) {
            $dataUser->tempat_lahir = explode(',', $dataUser->ttl)[0];
            $dataUser->tanggal_lahir = explode(',', $dataUser->ttl)[1];
        }

        $bulkData = new BulkData();

        return view('admin/data-user/edit', compact('dataUser','bulkData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dataValidated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255|email',
            'tempat_lahir' => 'nullable|max:255',
            'tanggal_lahir' => 'nullable|max:255',
            'jenis_kelamin' => 'nullable|max:255',
            'alamat' => 'nullable|max:255',
            'phone' => 'nullable|max:255'
        ]);

        $namaFoto = $request->input('foto_lama');

        if ($request->has('photo')) {
            $lokasi = 'foto_user/';
            $foto = $request->file('photo');
            $extensi = $request->file('photo')->extension();
            $new_image_name = 'Photo' . date('YmdHis') . uniqid() . '.' . $extensi;
            $upload = $foto->move(public_path($lokasi), $new_image_name);
            $namaFoto = $new_image_name;
            if ($upload) {
                $getFoto = DataUser::find($id)->user->photo;
                if ($getFoto != null) {
                    File::delete(public_path('foto_user/' . $getFoto));
                }
            }
        }

        $birth = $dataValidated['tempat_lahir'] . ", " . $dataValidated['tanggal_lahir'];
        try {
            DB::beginTransaction();
            DataUser::where('id', $id)
                ->update([
                    'name' => $dataValidated['name'],
                    'ttl' => $birth,
                    'jenis_kelamin' => $dataValidated['jenis_kelamin'],
                    'alamat' => $dataValidated['alamat'],
                    'phone' => $dataValidated['phone']
                ]);

            $userId = DataUser::find($id)->users_id;
            User::where('id', $userId)
                ->update([
                    'email' => $dataValidated['email'],
                    'photo' => $namaFoto
                ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('admin.data-user')->with('failed', "Gagal mengupdate data dengan nama " . $dataValidated['name']);
        }
        return redirect()->route('admin.data-user')->with('message', "Berhasil mengupdate data dengan nama " . "<b>". $dataValidated['name'] ."</b>" );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $userId = DataUser::find($id)->users_id;
            DataUser::destroy($id);
            User::destroy($userId);

           DB::commit();
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('admin.data-user')->with('failed', "Gagal menghapus data dengan nama " . $request->input('name') . " $th");
        }
        return redirect()->route('admin.data-user')->with('message', "Berhasil menghapus data dengan nama " . "<b>". $request->input('name') . "</b>");
    }
}
