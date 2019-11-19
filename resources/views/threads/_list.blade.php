@forelse($threads as $thread)
    <div class="card mb-3">
        <div class="card-header">

            <img class="" src="/storage/{{$thread->user->avatar()}}" alt="{{$thread->user->name}}" width="50" height="50">
            <span class="text-primary text-left">Posted By: <a href="/profiles/{{$thread->user->name}}">{{$thread->user->name}} </a> {{$thread->created_at->diffForHumans()}}</span>

        </div>

        <div class="card-body flex-grow-1">
            <article>
                <div class="level d-flex align-content-center">
                    <h4 class="flex-grow-1">
                        <a href="{{route('threads.show', ['channel' => $thread->channel->slug, 'thread' =>$thread->slug])}}">

                            @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                <strong>
                                    {{$thread->title}}
                                </strong>
                            @else
                                {{$thread->title}}
                            @endif

                        </a>
                    </h4>
                    <a href="{{route('threads.show', ['channel' => $thread->channel->slug, 'thread' =>$thread->slug])}}">{{$thread->replies_count}} {{str_plural('comment', $thread->replies_count)}}</a>
                </div>
                <div class="text-body text-justify">

                    {{\Illuminate\Support\Str::words($thread->body, 50)}}

                </div>
            </article>
            <hr>
        </div>

        <div class="card-footer">
            <span><i class="fa fa-eye"></i> {{$thread->views()}}</span>
        </div>

    </div>

@empty
    <div class="font-weight-bold h1">No Threads Yet</div>

@endforelse