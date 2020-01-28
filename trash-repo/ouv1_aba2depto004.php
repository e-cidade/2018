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
include("classes/db_tipoproc_classe.php");
include("classes/db_tipoprocdepto_classe.php");
include("dbforms/db_classesgenericas.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");

$oPost      = db_utils::postMemory($_POST);
$oGet       = db_utils::postMemory($_GET);
$dUsuario   = date('Y-m-d',db_getsession('DB_datausu'));

$cl_iframeseleciona = new cl_iframe_seleciona();
$cltipoproc         = new cl_tipoproc;
$cltipoprocdepto    = new cl_tipoprocdepto();

$cltipoproc->rotulo->label();


if ( isset($oPost->atualizar) ){

  $lSqlErro = false;	
	
  db_inicio_transacao();
	
  $cltipoprocdepto->excluir(null,"p41_tipoproc = {$oPost->p51_codigo} ");

  if ( $cltipoprocdepto->erro_status == 0 ) {
    $lSqlErro = true;
    $sMsgErro = $cltipoprocdepto->erro_msg; 
  }

  if ( !$lSqlErro ) {
  	 
    $aListaDepto = explode(',',$oPost->listadepto);
       	
  	foreach ( $aListaDepto as $iCodDepto ){
  		
	 $cltipoprocdepto->p41_coddepto = $iCodDepto;
     $cltipoprocdepto->p41_tipoproc = $oPost->p51_codigo;
  	 $cltipoprocdepto->incluir(null);

	 if ( $cltipoprocdepto->erro_status == 0 ) {
	   $lSqlErro = true;
	   $sMsgErro = $cltipoprocdepto->erro_msg;
	 }
	 
  	}
  	
  }
	
  db_fim_transacao($lSqlErro);	
	
}

$sqlDbDepart = " select coddepto,
                        descrdepto 
                   from db_depart 
                  where ( limite is null  
                       or limite > '{$dUsuario}'
                        ) ";

if (isset($oGet->p51_codigo)) {                        
  $iCod = $oGet->p51_codigo;                      
  $sqlTipoProcDepto = " select * 
                          from tipoprocdepto 
                               inner join tipoproc  on tipoproc.p51_codigo        = tipoprocdepto.p41_tipoproc 
                               inner join db_depart on tipoprocdepto.p41_coddepto = db_depart.coddepto
                         where p41_tipoproc = {$iCod} ";                       
                       
  $sqlTipoProc = " select p51_codigo,
                          p51_descr
                     from tipoproc 
                    where p51_codigo = {$iCod} ";

  $rsTipoProc  = pg_query($sqlTipoProc);
  $iTipoProc   = pg_numrows($rsTipoProc);

  if ($iTipoProc > 0) {
	  $oTipoProc  = db_utils::fieldsMemory($rsTipoProc,0);
	  $p51_codigo = $oTipoProc->p51_codigo;
	  $p51_descr  = $oTipoProc->p51_descr;
  }
}
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
    
    var iFrameDpto 	  = departamentos.document.form1;
    var iNroRegistros = iFrameDpto.elements.length;
	
	for( var iInd=0; iInd < iNroRegistros; iInd++){
	
      if( iFrameDpto.elements[iInd].type == "checkbox" && iFrameDpto.elements[iInd].checked){
         sDados  += sVirgula+iFrameDpto.elements[iInd].value;
		 sVirgula = ', ';
      }
	      
	}
	
	document.form1.listadepto.value = sDados;
	
  } 
 
 
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?
 if (isset($oGet->db_opcao)) {
?>
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
<table width="80%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="18%">&nbsp;</td>
    <td width="12%" title="<?=@$Tp51_codigo?>">
    <?=@$Lp51_codigo?>
    </td>
    <td align="left" width="6%"> 
    <?
      db_input('p51_codigo',4,$Ip51_codigo,true,'text',3,"");
      db_input('listadepto',10,'',true,'hidden',3,"");
    ?>
    </td>
    <td align="left"> 
    <?
      db_input('p51_descr',50,$Ip51_descr,true,'text',3,"")
    ?>
    </td>    
    <td width="12%" colspan="3">&nbsp;</td>
  </tr>     
</table>
<table width="80%" border="0" cellspacing="0" cellpadding="0">
  <tr>    
    <td colspan="3">&nbsp;</td>
  </tr> 
  <tr>    
    <td align="center">
      <input name='atualizar' type='submit' id='atualizar' value='Atualizar' onclick='return js_valida();'>    
    </td>
  </tr> 
  <tr>    
    <td colspan="3">&nbsp;</td>
  </tr> 
</table>
 <table width="80%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="400" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?
      $cl_iframeseleciona->sql           = $sqlDbDepart;
      $cl_iframeseleciona->campos        = "coddepto,descrdepto";
      $cl_iframeseleciona->legenda       = "Departamentos";
      $cl_iframeseleciona->textocabec    = "darkblue";
      $cl_iframeseleciona->textocorpo    = "black";
      $cl_iframeseleciona->fundocabec    = "#aacccc";
      $cl_iframeseleciona->fundocorpo    = "#ccddcc";
      $cl_iframeseleciona->iframe_height = '600px';
      $cl_iframeseleciona->iframe_width  = '100%';
      $cl_iframeseleciona->iframe_nome   = 'departamentos';
      $cl_iframeseleciona->chaves        = "coddepto";
      $cl_iframeseleciona->tamfontecabec = '12';
      $cl_iframeseleciona->tamfontecorpo = '10';      
      $cl_iframeseleciona->sql_marca     = $sqlTipoProcDepto;
      $cl_iframeseleciona->marcador      = true;
      $cl_iframeseleciona->iframe_seleciona($db_opcao);
    ?>
    </center>
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
 }
?>