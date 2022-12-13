<?php
/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

$oGet  = db_utils::postMemory($_GET);
$oRotulo  = new rotulocampo;
$oRotulo->label('z01_nome');
$oRotulo->label('nome');
$oRotulo->label('id_usuario');
$oRotulo->label('login');

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("estilos.css");
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("DBToggleList.widget.js");
      db_app::load("AjaxRequest.js");
      db_app::load("prototype.js"); 
    ?>
  </head>
  <body class="body-default">

    <form class="container" id="form1" name="form1" method="post">

      <fieldset>

        <legend>Lota��es:</legend>
        <table>
          <tr>
            <td width="105">
              <label><?php echo $Lnome; ?></label>
            </td>
            <td>
              <?php 
                db_input("iCodigoUsuario", 6, 1, true, 'text', 3) ; 
                db_input("nome",  50, 1, true, 'text', 3) ; 
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <label><strong>Insitui��o:</strong></label>
            </td>
            <td>
              <?php 

                $sSqlInstituicoes  = "select id_instit,nomeinst from db_config ";
                $sSqlInstituicoes .= " inner join db_userinst on id_instit  = codigo";
                $sSqlInstituicoes .= "                       and id_usuario = {$iCodigoUsuario}";
                                                            
                $rsInstituicoes = db_query($sSqlInstituicoes);

                db_selectrecord('instit', $rsInstituicoes, true, 1, 1);
              ?>
            </td>
          </tr>

          <?php for($iLotacao = 0; $iLotacao < pg_num_rows($rsInstituicoes); $iLotacao++) { 
                  
            $oLotacao = db_utils::fieldsMemory($rsInstituicoes, $iLotacao);
          ?>
            <tr>
              <td colspan="2">
                <div class="instituicoes" id="instit_<?=$oLotacao->id_instit?>"></div>
              </td>
            </tr>
          <?php } ?>

        </table>
      </fieldset>
      <input type="button" value="Alterar" onclick="salvarDados()" />
    </form>
  </body>
</html>

<script>

/**
 * C�digo do Usu�rio que esta sendo criado/alterado
 * @type integer
 */
var iCodigoUsuario = <?=$iCodigoUsuario?>;

/**
 * Intancias DBToggleList
 * @type Array
 */
var aInstancias = {};

(function(){

  /**
   * busca as lota��es dipon�veis e selecionadas para todas 
   * as institui��es que o usu�rio pode ter acesso.
   */
  var oRequisicao = new AjaxRequest('con1_permissaolotacao.RPC.php', {exec: 'carregarLotacoes', iCodigoUsuario: iCodigoUsuario});
  oRequisicao.setCallBack(fCallBackLotacoes);
  oRequisicao.execute();

  /**
   * Tratamento do evento change do  campo Instiui��o.
   */
  changeInstituicao($('instit').value);

  $('instit').observe('change', function(){
    changeInstituicao($(this).value);
  });

  $('institdescr').observe('change', function(){
    changeInstituicao($(this).value);
  });

})();

/**
 * Exibe as lota��es disponiveis e selecionadas, 
 * de acordo com a intitui��o selecionada no combo
 * 
 * @param  integer iInstituicao
 * @return boolean
 */
function changeInstituicao(iInstituicao){

  $$('.instituicoes').each(function(obj){
    $(obj).hide();
  });

  $('instit_' + iInstituicao).show();
}

/**
 *
 * Adiciona ao ToggleList as lota��es dispon�veis para  a institui��o informada como par�metro
 * 
 * @param  Array aLotacoes Lota��es dispon�veis para esta institui��o.
 * @param  integer iInstituicao c�digo da institui��o selecionada
 * @return boolean
 */
function montaDisponiveis(aLotacoes, iInstituicao) {

  var oToggleList = aInstancias[iInstituicao];

  for (var iLotacao = 0; iLotacao < aLotacoes.length; iLotacao++) {

    oLotacao = aLotacoes[iLotacao];
    oToggleList.addSelect({estrutural: oLotacao.estrutural, descricao: oLotacao.descricao, id: oLotacao.codigo});
  }
}

/**
 * Adiciona ao ToggleList as lota��es selecionadas para a institui��o informada como par�metro
 * 
 * @param  Array aLotacoes Lota��es selecionadas para esta institui��o.
 * @param  integer iInstituicao c�digo da institui��o selecionada
 * @return boolean
 */
