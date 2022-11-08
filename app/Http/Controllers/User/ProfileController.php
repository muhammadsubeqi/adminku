<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DataUser;
use App\Models\User;
use App\Services\BulkData;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = DataUser::where('users_id', auth()->user()->id)->first();
        
        // hitung umur
        if (isset($user->ttl)) {
            $tanggalLahir = explode(',', $user->ttl)[1];

            $birthDate = new DateTime($tanggalLahir);
            $today = new DateTime("today");
            if ($birthDate > $today) {
            }
            $y = $today->diff($birthDate)->y;
            $m = $today->diff($birthDate)->m;
            $d = $today->diff($birthDate)->d;
    
            $umur = $y . " tahun " . $m . " bulan " . $d . " hari";
        } else{
            $umur = '';
        }
       
        $photo = auth()->user()->photo;
        return view('user/profile/index', compact('user', 'umur', 'photo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $dataUser = DataUser::where('users_id', auth()->user()->id)->first();

        if ($dataUser->ttl != null) {
            $dataUser->tempat_lahir = explode(',', $dataUser->ttl)[0];
            $dataUser->tanggal_lahir = explode(',', $dataUser->ttl)[1];
        }

        $bulkData = new BulkData();

        return view('user/profile/edit', compact('dataUser','bulkData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
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
                    $getFoto = auth()->user()->photo;
                    if ($getFoto != null) {
                        File::delete(public_path('foto_user/' . $getFoto));
                    }
                }
            }
            
            if ($request->has('password')) {
                $passValidated = $request->validate([
                    'password' => 'required|max:255',
                    'konfirmasi_password' => 'required|same:password'
                ]);
                $dataValidated['password'] = $passValidated['password'];
                $dataValidated['konfirmasi_password'] = $passValidated['konfirmasi_password'];
            }
            
            $birth = $dataValidated['tempat_lahir'] . ", " . $dataValidated['tanggal_lahir'];
            $userId = auth()->user()->id;
       
            DB::beginTransaction();
            DataUser::where('users_id', $userId)
                ->update([
                    'name' => $dataValidated['name'],
                    'ttl' => $birth,
                    'jenis_kelamin' => $dataValidated['jenis_kelamin'],
                    'alamat' => $dataValidated['alamat'],
                    'phone' => $dataValidated['phone']
                ]);
            
            if ($request->has('password')) {
                User::where('id', $userId)
                    ->update([
                        'email' => $dataValidated['email'],
                        'password' => Hash::make($dataValidated['password']),
                        'photo' => $namaFoto
                    ]);
            } else {
                User::where('id', $userId)
                    ->update([
                        'email' => $dataValidated['email'],
                        'photo' => $namaFoto
                    ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            return redirect()->route('user.profile')->with('failed', 'Gagal mengupdate profile');
        }
        return redirect()->route('user.profile')->with('message', 'Berhasil mengupdate profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
