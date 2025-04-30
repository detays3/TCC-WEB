
function exibirMensagemErro() {
    const urlParams = new URLSearchParams(window.location.search);
    const erro = urlParams.get('erro');
    const textoerro= document.getElementById('mostrar_erro');


    textoerro.style.display="none";

    if (erro) {

        textoerro.innerHTML=decodeURI(erro);
        textoerro.style.display="block";
    }

    else
    {
        textoerro.style.display="none";
    }
}

// Executa a função quando a página carrega
window.onload = exibirMensagemErro;