function montaSelecionados(aLotacoes, iInstituicao) {

  var oToggleList = aInstancias[iInstituicao];

  for (var iLotacao = 0; iLotacao < aLotacoes.length; iLotacao++) {
    
    oLotacao = aLotacoes[iLotacao];
    oToggleList.addSelected({estrutural: oLotacao.estrutural, descricao: oLotacao.descricao, id: oLotacao.codigo});
  }
}

/**
 * Callback da requisi��o Ajax utilizada para montar os 
 * DBToggleList com as lota��es para cada uma das intitui��es.
 * 
 * @param  Object oRetorno retorno da requisi��o Ajax
 * @return boolean
 */
function fCallBackLotacoes(oRetorno) {

  var aColunas = [
    {
      sWidth : '60px',
      sLabel : 'Estrutural',
      sId    : 'estrutural',
      sAlign : 'left'
    },
    {
      sWidth : '300px',
      sLabel : 'Descri��o',
      sId    : 'descricao',
      sAlign : 'left'
    },
    {
      sId    : 'id',
      lVisible: false
    }
  ];

  var sCampos = {'selecao': "Lota��es Dispon�veis:", "selecionados":"Lota��es Selecionadas:"};

  /**
   * Percorre as institui��es realizando a cria��o do DBToggleList quando a mesmo possui lota��es cadastradas, 
   * caso n�o possua � adicionando uma mensagem de aviso.
   */
  for (iInstituicao = 0; iInstituicao < oRetorno.aInstituicoes.length; iInstituicao++) {

    var iCodigoInstituicao = oRetorno.aInstituicoes[iInstituicao],
    aLotacoesInstituicoes  = oRetorno.aLotacoesInstituicoes[iCodigoInstituicao] || [],
    aLotacoesUsuario       = oRetorno.aLotacoesUsuario[iCodigoInstituicao] || [];

    if (aLotacoesInstituicoes.length > 0 || aLotacoesUsuario.length > 0) {

      aInstancias[iCodigoInstituicao] = new DBToggleList( aColunas,  sCampos);
      aInstancias[iCodigoInstituicao].lViewButtonOrder = false;
    } else {
      $('instit_' + iCodigoInstituicao).update('Nenhuma lota��o cadastrada para esta institui��o.');
    }

    if(aLotacoesInstituicoes.length > 0) {
      montaDisponiveis(oRetorno.aLotacoesInstituicoes[iCodigoInstituicao], iCodigoInstituicao);
    }

    if (aLotacoesUsuario.length > 0) {
      montaSelecionados(oRetorno.aLotacoesUsuario[iCodigoInstituicao], iCodigoInstituicao);
    }

    if (aLotacoesInstituicoes.length > 0 || aLotacoesUsuario.length > 0) {
      aInstancias[iCodigoInstituicao].show($('instit_' + iCodigoInstituicao));
    }
  }
}

/**
 * Salva as lota��es selecionadas papra cada uma das institui��es.
 * 
 * @return boolean
 */
function salvarDados() {

  aLotacoesSelecionadas = [];
  
  /**
   * Realiza o tratamento de todas as lota��es selecionadas nos TogglesLists
   */
  for (iInstituicao in aInstancias) {

    var oInstancia = aInstancias[iInstituicao];
    if (oInstancia.getSelected().length > 0) {

      var aLotacoes         = oInstancia.getSelected();
      for ( var iLotacao = 0; iLotacao < aLotacoes.length; iLotacao++) {
        aLotacoes[iLotacao].descricao = aLotacoes[iLotacao].descricao.urlEncode();
      }
      aLotacoesSelecionadas = aLotacoesSelecionadas.concat(aLotacoes);
    }
  }
  
  var oRequisicao = new AjaxRequest('con1_permissaolotacao.RPC.php', { exec: 'salvarLotacoes', 
                                                                       iCodigoUsuario: iCodigoUsuario,
                                                                       aLotacoesSelecionadas: aLotacoesSelecionadas
                                                                     }
                                   );

  oRequisicao.setCallBack(function(oRetorno, lErro) {

    alert(oRetorno.message.urlDecode());

    if (oRetorno.  erro){
      return false;
    }
    return true;
  });

  oRequisicao.execute();
}
</script>
