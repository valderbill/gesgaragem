<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form');
  const inputPlaca = document.getElementById('placa');

  if (!form || !inputPlaca) return;

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

  // Validação ao enviar o formulário
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
