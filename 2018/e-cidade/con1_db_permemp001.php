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
include("dbforms/db_funcoes.php");

include("classes/db_db_permemp_classe.php");
include("classes/db_db_usupermemp_classe.php");
include("classes/db_db_depusuemp_classe.php");
include("classes/db_orcorgao_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_orcunidade_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcelemento_classe.php");

$clorcelemento = new cl_orcelemento;
$clorcorgao = new cl_orcorgao;
$cldb_usuarios = new cl_db_usuarios;
$cldb_depart = new cl_db_depart;
$clorcunidade = new cl_orcunidade;
$clorcdotacao = new cl_orcdotacao;
$cldb_permemp = new cl_db_permemp;
$cldb_usupermemp = new cl_db_usupermemp;
$cldb_depusuemp = new cl_db_depusuemp;

db_postmemory($HTTP_POST_VARS);
$anousu=db_getsession('DB_anousu');


if(isset($excluir_todos) && $excluir_todos!=""){
  $sqlerro=false;
  db_inicio_transacao();
  
   // apaga todas as permissoes do usuario/departamento  
  if(isset($coddepto) && $coddepto!=''){
	    $dbwhere="  db22_coddepto = $coddepto and db20_anousu=".db_getsession("DB_anousu");
	    // apaga permissoes deste departamento
            $res = $cldb_depusuemp->sql_record($cldb_depusuemp->sql_query(null,$coddepto,"*",null,$dbwhere));
	    if ($cldb_depusuemp->numrows > 0 ){
                $rows =  $cldb_depusuemp->numrows;
	        for ($x=0;$x < $rows ; $x++){
		    db_fieldsmemory($res,$x);

                    $cldb_depusuemp->excluir($db20_codperm,$id_usuario);		   
	            if($cldb_depusuemp->erro_status==0){
	                $sqlerro=true;
		        $erro_msg =  $cldb_depusuemp->erro_msg;
	            }
       	            $cldb_permemp->excluir($db20_codperm);
	            if ($cldb_permemp->erro_status==0){
	                $sqlerro=true;
	                $erro_msg =  $cldb_permemp->erro_msg;		       
	            }                  

	        } //end loop FOR
	    }  // end if	    	    	   
   }elseif(isset($id_usuario) && $id_usuario!=''){
	    $dbwhere="  db21_id_usuario = $id_usuario  and db20_anousu=".db_getsession("DB_anousu");  
	    // remove permissoes deste usuario	     
	    $res = $cldb_usupermemp->sql_record($cldb_usupermemp->sql_query(null,$id_usuario,"*",null,$dbwhere));
	    if ($cldb_usupermemp->numrows > 0 ){
                $rows = $cldb_usupermemp->numrows;
	        for ($x=0;$x < $rows ; $x++){
		    db_fieldsmemory($res,$x);

                    $cldb_usupermemp->excluir($db20_codperm,$id_usuario);		   
	            if($cldb_usupermemp->erro_status==0){
	                $sqlerro=true;
		        $erro_msg =  $cldb_usupermemp->erro_msg;
	            }
       	            $cldb_permemp->excluir($db20_codperm);
	            if ($cldb_permemp->erro_status==0){
	                $sqlerro=true;
	                $erro_msg =  $cldb_permemp->erro_msg;		       
	            }                  

	        }
 	    }  	    	    	   
	    
   }     
  db_fim_transacao($sqlerro);

} // end if 

