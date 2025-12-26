@if ($errors->any())
    <div class="w-4/8 m-auto text-center">
    @foreach($errors->all() as $error)
        <li class="text-red-600 list-none validation-except-margin">{{ $error }}</li>
        <div class="dropdown-divider"></div>
    @endforeach
    </div>
@endif