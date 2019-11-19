
@component('profiles.activities.activity')


    @slot('heading')

        {{$profile_user->name}} replied to
        <a href="{{route('threads.show',
         ['channel' => $activity->subject->thread->channel->slug,
         'thread' => $activity->subject->thread->id])}}#reply-{{$activity->subject->id}}">
            {{$activity->subject->thread->title}}
        </a>
        @endslot
    @slot('body')
        {{$activity->subject->body}}
    @endslot
    @endcomponent


