@extends('layouts.presensi')
@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Data Izin / Sakit</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
<style>
    .listview {
        background-color: transparent;
    border-top: none;
    }
    .listview > li::after {
        display: none;
    }
    .listview.image-listview > li {
        margin-bottom: 10px !important; /* Adjust as needed */
        border: 1px solid #e0e0e0; Optional: adds a light border like in the image
        border-radius: 5px; /* Optional: rounds the corners */
    }
    .listview.image-listview > li .item{
        padding: 10px; /* Optional: adds some padding inside the item */
        background-color: #fff;
    } 
</style>
<div class="row" style="margin-top:70px">
    <div class="col">
          @php
            $messagesuccess = Session::get('success');
            $messageerror = Session::get('error');
        @endphp
        @if (Session::get('success'))
        <div class="alert alert-success">
            {{ $messagesuccess }}
        </div>
        @endif
        @if (Session::get('error'))
        <div class="alert alert-error">
            {{ $messagesuccess }}
        </div>
        @endif
    </div>
</div>
<div class="row" style="margin-top: 1rem;">
    <div class="col">
        @if ($dataizin->isEmpty())
            <div class="alert alert-outline-info">
                Tidak ada data izin/sakit.
            </div>
        @else
            <ul class="listview image-listview">
                @foreach ($dataizin as $d)
                    <li>
                        <div class="item">
                            <div class="in">
                                <div>
                                    <strong>{{ $d->status == 'i' ? 'Izin' : 'Sakit' }}</strong><br>
                                    <small class="text-muted">{{ $d->keterangan }}</small>
                                    <br>
                                    <small class="text-muted" style="white-space: normal; word-wrap: break-word;">{{ date("d-m-Y", strtotime($d->tgl_izin)) }}</small>
                                </div>
                                @php
                                    $statusText = '';
                                    $statusClass = '';
                                    switch ($d->status_approved) {
                                        case 'a':
                                            $statusText = 'Approved';
                                            $statusClass = 'success';
                                            break;
                                        case 'd':
                                            $statusText = 'Declined';
                                            $statusClass = 'danger';
                                            break;
                                        default:
                                            $statusText = 'Waiting';
                                            $statusClass = 'warning';
                                            break;
                                    }
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
<div class="fab-button bottom-right" style="margin-bottom:70px">
    <a href="{{ route('karyawan.izin.create') }}" class="fab">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div> 
@endsection