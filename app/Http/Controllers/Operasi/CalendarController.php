<?php

namespace App\Http\Controllers\Operasi;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function show()
    {
        return Calendar::all();
    }

    public function tambah(Request $request)
    {
        try {
            $dataValidated = $request->validate([
                'title' => 'required',
                'start' => 'required',
                'end' => 'required',
                'warna' => 'required'
            ]);

            Calendar::create([
                'user_id' => auth()->user()->id,
                'title' => $dataValidated['title'],
                'start' => $dataValidated['start'],
                'end' => $dataValidated['end'],
                'warna' => $dataValidated['warna']
            ]);

            $calendar = Calendar::latest()->first();
            $data = [
                "message" => 200,
                "data" => "Berhasil menambahkan data " . $dataValidated['title'],
                "calendar" => $calendar
            ];
        } catch (\Throwable $th) {
            $data = [
                "message" => 500,
                "data" => $th->getMessage()
            ];
        }
        return $data;
    }

    public function edit(Request $request, $id)
    {
        try {
            $dataValidated = $request->validate([
                'title' => 'required',
                'start' => 'required',
                'end' => 'required',
                'warna' => 'required'
            ]);

            $calendar = Calendar::findOrFail($id);
            $calendar
                ->update([
                    'title' => $dataValidated['title'],
                    'start' => $dataValidated['start'],
                    'end' => $dataValidated['end'],
                    'warna' => $dataValidated['warna']
                ]);

            $data = [
                "message" => 200,
                "data" => "Berhasil mengupdate data " . $dataValidated['title'],
                "calendar" => $calendar
            ];
        } catch (\Throwable $th) {
            $data = [
                "message" => 500,
                "data" => $th->getMessage()
            ];
        }
        return $data;
    }

    public function hapus($id)
    {
        try {
            Calendar::destroy($id);
            $data = [
                "message" => 200,
                "data" => "Berhasil menghapus data"
            ];
        } catch (\Throwable $th) {
            $data = [
                "message" => 500,
                "data" => $th->getMessage()
            ];
        }
        return $data;
    }
}
