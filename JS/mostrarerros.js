const urlParams = new URLSearchParams(window.location.search);
const erros = urlParams.get('erros');

if (erros)
     {
    const container = document.getElementById('erros-container');
    // Substitui "|" por quebras de linha
    const mensagens = erros.replace(/\|/g, '<br>');
    container.innerHTML = `<div class="erro-validacao">${mensagens}</div>`;
    
    // Limpa a URL sem recarregar
    history.replaceState({}, '', window.location.pathname);
}