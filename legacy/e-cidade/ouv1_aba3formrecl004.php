<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("classes/db_formareclamacao_classe.php");
include("classes/db_tipoprocformareclamacao_classe.php");
include("dbforms/db_classesgenericas.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");

$oPost      = db_utils::postMemory($_POST);
$oGet       = db_utils::postMemory($_GET);
$db_opcao   = 2;

$cl_iframeseleciona        = new cl_iframe_seleciona();
$clformareclamacao         = new cl_formareclamacao();
$cltipoprocformareclamacao = new cl_tipoprocformareclamacao();

if (isset($oGet->p51_codigo)) {
  $iCod = $oGet->p51_codigo;
} else {
  $iCod = 0;	
}

if ( isset($oPost->atualizar) ){

  $lSqlErro = false;	
	
  db_inicio_transacao();
	
  $cltipoprocformareclamacao->excluir(null,"p43_tipoproc = {$iCod} ");

  if ( $cltipoprocformareclamacao->erro_status == 0 ) {
    $lSqlErro = true;
    $sMsgErro = $cltipoprocformareclamacao->erro_msg; 
  }

  if ( !$lSqlErro ) {
  	 
    $aListaFormReclamacao = explode(',',$oPost->listaformreclamacao);
       	
  	foreach ( $aListaFormReclamacao as $iCodTipoProcFormaReclamacao ){
  		
	 $cltipoprocformareclamacao->p43_formareclamacao = $iCodTipoProcFormaReclamacao;
     $cltipoprocformareclamacao->p43_tipoproc        = $iCod;
  	 $cltipoprocformareclamacao->incluir(null);	 

	 if ( $cltipoprocformareclamacao->erro_status == 0 ) {
	   $lSqlErro = true;
	   $sMsgErro = $cltipoprocformareclamacao->erro_msg;
	 }
	 
  	}
  	
  }
	
  db_fim_transacao($lSqlErro);	
	
}

$sqlFormaReclamacao = " select * 
                           from formareclamacao ";

$sqlTipoFormaReclamacao = " select * 
                              from tipoprocformareclamacao 
                                   inner join formareclamacao on tipoprocformareclamacao.p43_formareclamacao = formareclamacao.p42_sequencial 
                             where p43_tipoproc = {$iCod} ";                       
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript">

  function js_valida(){
  
    var sVirgula = '';
    var sDados   = '';
    
    var iFrameDpto 	  = formreclamacao.document.form1;
    var iNroRegistros = iFrameDpto.elements.length;
	
	for( var iInd=0; iInd < iNroRegistros; iInd++){
	
      if( iFrameDpto.elements[iInd].type == "checkbox" && iFrameDpto.elements[iInd].checked){
         sDados  += sVirgula+iFrameDpto.elements[iInd].value;
		 sVirgula = ', ';
      }
	      
	}
	
	document.form1.listaformreclamacao.value = sDados;
	
  } 
 
 
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<br>
<form name="form1" method="post" action="">
<?
 db_input('listaformreclamacao',10,'',true,'hidden',3,"");
?>
 <table width="80%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="400" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?
      $cl_iframeseleciona->sql           = $sqlFormaReclamacao;
      $cl_iframeseleciona->campos        = "p42_sequencial,p42_descricao";
      $cl_iframeseleciona->legenda       = "Forma de Reclamação";
      $cl_iframeseleciona->textocabec    = "darkblue";
      $cl_iframeseleciona->textocorpo    = "black";
      $cl_iframeseleciona->fundocabec    = "#aacccc";
      $cl_iframeseleciona->fundocorpo    = "#ccddcc";
      $cl_iframeseleciona->iframe_height = '350px';
      $cl_iframeseleciona->iframe_width  = '100%';
      $cl_iframeseleciona->iframe_nome   = 'formreclamacao';
      $cl_iframeseleciona->chaves        = "p42_sequencial";
      $cl_iframeseleciona->sql_marca     = $sqlTipoFormaReclamacao;
      $cl_iframeseleciona->tamfontecabec = '12';
      $cl_iframeseleciona->tamfontecorpo = '10';
      $cl_iframeseleciona->marcador      = true;
      $cl_iframeseleciona->iframe_seleciona($db_opcao);
    ?>
    </center>
	</td>
  </tr>
  <tr>    
    <td align="center">
      <input name='atualizar' type='submit' id='atualizar' value='Atualizar' onclick='return js_valida();'>    
    </td>
  </tr>   
</table>
</form>
</center>
</body>
</html>
<?
  if ( isset($oPost->atualizar) ){
  	if ( isset($lsqlErro) && $lsqlErro === true ) {
	  db_msgbox($sMsgErro);
  	}
  }
?>