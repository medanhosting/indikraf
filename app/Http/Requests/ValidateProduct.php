<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'seller_id'         => 'required',
          'category_id'       => 'required',
          'store_id'          => 'required',
          'product_name'      => 'required',
          'description'       => 'required',
          'stock'             => 'required|numeric',
          'first_price'       => 'required|numeric',
          'price'             => 'required|numeric',
          'weight'            => 'required|numeric',
          'meta_keyword'      => 'required',
          'meta_description'  => 'required',
          'file'              => 'required|image'
        ];
    }
}
