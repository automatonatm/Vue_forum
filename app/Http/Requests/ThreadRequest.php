<?php

namespace App\Http\Requests;

use App\Thread;
use Illuminate\Foundation\Http\FormRequest;

class ThreadRequest extends FormRequest
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
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id'
        ];
    }

    public function storeThread()
    {

        $thread = Thread::create([
            'title' => $this->title,
            'body' => $this->body,
            'user_id' => auth()->id(),
            'channel_id' =>$this->channel_id
        ]);

        if(request()->wantsJson()) {
            return response($thread, 201);
        }


        //dd($thread);
        return redirect($thread->path())
            ->with('flash', 'Your Thread has been Created!');
    }


}
