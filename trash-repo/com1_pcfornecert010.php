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
include("classes/db_pcfornecert_classe.php");
include("classes/db_pcforne_classe.php");
include("classes/db_pccertif_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpcfornecert = new cl_pcfornecert;
$clpcforne = new cl_pcforne;
$clpccertif = new cl_pccertif;
$clpcfornecert->rotulo->label();
$clpccertif->rotulo->label();
if(isset($atualiza)){
  db_inicio_transacao();
  $erro = false;
  $result = $clpcfornecert->excluir($pc61_numcgm);
  //
  reset($HTTP_POST_VARS);
  $db_msg="Certificados atualizados.";
  for($i=0;$i<sizeof($HTTP_POST_VARS);$i++){
    $campo = substr(key($HTTP_POST_VARS),0,12);
    if($campo == "pc61_certif_"){
      $campo = trim(substr(key($HTTP_POST_VARS),12));
      $campoo = $HTTP_POST_VARS["pc61_obs_".$campo];
      $campod = $HTTP_POST_VARS["pc61_vencim_".$campo."_ano"]."-".$HTTP_POST_VARS["pc61_vencim_".$campo."_mes"]."-".$HTTP_POST_VARS["pc61_vencim_".$campo."_dia"];
      $testad = $HTTP_POST_VARS["pc61_vencim_".$campo."_ano"].$HTTP_POST_VARS["pc61_vencim_".$campo."_mes"].$HTTP_POST_VARS["pc61_vencim_".$campo."_dia"];
      if($testad==""){
	$db_msg = "Data inválida!";
	$erro = true;
	break;
      }
      $lpcfornecert->pc61_certif = $campo;
      $clpcfornecert->pc61_numcgm = $pc61_numcgm;
      $clpcfornecert->pc61_vencim = $campod;
      $clpcfornecert->pc61_obs    = $campoo;
      $clpcfornecert->incluir($pc61_numcgm,$campo);
      if($clpcfornecert->erro_status=='0'){
        $erro = true;
        break;
      }
      $db_msg = "";
    }
    next($HTTP_POST_VARS);
  }
  if($db_msg==""){
    $db_msg = $clpcfornecert->erro_msg;
  }
  db_fim_transacao($erro);
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <form name="form1" method="post">
    <table border='1' cellspacing="0" cellpadding="0">
    <?
    $result = $clpccertif->sql_record($clpccertif->sql_query());
    if($clpccertif->numrows==0){
      echo "<tr>";
      echo "<td>";
      echo "Não existem documentos cadastrados.";
      echo "</td>";
      echo "</tr>";
    }else{
      echo "<tr>";
      echo "<td>";
      echo "<strong>Seleciona</strong>";
      echo "</td>\n";
      echo "<td align='left'>";
      echo "<strong>Descrição certificado</strong>";
      echo "</td>\n";
      echo "<td align='left'>";
      echo "<strong>Vencimento</strong>";
      echo "</td>\n";
      echo "<td align='left'>";
      echo "<strong>Observação</strong>";
      echo "</td>\n";
 
      echo "</tr>";
 
      for($i=0;$i<$clpccertif->numrows;$i++){
	db_fieldsmemory($result,$i);
        $campo = "pc61_certif_".$pc59_certif;
        $data_dia  = "pc61_vencim_".$pc59_certif."_dia";
        $data_mes  = "pc61_vencim_".$pc59_certif."_mes";
        $data_ano  = "pc61_vencim_".$pc59_certif."_ano";
        $obs   = "pc61_obs_".$pc59_certif;
        $result1 = $clpcfornecert->sql_record($clpcfornecert->sql_query($pc61_numcgm,$pc59_certif));
        if($result1!=false && $clpcfornecert->numrows>0){
           if(!isset($atualiza) || $erro == false){
             db_fieldsmemory($result1,0);
	     $$campo = 't';
	     $$data_dia  = substr($pc61_vencim,8,2);
	     $$data_mes  = substr($pc61_vencim,5,2);
	     $$data_ano  = substr($pc61_vencim,0,4);
	     $$obs   = $pc61_obs;
	   }
        }else{
           if(!isset($atualiza) || $erro == false){
	     $$campo = 'f';
	     $$data_dia = null;
	     $$data_mes = null;
	     $$data_ano = null;
	     $$obs   = null;
	   }
        }
	echo "<tr>";
        echo "<td title='$Tpc61_certif'>";
        echo db_input("pc61_certif",6,$Ipc59_certif,true,"checkbox",2,"","pc61_certif_".$pc59_certif);
        echo "</td>\n";
	echo "<td align='left'>";
        echo $pc59_descr;
        echo "</td>\n";
        echo "<td>";
        echo db_inputdata("pc61_vencim",$$data_dia,$$data_mes,$$data_ano,true,'text',2,"","pc61_vencim_".$pc59_certif);
        echo "</td>\n";
	echo "<td>";
        echo db_textarea("pc61_obs",1,40,$Ipc61_obs,true,'text',2,"","pc61_obs_".$pc59_certif);
        echo "</td>\n";
	
        echo "</tr>";
      }
      echo "<tr><td colspan='4' align='center'>\n";
      echo "<input name='atualiza' value='Atualiza' type='submit'>";
      echo "<input name='pc61_numcgm' value='$pc61_numcgm' type='hidden'>";
      echo "</td></tr>";
    }
    ?>
    </table>
    </form>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($erro) ){
  
  $clpcfornecert->erro_msg = $db_msg;
  if($clpcfornecert->erro_status=="1"){
    $clpcfornecert->erro(true,false);
    if($clpcfornecert->erro_campo!=""){
      echo "<script> document.form1.".$clpcfornecert->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpcfornecert->erro_campo.".focus();</script>";
    };
  }else{
    $clpcfornecert->erro(true,false);
  };
};
?>