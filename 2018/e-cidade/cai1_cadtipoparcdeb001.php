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
include("classes/db_cadtipoparcdeb_classe.php");
include("classes/db_cadtipoparc_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clcadtipoparcdeb = new cl_cadtipoparcdeb;
$clcadtipoparc = new cl_cadtipoparc;
$db_opcao = 22;
$db_botao = false;
if(isset($_self)) {
  $sqlerro = false;
  /*
$clcadtipoparcdeb->k41_cadtipoparc = $k41_cadtipoparc;
$clcadtipoparcdeb->k41_arretipo = $k41_arretipo;
  */
}

if( isset($_self)) {
  $sql1 = "select k40_codigo,k40_dtini,k40_dtfim from cadtipoparc where k40_codigo = $k41_cadtipoparc";
    $result1 = pg_query($sql1);
    db_fieldsmemory($result1,0);
    /*
     ####################### retirado este codigo a pedido da tarefa 13885 ########################
    $sql ="
	    select * from (
	        select k40_codigo,k40_dtini,k40_dtfim 
	              from cadtipoparc 
	              inner join cadtipoparcdeb on k41_cadtipoparc = k40_codigo 
	              where k41_arretipo = $k41_arretipo ) as x
	      where (k40_dtini::date,k40_dtfim::date )
	      overlaps ('$k40_dtini'::date,'$k40_dtfim'::date) and k40_codigo <> $k40_codigo ";
    $resulttipo = pg_query($sql);
    $linhastipo = pg_num_rows($resulttipo);
    if($linhastipo>0){
      db_fieldsmemory($resulttipo,0);
      $erro_msg= "Já existe regra cadastrada para este tipo de débito dentro deste período. (Regra: $k40_codigo)";
                
      $sqlerro = true;
    }
    */
}

if(isset($_self) && $_self=="Incluir"){
      
    if($sqlerro==false){
    db_inicio_transacao();
    $clcadtipoparcdeb->incluir($k41_cadtipoparc,$k41_arretipo);
    $erro_msg = $clcadtipoparcdeb->erro_msg;
    if($clcadtipoparcdeb->erro_status==0){
      $sqlerro=true;
    }else{
    	$k41_arretipo="";
    	$k00_descr="";
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($_self) && $_self=="Alterar"){
  if($sqlerro==false){
    db_inicio_transacao();
    $clcadtipoparcdeb->alterar($k41_cadtipoparc,$k41_arretipo);
    $erro_msg = $clcadtipoparcdeb->erro_msg;
    if($clcadtipoparcdeb->erro_status==0){
      $sqlerro=true;
    }else{
    	$k41_arretipo="";
    	$k00_descr="";
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($_self) && $_self=="Excluir"){
  if($sqlerro==false){
    db_inicio_transacao();
    $clcadtipoparcdeb->excluir($k41_cadtipoparc,$k41_arretipo);
    $erro_msg = $clcadtipoparcdeb->erro_msg;
    if($clcadtipoparcdeb->erro_status==0){
      $sqlerro=true;
    }else{
    	$k41_arretipo="";
    	$k00_descr="";
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
  
   $result = $clcadtipoparcdeb->sql_record($clcadtipoparcdeb->sql_query($k41_cadtipoparc,$k41_arretipo));
   if($result!=false && $clcadtipoparcdeb->numrows>0){
     db_fieldsmemory($result,0);
   }
}
$sql= "select * from cadtipoparc where k40_codigo = $k41_cadtipoparc";
$result = pg_query($sql);
db_fieldsmemory($result,0);

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
	<?
	include("forms/db_frmcadtipoparcdeb.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($_self) && $_self!=""){
    db_msgbox($erro_msg);
    /*
	 echo "<script>  
	    document.form1.k41_vencini_dia.value='';
	    document.form1.k41_vencini_mes.value='';
	    document.form1.k41_vencini_ano.value='';
 		document.form1.k41_vencfim_dia.value='';
		document.form1.k41_vencfim_mes.value='';
		document.form1.k41_vencfim_ano.value='';
     </script>";
    */
	if($clcadtipoparcdeb->erro_campo!=""){
        echo "<script> document.form1.".$clcadtipoparcdeb->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clcadtipoparcdeb->erro_campo.".focus();</script>";
        
    }else{
     db_redireciona("?k41_cadtipoparc=$k41_cadtipoparc");
    }
}
?>