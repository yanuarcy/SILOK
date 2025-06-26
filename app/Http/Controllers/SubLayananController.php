<?php

namespace App\Http\Controllers;

use App\Models\SubLayanan;
use DataTables;
use App\Models\LayananItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SubLayananController extends Controller
{
    public function getData()
    {
        $query = SubLayanan::with('layanan');

        $isAuthorized = auth()->user() && (auth()->user()->role === 'admin' || auth()->user()->role === 'Operator');

        return DataTables::of($query)
            ->addColumn('actions', function($subLayanan) use ($isAuthorized) {
                if (!$isAuthorized) {
                    return '';
                }

                return '
                    <div class="d-flex justify-content-center gap-2">
                        <a href="'.route('sub-layanan.edit', $subLayanan->id).'" class="btn btn-warning btn-sm">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form action="'.route('sub-layanan.destroy', $subLayanan->id).'" method="POST" class="delete-form">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm btn-delete" data-name="'.$subLayanan->title.'">
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
        $subLayanan = Sublayanan::findOrFail($id);
        return view('admin.masterdata.Layanan.edit-sub-layanan', [
            'type_menu' => 'master-data',
            'subLayanan' => $subLayanan
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $subLayanan = SubLayanan::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'has_items' => 'required|boolean',
                'new_items.*.title' => 'required_if:has_items,1|string|max:255',
                'new_items.*.image' => 'required_if:has_items,1|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            // Update sub layanan
            if ($request->hasFile('image')) {
                if ($subLayanan->image && file_exists(public_path('img/layanan/'.$subLayanan->image))) {
                    unlink(public_path('img/layanan/'.$subLayanan->image));
                }

                // Get original filename and use it directly
                $imageName = $request->file('image')->getClientOriginalName();
                $path = public_path('img/layanan');

                // Handle duplicate filenames
                $counter = 1;
                $newImageName = $imageName;
                while (File::exists($path . '/' . $newImageName)) {
                    $filename = pathinfo($imageName, PATHINFO_FILENAME);
                    $extension = pathinfo($imageName, PATHINFO_EXTENSION);
                    $newImageName = $filename . '_' . $counter . '.' . $extension;
                    $counter++;
                }

                $request->image->move($path, $newImageName);
                $subLayanan->image = $newImageName;
            }

            $subLayanan->title = $request->title;
            $subLayanan->slug = Str::slug($request->title);
            $subLayanan->has_items = $request->boolean('has_items');
            $subLayanan->save();

            // Only handle items if has_items is true
            if ($request->boolean('has_items')) {
                if ($request->has('new_items') && is_array($request->new_items)) {
                    foreach ($request->new_items as $itemData) {
                        if (isset($itemData['title']) && isset($itemData['image']) && $itemData['image'] instanceof UploadedFile) {
                            // Get original filename for item image and use it directly
                            $itemImageName = $itemData['image']->getClientOriginalName();
                            $path = public_path('img/layanan');

                            // Handle duplicate filenames for items
                            $counter = 1;
                            $newItemImageName = $itemImageName;
                            while (File::exists($path . '/' . $newItemImageName)) {
                                $filename = pathinfo($itemImageName, PATHINFO_FILENAME);
                                $extension = pathinfo($itemImageName, PATHINFO_EXTENSION);
                                $newItemImageName = $filename . '_' . $counter . '.' . $extension;
                                $counter++;
                            }

                            $itemData['image']->move($path, $newItemImageName);

                            LayananItem::create([
                                'sub_layanan_id' => $subLayanan->id,
                                'title' => $itemData['title'],
                                'slug' => Str::slug($itemData['title']),
                                'image' => $newItemImageName
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Sub Layanan updated successfully',
                'data' => $subLayanan->load('items')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating Sub Layanan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Find the model
            $subLayanan = SubLayanan::findOrFail($id);

            // Delete associated image
            if ($subLayanan->image && file_exists(public_path('img/layanan/'.$subLayanan->image))) {
                unlink(public_path('img/layanan/'.$subLayanan->image));
            }

            // Delete associated items images
            foreach ($subLayanan->items as $item) {
                if ($item->image && file_exists(public_path('img/layanan/'.$item->image))) {
                    unlink(public_path('img/layanan/'.$item->image));
                }
                $item->delete();
            }

            $subLayanan->delete();

            DB::commit();

            if(request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sub Layanan deleted successfully'
                ]);
            }

            return redirect()->route('masterdata.layanan')
                ->with('success', 'Sub Layanan deleted successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Delete failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if(request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting Sub Layanan: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error deleting Sub Layanan');
        }
    }
}
