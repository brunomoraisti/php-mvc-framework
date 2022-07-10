/**
 * RECUPERAÇÃO DE SENHA
 */
function recuperasenha() {

    var online = navigator.onLine;
    if (online) {
        validaForm()

        if (document.querySelector("#cpf").checkValidity() === true) {
            clickBotaoProgressAtivo("btnEsqueciSenhaEnviar");

            var cpf = document.getElementById("cpf").value;

            $.cardProgress('#cardEsqueciSenha');
            $.ajax({
                type: "POST",
                url: "/api/recuperasenha",
                data: "cpf=" + cpf,
                dataType: "json"
            }).done(function (retorno) {
                clickBotaoProgressInativo("btnEsqueciSenhaEnviar","Entrar");
                if (retorno.error) {
                    iziToast.error({title: 'Erro!', message: retorno.msg, position: 'bottomRight'});
                    document.getElementById("cpf").value = "";
                    removeValidaForm()
                } else {
                    document.getElementById("cpf").value = "";
                    iziToast.success({title: 'Legal!', message: retorno.msg, position: 'bottomRight'});
                }
            }).fail(function (xhr, status, error) {
                clickBotaoProgressInativo("btnEsqueciSenhaEnviar","Entrar");
                iziToast.error({title: 'Erro!', message: 'Tente novamente mais tarde', position: 'bottomRight'});

            });
        }
    } else {
        iziToast.error({title: 'Erro!', message: 'Sem internet', position: 'bottomRight'});

    }
}