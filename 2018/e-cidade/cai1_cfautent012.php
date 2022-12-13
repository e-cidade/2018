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
include("classes/db_cfautent_classe.php");
include("classes/db_cfautentconta_classe.php");
include("classes/db_cfautentdocasschq_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clcfautent = new cl_cfautent;
$clcfautentconta = new cl_cfautentconta;
$clcfautentdocasschq = new cl_cfautentdocasschq;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  
  $clcfautent->k11_impassche = $k11_impassche;
  $clcfautent->alterar($k11_id);

  $result = $clcfautentconta->sql_record($clcfautentconta->sql_query($k11_id)); 
	$intNumrowsConta = $clcfautentconta->numrows;
  if($intNumrowsConta > 0){
	   $clcfautentconta->excluir($k11_id);	
	} 
  if($k11_id != ""){
		$clcfautentconta->k16_conta = $k16_conta;
		$clcfautentconta->incluir($k11_id);
	}
    if($k11_impassche==1){
      $sqlchq = "select * from cfautentdocasschq where k39_cfautent =".$k11_id;
      $resultchq = pg_query($sqlchq);
      $linhaschq = pg_num_rows($resultchq);
      if($linhaschq >0){
        //altera

        db_fieldsmemory($resultchq,0);
        $clcfautentdocasschq->k39_sequencial = $k39_sequencial;
        $clcfautentdocasschq->k39_cfautent   = $k11_id;
		$clcfautentdocasschq->k39_documento  = $db03_docum;
		$clcfautentdocasschq->alterar($k39_sequencial);
		if($clcfautentdocasschq->erro_status == 0){
		  $sqlerro = true;
	      $clcfautentdocasschq->erro_msg = " - ".$clcfautentdocasschq->erro_msg;		
		}        
      }else{
       
	    //incluir na cfautentdocasschq
		$clcfautentdocasschq->k39_cfautent  = $k11_id;
		$clcfautentdocasschq->k39_documento = $db03_docum;
		$clcfautentdocasschq->incluir(null);
		if($clcfautentdocasschq->erro_status == 0){
		  $sqlerro = true;
	      $clcfautentdocasschq->erro_msg = " - ".$clcfautentdocasschq->erro_msg;		
		}
      }
  	}
	
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   
   $sCampos  = "a.db64_nome as tipoimpdescr,";
   $sCampos .= "b.db64_nome as tipoimpchequedescr,";
   $sCampos .= "*";
   
   $result = $clcfautent->sql_record($clcfautent->sql_query_impressora($chavepesquisa,$sCampos));
   db_fieldsmemory($result,0);
   $result2 = $clcfautentconta->sql_record($clcfautentconta->sql_query($chavepesquisa)); 
	 $intNumrowsConta = $clcfautentconta->numrows;
   if($intNumrowsConta > 0){
     db_fieldsmemory($result2,0);
	 }
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmcfautent.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  if($clcfautent->erro_status=="0"){
    $clcfautent->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcfautent->erro_campo!=""){
      echo "<script> document.form1.".$clcfautent->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcfautent->erro_campo.".focus();</script>";
    }
  }else{
    $clcfautent->erro(true,true);
  }
}

if ( isset($k11_id) && trim($k11_id) != "" ) {
	  
	  echo " <script> 																																									 ";
	  echo "   parent.document.formaba.modimprime.disabled = false; 																		 ";
    echo "   parent.iframe_modimprime.location.href='cai1_cfautentmodimprime.php?idAutent={$k11_id}';  ";
  	echo " </script>												  										  	         												 ";
	
}

if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","k11_ident1",true,1,"k11_ident1",true);
</script>