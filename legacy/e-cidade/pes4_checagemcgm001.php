<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

$oPost        = db_utils::postMemory($_POST);
$oGet         = db_utils::postMemory($_GET);

$sMensagem    = "Nenhum registro foi encontrado.";
$aDadosDuplos = array(); 

if (isset($oPost->processar)) {
		
  try {
  	
    $sNomeArquivoTmp = $_FILES["arquivo"]["tmp_name"];
    if (substr($_FILES["arquivo"]["type"], -3) != "csv") {
    
      $sNomeArquivo = $_FILES["arquivo"]["name"];
      throw new Exception("Erro arquivo [{$sNomeArquivo}] inválido para o formato CSV!");
    }
    
    $oDaoCgm       = db_utils::getDao("cgm");
    $pArquivo      = fopen($sNomeArquivoTmp, "r");
    $iLinhaArquivo = 0;
    while ($aDadosArquivo = fgets($pArquivo)) {

      $aDadosLinha = explode(";", $aDadosArquivo);      
      if ($iLinhaArquivo != 0) {
        
        $sCamposCgm  = "cgm.z01_numcgm, cgm.z01_nome";
        $sWhereCgm   = "cgm.z01_cgccpf = {$aDadosLinha[25]}";
        $sSqlCgm     = $oDaoCgm->sql_query_file(null, "*", null, $sWhereCgm);
        $rsSqlCgm    = $oDaoCgm->sql_record($sSqlCgm);
        $iNumRowsCgm = $oDaoCgm->numrows;
        for ($iInd = 0; $iInd < $iNumRowsCgm; $iInd++) {
          
          $oCgm         = db_utils::fieldsMemory($rsSqlCgm, $iInd);
          $oDadosDuplos = new stdClass();
          $oDadosDuplos->linhaarquivo = $iLinhaArquivo;
          $oDadosDuplos->z01_numcgm   = $oCgm->z01_numcgm;
          $oDadosDuplos->z01_nome     = $oCgm->z01_nome;
          $oDadosDuplos->matricula    = $aDadosLinha[2];
          $oDadosDuplos->nome         = $aDadosLinha[3];
          
          $aDadosDuplos[] = $oDadosDuplos;
        }
      }
      
      $iLinhaArquivo++;
    }
    
    $_SESSION["oChecagemCGM"] = $aDadosDuplos;
    
    fclose($pArquivo);
  } catch (Exception $oErro) {
  	
  	unset($_SESSION["oChecagemCGM"]);
  	$sMensagem = $oErro->getMessage();
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js,arrays.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>
<style>
fieldset table td:first-child {  
  width: 90px;
  white-space: nowrap;
}
    
td {
  white-space: nowrap;
}
    
#rh108_status {
  width: 100%;
}
</style>
<script>
function js_processar() {
    
  if ($('arquivo').value == '') {
    
    alert('Informe um arquivo válido!');
    return false;
  }
}
  
function js_emite() {
  
  jan = window.open('pes4_checagemcgm002.php',
                    '',
                    'width='+(screen.availWidth-5)
                            +',height='+(screen.availHeight-40)
                            +',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
  
js_clearSession = function() {
  
  var oParam  = new Object();
  oParam.exec = "clearSession"; 
  var oAjax   = new Ajax.Request('pes4_servidorpagamento.RPC.php',
                                  {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                  });
  return true;
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onbeforeunload='js_clearSession();'>
<form name="form1" method="post" action="pes4_checagemcgm001.php" enctype="multipart/form-data">
  <table border="0" align="center" cellspacing="0" cellpadding="0">
    <tr>
      <td height="40px">&nbsp;</td>
    </tr>
    <tr> 
      <td valign="top" bgcolor="#CCCCCC"> 
        <fieldset>
          <legend>
            <b>Processar Checagem CGM</b>
            <table border="0">
              <tr>
                <td>
                  <b>Arquivo:</b>
                </td>
                <td> 
                  <?
                    db_input("arquivo", 30, 0, true, "file", 1);
                  ?>
                </td>
              </tr>
            </table>
          </legend>
        </fieldset>
      </td>
    </tr>
  </table>
  <table align="center">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
        <input type="submit" id="processar" name="processar" value="Processar" onclick="return js_processar();">
      </td>
    </tr>
  </table>
</form>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<?
if (isset($oPost->processar)) {
	
	if (isset($sMensagem) && count($aDadosDuplos) == 0) {
		db_msgbox($sMensagem);
	} else {
		echo "<script>js_emite();</script>";
	}
}
?>
</html>