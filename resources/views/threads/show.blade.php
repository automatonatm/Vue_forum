@extends('layouts.app')

@section('header')

    <link href="{{ asset('css/vendor/jquery.atwho.css') }}" rel="stylesheet">
    @endsection

@section('content')

    <thread-view :initial-replies-count="{{$thread->replies_count}}" inline-template>

        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card justify-content-center">
                        <div class="card-header">
                            @php

                                    @endphp
                            <div class="d-flex">
                                <div class="flex-grow-1">

                                    <img class="" src="/storage/{{$thread->user->avatar()}}" alt="" width="50" height="50">

                                    <a href="{{route('profile', $thread->user->name)}}">{{$thread->user->name}}</a> posted:
                                    {{$thread->title}}  {{$thread->created_at->diffForHumans()}}

                                </div>
                                @guest

                                @else
                                    @can('update', $thread)
                                        <div class="">
                                            <form method="POST" action="{{$thread->path()}}">
                                                {{csrf_field()}}
                                                {{method_field('DELETE')}}
                                                <button type="submit" class="btn btn-link">Delete Thread</button>
                                            </form>
                                        </div>
                                    @endcan
                                @endguest
                            </div>
                        </div>

                        <div class="card-body">
                            <article>

                                <div class="text-body text-justify">{{$thread->body}}</div>
                            </article>
                            <hr>
                            <replies

                                     @add="repliesCount++"
                                     @removed="repliesCount--">

                            </replies>




                        </div>
                    </div>
                </div>

                <div class="col-md-4">

                    <div class="card">

                        <div class="card-body">
                            <p>
                                This thread was published {{$thread->created_at->diffForHumans()}}
                                by <a href="{{route('profile',$thread->user->name )}}">{{$thread->user->name}}</a> and current has @{{repliesCount}} {{str_plural('comment', $thread->replies_count)}}
                            </p>

                          <subscribe-button :active="{{json_encode($thread->isSubscribedTo)}}"></subscribe-button>

                        </div>

                    </div>

                </div>
            </div>


        </div>

    </thread-view>

@endsection
