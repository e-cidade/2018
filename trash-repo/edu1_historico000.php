<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);
$db_opcao      = 1;
$db_botao      = true;

$oDaoAluno         = db_utils::getDao("aluno");
$oDaoHistorico     = db_utils::getDao("historico");

$clrotulo      = new rotulocampo;
$oDaoAluno->rotulo->label();
$clrotulo->label("ed61_i_aluno");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">    
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js, windowAux.widget.js');
      db_app::load('dbmessageBoard.widget.js, dbcomboBox.widget.js, dbtextField.widget.js');
      db_app::load('estilos.css, grid.style.css');
    ?>
  </head>
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="left" valign="top" bgcolor="#CCCCCC">
        <center>
          <br></br>
          <form name="form1" method="post" action="">
            <table>
              <tr>
                <td nowrap title="<?=@$Ted47_i_codigo?>">
                  <?
                  db_ancora(@$Led61_i_aluno,"js_pesquisaed47_i_codigo(true);",$db_opcao);
                  ?>
                </td>
                <td>
                  <?
                  db_input('ed47_i_codigo',10,@$Ied47_i_codigo,true,'text',3,"");
                  db_input('ed47_v_nome',40,@$Ied47_v_nome,true,'text',3,"");
                  ?>
                </td>
              </tr>
              <tr>
              	<td colspan="2">
              		<input type="button" id='impHistorico'   name='impHistorico'   value='Emitir Histórico Escolar'
              			     onclick='js_emiteHistorico()' disabled="disabled"/>
            			<input type="button" id='impCertificado' name='impCertificado' value='Emitir Certificado de Conclusão'
            			       onclick='js_emiteCertificado()' disabled="disabled" />
              	</td>
              </tr>
            </table>
          </form>
          <fieldset style="width:99%;padding:0px;">
            <legend><b>Histórico do Aluno</b></legend>
            <table width="100%" border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td valign="top" width="42%">
                  <iframe src="edu1_historicoarvore.php?ed61_i_aluno=<?=@$ed47_i_codigo?>&ed47_v_nome=<?=@$ed47_v_nome?>" name="arvore" id="arvore" width="99%" height="230" frameborder="1" marginwidth="0" leftmargin="0" topmargin="0"></iframe>
                  <br>
                  <iframe src="" name="disciplina" id="disciplina" width="99%" height="230" frameborder="1" marginwidth="0" leftmargin="0" topmargin="0"></iframe>
                </td>
                <td align="right" valign="top">
                  <iframe src="" name="dados" id="dados" width="99%" height="462" frameborder="1" marginwidth="0" leftmargin="0" topmargin="0"></iframe>
                </td>
              </tr>
            </table>
          </fieldset>
        </center>
      </td>
    </tr>
    </table>
    <form name="hist" method="post" action="">
      <?db_input('ed47_i_codigo',10,@$ed47_i_codigo,true,'hidden',$db_opcao,"")?>
      <?db_input('ed47_v_nome',100,@$ed47_v_nome,true,'hidden',$db_opcao,"")?>
    </form>
  </body>
