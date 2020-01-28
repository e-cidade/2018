/**
 * Classe para manutenção do cadastro de cidadão
 * @param iCodigo    - Código do cidadão (aceita vazio)
 * @param oDestino   - Elemento de destino onde a tela será renderizada
 * @param sInstancia - Instancia da classe
 * @returns {DBViewCidadao.Cidadao}
 * 
 * @todo CLASSE IMPLEMENTADA SOMENTE NO CADASTRO DO ALUNO, PARA CRIAÇÃO DE NOVOS CIDADÃOS (PAI, MÃE, RESPONSÁVEL)
 *       AO UTILIZAR EM OUTROS MENUS, VERIFICAR A LÓGICA PARA FUNCIONAMENTO DE ACORDO COM O MESMO
 */
DBViewCidadao.Cidadao = function (iCodigo, oDestino, sInstancia) {
  
  this.iCodigo         = iCodigo;
  this.sInstancia      = sInstancia;
  this.oDestino        = oDestino;
  this.oDadosCidadao   = null;
  this.sUrlRpcCidadao  = 'ouv4_cidadao.RPC.php';
  this.sUrlRpcCadEnder = 'con4_cadender.RPC.php';
  
  /**
   * Elemento input do código do cidadão
   */
  this.oInputCodigoCidadao                       = document.createElement('input');
  this.oInputCodigoCidadao.id                    = 'oInputCodigoCidadao';
  this.oInputCodigoCidadao.style.width           = '50px';
  this.oInputCodigoCidadao.readOnly              = true;
  this.oInputCodigoCidadao.style.backgroundColor = '#DEB887';
  
  /**
   * Elemento input do nome do cidadão
   */
  this.oInputNomeCidadao             = document.createElement('input');
  this.oInputNomeCidadao.id          = 'oInputNomeCidadao';
  this.oInputNomeCidadao.style.width = '755px';
  this.oInputNomeCidadao.maxLength   = 100;

  /**
   * Elemento input da identidade do cidadão
   */
  this.oInputIdentidade             = document.createElement('input');
  this.oInputIdentidade.id          = 'oInputIdentidade';
  this.oInputIdentidade.style.width = '125px';
  this.oInputIdentidade.maxLength   = 20;
  
  /**
   * Elemento input do CPF/CNPJ do cidadão
   */
  this.oInputCPFCNPJ              = document.createElement('input');
  this.oInputCPFCNPJ.id           = 'oInputCPFCNPJ';
  this.oInputCPFCNPJ.style.width  = '100%';
  this.oInputCPFCNPJ.maxLength    = 14;
  
  /**
   * Elemento DBTextFieldData da data de nascimento
   */
  this.oInputDataNascimento = new DBTextFieldData('inputDataNascimento', sInstancia+'.oInputDataNascimento', null);
  
  /**
   * Elemento select do sexo do cidadão
   */
  this.oSelectSexo             = document.createElement('select');
  this.oSelectSexo.id          = 'oSelectSexo';
  this.oSelectSexo.style.width = '100%';
  this.oSelectSexo.add(new Option('Masculino', 'M'));
  this.oSelectSexo.add(new Option('Feminino', 'F'));
  
  /**
   * Elemento input do endereço do cidadão
   */
  this.oInputEndereco              = document.createElement('input');
  this.oInputEndereco.id           = 'oInputEndereco';
  this.oInputEndereco.style.width  = '575px';
  this.oInputEndereco.maxLength    = 100;
  
  /**
   * Elemento input do número do endereço do cidadão
   */
  this.oInputEnderecoNumero              = document.createElement('input');
  this.oInputEnderecoNumero.id           = 'oInputEnderecoNumero';
  this.oInputEnderecoNumero.style.width  = '150px';
  
  /**
   * Elemento input do bairro do cidadão
   */
  this.oInputBairro              = document.createElement('input');
  this.oInputBairro.id           = 'oInputBairro';
  this.oInputBairro.style.width  = '575px';
  this.oInputBairro.maxLength    = 100;
  
  /**
   * Elemento input do complemento do endereço do cidadão
   */
  this.oInputComplemento              = document.createElement('input');
  this.oInputComplemento.id           = 'oInputComplemento';
  this.oInputComplemento.style.width  = '150px';
  this.oInputComplemento.maxLength    = 50;
  
  /**
   * Elemento select da UF do cidadão
   */
  this.oSelectUf              = document.createElement('select');
  this.oSelectUf.id           = 'oSelectUf';
  this.oSelectUf.style.width  = '100%';
  
  /**
   * Elemento select do município do cidadão
   */
  this.oSelectMunicipio              = document.createElement('select');
  this.oSelectMunicipio.id           = 'oSelectMunicipio';
  this.oSelectMunicipio.style.width  = '100%';
  this.oSelectMunicipio.add(new Option('Selecione um município', ''));
  
  /**
   * Elemento input do CEP do cidadão
   */
  this.oInputCep              = document.createElement('input');
  this.oInputCep.id           = 'oInputCep';
  this.oInputCep.style.width  = '150px';
  this.oInputCep.maxLength    = 8;
  
  /**
   * Elemento input do email do cidadão
   */
  this.oInputEmail              = document.createElement('input');
  this.oInputEmail.id           = 'oInputEmail';
  this.oInputEmail.style.width  = '675px';
  this.oInputEmail.maxLength    = 100;
  
  /**
   * Elemento select de controle se o email é o principal ou não do cidadão
   */
  this.oSelectEmailPrincipal             = document.createElement('select');
  this.oSelectEmailPrincipal.id          = 'oSelectEmailPrincipal';
  this.oSelectEmailPrincipal.style.width = '100%';
  this.oSelectEmailPrincipal.add(new Option('Sim', 't'));
  this.oSelectEmailPrincipal.add(new Option('Não', 'f'));
  
  /**
   * Grid com os emails cadastrados para o cidadão
   */
  this.oGridEmail              = new DBGrid('gridEmail');
  this.oGridEmail.nameInstance = sInstancia+'.oGridEmail';
  this.oGridEmail.setHeader(new Array("Código", "Descrição", "Principal", "Ações"));
  this.oGridEmail.setCellAlign(new Array("right", "left", "left", "center"));
  this.oGridEmail.setCellWidth(new Array("10%", "60%", "15%", "15%"));
  this.oGridEmail.setHeight(80);
  this.oGridEmail.aHeaders[0].lDisplayed = false;
  
  /**
   * Elemento select de controle do tipo de telefone a ser cadastra
   */
  this.oSelectTipoTelefone             = document.createElement('select');
  this.oSelectTipoTelefone.id          = 'oSelectTipoTelefone';
  this.oSelectTipoTelefone.style.width = '100px';
  this.oSelectTipoTelefone.add(new Option('Residencial', 1));
  this.oSelectTipoTelefone.add(new Option('Celular', 2));
  this.oSelectTipoTelefone.add(new Option('Comercial', 3));
  this.oSelectTipoTelefone.add(new Option('Fax', 4));
  
  /**
   * Elemento select de controle se o telefone é o principal ou não
   */
  this.oSelectTelefonePrincipal             = document.createElement('select');
  this.oSelectTelefonePrincipal.id          = 'oSelectTelefonePrincipal';
  this.oSelectTelefonePrincipal.style.width = '100px';
  this.oSelectTelefonePrincipal.add(new Option('Sim', 't'));
  this.oSelectTelefonePrincipal.add(new Option('Não', 'f'));
  
  /**
   * Elemento input do DDD do telefone
   */
  this.oInputDDD              = document.createElement('input');
  this.oInputDDD.id           = 'oInputDDD';
  this.oInputDDD.style.width  = '100px';
  this.oInputDDD.maxLength    = 5;
  
  /**
   * Elemento input do número do telefone
   */
  this.oInputNumero              = document.createElement('input');
  this.oInputNumero.id           = 'oInputNumero';
  this.oInputNumero.style.width  = '100px';
  this.oInputNumero.maxLength    = 10;
  
  /**
   * Elemento input do ramal do telefone
   */
  this.oInputRamal              = document.createElement('input');
  this.oInputRamal.id           = 'oInputRamal';
  this.oInputRamal.style.width  = '100px';
  this.oInputRamal.maxLength    = 10;
  
  /**
   * Elemento textarea com as observações referente ao telefone
   */
  this.oTextAreaObservacoes              = document.createElement('textarea');
  this.oTextAreaObservacoes.id           = 'oTextAreaObservacoes';
  this.oTextAreaObservacoes.style.width  = '860px';
  
  /**
   * Grid com os telefones cadastrados
   */
  this.oGridTelefone              = new DBGrid('gridTelefone');
  this.oGridTelefone.nameInstance = sInstancia+'.oGridTelefone';
  
  var aHeaderTelefone = new Array(
                                    "Código", 
                                    "Descrição Tipo", 
                                    "DDD", 
                                    "Número", 
                                    "Ramal", 
                                    "Principal", 
                                    "Ações", 
                                    "Código Tipo", 
                                    "Observações"
                                  );
  this.oGridTelefone.setHeader(aHeaderTelefone);
  this.oGridTelefone.setCellAlign(new Array("right", "left", "right", "right", "right", "center", "center", "left", "left"));
  this.oGridTelefone.setHeight(80);
  this.oGridTelefone.aHeaders[0].lDisplayed = false;
  this.oGridTelefone.aHeaders[7].lDisplayed = false;
  this.oGridTelefone.aHeaders[8].lDisplayed = false;
  
  /**
   * Propriedades para controle de CPF obrigatório, se já possui email principal, se possui telefone principal e se
   * deve ser carregado em uma windowAux
   */
  this.lCpfObrigatorio       = false;
  this.lTemEmailPrincipal    = false;
  this.lTemTelefonePrincipal = false;
  this.lWindowAux            = false;
  
  this.oCallBackAposSalvar = function() {
    return true;    
  };
};

