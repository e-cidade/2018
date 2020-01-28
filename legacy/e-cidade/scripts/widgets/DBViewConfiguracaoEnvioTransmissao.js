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

        var mensagemAviso = "Foram configurados pagamentos como forma de Transmiss�o (TRA). Para essa modalidade de  ";
        mensagemAviso    += "pagamento com credores do tipo �rg�o p�blico � necess�rio informar a finalidade de pagamento atrav�s da Configura��o de Envio.\n\n";
        mensagemAviso    += "Voc� pode realizar esta configura��o agora pressionando OK ou atrav�s do bot�o Configura��es de Envio.\n";
        mensagemAviso    += "Voc� tamb�m pode efetuar a configura��o de envio depois, clicando em CANCELAR e acessando o menu: Procedimentos > Agenda > Configura��o de Envio.\n";
        if (confirm(mensagemAviso)) {

          var movimentos = retorno.movimentosRelacionados.join(',');
          location.href = "emp4_configuracaoarquivoenvio001.php?origem="+self.origemSelecionada+"&movimentos="+movimentos;
        }
      }
    }
  ).setMessage('Aguarde, verificando natureza de credores...').execute();
};