</html>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>

  var sUrlRPC = 'edu4_historicoaluno.RPC.php';

  function js_pesquisaed47_i_codigo(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_aluno','func_aluno.php?funcao_js=parent.js_mostraaluno1|ed47_i_codigo|ed47_v_nome','Pesquisa',true);
    }
  }
  function js_mostraaluno1(chave1,chave2){
    document.form1.ed47_i_codigo.value = chave1;
    document.form1.ed47_v_nome.value = chave2;
    db_iframe_aluno.hide();
    document.form1.submit();
  }

  /*
   * Função para carregar a tela de lançamento de Disciplinas
   */
  function js_lancarDisciplina(iCodigoHistoricoAno, iTipoHistorico, iEnsino, iHistoricomps, iCurso, iAnoReferencia) {

    aDisciplinasLancadas = new Array();
    iCodigoHistorico     = iCodigoHistoricoAno;
    iTipoHistoricoTurma  = iTipoHistorico;
    iEnsinoTurma         = iEnsino;
    iHistoricompsAluno   = iHistoricomps;
    iCodigoCurso         = iCurso;

    var iTamanhoJanela = document.body.getWidth()/1.3;
    oWindowDisciplinas = new windowAux('wndDisciplina', 'Lançar Disciplinas', iTamanhoJanela);
    var sConteudo      = "<div id='conteudo'>";
    sConteudo          += "  <fieldset>";
    sConteudo          += "    <legend>";
    sConteudo          += "      <b>Lançamento de Disciplina</b>";
    sConteudo          += "    </legend>";
    sConteudo          += "    <table>";
    sConteudo          += "      <tr style='display:none'>";
    sConteudo          += "        <td>";
    sConteudo          += "          <b>Código:</b>";
    sConteudo          += "        </td>";
    sConteudo          += "        <td id='ctnCodigo'>";
    sConteudo          += "        </td>";
    sConteudo          += "      </tr>";
    sConteudo          += "      <tr>";
    sConteudo          += "        <td>";
    sConteudo          += "          <a href='#' onclick='js_pesquisarDisciplina(true);return false'><b>Disciplina:</b></a>";
    sConteudo          += "        </td>";
    sConteudo          += "        <td>";
    sConteudo          += "          <span id='ctnCodigoDisciplina'>";
    sConteudo          += "          </span>";
    sConteudo          += "          <span  id='ctnDescricaoDisciplina'>";
    sConteudo          += "          </span>";
    sConteudo          += "        </td>";
    sConteudo          += "      </tr>";
    sConteudo          += "      <tr>";
    sConteudo          += "        <td>";
    sConteudo          += "          <b>Situação:</b>";
    sConteudo          += "        </td>";
    sConteudo          += "        <td id='ctnCboSituacao'>";
    sConteudo          += "        </td>";
    sConteudo          += "      </tr>";
    sConteudo          += "      <tr>";
    sConteudo          += "        <td>";
    sConteudo          += "          <b>Carga Horária:</b>";
    sConteudo          += "        </td>";
    sConteudo          += "        <td id='ctnTxtCargaHoraria'>";
    sConteudo          += "        </td>";
    sConteudo          += "      </tr>";
    sConteudo          += "      <tr id='ctnLinhaResultado'>";
    sConteudo          += "        <td>";
    sConteudo          += "          <b>Resultado:</b>";
    sConteudo          += "        </td>";
    sConteudo          += "        <td id='ctnCboResultado'>";
    sConteudo          += "        </td>";
    sConteudo          += "      </tr>";
    sConteudo          += "      <tr>";
    sConteudo          += "        <td>";
    sConteudo          += "          <b>Aproveitamento:</b>";
    sConteudo          += "        </td>";
    sConteudo          += "        <td id='ctnTxtAproveitamento'>";
    sConteudo          += "        </td>";
    sConteudo          += "      </tr>";
    sConteudo          += "      <tr>";
    sConteudo          += "        <td>";
    sConteudo          += "          <b>Termo Final:</b>";
    sConteudo          += "        </td>";
    sConteudo          += "        <td id='ctnTxtTermoFinal'>";
    sConteudo          += "        </td>";
    sConteudo          += "      </tr>";
    sConteudo          += "      <tr id='ctnLinhaJustificativa' style='visibility: hidden;'>";
    sConteudo          += "        <td>";
    sConteudo          += "          <a href='#' onclick='js_pesquisarJustificativa(true);return false'><b>Justificativas:</b></a>";
    sConteudo          += "        </td>";
    sConteudo          += "        <td>";
    sConteudo          += "          <span id='ctnTxtCodigoJustificativa'>";
    sConteudo          += "          </span>";
    sConteudo          += "          <span  id='ctnTxtDescricaoJustificativa'>";
    sConteudo          += "          </span>";
    sConteudo          += "        </td>";
    sConteudo          += "      </tr>";
    sConteudo          += "    </table>";
    sConteudo          += "  </fieldset>";
    sConteudo          += "  <center>";
    sConteudo          += "    <input id='btnSalvarDisciplina' type='button' value='Salvar'>";
    sConteudo          += "    <input id='btnLimparDadosDisciplina' type='button' value='Limpar Dados'>";
    sConteudo          += "  </center>";
    sConteudo          += "  <fieldset>";
    sConteudo          += "    <div id='ctnGridDisciplinas'>";
    sConteudo          += "    </div>";
    sConteudo          += "  </fieldset>";
    sConteudo          += "</div>";
    oWindowDisciplinas.setContent(sConteudo);
    oWindowDisciplinas.setShutDownFunction(function() {

      /**
       * validados os dados, caso a etapa esteja marcada como reprovada;
       * ao menos uma disciplina deve estar como Reprovada.
       */
      if (iTipoHistoricoTurma == 1) {
        sResultadoFinal     = dados.$F('ed62_c_resultadofinal');
      } else if (iTipoHistoricoTurma == 2) {
        sResultadoFinal     = dados.$F('ed99_c_resultadofinal');
      }
      var aDisciplinas               = oDataGridDisciplinas.aRows;
      if (sResultadoFinal == 'R' && aDisciplinas.length > 0) {

        var lPossuiDisciplinaReprovada = false;
        aDisciplinas.each(function(oDisciplina, iContador) {

          if (oDisciplina.aCells[5].getValue() == 'R') {
            lPossuiDisciplinaReprovada = true;
          }
        });
        if (!lPossuiDisciplinaReprovada) {

           alert('O resultado final da etapa é reprovado, verifique o campo resultado das disciplinas informando no mínimo '+
                 'uma como reprovado ou altere o resultado final da etapa'
                );
          return false;
        }
      }

      oWindowDisciplinas.destroy();
      dados.location.href = dados.location.href+'&lFechou=true&iQtdDisciplinas='+oDataGridDisciplinas.getRows().length;
      arvore.location.reload();
    });

    var oMessageBoard = new DBMessageBoard('messageboard1',
                                           'Lançamento de Histórico - Lançar Disciplinas',
                                           'Informe as disciplinas do aluno. Após terminar, clique em salvar.',
                                           oWindowDisciplinas.getContentContainer()
                                          );
    oMessageBoard.show();
    oWindowDisciplinas.show();

    oTxtCodigo = new DBTextField('oTxtCodigo', 'oTxtCodigo', '', '10');
    oTxtCodigo.setReadOnly(true);
    oTxtCodigo.show($('ctnCodigo'));

    oTxtCodigoDisciplina = new DBTextField('oTxtCodigoDisciplina', 'oTxtCodigoDisciplina', '', '10');
    oTxtCodigoDisciplina.addEvent('onChange', ";js_pesquisarDisciplina(false)");
    oTxtCodigoDisciplina.show($('ctnCodigoDisciplina'));

    oTxtDescricaoDisciplina = new DBTextField('oTxtDescricaoDisciplina', 'oTxtDescricaoDisciplina', '', '30');
    oTxtDescricaoDisciplina.setReadOnly(true);
    oTxtDescricaoDisciplina.show($('ctnDescricaoDisciplina'));

    oCboSituacao  = new DBComboBox('oCboSituacao', 'oCboSituacao');
    oCboSituacao.addItem('', '');
    oCboSituacao.addItem('CONCLUÍDO', 'CONCLUÍDO');
    oCboSituacao.addItem('AMPARADO', 'AMPARADO');
    oCboSituacao.addItem('NÃO OPTANTE', 'NÃO OPTANTE');
    oCboSituacao.addEvent('onChange', ";js_validarSituacao(false)");
    oCboSituacao.show($('ctnCboSituacao'));

    oCboResultado  = new DBComboBox('oCboResultado', 'oCboResultado');
    oCboResultado.addItem('', '');
    oCboResultado.show($('ctnCboResultado'));

    oTxtCargaHoraria = new DBTextField('oTxtCargaHoraria', 'oTxtCargaHoraria', '', '10');
    oTxtCargaHoraria.addStyle('background-color','#E6E4F1');
    oTxtCargaHoraria.show($('ctnTxtCargaHoraria'));

    oTxtAproveitamento = new DBTextField('oTxtAproveitamento', 'oTxtAproveitamento', '', '10');
    oTxtAproveitamento.show($('ctnTxtAproveitamento'));

    oDataGridDisciplinas               = new DBGrid('gridDisciplinas');
    oDataGridDisciplinas.sNameInstance = 'oDataGridDisciplinas';
    oDataGridDisciplinas.setCellWidth(new Array("15px", "15px", "75px", "75px", "15px", "25px", "25px", "25px"));
    oDataGridDisciplinas.setCellAlign(new Array("center","center",
                                                "left",
                                            	  "left",
                                            	  "right",
                                            	  "center",
                                            	  "right",
                                            	  "center"
                                            	 ));
    oDataGridDisciplinas.setHeader(new Array('Cod.','Cod. Disc.',
                                             'Disciplina',
                                             'Situação',
                                             'CH',
                                             'Resultado',
                                             'Aproveitamento',
                                             'Ação')
                                  );

    oDataGridDisciplinas.show($('ctnGridDisciplinas'));

    oTxtCodigoJustificativa = new DBTextField('oTxtCodigoJustificativa', 'oTxtCodigoJustificativa', '', '10');
    oTxtCodigoJustificativa.addEvent('onChange', ";js_pesquisarJustificativa(false)");
    oTxtCodigoJustificativa.show($('ctnTxtCodigoJustificativa'));

    oTxtDescricaoJustificativa = new DBTextField('oTxtDescricaoJustificativa', 'oTxtDescricaoJustificativa', '', '30');
    oTxtDescricaoJustificativa.setReadOnly(true);
    oTxtDescricaoJustificativa.show($('ctnTxtDescricaoJustificativa'));

    oTxtTermoFinal = new DBTextField('oTxtTermoFinal', 'oTxtTermoFinal', '', 10);
    oTxtTermoFinal.setMaxLength(4);
    oTxtTermoFinal.show($('ctnTxtTermoFinal'));

    $('btnSalvarDisciplina').observe('click', js_validarDadosDisciplina);
    $('btnLimparDadosDisciplina').observe('click', js_limparDadosDisciplina);

    js_buscarDisciplina();
    js_pesquisaTermos(iCodigoHistoricoAno, iEnsino, iAnoReferencia);
    js_limparDadosDisciplina();
  }

  /**
   * Pesquisamos os termos para apresentacao no comboBox Resultado
   */
  function js_pesquisaTermos(iCodigoHistoricoAno, iEnsino, iAnoReferencia) {

    var oParametro                 = new Object();
    oParametro.exec                = 'pesquisaTermos';
    oParametro.iCodigoHistoricoAno = iCodigoHistoricoAno;
    oParametro.iEnsino             = iEnsino;
    oParametro.iAnoReferencia      = iAnoReferencia
    var oAjax = new Ajax.Request(sUrlRPC,
                                 {
                                   asynchronous: false,
                                   method:     'post',
                                   parameters: 'json='+Object.toJSON(oParametro),
                                   onComplete: js_retornaPesquisaTermos
                                 }
                                );
  }

  function js_retornaPesquisaTermos(oResponse) {

    var oRetorno = eval('('+oResponse.responseText+')');
    if (oRetorno.status == 1 && oRetorno.aTermos.length > 0) {

      oRetorno.aTermos.each(function(oLinha, iContador) {

        oCboResultado.addItem(oLinha.sReferencia.urlDecode(), oLinha.sDescricao.urlDecode());
      });
    } else {

      oCboResultado.addItem('A', 'APROVADO');
      oCboResultado.addItem('R', 'REPROVADO');
      oCboResultado.addItem('P', 'APROVADO PARCIALMENTE');
    }
  }

  function js_pesquisarDisciplina(lMostrar) {

  	if (lMostrar) {

      js_OpenJanelaIframe('',
                          'db_iframe_disciplina',
                          'func_disciplina.php?ensino='+iEnsinoTurma+
                          '&funcao_js=parent.js_mostrarDisciplinas|ed12_i_codigo|ed232_c_descr',
                          'Pesquisar Disciplinas',
                           true
                          );
      $('Jandb_iframe_disciplina').style.zIndex = 10000;
  	} else {

      if (oTxtCodigoDisciplina.getValue() != "") {

     	  js_OpenJanelaIframe('',
                            'db_iframe_disciplina',
                            'func_disciplina.php?pesquisa_chave='+oTxtCodigoDisciplina.getValue()+
                            '&ensino='+iEnsinoTurma+
                            '&funcao_js=parent.js_mostrarDisciplinasErro',
                            'Pesquisar Disciplinas',
                             false
                          );

      } else {
         oTxtDescricaoDisciplina.setValue('');
      }
  	}
  }

  function js_mostrarDisciplinas(iCodigo, sDescricao) {

  	oTxtCodigoDisciplina.setValue(iCodigo);
  	oTxtDescricaoDisciplina.setValue(sDescricao);
  	db_iframe_disciplina.hide();
  }

  function js_mostrarDisciplinasErro(sDescricao, lErro) {

 	  oTxtDescricaoDisciplina.setValue(sDescricao);
  	if (lErro) {
  	  oTxtCodigoDisciplina.setValue('');
  	}
  }

  /*
   * Função para mostrar/ocultar campos de acordo com a situação
   */
  function js_validarSituacao() {

    if (oCboSituacao.getValue() == 'AMPARADO') {

    	oCboResultado.setDisable();
    	oCboResultado.setValue('');
    	oTxtAproveitamento.setReadOnly(true);
    	oTxtAproveitamento.setValue('');
    	oTxtCodigoJustificativa.setValue('');
    	oTxtDescricaoJustificativa.setValue('');
    	oTxtTermoFinal.setValue('');
    	oTxtTermoFinal.setReadOnly(true);
    	$('ctnLinhaJustificativa').style.visibility = 'visible';
    } else if (oCboSituacao.getValue() == 'NÃO OPTANTE') {

      oTxtCargaHoraria.setReadOnly(true);
      oCboResultado.setDisable(true);
      oTxtTermoFinal.setReadOnly(false);
      $('ctnLinhaJustificativa').style.visibility = 'hidden';
    } else {

      oCboResultado.setEnable();
      oTxtAproveitamento.setReadOnly(false);
      oTxtTermoFinal.setReadOnly(false);
      $('ctnLinhaJustificativa').style.visibility = 'hidden';
    }
  }

  /*
   * Função para validação dos dados da disciplina
   */
  function js_validarDadosDisciplina() {

  	if (iTipoHistoricoTurma == 1) {

    	sNotaMinima         = dados.$F('ed62_c_minimo');
  	  sResultadoFinal     = dados.$F('ed62_c_resultadofinal');
  	}

  	if (iTipoHistoricoTurma == 2) {

    	sNotaMinima         = dados.$F('ed99_c_minimo');
  	  sResultadoFinal     = dados.$F('ed99_c_resultadofinal');
  	}

  	var lDadosValidados     = true;

  	if (oTxtCodigoDisciplina.getValue() == '') {

      alert ('Deve ser informada a disciplina');
      lDadosValidados = false;
      return false;
  	}

  	if (oCboSituacao.getValue() == '') {

  		alert ('Deve ser informada a situação da disciplina');
  		lDadosValidados = false;
      return false;
  	}

    if (oCboSituacao.getValue() == 'CONCLUÍDO') {

    	if (oCboResultado.getValue() == '') {

    		alert ('Deve ser informado o resultado da disciplina');
    		lDadosValidados = false;
        return false;
    	}

    	if (oTxtAproveitamento.getValue() == '') {

    		alert ('Deve ser informado o aproveitamento para a disciplina');
    		lDadosValidados = false;
        return false;
    	}
    }

  	if (oCboSituacao.getValue() == 'AMPARADO') {

    	if (oTxtCodigoJustificativa.getValue() == '') {

    		alert ('Deve ser informada justificativa para amparo na disciplina');
    		lDadosValidados = false;
        return false;
    	}
  	}
    if (sResultadoFinal == 'A'  && oCboSituacao.getValue() != 'AMPARADO' && oCboSituacao.getValue() != 'NÃO OPTANTE') {

    	if (oCboResultado.getValue() != 'A') {

        alert ('O resultado final da etapa é Aprovado. Portanto, o resultado da disciplina deve estar como Aprovado.');
        lDadosValidados = false;
        return false;
    	}

    	if (oTxtAproveitamento.getValue() < parseFloat(sNotaMinima)) {

    		alert ('Situação está como Aprovado, mas a nota informada é menor que o mínimo para aprovação: '+sNotaMinima);
    		lDadosValidados = false;
        return false;
    	}
    }

    if (oCboResultado.getValue() == 'R') {
    	if (oTxtAproveitamento.getValue() > parseFloat(sNotaMinima)) {

    		alert ('Situação está como Reprovado, mas a nota informada é maior ou igual ao mínimo para aprovação: '+sNotaMinima);
    		lDadosValidados = false;
        return false;
    	}
    }

  	if (lDadosValidados == true) {
      js_incluirDisciplina();
  	}
  }

  function js_pesquisarJustificativa(lMostra) {

    if (lMostra) {

      js_OpenJanelaIframe(
                          '',
                          'db_iframe_justificativa',
                          'func_justificativa.php?funcao_js=parent.js_mostrarJustificativa|ed06_i_codigo|ed06_c_descr',
                          'Pesquisar Justificativa',
                          true
                         );
      $('Jandb_iframe_justificativa').style.zIndex = 10000;
    } else {

    	if (oTxtCodigoJustificativa.getValue() != "") {

    		js_OpenJanelaIframe(
            '',
            'db_iframe_justificativa',
            'func_justificativa.php?pesquisa_chave='+oTxtCodigoJustificativa.getValue()+
            '&funcao_js=parent.js_mostrarJustificativaErro',
            'Pesquisar Justificativa',
            false
           );
    	}
    }
  }

  function js_mostrarJustificativa(iCodigo, sDescricao) {

  	oTxtCodigoJustificativa.setValue(iCodigo);
  	oTxtDescricaoJustificativa.setValue(sDescricao);
  	db_iframe_justificativa.hide();
  }

  function js_mostrarJustificativaErro(sDescricao, lErro) {

  	oTxtDescricaoJustificativa.setValue(sDescricao);
  	if (lErro) {
      oTxtCodigoJustificativa.setValue('');
  	}
  }

  /*
   * Função para buscar as disciplinas vinculadas ao aluno
   */
  function js_buscarDisciplina() {

  	var oParametro                 = new Object();
  	oParametro.exec                = 'getDisciplinasHistorico';
  	oParametro.iCodigoHistoricoAno = iCodigoHistorico;
  	oParametro.iTipoHistorico      = iTipoHistoricoTurma;

  	var oAjax = new Ajax.Request(
  	  	                         sUrlRPC,
  	  	                         {
    	  	                         method:     'post',
    	  	                         parameters: 'json='+Object.toJSON(oParametro),
    	  	                         onComplete: js_preencherDisciplinas
  	  	                         }
  	  	                        );
  }

  /*
   * Função para preencher o DBGrid com as disciplinas
   */
  function js_preencherDisciplinas(oResponse) {

  	var oRetorno         = eval('('+oResponse.responseText+')');
  	oDataGridDisciplinas.clearAll(true);
  	aDisciplinasLancadas = oRetorno.disciplinas;
  	oRetorno.disciplinas.each(function (oLinha, iContador) {

      var aLinha = new Array();
      aLinha[0]   = oLinha.codigo;
      aLinha[1]   = oLinha.codigo_disciplina;
      aLinha[2]   = oLinha.descricao_disciplina.urlDecode();
      aLinha[3]   = oLinha.situacao.urlDecode();
      aLinha[4]   = oLinha.carga_horaria;
      aLinha[5]   = oLinha.resultado_final.urlDecode();
      aLinha[6]   = oLinha.resultado_obtido;
      aLinha[7]   = '<input type="button" value="A" onclick="js_carregaDadosDisciplina('+oLinha.codigo+')" />';
      aLinha[7]  += '<input type="button" value="E" onclick="js_excluirDisciplina('+oLinha.codigo+')" />';
      oDataGridDisciplinas.addRow(aLinha);
  	});

  	oDataGridDisciplinas.renderRows();
  }

  /*
   * Função para incluir uma disciplina
   */
  function js_incluirDisciplina() {

    var iCodigoDisciplina  = oTxtCodigoDisciplina.getValue();
    var iJustificativa     = '';
    var iCargaHoraria      = oTxtCargaHoraria.getValue();
    var iResultado         = oCboResultado.getValue();
    var iAproveitamento    = oTxtAproveitamento.getValue();
    var iSituacao          = oCboSituacao.getValue();
    var sTipoResultado     = 'N';
    var iOrdenacao         = 0;
    var iCodigoLancamento  = oTxtCodigo.getValue();
    var sTermoFinal        = oTxtTermoFinal.getValue();

    if (iSituacao == 'AMPARADO') {

    	sTipoResultado = 'A';
    	iJustificativa = oTxtCodigoJustificativa.getValue();
    }

  	js_divCarregando("Aguarde, salvando dados da disciplina", "msgBox");
  	var oParametro                 = new Object();
  	oParametro.exec                = 'incluirDisciplinaHistorico';
  	oParametro.iCodigoAluno        = $F('ed47_i_codigo');
  	oParametro.iCodigoHistoricoAno = iCodigoHistorico;
  	oParametro.iTipoHistorico      = iTipoHistoricoTurma;
  	oParametro.iHistoricomps       = iHistoricompsAluno;
  	oParametro.iCodigoDisciplina   = iCodigoDisciplina;
  	oParametro.iJustificativa      = iJustificativa;
  	oParametro.iCargaHoraria       = iCargaHoraria;
  	oParametro.iResultado          = iResultado;
  	oParametro.iAproveitamento     = iAproveitamento;
  	oParametro.iSituacao           = encodeURIComponent(tagString(iSituacao));
  	oParametro.sTipoResultado      = sTipoResultado;
  	oParametro.iOrdenacao          = iOrdenacao;
  	oParametro.iCodigoLancamento   = iCodigoLancamento;
  	oParametro.iCodigoCurso        = iCodigoCurso;
  	oParametro.sTermoFinal         = sTermoFinal;

  	var oAjax = new Ajax.Request(
  	  	                         sUrlRPC,
  			                         {
    			                         method:     'post',
    			                         parameters: 'json='+Object.toJSON(oParametro),
    			                         onComplete: js_salvarDisciplina
  			                         }
  	  	                        );
  }

  /*
   * Função que salva a Disciplina
   */
  function js_salvarDisciplina (oResponse) {

  	js_removeObj("msgBox");
  	var oRetorno = eval("("+oResponse.responseText+")");
  	alert(oRetorno.message.urlDecode());
  	if (oRetorno.status == 1) {

  		js_limparDadosDisciplina();
  	  js_buscarDisciplina();
  	}
  }

