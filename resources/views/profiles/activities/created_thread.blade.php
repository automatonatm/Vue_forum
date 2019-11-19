
@component('profiles.activities.activity')


    @slot('heading')

        {{$profile_user->name}} Created
        <a href="{{route('threads.show', ['channel' => $activity->subject->channel->slug, 'thread' => $activity->subject->id])}}">
            {{$activity->subject->title}}
        </a>

    @endslot


    @slot('body')

        {{$activity->subject->body}}

    @endslot

@endcomponent


