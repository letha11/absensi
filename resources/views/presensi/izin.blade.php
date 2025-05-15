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
                                    <strong>{{ date("d-m-Y", strtotime($d->tgl_izin)) }}</strong><br>
                                    <small class="text-muted">{{ $d->status == 'i' ? 'Izin' : 'Sakit' }}</small>
                                    <br>
                                    <small class="text-muted" style="white-space: normal; word-wrap: break-word;">{{ $d->keterangan }}</small>
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
    <a href="/presensi/buatizin" class="fab">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div> 
@endsection