function js_excluirDisciplina(iCodigoDisciplina) {

  if (confirm('Confirma a exclusão da disciplina?')) {

    js_divCarregando("Aguarde, excluindo disciplina.", "msgBox");
    var oParametro                 = new Object();
    oParametro.exec                = 'excluirDisciplinaHistorico';
    oParametro.iCodigoHistoricoAno = iCodigoHistorico;
    oParametro.iTipoHistorico      = iTipoHistoricoTurma;
    oParametro.iHistoricomps       = iHistoricompsAluno;
    oParametro.iDisciplina         = iCodigoDisciplina;
    oParametro.iCodigoAluno        = $F('ed47_i_codigo');
    oParametro.iCodigoCurso        = iCodigoCurso;
    var oAjax = new Ajax.Request(
                                 sUrlRPC,
                                 {
                                   method:     'post',
                                   parameters: 'json='+Object.toJSON(oParametro),
                                   onComplete: function (oResponse) {

                                     js_removeObj('msgBox');
                                     var oRetorno = eval("("+oResponse.responseText+")");
                                     alert(oRetorno.message.urlDecode());
                                     if (oRetorno.status == 1) {
                                       js_buscarDisciplina();
                                     }
                                   }
                                 }
                                );
  }
}

function js_carregaDadosDisciplina(iCodigoLancamento) {

	var iCodigoDisciplina     = oTxtCodigoDisciplina.getValue();
	var iSequencialDisciplina = oTxtCodigo.getValue();

	var oParametro                 = new Object();
	oParametro.exec                = 'carregaDadosDisciplina';
	oParametro.iCodigo             = iCodigoLancamento;
	oParametro.iCodigoHistoricoAno = iCodigoHistorico;
  oParametro.iTipoHistorico      = iTipoHistoricoTurma;
  oParametro.iHistoricomps       = iHistoricompsAluno;
  oParametro.iDisciplina         = iCodigoDisciplina;
  oParametro.iCodigoAluno        = $F('ed47_i_codigo');
  oParametro.iCodigoCurso        = iCodigoCurso;
  var oAjax = new Ajax.Request(
                               sUrlRPC,
                               {
                                method: 'post',
                                parameters: 'json='+Object.toJSON(oParametro),
                                onComplete: js_mostraDados
                               }
  	                          );
}

