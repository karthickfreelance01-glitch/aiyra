@extends('admin.layouts.app')
@section('title', 'My Account')
@section('subtitle', 'Update your login details and password.')

@section('content')
<form action="{{ route('admin.account.update') }}" method="POST" class="narrow">
    @csrf
    <div class="card">
        <div class="card__head"><h3><i class="bi bi-person"></i> Profile</h3></div>
        <div class="card__body">
            <div class="form-row">
                <div class="form-group"><label class="form-label">Name</label><input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Login Email</label><input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control" required></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card__head"><h3><i class="bi bi-shield-lock"></i> Change Password</h3><small class="muted">Leave blank to keep the current password</small></div>
        <div class="card__body">
            <div class="form-group"><label class="form-label">Current Password</label><input type="password" name="current_password" class="form-control" autocomplete="current-password"></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">New Password</label><input type="password" name="password" class="form-control" autocomplete="new-password" minlength="8"></div>
                <div class="form-group"><label class="form-label">Confirm New Password</label><input type="password" name="password_confirmation" class="form-control" autocomplete="new-password"></div>
            </div>
            <button type="submit" class="btn btn--primary"><i class="bi bi-check2-circle"></i> Save Account</button>
        </div>
    </div>
</form>
@endsection
