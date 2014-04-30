<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("dbforms/db_funcoes.php");
include("classes/db_bens_classe.php");
include("classes/db_clabens_classe.php");
include("classes/db_bensmater_classe.php");
include("classes/db_bensimoveis_classe.php");
include("classes/db_bensbaix_classe.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_cfpatri_classe.php");
include("classes/db_bensplaca_classe.php");
include("classes/db_benslote_classe.php");
include("classes/db_benstransfcodigo_classe.php");
include("classes/db_departdiv_classe.php");
include("classes/db_bensdiv_classe.php");
include("classes/db_db_departorg_classe.php");
include_once("classes/db_cfpatriplaca_classe.php");
$cldepartorg    = new cl_db_departorg;
$cldb_estrut    = new cl_db_estrut;
$clbens         = new cl_bens;
$clbensmater    = new cl_bensmater;
$clbensimoveis  = new cl_bensimoveis;
$clclabens      = new cl_clabens;
$clbensbaix     = new cl_bensbaix;
$clcfpatri      = new cl_cfpatri;
$clbensplaca    = new cl_bensplaca;
$clbenslote     = new cl_benslote;
$cldepartdiv    = new cl_departdiv;
$clbensdiv      = new cl_bensdiv;
$clcfpatri      = new cl_cfpatri;
$clcfpatriplaca = new cl_cfpatriplaca;
$clbenstransfcodigo = new cl_benstransfcodigo;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
if(isset($db_atualizar) || isset($alterar)){
  $db_opcao = 2;
  $db_botao = true;
}else{
  $db_opcao = 22;
  $db_botao = false;
}
if(isset($alterar)){
	
   $result = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query_file(db_getsession("DB_instit")));
   if ($clcfpatriplaca->numrows > 0) {
     db_fieldsmemory($result,0);
   } else {
   	 $t07_digseqplaca = 4;
   }
	
  $sqlerro=false;
  if(isset($t64_class) && trim($t64_class) == ""){
    if(isset($t52_descr) && trim($t52_descr) != ''){
      $erro_msg = "Usuário: \\n\\n Campo Classificação do Material nao Informado \\n\\n Administrador.";
      $sqlerro = true;
      $clbens->erro_campo = 't64_class';
    }else{
      $erro_msg = "Usuário: \\n\\n Campo Descrição do Material nao Informado \\n\\n Administrador.";
      $sqlerro = true;
      $clbens->erro_campo = 't52_descr';
    }
  }

  if($sqlerro==false){
    //rotina q retira os pontos do estrutural da classe e busca o código do estrutural na tabela clabens
    $t64_class = str_replace(".","",$t64_class);
    $result_t64_codcla = $clclabens->sql_record($clclabens->sql_query_file(null,"t64_codcla as class",null," t64_class = '$t64_class' "));
    if($clclabens->numrows>0){
      db_fieldsmemory($result_t64_codcla,0);
    }else{
      $erro_msg = "Usuário: \\n\\n Alteração não concluída, Classificação Informada nao Existe \\n\\n Administrador.";
      $sqlerro=true;
    }
  }
  if($sqlerro==false){
    db_inicio_transacao();
    $result_lote = $clbenslote->sql_record($clbenslote->sql_query_file(null,"t43_bem",null,"t43_codlote=$t42_codigo"));
    for ($w=0;$w<$clbenslote->numrows;$w++) {
    	 db_fieldsmemory($result_lote,$w);
    	 
    	 if ($sqlerro == false) {
    		 
    	 	if ($update_ident == "true") {
    	 		$seq = pg_result(pg_query("select max(t41_placaseq) from bensplaca where t41_placa = '$t64_class' "),0,0)+1;
    	 		if ($seq == "" || $seq == 0) {
    	 			$seq = 1;
    	 		}
    		  $clbens->t52_ident = str_replace(".","",$t64_class.db_formatar($seq,'f','0',$t07_digseqplaca,'e',0));
    		}
    		 
      	 $clbens->t52_bem    = $t43_bem;
      	 $clbens->t52_descr  = $t52_descr;
      	 $clbens->t52_codcla = $class;
      	 $clbens->t52_numcgm = $t52_numcgm;
      	 $clbens->t52_valaqu = $t52_valaqu;
      	 $clbens->t52_dtaqu  = $t52_dtaqu_ano."-".$t52_dtaqu_mes."-".$t52_dtaqu_dia;      	
      	 $clbens->t52_obs    = $t52_obs;
      	 $clbens->t52_depart = $t52_depart;
      	 $clbens->alterar($t43_bem);
    	   if ($clbens->erro_status==0) {
			     $sqlerro=true;
      	 }
      	 
      	 if ( $sqlerro == false && $update_ident == "true") {
      	 	 $codigo                    = pg_result($clbensplaca->sql_record($clbensplaca->sql_query_file (null,"t41_codigo",null,"t41_bem = {$t43_bem}")),0,0);
           $clbensplaca->t41_codigo   = $codigo;      	 	 
      	 	 $clbensplaca->t41_bem      = $t43_bem;
      	 	 $clbensplaca->t41_placa    = str_replace(".","",$t64_class);
      	 	 $clbensplaca->t41_placaseq = str_replace(".","",$seq);
      	 	 $clbensplaca->alterar($codigo);
      	 	 if ($clbensplaca->erro_status==0) {
			       $sqlerro=true;
      	   }
      	   
      	 }   

      	$erro_msg = $clbens->erro_msg;
      	 
    	}
     
    if ($sqlerro == false) {
		  $result_bensdiv=$clbensdiv->sql_record($clbensdiv->sql_query_file($t43_bem));
		  if ($clbensdiv->numrows>0) {
			  $clbensdiv->excluir($t43_bem);
			   if ($clbensdiv->erro_status==0) {
				   $sqlerro=true;
				   $erro_msg=$clbensdiv->erro_msg;
			   } 
		  }
		  
		  if ($sqlerro == false) {
			  if ($t33_divisao!="") {
				  $clbensdiv->t33_divisao=$t33_divisao;
				  $clbensdiv->incluir($t43_bem);
				   if ($clbensdiv->erro_status==0) {
					   $sqlerro=true;
					   $erro_msg=$clbensdiv->erro_msg;
				   } 
			  }
		  }
	  }
   }
   db_fim_transacao($sqlerro);
  }
}   
if(isset($chavepesquisa)){
	$db_opcao = 2;
  $db_botao = true;
  $desabilitar_campos = 'false';
  $bem_trans="";
  $vir="";
  $result = $clbenslote->sql_record($clbenslote->sql_query(null,"distinct t42_codigo,t42_descr,t52_codcla,t64_class,t64_descr,t52_numcgm,z01_nome,t52_valaqu,t52_dtaqu,t52_descr,t52_obs,t52_depart,descrdepto",null,"t43_codlote=$chavepesquisa")); 
  if($clbenslote->numrows>1){
  	db_msgbox("Não é possivel alterar!!Existe bem que ja foi alterado individualmente!!");
  	echo "<script>location.href='pat1_bensaltlote005.php';</script>";
  	exit;
  }else if($clbenslote->numrows>0){
  	$result_lote = $clbenslote->sql_record($clbenslote->sql_query_file(null,"t43_bem",null,"t43_codlote=$chavepesquisa"));
  	
    for($w=0;$w<$clbenslote->numrows;$w++){
    	db_fieldsmemory($result_lote,$w);
    	$result_transf=$clbenstransfcodigo->sql_record($clbenstransfcodigo->sql_query_file(null,"*",null," t95_codbem = $t43_bem "));
    	if ($clbenstransfcodigo->numrows>0){
    		$bem_trans .= $vir." ".$t43_bem;
    		$vir=","; 
    	}    	
    }
    if ($bem_trans!=""){
    	db_msgbox("Não é possivel alterar!!Bens $bem_trans transferidos!!");
    }
    db_fieldsmemory($result,0);    
  } 
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
<br><br>
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	  include("forms/db_frmbensaltlote.php");
	?>
    </center>
    </td>
  </tr>
</table>
  <? 
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<?
if(isset($alterar) && $erro_msg!=""){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    if($clbens->erro_campo!=""){
      echo "<script> document.form1.".$clbens->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbens->erro_campo.".focus();</script>";
    };
  }
}
if(isset($chavepesquisa)){
	/*
  if($desabilitar_campos == 'false'){
   echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.bensbaix.disabled=false;
         parent.document.formaba.bensimoveis.disabled=false;
         parent.document.formaba.bensmater.disabled=false;
         top.corpo.iframe_bensbaix.location.href='pat1_bensbaix001.php?t55_codbem=".@$chavepesquisa."';
         top.corpo.iframe_bensimoveis.location.href='pat1_bensimoveis001.php?db_opcaoal=22&t54_codbem=".@$chavepesquisa."';
         top.corpo.iframe_bensmater.location.href='pat1_bensmater001.php?db_opcaoal=22&t53_codbem=".@$chavepesquisa."';
       }\n
    js_db_libera();
  </script>\n
 ";
  }else{
  echo "
  <script>
      function js_db_bloqueia(){
	 parent.document.formaba.bensbaix.disabled=false;
         parent.document.formaba.bensimoveis.disabled=false;
         parent.document.formaba.bensmater.disabled=false;
         top.corpo.iframe_bensbaix.location.href='pat1_bensbaix001.php?t55_codbem=".@$chavepesquisa."';
         top.corpo.iframe_bensimoveis.location.href='pat1_bensimoveis001.php?db_opcaoal=33&t54_codbem=".@$chavepesquisa."';
         top.corpo.iframe_bensmater.location.href='pat1_bensmater001.php?db_opcaoal=33&t53_codbem=".@$chavepesquisa."';
      }\n
    js_db_bloqueia();
  </script>\n   
 ";
  }
  */
}
 if(($db_opcao==22||$db_opcao==33) && $msg_erro==""){
    echo "<script>js_pesquisa();</script>";
 }
?>