function js_mostraDados(oResponse) {

	var oRetorno = eval("("+oResponse.responseText+")");
	if (oRetorno.status == 1) {

		oTxtCodigoDisciplina.setValue(oRetorno.oDisciplina.iCodigoDisciplina);
		oTxtDescricaoDisciplina.setValue(oRetorno.oDisciplina.sDescricaoDisciplina.urlDecode());
		oTxtCargaHoraria.setValue(oRetorno.oDisciplina.iCargaHoraria);
    oCboResultado.setValue(oRetorno.oDisciplina.sResultado);
    oTxtCodigo.setValue(oRetorno.oDisciplina.iCodigoLancamento);
    oCboSituacao.setValue(oRetorno.oDisciplina.sSituacao.urlDecode());
		oTxtAproveitamento.setValue(oRetorno.oDisciplina.nAproveitamento);
		oTxtTermoFinal.setValue(oRetorno.oDisciplina.sTermoFinal);
		js_validarSituacao();
		oTxtCodigoJustificativa.setValue(oRetorno.oDisciplina.iJustificativa);
		if (oRetorno.oDisciplina.iJustificativa != "") {
		  js_pesquisarJustificativa(false)
		}
	}
}

function js_limparDadosDisciplina() {

	oTxtCodigo.setValue('');
	oTxtCodigoDisciplina.setValue('');
	oTxtDescricaoDisciplina.setValue('');
	oCboSituacao.setValue('');
	oTxtCargaHoraria.setValue('');
	oCboResultado.setValue('');
	oTxtAproveitamento.setValue('');
	oTxtCodigoJustificativa.setValue('');
	oTxtDescricaoJustificativa.setValue('');
	oTxtTermoFinal.setValue('');

	js_preencherDadosPadrao();
}