/**
 * Seta se deve ser carregado em uma windowAux
 * 
 * @param boolean lWindowAux
 */
DBViewCidadao.Cidadao.prototype.setWindowAux = function(lWindowAux) {
  this.lWindowAux = lWindowAux;
};

/**
 * Monta a janela de cadastro e alteração do cidadão
 */
DBViewCidadao.Cidadao.prototype.montaJanela = function () {

  var oSelf = this;
  
  /**
   * Conteúdo do fieldset dos dados gerais do cidadão
   */
  var sConteudoCidadao  = "<fieldset class='separator'>";
      sConteudoCidadao += "  <legend class='bold'>Cadastro Cidadão</legend>";
      sConteudoCidadao += "  <table>";
      sConteudoCidadao += "    <tr>";
      sConteudoCidadao += "      <td><label class='bold'>Cidadão:</label></td>";
      sConteudoCidadao += "      <td id='ctnCodigoCidadao'></td>";
      sConteudoCidadao += "    </tr>";
      sConteudoCidadao += "    <tr>";
      sConteudoCidadao += "      <td><label class='bold'>Nome / Razão Social:</label></td>";
      sConteudoCidadao += "      <td id='ctnNomeCidadao' colspan='3'></td>";
      sConteudoCidadao += "    </tr>";
      sConteudoCidadao += "    <tr>";
      sConteudoCidadao += "      <td><label class='bold'>Identidade:</label></td>";
      sConteudoCidadao += "      <td id='ctnIdentidade'></td>";
      sConteudoCidadao += "      <td><label class='bold'>CPF:</label></td>";
      sConteudoCidadao += "      <td id='ctnCpf'></td>";
      sConteudoCidadao += "    </tr>";
      sConteudoCidadao += "    <tr>";
      sConteudoCidadao += "      <td><label class='bold'>Data de Nascimento:</label></td>";
      sConteudoCidadao += "      <td id='ctnDataNascimento'></td>";
      sConteudoCidadao += "      <td><label class='bold'>Sexo:</label></td>";
      sConteudoCidadao += "      <td id='ctnSexo'></td>";
      sConteudoCidadao += "    </tr>";
      sConteudoCidadao += "  </table>";
      sConteudoCidadao += "</fieldset>";
      
  /**
   * Conteúdo do fieldset dos dados de localização do cidadão (Endereço, Número, ...)
   */
  var sConteudoEndereco = "<fieldset class='separator'>";
      sConteudoEndereco += "  <legend class='bold'>Endereço</legend>";
      sConteudoEndereco += "  <table>";
      sConteudoEndereco += "    <tr>";
      sConteudoEndereco += "      <td><a class='bold' href='#' onClick='"+this.sInstancia+".pesquisaEndereco();'>Endereço:</a></td>";
      sConteudoEndereco += "      <td id='ctnEndereco'></td>";
      sConteudoEndereco += "      <td><label class='bold'>Número:</label></td>";
      sConteudoEndereco += "      <td id='ctnNumero'></td>";
      sConteudoEndereco += "    </tr>";
      sConteudoEndereco += "    <tr>";
      sConteudoEndereco += "      <td><a class='bold' href='#' onClick='"+this.sInstancia+".pesquisaBairro();'>Bairro:</a></td>";
      sConteudoEndereco += "      <td id='ctnBairro'></td>";
      sConteudoEndereco += "      <td><label class='bold'>Complemento:</label></td>";
      sConteudoEndereco += "      <td id='ctnComplemento'></td>";
      sConteudoEndereco += "    </tr>";
      sConteudoEndereco += "    <tr>";
      sConteudoEndereco += "      <td><label class='bold'>UF:</label></td>";
      sConteudoEndereco += "      <td id='ctnUf'></td>";
      sConteudoEndereco += "      <td><label class='bold'>CEP:</label></td>";
      sConteudoEndereco += "      <td id='ctnCep'></td>";
      sConteudoEndereco += "    </tr>";
      sConteudoEndereco += "    <tr>";
      sConteudoEndereco += "      <td><label class='bold'>Município:</label></td>";
      sConteudoEndereco += "      <td id='ctnMunicipio'></td>";
      sConteudoEndereco += "    </tr>";
      sConteudoEndereco += "  </table>";
      sConteudoEndereco += "</fieldset>";
      
  /**
   * Conteúdo do fieldset dos Emails do cidadão
   */
  var sConteudoEmail = "<fieldset class='separator'>";
      sConteudoEmail += "  <legend class='bold'>Email</legend>";
      sConteudoEmail += "  <table>";
      sConteudoEmail += "    <tr>";
      sConteudoEmail += "      <td><label class='bold'>Email:</label></td>";
      sConteudoEmail += "      <td id='ctnEmail'></td>";
      sConteudoEmail += "      <td><label class='bold'>Principal:</label></td>";
      sConteudoEmail += "      <td id='ctnEmailPrincipal'></td>";
      sConteudoEmail += "      <td><input id='incluirEmail' type='button' value='Incluir'></td>";
      sConteudoEmail += "    </tr>";
      sConteudoEmail += "    <tr>";
      sConteudoEmail += "      <td colspan='5'>";
      sConteudoEmail += "        <fieldset>";
      sConteudoEmail += "          <legend class='bold'>Lista de Emails</legend>";
      sConteudoEmail += "          <div id='gridEmails'></div>";
      sConteudoEmail += "        </fieldset>";
      sConteudoEmail += "      </td>";
      sConteudoEmail += "    </tr>";
      sConteudoEmail += "  </table>";
      sConteudoEmail += "</fieldset>";
      
  /**
   * Conteúdo do fieldset dos Telefones do cidadão
   */
  var sConteudoTelefone = "<fieldset class='separator'>";
      sConteudoTelefone += "  <legend class='bold'>Telefone/Fax</legend>";
      sConteudoTelefone += "  <table>";
      sConteudoTelefone += "    <tr>";
      sConteudoTelefone += "      <td width='10%'><label class='bold'>Tipo Telefone:</label></td>";
      sConteudoTelefone += "      <td id='ctnTipoTelefone'></td>";
      sConteudoTelefone += "      <td width='8%'><label class='bold'>Principal:</label></td>";
      sConteudoTelefone += "      <td id='ctnTelefonePrincipal'></td>";
      sConteudoTelefone += "    </tr>";
      sConteudoTelefone += "    <tr>";
      sConteudoTelefone += "      <td><label class='bold'>DDD:</label></td>";
      sConteudoTelefone += "      <td id='ctnTelefoneDDD'></td>";
      sConteudoTelefone += "      <td><label class='bold'>Número:</label></td>";
      sConteudoTelefone += "      <td id='ctnTelefoneNumero'></td>";
      sConteudoTelefone += "      <td width='5%'><label class='bold'>Ramal:</label></td>";
      sConteudoTelefone += "      <td id='ctnTelefoneRamal'></td>";
      sConteudoTelefone += "    </tr>";
      sConteudoTelefone += "    <tr>";
      sConteudoTelefone += "    </tr>";
      sConteudoTelefone += "    <tr>";
      sConteudoTelefone += "      <td colspan='6'>";
      sConteudoTelefone += "        <fieldset>";
      sConteudoTelefone += "          <legend class='bold'>Observações</legend>";
      sConteudoTelefone += "          <div id='ctnObservacoes'></div>";
      sConteudoTelefone += "        </fieldset>";
      sConteudoTelefone += "      </td>";
      sConteudoTelefone += "    </tr>";
      sConteudoTelefone += "    <tr>";
      sConteudoTelefone += "      <td colspan='6' style='text-align: center;'>";
      sConteudoTelefone += "        <input id='incluirTelefone' type='button' value='Incluir'>";
      sConteudoTelefone += "      </td>";
      sConteudoTelefone += "    </tr>";
      sConteudoTelefone += "    <tr>";
      sConteudoTelefone += "      <td colspan='6'>";
      sConteudoTelefone += "        <fieldset>";
      sConteudoTelefone += "          <legend class='bold'>Lista de Telefones</legend>";
      sConteudoTelefone += "          <div id='gridTelefones'></div>";
      sConteudoTelefone += "        </fieldset>";
      sConteudoTelefone += "      </td>";
      sConteudoTelefone += "    </tr>";
      sConteudoTelefone += "  </table>";
      sConteudoTelefone += "</fieldset>";
  /**
   * Botões das ações 
   */
  var iAltura       = document.body.clientHeight / 1.2; 
  var iAlturaJanela = iAltura - 150; 
  var sBotoes = "<div class='container'>";
      sBotoes += "  <input id='btnSalvar' type='button' value='Salvar'>";
      sBotoes += "  <input id='btnPesquisar' type='button' value='Pesquisar' onClick='" + this.sInstancia + ".pesquisaCidadao()'>";
      sBotoes += "</div>";
      
  var sConteudos = '<fieldset>';
  if (this.lWindowAux) {
    sConteudos += '<div style="height:'+iAlturaJanela+'px;overflow:auto">';
  }
  sConteudos += sConteudoCidadao + sConteudoEndereco + sConteudoEmail + sConteudoTelefone;
  if (this.lWindowAux) {
    sConteudos += '</div>';
  }
  sConteudos += '</fieldset>' + sBotoes;
  
  /**
   * Verifica se o conteúdo será aberto em uma WindowAux
   */
  if (this.lWindowAux) {
    
    var iLargura = document.body.getWidth() / 1.7;
    
    this.oWindowCidadao = new windowAux('wndCidadao', 'Cidadão', iLargura, iAltura);
    this.oWindowCidadao.allowCloseWithEsc(false);
    this.oWindowCidadao.setShutDownFunction(function () {
      oSelf.oWindowCidadao.destroy();
    });
    
    this.oWindowCidadao.setContent(sConteudos);
    
    new DBMessageBoard(
                        'msgCidadao', 
                        'Cadastro do Cidadão', 
                        'Informações do cadastro do cidadão', 
                        this.oWindowCidadao.getContentContainer()
                      );
    
    this.oWindowCidadao.show();
  } else {
    this.oDestino.innerHTML = sConteudos;
  }
  
  /**
   * Posiciona os campos na tela
   */
  $('ctnCodigoCidadao').appendChild(this.oInputCodigoCidadao);
  $('ctnNomeCidadao').appendChild(this.oInputNomeCidadao);
  $('ctnIdentidade').appendChild(this.oInputIdentidade);
  $('ctnCpf').appendChild(this.oInputCPFCNPJ);
  $('ctnSexo').appendChild(this.oSelectSexo);
  this.oInputDataNascimento.show($('ctnDataNascimento'));
  
  $('ctnEndereco').appendChild(this.oInputEndereco);
  $('ctnNumero').appendChild(this.oInputEnderecoNumero);
  $('ctnBairro').appendChild(this.oInputBairro);
  $('ctnComplemento').appendChild(this.oInputComplemento);
  $('ctnUf').appendChild(this.oSelectUf);
  $('ctnMunicipio').appendChild(this.oSelectMunicipio);
  $('ctnCep').appendChild(this.oInputCep);
  
  $('ctnEmail').appendChild(this.oInputEmail);
  $('ctnEmailPrincipal').appendChild(this.oSelectEmailPrincipal);
  this.oGridEmail.show($('gridEmails'));
  
  $('ctnTipoTelefone').appendChild(this.oSelectTipoTelefone);
  $('ctnTelefonePrincipal').appendChild(this.oSelectTelefonePrincipal);
  $('ctnTelefoneDDD').appendChild(this.oInputDDD);
  
  $('ctnTelefoneNumero').appendChild(this.oInputNumero);
  $('ctnTelefoneRamal').appendChild(this.oInputRamal);
  $('ctnObservacoes').appendChild(this.oTextAreaObservacoes);
  this.oGridTelefone.show($('gridTelefones'));
  
  /**
   * Observe configurados
   */
  $('btnSalvar').observe('click', function() {
    oSelf.salvar();
  });
  
  $('oInputNomeCidadao').observe("keyup", function() {
    $('oInputNomeCidadao').value = $('oInputNomeCidadao').value.toUpperCase();
  });
  
  $('oInputCPFCNPJ').observe("change", function() {
    
    if (!empty(oSelf.oInputCPFCNPJ.value) && !validaCpfCnpj(oSelf.oInputCPFCNPJ)) {
      
      oSelf.oInputCPFCNPJ.focus();
      alert(_M('patrimonial.ouvidoria.DBViewCidadao_cidadao.cpfcnpj_invalido'));
      return false;
    }
  });
  
  $('oInputEndereco').observe("keyup", function() {
    $('oInputEndereco').value = $('oInputEndereco').value.toUpperCase();
  });
  
  $('oInputBairro').observe("keyup", function() {
    $('oInputBairro').value = $('oInputBairro').value.toUpperCase();
  });
  
  $('oSelectUf').observe("change", function() {
    oSelf.pesquisaMunicipios();
  });
  
  $('incluirEmail').observe('click', function() {
    oSelf.incluirEmail();
  });
  
  $('incluirTelefone').observe('click', function() {
    oSelf.incluirTelefone();
  });
  
  $('oInputDDD').observe('keyup', function() {
    this.value = this.value.replace(/[^0-9]/, '');
  });
  
  $('oInputNumero').observe('keyup', function() {
    this.value = this.value.replace(/[^0-9]/, '');
  });
  
  $('oInputRamal').observe('keyup', function() {
    this.value = this.value.replace(/[^0-9]/, '');
  });
};

