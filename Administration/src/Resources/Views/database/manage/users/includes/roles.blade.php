@if($roles->count())
	<div class="form-group">
		<label class="col-form-label text-md-right">Tetapkan peran untuk user ini</label>
		@foreach ($roles as $role)
			<div class="custom-control custom-checkbox">
				<input class="custom-control-input autocheck" type="checkbox" id="roles-{{ $role->id }}" value="{{ $role->id }}" name="roles[]" @if($user->roles->firstWhere('id', $role->id)) checked @endif>
				<label class="custom-control-label" for="roles-{{ $role->id }}">{{ $role->display_name ?: $role->name }}</label>
			</div>
		@endforeach
		@if ($errors->has('roles.0')) 
			<div>
				<small class="text-danger"> {{ $errors->first('roles.0') }} </small>
			</div>
		@endif
	</div>
	<div class="form-group mb-0">
		<button class="btn btn-primary" type="submit">Simpan</button>
	</div>
@else
	<div>Tidak ada data peran</div>
@endif