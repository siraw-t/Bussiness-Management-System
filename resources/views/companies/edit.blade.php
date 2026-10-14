@extends('layouts.app')

@section('content')
<style>
    .form-page-head {
        text-align: center;
        margin-bottom: 26px;
    }
    .form-page-head h1 {
        font-size: 1.7rem;
        font-weight: 800;
        margin: 0 0 6px;
        color: var(--text-primary);
    }
    .form-page-head p { color: var(--text-secondary); font-size: 0.9rem; margin: 0; }

    .form-card {
        max-width: 780px;
        margin: 0 auto;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .form-section {
        padding: 26px 28px;
        border-bottom: 1px solid var(--border-color);
    }
    .form-section:last-of-type { border-bottom: none; }
    .form-section h5 {
        font-size: 0.95rem;
        font-weight: 700;
        margin: 0 0 18px;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-section h5 .step-num {
        width: 26px; height: 26px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .logo-preview {
        width: 80px; height: 80px;
        border-radius: 16px;
        object-fit: cover;
        border: 2px solid var(--border-color);
        margin-top: 10px;
    }

    .form-actions {
        padding: 22px 28px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    [data-bs-theme="dark"] .form-actions { background: #0b1120; }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 26px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff;
        border: none;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        transition: var(--transition);
    }
    .btn-submit:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 26px rgba(99,102,241,0.45); }

    @media (max-width: 576px) {
        .form-section { padding: 20px 18px; }
        .form-actions { padding: 18px; flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; }
    }
</style>

<div class="form-page-head">
    <h1>Edit Company</h1>
    <p>Update company information for <strong>{{ $company->company_name }}</strong></p>
</div>

<form action="{{ route('companies.update', $company->company_id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-card">
        <div class="form-section">
            <h5><span class="step-num">1</span> Company Information</h5>

            <div class="mb-3">
                <label for="company_name" class="form-label">Company Name</label>
                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $company->company_name }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="country" class="form-label">Country</label>
                    <select class="form-select" id="country" name="country" required>
                        <option value="" disabled>Select a country</option>
                        @foreach ([
                            'Afghanistan','Albania','Algeria','Andorra','Angola','Antigua and Barbuda','Argentina','Armenia','Australia','Austria','Azerbaijan','Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil','Brunei','Bulgaria','Burkina Faso','Burundi','Cabo Verde','Cambodia','Cameroon','Canada','Central African Republic','Chad','Chile','China','Colombia','Comoros','Congo','Costa Rica','Croatia','Cuba','Cyprus','Czech Republic','Democratic Republic of the Congo','Denmark','Djibouti','Dominica','Dominican Republic','Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Eswatini','Ethiopia','Fiji','Finland','France','Gabon','Gambia','Georgia','Germany','Ghana','Greece','Grenada','Guatemala','Guinea','Guinea-Bissau','Guyana','Haiti','Honduras','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Israel','Italy','Jamaica','Japan','Jordan','Kazakhstan','Kenya','Kiribati','Kuwait','Kyrgyzstan','Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein','Lithuania','Luxembourg','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Mauritania','Mauritius','Mexico','Micronesia','Moldova','Monaco','Mongolia','Montenegro','Morocco','Mozambique','Myanmar','Namibia','Nauru','Nepal','Netherlands','New Zealand','Nicaragua','Niger','Nigeria','North Korea','North Macedonia','Norway','Oman','Pakistan','Palau','Palestine','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Poland','Portugal','Qatar','Romania','Russia','Rwanda','Saint Kitts and Nevis','Saint Lucia','Saint Vincent and the Grenadines','Samoa','San Marino','Sao Tome and Principe','Saudi Arabia','Senegal','Serbia','Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','South Korea','South Sudan','Spain','Sri Lanka','Sudan','Suriname','Sweden','Switzerland','Syria','Taiwan','Tajikistan','Tanzania','Thailand','Togo','Tonga','Trinidad and Tobago','Tunisia','Turkey','Turkmenistan','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','Uruguay','Uzbekistan','Vanuatu','Vatican City','Venezuela','Vietnam','Yemen','Zambia','Zimbabwe'
                        ] as $countryName)
                            <option value="{{ $countryName }}" {{ $countryName === $company->country ? 'selected' : '' }}>{{ $countryName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{ $company->city }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contact_email" class="form-label">Contact Email</label>
                    <input type="email" class="form-control" id="contact_email" name="contact_email" value="{{ $company->contact_email }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="contact_phone" class="form-label">Contact Phone</label>
                    <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="{{ $company->contact_phone }}" required>
                </div>
            </div>

            <div class="mb-0">
                <label for="logo" class="form-label">Company Logo</label>
                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                @if ($company->logo)
                    <div>
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="Company Logo" class="logo-preview">
                        <div class="text-muted small mt-1">Current logo</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('companies.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Update Company
            </button>
        </div>
    </div>
</form>
@endsection