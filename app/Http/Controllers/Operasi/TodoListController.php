<?php

namespace App\Http\Controllers\Operasi;

use App\Http\Controllers\Controller;
use App\Models\TodoList;
use DateTime;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    public function show()
    {
        $todoList = TodoList::all();
        return $todoList;
    }

    public function todoList($offset)
    {
        $todoList = $this->hitungDeadline(TodoList::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->offset($offset)->limit(5)->get());
        $todoList = $this->EditFormatDeadline($todoList);
        $data = [
            "message" => 200,
            "data" => $todoList
        ];
        return $data;
    }

    public function check($id, $status)
    {
        try {
            $todoList = TodoList::findOrFail($id);
            $todoList
                ->update([
                    'status' => $status
                ]);
            $data = [
                "message" => 200,
                "data" => "Berhasil update $todoList->tugas $status"
            ];
        } catch (\Throwable $th) {
            $data = [
                "message" => 500,
                "data" => "Gagal update " . $th->getMessage()
            ];
        }
        return $data;
    }

    public function tambah(Request $request)
    {
        $dataValidate = $request->validate([
            'tugas' => 'required',
            'deadline' => 'required'
        ]);
        $datetime = explode(" ", $dataValidate['deadline']);
        $date = explode("/", $datetime[0]);
        $deadline = "$date[1]-$date[0]-$date[2] $datetime[1] $datetime[2]";
        $deadline = new DateTime($deadline);

        try {
            TodoList::create([
                'user_id' => auth()->user()->id,
                'tugas' => $dataValidate['tugas'],
                'deadline' => $deadline,
                'status' => 'aktif'
            ]);

            $data = [
                "message" => 200,
                "data" => "Berhasil menambahkan data" . $dataValidate['tugas']
            ];
        } catch (\Throwable $th) {
            $data = [
                "message" => 500,
                "data" => $th->getMessage()
            ];
        }

        return $data;
    }

    public function edit(Request $request)
    {
        $dataValidate = $request->validate([
            'id' => 'required',
            'tugas' => 'required',
            'deadline' => 'required'
        ]);
        $datetime = explode(" ", $dataValidate['deadline']);
        $date = explode("/", $datetime[0]);
        $deadline = "$date[1]-$date[0]-$date[2] $datetime[1] $datetime[2]";
        $deadline = new DateTime($deadline);

        try {
            $todoList = TodoList::findOrFail($dataValidate['id']);
            $todoList
                ->update([
                    'tugas' => $dataValidate['tugas'],
                    'deadline' => $deadline
                ]);
            $data = [
                "message" => 200,
                "data" => "Berhasil mengedit data " . $dataValidate['tugas']
            ];
        } catch (\Throwable $th) {
            $data = [
                "message" => 500,
                "data" => $th->getMessage()
            ];
        }

        return $data;
    }

    public function hapus(Request $request, $id)
    {
        $pesan = $request->has('tugas') ? $request->input('tugas') : $id;
        try {
            TodoList::destroy($id);
            $data = [
                "message" => 200,
                "data" => "Berhasil menghapus data " . $pesan
            ];
        } catch (\Throwable $th) {
            $data = [
                "message" => 500,
                "data" => $th->getMessage()
            ];
        }
        return $data;
    }

    public function jumlahHalaman()
    {
        $nTodoList = count(TodoList::all());
        $jumlahHalaman = ceil($nTodoList / 5);
        $data = [
            "message" => 200,
            "data" => $jumlahHalaman
        ];
        return $data;
    }

    public function hitungDeadline($todoList)
    {
        // hitung deadline
        foreach ($todoList as $dt) {
            $deadline = $dt->deadline;
            $hitungDeadline = new DateTime($deadline);
            $today = new DateTime();
            if ($hitungDeadline > $today) {
                $dt->deadline_baru = "0 tahun 0 bulan 0 hari";
            }
            $y = $today->diff($hitungDeadline)->y;
            $m = $today->diff($hitungDeadline)->m;
            $d = $today->diff($hitungDeadline)->d;
            $h = $today->diff($hitungDeadline)->h;
            $i = $today->diff($hitungDeadline)->i;
            $s = $today->diff($hitungDeadline)->s;
            $invert = $today->diff($hitungDeadline)->invert;

            if ($y != 0) {
                $dt->deadline_baru = $y . ' tahun';
                $dt->deadline_invert = $invert;
                $dt->peringatan = "primary"; //bootstrap label color
                if ($invert == 1) {
                    $dt->peringatan = "secondary";
                }
                continue;
            }
            if ($m != 0) {
                $dt->deadline_baru = $m . ' bulan';
                $dt->deadline_invert = $invert;
                $dt->peringatan = "primary";
                if ($invert == 1) {
                    $dt->peringatan = "secondary";
                }
                continue;
            }
            if ($d != 0) {
                $dt->deadline_baru = $d . ' hari';
                $dt->deadline_invert = $invert;
                $dt->peringatan = "success";
                if ($invert == 1) {
                    $dt->peringatan = "secondary";
                }
                continue;
            }
            if ($h != 0) {
                $dt->deadline_baru = $h . ' jam';
                $dt->deadline_invert = $invert;
                $dt->peringatan = "warning";
                if ($invert == 1) {
                    $dt->peringatan = "secondary";
                }
                continue;
            }
            if ($i != 0) {
                $dt->deadline_baru = $i . ' menit';
                $dt->deadline_invert = $invert;
                $dt->peringatan = "warning";
                if ($invert == 1) {
                    $dt->peringatan = "secondary";
                }
                continue;
            }
            if ($s != 0) {
                $dt->deadline_baru = $s . ' detik';
                $dt->deadline_invert = $invert;
                $dt->peringatan = "danger";
                if ($invert == 1) {
                    $dt->peringatan = "secondary";
                }
                continue;
            }
        }
        return $todoList;
    }

    public function EditFormatDeadline($todoList)
    {
        foreach ($todoList as $dt) {
            $date = date('m/d/Y h:i A', strtotime($dt->deadline));
            $dt->deadline = $date;
        }

        return $todoList;
    }
}
