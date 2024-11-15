<?php

namespace App\Http\Controllers;

use App\Models\LayananItem;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;


class LayananItemController extends Controller
{
    public function getData()
    {
        $query = LayananItem::with('subLayanan');

        return DataTables::of($query)
            ->addColumn('actions', function($item) {
                return '
                    <div class="d-flex justify-content-center gap-2">
                        <a href="'.route('layanan-item.edit', $item->id).'" class="btn btn-warning btn-sm">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form action="'.route('layanan-item.destroy', $item->id).'" method="POST" class="delete-form">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm btn-delete" data-name="'.$item->title.'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function edit($id)
    {
        $LayananItem = LayananItem::findOrFail($id);
        return view('admin.masterdata.Layanan.edit-layanan-item', [
            'type_menu' => 'master-data',
            'item' => $LayananItem
        ]);
    }

    public function update(Request $request, LayananItem $item)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if (file_exists(public_path('images/layanan/'.$item->image))) {
                unlink(public_path('images/layanan/'.$item->image));
            }

            $imageName = $request->file('image')->getClientOriginalName();
            $path = public_path('images/layanan');

            // Handle duplicate filenames
            $counter = 1;
            $newImageName = $imageName;
            while (File::exists($path . '/' . $newImageName)) {
                $filename = pathinfo($imageName, PATHINFO_FILENAME);
                $extension = pathinfo($imageName, PATHINFO_EXTENSION);
                $newImageName = $filename . '_' . $counter . '.' . $extension;
                $counter++;
            }

            // Move and save the file
            $request->image->move($path, $newImageName);
            $item->image = $newImageName;
        }

        $item->title = $request->title;
        $item->slug = Str::slug($request->title);
        $item->save();

        return redirect()->route('sub-layanan.edit', $item->subLayanan->id)
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(LayananItem $item)
    {
        try {
            $subLayananId = $item->sub_layanan_id;

            // Delete associated image
            if (file_exists(public_path('img/layanan/'.$item->image))) {
                unlink(public_path('img/layanan/'.$item->image));
            }

            $item->delete();

            // Return JSON response for AJAX request
            if(request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item deleted successfully'
                ]);
            }

            // Return redirect for normal request
            return redirect()->route('sub-layanan.edit', $subLayananId)
                ->with('success', 'Item deleted successfully.');
        } catch (\Exception $e) {
            if(request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting item: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error deleting item');
        }
    }

}