/**
 * Carrega a lookup de pesquisa do cidadão, buscando o sequencial do mesmo
 */
DBViewCidadao.Cidadao.prototype.pesquisaCidadao = function () {
  
  var sUrl = 'func_cidadao.php?funcao_js=parent.'+this.sInstancia+'.getDados|ov02_sequencial';
  js_OpenJanelaIframe('top.corpo', 'db_iframe_cidadao', sUrl, 'Pesquisa Cidadão', true);
  
  $('Jandb_iframe_cidadao').style.zIndex = 10000;
};

/**
 * Busca os dados de um cidadão, de acordo com o código recebido como parâmetro
 * 
 * @param integer iCidadao - Código do cidadão
 */
DBViewCidadao.Cidadao.prototype.getDados = function () {
  
  if (arguments[1] !== false) {
    db_iframe_cidadao.hide();
  }
  
  if (empty(this.iCodigo)) {
    return false;
  }
  
  var oSelf                = this;
  var oParametro           = new Object();
      oParametro.sExecucao = 'getDados';
      oParametro.iCidadao  = arguments[0];
      
  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = function(oResponse) {
                                                           oSelf.retornoCidadao(oResponse, oSelf);
                                                         };
  
  js_divCarregando(_M('patrimonial.ouvidoria.DBViewCidadao_cidadao.pesquisando_dados_cidadao'), "msgBox");
  
  new Ajax.Request(this.sUrlRpcCidadao, oDadosRequisicao);
};

