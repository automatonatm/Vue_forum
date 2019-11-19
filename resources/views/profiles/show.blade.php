
@extends('layouts.app')

@section('content')

 <div class="container">
     <div class="title flex-column">
         <h1>
             {{$profile_user->name}}
             <small>Since {{$profile_user->created_at->diffForHumans()}}</small>
         </h1>

         <avatar-form :user="{{$profile_user}}" :avatar_path="{{json_encode($profile_user->avatar())}}"></avatar-form>


     </div>

     <div class="row">
         <div class="col-md-8 offset-md-2">
             @forelse($activities as $date => $activity)
                   <div class="h2">{{$date}}</div>
                 @foreach($activity as $record)
                     @if(view()->exists("profiles.activities.{$record->type}"))
                     @include("profiles.activities.{$record->type}", ['activity' => $record])
                     @endif
                     @endforeach
             @empty
                 <span class="font-weight-bold">
                     No Threads Yet
                 </span>

             @endforelse

                 <div class="mt-2">
                  {{--   {{$threads->links()}}--}}

                 </div>




         </div>

     </div>
 </div>




    @endsection