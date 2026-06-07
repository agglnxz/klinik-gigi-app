@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        .pw {
            max-width: 680px;
            width: 100%;
            margin: 0 auto;
        }

        .ph {
            background: #2ba58d;
            border-radius: 12px 12px 0 0;
            padding: 2rem 2rem 2.5rem;
        }

        .av-wrap {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .av-wrap:hover .av-ov {
            opacity: 1;
        }

        .av-base {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.6);
            display: block;
            object-fit: cover;
        }

        .av-ov {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.45);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .2s;
            gap: 2px;
        }

        .av-ov i {
            font-size: 20px;
            color: #fff;
        }

        .av-ov span {
            font-size: 10px;
            color: rgba(255, 255, 255, .9);
        }

        .pb {
            background: #fff;
            border-radius: 0 0 12px 12px;
            border: .5px solid #e0e0e0;
            border-top: none;
            padding: 1.75rem 2rem 2rem;
        }

        .sec-title {
            font-size: 12px;
            font-weight: 600;
            color: #888;
            letter-spacing: .5px;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .fg {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem 1.25rem;
        }

        .fi {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .fi.full {
            grid-column: 1 / -1;
        }

        .fi label {
            font-size: 13px;
            color: #666;
        }

        .fi input {
            border: .5px solid #ccc;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 14px;
            color: #333;
            background: #fff;
            outline: none;
        }

        .divider {
            border: none;
            border-top: .5px solid #e0e0e0;
            margin: 1.5rem 0;
        }

        .badge-aktif {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e6f4ee;
            color: #1a7a4a;
            font-size: 14px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 8px;
        }

        .badge-aktif i {
            font-size: 24px;
            line-height: 1;
        }

        .toast {
            display: none;
            background: #2ba58d;
            color: #fff;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 13px;
            align-items: center;
            gap: 6px;
            margin-top: 1.25rem;
        }
    </style>

    <div class="pw">

        <div class="ph">
            <div style="display:flex;align-items:center;gap:1.25rem;">

                <form id="profileFotoForm" action="{{ route('profile.foto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <label for="profileFotoInput" class="av-wrap">
                        @if (Auth::user()->foto)
                            <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto Profil" class="av-base">
                        @else
                            <svg class="av-base" id="avSvg" viewBox="0 0 88 88">
                                <circle cx="44" cy="44" r="44" fill="#1a7a65" />
                                <circle cx="44" cy="34" r="16" fill="rgba(255,255,255,0.55)" />
                                <ellipse cx="44" cy="72" rx="26" ry="18"
                                    fill="rgba(255,255,255,0.35)" />
                            </svg>
                        @endif
                        <div class="av-ov">
                            <i class="ti ti-camera"></i>
                            <span>Ubah Foto</span>
                        </div>
                    </label>
                    <input id="profileFotoInput" type="file" name="foto" accept=".jpg,.jpeg,.png,.webp"
                        style="display:none;" onchange="this.form.submit()">
                </form>

                <div>
                    <p style="font-size:22px;font-weight:600;color:#fff;margin:0 0 4px;">
                        {{ Auth::user()->name }}
                    </p>

                    <p style="font-size:14px;color:rgba(255,255,255,0.8);margin:0;">
                        {{ Auth::user()->email }}
                    </p>
                </div>

            </div>
        </div>

        <div class="pb">
            @if (session('success'))
                <div class="toast" style="display:flex;background:#2ba58d;">{{ session('success') }}</div>
            @endif
            @if ($errors->has('foto'))
                <div class="toast" style="display:flex;background:#f44336;">{{ $errors->first('foto') }}</div>
            @endif

            <div class="divider"></div>

            <p class="sec-title">Informasi Akun</p>

            <div class="fg">

                <div class="fi">
                    <label>Nama Lengkap</label>
                    <input type="text" value="{{ Auth::user()->name }}" readonly>
                </div>

                <div class="fi">
                    <label>Email</label>
                    <input type="text" value="{{ Auth::user()->email }}" readonly>
                </div>

                <div class="fi">
                    <label>No. Telepon</label>
                    <input type="text" value="{{ data_get(Auth::user(), 'kontak', '-') }}" readonly>
                </div>

                <div class="fi">
                    <label>Role</label>
                    <input type="text" value="{{ Auth::user()->role }}" readonly>
                </div>

                <div class="fi full">
                    <label>Password</label>
                    <input type="password" value="********" readonly>
                </div>

            </div>

            <div class="divider"></div>

            <p class="sec-title">Status Akun</p>

            <div style="display:flex;align-items:center;gap:10px;">
                <span class="badge-aktif">
                    <i class="ti ti-circle-check"></i>
                    {{ Auth::user()->is_aktif ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>

        </div>

    </div>
@endsection
