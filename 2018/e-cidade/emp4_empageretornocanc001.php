<?php
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
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_empagegera_classe.php");
require_once("classes/db_empagemov_classe.php");
require_once("classes/db_empageconf_classe.php");
require_once("classes/db_empageconfgera_classe.php");
require_once("classes/db_empageconfcanc_classe.php");
require_once("classes/db_db_bancos_classe.php");
require_once("classes/db_empagedadosret_classe.php");
require_once("classes/db_empagedadosretmov_classe.php");
require_once("classes/db_errobanco_classe.php");
require_once("classes/db_empord_classe.php");

$clempagegera        = new cl_empagegera;
$clempagemov         = new cl_empagemov;
$clempageconf        = new cl_empageconf;
$clempageconfgera    = new cl_empageconfgera;
$clempageconfcanc    = new cl_empageconfcanc;
$cldb_bancos         = new cl_db_bancos;
$clempagedadosret    = new cl_empagedadosret;
$clempagedadosretmov = new cl_empagedadosretmov;
$clerrobanco         = new cl_errobanco;
$clempord            = new cl_empord;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

if(isset($atualizar)){
  db_inicio_transacao();
  $sqlerro=false;
  $arr =  split(",",$movs);
  for($mi=0;$mi<sizeof($arr);$mi++){
    $movimento = $arr[$mi];
    if($sqlerro==false){
      $clempageconfgera->e90_codmov = $movimento;
      $clempageconfgera->e90_codgera= $codigogera;
      $clempageconfgera->excluir($movimento,$codigogera);
      $erro_msg = $clempageconfgera->erro_msg;
      if($clempageconfgera->erro_status==0){
	    $sqlerro = true;
	    break;
      }
    }
    if($sqlerro==false){
      $clempageconf->e86_codmov = $movimento;
      $clempageconf->excluir($movimento);
      $erro_msg = $clempageconf->erro_msg;
      if($clempageconf->erro_status==0){
	    $sqlerro = true;
	    break;
      }
    }
    if($sqlerro==false){
      $clempagedadosretmov->e76_codret     = $retornoarq;
      $clempagedadosretmov->e76_codmov     = $movimento;
      $clempagedadosretmov->e76_processado = 'true';
      $clempagedadosretmov->alterar($retornoarq,$movimento);
      if($clempagedadosretmov->erro_status==0){
        $erro_msg = $clempagedadosretmov->erro_msg;
	    $sqlerro = true;
	    break;
      }
    }
  }
  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_atualizar(){
  if(canc.document.form1){
    obj = canc.document.form1;
    var coluna='';
    var sep='';
    for(i=0; i<obj.length; i++){
      nome = obj[i].name.substr(0,5);
      if(nome=="CHECK" && obj[i].checked==true){
	coluna += sep+obj[i].value;
	sep= ",";
      }
    }
    if(coluna==''){
      alert("Selecione um movimento!");
      return false;
    }
    document.form1.movs.value = coluna;
    return true;
  }else{
    return false;
  }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body  bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<br>
<table width="100%" height="90%" border="0" cellspacing="0" cellpadding="0">
  <form name="form1" enctype="multipart/form-data" method="post">
  <tr> 
    <td height="90%"  width="100%" align="center" valign="top">
      <center>
        <?
        if(isset($retornoarq) && trim($retornoarq)!=""){
        ?><br>
          <?
	      db_input("movs",10,'',true,'hidden',3);
	      db_input("retornoarq",10,'',true,'hidden',3);
	      $passaparametro = "";
	      if(isset($contapaga) && trim($contapaga)!=""){
	        db_input("contapaga",10,'',true,'text',3);
	        $passaparametro = "&contapaga=$contapaga";
	      }
	      $result_nomebanco = $clempagedadosret->sql_record($clempagedadosret->sql_query_bco($retornoarq,"db90_descr as nomebanco,e75_codgera as codigogera"));
	      if($clempagedadosret->numrows>0){
	        db_fieldsmemory($result_nomebanco,0);
	        db_input("codigogera",10,'',true,'hidden',3);
	      }
	      ?>
          <iframe name="canc" src="emp4_empageretornocanc001_iframe.php?lCancelado=0&retornoarq=<?=(@$retornoarq)?><?=$passaparametro?>" width="100%"  height='500' marginwidth="0" marginheight="0" frameborder="0"></iframe><br>
          <small><span style="color:darkblue;">**Movimento processado ou agendado pelo banco</span></small><BR>
          <input name="atualizar" type="submit"  value="Cancelar selecionados" onclick='return js_atualizar();'>
	    <?
	    }else{
	    ?>
	      <BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>
	      <b>Arquivo retorno não encontrado.</b>
	      <BR><BR><BR><BR><BR><BR><BR><BR><BR>
	    <?
	    }
	    ?>
	<?
	$voltacorreto = "emp4_selarquivo001.php?canc=true";
	?>
          <input name="voltar" type="button"  value="Voltar" onclick="location.href='<?=$voltacorreto?>'">
      </center>
    </td>
  </tr>
  </form>
</table>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($atualizar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
  }
}
?>