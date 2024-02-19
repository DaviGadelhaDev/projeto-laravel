let inputValor = document.getElementById('valor')

inputValor.addEventListener('input', function(){
    let value = this.value.replace(/[^\d]/g, '')
    var format = (value.slice(0, -2).replace(/\B(?=(\d{3}) + (?!\d))/g, '.')) + "" + value.slice(-2)
    format = format.slice(0, -2) + ',' + format.slice(-2)
    this.value = format
})