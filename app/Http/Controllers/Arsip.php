<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array(
            'id' => "posts",
            'menu' => 'Gallery',
            'galleries' => Post::where('picture', '!=', '')->whereNotNull('picture')->orderBy('created_at', 'desc')->paginate(30)
        );
        return view('gallery.index', $data);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gallery.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'picture' => 'image|nullable|max:10000'
        ]);
        // Upload Image
        if ($request->hasFile('picture')) {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $filenameToSave = $filename . '_' . time() . '.' . $extension;
            // Menyimpan gambar di public/posts
            $path = $request->file('picture')->storeAs('posts', $filenameToSave);
        } else {
            $filenameToSave = null;
        }
        $post = new Post;
        $post->picture = $filenameToSave;
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->save();
        return redirect('gallery')->with('success', 'Berhasil menambahkan data baru');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $gallery = Post::find($id);
    
        if (!$gallery) {
            return redirect('gallery')->with('error', 'Gallery item not found');
        }
        
        return view('gallery.edit')->with('gallery', $gallery);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'picture' => 'image|nullable|max:1999'
        ]);
        $post = Post::find($id);
        if ($request->hasFile('picture')) {
            if ($post->picture != 'noimage.png') {
                Storage::delete('public/posts/' . $post->picture); // Pastikan path sesuai
            }
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $filenameToSave = $filename . '_' . time() . '.' . $extension;
            // Menyimpan gambar di public/posts
            $path = $request->file('picture')->storeAs('posts/', $filenameToSave);
            $post->picture = $filenameToSave;
        }
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->save();
        return redirect('gallery')->with('success', 'Data berhasil diperbarui');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if ($post->picture != 'noimage.png') {
            Storage::delete('public/posts/' . $post->picture); // Pastikan path sesuai
        }
        $post->delete();
        return redirect('gallery')->with('success', 'Data berhasil dihapus');
    }
}