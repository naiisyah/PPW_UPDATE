@extends('auth.layouts')
@section('content')
<table>
    <tr>
        <td>Name</td>
        <td>Email</td>
        <td>Photo</td>
        <td>Action</td>
    </tr>
    @foreach ($userss as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
            @if($user->photo)
            <img src="{{ asset('storage/'.$user->photo) }}" width="100px">
            @else
            <img src="{{ asset('noimage.jpg') }}" width="100px">
            @endif
        </td>
        <td>
            <a href="{{ route('users.edit', $user->id, class="btn btn-sm btn-primary") }}">Edit</a>
            <form action="{{ route('users.destroy', $user->id) }}"method ="POST">
                @method('DELETE')
                @csrf
                <br/>
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection