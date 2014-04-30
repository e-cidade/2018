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
include("classes/db_saniatividade_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_sanitario_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clsaniatividade = new cl_saniatividade;
$clsanitario = new cl_sanitario;
$db_opcao = 22;
$db_botao = false;
//if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
if((isset($HTTP_POST_VARS["opcaoExec"]) && $HTTP_POST_VARS["opcaoExec"])=="Alterar"){
 
  db_inicio_transacao();
  $sqlerro=false;
  $db_opcao = 2;
  if($y83_ativprinc == 't'){
    $result = $clsaniatividade->sql_record($clsaniatividade->sql_query("","","y83_codsani as cod,y83_seq as seq",""," y83_codsani = $y83_codsani"));
    if($clsaniatividade->numrows > 0){
      db_fieldsmemory($result,0);
      $clsaniatividade->y83_codsani = $cod;
      $clsaniatividade->y83_estado = 'f';
      $clsaniatividade->alterar_atividade($cod,'f');
      if ($clsaniatividade->erro_status==0){
        $sqlerro=true;
      }
    }
    $clsaniatividade->alterar($y83_codsani,$y83_seq);
    if ($clsaniatividade->erro_status==0){
      $sqlerro=true;
    }

  }else{
    // verificar se existe outra atividade como principal
   // db_msgbox("princ = $y83_ativprinc ... seq = $y83_seq");
    $sqlpri = "select * from saniatividade where y83_codsani = $y83_codsani and y83_seq <> $y83_seq";
    $resultpri = pg_query($sqlpri);
    $linhaspri = pg_num_rows($resultpri);
    if($linhaspri>0){
      // se existir ... deixa fazer a alteração
      $pri=0;
      for($i=0;$i<$linhaspri;$i++){
        db_fieldsmemory($resultpri,$i);
        //echo "xxxxxxxxxxxxx $y83_seq ... $y83_ativprinc";
        if($y83_ativprinc == 't' ){
          $pri = 1;
        }
      }
      if($pri != 1){
        // senão... manda incluir outra como principal
        db_msgbox("Informe outra atividade como principal.");
      }else{
      $clsaniatividade->alterar($y83_codsani,$y83_seq);
	    if ($clsaniatividade->erro_status==0){
	      $sqlerro=true;
	    }
      }
     
    }
      
  }

  db_fim_transacao($sqlerro);

	if (!$sqlerro) {
		echo " <script>
          	parent.iframe_calculo.location.href='fis1_sanicalc001.php?y80_codsani=".$y83_codsani."';
         	 </script>";
	}

}else if(isset($chavepesquisa)){
  $db_opcao = 2;
  $result = $clsaniatividade->sql_record($clsaniatividade->sql_query($chavepesquisa,$chavepesquisa1));
  db_fieldsmemory($result,0);
  $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0" onLoad="a=1">
<table width="790" border="0" cellpadding="0" cellspacing="0"
	bgcolor="#5786B2">
	<tr>
		<td height="430" align="left" valign="top" bgcolor="#CCCCCC">
		<center><?
		include("forms/db_frmsaniatividade.php");
		?></center>
		</td>
	</tr>
</table>
</body>
</html>
		<?
		if((isset($HTTP_POST_VARS["opcaoExec"]) && $HTTP_POST_VARS["opcaoExec"])=="Alterar"){
		  if($clsaniatividade->erro_status=="0"&&$sqlerro==true){
		    $clsaniatividade->erro(true,false);
		    $db_botao=true;
		    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		    if($clsaniatividade->erro_campo!=""){
		      echo "<script> document.form1.".$clsaniatividade->erro_campo.".style.backgroundColor='#99A9AE';</script>";
		      echo "<script> document.form1.".$clsaniatividade->erro_campo.".focus();</script>";
		    };
		  }else{
		    $clsaniatividade->erro(true,false);
		    echo "<script>parent.iframe_saniatividade.location.href='fis1_saniatividade001.php?y83_codsani=".$y83_codsani."';</script>";
		  };
		};
		?>