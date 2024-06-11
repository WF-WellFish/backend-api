<?php

namespace App\Actions\Api\MachineLearning;

use App\Actions\Action;
use App\Models\ClassificationHistory;
use Illuminate\Support\Facades\Auth;

class ClassificationAction extends Action
{
    /**
     * Classify user image and store the result in the database.
     * Call the machine learning API to classify the image.
     *
     * @param array $data
     * @return array
     */
    public function handle(array $data): array
    {
        //        try {
        //        $response = Http::post('http://localhost:5000/classification', [
        //            'image' => $request->image
        //        ]);
        //        } Catch(\Exception $e) {
        //            return $e->getMessage();
        //        }
        //

        // temporary result only for testing, replace with the actual result from the machine learning API
        $result = ClassificationHistory::factory()->make()->toArray();

        ClassificationHistory::query()->create(
            array_merge($result, ['user_id' => Auth::user()->id])
        );

        return $result;
    }
}