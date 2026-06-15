document.addEventListener('DOMContentLoaded', function () {
    function onlyDigits(value) {
        return (value || '').replace(/\D+/g, '');
    }

    function formatPhone(value) {
        const digits = onlyDigits(value).slice(0, 11);
        if (!digits) return '';
        const ddd = digits.slice(0, 2);
        const rest = digits.slice(2);
        if (!rest) return `(${ddd}`;
        if (rest.length <= 4) return `(${ddd}) ${rest}`;
        if (rest.length <= 8) return `(${ddd}) ${rest.slice(0, 4)}-${rest.slice(4)}`;
        return `(${ddd}) ${rest.slice(0, 5)}-${rest.slice(5, 9)}`;
    }

    function formatCnpj(value) {
        const digits = onlyDigits(value).slice(0, 14);
        if (!digits) return '';
        const p1 = digits.slice(0, 2);
        const p2 = digits.slice(2, 5);
        const p3 = digits.slice(5, 8);
        const p4 = digits.slice(8, 12);
        const p5 = digits.slice(12, 14);
        let out = p1;
        if (digits.length > 2) out += `.${p2}`;
        if (digits.length > 5) out += `.${p3}`;
        if (digits.length > 8) out += `/${p4}`;
        if (digits.length > 12) out += `-${p5}`;
        return out;
    }

    function formatCurrency(value) {
        const digits = onlyDigits(value).slice(0, 12);
        if (!digits) return '';

        const amount = Number(digits) / 100;
        return amount.toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        });
    }

    document.querySelectorAll('[data-mask="phone"]').forEach(function (input) {
        input.addEventListener('input', function () {
            input.value = formatPhone(input.value);
        });
    });

    document.querySelectorAll('[data-mask="cnpj"]').forEach(function (input) {
        input.addEventListener('input', function () {
            input.value = formatCnpj(input.value);
        });
    });

    document.querySelectorAll('[data-mask="currency"]').forEach(function (input) {
        input.addEventListener('input', function () {
            input.value = formatCurrency(input.value);
        });
    });
});