function js_emiteHistorico() {

  var sUrl = "edu2_historico001.php";
  sUrl    += "?ed47_i_codigo="+$F('ed47_i_codigo');
  sUrl    += "&ed47_v_nome="+$F('ed47_v_nome');

  js_OpenJanelaIframe('',
                      'db_iframe_justificativa',
                      sUrl,
                      'Emitir Histórico',
                      true
                     );

}


function js_validaEmissaoCertificado() {

  var oParam        = new Object();
  oParam.exec       = "validaEmissaoCertificado";
  oParam.iAluno     = $F('ed47_i_codigo')
  var sUrl           = 'edu4_historicoaluno.RPC.php';

  new Ajax.Request(sUrl,
                   {method: 'post',
                    asynchronous: false,
                    parameters: 'json='+Object.toJSON(oParam),
                    onComplete: function (oAjax) {

                                  var oRetorno = eval('('+oAjax.responseText+')');

                                  $('impCertificado').disabled = false;
																	if (!oRetorno.lPermiteImpressao) {
																		$('impCertificado').disabled = true;
																	}
                                }
                   }
                  );
}

function js_emiteCertificado() {

  var sUrl = "edu2_certconclusao001.php";
  sUrl    += "?ed47_i_codigo="+$F('ed47_i_codigo');
  sUrl    += "&ed47_v_nome="+$F('ed47_v_nome');

  js_OpenJanelaIframe('',
                      'db_iframe_justificativa',
                      sUrl,
                      'Emitir Certificado',
                      true
                     );
}

