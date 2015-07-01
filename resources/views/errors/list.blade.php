@if($errors->any())
    <ul id="errors" style="color: red">
        @foreach($errors->all() as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif