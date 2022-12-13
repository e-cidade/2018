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
include("classes/db_mer_estoque_classe.php");
include("classes/db_mer_estoqueitem_classe.php");
include("classes/db_matrequiitem_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmer_estoque     = new cl_mer_estoque;
$clmer_estoqueitem = new cl_mer_estoqueitem;
$cl_matrequiitem   = new cl_matrequiitem;
$escola            = db_getsession("DB_coddepto");
$db_opcao          = 22;
$db_botao          = false;
if (isset($incluir)) {
	
  $sqlr="select * from mer_estoqueitem where me19_i_matrequi=".$codrequi;
  $resultr = pg_query($sqlr);
  $linhasr = pg_num_rows($resultr);
  
  if ($linhasr==0) {
  	
	$sql = $cl_matrequiitem->sql_query("","*","","m41_codmatrequi=".$codrequi);
	$result = pg_query($sql);
	$linhas = pg_num_rows($result);
	$vet    = explode(",",$lista);
    db_inicio_transacao();
    
	for ($x=0;$x<count($vet);$x++) {
		
	  db_fieldsmemory($result,$vet[$x]);
	  $sqlv        = "select * from mer_estoque where me18_i_codmater=".$m41_codmatmater; 
	  $resultv     = pg_query($sqlv);  
	  $linhasv     = pg_num_rows($resultv);
	  $sqlvalor    = "select * from matestoque where m70_codmatmater=".$m41_codmatmater;
	  $resultvalor = pg_query($sqlvalor);  
	  db_fieldsmemory($resultvalor,0);	   
	  
	  if ($linhasv==0) {
	  	  
	    $clmer_estoque->me18_f_quant      = $m41_quant;
	    $clmer_estoque->me18_i_calendario = $calendario;	   	     
	    $valorunit                        = $m70_valor/$m70_quant;
	    $valor                            = $valorunit*$m41_quant;
	    $clmer_estoque->me18_f_valor      = $valor;
	    $clmer_estoque->me18_i_escola     = $escola;	   
	    $clmer_estoque->me18_i_codmater   = $m41_codmatmater;	   
	    $clmer_estoque->incluir(null);
	    
	  } else {
	  	
	   	db_fieldsmemory($resultv,0);
	   	$quant                       = $me18_f_quant+$m41_quant;
	    $valorunit                   = $m70_valor/$m70_quant;
	    $valor                       = $me18_f_valor+($valorunit*$m41_quant);
	    $clmer_estoque->me18_f_valor = $valor;
	    $clmer_estoque->me18_f_quant = $quant;
	    $clmer_estoque->alterar($me18_i_codigo);
	    
	  }
	  $valor                                = $valorunit*$m41_quant;
	  $clmer_estoqueitem->me19_f_quant      = $m41_quant;
      $clmer_estoqueitem->me19_f_valor      = $valor;
      $numer                                = '0';
      $clmer_estoqueitem->me19_f_quantatend = $numer;
      $dia                                  = date('d',db_getsession("DB_datausu"));
      $mes                                  = date('m',db_getsession("DB_datausu"));
      $ano                                  = date('Y',db_getsession("DB_datausu"));
      $data                                 = $ano."-".$mes."-".$dia;
      $clmer_estoqueitem->me19_i_matrequi   = $codrequi;
      $clmer_estoqueitem->me19_d_data       = $data;
      $clmer_estoqueitem->me19_i_merestoque = $clmer_estoque->me18_i_codigo;
	  $clmer_estoqueitem->incluir(null);
	}        
	db_fim_transacao();
  } else{
    db_msgbox("Requisição já entregue");
  }
    
}

if (isset($alterar)) {
	
  db_inicio_transacao();
  $db_opcao = 2;
  $clmer_estoque->alterar($me18_i_codigo);
  db_fim_transacao();
  
} elseif (isset($chavepesquisa)) {
	
  $db_opcao = 2;
  $result   = $clmer_estoque->sql_record($clmer_estoque->sql_query($chavepesquisa)); 
  db_fieldsmemory($result,0);
  $db_botao = true;
   
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
<center>
<table border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <fieldset style="width:95%"><legend><b>Alteração de Estoque</b></legend>
	<? include("forms/db_frmmer_estoque.php");?>
	</fieldset>
	</td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),
        db_getsession("DB_modulo"),
        db_getsession("DB_anousu"),
        db_getsession("DB_instit")
       );
?>
</body>
</html>
<?
if (isset($alterar)) {
	
  if ($clmer_estoque->erro_status=="0") {
  	
    $clmer_estoque->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clmer_estoque->erro_campo!="") {
    	
      echo "<script> document.form1.".$clmer_estoque->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmer_estoque->erro_campo.".focus();</script>";
      
    }
  } else {
    $clmer_estoque->erro(true,true);
  }
}
if ($db_opcao==22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","me18_f_quant",true,1,"me18_f_quant",true);
</script>