/**
 * Retorna os dados do cidadão, preenchendo os campos de acordo com as informações existentes
 */
DBViewCidadao.Cidadao.prototype.retornoCidadao = function (oResponse, oSelf) {
  
  js_removeObj("msgBox");
  
  var oSelf    = this;
  var oRetorno = eval('('+oResponse.responseText+')');
  oSelf.oDadosCidadao = oRetorno;
  
  /**
   * Preenche os campos do fieldset de dados padrão do cidadão
   */
  $('oInputNomeCidadao').value   = oRetorno.sNome.urlDecode();
  $('oInputIdentidade').value    = oRetorno.sIdentidade.urlDecode();
  $('oInputCPFCNPJ').value       = oRetorno.sCpf.urlDecode();
  $('inputDataNascimento').value = oRetorno.dtNascimento.urlDecode();
  $('oSelectSexo').value         = oRetorno.sSexo.urlDecode();
  
  /**
   * Preenche os campos do fieldset dos endereços
   */
  $('oInputEndereco').value       = oRetorno.sEndereco.urlDecode();
  $('oInputEnderecoNumero').value = oRetorno.iNumero;
  $('oInputBairro').value         = oRetorno.sBairro.urlDecode();
  $('oInputComplemento').value    = oRetorno.sComplemento.urlDecode();
  $('oInputCep').value            = oRetorno.sCep.urlDecode();
  
  /**
   * 1º Busca os estados
   * 2º Percorre os estados e compara a sigla que retornou do cidadão com a sigla do select. Caso seja igual, atribui 
   *    como selected e busca o município.
   * 3º Caso nos dados do cidadão tenha um município cadastrado, percorre os municípios comparando o valor e atribuindo
   *    como selected caso seja igual
   */
  oSelf.pesquisaEstados();
  
  if (!empty(oRetorno.sUf.urlDecode())) {
    
    var iEstados = $('oSelectUf').options.length;
    for (var iContador = 0; iContador < iEstados; iContador++) {
      
      if (oRetorno.sUf.urlDecode() == $('oSelectUf').options[iContador].getAttribute('sigla')) {
        $('oSelectUf').options[iContador].selected = true;
      }
    }
    
    oSelf.pesquisaMunicipios();
    if (!empty(oRetorno.sMunicipio.urlDecode())) {
      
      var iMunicipios = $('oSelectMunicipio').options.length;
      for (var iContador = 0; iContador < iMunicipios; iContador++) {
      
        if (oRetorno.sMunicipio.urlDecode() == $('oSelectMunicipio').options[iContador].value) {
          $('oSelectMunicipio').options[iContador].selected = true;
        }
      }
    }
  }
  
  /**
   * Caso existam emails, preenche a grid
   */
  if (oRetorno.aEmail.length > 0) {
    
    oSelf.oGridEmail.clearAll(true);
    oRetorno.aEmail.each(function(oEmail, iSeq) {
      
      var aLinha = new Array();
          aLinha.push('');
          aLinha.push(oEmail.sEmail.urlDecode());
          aLinha.push(oEmail.sPrincipal.urlDecode());
          aLinha.push('<input type="button" value="Excluir" onclick="' + oSelf.sInstancia + '.renderizaGridEmail(true, ' + iSeq + ')" >');
          
      oSelf.oGridEmail.addRow(aLinha);
      
      if (oEmail.lPrincipal === true) {
        oSelf.lTemEmailPrincipal = true;
      }
    });
    
    oSelf.oGridEmail.renderRows();
  }
      
  /**
   * Caso existam telefones, preenche a grid
   */
  if (oRetorno.aTelefones.length > 0) {
    
    var aTiposTelefone   = new Array();
        aTiposTelefone[1] = 'Residencial';
        aTiposTelefone[2] = 'Celular';
        aTiposTelefone[3] = 'Comercial';
        aTiposTelefone[4] = 'Fax';
    
    oSelf.oGridTelefone.clearAll(true);
    oRetorno.aTelefones.each(function(oTelefone, iSeq) {
    
      var aLinha = new Array();
          aLinha.push(oTelefone.iCodigo);
          
          if (aTiposTelefone.hasOwnProperty(oTelefone.iTipo)) {
            aLinha.push(aTiposTelefone[oTelefone.iTipo]);
          } else {
            aLinha.push('');
          }
          
          aLinha.push(oTelefone.sDDD.urlDecode());
          aLinha.push(oTelefone.iNumero);
          aLinha.push(oTelefone.sRamal.urlDecode());
          aLinha.push(oTelefone.sPrincipal.urlDecode());
          aLinha.push('<input type="button"' 
                           +' value="Excluir"' 
                           +' onclick="' + oSelf.sInstancia + '.renderizaGridTelefone(true, ' + iSeq + ')" >');
          aLinha.push(oTelefone.iTipo);
          aLinha.push(oTelefone.sObservacoes.urlDecode());
    
      oSelf.oGridTelefone.addRow(aLinha);
      
      if (oTelefone.lPrincipal === true) {
        oSelf.lTemTelefonePrincipal = true;
      }
    });
    
    oSelf.oGridTelefone.renderRows();
  }
};

