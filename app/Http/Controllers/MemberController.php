<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use RealRashid\SweetAlert\Facades\Alert;


class MemberController extends Controller
{
    public function index()
    {
        return view('admin.masterdata.Member.data-member', ['type_menu' => 'master-data']);
    }

    public function getData(Request $request)
    {
        $query = User::all();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function($user) {
                return $user->created_at ? $user->created_at->format('d F Y') : '';
                // Or for Indonesian format:
                // return $user->created_at ? $user->created_at->isoFormat('D MMMM Y') : '';
            })
            ->addColumn('actions', function($query) {
                return '
                    <div class="d-flex justify-content-center gap-2">
                        <a href="'.route('Member.edit', $query->id).'" class="btn btn-warning btn-sm">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form action="'.route('Member.destroy', $query->id).'" method="POST" class="delete-form">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm btn-delete" data-name="'.$query->name.'">
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
        $member = User::findOrFail($id);
        return view('admin.masterdata.Member.edit-member', [
            'type_menu' => 'master-data',
            'member' => $member
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'username' => 'required|unique:users,username,'.$id,
            'telp' => 'required',
            'role' => 'required|in:admin,user,Front Office, Back Office, Editor',
        ]);

        $member = User::findOrFail($id);
        $member->update($validated);

        toast('Member Data Updated Successfully','success');
        return redirect()->route('Member.index');
    }

    public function destroy($id)
    {
        try {
            $member = User::findOrFail($id);
            $member->delete();

            if (request()->ajax()) {
                return response()->json(['success' => true]);
            }

            toast('Member Data Deleted Successfully','success');
            return redirect()->route('Member.index');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false], 500);
            }

            toast('Failed to delete member','error');
            return redirect()->route('Member.index');
        }
    }
}
