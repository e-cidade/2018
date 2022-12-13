DBViewConfiguracaoEnvioTransmissao = function (movimentos, origem) {

  this.movimentos = movimentos;
  this.origem = origem;
  this.origemSelecionada = 'empenho';

  if (this.origem === 2) {
    this.origemSelecionada = 'slip';
  }
};

DBViewConfiguracaoEnvioTransmissao.prototype.verificarMovimentos = function () {

  var self = this;
  if (this.movimentos.length === 0) {
    return false;
  }

  new AjaxRequest(
    'emp4_manutencaoPagamentoRPC.php',
    {'exec' : 'verificarNaturezaCredor', 'origem' : this.origem, 'codigoMovimentos' : this.movimentos},
    function (retorno, erro) {

      if (erro) {
        alert(retorno.mensagem);
        return false;
      }

      if (retorno.movimentosRelacionados.length > 0) {

        var mensagemAviso = "Foram configurados pagamentos como forma de Transmissão (TRA). Para essa modalidade de  ";
        mensagemAviso    += "pagamento com credores do tipo órgão público é necessário informar a finalidade de pagamento através da Configuração de Envio.\n\n";
        mensagemAviso    += "Você pode realizar esta configuração agora pressionando OK ou através do botão Configurações de Envio.\n";
        mensagemAviso    += "Você também pode efetuar a configuração de envio depois, clicando em CANCELAR e acessando o menu: Procedimentos > Agenda > Configuração de Envio.\n";
        if (confirm(mensagemAviso)) {

          var movimentos = retorno.movimentosRelacionados.join(',');
          location.href = "emp4_configuracaoarquivoenvio001.php?origem="+self.origemSelecionada+"&movimentos="+movimentos;
        }
      }
    }
  ).setMessage('Aguarde, verificando natureza de credores...').execute();
};