if(isset($incluir_todos)){
  $sqlerro=false;
  db_inicio_transacao();
  
  
  // apaga todas as permissoes do usuario e insere novamente
  
  if(isset($coddepto) && $coddepto!=''){
	    $dbwhere="  db22_coddepto = $coddepto and db20_anousu=".db_getsession("DB_anousu");
	    // apaga permissoes deste departamento
            $res = $cldb_depusuemp->sql_record($cldb_depusuemp->sql_query(null,$coddepto,"*",null,$dbwhere));
	    if ($cldb_depusuemp->numrows > 0 ){
                $rows =  $cldb_depusuemp->numrows;
	        for ($x=0;$x < $rows ; $x++){
		    db_fieldsmemory($res,$x);

                    $cldb_depusuemp->excluir($db20_codperm,$id_usuario);		   
	            if($cldb_depusuemp->erro_status==0){
	                $sqlerro=true;
		        $erro_msg =  $cldb_depusuemp->erro_msg;
	            }
       	            $cldb_permemp->excluir($db20_codperm);
	            if ($cldb_permemp->erro_status==0){
	                $sqlerro=true;
	                $erro_msg =  $cldb_permemp->erro_msg;		       
	            }                  

	        }
	    }  	    	    	   

	    
	    
  }else if(isset($id_usuario) && $id_usuario!=''){
	    $dbwhere="  db21_id_usuario = $id_usuario  and db20_anousu=".db_getsession("DB_anousu");  
	    // remove permissoes deste usuario	     
	    $res = $cldb_usupermemp->sql_record($cldb_usupermemp->sql_query(null,$id_usuario,"*",null,$dbwhere));
	    if ($cldb_usupermemp->numrows > 0 ){
                $rows = $cldb_usupermemp->numrows;
	        for ($x=0;$x < $rows ; $x++){
		    db_fieldsmemory($res,$x);

                    $cldb_usupermemp->excluir($db20_codperm,$id_usuario);		   
	            if($cldb_usupermemp->erro_status==0){
	                $sqlerro=true;
		        $erro_msg =  $cldb_usupermemp->erro_msg;
	            }
       	            $cldb_permemp->excluir($db20_codperm);
	            if ($cldb_permemp->erro_status==0){
	                $sqlerro=true;
	                $erro_msg =  $cldb_permemp->erro_msg;		       
	            }                  

	        }
	    }  	    	    	   
	    
  }  
  
  
  
  
  // insere as permissoes por orgao
  $result = $clorcorgao->sql_record(
            $clorcorgao->sql_query(null,
                                   null,
                                   "o40_orgao as db20_orgao",
                                   "o40_orgao",
                                   "o40_anousu=".db_getsession("DB_anousu")));

  $numrows=$clorcorgao->numrows; 
  for($i=0; $i<$numrows; $i++){
    db_fieldsmemory($result,$i);
   
	if(isset($coddepto) && $coddepto!=''){
	    $dbwhere="  db22_coddepto = $coddepto ";  
	}else if(isset($id_usuario) && $id_usuario!=''){
	    $dbwhere="  db21_id_usuario = $id_usuario ";  
	}
	      
	if($sqlerro==false){
	  $cldb_permemp->db20_anousu=$anousu;
	  $cldb_permemp->db20_orgao=$db20_orgao;
	  $cldb_permemp->db20_unidade='0';
	  $cldb_permemp->db20_funcao='0';
	  $cldb_permemp->db20_subfuncao='0';
	  $cldb_permemp->db20_programa='0';
	  $cldb_permemp->db20_projativ='0';
	  $cldb_permemp->db20_codele='0';
	  $cldb_permemp->db20_codigo='0';
	  $cldb_permemp->db20_codperm='0';
	  $cldb_permemp->incluir(null);
	  $db20_codperm = $cldb_permemp->db20_codperm;
	  $erro_msg =  $cldb_permemp->erro_msg;
	  if($cldb_permemp->erro_status==0){
	    $sqlerro=true;
	  }
	}  
	if(isset($coddepto) && $coddepto!='' && $sqlerro==false){
	    $cldb_depusuemp->db22_codperm=$db20_codperm;
	    $cldb_depusuemp->db22_coddepto=$coddepto;
	    $cldb_depusuemp->incluir($db20_codperm,$coddepto);
	    $erro_msg =  $cldb_depusuemp->erro_msg;
	    if($cldb_depusuemp->erro_status==0){
	      $sqlerro=true;
	    }
	}else if(isset($id_usuario) && $sqlerro==false){
	    $cldb_usupermemp->db21_codperm=$db20_codperm;
	    $cldb_usupermemp->db21_id_usuario=$id_usuario;
	    $cldb_usupermemp->incluir($db20_codperm,$id_usuario);
	    $erro_msg =  $cldb_usupermemp->erro_msg;
	    if($cldb_usupermemp->erro_status==0){
	      $sqlerro=true;
	    }
	}  
	unset($db20_codperm);
  }
  db_fim_transacao($sqlerro);
  
}else if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();

