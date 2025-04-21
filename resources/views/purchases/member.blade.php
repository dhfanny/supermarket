@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-xl">
    <h2 class="text-xl font-bold mb-4">Konfirmasi Pembelian</h2>

    {{-- Ringkasan Produk --}}
    <div class="mb-6">
        <table class="w-full border text-sm text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-3 py-2">Produk</th>
                    <th class="border px-3 py-2">Qty</th>
                    <th class="border px-3 py-2">Harga</th>
                    <th class="border px-3 py-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['products'] as $item)
                <tr>
                    <td class="border px-3 py-2">{{ $item['nama_produk'] }}</td>
                    <td class="border px-3 py-2">{{ $item['qty'] }}</td>
                    <td class="border px-3 py-2">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                    <td class="border px-3 py-2">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="bg-gray-100 font-semibold">
                    <td colspan="3" class="border px-3 py-2 text-right">Total</td>
                    <td class="border px-3 py-2" id="total-price-cell">Rp {{ number_format($data['total_price'], 0, ',', '.') }}</td>
                </tr>
                <tr class="text-sm text-red-600">
                    <td colspan="3" class="border px-3 py-2 text-right">Diskon Poin</td>
                    <td class="border px-3 py-2" id="diskon-poin-cell">Rp 0</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Form Pembayaran --}}
    <form id="purchaseForm" action="{{ route('purchases.finish') }}" method="POST">
        @csrf
        <input type="hidden" name="diskon_poin" id="diskon_poin" value="0">

        {{-- Pilih Member --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">Apakah Pembeli Member?</label>
            <select name="is_member" id="is_member" class="w-full border rounded px-3 py-2">
                <option value="0">Bukan</option>
                <option value="1">Ya</option>
            </select>
        </div>

        {{-- Jika Member --}}
        <div id="member_fields" class="hidden">
            <div class="mb-4">
                <label class="block font-medium mb-1">Nomor Telepon</label>
                <input type="text" name="no_phone" id="no_phone" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1">Nama</label>
                <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <p id="member-points" class="text-sm text-gray-600 hidden">Poin Anda: <span id="points-amount">0</span></p>
            </div>
            <!-- Checkbox Gunakan Poin -->
<div class="mb-4" id="point_section" style="display: none;">
    <label class="inline-flex items-center">
        <input type="checkbox" name="use_points_checkbox" value="1" class="mr-2" id="use_points">
        <span>Gunakan poin untuk potongan</span>
    </label>
</div>

            <p id="point_info" class="text-sm text-gray-500 italic hidden">Poin belum tersedia karena ini transaksi pertama.</p>
        </div>

        {{-- Total Bayar --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">Total Pembayaran</label>
            <div class="flex items-center border rounded px-3 py-2">
                <span class="text-gray-600 mr-1">Rp</span>
                <input type="text" id="total_payment" name="total_payment" class="w-full outline-none" required>
            </div>
            <p id="reminder" class="text-sm text-red-500 mt-1 hidden">
                💡 Total pembayaran harus minimal Rp {{ number_format($data['total_price'], 0, ',', '.') }}
            </p>
        </div>

        {{-- Submit --}}
        <div class="text-center mt-6">
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Selesaikan Pembelian
            </button>
        </div>
    </form>
</div>

{{-- Script --}}
<script>
    const totalHarga = {{ $data['total_price'] }};
    let memberPoints = 0;
    let diskonPoin = 0;

    const form = document.getElementById('purchaseForm');
    const isMemberSelect = document.getElementById('is_member');
    const phoneInput = document.getElementById('no_phone');
    const nameInput = document.getElementById('name');
    const usePointsCheckbox = document.getElementById('use_points');
    const paymentInput = document.getElementById('total_payment');
    const reminder = document.getElementById('reminder');
    const memberFields = document.getElementById('member_fields');
    const memberPointsInfo = document.getElementById('member-points');
    const pointsAmount = document.getElementById('points-amount');
    const pointSection = document.getElementById('point_section');
    const pointInfo = document.getElementById('point_info');
    const totalPriceCell = document.getElementById('total-price-cell');
    const diskonPoinCell = document.getElementById('diskon-poin-cell');
    const diskonPoinInput = document.getElementById('diskon_poin');

    isMemberSelect.addEventListener('change', function () {
        if (this.value === '1') {
            memberFields.classList.remove('hidden');
            memberPointsInfo.classList.remove('hidden');
        } else {
            memberFields.classList.add('hidden');
            memberPointsInfo.classList.add('hidden');
            pointSection.style.display = 'none';
            pointInfo.classList.add('hidden');
        }

        // Reset input pembayaran dan diskon poin saat beralih status member
        paymentInput.value = '';
        usePointsCheckbox.checked = false;
        diskonPoin = 0;
        diskonPoinInput.value = 0;
        diskonPoinCell.innerText = 'Rp 0';
        totalPriceCell.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalHarga);
    });

    phoneInput.addEventListener('blur', fetchMemberData);
    phoneInput.addEventListener('change', fetchMemberData);

    function fetchMemberData() {
        const phone = phoneInput.value.trim();
        if (isMemberSelect.value !== '1') return;

        if (phone.length > 0) {
            fetch(`/check-member-history?no_phone=${phone}`)
                .then(res => res.json())
                .then(data => {
                    if (data.exists) {
                        memberPoints = data.points;
                        pointsAmount.innerText = memberPoints;
                        nameInput.value = data.name;

                        if (data.hasPurchases) {
                            pointSection.style.display = 'block';
                            pointInfo.classList.add('hidden');
                        } else {
                            pointSection.style.display = 'none';
                            pointInfo.classList.remove('hidden');
                        }
                    } else {
                        memberPoints = 0;
                        pointsAmount.innerText = '0';
                        nameInput.value = '';
                        pointSection.style.display = 'none';
                        pointInfo.classList.add('hidden');
                    }

                    // Reset input pembayaran dan diskon poin saat data member berubah
                    paymentInput.value = '';
                    usePointsCheckbox.checked = false;
                    diskonPoin = 0;
                    diskonPoinInput.value = 0;
                    diskonPoinCell.innerText = 'Rp 0';
                    totalPriceCell.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalHarga);
                })
                .catch(err => console.log('Error:', err));
        }
    }

    usePointsCheckbox.addEventListener('change', function () {
        if (this.checked && memberPoints > 0) {
            diskonPoin = Math.min(totalHarga, memberPoints);
            diskonPoinInput.value = diskonPoin;

            diskonPoinCell.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(diskonPoin);
            totalPriceCell.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalHarga - diskonPoin);

            // Jangan langsung isi input pembayaran
            paymentInput.value = '';
        } else {
            diskonPoin = 0;
            diskonPoinInput.value = 0;

            diskonPoinCell.innerText = 'Rp 0';
            totalPriceCell.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalHarga);

            // Jangan langsung isi input pembayaran
            paymentInput.value = '';
        }
    });

    paymentInput.addEventListener('input', function () {
        let value = this.value.replace(/\D/g, '');
        this.value = new Intl.NumberFormat('id-ID').format(value);

        let numeric = parseInt(value || 0);
        const minimum = totalHarga - diskonPoin;

        // Update reminder with correctly formatted total
        reminder.innerText = `💡 Total pembayaran harus minimal Rp ${new Intl.NumberFormat('id-ID').format(minimum)}`;

        reminder.classList.toggle('hidden', numeric >= minimum);
    });

    form.addEventListener('submit', function (e) {
        let cleanValue = paymentInput.value.replace(/\./g, '').replace('Rp', '').trim();
        let numeric = parseInt(cleanValue || 0);
        const minimum = totalHarga - diskonPoin;

        if (numeric < minimum) {
            e.preventDefault();
            reminder.classList.remove('hidden');
            paymentInput.focus();
            return false;
        }

        // Ubah format jadi angka sebelum submit
        paymentInput.value = cleanValue;
    });
</script>
@endsection