/**
 * Carrega a lookup de pesquisa de endereços
 */
DBViewCidadao.Cidadao.prototype.pesquisaEndereco = function () {
  
  var sUrl = 'func_ruas.php?funcao_js=parent.'+this.sInstancia+'.mostraEndereco|j14_codigo|j14_nome';
  js_OpenJanelaIframe('', 'db_iframe_endereco', sUrl, 'Pesquisa Endereço', true);
  
  $('Jandb_iframe_endereco').style.zIndex = 10000;
};

/**
 * Retorno da lookup de pesquisa, preenchendo o endereço com a descrição
 */
DBViewCidadao.Cidadao.prototype.mostraEndereco = function () {
  
  this.oInputEndereco.value = arguments[1];
  db_iframe_endereco.hide();
};

/**
 * Carrega a lookup de pesquisa dos bairros. Busca o código e descrição
 */
DBViewCidadao.Cidadao.prototype.pesquisaBairro = function () {
  
  var sUrl = 'func_bairro.php?funcao_js=parent.'+this.sInstancia+'.mostraBairro|j13_codi|j13_descr';
  js_OpenJanelaIframe('', 'db_iframe_bairro', sUrl, 'Pesquisa Bairro', true);
  $('Jandb_iframe_bairro').style.zIndex = 10000;
};

/**
 * Retorno da busca pelo bairro, preenchendo o bairro com a descrição
 */
DBViewCidadao.Cidadao.prototype.mostraBairro = function () {
  
  this.oInputBairro.value = arguments[1];
  db_iframe_bairro.hide();
};

