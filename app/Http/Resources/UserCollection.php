<?php
 
namespace App\Http\Resources;
 
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
 
class UserCollection extends ResourceCollection
{
    
    public function toArray(Request $request): array
    {
        $data = [];
        foreach($this->collection as $user){
            $data[] = [
                "id" => $user->id,
                "email" => $user->email,
                "name" => $user->name,
                "photo" => url('public/photo'.$user->photo),
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at,
            ];
        }
        return [
            // 'data' => $this->collection,
            'data' => $data,
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}