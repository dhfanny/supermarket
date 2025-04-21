@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-xl">
    <h2 class="text-xl font-bold mb-4">Konfirmasi Pembelian</h2>

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

    <form id="purchaseForm" action="{{ route('purchases.finish') }}" method="POST">
        @csrf
        <input type="hidden" name="diskon_poin" id="diskon_poin" value="0">

        <div class="mb-4">
            <label class="block font-medium mb-1">Apakah Pembeli Member?</label>
            <select name="is_member" id="is_member" class="w-full border rounded px-3 py-2">
                <option value="0">Bukan</option>
                <option value="1">Ya</option>
            </select>
        </div>

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
            <div class="mb-4" id="point_section" style="display: none;">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="use_points_checkbox" value="1" class="mr-2" id="use_points">
                    <span>Gunakan poin untuk potongan</span>
                </label>
            </div>
            <p id="point_info" class="text-sm text-gray-500 italic hidden">Poin belum tersedia karena ini transaksi pertama.</p>
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Total Pembayaran</label>
            <div class="flex items-center border rounded px-3 py-2">
                <span class="text-gray-600 mr-1">Rp</span>
                <input type="text" id="total_payment" name="total_payment" class="w-full outline-none" required>
            </div>
            <p id="reminder" class="text-sm text-red-500 mt-1 hidden">
                💡Total pembayaran harus minimal <span id="payment-minimum-info"></span>
            </p>
        </div>

        <div class="text-center mt-6">
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Selesaikan Pembelian
            </button>
        </div>
    </form>
</div>

<script>
    const totalHarga = {{ $data['total_price'] }};
    let memberPoints = 0;
    let diskonPoin = 0;
    let minimumPayment = totalHarga;

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
    const paymentMinimumInfo = document.getElementById('payment-minimum-info');

    function resetFormValues() {
        paymentInput.value = '';
        usePointsCheckbox.checked = false;
        diskonPoin = 0;
        diskonPoinInput.value = 0;
        diskonPoinCell.innerText = 'Rp 0';
        totalPriceCell.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalHarga);
        minimumPayment = totalHarga;
        updateMinimumPayment();
    }

    function updateMinimumPayment() {
        const cleanValue = paymentInput.value.replace(/\D/g, '');
        const numeric = parseInt(cleanValue || 0);
        reminder.classList.toggle('hidden', numeric >= minimumPayment);
        paymentMinimumInfo.innerText = new Intl.NumberFormat('id-ID').format(minimumPayment);
    }

    function fetchMemberData() {
    const phone = phoneInput.value.trim();
    if (isMemberSelect.value !== '1' || !phone.length) return;

    fetch(`/check-member-history?no_phone=${phone}`)
        .then(res => res.json())
        .then(data => {
            if (data.exists) {
                nameInput.value = data.name;
                nameInput.setAttribute('readonly', true); // Menambahkan readonly jika nama ditemukan
                memberPoints = data.points;
                const pointsToBeEarned = Math.floor(totalHarga * 0.1);
                const totalPoints = memberPoints + pointsToBeEarned;

                pointsAmount.innerText = `${totalPoints} poin (termasuk ${pointsToBeEarned} poin dari transaksi ini)`;
                memberPointsInfo.classList.remove('hidden');

                if (data.hasPurchases) {
                    pointSection.style.display = 'block';
                    pointInfo.classList.add('hidden');
                } else {
                    pointSection.style.display = 'none';
                    pointInfo.classList.remove('hidden');
                }
            } else {
                nameInput.value = '';
                nameInput.removeAttribute('readonly'); // Menghapus readonly jika nama tidak ditemukan
                memberPoints = 0;
                pointsAmount.innerText = '0 poin';
                pointSection.style.display = 'none';
                pointInfo.classList.add('hidden');
                memberPointsInfo.classList.remove('hidden');
            }

            resetFormValues();
        })
        .catch(err => console.error('Fetch error:', err));
}


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

        resetFormValues();
    });

    phoneInput.addEventListener('blur', fetchMemberData);
    phoneInput.addEventListener('change', fetchMemberData);

    usePointsCheckbox.addEventListener('change', function () {
        const pointsToBeEarned = Math.floor(totalHarga * 0.1);
        const totalPoints = memberPoints + pointsToBeEarned;

        if (this.checked && totalPoints > 0) {
            diskonPoin = Math.min(totalHarga, totalPoints);
            diskonPoinInput.value = diskonPoin;
            diskonPoinCell.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(diskonPoin);
            totalPriceCell.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalHarga - diskonPoin);
            minimumPayment = totalHarga - diskonPoin;
        } else {
            diskonPoin = 0;
            diskonPoinInput.value = 0;
            diskonPoinCell.innerText = 'Rp 0';
            totalPriceCell.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalHarga);
            minimumPayment = totalHarga;
        }

        updateMinimumPayment();
    });

    paymentInput.addEventListener('input', function () {
        const clean = this.value.replace(/\D/g, '');
        this.value = new Intl.NumberFormat('id-ID').format(clean);
        updateMinimumPayment();
    });

    form.addEventListener('submit', function (e) {
        const clean = paymentInput.value.replace(/\D/g, '');
        const numeric = parseInt(clean || 0);

        if (numeric < minimumPayment) {
            e.preventDefault();
            reminder.classList.remove('hidden');
            paymentInput.focus();
        } else {
            paymentInput.value = clean;
        }
    });
</script>
@endsection