/**
 * Busca os estados da tabela cadender, vinculados ao país 1 (Brasil)
 */
DBViewCidadao.Cidadao.prototype.pesquisaEstados = function () {
  
  this.limpaEstados();
  
  var oSelf                = this;
  var oParametro           = new Object();
      oParametro.sExecucao = 'getEstados';
      oParametro.iPais     = 1;
  
  var oDadosRequisicao              = new Object();
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function(oResponse) {
                                                             oSelf.retornoEstados(oResponse, oSelf);
                                                           };
  
  js_divCarregando(_M('patrimonial.ouvidoria.DBViewCidadao_cidadao.pesquisando_estados'), "msgBox");
  new Ajax.Request(this.sUrlRpcCadEnder, oDadosRequisicao);
};

/**
 * Retorno dos estados vinculados ao país 1, preenchendo o select oSelectUf
 * 
 * @param object oResponse - Retorno das informações do RPC
 * @param object oSelf     - 'this' da função responsável por chamar retornoEstados
 */
DBViewCidadao.Cidadao.prototype.retornoEstados = function (oResponse, oSelf) {
  
  js_removeObj("msgBox");
  
  var oRetorno = eval('('+oResponse.responseText+')');
  var oSelf = this;
  
  oRetorno.aEstados.each(function(oEstado, iSeq) {
    
    var oOpcao = new Option(oEstado.sDescricao.urlDecode(), oEstado.iSequencial);
        oOpcao.setAttribute('sigla', oEstado.sSigla.urlDecode());
    
    oSelf.oSelectUf.add(oOpcao);
  });
};

/**
 * Remove as options do combo dos estados
 */
DBViewCidadao.Cidadao.prototype.limpaEstados = function () {
  
  iTotalEstados = this.oSelectUf.length;

  for (var iContador = 0; iContador < iTotalEstados; iContador++) {
    this.oSelectUf.options.remove(iContador);
  }
  this.oSelectUf.add(new Option('Selecione um Estado', ''));
};

/**
 * Pesquisa os municípios vinculados ao estado selecionado
 */
DBViewCidadao.Cidadao.prototype.pesquisaMunicipios = function() {
  
  this.limpaMunicipios();
  
  if (!empty(this.oSelectUf.value)) {
    
    var oSelf                = this;
    var oParametro           = new Object();
        oParametro.sExecucao = 'getMunicipios';
        oParametro.iEstado   = this.oSelectUf.value;
    
    var oDadosRequisicao              = new Object();
        oDadosRequisicao.method       = 'post';
        oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.asynchronous = false;
        oDadosRequisicao.onComplete   = function(oResponse) {
                                                           oSelf.retornoMunicipios(oResponse, oSelf);
                                                         };
                                      
    js_divCarregando(_M('patrimonial.ouvidoria.DBViewCidadao_cidadao.pesquisando_municipios'), "msgBox");
    new Ajax.Request(this.sUrlRpcCadEnder, oDadosRequisicao);
  }
};

/**
 * Retorno dos municipios vinculados ao estado selecionado
 * 
 * @param Object oResponse - Retorno do RPC dos municípios
 * @param Object oSelf - 'this' da função responsável por chamar retornoMunicipios
 */
DBViewCidadao.Cidadao.prototype.retornoMunicipios = function (oResponse, oSelf) {

  js_removeObj("msgBox");
  
  var oRetorno = eval('('+oResponse.responseText+')');
  var oSelf    = this;
  
  oRetorno.aMunicipios.each(function(oMunicipio, iSeq) {
    oSelf.oSelectMunicipio.add(new Option(oMunicipio.sDescricao.urlDecode(), oMunicipio.sDescricao.urlDecode()));
  });
};

/**
 * Remove as options do combo dos municipios
 */
DBViewCidadao.Cidadao.prototype.limpaMunicipios = function () {
  
  iTotalMunicipios = this.oSelectMunicipio.length;

  for (var iContador = 0; iContador < iTotalMunicipios; iContador++) {
    this.oSelectMunicipio.options.remove(iContador);
  }
  this.oSelectMunicipio.add(new Option('Selecione um município', ''));
};

/**
 * Inclui um email na Grid de emails validando se o email é valido e a inclusão de mais de um email principal
 */
DBViewCidadao.Cidadao.prototype.incluirEmail = function() {
  
  if (!validaEmail(this.oInputEmail.value)) {
    return false;
  }
  
  if (this.lTemEmailPrincipal && this.oSelectEmailPrincipal.value == 't') {
    
    if (confirm(_M('patrimonial.ouvidoria.DBViewCidadao_cidadao.confirma_email_secundario'))) {
      this.oSelectEmailPrincipal.value = 'f';
    } else {
      
      this.oInputEmail.focus();
      return false;
    }
  }
  
  this.renderizaGridEmail(false, 0);
};

/**
 * Atualiza e renderiza a grid do Email, realiza a inclusão e exclusão dos emails
 * 
 * @param boolean lRemove - True caso seja uma operação de remoção de linha
 *                         - False caso seja inserção
 * @param integer iIndice - Indice do item a ser removido
 */
DBViewCidadao.Cidadao.prototype.renderizaGridEmail = function ( lRemove , iIndice ) {
  
  if (lRemove) {
    
    var oMensagem        = new Object();
        oMensagem.sEmail = this.oGridEmail.aRows[iIndice].aCells[1].getContent();
    
    if (!confirm(_M('patrimonial.ouvidoria.DBViewCidadao_cidadao.confirma_exclusao_email', oMensagem))) {
      return false;
    }
  }
  
  var aTemporario = this.oGridEmail.aRows;
  var oSelf       = this;
  var iIndiceGrid = 0;
  
  this.oGridEmail.clearAll(true);

  aTemporario.each(function(oLinha, iSeq) {
    
    if (!(lRemove && iSeq == iIndice)) {
      
      var sInput = '<input type="button"' 
                        +' value="Excluir"' 
                        +' onclick="' + oSelf.sInstancia + '.renderizaGridEmail(true, ' + (iIndiceGrid++) + ')" >';
      var aLinha = new Array();
      
      aLinha.push(oLinha.aCells[0].getContent());
      aLinha.push(oLinha.aCells[1].getContent());
      aLinha.push(oLinha.aCells[2].getContent());
      aLinha.push(sInput);
      
      oSelf.oGridEmail.addRow(aLinha);
    }
  });
  
  if (!lRemove) {
      
    var sPrincipal = 'Não';
    
    if (this.oSelectEmailPrincipal.value == 't') {
      
      sPrincipal               = 'Sim';
      this.lTemEmailPrincipal = true;
    }
    
    var aRow = new Array();
        aRow.push('');
        aRow.push(this.oInputEmail.value);
        aRow.push(sPrincipal);
        aRow.push('<input type="button"' 
                       +' value="Excluir"' 
                       +' onclick="' + this.sInstancia + '.renderizaGridEmail(true, ' + iIndiceGrid + ')">');
    
    this.oGridEmail.addRow(aRow);
  }
  
  this.oGridEmail.renderRows();
  this.limpaCamposEmail();
};

