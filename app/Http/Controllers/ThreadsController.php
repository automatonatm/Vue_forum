<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Requests\ThreadRequest;
use App\Rules\Recaptcha;
use App\Thread;
use App\ThreadFilters;
use App\Trending;
use Illuminate\Http\Request;


/**
 * Class ThreadsController
 * @package App\Http\Controllers
 */
class ThreadsController extends Controller
{
    /**
     * ThreadsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }


    /**
     * Display a listing of the resource.
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @param Trending $trending
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {


        $threads = $this->getThread($channel, $filters);
        if(request()->wantsJson()) {
            return $threads;
        }




        return view('threads.index',
            [
                'threads' => $threads,
                'trending' =>  $trending->get() // Getting the trending threads
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('threads.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Recaptcha $recaptcha)
    {

       //return $request->all();

        $this->validate($request, [
                'title' => 'required|spamfree',
                'body' => 'required|spamfree',
                'channel_id' => 'required|exists:channels,id',
                'g-recaptcha-response' => ['required', $recaptcha]
        ]);


        $thread = Thread::create([
            'title' => $request->title,
            'body' => request('body'),
            'user_id' => auth()->id(),
            'channel_id' =>$request->channel_id
        ]);

        if(request()->wantsJson()) {
            return response($thread, 201);
        }

        //dd($thread);
        return redirect($thread->path())
            ->with('flash', 'Your Thread has been Created!');


      // return $request->storeThread();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel, Thread $thread, Trending $trending)
    {

        //Record that the user visited this page
        //Record a time stamp


            if(auth()->check()) {
                auth()->user()->read($thread);
            }

            $trending->push($thread); //Set Trending thread
            $thread->recordViews();  // Increment views

          return view('threads.show', [
                'thread' => $thread
              ]

        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Channel $channel, Thread $thread)
    {




    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel, Thread $thread)
    {

        //$thread->replies()->delete();
        /*
         * We delete Reply using model events
         * */

       $this->authorize('update', $thread);

        $thread->delete();

        if(\request()->wantsJson()) {
            return response([], 204);
        }
        return redirect('/threads')->with('flash', 'Thread Has Been Removed');
    }

    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    protected function getThread(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads = $channel->threads()->latest();
        }
        return $threads->paginate(10);
    }

    public function storeThread()
    {

    }


}
