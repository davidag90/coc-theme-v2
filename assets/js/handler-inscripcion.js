const selectVariableProd = document.getElementById('select-variable-prod');
const btnVariableProd = document.getElementById('btn-variable-prod');

selectVariableProd.addEventListener('change', (event) => {   
   let variationID = event.target.value;
   let btnVariableProdHREF = CHECKOUT_URL + variationID;

   btnVariableProd.setAttribute('href', btnVariableProdHREF);
   btnVariableProd.classList.remove('disabled');
});