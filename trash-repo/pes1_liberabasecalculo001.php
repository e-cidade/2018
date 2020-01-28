<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conn.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cfpess_classe.php");
require_once("classes/db_rhempenhofolharhemprubrica_classe.php"); //$oRhEmpenhoFolhaEmpRubrica
require_once("classes/db_rhempenhofolhaempenho_classe.php"); // $oEmpFolhaEmpenho
require_once("classes/db_rhempenhofolha_classe.php"); //$oEmpenhoFolha


$oCfPess                   = new cl_cfpess();
$oRhEmpenhoFolhaEmpRubrica = new cl_rhempenhofolharhemprubrica(); 
$oEmpFolhaEmpenho          = new cl_rhempenhofolhaempenho();

$lSqlErro = false;
$sMsgErro = "";

$iIp            = db_getsession('DB_servidor');
$sNomeBaseAtual = db_getsession('DB_base');
$iAno           = date('Y'); 
$iMes           = date('m');

$oPost = db_utils::postMemory($HTTP_POST_VARS);

if ( isset($btnProcessaBase) ) {
  
  $rsConectaLiberaBase  = @pg_connect("host={$oPost->iIp} dbname={$oPost->oObjSelect} user=".$DB_USUARIO." port=".$DB_PORTA." password=".$DB_SENHA);
  db_query($rsConectaLiberaBase, "select fc_startsession()");

  $oCfPess->r11_anousu = $oPost->iAno;
  $oCfPess->r11_mesusu = $oPost->iMes;
  
  $sSqlCfPess  = $oCfPess->sql_query_file($oCfPess->r11_anousu, $oCfPess->r11_mesusu);
  $rsSqlCfPess = db_query($rsConectaLiberaBase, $sSqlCfPess);
  $iRowCfPess  = pg_num_rows($rsSqlCfPess);
  
  /**
   * Caso existam registros para a competência informada, a operação será abortada.
   */
  if ( $iRowCfPess == 0 ) {
    $sMsgErro = "Não existem registros para competência: ".$oPost->iAno."/".$oPost->iMes."\\n\\nOperação abortada.";    
  } else {
    
      // Inicia Transação
      db_query($rsConectaLiberaBase,"begin");
      
      $sWherePadraoExclusao = " rh72_anousu = {$oPost->iAno} and rh72_mesusu = {$oPost->iMes} ";

      /*
       * Esta rotina exclui os dados da tabela rhempenhofolhaemprubrica
       */      
      $sSqlFolhaEmpRubrica  = " select rh81_sequencial                                                    ";
      $sSqlFolhaEmpRubrica .= "   from rhempenhofolharhemprubrica                                         ";
      $sSqlFolhaEmpRubrica .= "        inner join rhempenhofolha on rh81_rhempenhofolha = rh72_sequencial ";
      $sSqlFolhaEmpRubrica .= "  where {$sWherePadraoExclusao}                                            ";

      $rsExecExcFolhaEmpRubrica  = db_query($rsConectaLiberaBase, $sSqlFolhaEmpRubrica);      
      $iLinhasExcFolhaEmpRubrica = pg_num_rows($rsExecExcFolhaEmpRubrica);

      if ( !$lSqlErro ) {

        $iExcFolhaEmpRubrica = 0;

        if ( $iLinhasExcFolhaEmpRubrica > 0 ) {

          for ( $i = 0 ; $i < $iLinhasExcFolhaEmpRubrica ; $i++ ) {
            
            $oRsFolhaEmpRubrica = db_utils::fieldsMemory($rsExecExcFolhaEmpRubrica, $i);
    
            $sSqlExcluiEmpRubrica  = " delete                                                          ";
            $sSqlExcluiEmpRubrica .= "   from rhempenhofolharhemprubrica                               ";
            $sSqlExcluiEmpRubrica .= "  where rh81_sequencial = {$oRsFolhaEmpRubrica->rh81_sequencial} ";
            
            $rsExcluiEmpRubrica = db_query($rsConectaLiberaBase, $sSqlExcluiEmpRubrica);
            
            /**
             * Verifica se houve erros na exclusão de registros
             */
            if ( !$rsExcluiEmpRubrica ) {
              
              $sMsgErro  = "Erro ao excluir registros da tabela rhempenhofolharhemprubrica.";
              $sMsgErro .= "Administrador: ".pg_last_error($rsConectaLiberaBase);
              $lSqlErro  = true;
            }
            
            $iExcFolhaEmpRubrica++;
          }
        }
      }

      /**
       * Esta rotina exclui os dados da tabela EmpenhoFolhaEmpenho
       */      
      $sSqlEmpFolhaEmpenho  = "select rh76_sequencial       "; 
      $sSqlEmpFolhaEmpenho .= "  from rhempenhofolhaempenho ";
      $sSqlEmpFolhaEmpenho .= "       inner join rhempenhofolha on rh76_rhempenhofolha = rh72_sequencial ";
      $sSqlEmpFolhaEmpenho .= " where {$sWherePadraoExclusao};";

      $rsEmpFolhaEmpenho      = db_query($rsConectaLiberaBase, $sSqlEmpFolhaEmpenho);
      $iLinhasEmpFolhaEmpenho = pg_num_rows($rsEmpFolhaEmpenho);
      
      if ( !$lSqlErro ) {
        
        $iExcEmpFolhaEmpenho = 0;

        if ( $iLinhasEmpFolhaEmpenho > 0 ) {
          
          for ( $i = 0 ; $i < $iLinhasEmpFolhaEmpenho ; $i++ ) {

            $oRsEmpFolhaEmpenho = db_utils::fieldsMemory($rsEmpFolhaEmpenho, $i);

            $sSqlExcluiEmpFolhaEmpenho  = "delete from rhempenhofolhaempenho ";
            $sSqlExcluiEmpFolhaEmpenho .= " where rh76_sequencial = {$oRsEmpFolhaEmpenho->rh76_sequencial}";
            
            $rsExcluirEmpFolhaEmpenho = db_query($rsConectaLiberaBase, $sSqlExcluiEmpFolhaEmpenho);
            
            if ( !$rsExcluirEmpFolhaEmpenho ) {              
              
              $sMsgErro  = "Erro ao excluir registros da tabela rhempenhofolhaempenho.";
              $sMsgErro .= "Administrador: ".pg_last_error($rsConectaLiberaBase);
              $lSqlErro = true;
            }

            $iExcEmpFolhaEmpenho++;
          }
        }
      }
      
      /**
       * Esta rotina exclui os dados da tabela orcreservarhempenhofolha
       */
      $sSqlOrcReservaRhEmpFolha  = " select o120_sequencial ";
      $sSqlOrcReservaRhEmpFolha .= "   from orcreservarhempenhofolha ";
      $sSqlOrcReservaRhEmpFolha .= "        inner join rhempenhofolha on o120_rhempenhofolha = rh72_sequencial ";
      $sSqlOrcReservaRhEmpFolha .= "  where {$sWherePadraoExclusao}";
      
      $rsOrcReservaRhEmpFolha      = db_query($rsConectaLiberaBase, $sSqlOrcReservaRhEmpFolha);
      $iLinhasOrcReservaRhEmpFolha = pg_num_rows($rsOrcReservaRhEmpFolha);
      
      if ( !$lSqlErro ) {

        $iExcOrcReservaRhEmpFolha = 0;

        if ( $iLinhasOrcReservaRhEmpFolha > 0 ) {

          for ( $i = 0 ; $i < $iLinhasOrcReservaRhEmpFolha ; $i++ ) {

            $oOrcEmpFolha = db_utils::fieldsMemory($rsOrcReservaRhEmpFolha, $i);

            $sSqlExcluirOrcReservaRhEmpFolha  = " delete from orcreservarhempenhofolha "; 
            $sSqlExcluirOrcReservaRhEmpFolha .= " where o120_sequencial = {$oOrcEmpFolha->o120_sequencial}";
            
            $rsExcluirOrcReservaRhEmpFolha = db_query($rsConectaLiberaBase, $sSqlExcluirOrcReservaRhEmpFolha);
            
            if ( !$rsExcluirOrcReservaRhEmpFolha ) {
              
              $sMsgErro  = "Não foi possível excluir os registro da tabela orcreservarhempenhofolha";
              $sMsgErro .= "Administrador: ".pg_last_error($rsConectaLiberaBase);
              $lSqlErro = true;
            }
            $iExcOrcReservaRhEmpFolha++;
          }
        }
      }

      if ( !$lSqlErro ) {
        
        $sSqlExcluiEmpenhoFolha = "delete from rhempenhofolha where rh72_anousu = {$oPost->iAno} and rh72_mesusu = {$oPost->iMes}";
        $rsExcluiEmpenhoFolha   = db_query($rsConectaLiberaBase, $sSqlExcluiEmpenhoFolha);
        $iLinhasExcluidas       = pg_affected_rows($rsExcluiEmpenhoFolha);
        
        if ( !$rsExcluiEmpenhoFolha ) {
          
          $sMsgErro  = "Não foi possível excluir os dados da tabela rhempenhofolha.\\n\\n";
          $sMsgErro .= "Administrador: ".pg_last_error($rsConectaLiberaBase);
          $lSqlErro  = true;
        }
      }
      
      
      if ( !$lSqlErro ) {
        
        $sMsgErro  = "Processo concluído com sucesso!\\n\\n";
        $sMsgErro .= "Total de Registros Excluídos: ".($iExcFolhaEmpRubrica+$iExcEmpFolhaEmpenho+$iExcOrcReservaRhEmpFolha+$iLinhasExcluidas);

        $sEncerraLiberacao = "commit;";
      } else {
        $sEncerraLiberacao = "rollback;";        
      }
      
      
      
      db_query($rsConectaLiberaBase, $sEncerraLiberacao);
      pg_close($rsConectaLiberaBase);
      
      $conn = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA");
  }
}
?>
<html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbcomboBox.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<br><br>
<center>
  <form method="post" action="" onsubmit="return js_processaBase();">
  <fieldset style="width: 430px; padding: 20px">
    <legend><strong>Libera Base para Cálculo</strong></legend>
    <table width="100%">
      <tr>
        <td width="100px">
          <b>IP do Servidor:</b>
        </td>
        <td>
          <?
            db_input('iIp', 25, 3, true, 'text', 1, "onChange='js_buscaBases(false);'");
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <b>Bases:</b>
        </td>
        <td>
          <span id='spanBases'></span>
        </td>
      </tr>
      <tr>
        <td>
          <b>Competência:</b>
        </td>
        <td>
          <?
            db_input('iAno', 3, true, 3, 'text', 1);
            echo " / ";
            db_input('iMes', 1, true, 3, 'text', 1);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <p><input type="submit" name="btnProcessaBase" value="Processar"></p>
  </form>
