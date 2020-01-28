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

// select k00_numpre,k00_numpar,k00_receit from arrecad where k00_numpre = 11111454;
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require("classes/db_notificacao_classe.php");
include("libs/db_sessoes.php");
//include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

db_postmemory($HTTP_SERVER_VARS);

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function js_recebenotif(notifi){
    js_OpenJanelaIframe('top.corpo','db_iframe_notificacao','cai3_gerfinanc019.php?notifi='+notifi,'Recebimento de Notifica��o',true);
}

</script>
<style>
.fonte {
font-family:Arial, Helvetica, sans-serif;
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>
<?
if(isset($erro1)) { ?>
 <br><br><br><Br><h3>D�bitos recentemente pagos!</h3>
<? 
}else{
?>
  <table width="100%" border="0" cellpadding="0" cellspacing="5">
   <tr>
   <td align="left" ><h3> Notifica��es Enviadas ao Contribuinte:</h3>
  <?
  $clnotificacao = new cl_notificacao;
   $numpres = "";
   if(isset($matric)){
     $numpres .= " k55_matric = $matric and ";
   }else if(isset($inscr)){
     $numpres .= " k56_inscr = $inscr and ";
   }else if(isset($numcgm)){
     $numpres .= " k57_numcgm = $numcgm and ";
   }else if(isset($numpre)){
     $numpres .= " notidebitos.k53_notifica = notificacao.k50_notifica and notidebitos.k53_numpre = $numpre and ";
   }else if(isset($Parcelamento)){
     $numpres .= " notidebitos.k53_numpre = ( select v07_numpre from termo where v07_parcel = $Parcelamento limit 1 ) and ";

   }else{
     $numpres = "";
   }
   
   $sCampos  = " distinct k50_notifica, ";
   $sCampos .= "   		  case when k43_notifica is not null then 'Parcial' else 'Geral' end as tipo, ";
   $sCampos .= "   		  k50_dtemite, ";
   $sCampos .= "   		  k52_hora, ";
   $sCampos .= "   		  k51_descr, ";
   $sCampos .= "   		  k50_obs, ";
   $sCampos .= "   		  db_usuarios.nome, ";
   $sCampos .= "   		  k54_data, ";
   $sCampos .= "   		  k54_hora, ";
   $sCampos .= "   		  k54_assinante::text, ";
   $sCampos .= "		  k54_obs ";
   
   $sql = $clnotificacao->sql_query_usuario("",$sCampos," k50_notifica ",$numpres." notificacao.k50_instit = ".db_getsession('DB_instit') );
 
   db_lovrot($sql,10,"()","16","js_recebenotif|0",null,"NoMe",array(),false);

 
   $result = pg_exec("select k03_msg from numpref where k03_anousu = ".db_getsession("DB_anousu"));
  if(pg_numrows($result)!=0){
    $str = str_replace("\n","<br>",pg_result($result,0,0));
    ?>
    </td>
  </tr>
    <tr>
      <td align="left" class="fonte"><h3>Mensagem aos Usu&aacute;rios:</h3></td>
    </tr>
    <tr>
      <td class="fonte" align="justify" valign="top">
	<?
	echo str_replace(" ","&nbsp;",$str);
	?>
      </td>
    </tr>
  <?
   }
   ?> 
 <tr>
    <td align="center">&nbsp;</td>
  </tr>
  
  </table>
<?
}
?>
</center>
</body>
</html>