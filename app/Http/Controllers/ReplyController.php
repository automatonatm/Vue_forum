<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

/**
 * Class ReplyController
 * @package App\Http\Controllers
 */
class ReplyController extends Controller
{
    /**
     * ReplyController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * @param Channel $channel
     * @param Thread $thread
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Channel $channel, Thread $thread)
    {
            return $thread->replies()->paginate(15);
    }

    /**
     * @param Channel $channel
     * @param Thread $thread
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function store(Channel $channel, Thread $thread, Request $request)
    {

        if($thread->locked) {
            return response('Thread is locked', 422);
        }

            $this->authorize('create', new Reply());

            $this->validate($request, [
                'body' => 'required|spamfree'
            ]);

            $reply = $thread->addReply([
                'body' => $request->body,
                'user_id' => auth()->id()
            ]);

            //inspect the body of the reply for user name mentions

            if(\request()->expectsJson()) {
                return $reply->load('user');
            }

    }

    /**
     * @param Reply $reply
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Reply $reply, Request $request)
    {

        $this->validate($request, ['body' => 'required|spamfree']);

        $this->authorize('update', $reply);
        $reply->update([
            'body' => \request('body')
        ]);

    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Reply $reply)
    {

        $this->authorize('update', $reply);

        if(\request()->ajax()) {
            $reply->delete();
            exit();
        }

        $reply->delete();

        return redirect()->back();

    }

}
