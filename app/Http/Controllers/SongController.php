<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;

/**
 * @OA\Info(title="Music Catalog API", version="1.0")
 */

/**
 * @OA\Schema(
 *     schema="Song",
 *     type="object",
 *     @OA\Property(property="title", type="string", example="Song Title"),
 *     @OA\Property(property="track_number", type="integer", example=1),
 *     @OA\Property(property="album_id", type="integer", example=1)
 * )
 */
class SongController extends Controller
{
    // Получение списка песен
    /**
     * @OA\Get(
     *     path="/api/songs",
     *     summary="Get all songs",
     *     @OA\Response(response="200", description="A list of songs")
     * )
     */
    public function index()
    {
        return Song::with('album')->get();
    }

    // Создание новой песни
    /**
     * @OA\Post(
     *     path="/api/songs",
     *     summary="Create a new song",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Song")
     *     ),
     *     @OA\Response(response="201", description="Song created"),
     *     @OA\Response(response="400", description="Invalid input")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'track_number' => 'required|integer',
            'album_id' => 'required|exists:albums,id',
        ]);

        $song = Song::create($request->all());
        return response()->json($song, 201);
    }

    // Получение конкретной песни
    /**
     * @OA\Get(
     *     path="/api/songs/{id}",
     *     summary="Get a specific song",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Song found"),
     *     @OA\Response(response="404", description="Song not found")
     * )
     */
    public function show($id)
    {
        return Song::with('album')->findOrFail($id);
    }

    // Обновление данных песни
    /**
     * @OA\Put(
     *     path="/api/songs/{id}",
     *     summary="Update an existing song",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Song")
     *     ),
     *     @OA\Response(response="200", description="Song updated"),
     *     @OA\Response(response="404", description="Song not found"),
     *     @OA\Response(response="400", description="Invalid input")
     * )
     */
    public function update(Request $request, $id)
    {
        $song = Song::findOrFail($id);
        $song->update($request->all());
        return response()->json($song, 200);
    }

    // Удаление песни
    /**
     * @OA\Delete(
     *     path="/api/songs/{id}",
     *     summary="Delete a song",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Song deleted"),
     *     @OA\Response(response="404", description="Song not found")
     * )
     */
    public function destroy($id)
    {
        Song::destroy($id);
        return response()->json(null, 204);
    }
}