/**
 * Limpa os campos de cadastro de email
 */
DBViewCidadao.Cidadao.prototype.limpaCamposEmail = function () {
  
  this.oInputEmail.value           = '';
  this.oSelectEmailPrincipal.value = 't';
  this.oInputEmail.focus();
};

/**
 * Inclui um telefone no cadastro do cidadão. Valida se o número está vazio e se já há um telefone principal incluso.
 */
DBViewCidadao.Cidadao.prototype.incluirTelefone = function() {
  
  if (empty(this.oInputNumero.value)) {
    
    alert(_M('patrimonial.ouvidoria.DBViewCidadao_cidadao.numero_vazio'));
    return false;
  }
  
  if (this.lTemTelefonePrincipal && this.oSelectTelefonePrincipal.value == 't') {
    
    if (confirm(_M('patrimonial.ouvidoria.DBViewCidadao_cidadao.confirma_telefone_secundario'))) {
      this.oSelectTelefonePrincipal.value = 'f';
    } else {
      
      this.oInputNumero.focus();
      return false;
    }
  }
  
  this.renderizaGridTelefone(false, 0);
};

/**
 * Atualiza e renderiza a Grid dos telefones. Utilizado tanto para inclusão quanto exclusão dos telefones na Grid.
 * 
 * @param boolean lRemove - True caso seja uma operação de remoção de linha
 *                         - False caso seja inserção
 * @param integer iIndice - Indice do item a ser removido
 */
DBViewCidadao.Cidadao.prototype.renderizaGridTelefone = function ( lRemove , iIndice ) {

  if (lRemove) {

    var oMensagem                = new Object();
        oMensagem.sDescricaoTipo = this.oGridTelefone.aRows[iIndice].aCells[1].getContent();
        oMensagem.sTelefone      = this.oGridTelefone.aRows[iIndice].aCells[3].getContent();
    
    if (!confirm(_M('patrimonial.ouvidoria.DBViewCidadao_cidadao.confirma_exclusao_telefone', oMensagem))) {
      return false;
    }

    if( this.oGridTelefone.aRows[iIndice].aCells[5].getContent() == 'Sim' ) {
      this.lTemTelefonePrincipal = false;
    }
  }
  
  var aTemporario = this.oGridTelefone.aRows;
  var oSelf       = this;
  var iIndiceGrid = 0;
  
  this.oGridTelefone.clearAll(true);

  aTemporario.each(function(oLinha, iSeq) {
    
    if (!(lRemove && iSeq == iIndice)) {
      
      var sInput = '<input type="button"' 
                         +' value="Excluir"' 
                         +' onclick="' + oSelf.sInstancia + '.renderizaGridTelefone(true, ' + (iIndiceGrid++) + ')" >';
      var aLinha = new Array();
      
      aLinha.push(oLinha.aCells[0].getContent());
      aLinha.push(oLinha.aCells[1].getContent());
      aLinha.push(oLinha.aCells[2].getContent());
      aLinha.push(oLinha.aCells[3].getContent());
      aLinha.push(oLinha.aCells[4].getContent());
      aLinha.push(oLinha.aCells[5].getContent());
      aLinha.push(sInput);
      aLinha.push(oLinha.aCells[7].getContent());
      aLinha.push(oLinha.aCells[8].getContent());
      
      oSelf.oGridTelefone.addRow(aLinha);
    }
  });
  
  if (!lRemove) {
    
    var aRow = new Array();
    aRow.push('');
    aRow.push($('oSelectTipoTelefone').options[$('oSelectTipoTelefone').selectedIndex].innerHTML);
    aRow.push(this.oInputDDD.value);
    aRow.push(this.oInputNumero.value);
    aRow.push(this.oInputRamal.value);
    
    var sPrincipal = 'Não';
    
    if (this.oSelectTelefonePrincipal.value == 't') {
      
      sPrincipal                  = 'Sim';
      this.lTemTelefonePrincipal = true;
    }
    
    aRow.push(sPrincipal);
    aRow.push('<input type="button"' 
                   +' value="Excluir"'
                   +' onclick="' + this.sInstancia + '.renderizaGridTelefone(true, ' + iIndiceGrid + ')">');
    aRow.push(this.oSelectTipoTelefone.value);
    aRow.push(this.oTextAreaObservacoes.value);
    
    this.oGridTelefone.addRow(aRow);
  }
  
  this.oGridTelefone.renderRows();
  this.limpaCamposTelefone();
};

/**
 * Limpa os campos de cadastro de telefone
 */
DBViewCidadao.Cidadao.prototype.limpaCamposTelefone = function () {
  
  this.oSelectTipoTelefone.value      = 1;
  this.oSelectTelefonePrincipal.value = 't';
  this.oInputDDD.value                = '';
  this.oInputNumero.value             = '';
  this.oInputRamal.value              = '';
  this.oTextAreaObservacoes.value     = '';
  
  this.oInputDDD.focus();
};

/**
 * Salva os dados do cidadão, tanto para inclusão quanto alteração
 * Antes de salvar, valida se as informações estão corretas
 */
