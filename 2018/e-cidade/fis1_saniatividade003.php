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
include("classes/db_sanitario_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clsaniatividade = new cl_saniatividade;
$clsanitario = new cl_sanitario;
$db_botao = false;
$db_opcao = 33;
if((isset($HTTP_POST_VARS["opcaoExec"]) && $HTTP_POST_VARS["opcaoExec"])=="Excluir"){
  
  db_inicio_transacao();
  $sqlerro=false;
  $db_opcao = 3;
  $result = $clsaniatividade->sql_record($clsaniatividade->sql_query("","","y83_codsani as cod,y83_seq as seq,y83_ativprinc as princ",""," y83_codsani = $y83_codsani and y83_seq = $y83_seq"));
  if($clsaniatividade->numrows > 0){
    db_fieldsmemory($result,0);
    if($princ == 't'){
      $erro_msg ="Informe outra atividade como principal, para depois excluir esta.";
      /*
       $result = $clsaniatividade->sql_record($clsaniatividade->sql_query("","","min(y83_seq) as seq",""," y83_codsani = $y83_codsani"));
       if($clsaniatividade->numrows > 0){
       db_fieldsmemory($result,0);
       $clsaniatividade->y83_codsani = $cod;
       $clsaniatividade->y83_seq = $seq;
       $clsaniatividade->y83_estado = 't';
       $clsaniatividade->alterar_atividade($cod,'t');
       if ($clsaniatividade->erro_status==0){
       $sqlerro=true;
       }
       }*/
    }else{
      $clsaniatividade->excluir($y83_codsani,$y83_seq);
      $erro_msg = $clsaniatividade->erro_msg;
      if ($clsaniatividade->erro_status==0){
        $sqlerro=true;
      }
    }
  }

  $clsaniatividade->sql_record($clsaniatividade->sql_query("","","*",""," y83_codsani = $y83_codsani and y83_dtfim is null"));
  if($clsaniatividade->numrows == 0){
    $result = $clsanitario->sql_record($clsanitario->sql_query("","*",""," y80_codsani = $y83_codsani"));
    if($clsanitario->numrows > 0){
      db_fieldsmemory($result,0);
      if($y80_dtbaixa == ""){
        $result = $clsaniatividade->sql_record($clsaniatividade->sql_query("","","y83_dtfim",""," y83_codsani = $y83_codsani"));
        if($clsaniatividade->numrows > 0){
          $dia = date("d",db_getsession("DB_datausu"));
          $mes = date("m",db_getsession("DB_datausu"));
          $ano = date("Y",db_getsession("DB_datausu"));
          db_fieldsmemory($result,0);
          if($y83_dtfim != "")
          $clsanitario->y80_dtbaixa= $y83_dtfim;
          else
          $clsanitario->y80_dtbaixa= $ano."-".$mes."-".$dia;
        }
        $clsanitario->y80_codsani=$y83_codsani;
        $clsanitario->alterar($y83_codsani);
        if ($clsanitario->erro_status==0){
          $sqlerro=true;
        }
        echo "
	     <script>
	     parent.iframe_sanitario.location.href='fis1_sanitario002.php?chavepesquisa=$y83_codsani';\n
	     </script>
	     ";
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
  $db_opcao = 3;
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
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
		if((isset($HTTP_POST_VARS["opcaoExec"]) && $HTTP_POST_VARS["opcaoExec"])=="Excluir"){
		  if($clsaniatividade->erro_status=="0"&&$sqlerro==true){
		    $clsaniatividade->erro(true,false);
		    echo"<script>parent.iframe_saniatividade.location.href='fis1_saniatividade001.php?y83_codsani=".$y83_codsani."&abas=1'</script>;\n";
		  }else{
		    db_msgbox($erro_msg);
		    //$clsaniatividade->erro(true,false);
		    echo"<script>parent.iframe_saniatividade.location.href='fis1_saniatividade001.php?y83_codsani=".$y83_codsani."&abas=1'</script>;\n";
		  };
		};
		?>