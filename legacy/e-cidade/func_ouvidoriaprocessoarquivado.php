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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_tipoproc_classe.php");
require_once("classes/db_protprocesso_classe.php");
require_once("classes/db_procarquiv_classe.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$cltipoproc              = new cl_tipoproc;
$clprocarquiv            = new cl_procarquiv;
$clprotprocesso          = new cl_protprocesso;

$clprocarquiv->rotulo->label("p67_codproc");
$clprocarquiv->rotulo->label("p67_dtarq");
$clprotprocesso->rotulo->label("p58_requer");
$cltipoproc->rotulo->label("p51_codigo");


$sCampos = "";
$sWhere  = "";
$sAnd    = "";
$iDepto  = db_getsession("DB_coddepto");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="">
<table border="0" align="center" cellspacing="0" width="80%">
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" title="<?=$Tp67_codproc?>" width="15%">
      <?=$Lp67_codproc?>
    </td>
    <td align="left" title="<?=$Tp67_codproc?>">
      <?
        db_input("p67_codproc",10,$Ip67_codproc,true,"text",1,"");
      ?>
    </td> 
  </tr>
  <tr>
    <td>
      <strong>Atendimento:</strong>
    </td>
    <td align="left" title="Atendimento">
    	<?php 
    		db_input('atendimento', 10, "", true, "text", 1, " onchange='js_validaAtendimento();' ");
    	?>
    </td>
  </tr>
  <tr>
    <td align="left" title="<?=$Tp58_requer?>" width="15%">
      <?=$Lp58_requer?>
    </td>
    <td align="left" title="<?=$Tp58_requer?>">
      <?
        db_input("p58_requer",40,$Ip58_requer,true,"text",1,"");
      ?>
    </td> 
  </tr>
  <tr>
    <td align="left" title="<?=$Tp67_dtarq?>" width="15%">
      <?=$Lp67_dtarq?>
    </td>
    <td align="left" title="<?=$Tp67_dtarq?>">
      <?=db_inputdata('datainicial','','','',true,'text',1)?>
      <b>à</b>
      <?=db_inputdata('datafinal','','','',true,'text',1)?>
    </td> 
  </tr>
  <tr>
    <td align="left" title="<?=$Tp51_codigo?>">
      <?=$Lp51_codigo?>
    </td>
    <td align="left" colspan="2" title="<?=$Tp51_codigo?>">
      <?
        $sSqlTipoProcesso  = $cltipoproc->sql_query(null,"p51_codigo,p51_descr","p51_codigo,p51_descr","p51_tipoprocgrupo = 2");
        $rsTipoProcesso    = $cltipoproc->sql_record($sSqlTipoProcesso);
        db_selectrecord('p51_codigo',$rsTipoProcesso,true,1,"","","","0");
      ?>
    </td>
  </tr> 
</table>
<table align="center" cellpadding="0" cellspacing="0" width="80%">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td align="center"> 
      <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar"> 
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" 
             onClick="parent.db_iframe_processoarquivado.hide();">
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table border="0" align="center" cellspacing="0" width="80%">
  <tr> 
    <td align="center" valign="top"> 
      <?
        $sCampos .= "   distinct p67_codproc,                                                         ";
        $sCampos .= "            ov01_tipoprocesso,                                                   ";
        $sCampos .= "            p51_descr,                                                           ";
        $sCampos .= "            p67_dtarq,                                                           ";
        $sCampos .= "            p67_historico,                                                       ";
        $sCampos .= "            (case when p58_requer isnull                                         ";
        $sCampos .= "              then z01_nome else p58_requer end                                  ";
        $sCampos .= "            ) as p58_requer,                                                     ";
        $sCampos .= "            p58_obs                                                              ";
        
        if (isset($sCampos) && empty($sCampos)) {
          $sCampos .= "procarquiv.*, ouvidoriaatendimento.*, protprocesso.*";
        }
        
        $sWhere  .= "     p51_tipoprocgrupo = 2                                                       ";
        $sWhere  .= " and p67_coddepto = {$iDepto}                                                    ";
        $sAnd     = " and ";
        
	      if (!isset($pesquisa_chave)) {
	      	
	      	if (isset($oPost->p67_codproc)) {
	      		
	      		if (!empty($oPost->p67_codproc)) {
	      			
              $sWhere    .= "{$sAnd} p67_codproc = {$oPost->p67_codproc} "; 
              $sAnd       = " and "; 
	      		}
	      	}
	      	
	      	if (isset($oPost->atendimento)){
	      		
	      		list ($iAtendimento, $iAnoUsu) = explode('/', $oPost->atendimento);
	      		if ((!empty($iAtendimento)) && (!empty($iAnoUsu))) {
	      			
	      			$sWhere .= " {$sAnd} ov01_numero = {$iAtendimento} and ov01_anousu = {$iAnoUsu} ";
	      			$sAnd    = " and ";
	      		}
	      	}
	      	
		      if (isset($oPost->p58_requer)) {
		      	
		      	if (!empty($oPost->p58_requer)) {

              $sWhere    .= "{$sAnd} p58_requer like '{$oPost->p58_requer}%' "; 
              $sAnd       = " and ";
		      	}
	        }
	      	
	      	if (isset($oPost->p51_codigo)) {
	      		
		      	if (!empty($oPost->p51_codigo)) {
	        
	            $sWhere    .= "{$sAnd} ov01_tipoprocesso = {$oPost->p51_codigo}"; 
	            $sAnd       = " and "; 
		        }
	      	}
	      	
	        if (isset($oPost->datainicial) && isset($oPost->datafinal)) {
	        	
		        if ((!empty($oPost->datainicial)) && (!empty($oPost->datafinal))) {
		          
		          $dtInicial  = implode("-",array_reverse(explode("/",$oPost->datainicial)));
		          $dtFinal    = implode("-",array_reverse(explode("/",$oPost->datafinal)));
		          
		          $sWhere    .= "{$sAnd} procarquiv.p67_dtarq between '{$dtInicial}' and '{$dtFinal}'"; 
		          $sAnd       = " and "; 
		        } else if (!empty($oPost->datainicial)) {
		          
		          $dtInicial  = implode("-",array_reverse(explode("/",$oPost->datainicial)));
		          $sWhere    .= "{$sAnd} procarquiv.p67_dtarq >= '{$dtInicial}'";
		          $sAnd       = " and ";
		        } else if (!empty($oPost->datafinal)) {
		        
		          $dtFinal    = implode("-",array_reverse(explode("/",$oPost->datafinal)));
		          $sWhere    .= "{$sAnd} procarquiv.p67_dtarq <= '{$dtFinal}'";
		          $sAnd       = " and ";
		        } 
	        }
	      
	      	$sSqlProcArquiv = $clprocarquiv->sql_query_ouvprocarquivado(null,$sCampos,"p67_codproc, p67_dtarq desc",
	      	                                                            $sWhere);
	        db_lovrot($sSqlProcArquiv,15,"()","",$funcao_js,"","NoMe");
	      } else {
	      	
	        if($pesquisa_chave!=null && $pesquisa_chave!=""){
	        	
	        	$sSqlProcArquiv  = $clprocarquiv->sql_query_ouvprocarquivado(null,$sCampos,"p67_codproc, p67_dtarq desc",
	        	                                                             $sWhere);
	        	$rsSqlProcArquiv = $clprocarquiv->sql_record($sSqlProcArquiv);
	          if ($clprocarquiv->numrows != 0) {
	          
	            db_fieldsmemory($rsSqlProcArquiv,0);
	            echo "<script>"
	                   .$funcao_js."('$p67_codproc','$p58_requer','$ov01_tipoprocesso','$p51_descr',
	                                 '$p67_dtarq','$p58_obs',false);
	                  </script>";  
	          } else {
	           echo "<script>".$funcao_js."('','Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
	          }
	        } else {
	         echo "<script>".$funcao_js."('','','','','','',false);</script>";
	        }
	      }
      ?>
     </td>
   </tr>
</table>
</form>
</body>
<script>
  document.form1.p67_codproc.style.width  = "11%";
  document.form1.datainicial.style.width  = "11%";
  document.form1.datafinal.style.width    = "11%";
  document.form1.p51_codigo.style.width   = "11%";

  function js_validaAtendimento() {

	  var sAtendimento = $F('atendimento');
	  if (sAtendimento.indexOf('/') == -1) {
		  
		  alert ('O formato do campo atendimento é ATENDIMENTO/ANO. Favor verificar.');
		  $('atendimento').value = "";
		  $('atendimento').focus();
	  }
	  return false;
  }
</script>
</html>