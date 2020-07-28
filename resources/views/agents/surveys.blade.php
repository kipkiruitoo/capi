@extends('voyager::master')

@section('content')
<div class="container">
    <div class="row">
        <h4>Pick a Survey to continue</h4>
    </div>
    <div class="row">
        <div class="col col-md-6 offset-md-6">
            <div class="form">
                <form action="{{route('survey-manager.run')}}" method="POST">
                    @csrf
                    <input type="hidden" name="project" value="{{$project}}">
                    <div class="form-group">

                        <label for="survey">Surveys</label>
                        <select name="survey" class="form-control" id="survey">
                            @foreach($surveys as $survey)
                            <option value="{{$survey->slug}}">{{$survey->name}}</option>
                            @endforeach
                        </select>

                        <!-- <input type="text" class="form-control"> -->
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-dark ">New Interview</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <h4>Your most recent Interviews</h4>
        <div class="col col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project</th>
                        <th>Survey</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($interviews as $item)
                        <tr>
                        <td>{{$item->id}}</td>
                        <td>{{App\Projects::find($item->project)->name ?? "Not Available"}}</td>
                        <td>{{AidynMakhataev\LaravelSurveyJs\app\Models\Survey::find($item->survey)->name ?? "Not Available"}}</td>
                        <td>{{$item->created_at}}</td>
                        <td><a href="{{url('/agent/' . $item->survey .'/results/' . $item->id)}}"><button
                                class="btn btn-dark">View Results</button></a></td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
        <div class="col col-md-12">
{{ $interviews->links() }}
        </div>
    </div>
</div>
<script>
    window.onload = function () {
        var reload =  localStorage.getItem("reload")
        if (reload == 1) {
            localStorage.setItem("reload", 0)
            location.reload();
        }
    }
</script>
@endsection
