import 'bootstrap';
import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrf) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;
}

function formatVnd(value) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', maximumFractionDigits: 0 }).format(value);
}

function initBookingPicker() {
    const root = document.querySelector('[data-booking-picker]');
    if (!root) return;

    const dateInput = root.querySelector('[data-booking-date]');
    const slotsContainer = root.querySelector('[data-slots]');
    const slotInput = root.querySelector('input[name="time_slot_id"]');
    const summaryDate = document.querySelector('[data-summary-date]');
    const summaryTime = document.querySelector('[data-summary-time]');
    const summaryPrice = document.querySelector('[data-summary-price]');
    const availabilityUrl = root.dataset.availabilityUrl;

    const selectSlot = (button) => {
        root.querySelectorAll('[data-slot]').forEach((item) => item.classList.remove('active'));
        button.classList.add('active');
        slotInput.value = button.dataset.slotId;
        summaryTime.textContent = button.dataset.label;
        summaryPrice.textContent = formatVnd(Number(button.dataset.price));
    };

    slotsContainer?.addEventListener('click', (event) => {
        const button = event.target.closest('[data-slot]');
        if (!button || button.disabled || button.classList.contains('disabled')) return;
        selectSlot(button);
    });

    dateInput?.addEventListener('change', async () => {
        summaryDate.textContent = new Date(`${dateInput.value}T00:00:00`).toLocaleDateString('vi-VN');
        slotInput.value = '';
        summaryTime.textContent = 'Chưa chọn';
        summaryPrice.textContent = '0 ₫';
        slotsContainer.innerHTML = '<div class="text-muted">Đang tải lịch trống...</div>';

        try {
            const response = await axios.get(availabilityUrl, { params: { booking_date: dateInput.value } });
            slotsContainer.innerHTML = response.data.data.map((slot) => `
                <button type="button" class="slot-chip ${slot.available ? '' : 'disabled'}" data-slot data-slot-id="${slot.id}" data-label="${slot.label}" data-price="${slot.price}" ${slot.available ? '' : 'disabled'}>
                    ${slot.label}<small class="d-block mt-1">${slot.available ? slot.formatted_price : 'Đã có người đặt'}</small>
                </button>
            `).join('');
        } catch (error) {
            slotsContainer.innerHTML = '<div class="alert alert-danger">Không thể tải lịch trống. Vui lòng thử lại.</div>';
        }
    });
}

function initConfirmForms() {
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const message = form.dataset.confirm || 'Bạn có chắc chắn muốn thực hiện thao tác này?';
            if (!window.confirm(message)) event.preventDefault();
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initBookingPicker();
    initConfirmForms();
});
