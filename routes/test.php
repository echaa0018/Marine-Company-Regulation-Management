<?php

use Illuminate\Support\Facades\Route;
use App\Models\Document;

Route::get('/test-documents', function () {
    try {
        $documents = Document::all();

        echo "<h1>Document Test</h1>";
        echo "<p>Found " . $documents->count() . " documents:</p>";

        foreach ($documents as $doc) {
            echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
            echo "<h3>{$doc->title}</h3>";
            echo "<p><strong>Number:</strong> {$doc->number}</p>";
            echo "<p><strong>ID:</strong> {$doc->id}</p>";
            echo "<p><strong>Type:</strong> {$doc->type}</p>";
            echo "<p><strong>Status:</strong> {$doc->status}</p>";
            echo "<a href='/document/{$doc->id}' style='background: blue; color: white; padding: 5px 10px; text-decoration: none;'>View Detail</a>";
            echo "</div>";
        }
    } catch (Exception $e) {
        echo "<h1>Error: " . $e->getMessage() . "</h1>";
    }
})->middleware('web');
