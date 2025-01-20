<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'subject_id' => $this->subject_id,
            'test_id' => $this->test_id,
            'mcq' => $this->mcq,
            'cq' => $this->cq,
            'total_mark' => $this->total_mark,
            'total_exam' => $this->total_exam,
            'date' => $this->date,
            'subject' => $this->subject,
            'student' => $this->student,
            'test' => $this->test,
        ];
    }
}
