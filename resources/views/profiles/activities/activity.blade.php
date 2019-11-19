
{{--
@if($activity->type == 'created_reply')
    {{$profile_user->name}} Published a reply
@endif--}}


<div class="card mt-3">
    <div class="card-header">
        {{-- <a href="#">{{$thread->user->name}}</a> posted:
         <a href="{{route('threads.show', ['channel' => $thread->channel->slug, 'thread' =>$thread->id])}}">
             {{$thread->title}}
         </a>--}}
        {{-- {{$thread->created_at->diffForHumans()}}--}}
       {{$heading}}

    </div>

    <div class="card-body">
        <article>

            <div class="text-body text-justify">
              {{$body}}
            </div>
        </article>
    </div>
</div>
