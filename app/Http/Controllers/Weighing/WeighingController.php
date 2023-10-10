<?php

namespace App\Http\Controllers\Weighing;

use App\Http\Controllers\Controller;
use App\Models\Weighing;
use DataTables;
use Illuminate\Http\Request;
use Lang;
use Laratrust;

class WeighingController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-weighing')) {
            return abort(404);
        }

        return view('weighing.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-weighing')) {
            return abort(404);
        }

        $weighings = Weighing::select('id');

        return DataTables::of($weighings)
            ->addColumn('action', function ($weighing) {
                $edit = '<a href="' . route('weighing.edit', $weighing->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i class="la la-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('weighing.destroy', $weighing->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $weighing->name . '"><i class="la la-trash"></i></a>';

                return (Laratrust::isAbleTo('update-weighing') ? $edit : '') . (Laratrust::isAbleTo('delete-weighing') ? $delete : '');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        if (!Laratrust::isAbleTo('create-weighing')) {
            return abort(404);
        }

        return view('weighing.create');
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-weighing')) {
            return abort(404);
        }

        $this->validate($request, [
            'name' => ['required', 'string', 'max:191'],
            'duration' => ['required'],
            'color' => ['required'],
        ]);

        $weighing = new Weighing;
        $weighing->name = $request->name;
        $weighing->duration = $request->duration;
        $weighing->color = $request->color;
        $weighing->save();

        $message = Lang::get('Weighing') . ' \'' . $weighing->name . '\' ' . Lang::get('successfully created.');
        return redirect()->route('weighing.index')->with('status', $message);
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-weighing')) {
            return abort(404);
        }

        $weighing = Weighing::select('id')->findOrFail($id);

        return view('weighing.edit', compact('weighing'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-weighing')) {
            return abort(404);
        }

        $weighing = Weighing::findOrFail($id);

        $this->validate($request, [
            'name' => ['required', 'string', 'max:191'],
            'duration' => ['required'],
            'color' => ['required'],
        ]);

        $weighing->name = $request->name;
        $weighing->duration = $request->duration;
        $weighing->color = $request->color;
        $weighing->save();

        $message = Lang::get('Weighing') . ' \'' . $weighing->name . '\' ' . Lang::get('successfully updated.');
        return redirect()->route('weighing.index')->with('status', $message);
    }

    public function destroy($id)
    {
        if (!Laratrust::isAbleTo('delete-weighing')) {
            return abort(404);
        }

        $weighing = Weighing::findOrFail($id);
        $name = $weighing->name;
        $weighing->delete();

        $message = Lang::get('Weighing') . ' \'' . $name . '\' ' . Lang::get('successfully deleted.');
        return redirect()->route('weighing.index')->with('status', $message);
    }
}