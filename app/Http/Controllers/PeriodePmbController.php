<?php

namespace App\Http\Controllers;

use App\Models\Periode_pmb;
use App\Models\Semester;
use DateTime;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PeriodePmbController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Periode_pmb::get())
                ->addColumn('action', function ($row) {
                    $editUrl = route('periode_pmb.edit', $row->id);
                    $deleteUrl = route('periode_pmb.destroy', $row->id);
                    $csrfToken = csrf_field();
                    $methodField = method_field('delete');

                    return <<<EOT
                        <form id="delete-form-$row->id" onsubmit="event.preventDefault(); confirmDelete($row->id);" action="$deleteUrl" method="POST">
                            $csrfToken
                            $methodField
                            <a href="$editUrl" class="btn btn-outline-warning btn-sm edit" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <button type="submit" class="btn icon icon-left btn-outline-danger btn-sm delete" title="Delete">
                                <i class="fas fa-trash-alt"></i>    
                            </button>
                        </form>
                    EOT;
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1 ? 'buka' : 'tutup';
                })
                ->make(true);
        }

        return view('pages.admin.periode_pmb.index');
    }

    public function create() {
        return view('pages.admin.periode_pmb.add');
    }

    public function searchSemester (Request $request) {
        $search = $request->query('semester_id') != '' ? $request->query('semester_id') : 'null';
        return $request->ajax() ? Semester::where('semester_id', 'like', "%$search%")->get() : abort(404);
    }

    public function store(Request $request)
    {
        Periode_pmb::create([
            'semester_id' => $request->semester_id, 
            'period_number' => $request->period_number,
            'start_date' => $request->start_date, 
            'end_date' => $request->end_date,
            'status' => '1'
        ]);

        return redirect()->route('periode_pmb.index');
    }


    public function destroy (string $id, Periode_pmb $periode_pmb) {
        $periode_pmb->findOrFail($id)->delete();
        return redirect()->back();
    }

    public function edit (string $id) {
        $prev_period_data = Periode_pmb::findOrFail($id);
        $prev_semester_data = Semester::findOrFail($prev_period_data->semester_id);

        return view('pages.admin.periode_pmb.edit')->with(compact('prev_period_data', 'prev_semester_data'));
    }

    public function update (Request $request, string $id, Periode_pmb $periode_pmb) {
        $periode_pmb->findOrFail($id)->update($request->except('_token'));

        return redirect()->route('periode_pmb.index');
    }
}
