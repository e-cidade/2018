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
  include("classes/db_cgm_classe.php");
  include("classes/db_issbase_classe.php");
  include("classes/db_iptubase_classe.php");
  include("classes/db_termo_classe.php");
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  $clcgm = new cl_cgm;
  $clissbase = new cl_issbase;
  $cliptubase = new cl_iptubase;
  $cltermo = new cl_termo;
  if (isset($tipo)){
     if ($tipo=="CGM"){
  	     $label_tipo = "Cgm";
  	     $sql=$clcgm->sql_query_file($cod);
     }else if ($tipo=="MATRICULA"){
  	     $label_tipo = "Matricula";
        	$sql=$cliptubase->sql_query($cod);
     }else if ($tipo=="INSCRICAO"){
        	$label_tipo = "Inscrição";
  	      $sql=$clissbase->sql_query($cod);
     }else if ($tipo == "PARCEL"){
  	      $label_tipo = "Parcelamento";
          $sql=$cltermo->sql_query_consulta($cod,"*,resp.z01_nome as nome, resp.z01_numcgm as cgm");
     }

  $result= pg_exec($sql);
  $num= pg_numrows($result);
}

?>
<html>
<head> 
<title>Dados da Inscri&ccedil;&atilde;o - BCI</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>
<?
if (isset($tipo)){
  if ($num > 0) { 
    db_fieldsmemory($result,0,1);
    if ($tipo == "PARCEL"){
      $z01_nome=$nome;
      $z01_numcgm=$cgm;
     }
?>
<table width="750" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr bgcolor="#CCCCCC"> 
    <td colspan="4" align="center"><font color="#333333"><strong>&nbsp;SITUAÇÃO FISCAL&nbsp;</strong></font><font color="#666666"><strong> 
      </strong></font></td>
  </tr>
  <tr> 
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;<?=@$label_tipo?> n&ordm;:&nbsp;</td>
    <td width="356" align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      <?=$cod?>
      &nbsp; </strong></font></td>
      <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Nome:&nbsp; </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      <?=$z01_nome?>
      &nbsp; </strong></font></td>    
  </tr>   
    <td colspan="4" align="left"><table width="100%" height="100%" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr valign="bottom"> 
          <td width="12%"><table width="80%" border="0" cellspacing="2" cellpadding="0">
	          <tr>                
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" ><a href="cai3_consitfiscal_detalhes.php?solicitacao=VISTORIA&cod=<?=$cod?>&tipo=<?=$tipo?>" target="iframeDetalhes">&nbsp;Vistorias&nbsp;</a></td>
              </tr>
              <tr> 
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cai3_consitfiscal_detalhes.php?cod=<?=$cod?>&solicitacao=NOTIFICA&tipo=<?=$tipo?>" target="iframeDetalhes">&nbsp;Notificação&nbsp;</a></td>
              </tr>
              <tr> 
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cai3_consitfiscal_detalhes.php?cod=<?=$cod?>&solicitacao=AUTO&tipo=<?=$tipo?>" target="iframeDetalhes">&nbsp;Auto de Infração&nbsp;</a></td>
              </tr>
              <tr> 
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cai3_consitfiscal_detalhes.php?cod=<?=$cod?>&solicitacao=LEVANTA&tipo=<?=$tipo?>" target="iframeDetalhes">&nbsp;Levantamento&nbsp;</a></td>
              </tr>      
            </table></td>
          <td width="88%" align="left"> <iframe align="middle" height="100%" frameborder="0" marginheight="0" marginwidth="0" name="iframeDetalhes" width="100%"> 
            </iframe> </td>
        </tr>
      </table></td>
  </tr>
</table>
<?
}  
  }else{ 
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="center">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center"><strong>Pesquisa da(o) <?=@$label_tipo?> n&deg;
      &nbsp;<?=$cod?>&nbsp;
      n&atilde;o retornou nenhum registro.</strong></td>
  </tr>
</table>
<? 
  }
  
?>
</body>
</html>