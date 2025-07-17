<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const inputPlaca = document.getElementById('placa');
    const inputTipo = document.getElementById('tipo');
    const inputModelo = document.getElementById('modelo');
    const inputCor = document.getElementById('cor');
    const inputMarca = document.getElementById('marca');
    const motoristaOficialContainer = document.getElementById('motorista-oficial-container');
    const motoristaNomeContainer = document.getElementById('motorista-nome-container');
    const motoristaOficialSelect = document.getElementById('motorista_id');
    const motoristaInput = document.getElementById('motorista_nome');
    const acessoHidden = document.getElementById('acesso_id_hidden');

    // Validação da placa (formato antigo ou Mercosul)
    function validarPlaca(placa) {
        const regex = /^[A-Z]{3}[0-9]{4}$|^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/;
        return regex.test(placa);
    }

    // Força maiúsculas e remove caracteres inválidos na placa
    inputPlaca.addEventListener('input', function () {
        let val = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 7);
        this.value = val;
    });

    // Força maiúsculas em modelo, cor e marca
    ['modelo', 'cor', 'marca'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', function () {
                this.value = this.value.toUpperCase();
            });
        }
    });

    // Mostra/esconde campos conforme o tipo
    function toggleCamposMotorista() {
        const tipo = inputTipo.value;

        if (tipo === 'OFICIAL') {
            motoristaOficialContainer.style.display = 'block';
            motoristaNomeContainer.style.display = 'none';
            motoristaInput.value = '';
            acessoHidden.value = '';
        } else if (tipo === 'PARTICULAR' || tipo === 'MOTO') {
            motoristaOficialContainer.style.display = 'none';
            motoristaNomeContainer.style.display = 'block';
            motoristaOficialSelect.value = '';
        } else {
            motoristaOficialContainer.style.display = 'none';
            motoristaNomeContainer.style.display = 'none';
            motoristaOficialSelect.value = '';
            motoristaInput.value = '';
            acessoHidden.value = '';
        }
    }

    inputTipo.addEventListener('change', toggleCamposMotorista);
    toggleCamposMotorista();

    // Autocomplete do nome do motorista (para PARTICULAR/MOTO)
    $('#motorista_nome').select2({
        placeholder: 'Digite o nome...',
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("acessos.buscar") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(item => ({
                        id: item.id,
                        text: item.nome + ' - ' + item.matricula
                    }))
                };
            },
            cache: true
        }
    });

    $('#motorista_nome').on('select2:select', function (e) {
        const selected = e.params.data;
        acessoHidden.value = selected.id;
    });

    // Validação da placa ao enviar o formulário
    form.addEventListener('submit', function (e) {
        const placa = inputPlaca.value.trim();
        if (!validarPlaca(placa)) {
            alert('Placa inválida! Use o formato ABC1234 (antigo) ou ABC1D23 (Mercosul).');
            inputPlaca.focus();
            e.preventDefault();
            return false;
        }
    });
});
</script>