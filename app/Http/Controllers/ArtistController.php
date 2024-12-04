<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;

/**
 * @OA\Info(title="Music Catalog API", version="1.0")
 */

/**
 * @OA\Schema(
 *     schema="Artist",
 *     type="object",
 *     @OA\Property(property="name", type="string", example="Artist Name")
 * )
 */
class ArtistController extends Controller
{
    // Получение списка исполнителей
    /**
     * @OA\Get(
     *     path="/api/artists",
     *     summary="Get all artists",
     *     @OA\Response(response="200", description="A list of artists")
     * )
     */
    public function index()
    {
        return Artist::all();
    }

    // Создание нового исполнителя
    /**
     * @OA\Post(
     *     path="/api/artists",
     *     summary="Create a new artist",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Artist")
     *     ),
     *     @OA\Response(response="201", description="Artist created"),
     *     @OA\Response(response="400", description="Invalid input")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $artist = Artist::create($request->all());
        return response()->json($artist, 201);
    }

    // Получение конкретного исполнителя
    /**
     * @OA\Get(
     *     path="/api/artists/{id}",
     *     summary="Get a specific artist",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Artist found"),
     *     @OA\Response(response="404", description="Artist not found")
     * )
     */
    public function show($id)
    {
        return Artist::findOrFail($id);
    }

    // Обновление данных исполнителя
    /**
     * @OA\Put(
     *     path="/api/artists/{id}",
     *     summary="Update an existing artist",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Artist")
     *     ),
     *     @OA\Response(response="200", description="Artist updated"),
     *     @OA\Response(response="404", description="Artist not found"),
     *     @OA\Response(response="400", description="Invalid input")
     * )
     */
    public function update(Request $request, $id)
    {
        $artist = Artist::findOrFail($id);
        $artist->update($request->all());
        return response()->json($artist, 200);
    }

    // Удаление исполнителя
    /**
     * @OA\Delete(
     *     path="/api/artists/{id}",
     *     summary="Delete an artist",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Artist deleted"),
     *     @OA\Response(response="404", description="Artist not found")
     * )
     */
    public function destroy($id)
    {
        Artist::destroy($id);
        return response()->json(null, 204);
    }
}