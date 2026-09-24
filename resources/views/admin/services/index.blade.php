@extends('layouts.admin')

@section('title', 'Layanan & Harga')
@section('page_title', 'Paket Grooming & Layanan')

@section('admin_content')
<div class="grid grid-cols-2" style="gap: 2rem; align-items: start;">
    <!-- Services List -->
    <div>
        <div style="margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: baseline;">
            <h3 style="font-size: 1.2rem;">Katalog Layanan Aktif</h3>
            <span class="badge badge-brass">{{ $services->count() }} Layanan</span>
        </div>

        <div class="flex flex-col gap-3">
            @foreach($services as $srv)
                <div class="card" style="padding: 1.5rem; background: var(--surface);">
                    <div style="display: flex; gap: 1.25rem;">
                        <div style="width: 72px; height: 72px; border-radius: var(--radius-sm); overflow: hidden; flex-shrink: 0; border: 1px solid var(--border);">
                            <img src="{{ asset($srv->photo) }}" alt="{{ $srv->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                <div style="font-size: 1.1rem; font-weight: 700;">{{ $srv->name }}</div>
                                <div style="font-family: var(--font-serif); font-size: 1.2rem; color: var(--brass); font-weight: 600;">
                                    {{ $srv->formatted_price }}
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.6rem; margin: 0.3rem 0; font-size: 0.78rem; color: var(--text-muted);">
                                <span>{{ $srv->duration_minutes }} Menit</span>
                                <span style="color: var(--border);">/</span>
                                <span>{{ $srv->booking_items_count }} kali dipesan</span>
                                @if($srv->badge)
                                    <span style="color: var(--border);">/</span>
                                    <span class="badge badge-brass" style="font-size: 0.65rem;">{{ $srv->badge }}</span>
                                @endif
                                <span style="color: var(--border);">/</span>
                                <span class="badge {{ $srv->is_active ? 'badge-success' : 'badge-muted' }}" style="font-size: 0.65rem;">
                                    {{ $srv->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                </span>
                            </div>

                            <p style="font-size: 0.82rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1rem;">
                                {{ $srv->description }}
                            </p>

                            <!-- Edit Trigger / Delete Form -->
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <button type="button" class="btn btn-outline btn-sm" onclick="editService({{ json_encode($srv) }})">
                                    Edit Layanan
                                </button>
                                <form action="{{ route('admin.services.destroy', $srv->id) }}" method="POST" onsubmit="return confirm('Hapus layanan {{ $srv->name }}?');">
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
        <h3 id="formTitle" style="font-size: 1.2rem; margin-bottom: 1.25rem;">Tambah Paket Layanan Baru</h3>

        <form id="serviceForm" action="{{ route('admin.services.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="methodField" value="POST">

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Nama Layanan *</label>
                <input type="text" name="name" id="serviceName" required placeholder="cth. Signature Haircut & Wash" style="width: 100%; height: 38px; padding: 0 0.75rem;">
            </div>

            <div class="grid grid-cols-2" style="gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Harga (Rp) *</label>
                    <input type="number" name="price" id="servicePrice" required placeholder="180000" style="width: 100%; height: 38px; padding: 0 0.75rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Durasi (Menit) *</label>
                    <input type="number" name="duration_minutes" id="serviceDuration" required value="45" style="width: 100%; height: 38px; padding: 0 0.75rem;">
                </div>
            </div>

            <div class="grid grid-cols-2" style="gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Label / Badge (Opsional)</label>
                    <input type="text" name="badge" id="serviceBadge" placeholder="cth. Signature, Populer" style="width: 100%; height: 38px; padding: 0 0.75rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Path Foto</label>
                    <input type="text" name="photo" id="servicePhoto" value="images/barbershop/service-haircut.jpg" style="width: 100%; height: 38px; padding: 0 0.75rem;">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Deskripsi Layanan</label>
                <textarea name="description" id="serviceDesc" rows="3" placeholder="Detail lengkap fasilitas dan layanan..." style="width: 100%; padding: 0.65rem 0.75rem;"></textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="serviceActive" value="1" checked style="accent-color: var(--brass);">
                    <span>Layanan aktif dan dapat dipesan pelanggan secara online</span>
                </label>
            </div>

            <div class="flex gap-2">
                <button type="submit" id="submitBtn" class="btn btn-brass flex-1">
                    Simpan Layanan
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
function editService(srv) {
    document.getElementById('formTitle').textContent = 'Edit Layanan: ' + srv.name;
    const form = document.getElementById('serviceForm');
    form.action = '/admin/services/' + srv.id;
    document.getElementById('methodField').value = 'PUT';

    document.getElementById('serviceName').value = srv.name;
    document.getElementById('servicePrice').value = srv.price;
    document.getElementById('serviceDuration').value = srv.duration_minutes;
    document.getElementById('serviceBadge').value = srv.badge || '';
    document.getElementById('servicePhoto').value = srv.photo || '';
    document.getElementById('serviceDesc').value = srv.description || '';
    document.getElementById('serviceActive').checked = !!srv.is_active;

    document.getElementById('submitBtn').textContent = 'Simpan Perubahan';
    document.getElementById('resetBtn').style.display = 'inline-flex';
}

function resetForm() {
    document.getElementById('formTitle').textContent = 'Tambah Paket Layanan Baru';
    const form = document.getElementById('serviceForm');
    form.action = '{{ route("admin.services.store") }}';
    document.getElementById('methodField').value = 'POST';
    form.reset();
    document.getElementById('submitBtn').textContent = 'Simpan Layanan';
    document.getElementById('resetBtn').style.display = 'none';
}
</script>
@endpush
@endsection
