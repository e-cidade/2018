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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require_once ("libs/db_utils.php");
require_once ("classes/db_db_config_classe.php");

db_postmemory($HTTP_POST_VARS);

$cldbconfig = new cl_db_config();

// Preenche os inputs com o ano/mes da folha atual
$anofolha             = db_anofolha();
$mesfolha             = db_mesfolha();
$sDataBaseUso         = db_getsession('DB_base');
$sSqlConfigPrefeitura = $cldbconfig->sql_query_file (null, 'munic', null, 'prefeitura = true');
$rsConfigPrefeitura   = $cldbconfig->sql_record($sSqlConfigPrefeitura);
$oConfigPrefeitura    = db_utils::fieldsMemory($rsConfigPrefeitura, 0);
$sPrefeituraProducao  = strtolower($oConfigPrefeitura->munic);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<br><br>
  <center>
  <form name="form1" method="post" action="">
    <fieldset style="width: 300px;">
      <legend><strong>Processar Dados Refeisul</strong></legend>
      <table width="100%" border="0">
        <tr>
          <td width="40"><b>Competência:</b></td>
          <td>
            <?
              db_input("anofolha", 5, '', true, 'text', 1);
              echo " / ";
              db_input("mesfolha", 3, '', true, 'text', 1);
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Base:</b></td>
          <td>
            <?
              $sSqlDataBases = "select datname,datname from pg_database where substr(datname, 1, 6) != 'templa' and " . (substr(db_getsession('DB_base'),0,5) == "ontem"?"true":" datname != '".db_getsession('DB_base') . "'") . " order by datname;";
              $rsDatabases   = pg_query($sSqlDataBases);
              db_selectrecord('datname', $rsDatabases, true, 1, "style: width:100%;", '', '', '', '', 1);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <p align="center"><input type="button" name="btn_Processar" id="btn_Processar" value="Processar" onclick="js_validaDados();" ></p>
  </form>
  </center>
  
  
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script>
  /**
   *  Verifica se a base selecionada é a que o usuário está logado.
   */
  var selectBase = $('datname');
  selectBase.observe ('change', function () {
  
    if ( selectBase.value == '<?=$sPrefeituraProducao; ?>' ) {
    
      alert ("Esta rotina não pode ser executada na base de produção: <?=$sPrefeituraProducao; ?>");
      $('btn_Processar').disabled = true;
    } else {
      $('btn_Processar').disabled = false;
    }    
  });

  function js_validaDados() {

    var sBaseSelecionada = $('datname').value;
    var iMesFolha        = $('mesfolha').value;
    var iAnoFolha        = $('anofolha').value;
    var lRetorno         = false;
    
    if ( sBaseSelecionada == '<?=$sPrefeituraProducao; ?>') {
      alert ("Esta rotina não pode ser executada na base de produção: <?=$sPrefeituraProducao; ?>");
      return false;
    }
      
    if ( sBaseSelecionada != '<?=$sPrefeituraProducao; ?>' && 
         iMesFolha        != ""                            &&
         iAnoFolha        != ""                            && 
         sBaseSelecionada != "") {
      lRetorno = true;
    }
    
    if ( lRetorno ) {
    
      var sParametro = "iMesFolha="+iMesFolha+"&iAnoFolha="+iAnoFolha+"&sBase="+sBaseSelecionada+"&sMunicipio=+<?=$sPrefeituraProducao;?>";
      js_OpenJanelaIframe('top.corpo', 
                          'db_iframe_relrefeisul', 
                          'pes1_relrefeisul002.php?'+sParametro,
                          'Processando ... ',
                          true);
    }
  }
</script>
</body>
</html>