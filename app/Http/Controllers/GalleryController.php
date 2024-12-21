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
    // menampilkan data pada table post yang memiliki gambar dan gambar tidak kosong
    public function index()
    {
        $data = array(
            'id' => "posts",
            'menu' => 'Gallery',
            'galleries' => array()
            // 'galleries' => Post::where('picture', '!=', '')->whereNotNull('picture')->orderBy('created_at', 'desc')->paginate(30)
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
    // form upload gambar
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'picture' => 'image|nullable|max:1999'
        ]);
        if ($request->hasFile('picture')){
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $basename = uniqid().time();
            $smallFilname = "small_{$basename}.{$extension}"; 
            $mediumFilname = "medium_{$basename}.{$extension}"; 
            $largeFilname = "large_{$basename}.{$extension}"; 
            $FilnameSimpan = "{$basename}.{$extension}";
            $path = $request->file('picture')->storeAs('posts_image', $FilnameSimpan);    
        }else{
            $FilnameSimpan = 'noimage.png';
        }
        // dd($request->input());
        $post = new Post;
        $post -> picture = $FilnameSimpan;
        $post -> title = $request->input('title');
        $post -> description = $request->input('description');
        $post -> save();

        return redirect('gallery')->with('success', 'Berhasil menambahkan data baru');
    }  

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gallery = Post::findOrFail($id); // Mencari data berdasarkan ID, atau gagal jika tidak ditemukan

        if (!$gallery) {
            return redirect('gallery')->with('error', 'Gallery item not found');
        }

        return view('gallery.edit')->with('gallery', $gallery); // Mengirim data ke view edit
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
    
        $post = Post::findOrFail($id); // Mengambil data yang ingin diperbarui
       
        // Mengecek jika ada gambar baru yang diupload
        if ($request->hasFile('picture')) {
            if ($post->picture != 'noimage.png') {
                Storage::delete('public/posts/' . $post->picture); // Pastikan path sesuai
            }
            // Mengupload dan menyimpan gambar baru
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $basename = uniqid().time();
            $filenameToSave = "{$basename}.{$extension}";
            $path = $request->file('picture')->storeAs('posts_image', $filenameToSave);
            $post->picture = $filenameToSave;
        }
    
        // Mengupdate judul dan deskripsi
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->save();
    
        return redirect('gallery')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
    
        // Mengecek jika file gambar ada dan menghapusnya
        if ($post->picture != 'noimage.png') {
            Storage::delete('posts_image/' . $post->picture);
        }
    
        $post->delete(); // Menghapus data dari database
    
        return redirect('gallery')->with('success', 'Data berhasil dihapus');
    }

    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/gallery",
     *     tags={"gallery"},
     *     summary="Returns a Sample API gallery response",
     *     description="A sample gallery to test out the API",
     *     operationId="gallery",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent
     *           (example={
     *               "success": true,
     *               "message": "Berhasil memproses galleries",
     *               "galleries": {
     *                  {
     *                      "id": 1,
     *                      "title": "gallery bell",
     *                      "description": "deskripsi gallery bell",
     *                      "picture": "bell.jpeg",
     *                      "created_at": "2024-11-06T02:20:42.000000Z",
     *                      "updated_at": "2024-11-06T02:20:42.000000Z"
     *                  }
     *              }
     *          }),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data tidak ditemukan",
     *         @OA\JsonContent
     *           (example={
     *               "detail": "strings"
     *          }),
     *     )
     * )
     */
    
    public function gallery()
    {
        $data = array(
            'message' => 'Berhasil memproses galleries',
            'success' => true,
            'galleries' => Post::where('picture', '!=', '')->whereNotNull('picture')->orderBy('created_at', 'desc')->get()
        );
        return response()->json($data);
    }
}
