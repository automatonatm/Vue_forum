
@component('profiles.activities.activity')


    @slot('heading')



     {{--   {{route('threads.show', ['channel' => $activity->subject->thread->channel->slug, 'thread' => $activity->subject->thread->id])}}--}}
        <a href="{{route('threads.show',
        ['channel' => $activity->subject->favorited->thread->channel->slug,
         'thread' => $activity->subject->favorited->thread->id])}}#reply-{{$activity->subject->favorited->id}}">
            {{$profile_user->name}} favorited a reply

        </a>

    @endslot


    @slot('body')

        {{$activity->subject->favorited->body}}

    @endslot

@endcomponent


