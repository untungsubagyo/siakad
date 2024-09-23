<?php

namespace App\Http\Controllers;

use App\Models\Active_status;
use App\Models\All_prodi;
use App\Models\Employee_level;
use App\Models\Lecturer;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Hamcrest\Core\AllOf;

class LecturerController extends Controller
{
  public function index()
    {
        // Kirimkan view tanpa data karena DataTables akan meng-handle data secara AJAX
        return view('pages.admin.lecturer.index');
    }

    public function data(Request $request)
{
    if ($request->ajax()) {
        $lecturer = Lecturer::with(['prodi', 'employee_level', 'ActiveStatus'])->get();

        $lecturer = $lecturer->map(function($lecturer) {
            $lecturer->birth_date = Carbon::parse($lecturer->birth_date)->format('d-m-Y');
            $lecturer->assign_letter_date = $lecturer->assign_letter_date ? Carbon::parse($lecturer->assign_letter_date)->format('d-m-Y') : null;
            $lecturer->assign_letter_tmt = $lecturer->assign_letter_tmt ? Carbon::parse($lecturer->assign_letter_tmt)->format('d-m-Y') : null;
            $lecturer->exit_date = $lecturer->exit_date ? Carbon::parse($lecturer->exit_date)->format('d-m-Y') : null;
            return $lecturer;
        });

        return DataTables::of($lecturer)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return '<a href="'.route('lecturer.edit', $data->id).'" class="btn btn-outline-warning btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form id="delete-form-' . $data->id . '" 
                              onsubmit="event.preventDefault(); confirmDelete(' . $data->id . ');" 
                              action="' . route('lecturer.destroy', $data->id) . '" 
                              method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn icon icon-left btn-outline-danger btn-sm delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                      </form>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return abort(404);
}


    // Show the form for creating a new lecturer
    public function create()
    {
        $activeStatuses = Active_status::all();
        $employeeLevels = Employee_level::all();
        $prodiList = All_prodi::all();
        return view('pages.admin.lecturer.form', compact('activeStatuses', 'employeeLevels', 'prodiList'));
    }

        public function searchProdi (Request $request) {
        $search = $request->query('nama_prodi') != '' ? $request->query('nama_prodi') : 'null';
        return $request->ajax() ? All_prodi::where('nama_prodi', 'like', "%$search%")->get() : abort(404);
    }
    
    // Store a newly created lecturer in the database
    public function store(Request $request)
{
    // Ubah '-' menjadi null sebelum validasi
    $request->merge([
        'nuptk' => $request->nuptk === '-' ? null : $request->nuptk,
        'nik' => $request->nik === '-' ? null : $request->nik,
    ]);

    // Lakukan validasi setelah merubah data
    $request->validate([
        'nuptk' => 'nullable|string|max:16|unique:lecturer,nuptk', // Mengubah 'required' menjadi 'nullable'
        'nidn' => 'nullable|string|max:10|unique:lecturer,nidn',
        'nik' => 'nullable|string|max:16',
        'gender' => 'required|in:Laki-laki,Perempuan',
        'name' => 'required|string|max:200',
        'active_status_id' => 'required|exists:active_status,id',
        'birth_date' => 'required|date',
        'birth_place' => 'required|string|max:100',
        'mothers_name' => 'required|string|max:200',
        'mariage_status' => 'required|in:belum kawin,kawin,cerai hidup,cerai mati',
        'employee_level_id' => 'required|exists:employee_level,id',
        'level_education' => 'required|in:S1,S2,S3',
        'phone_number' => 'nullable|string|max:13',
        'email' => 'nullable|email|max:255',
        'assign_letter_number' => 'nullable|string|max:30',
        'assign_letter_date' => 'nullable|date',
        'assign_letter_tmt' => 'nullable|date',
        'exit_date' => 'nullable|date',
        'prodi_id' => 'required|exists:prodi,id',
    ]);

    // Simpan data
    Lecturer::create($request->all());

    return redirect()->route('lecturer.index')->with('success', 'Lecturer created successfully.');
}


    // Show the form for editing the specified lecturer
    public function edit($id)
    {
        $lecturer = Lecturer::findOrFail($id); // Menggunakan id sebagai primary key
        $activeStatuses = Active_status::all();
        $employeeLevels = Employee_level::all();
        $prodiList = All_prodi::all();

        return view('pages.admin.lecturer.form_edit', compact('lecturer', 'activeStatuses', 'employeeLevels', 'prodiList'));
    }

    // Update the specified lecturer in the database
    public function update(Request $request, $id)
{
    // Ubah '-' menjadi null sebelum validasi
    $request->merge([
        'nuptk' => $request->nuptk === '-' ? null : $request->nuptk,
        'nik' => $request->nik === '-' ? null : $request->nik,
    ]);

    // Lakukan validasi setelah merubah data
    $request->validate([
        'nuptk' => 'nullable|string|max:16|unique:lecturer,nuptk,' . $id,
        'nidn' => 'nullable|string|max:10|unique:lecturer,nidn,' . $id,
        'nik' => 'nullable|string|max:16',
        'gender' => 'required|in:Laki-laki,Perempuan',
        'name' => 'required|string|max:200',
        'active_status_id' => 'required|exists:active_status,id',
        'birth_date' => 'required|date',
        'birth_place' => 'required|string|max:100',
        'mothers_name' => 'required|string|max:200',
        'mariage_status' => 'required|in:belum kawin,kawin,cerai hidup,cerai mati',
        'employee_level_id' => 'required|exists:employee_level,id',
        'level_education' => 'required|in:S1,S2,S3',
        'phone_number' => 'nullable|string|max:13',
        'email' => 'nullable|email|max:255',
        'assign_letter_number' => 'nullable|string|max:30',
        'assign_letter_date' => 'nullable|date',
        'assign_letter_tmt' => 'nullable|date',
        'exit_date' => 'nullable|date',
        'prodi_id' => 'required|exists:prodi,id',
    ]);

    // Update data lecturer
    $lecturer = Lecturer::findOrFail($id);
    $lecturer->update($request->all());

    return redirect()->route('lecturer.index')->with('success', 'Lecturer updated successfully.');
}

    // Remove the specified lecturer from the database
    public function destroy($id)
    {
        $lecturer = Lecturer::findOrFail($id); // Menggunakan id untuk pencarian
        $lecturer->delete();

        return redirect()->route('lecturer.index')->with('success', 'Lecturer deleted successfully.');
    }
}