<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

/**
 * @OA\Info(title="Music Catalog API", version="1.0")
 */

/**
 * @OA\Schema(
 *     schema="Album",
 *     type="object",
 *     @OA\Property(property="title", type="string", example="Album Title"),
 *     @OA\Property(property="release_year", type="integer", example=2023),
 *     @OA\Property(property="artist_id", type="integer", example=1)
 * )
 */
class AlbumController extends Controller
{
    // Получение списка альбомов
    /**
     * @OA\Get(
     *     path="/api/albums",
     *     summary="Get all albums",
     *     @OA\Response(response="200", description="A list of albums")
     * )
     */
    public function index()
    {
        return Album::with('artist')->get();
    }

    // Создание нового альбома
    /**
     * @OA\Post(
     *     path="/api/albums",
     *     summary="Create a new album",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Album")
     *     ),
     *     @OA\Response(response="201", description="Album created"),
     *     @OA\Response(response="400", description="Invalid input")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'release_year' => 'required|integer',
            'artist_id' => 'required|exists:artists,id',
        ]);

        $album = Album::create($request->all());
        return response()->json($album, 201);
    }

    // Получение конкретного альбома
    /**
     * @OA\Get(
     *     path="/api/albums/{id}",
     *     summary="Get a specific album",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Album found"),
     *     @OA\Response(response="404", description="Album not found")
     * )
     */
    public function show($id)
    {
        return Album::with('artist', 'songs')->findOrFail($id);
    }

    // Обновление данных альбома
    /**
     * @OA\Put(
     *     path="/api/albums/{id}",
     *     summary="Update an existing album",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Album")
     *     ),
     *     @OA\Response(response="200", description="Album updated"),
     *     @OA\Response(response="404", description="Album not found"),
     *     @OA\Response(response="400", description="Invalid input")
     * )
     */
    public function update(Request $request, $id)
    {
        $album = Album::findOrFail($id);
        $album->update($request->all());
        return response()->json($album, 200);
    }

    // Удаление альбома
    /**
     * @OA\Delete(
     *     path="/api/albums/{id}",
     *     summary="Delete an album",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Album deleted"),
     *     @OA\Response(response="404", description="Album not found")
     * )
     */
    public function destroy($id)
    {
        Album::destroy($id);
        return response()->json(null, 204);
    }
}