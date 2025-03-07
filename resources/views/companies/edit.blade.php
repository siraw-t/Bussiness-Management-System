@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Company</h1>

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <form action="{{ route('companies.update', $company->company_id) }}" method="POST"
                    class="p-4 border rounded shadow-sm bg-light" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name"
                            value="{{ $company->company_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-select" id="country" name="country" required>
                            <option value="" disabled>Select a country</option>
                            @foreach ([
                                'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 
                                'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 
                                'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 
                                'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 
                                'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia', 
                                'Cameroon', 'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 
                                'Colombia', 'Comoros', 'Congo', 'Costa Rica', 'Croatia', 'Cuba', 
                                'Cyprus', 'Czech Republic', 'Democratic Republic of the Congo', 'Denmark', 
                                'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt', 
                                'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini', 
                                'Ethiopia', 'Fiji', 'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 
                                'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 
                                'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hungary', 'Iceland', 
                                'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 
                                'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 
                                'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 
                                'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Madagascar', 
                                'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 
                                'Mauritania', 'Mauritius', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 
                                'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 
                                'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 
                                'Nigeria', 'North Korea', 'North Macedonia', 'Norway', 'Oman', 'Pakistan', 
                                'Palau', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 
                                'Philippines', 'Poland', 'Portugal', 'Qatar', 'Romania', 'Russia', 
                                'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 
                                'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 
                                'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 
                                'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Korea', 
                                'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 
                                'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 
                                'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 
                                'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 
                                'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu', 
                                'Vatican City', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'
                            ] as $countryName)
                                <option value="{{ $countryName }}" {{ $countryName === $company->country ? 'selected' : '' }}>
                                    {{ $countryName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city"
                            value="{{ $company->city }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="contact_email" class="form-label">Contact Email</label>
                        <input type="email" class="form-control" id="contact_email" name="contact_email"
                            value="{{ $company->contact_email }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="contact_phone" class="form-label">Contact Phone</label>
                        <input type="text" class="form-control" id="contact_phone" name="contact_phone"
                            value="{{ $company->contact_phone }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label">Company Logo</label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                        @if ($company->logo)
                            <small>Current Logo: <img src="{{ asset('storage/' . $company->logo) }}" alt="Company Logo"
                                    style="width: 100px; height: auto;"></small>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Company</button>
                </form>
            </div>
        </div>
    </div>
@endsection