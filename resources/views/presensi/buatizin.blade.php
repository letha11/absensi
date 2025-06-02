@extends('layouts.presensi')
@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Form Izin Kerja</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
<div class="row" style="margin-top:70px">
    <div class="col">
        <form method="POST" action="{{ route('karyawan.izin.store') }}" id="formIzin">
            @csrf
                    <div class="form-group">
                        <!-- Gunakan class 'datepicker' khusus Materialize -->
                        <input type="text" name="tgl_izin" class="datepicker" id="tgl_izin" placeholder="Tanggal">
                    </div>
            <div class="form-group">
                <select name="status" id="status" class="form-control">
                    <option value="">Izin / Sakit</option>
                    <option value="i">Izin</option>
                    <option value="s">Sakit</option>
                </select>
            </div>
            <div class="form-group">
                <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Keterangan"></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary w-100">Kirim</button>
            </div>
        </form>
    </div>
</div>
@endsection

<!-- CSS Materialize (letakkan di head) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">

<!-- JavaScript Materialize (letakkan sebelum closing body) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
@push('myscript')
<style>
    .datepicker-modal {
        max-height: 450px !important; /* Adjust as needed */
        overflow-y: auto !important;
    }

    /* Optional: Adjust the content within the modal if necessary */
    .datepicker-modal .modal-content {
        padding-bottom: 0 !important;
    }

    /* Ensure the calendar itself doesn't overflow if its content is too tall */
    .datepicker-calendar-container {
        max-height: 300px; /* Adjust based on your preferred calendar height */
        overflow-y: auto;
    }
</style>
<script>
    // Inisialisasi datepicker setelah DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        var datepicker = M.Datepicker.init(document.getElementById('tgl_izin'), {
            format: 'yyyy-mm-dd',
            autoClose: true,
            showClearBtn: true,
            i18n: {
                cancel: 'Batal',
                clear: 'Hapus',
                done: 'OK',
                months: [
                    'Januari', 'Februari', 'Maret', 'April',
                    'Mei', 'Juni', 'Juli', 'Agustus',
                    'September', 'Oktober', 'November', 'Desember'
                ],
                monthsShort: [
                    'Jan', 'Feb', 'Mar', 'Apr',
                    'Mei', 'Jun', 'Jul', 'Ags',
                    'Sep', 'Okt', 'Nov', 'Des'
                ],
                weekdays: [
                    'Minggu', 'Senin', 'Selasa', 'Rabu',
                    'Kamis', 'Jumat', 'Sabtu'
                ],
                weekdaysShort: [
                    'Min', 'Sen', 'Sel', 'Rab',
                    'Kam', 'Jum', 'Sab'
                ],
                weekdaysAbbrev: ['M','S','S','R','K','J','S']
            }
        });

        $("#formIzin").submit(function(){
            var tgl_izin = $("#tgl_izin").val();
            var status = $("#status").val();
            var keterangan = $("#keterangan").val();
            if (tgl_izin == "") {
                Swal.fire({
                        title: 'Isi Tanggal dulu Bro !',
                        text: 'Tanggal Harus Diisi',
                        icon: 'warning'
                    })
                return false;
            } else if (status == ""){
                Swal.fire({
                        title: 'Belum pilih status Bro !',
                        text: 'Status harus dipilih',
                        icon: 'warning'
                    })
                return false;
            } else if (keterangan == ""){
                Swal.fire({
                        title: 'Isi Keterangan juga Bro !',
                        text: 'Keterangan Perlu diisi',
                        icon: 'warning'
                    })
                return false;
            }
        });
    });
</script>
@endpush