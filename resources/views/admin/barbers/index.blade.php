@extends('layouts.admin')

@section('title', 'Master Barber')
@section('page_title', 'Manajemen Tim Barber & Meja')

@section('admin_content')
<div class="grid grid-cols-2" style="gap: 2rem; align-items: start;">
    <!-- Barbers List -->
    <div>
        <div style="margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: baseline;">
            <h3 style="font-size: 1.2rem;">Daftar Barber Studio</h3>
            <span class="badge badge-brass">{{ $barbers->count() }} Master Barber</span>
        </div>

        <div class="flex flex-col gap-3">
            @foreach($barbers as $barber)
                <div class="card" style="padding: 1.5rem; background: var(--surface);">
                    <div style="display: flex; gap: 1.25rem;">
                        <div style="width: 72px; height: 72px; border-radius: var(--radius-sm); overflow: hidden; flex-shrink: 0; border: 1px solid var(--border-light);">
                            <img src="{{ asset($barber->photo) }}" alt="{{ $barber->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                <div style="font-size: 1.1rem; font-weight: 700;">{{ $barber->name }}</div>
                                <span class="badge badge-muted">{{ $barber->experience_years }} Thn Pengalaman</span>
                            </div>

                            <div style="font-size: 0.82rem; color: var(--brass); font-weight: 600; margin: 0.2rem 0;">
                                {{ $barber->specialty }}
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.6rem; font-size: 0.76rem; color: var(--text-muted); flex-wrap: wrap;">
                                <span class="badge badge-brass" style="font-weight: 800;">MEJA {{ $barber->chair_code ?: 'A1' }}</span>
                                <span style="color: var(--border);">/</span>
                                @if($barber->is_maintenance)
                                    <span class="badge" style="background: rgba(217,119,6,0.2); color: #F59E0B; border: 1px solid rgba(217,119,6,0.4);">
                                        🔧 SEDANG DIPERBAIKI
                                    </span>
                                @else
                                    <span class="badge badge-success">
                                        ✓ MEJA SIAP PAKAI
                                    </span>
                                @endif
                                <span style="color: var(--border);">/</span>
                                <span>{{ $barber->bookings_count }} Janji Temu</span>
                            </div>

                            <p style="font-size: 0.82rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1rem;">
                                {{ $barber->bio }}
                            </p>

                            <!-- Maintenance Toggle / Edit / Delete -->
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center; flex-wrap: wrap;">
                                <form action="{{ route('admin.barbers.toggleMaintenance', $barber->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $barber->is_maintenance ? 'btn-brass' : 'btn-outline' }}" title="Ubah status perbaikan meja">
                                        {{ $barber->is_maintenance ? '✓ Selesai Perbaikan' : '🔧 Set Perbaikan Meja' }}
                                    </button>
                                </form>
                                <button type="button" class="btn btn-outline btn-sm" onclick="editBarber({{ json_encode($barber) }})">
                                    Edit
                                </button>
                                <form action="{{ route('admin.barbers.destroy', $barber->id) }}" method="POST" onsubmit="return confirm('Hapus master barber {{ $barber->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Create / Edit Form -->
    <div class="card" style="padding: 1.75rem; position: sticky; top: 88px;">
        <h3 id="formTitle" style="font-size: 1.2rem; margin-bottom: 1.25rem;">Tambah Master Barber</h3>

        <form id="barberForm" action="{{ route('admin.barbers.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="methodField" value="POST">

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Nama Lengkap Barber *</label>
                <input type="text" name="name" id="barberName" required placeholder="cth. Liam Sterling" style="width: 100%; height: 38px; padding: 0 0.75rem;">
            </div>

            <div class="grid grid-cols-2" style="gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Spesialisasi Potongan *</label>
                    <input type="text" name="specialty" id="barberSpecialty" required placeholder="cth. Classic Pompadour & Shave" style="width: 100%; height: 38px; padding: 0 0.75rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Pengalaman (Tahun) *</label>
                    <input type="number" name="experience_years" id="barberExp" required value="5" min="1" max="50" style="width: 100%; height: 38px; padding: 0 0.75rem;">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Path Foto</label>
                <input type="text" name="photo" id="barberPhoto" value="images/barbershop/barber-andre.jpg" style="width: 100%; height: 38px; padding: 0 0.75rem;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Biografi &amp; Pengalaman</label>
                <textarea name="bio" id="barberBio" rows="3" placeholder="Latar belakang keahlian dan pengalaman..." style="width: 100%; padding: 0.65rem 0.75rem;"></textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="barberActive" value="1" checked style="accent-color: var(--brass);">
                    <span>Barber sedang aktif dan siap menerima booking pelanggan</span>
                </label>
            </div>

            <div class="flex gap-2">
                <button type="submit" id="submitBtn" class="btn btn-brass flex-1">
                    Simpan Barber
                </button>
                <button type="button" id="resetBtn" class="btn btn-outline" onclick="resetForm()" style="display: none;">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function editBarber(b) {
    document.getElementById('formTitle').textContent = 'Edit Barber: ' + b.name;
    const form = document.getElementById('barberForm');
    form.action = '/admin/barbers/' + b.id;
    document.getElementById('methodField').value = 'PUT';

    document.getElementById('barberName').value = b.name;
    document.getElementById('barberSpecialty').value = b.specialty;
    document.getElementById('barberExp').value = b.experience_years;
    document.getElementById('barberPhoto').value = b.photo || '';
    document.getElementById('barberBio').value = b.bio || '';
    document.getElementById('barberActive').checked = !!b.is_active;

    document.getElementById('submitBtn').textContent = 'Simpan Perubahan';
    document.getElementById('resetBtn').style.display = 'inline-flex';
}

function resetForm() {
    document.getElementById('formTitle').textContent = 'Tambah Master Barber';
    const form = document.getElementById('barberForm');
    form.action = '{{ route("admin.barbers.store") }}';
    document.getElementById('methodField').value = 'POST';
    form.reset();
    document.getElementById('submitBtn').textContent = 'Simpan Barber';
    document.getElementById('resetBtn').style.display = 'none';
}
</script>
@endpush
@endsection
