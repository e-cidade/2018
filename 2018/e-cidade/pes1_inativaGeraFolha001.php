<?
/*
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
  require_once("libs/db_utils.php");
  require_once("libs/db_usuariosonline.php");
  require_once("dbforms/db_funcoes.php");
  require_once("classes/db_rhgeracaofolha_classe.php");
  
  $oRhGeracaoFolha = new cl_rhgeracaofolha(); 
  $oRotulo = new rotulocampo();
  $oRotulo->label('rh102_sequencial');
  $oRotulo->label('rh102_descricao');
  $oRotulo->label('rh102_anousu');
  $oRotulo->label('rh102_mesusu');
  $oRotulo->label('rh103_tipofolha');
  
  $oPost = db_utils::postMemory($HTTP_POST_VARS);

  if ( isset($oPost->btnInativar) ) {

    $lSqlErro = false;
    $sMsgErro = "Operação realizada com sucesso!";

    db_inicio_transacao();

    $oRhGeracaoFolha->rh102_ativo      = "false";
    $oRhGeracaoFolha->rh102_sequencial = $oPost->rh102_sequencial;
    $oRhGeracaoFolha->alterar($oPost->rh102_sequencial);

    if ( $oRhGeracaoFolha->erro_status == "0" ) {
      $lSqlErro = true;
      $sMsgErro = $oRhGeracaoFolha->erro_msg;
    }
    
    db_fim_transacao($lSqlErro);
  }
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="">
<br /><br /><br />

<form name="form1" method="post" onsubmit="return js_inativar();">
  <center>
    <fieldset style="width: 500px; padding: 10px;">
      <legend><strong>Geração de Folha</strong></legend>
      <table width="100%">
        <tr>
          <td title="<?=$Trh102_sequencial;?>" width="100px">
            <b>Sequencial:</b>
          </td>
          <td>
            <?
              db_input("rh102_sequencial", 10, $Irh102_sequencial, true, "text", 3);
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=$Trh102_descricao;?>">
            <b>Descrição:</b>
          </td>
          <td>
            <?
              db_input("rh102_descricao", 50, $Irh102_descricao, true, "text", 3);
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=$Trh102_anousu." - ".$Trh102_mesusu;?>">
            <b>Ano / Mês:</b>
          </td>
          <td>
            <?
              db_input("rh102_anousu", 5, $Irh102_anousu, true, "text", 3);
              echo " / ";
              db_input("rh102_mesusu", 1, $Irh102_mesusu, true, "text", 3);
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=$Trh103_tipofolha." - ".$Trh103_tipofolha;?>">
            <b>Tipo de folha:</b>
          </td>
          <td>
            <?
              db_input("rh103_tipofolha", 50, $Irh102_anousu, true, "text", 3);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <p align="center">
      <input type="submit" name="btnInativar" id="btnInativar" value="Inativar" />
      &nbsp;&nbsp;
      <input type="button" name="btnPesquisar" id="btnPesquisar" value="Pesquisar" onclick="js_pesquisar();" />    
    </p>
  </center>
</form>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>

  (function(){

    /**
     * Chama a função js_pesquisar para abrir 
     * a lookup de pesquisa.
     */
    js_pesquisar();
  })()

  /**
   *  Abre Pesquisa
   */
  function js_pesquisar() {
    js_OpenJanelaIframe('top.corpo','db_iframe_rhgeracaofolha','func_rhgeracaofolha.php?ativas=true&funcao_js=parent.js_preenchePesquisa|rh102_sequencial|rh102_descricao|rh102_mesusu|rh102_anousu|rh103_tipofolha','Pesquisa',true);
  }
  
  /**
   * Efetua o processamento
   */
  function js_inativar() {
    
    if ( $('rh102_sequencial').value == "" || $('rh102_descricao').value == "" || $('rh102_mesusu').value == "" || $('rh102_anousu').value == "" || $('rh103_tipofolha').value == "") {
    
      alert("Por favor, preencha o formulário corretamente.");
      return false;
    } else {
      if ( !confirm("Confirma a operação?") ) {
        return false;
      }      
    }
  }  
  
  /**
   * Esta função preenche os dados do formulário com os dados buscados na lookup
   */
  function js_preenchePesquisa(iSequencial, sDescricao, iMesUso, iAnoUso, rh103_tipofolha) {
  
    $('rh102_sequencial').value = iSequencial;
    $('rh102_descricao').value  = sDescricao;
    $('rh102_mesusu').value     = iMesUso;
    $('rh102_anousu').value     = iAnoUso;
    $('rh103_tipofolha').value = rh103_tipofolha;
    $('btnInativar').enable();
    db_iframe_rhgeracaofolha.hide();
  }
  
  
  /**
   *   Inicia a rotina com os valores zerados. Apenas por segurança!
   */
  $('rh102_sequencial').value = "";
  $('rh102_descricao').value  = "";
  $('rh102_mesusu').value     = "";
  $('rh102_anousu').value     = "";
  $('rh103_tipofolha').value = "";
  $('btnInativar').disable();
</script>

<?
  if ( isset($oPost->btnInativar) ) {
    db_msgbox($sMsgErro);
  }
?>