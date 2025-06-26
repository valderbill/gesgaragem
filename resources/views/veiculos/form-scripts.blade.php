<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form');
  const inputPlaca = document.getElementById('placa');
  const inputTipo = document.getElementById('tipo');
  const acessoGroup = document.getElementById('acesso-container');
  const acessoSelect = document.getElementById('acesso_id');

  if (!form || !inputPlaca || !inputTipo || !acessoSelect) return;

  // Função para validar placa (ABC1234 ou ABC1D23)
  function validarPlaca(placa) {
    const regex = /^[A-Z]{3}[0-9]{4}$|^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/;
    return regex.test(placa);
  }

  // Forçar maiúsculas e remover caracteres inválidos na placa durante digitação
  inputPlaca.addEventListener('input', function() {
    let val = this.value.toUpperCase();
    val = val.replace(/[^A-Z0-9]/g, '');
    val = val.substring(0, 7);
    this.value = val;
  });

  // Maiúsculas para modelo, cor e marca durante digitação
  ['modelo', 'cor', 'marca'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
      });
    }
  });

  // Mostrar ou esconder o campo "Acesso Liberado" com base no tipo
  function atualizarCampoMotorista() {
    const tipo = inputTipo.value;

    if (tipo === 'OFICIAL') {
      acessoGroup.style.display = 'block';
      acessoSelect.disabled = false;
    } else {
      acessoGroup.style.display = 'none';
      acessoSelect.disabled = true;
      acessoSelect.innerHTML = '<option value="">--</option>'; // Limpa opções se não for oficial
    }
  }

  inputTipo.addEventListener('change', atualizarCampoMotorista);
  atualizarCampoMotorista(); // chamada inicial

  // Buscar motorista automaticamente ao sair do campo de placa
  inputPlaca.addEventListener('blur', function() {
    const placa = inputPlaca.value.trim();
    const tipo = inputTipo.value;

    if (placa && (tipo === 'PARTICULAR' || tipo === 'MOTO')) {
      fetch(`/api/motorista-por-placa/${placa}`)
        .then(res => res.ok ? res.json() : null)
        .then(data => {
          if (data && data.id) {
            const option = new Option(`${data.nome} - ${data.matricula}`, data.id, true, true);
            acessoSelect.innerHTML = '';
            acessoSelect.appendChild(option);
          }
        })
        .catch(error => {
          console.error('Erro ao buscar motorista:', error);
        });
    }
  });

  // Validação da placa antes de enviar o formulário
  form.addEventListener('submit', function(e) {
    const placa = inputPlaca.value.trim();
    if (!validarPlaca(placa)) {
      alert('Placa inválida! Use o formato ABC1234 (antigo) ou ABC1D23 (Mercosul)');
      inputPlaca.focus();
      e.preventDefault();
      return false;
    }
  });
});
</script>
