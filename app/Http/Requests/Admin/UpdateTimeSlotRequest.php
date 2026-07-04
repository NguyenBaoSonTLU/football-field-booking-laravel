<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class UpdateTimeSlotRequest extends StoreTimeSlotRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $timeSlotId = $this->route('timeSlot')?->id ?? $this->route('timeSlot');
        $rules['start_time'][] = Rule::unique('time_slots', 'start_time')
            ->where(fn ($query) => $query->where('end_time', $this->input('end_time')))
            ->ignore($timeSlotId);

        return $rules;
    }
}
