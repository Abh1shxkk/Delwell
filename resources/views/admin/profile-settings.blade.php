@extends('admin.layout')
@section('title', 'Profile Settings')
@section('content')

<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-account-settings"></i>
    </span> Profile Settings
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span>Settings <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
      </li>
    </ul>
  </nav>
</div>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Profile Picture</h4>
        <form class="form-sample" method="POST" action="{{ route('admin.profile.picture.update') }}" enctype="multipart/form-data" id="profilePictureForm">
          @csrf
          @method('PUT')
          
          <div class="row">
            <div class="col-md-12">
              <div class="text-center mb-4">
                @php
                  $admin = Auth::guard('admin')->user();
                  $profileImage = asset('dist/images/faces/face1.jpg');
                  
                  if ($admin && $admin->profile_image) {
                    $profileImage = url('storage/' . $admin->profile_image);
                  }
                @endphp
                <img src="{{ $profileImage }}" 
                     alt="Profile Picture" 
                     class="rounded-circle" 
                     id="profilePreview"
                     style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #ddd;"
                     onerror="this.src='{{ asset('dist/images/faces/face1.jpg') }}'">
              </div>
              
              <div class="form-group">
                <div class="drop-zone" id="dropZone">
                  <i class="mdi mdi-cloud-upload" style="font-size: 48px; color: #999;"></i>
                  <p class="mb-2">Drag & Drop your image here</p>
                  <p class="text-muted mb-2">or</p>
                  <label for="profilePicture" class="btn btn-gradient-primary btn-sm">Browse Files</label>
                  <input type="file" 
                         class="form-control d-none @error('profile_image') is-invalid @enderror" 
                         id="profilePicture" 
                         name="profile_image" 
                         accept="image/*">
                  <p class="text-muted mt-2 mb-0" style="font-size: 12px;">Supported formats: JPG, PNG, GIF (Max: 2MB)</p>
                  @error('profile_image')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              
              <button type="submit" class="btn btn-gradient-primary me-2" id="uploadBtn" disabled>Upload Picture</button>
              @if(Auth::guard('admin')->user()->profile_image)
                <button type="button" class="btn btn-gradient-danger" id="removeBtn">Remove Picture</button>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Account Information</h4>
        <form class="form-sample" method="POST" action="{{ route('admin.profile.update') }}">
          @csrf
          @method('PUT')
          
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Username</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control @error('username') is-invalid @enderror" 
                         name="username" 
                         value="{{ old('username', Auth::guard('admin')->user()->username) }}" 
                         required>
                  @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-9">
                  <input type="email" class="form-control @error('email') is-invalid @enderror" 
                         name="email" 
                         value="{{ old('email', Auth::guard('admin')->user()->email) }}" 
                         required>
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">First Name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                         name="first_name" 
                         value="{{ old('first_name', Auth::guard('admin')->user()->first_name) }}">
                  @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Last Name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                         name="last_name" 
                         value="{{ old('last_name', Auth::guard('admin')->user()->last_name) }}">
                  @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-gradient-primary me-2">Update Profile</button>
          <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Change Password</h4>
        <form class="form-sample" method="POST" action="{{ route('admin.password.update') }}">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Current Password</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                         name="current_password" 
                         autocomplete="current-password"
                         required>
                  @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">New Password</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control @error('password') is-invalid @enderror" 
                         name="password" 
                         autocomplete="new-password"
                         required>
                  @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Confirm Password</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control" 
                         name="password_confirmation" 
                         autocomplete="new-password"
                         required>
                </div>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-gradient-primary me-2">Change Password</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('styles')
<style>
  .drop-zone {
    border: 2px dashed #ccc;
    border-radius: 8px;
    padding: 40px;
    text-align: center;
    transition: all 0.3s ease;
    background-color: #f8f9fa;
  }
  
  .drop-zone.drag-over {
    border-color: #007bff;
    background-color: #e7f3ff;
  }
  
  .drop-zone:hover {
    border-color: #999;
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('profilePicture');
    const profilePreview = document.getElementById('profilePreview');
    const uploadBtn = document.getElementById('uploadBtn');
    const removeBtn = document.getElementById('removeBtn');
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      dropZone.addEventListener(eventName, preventDefaults, false);
      document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }
    
    // Highlight drop zone when dragging over it
    ['dragenter', 'dragover'].forEach(eventName => {
      dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
      dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight(e) {
      dropZone.classList.add('drag-over');
    }
    
    function unhighlight(e) {
      dropZone.classList.remove('drag-over');
    }
    
    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
      const dt = e.dataTransfer;
      const files = dt.files;
      
      if (files.length > 0) {
        fileInput.files = files;
        handleFiles(files);
      }
    }
    
    // Handle file input change
    fileInput.addEventListener('change', function(e) {
      handleFiles(this.files);
    });
    
    function handleFiles(files) {
      if (files.length === 0) return;
      
      const file = files[0];
      
      // Validate file type
      if (!file.type.startsWith('image/')) {
        alert('Please upload an image file');
        return;
      }
      
      // Validate file size (2MB)
      if (file.size > 2 * 1024 * 1024) {
        alert('File size must be less than 2MB');
        return;
      }
      
      // Preview image
      const reader = new FileReader();
      reader.onload = function(e) {
        profilePreview.src = e.target.result;
        uploadBtn.disabled = false;
      };
      reader.readAsDataURL(file);
    }
    
    // Handle remove button
    if (removeBtn) {
      removeBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to remove your profile picture?')) {
          fetch('{{ route("admin.profile.picture.remove") }}', {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              profilePreview.src = '{{ asset("dist/images/faces/face1.jpg") }}';
              removeBtn.style.display = 'none';
              location.reload();
            } else {
              alert('Failed to remove profile picture');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
          });
        }
      });
    }
  });
</script>
@endpush
