@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('threads._list')
                <div class="align-content-center">
                        {{$threads->render()}}
                </div>
            </div>

            <div class="col-md-4">

                    <div class="card section-padding">
                        <div class="card-header">
                            Search Forum
                        </div>
                        <div class="card-body">
                            <form action="/threads/search" method="GET">
                                <div class="form-group">
                                    <input class="form-control" type="text" placeholder="Search for something.." name="q">
                                </div>
                                <div class="form-group">
                                    <button class="btn-primary" type="submit">Search</button>
                                </div>
                            </form>
                        </div>

            </div>

                @if(count($trending))
                    <div class="card">
                        <div class="card-header">
                            Trending Threads
                        </div>
                        <div class="card-body">
                            @foreach($trending as $thread)
                                <li class="list-group-item">
                                    <a href="{{url($thread->path)}}">
                                        {{\Illuminate\Support\Str::words($thread->title, 5)}}
                                    </a>
                                </li>
                            @endforeach
                        </div>
                    </div>
                @endif

        </div>
    </div>
@endsection
