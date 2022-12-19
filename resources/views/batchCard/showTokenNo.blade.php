<!-- Custom STYLES -->			
<link href="{{asset('public/assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" />		
<ul id="searchResult">
    @if($tokenNumberArr->isNotEmpty())
    @foreach($tokenNumberArr as $tokenNo)
    <li value="{{$tokenNo->id}}"><span>{{$tokenNo->reference_no}}</span></li>
    @endforeach
    @else
    <li class="no-data" value="">No results Found</li>
    @endif
</ul>