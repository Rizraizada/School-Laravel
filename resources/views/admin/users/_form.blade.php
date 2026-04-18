<div class="form-grid">
    <div class="form-group">
        <label for="username">Username *</label>
        <input id="username" type="text" name="username" value="{{ old('username', $user->username ?? '') }}" required>
    </div>
    <div class="form-group">
        <label for="full_name">Full Name *</label>
        <input id="full_name" type="text" name="full_name" value="{{ old('full_name', $user->full_name ?? '') }}" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}">
    </div>
    <div class="form-group">
        <label for="role">Role *</label>
        <select id="role" name="role" required>
            @php $role = old('role', $user->role ?? 'teacher'); @endphp
            <option value="headmaster" @selected($role === 'headmaster')>Headmaster</option>
            <option value="teacher" @selected($role === 'teacher')>Teacher</option>
            <option value="admin" @selected($role === 'admin')>Admin</option>
        </select>
    </div>
    <div class="form-group">
        <label for="password">Password {{ $isEdit ? '' : '*' }}</label>
        <input id="password" type="password" name="password" {{ $isEdit ? '' : 'required' }}>
    </div>
    <div class="form-group">
        <label for="phone">Phone</label>
        <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}">
    </div>
    <div class="form-group">
        <label for="gender">Gender</label>
        <select id="gender" name="gender">
            @php $gender = old('gender', $user->gender ?? ''); @endphp
            <option value="">Select</option>
            <option value="male" @selected($gender === 'male')>Male</option>
            <option value="female" @selected($gender === 'female')>Female</option>
            <option value="other" @selected($gender === 'other')>Other</option>
        </select>
    </div>
    <div class="form-group">
        <label for="expertise">Expertise</label>
        <input id="expertise" type="text" name="expertise" value="{{ old('expertise', $user->expertise ?? '') }}">
    </div>
    <div class="form-group">
        <label for="position">Position</label>
        <input id="position" type="text" name="position" value="{{ old('position', $user->position ?? '') }}">
    </div>
</div>
<div class="form-group" style="margin-top:12px;">
    <label for="address">Address</label>
    <textarea id="address" name="address">{{ old('address', $user->address ?? '') }}</textarea>
</div>
<div class="form-group" style="margin-top:12px;">
    <label for="description">Description</label>
    <textarea id="description" name="description">{{ old('description', $user->description ?? '') }}</textarea>
</div>
<div class="form-group" style="margin-top:12px;">
    <label for="image">Profile Image</label>
    <input id="image" type="file" name="image" accept="image/*">
    @if(!empty($user?->image))
        <p class="mt-2"><img class="preview" src="{{ asset('storage/'.$user->image) }}" alt="user image"></p>
    @endif
</div>
<div class="inline-actions" style="margin-top:16px;">
    <button class="btn" type="submit">{{ $isEdit ? 'Update User' : 'Create User' }}</button>
    <a class="btn" style="background:#6b7280;" href="{{ route('admin.users.index') }}">Cancel</a>
</div>
