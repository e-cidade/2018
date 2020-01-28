<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_orcindicaindiceesperado_classe.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clorcindiceesperado      = new cl_orcindicaindiceesperado();
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir();

$clorcindiceesperado->rotulo->label();

$lSqlErro = false;
$sErroMsg = '';

/**
 * Valida se não há registro cadastrado para o exercício informado no mesmo Indicador
 * @param $oPost
 * @return bool
 * @throws BusinessException
 * @throws DBException
 */
function validaExercicio($oPost) {

  if(empty($oPost->o25_orcindica) || empty($oPost->o25_anousu)) {
    return false;
  }

  $oDaoOrcIndicaIndice   = new cl_orcindicaindiceesperado();
  $sWhereOrcIndicaIndice = "o25_orcindica = {$oPost->o25_orcindica} AND o25_anousu = {$oPost->o25_anousu}";
  $sSqlOrcIndicaIndice   = $oDaoOrcIndicaIndice->sql_query_file(null, 'o25_orcindica', null, $sWhereOrcIndicaIndice);
  $rsOrcIndicaIndice     = db_query($sSqlOrcIndicaIndice);

  if(!$rsOrcIndicaIndice) {
    throw new DBException('Erro ao validar o ano informado para o índice esperado.');
  }

  if(pg_num_rows($rsOrcIndicaIndice) > 0) {

    $iSequencialOrcIndica = db_utils::fieldsMemory($rsOrcIndicaIndice, 0)->o25_orcindica;

    if($oPost->o25_orcindica == $iSequencialOrcIndica && isset($oPost->incluir)) {
      throw new BusinessException("Índice no exercício {$oPost->o25_anousu} já cadastrado para o Indicador.");
    }
  }

  return true;
}

try {

  if (isset($oPost->incluir)) {

    validaExercicio($oPost);

    db_inicio_transacao();

    $clorcindiceesperado->o25_anousu = $oPost->o25_anousu;
    $clorcindiceesperado->o25_orcindica = $oPost->o25_orcindica;
    $clorcindiceesperado->o25_valor = $oPost->o25_valor;

    $clorcindiceesperado->incluir(null);

    if ($clorcindiceesperado->erro_status == 0) {

      $lSqlErro = true;
      throw new DBException($clorcindiceesperado->erro_msg);
    }

    db_msgbox($clorcindiceesperado->erro_msg);
    db_fim_transacao($lSqlErro);
  }

  if (isset($oPost->alterar)) {

    validaExercicio($oPost);

    db_inicio_transacao();

    $clorcindiceesperado->o25_sequencial = $oPost->o25_sequencial;
    $clorcindiceesperado->o25_anousu = $oPost->o25_anousu;
    $clorcindiceesperado->o25_orcindica = $oPost->o25_orcindica;
    $clorcindiceesperado->o25_valor = $oPost->o25_valor;

    $clorcindiceesperado->alterar($oPost->o25_sequencial);

    if ($clorcindiceesperado->erro_status == 0) {

      $lSqlErro = true;
      throw new DBException($clorcindiceesperado->erro_msg);
    }

    db_msgbox($clorcindiceesperado->erro_msg);
    db_fim_transacao($lSqlErro);
  }

  if (isset($oPost->excluir)) {

    db_inicio_transacao();

    $clorcindiceesperado->excluir($oPost->o25_sequencial);

    if ($clorcindiceesperado->erro_status == 0) {

      $lSqlErro = true;
      throw new DBException($clorcindiceesperado->erro_msg);
    }

    db_msgbox($clorcindiceesperado->erro_msg);
    db_fim_transacao($lSqlErro);
  }

  if (isset($oPost->opcao)) {

    $rsConsultaProgramFisica = $clorcindiceesperado->sql_record($clorcindiceesperado->sql_query($oPost->o25_sequencial));

    if ($clorcindiceesperado->numrows > 0) {
      db_fieldsmemory($rsConsultaProgramFisica, 0);
    }
  }
} catch (Exception $oErro) {
  db_msgbox($oErro->getMessage());
}

if(isset($oGet->codindica)) {
  $o25_orcindica = $oGet->codindica;
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?php
if(isset($oPost->opcao) && $oPost->opcao == "alterar") {

  $db_botao			     = true;
  $db_opcao 		     = 2;
  $db_opcaoPrincipal = 2;
} else if(isset($oPost->opcao) && $oPost->opcao == "excluir") {

  $db_botao			     = true;
  $db_opcao 		     = 3;
  $db_opcaoPrincipal = 3;
} else {

  $db_opcao 		 = 1;
  $db_botao 		 = true;

  if(isset($oPost->novo) || isset($oPost->alterar) ||   isset($oPost->excluir) || (isset($oPost->incluir) && $lSqlErro == false ) ) {

    $o25_sequencial	= "";
    $o25_anousu 	  = "";
    $o25_valor  	  = "";
  }
}
?>
<table  align="center" style="padding-top:15px;">
  <tr>
    <td>
      <form name="form1" method="post" action="">
        <table border="0" align="center">
          <tr>
            <td>
              <?=$Lo25_orcindica?>
            </td>
            <td>
              <?php
              db_input('o25_orcindica', 10, "", true, 'text', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?=$Lo25_anousu?>
            </td>
            <td>
              <?php
              db_input('o25_sequencial', 10, "",           true, 'hidden',        3);
              db_input('o25_anousu' ,    10, $Io25_anousu, true, 'text',  $db_opcao);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?=$Lo25_valor?>
            </td>
            <td>
              <?php
              db_input('o25_valor', 10, "", true, 'text', $db_opcao);
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
                     type="submit"
                     id="db_opcao"
                     value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
                     <?=($db_botao==false?"disabled":"")?>  >
              <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao == 1 ? "style='visibility:hidden;'" : "")?> >
            </td>
          </tr>
        </table>
        <table>
          <tr>
            <td valign="top"  align="center">
              <?php
              $aChavePri = array(
                "o25_sequencial" => @$o25_sequencial,
                "o25_orcindica"  => @$o25_orcindica
              );

              $sWhere  = " o25_orcindica = ".@$o25_orcindica;

              $cliframe_alterar_excluir->chavepri	     = $aChavePri;
              $cliframe_alterar_excluir->sql   		     = $clorcindiceesperado->sql_query(null,"*","o25_sequencial",$sWhere);
              $cliframe_alterar_excluir->campos  	     = "o25_anousu,o25_valor";
              $cliframe_alterar_excluir->legenda 	     = "Índice Esperado";
              $cliframe_alterar_excluir->iframe_height = "160";
              $cliframe_alterar_excluir->iframe_width  = "700";
              $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
              ?>
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>
</body>
</html>
<script>

  function js_cancelar() {

    var opcao = document.createElement("input");
    opcao.setAttribute("type","hidden");
    opcao.setAttribute("name","novo");
    opcao.setAttribute("value","true");
    document.form1.appendChild(opcao);
    document.form1.submit();
  }
</script>