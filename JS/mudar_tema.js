document.addEventListener('DOMContentLoaded', function() {
  const temaBotao = document.getElementById('btn_tema');
  const root = document.documentElement;

  // Verifica se há um tema salvo no localStorage
  const temaSalvo = localStorage.getItem('tema');

  if (temaSalvo) 
    {
      root.setAttribute('data-tema', temaSalvo);
  } else {
      root.setAttribute('data-tema', 'light'); // Tema padrão
  }

  // Atualiza o ícone do botão com base no tema atual
  const icon_claro = "<i class='fa-solid fa-sun' id='icon_tema'></i>";
  const icon_dark = "<i class='fa-solid fa-moon' id='icon_tema'></i>";
  temaBotao.innerHTML = root.getAttribute('data-tema') === 'dark' ? icon_dark : icon_claro;

  // Adiciona um evento de clique ao botão de tema
  temaBotao.addEventListener('click', function() 
  {
      const temaAtual = root.getAttribute('data-tema');
      if (temaAtual === 'dark') 
        {
          root.setAttribute('data-tema', 'light');
          temaBotao.innerHTML = icon_claro;
          localStorage.setItem('tema', 'light');
        } 
      else
        {
          root.setAttribute('data-tema', 'dark');
          temaBotao.innerHTML = icon_dark;
          localStorage.setItem('tema', 'dark');
        }
  });
});