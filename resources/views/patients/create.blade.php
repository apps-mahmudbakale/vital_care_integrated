@extends('layouts.app')

@section('title', 'Add New Patient')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Patients /</span> Add New</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Patient Registration</h5>
                <hr class="my-0">
                <div class="card-body">
                    <form action="{{ route('app.patients.store') }}" method="POST">
                        @csrf
                      <input type="hidden" name="temp_id" value="{{$data->id}}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="firstname">First Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="firstname" value="{{ old('firstname') ?? $data->first_name }}" id="firstname"
                                    class="form-control @error('firstname') is-invalid @enderror" placeholder="First Name">
                                @if ($errors->has('firstname'))
                                    <div class="text-danger">
                                        {{ $errors->first('firstname') }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="middlename">Middle Name</label>
                                <input type="text" name="middlename" value="{{ old('middlename') ?? $data->middle_name }}" id="middlename"
                                    class="form-control" placeholder="Midddle Name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="lastname">Last Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="lastname" value="{{ old('lastname') ??  $data->last_name }}" id="lastname"
                                    class="form-control  @error('lastname') is-invalid @enderror" placeholder="Last Name">
                                @if ($errors->has('lastname'))
                                    <div class="text-danger">
                                        {{ $errors->first('lastname') }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">

                                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email') ?? $data->email }}" id="email"
                                    class="form-control  @error('email') is-invalid @enderror" placeholder="Email">
                                @if ($errors->has('email'))
                                    <div class="text-danger">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="phone">Phone <span class="text-danger">*</span></label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') ?? $data->phone }}"
                                    class="form-control @error('phone') is-invalid @enderror" placeholder="Phone">
                                @if ($errors->has('phone'))
                                    <div class="text-danger">
                                        {{ $errors->first('phone') }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="gender">Gender <span class="text-danger">*</span></label>
                                <select name="gender" id="gender"
                                    class="form-control @error('gender') is-invalid @enderror">
                                  <option value="{{$data->gender}}" selected>{{$data->gender ?: 'Select Gender...'}}</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                @if ($errors->has('gender'))
                                    <div class="text-danger">
                                        {{ $errors->first('gender') }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label for="date_of_birth" class="form-label"> Date of Birth <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') ?? $data->date_of_birth }}"
                                    class="form-control @error('date_of_birth') is-invalid @enderror">
                                @if ($errors->has('date_of_birth'))
                                    <div class="text-danger">
                                        {{ $errors->first('date_of_birth') }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="religion_id">Religion <span
                                        class="text-danger">*</span></label>
                                <select name="religion_id" id="religion_id"
                                    class="form-control @error('religion_id') is-invalid @enderror">
                                    <option value="" selected>Select Religion...</option>
                                    @foreach ($religions as $religion)
                                        <option value="{{ $religion->id }}" {{ (old('religion_id') ?? $data->religion_id) == $religion->id ? 'selected' : '' }}>{{ $religion->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('religion_id'))
                                    <div class="text-danger">
                                        {{ $errors->first('religion_id') }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="marital_status">Marital Status <span
                                        class="text-danger">*</span></label>
                                <select name="marital_status" id="marital_status"
                                    class="form-control @error('marital_status') is-invalid @enderror">
                                    <option value="" selected>Select Marital Status...</option>
                                    <option value="Single" {{ (old('marital_status') ?? $data->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ (old('marital_status') ?? $data->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" {{ (old('marital_status') ?? $data->marital_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                </select>
                                @if ($errors->has('marital_status'))
                                    <div class="text-danger">
                                        {{ $errors->first('marital_status') }}
                                    </div>
                                @endif
                            </div>
                          <div class="col-md-4">
                            <label class="form-label" for="tribe">Tribe <span
                                class="text-danger">*</span></label>
                            <select name="tribe" id="tribe"
                                    class="form-control @error('tribe') is-invalid @enderror">
                              <option value="" selected>Select Tribe...</option>
                              <option value="Hausa">Hausa</option>
                              <option value="Igbo">Igbo</option>
                              <option value="Yoruba">Yoruba</option>
                              <option value="Ijaw">Ijaw</option>
                              <option value="Kanuri">Kanuri</option>
                              <option value="Ibibio">Ibibio</option>
                              <option value="Fulani">Fulani</option>
                              <option value="Tiv">Tiv</option>
                              <option value="Others">Others</option>
                            </select>
                            @if ($errors->has('tribe'))
                            <div class="text-danger">
                              {{ $errors->first('tribe') }}
                            </div>
                            @endif
                          </div>
                          <div class="col-md-4">
                            <label for="disability" class="form-label">Disability<span
                                class="text-danger">*</span></label>
                            <input name="disability" id="disability" value="{{ old('disability') }}"
                                   class="form-control @error('disability') is-invalid @enderror">
                          </div>
                            <div class="col-md-4">
                                <label for="occupation" class="form-label">Occupation <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="occupation" value="{{ old('occupation') ?? $data->occupation }}"
                                    class="form-control @error('occupation') is-invalid @enderror"
                                    placeholder="Occupation">
                                @error('occupation')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="state_r" class="form-label">State of Residence<span
                                        class="text-danger">*</span></label>
                                <select name="state_of_residence" id="state_r"
                                    class="form-control @error('state_of_residence') is-invalid @enderror">
                                    <option value="" selected>Select State...</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state }}" {{ (old('state_of_residence') ?? $data->state_of_residence) == $state ? 'selected' : '' }}>{{ $state }}</option>
                                    @endforeach
                                </select>
                                @error('state_of_residence')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="lga_r" class="form-label">LGA<span class="text-danger">*</span></label>
                                <input type="text" name="lga_of_residence" id="lga_r" value="{{ old('lga_of_residence') ?? $data->lga_of_residence }}"
                                    class="form-control @error('lga_of_residence') is-invalid @enderror">
                                @error('lga_of_residence')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="residential_address" class="form-label">Residential Address <span
                                        class="text-danger">*</span></label>
                                <input name="residential_address" id="residential_address" value="{{ old('residential_address') ?? $data->residential_address }}"
                                    class="form-control @error('residential_address') is-invalid @enderror">
                                @error('residential_address')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="next_of_kin_name">Next of Kin's Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="next_of_kin_name" id="next_of_kin_name" value="{{ old('next_of_kin_name') }}"
                                    class="form-control @error('next_of_kin_name') is-invalid @enderror">
                                @if ($errors->has('next_of_kin_name'))
                                    <div class="text-danger">
                                        {{ $errors->first('next_of_kin_name') }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="next_of_kin_relationship">Next of Kin's Relationship <span
                                        class="text-danger">*</span></label>
                                <select name="next_of_kin_relationship" id="next_of_kin_relationship"
                                    class="form-control @error('next_of_kin_relationship') is-invalid @enderror">
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Husband">Husband</option>
                                    <option value="Wife">Wife</option>
                                    <option value="Son">Son</option>
                                    <option value="Daughter">Daughter</option>
                                    <option value="Brother">Brother</option>
                                    <option value="Sister">Sister</option>
                                    <option value="Other">Other</option>
                                </select>
                                @if ($errors->has('next_of_kin_relationship'))
                                    <div class="text-danger">
                                        {{ $errors->first('next_of_kin_relationship') }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label for="next_of_kin_phone" class="form-label">Next of Kin's Phone<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="next_of_kin_phone" id="next_of_kin_phone" value="{{ old('next_of_kin_phone') }}"
                                    class="form-control @error('next_of_kin_phone') is-invalid @enderror">
                                @if ($errors->has('next_of_kin_phone'))
                                    <div class="text-danger">
                                        {{ $errors->first('next_of_kin_phone') }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label for="next_of_kin_address" class="form-label">Next of Kin's Address <span
                                        class="text-danger">*</span></label>
                                <input name="next_of_kin_address" id="next_of_kin_address" value="{{ old('next_of_kin_address') }}"
                                    class="form-control @error('next_of_kin_address') is-invalid @enderror">
                                @if ($errors->has('next_of_kin_address'))
                                    <div class="text-danger">
                                        {{ $errors->first('next_of_kin_address') }}
                                    </div>
                                @endif
                            </div>
                            @if (app(App\Settings\SystemSettings::class)->insurance_billers)
                                <div class="col-md-4">

                                    <label class="form-label" for="hmo_id">HMO Plan </label>
                                    <select name="hmo_id" id="hmo_id" class="form-control">
                                        <option value="" selected>Select HMO Plan...</option>
                                        @foreach ($hmos as $hmo)
                                            <option value="{{ $hmo->id }}">{{ $hmo->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">

                                    <label class="form-label" for="dependent">Dependent? </label>
                                    <select name="dependent" id="dependent" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No" selected>No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">

                                    <label class="form-label" for="principal_id">Principal ID</label>
                                    <input type="text" name="principal_id" id="principal_id" class="form-control"
                                        placeholder="HMO Principal ID">
                                </div>
                            @endif
                        </div>
                        <div class="pt-4">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1">Submit</button>
                            <a href="{{ route('app.patients.index') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
