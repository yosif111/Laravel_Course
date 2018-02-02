<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Book;
class BookController extends Controller
{
    
    public function createBook(Request $request){
        $fields = $request->only('title','ISBN','author','genre');
        $rules = array(
            'ISBN' => 'unique:books|required|min:5',
            'author' => 'alpha|max:20',
            'title' => 'unique:books'
        );
        $validator = \Validator::make($fields, $rules);

        if( $validator->fails() ){
            return new Response(['Message' => 'validation error'],401);
        }
        Book::create($fields);
        return new Response(['Message' => 'the book was added'],200);
    }

    public function show($id){
        $book =  Book::find($id);

        if(!$book){
            return new Response(['Message' => 'book not found'],404);
        }
        return new Response($book,200);
    }

    public function delete($id){
        $book = Book::destroy($id);

        if(! $book)
            return new Response(['Message' => 'book not found'],404);

        return new Response(['Message' => 'book deleted :)'],200);
    }   

    public function update(Request $request){
        $book = Book::find($request->id);

        if(! $book)
             return new Response(['Message' => 'book not found'],404);
        $fields = $request->only('title','author');

        $rules = array(
            'author' => 'alpha|max:20',
            'title' => 'unique:books'
        );
        $validator = \Validator::make($fields, $rules);
        if( $validator->fails() ){
            return new Response(['Message' => 'validation error'],401);
        }

        $book->update($fields);
        $book->save();

        return new Response(['Message' => 'Book updated'],200);
    }
}
