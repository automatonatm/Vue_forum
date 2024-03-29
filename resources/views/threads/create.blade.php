@extends('layouts.app')

@section('header')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create a new thread</div>

                    <div class="card-body">
                        <form method="post" action="{{route('threads.store')}}">
                            {{csrf_field()}}


                            <div class="form-group">
                                <label for="channel_id">Choose a channel:</label>
                                <select class="form-control" name="channel_id" id="channel_id"  required>
                                    <option value="">Choose one...</option>
                                    @foreach($channels as $channel)
                                        <option value="{{$channel->id}}" {{old('channel_id') == $channel->id ? 'selected' : ''}}>{{$channel->name}}</option>
                                        @endforeach
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" id="title" name="title" value="{{old('title')}}" class="form-control" required>
                            </div>


                            <div class="form-group">
                                <label for="body">Body:</label>
                                <textarea id="body" name="body"  rows="5" class="form-control" required>{{old('body')}}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="6Lf2kcUUAAAAADw8ImYQfKTjAwmBigjvNXW2Njdm"></div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Publish</button>
                            </div>
                            @if(count($errors))
                                <ul class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
