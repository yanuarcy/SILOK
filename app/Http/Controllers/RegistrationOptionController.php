<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationOption;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use DataTables;


class RegistrationOptionController extends Controller
{
    public function index()
    {
        return view('admin.masterdata.Layanan.kategori-pendaftaran', ['type_menu' => 'master-data']);
    }

    public function create()
    {
        return view('admin.masterdata.Layanan.create-kategori-pendaftaran', ['type_menu' => 'master-data']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName(); // Get original filename

            // Pastikan direktori exist
            $path = public_path('img/layanan');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);
            }

            // Jika file dengan nama yang sama sudah ada, tambahkan number
            $counter = 1;
            $newImageName = $imageName;
            while (File::exists($path . '/' . $newImageName)) {
                $filename = pathinfo($imageName, PATHINFO_FILENAME);
                $extension = pathinfo($imageName, PATHINFO_EXTENSION);
                $newImageName = $filename . '_' . $counter . '.' . $extension;
                $counter++;
            }

            // Pindahkan file ke direktori public/img/layanan
            $image->move($path, $newImageName);
            $validated['image'] = $newImageName;
        }

        RegistrationOption::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data kategori pendaftaran berhasil ditambahkan!'
        ]);

        // return redirect()->route('registration-options.index')
        //     ->with('success', 'Registration option created successfully.');
    }

    public function edit(RegistrationOption $registrationOption)
    {
        return view('admin.masterdata.Layanan.edit-kategori-pendaftaran', compact('registrationOption'), ['type_menu' => 'master-data']);
    }

    public function update(Request $request, RegistrationOption $registrationOption)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($registrationOption->image) {
                $oldImagePath = public_path('img/layanan/' . $registrationOption->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $image = $request->file('image');
            $imageName = $image->getClientOriginalName(); // Get original filename

            // Pastikan direktori exist
            $path = public_path('img/layanan');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);
            }

            // Jika file dengan nama yang sama sudah ada, tambahkan number
            $counter = 1;
            $newImageName = $imageName;
            while (File::exists($path . '/' . $newImageName)) {
                $filename = pathinfo($imageName, PATHINFO_FILENAME);
                $extension = pathinfo($imageName, PATHINFO_EXTENSION);
                $newImageName = $filename . '_' . $counter . '.' . $extension;
                $counter++;
            }

            // Pindahkan file ke direktori public/img/layanan
            $image->move($path, $newImageName);
            $validated['image'] = $newImageName;
        }

        $registrationOption->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data kategori pendaftaran update successfully!'
        ]);

        // return redirect()->route('registration-options.index')
        //     ->with('success', 'Registration option updated successfully.');
    }

    public function destroy(RegistrationOption $registrationOption)
    {
        try {
            // Delete image if exists
            if ($registrationOption->image) {
                $imagePath = public_path('img/layanan/' . $registrationOption->image);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            $registrationOption->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Delete Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to delete: ' . $e->getMessage()
            ], 500);
        }
    }

    public function data()
    {
        try {
            $registrationOptions = RegistrationOption::query();

            return DataTables::of($registrationOptions)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return $row->image
                        ? '<img src="' . asset('img/layanan/' . $row->image) . '" height="50" alt="' . $row->title . '">'
                        : 'No Image';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d F Y') : '-';
                })
                ->addColumn('actions', function ($row) {
                    $edit = '<a href="' . route('registration-options.edit', $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a>';
                    $delete = '<form action="' . route('registration-options.destroy', $row->id) . '" method="POST" class="d-inline">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm btn-delete" data-name="' . $row->title . '"><i class="fas fa-trash"></i></button>
                    </form>';
                    return $edit . ' ' . $delete;
                })
                ->rawColumns(['image', 'actions'])
                ->make(true);

        } catch (\Exception $e) {
            Log::error('DataTables Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load data: ' . $e->getMessage()
            ], 500);
        }
    }
}