/*Verifica se já não foi cadastrado*/
      if(isset($o56_elemento) && $o56_elemento!='0'){
	$result = $clorcelemento->sql_record(
	          $clorcelemento->sql_query_file(null,
		                                 null,
						 'o56_codele as db20_codele',
						 '',
						 " o56_anousu = ".db_getsession("DB_anousu")." and  
						   o56_elemento ='$o56_elemento' "));
	db_fieldsmemory($result,0);  
      }else{
	$db20_codele='0';
      }
      if(isset($coddepto) && $coddepto!=''){
	$dbwhere="  db22_coddepto = $coddepto ";  
      }else if(isset($id_usuario) && $id_usuario!=''){
	$dbwhere="  db21_id_usuario = $id_usuario ";  
      }
      $sql= $cldb_permemp->sql_query_origem(null,
                                            "db20_codperm",
					    "",
					    " $dbwhere and 
					      db20_anousu=$anousu and (db20_orgao=$db20_orgao and 
					                               db20_unidade=$db20_unidade and 
								       db20_funcao=$db20_funcao and 
								       db20_subfuncao=$db20_subfuncao and 
								       db20_programa=$db20_programa and 
								       db20_projativ=$db20_projativ and 
								       db20_codele=$db20_codele and 
								       db20_codigo=$db20_codigo)");
      $cldb_permemp->sql_record($sql); 
      if($cldb_permemp->numrows>0){
	  $cldb_permemp->erro_status='0'; 
	  $sqlerro=true;
	  $erro_msg='Seleção já cadastrada!';
      }    

      if ($db20_orgao == 0){
           $sqlerro  = true;
           $erro_msg = "Inclusão abortada. Escolher para este caso Incluir Todos";
      }
/*fim*/  
  
  
  
   if($sqlerro==false){
      $cldb_permemp->db20_anousu=$anousu;
      $cldb_permemp->db20_orgao=$db20_orgao;
      $cldb_permemp->db20_unidade=$db20_unidade;
      $cldb_permemp->db20_funcao=$db20_funcao;
      $cldb_permemp->db20_subfuncao=$db20_subfuncao;
      $cldb_permemp->db20_programa=$db20_programa;
      $cldb_permemp->db20_projativ=$db20_projativ;
      $cldb_permemp->db20_codele=$db20_codele;
      $cldb_permemp->db20_codigo=$db20_codigo;
      $cldb_permemp->db20_codperm=$db20_codperm;
      $cldb_permemp->db20_tipoperm=$db20_tipoperm;

      $cldb_permemp->incluir($db20_codperm);
      $db20_codperm = $cldb_permemp->db20_codperm;
      $erro_msg =  $cldb_permemp->erro_msg;
      if($cldb_permemp->erro_status==0){
	$sqlerro=true;
      }
   }  	
  if(isset($coddepto) && $coddepto!='' && $sqlerro==false){
    
      $cldb_depusuemp->db22_codperm=$db20_codperm;
      $cldb_depusuemp->db22_coddepto=$coddepto;
      $cldb_depusuemp->incluir($db20_codperm,$coddepto);
      $erro_msg =  $cldb_depusuemp->erro_msg;
      if($cldb_depusuemp->erro_status==0){
	$sqlerro=true;
      }
    
  }else if(isset($id_usuario) && $sqlerro==false){
      $cldb_usupermemp->db21_codperm=$db20_codperm;
      $cldb_usupermemp->db21_id_usuario=$id_usuario;
      $cldb_usupermemp->incluir($db20_codperm,$id_usuario);
      $erro_msg =  $cldb_usupermemp->erro_msg;
      if($cldb_usupermemp->erro_status==0){
	$sqlerro=true;
      }
  }  
  db_fim_transacao($sqlerro);
}else if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
/*Verifica se já não foi cadastrado*/
      if(isset($o56_elemento) && $o56_elemento!='0'){
	$result = $clorcelemento->sql_record(
 	           $clorcelemento->sql_query_file(null,null,
	                                          'o56_codele as db20_codele','',
						  " o56_anousu = ".db_getsession("DB_anousu")." and 
						    o56_elemento ='$o56_elemento' "));
	db_fieldsmemory($result,0);  
      }else{
	$db20_codele='0';
      }
      if(isset($coddepto) && $coddepto!=''){
	$dbwhere="  db22_coddepto = $coddepto and db20_anousu=".db_getsession("DB_anousu");  
      }else if(isset($id_usuario) && $id_usuario!=''){
	$dbwhere="  db21_id_usuario = $id_usuario and db20_anousu=".db_getsession("DB_anousu");  
      }     
      /*
      $sql= $cldb_permemp->sql_query_origem(null,"db20_codperm as codperm","",
                                            "$dbwhere and (db20_orgao=$db20_orgao or 
					                   db20_unidade=$db20_unidade or
							   db20_funcao=$db20_funcao or
							   db20_subfuncao=$db20_subfuncao or
							   db20_programa=$db20_programa or
							   db20_projativ=$db20_projativ or
							   db20_codele=$db20_codele or
							   db20_codigo=$db20_codigo or
							   db20_tipoperm=$db20_tipoperm							   
							   )");      
      $result88=$cldb_permemp->sql_record($sql); 
      $numrows=$cldb_permemp->numrows;
      if($numrows>0 ){
	 for($i=0; $i<$numrows; $i++){
	  db_fieldsmemory($result88,$i);
	  if($codperm!=$db20_codperm){
	    $cldb_permemp->erro_status='0'; 
	    $sqlerro=true;
	    $erro_msg='Não houveram alterações ou seleção já cadastrada! ';
	    break;
	  }
	}  
      }    
      */
/*fim*/  
  if($sqlerro==false){ 
    $cldb_permemp->alterar($db20_codperm);
    $erro_msg =  $cldb_permemp->erro_msg;
  }  
  db_fim_transacao();
}else if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  if(isset($coddepto) && $coddepto!="" && $sqlerro==false){
      $cldb_depusuemp->db22_codperm=$db20_codperm;
      $cldb_depusuemp->db22_coddepto=$coddepto;
      $cldb_depusuemp->excluir($db20_codperm,$coddepto);
      $erro_msg =  $cldb_depusuemp->erro_msg;
      if($cldb_depusuemp->erro_status==0){
	$sqlerro=true;
      }
    
  }else if(isset($id_usuario) && $sqlerro==false){
      $cldb_usupermemp->db21_codperm=$db20_codperm;
      $cldb_usupermemp->db21_id_usuario=$id_usuario;
      $cldb_usupermemp->excluir($db20_codperm,$id_usuario);
      $erro_msg =  $cldb_usupermemp->erro_msg;
      if($cldb_usupermemp->erro_status==0){
	$sqlerro=true;
      }
  }  

  $cldb_permemp->excluir($db20_codperm);
  $erro_msg =  $cldb_permemp->erro_msg;
  if($cldb_permemp->erro_status==0){
     $sqlerro=true;
  }
  db_fim_transacao($sqlerro);
}

if(isset($coddepto) && $coddepto!=''){
  $result= $cldb_depart->sql_record($cldb_depart->sql_query_file($coddepto,"descrdepto"));
  if($cldb_depart->numrows>0){
     db_fieldsmemory($result,0); 
  }else{
    unset($coddepto);
    unset($id_usuario);
  }   
}else if(isset($id_usuario) && $id_usuario!='') {
  $result= $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($id_usuario,"nome"));
  if($cldb_usuarios->numrows>0){
     db_fieldsmemory($result,0); 
  }else{
    unset($id_usuario);
    unset($coddepto);
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
<?
/* rotina quando já tiver sido selecionado o usuario ou o departamento       */
  if(isset($coddepto) || isset($id_usuario)){
?>
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdb_permemp.php");
	?>
    </center>
    </td>
  </tr>
<?
/*****************fim*********************/
/* para pegar o id_usario ou o codigo do departamento       */
 }else{
   
$clrotulo = new rotulocampo;
$clrotulo->label("id_usuario");
$clrotulo->label("nome");
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");

$db_botao = true;
$db_opcao = 1;
?>  
<form name='form1'>
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
   	 <center>
<table> 
  <tr>
    <td nowrap title="<?=@$Tid_usuario?>">
       <?
       db_ancora(@$Lnome,"js_usu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('id_usuario',7,$Iid_usuario,true,'text',$db_opcao," onchange='js_usu(false);'");
db_input('nome',40,$Inome,true,'text',3,'');
?>
    <td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcoddepto?>">
       <?
       db_ancora(@$Lcoddepto,"js_coddepto(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('coddepto',7,$Icoddepto,true,'text',$db_opcao," onchange='js_coddepto(false);'");
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'');
?>
    <td>
  </tr>
  <tr>
    <td colspan='2' align='center'>
      <input type='submit' onclick='return js_verifica();' name='entrar' value='Entrar'>
    </td>
  </tr>
</table>  
    </center>
    </td>
  </tr>
</form>
<script>
  function js_verifica(){
    obj= document.form1;
    if(obj.id_usuario.value=='' && obj.coddepto.value=='' ){
       alert('Informe o usuário ou o departamento!');
       return false;
    }
       return true;
  }
  function js_usu(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_db_usuario','func_db_usuarios.php?funcao_js=parent.js_mostrausu1|id_usuario|nome','Pesquisa',true);
    }else{
      usu= document.form1.id_usuario.value;
      if(usu!=""){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuario','func_db_usuarios.php?pesquisa_chave='+usu+'&funcao_js=parent.js_mostrausu','Pesquisa',false);
      }else{ 	
	document.form1.nome.value='';
      } 	
    }
  }
  function js_mostrausu1(chave1,chave2){
    document.form1.id_usuario.value = chave1;
    document.form1.nome.value = chave2;
    db_iframe_db_usuario.hide();
  }
  function js_mostrausu(chave,erro){
    document.form1.nome.value = chave; 
    if(erro==true){ 
      document.form1.id_usuario.focus(); 
      document.form1.id_usuario.value = ''; 
    }
  }
  function js_coddepto(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostracoddepto1|coddepto|descrdepto','Pesquisa',true);
    }else{
      coddepto = document.form1.coddepto.value;
      if(coddepto!=""){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+coddepto+'&funcao_js=parent.js_mostracoddepto','Pesquisa',false);
      }else{ 	
	document.form1.descrdepto.value='';
      } 	
    }
  }
  function js_mostracoddepto1(chave1,chave2){
    document.form1.coddepto.value = chave1;
    document.form1.descrdepto.value = chave2;
    db_iframe_db_depart.hide();
  }
  function js_mostracoddepto(chave,erro){
    document.form1.descrdepto.value = chave; 
    if(erro==true){ 
      document.form1.coddepto.focus(); 
      document.form1.coddepto.value = ''; 
    }
  }
  document.form1.id_usuario.focus();
</script>
<?
 }
/**********************fim**************************/
?>  
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir_todos) ||isset($incluir) || isset($alterar) || isset($excluir)){
    db_msgbox($erro_msg);
  if($cldb_permemp->erro_status=="1"){
    echo "<script>js_limpa();</script>";
  };
};
?>