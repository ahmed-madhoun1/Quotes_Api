<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Quote as QuoteResource;
use App\Models\Quote;
use Illuminate\Support\Facades\Validator;

class QuoteController extends BaseController
{

    public function index()
    {
        $quotes = Quote::all();
        return $this->sendResponse(QuoteResource::collection($quotes), 'All quotes sent');
    }


    public function store(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'quote' => $request->quote,
            'author' => $request->author,
        ]);
        if($validator->failed()){
            return $this->sendError('Please validate error', $validator->errors());
        }
        $quote = Quote::create($inputs);
        return $this->sendResponse(new QuoteResource($quote), 'Quote created successfully');
    }

    public function show($id)
    {
        $quote = Quote::find($id);
        if(is_null($quote)){
            return $this->sendError('Quote not found');
        }
        return $this->sendResponse(new QuoteResource($quote), 'Quote found successfully');
    }

    public function update(Request $request, Quote $quote)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'quote' => $request->quote,
            'author' => $request->author,
        ]);
        if($validator->failed()){
            return $this->sendError('Please validate error', $validator->errors());
        }
        $quote->quote = $inputs['quote'];
        $quote->author = $inputs['author'];
        $quote->photo = $inputs['photo'];
        $quote->audio = $inputs['audio'];
        $quote->save();
        return $this->sendResponse(new QuoteResource($quote), 'Quote updated successfully');
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();
        return $this->sendResponse(new QuoteResource($quote), 'Quote deleted successfully');
    }
}
