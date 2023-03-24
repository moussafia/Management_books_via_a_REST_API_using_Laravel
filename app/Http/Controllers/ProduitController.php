<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index()
    {
        $produit=Produit::with(['Category','Collection','User'])->get();
        return response()->json([
            "produit"=>$produit
        ],200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required|max:255',
            'autor'=>'required|max:255',
            'collection_id'=>'max:255',
            'isbn'=>'required|digits:13|unique:produits,isbn',
            'date_pub'=>'required|date',
            'nmb_page'=>'required|integer',
            'emplacement' =>'required|max:255',
            'statut'=>'required|in:disponible,indisponible,emprunté,entraitement',
            'contenu'=>'required|min:10',
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',
        ]);
        $produit=new Produit($request->all());
        $produit->user_id=auth()->id();
        $produit->save();
        $categoryIDstring=implode(',',$request->category_id);
        $categoryIDs=explode(',',$categoryIDstring);
        foreach($categoryIDs as $category){
            $produit->category()->syncWithoutDetaching($category);
        }
        if($request->collection_id){
            $produit->collection()->associate($request->collection_id);
            $produit->save();
        }
        return response()->json([
            "message"=>'produit is succesfuly stored'
        ],201);
    }

    public function show($id)
    {
        $produit=Produit::findOrFail($id)->with(['Category','Collection','User'])->get();
        return response()->json([
            "produit"=>$produit
        ],200);
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'title'=>'max:255',
            'autor'=>'max:255',
            'collection_id'=>'max:255',
            'isbn'=>'digits:13|unique:produits,isbn',
            'date_pub'=>'date',
            'nmb_page'=>'integer',
            'emplacement' =>'max:255',
            'statut'=>'in:disponible,indisponible,emprunté,entraitement',
            'contenu'=>'min:10',
            'category_id' => 'array',
            'category_id.*' => 'exists:categories,id',
        ]);
        $produit=Produit::findOrFail($id);
        $produit->update($request->except('_method','category_id','collection_id'));
        if($request->category_id){
            $categoryIDstring=implode(',',$request->category_id);
            $categoryIDs=explode(',',$categoryIDstring);
            foreach($categoryIDs as $category){
                $produit->category()->syncWithoutDetaching($category);
            }
        }
        if($request->collection_id){
            $produit->collection()->associate($request->collection_id);
            $produit->save();
        }
        return response()->json([
            "message"=> "data is succesfuly updated",
            "produit"=>$produit
        ],200);
    }
    public function destroy($id)
    {
        $produit=new Produit;
        $produit->destroy($id);
        return response()->json([
            "message"=> "data is succesfuly deleted",
        ],200);
    }
    public function filtrerParGenre($genre_id){
        $category=Category::findOrFail($genre_id);
        $produitId=$category->produit()->pluck('produits.id')->toArray();
        $produit=Produit::where('id',$produitId)->with(['Category','Collection','User'])->get();
        return response()->json([
            "produit"=>$produit
        ],200);
    }
}