DBViewCidadao.Cidadao.prototype.salvar = function () {
  
  var oSelf = this;
  if (this.validacoes()) {
    
    var aCamposTelefone = ['', 'sTipo', 'iDDD', 'iNumero', 'iRamal', 'sPrincipal', '', 'iCodigoTipo', 'sObservacoes'];
    
    var oParametro       = new Object();
    oParametro.sExecucao = 'salvar';

    /**
     * Dados padrão do cidadão
     */
    oParametro.iCidadao     = this.oInputCodigoCidadao.value;
    oParametro.sNome        = encodeURIComponent(tagString(this.oInputNomeCidadao.value));
    oParametro.sIdentidade  = encodeURIComponent(tagString(this.oInputIdentidade.value));
    oParametro.sCpf         = encodeURIComponent(tagString(this.oInputCPFCNPJ.value));
    oParametro.dtNascimento = encodeURIComponent(tagString(this.oInputDataNascimento.getValue()));
    oParametro.sSexo        = this.oSelectSexo.value;

    /**
     * Dados do fieldset de endereço
     */
    oParametro.sEndereco    = encodeURIComponent(tagString(this.oInputEndereco.value));
    oParametro.iNumero      = this.oInputEnderecoNumero.value;
    oParametro.sBairro      = encodeURIComponent(tagString(this.oInputBairro.value));
    oParametro.sComplemento = encodeURIComponent(tagString(this.oInputComplemento.value));

    oParametro.sUf          = '';
    if ($('oSelectUf').options[$('oSelectUf').selectedIndex].value != '') {
      oParametro.sUf = encodeURIComponent(tagString($('oSelectUf').options[$('oSelectUf').selectedIndex].getAttribute('sigla')));
    }

    oParametro.sMunicipio   = '';
    if (this.oSelectMunicipio.value != '') {
      oParametro.sMunicipio = encodeURIComponent(tagString(this.oSelectMunicipio.value));
    }
    oParametro.sCep         = encodeURIComponent(tagString(this.oInputCep.value));

    /**
     * Dados das grid de email e telefone
     */
    oParametro.aEmail    = this.getGridRows(this.oGridEmail.aRows, ['', 'sEmail', 'sPrincipal']);
    oParametro.aTelefone = this.getGridRows(this.oGridTelefone.aRows, aCamposTelefone);
    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = function(oResponse) {
                                                             oSelf.retornoSalvar(oResponse, oSelf, oParametro);
                                                           };
        
    js_divCarregando(_M('patrimonial.ouvidoria.DBViewCidadao_cidadao.salvando_dados'), "msgBox");
    new Ajax.Request(this.sUrlRpcCidadao, oDadosRequisicao);
  }
};

/**
 * Retorno do salvar
 * @param object oResponse - Retorno das informações do RPC
 * @param object oSelf     - 'this' da função responsável por chamar retornoSalvar
 */
DBViewCidadao.Cidadao.prototype.retornoSalvar = function(oResponse, oSelf, oParametro) {
  
  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');
  
  alert(oRetorno.sMensagem.urlDecode());
  
  oSelf.oDadosCidadao       = oParametro;
  oSelf.oDadosCidadao.sNome = decodeURIComponent(oParametro.sNome);
  if (oRetorno.iStatus == 1 && oSelf.lWindowAux) {
    
    oSelf.oDadosCidadao.iCidadao = oRetorno.iCodigoCidadao; 
    oSelf.oCallBackAposSalvar();
    oSelf.oWindowCidadao.destroy();
  }
};

/**
 * Após salvar os dados do cidadao, executa a função passada
 */
DBViewCidadao.Cidadao.prototype.aposSalvar = function (fFunction) {
  this.oCallBackAposSalvar = fFunction;
};

/**
 * Retorna um array com os campos e valores de uma grid
 * @param array aGridRows - aRows da Grid
 * @param array aCampos   - Campos da Grid que deseja incluir no array. São utilizados como índice
 * @returns {Array}
 */
DBViewCidadao.Cidadao.prototype.getGridRows = function(aGridRows, aCampos) {
  
  var aTemporario = new Array();
  
  aGridRows.each(function(oLinha) {
    
    var oTemporario = {};
    
    oLinha.aCells.each(function(oCell, iSeq) {
      
      if (!empty(aCampos[iSeq])) {
        
        oTemporario[aCampos[iSeq]] = oCell.getValue().trim();
        if ( aCampos[iSeq] == 'iRamal' || aCampos[iSeq] == 'sObservacoes' ) {
          oTemporario[aCampos[iSeq]] = encodeURIComponent(tagString(oCell.getValue().trim()));
        }
      }
    });
    
    aTemporario.push(oTemporario);
  });
  
  return aTemporario;
};

/**
 * Validações para consistência dos dados antes de salvar
 * 
 * @returns {Boolean}
 */
DBViewCidadao.Cidadao.prototype.validacoes = function () {
  
  if (empty(this.oInputNomeCidadao.value)) {
    
    alert(_M('patrimonial.ouvidoria.DBViewCidadao_cidadao.nome_obrigatorio'));
    return false;
  }
  
  if (this.lCpfObrigatorio && empty(this.oInputCPFCNPJ.value)) {
    
    alert(_M('patrimonial.ouvidoria.DBViewCidadao_cidadao.cpf_obrigatorio'));
    return false;
  }
  
  if (!empty(this.oInputCPFCNPJ.value) && !validaCpfCnpj(this.oInputCPFCNPJ)) {
    
    alert(_M('patrimonial.ouvidoria.DBViewCidadao_cidadao.cpfcnpj_invalido'));
    return false;
  }
  
  return true;
};

/**
 * Seta se o campo CPF é obrigatório
 * @param boolean lCpfObrigatorio
 */
DBViewCidadao.Cidadao.prototype.cpfObrigatorio = function (lCpfObrigatorio) {
  this.lCpfObrigatorio = lCpfObrigatorio;
};

/**
 * Seta um CPF
 * @param string sCnpfCnpj
 */
DBViewCidadao.Cidadao.prototype.setCpfCnpj = function (sCnpfCnpj) {
  this.oInputCPFCNPJ.value = sCnpfCnpj;
};

/**
 * Chama as funções necessárias para a construção da tela
 */
DBViewCidadao.Cidadao.prototype.show = function () {
  
  this.montaJanela();
  this.pesquisaEstados();
  
  if (!empty(this.iCodigo)) {
    
    this.getDados(this.iCodigo, false);
    this.oInputCodigoCidadao.value = this.iCodigo;
  }
};

/**
 * Seta um nome
 * @param string sNome
 */
DBViewCidadao.Cidadao.prototype.setNome = function ( sNome ) {
  this.oInputNomeCidadao.value = sNome;
};