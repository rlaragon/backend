<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    use RefreshDatabase;

    /** @test */
    public function can_get_all_books()
    {
        $books = Book::factory(5)->create();

        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $books[0]->title
            ])->assertJsonFragment([
                'title' => $books[1]->title
            ]);
    }

    /** @test */
    public function can_get_one_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book))->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    /** @test */
    public function can_create_book()
    {
        $titulo = 'Nuevo libro';

        // verificación de que la validación es correcta
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'), [
            'title' => $titulo
        ])->assertJsonFragment([
            'title' => $titulo
        ]);

        $this->assertDatabaseHas('books', [
            'title' => $titulo
        ]);
    }

    /** @test */
    public function can_update_boos() 
    {
        $updatedTitle = 'Título editado';

        $book = Book::factory()->create();

        // verificación de que la validación es correcta
        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book), [
            'title' => $updatedTitle
        ])->assertJsonFragment([
            'title' => $updatedTitle
        ]);

        $this->assertDatabaseHas('books', [
            'title' => $updatedTitle
        ]);
    }

    /** @test */
    public function can_delete_books()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }

}