var lAlunoTemHistorico = false;

function js_validaAluno() {

	if ($F('ed47_i_codigo') == '') {

		alert('Selecione um aluno.');
		return false;
	}

	var oParam        = new Object();
  oParam.exec       = "alunoTemHistorico";
  oParam.iAluno     = $F('ed47_i_codigo')
  var sUrl           = 'edu4_historicoaluno.RPC.php';

  new Ajax.Request(sUrl,
                   {method: 'post',
                    asynchronous: false,
                    parameters: 'json='+Object.toJSON(oParam),
                    onComplete: function (oAjax) {

                                  var oRetorno = eval('('+oAjax.responseText+')');

                                  $('impHistorico').disabled   = false;
																	if (!oRetorno.lTemHistorico) {
																		$('impHistorico').disabled   = true;
																	}
																	lAlunoTemHistorico = oRetorno.lTemHistorico;
                                }
                   }
                  );
}

function js_preencherDadosPadrao() {

  var oParam          = new Object();
  oParam.exec         = "getDadosEtapa";
  oParam.sTipoEtapa   = this.dados.$F("sTipoRede");

  if (oParam.sTipoEtapa == 1) {
    oParam.iCodigoEtapa = this.dados.$F('ed62_i_codigo');
  } else {
    oParam.iCodigoEtapa = this.dados.$F('ed99_i_codigo');
  }

  var sUrl            = 'edu4_historicoaluno.RPC.php';
  new Ajax.Request(sUrl,
                  {method: 'post',
                   asynchronous: false,
                   parameters: 'json='+Object.toJSON(oParam),
                   onComplete: function (oAjax) {

                                  var oRetorno = eval('('+oAjax.responseText+')');

                                  
                                  oCboSituacao.getElement().value  = oRetorno.oEtapa.sSituacao.urlDecode();
                                  
                                  if ( oRetorno.oEtapa.sSituacao.urlDecode() == 'RECLASSIFICADO' ) {
                                    oCboSituacao.getElement().value = 'CONCLUÍDO';
                                  }
                                  oCboResultado.getElement().value = oRetorno.oEtapa.sResultado.urlDecode();
                                }
                   }
  );

  return true;
}

var lPrimeiroAcessoView = true;

if ($F('ed47_i_codigo') !='') {

  js_validaAluno();
  if (lAlunoTemHistorico) {
		js_validaEmissaoCertificado();
  }
}
</script>