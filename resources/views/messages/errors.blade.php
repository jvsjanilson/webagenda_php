@if ($errors->any() && $errors->first('error') != "")
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ $errors->first('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif
