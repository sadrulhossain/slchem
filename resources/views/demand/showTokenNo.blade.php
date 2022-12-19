<!-- Custom STYLES -->			
<link href="{{asset('public/assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" />		
<ul id="tokenResult">
    @if($tokenNumberArr->isNotEmpty())
    @foreach($tokenNumberArr as $tokenNo)
    <li value="{{$tokenNo->id}}"><span>{{$tokenNo->token_no}}</span></li>
    @endforeach
    @else
    <li class="no-data" value="">No results Found</li>
    @endif
</ul>