</center>
<?
$conn = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA");
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>
  sUrlRPC = 'buscaBasesClientes.RPC.php';


  /**
   *  Função js_processaBase
   *  Verifica os dados preenchidos no formulário 
   */
  function js_processaBase () {
    
    var iMes  = $('iMes').value;
    var iAno  = $('iAno').value;
    var sBase = $('oObjSelect').value;
    
    if ( iAno == "" || iAno == null || iAno > <?=$iAno;?> ) {
      alert ("Informe um ano de competência.");
      return false;
    }
    if ( iMes == "" || iMes == null || iMes > 12 ) {
      alert ("Informe um mes de competência.");
      return false;
    }
    
    if ( !confirm("Confirma a exclusão dos empenhamentos realizados para a competência: "+iAno+"/"+iMes+"?") ) {
      return false;
    }
  }

  /**
   *  Função js_buscaBases
   *  Busca as bases no servidor de acordo com o IP digitado pelo usuário
   */
  function js_buscaBases ( lPrimeiroAcesso ) {
    
    var iIpDigitado = $('iIp').value;
    
    if ( iIpDigitado == null || iIpDigitado == "" ) {
      alert ("Preencha o campo IP do Servidor corretamente.");
      return false;
    }
    
    js_divCarregando('Aguarde, atualizando...',"msgBoxBuscaBases");
    
    var oParam            = new Object();
        oParam.sExec      = "buscaBase";
        oParam.iIp        = iIpDigitado;
        if ( lPrimeiroAcesso ) {
          oParam.iIp      = '<?=$iIp;?>';
        }
        oParam.sBaseAtual = '<?=$sNomeBaseAtual;?>'; 
        
    var oAjax       = new Ajax.Request (sUrlRPC,
                                         {
                                            method: 'post',  
                                            parameters:'json='+Object.toJSON(oParam),
                                            onComplete: js_retornoBuscaBases  
                                         });
    
  }

function js_retornoBuscaBases(oAjax) {
  
  js_removeObj('msgBoxBuscaBases');
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if ( oRetorno.lErro ) {
    alert ("Não foi possível recuperar as bases do servidor "+$('iIp').value);
    return false;
  } else {
    
    /**
     *  Essa função faz com que o array tenha o mesmo valor para chave e valor dentro
     *  do array. Facilitando o resgate do nome da base dentro do Widget DBComboBox
     */
    var aOpcaoSelect = new Array();
    oRetorno.aBases.each(
      function (sBase) {
        
        aOpcaoSelect[sBase] = sBase;
      }
    );
    
  }
  
  var oObjSelect = new DBComboBox("oObjSelect", "oObjSelect", aOpcaoSelect);
      oObjSelect.show($('spanBases'));
      
}

js_buscaBases(true);
</script>

<?
/**
 * A conexão deve ser fechada aqui para não dar conflito 
 * com as Permissões de Menu
 */
if ( isset($btnProcessaBase) ) {
  
  db_msgbox($sMsgErro); 
 
}
?>