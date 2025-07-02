const fieldInstitucion = document.getElementById('billing_institucion_field');
const inputInstitucion = document.getElementById('billing_institucion');
const selectCategoria = document.getElementById('billing_categoria');

selectCategoria.addEventListener('change', (event) => {
  if (event.target.value === 'socio-convenio') {
    inputInstitucion.value = '';
    fieldInstitucion.classList.remove('d-none');
  } else {
    fieldInstitucion.classList.add('d-none');
    inputInstitucion.value = 'No procede';
  }
});

function formatCuitCuil(cuitCuil) {
  const inputBase = cuitCuil.replace(/[^0-9]/g, '');
  let formattedCuitCuil = inputBase;

  if (inputBase.length > 2) {
    formattedCuitCuil = `${inputBase.slice(0, 2)}-${inputBase.slice(2)}`;
  }

  if (inputBase.length > 10) {
    formattedCuitCuil = `${formattedCuitCuil.slice(0, 11)}-${inputBase.slice(10)}`;
  }

  return formattedCuitCuil.slice(0, 13);
}

const inputCuitCuil = document.getElementById('billing_cuit_cuil');

inputCuitCuil.addEventListener('input', (event) => {
  event.target.value = formatCuitCuil(event.target.value);
});


jQuery(function ($) {
  $('body').on('blur change', '#billing_cuit_cuil', function () {
    const wrapper = $(this).closest('.form-row');

    if (/[^0-9-]/g.test($(this).val()) || $(this).val().length < 13) {
      wrapper.addClass('woocommerce-invalid'); // error
    } else {
      wrapper.addClass('woocommerce-validated'); // success
    }
  });
});