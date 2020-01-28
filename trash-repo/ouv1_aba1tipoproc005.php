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
include("libs/db_utils.php");
include("classes/db_tipoproc_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$cltipoproc   = new cl_tipoproc;
$db_opcao     = 22;
$db_botao     = false;
$sqlerro      = false;

if(isset($oPost->db_opcao) && $oPost->db_opcao == "Alterar"){

  $result = $cltipoproc->sql_record(" select p58_codigo,
                                             p51_descr,
                                             p51_dtlimite as limite 
                                        from protprocesso 
                                             inner join tipoproc on p51_codigo = p58_codigo 
                                       where p58_codigo = $p51_codigo limit 1 ");
  if($cltipoproc->numrows > 0){
   db_fieldsmemory($result,0); 
   
   $d1 = substr($p51_dtlimite,6,4).substr($p51_dtlimite,3,2).substr($p51_dtlimite,0,2);
   $d2 = substr($limite,0,4).substr($limite,5,2).substr($limite,8,2); 
   if(db_getsession("DB_administrador") != 1 && $d2 != "" && $d2 < date('Ymd',db_getsession("DB_datausu")) ){
	db_msgbox('Aviso:\nAlteração não Permitida!\nData limite do processo menor que a data atual\nData Limite:'.db_formatar($limite,'d'));   	
   	$sqlerro = true;	   	
   }else if(db_getsession("DB_administrador") != 1 && $d1 > $d2 && $d2 != ""){
	db_msgbox('Aviso:\nAlteração não Permitida!\nData limite do processo menor que a data atual\nData Limite:'.db_formatar($limite,'d'));   	
   	$sqlerro = true;	
   }else if(db_getsession("DB_administrador") != 1 && $d1 == $d2){
   	db_msgbox('Aviso:\nJá existe um Processo cadastrado para o tipo escolhido!\nAlteração não permitida!');   	
   	$sqlerro = true;	
   }
  
   $cltipoproc->p51_descr = $p51_descr;
	  	
  }
  if($sqlerro == false){
   $lSqlErro = false;
   db_inicio_transacao();
   
    $cltipoproc->p51_descr = $oPost->p51_descr;
    $cltipoproc->alterar($p51_codigo);
    
     if ( $cltipoproc->erro_status == 0 ) {
       $lSqlErro = true;
       $sMsgErro = $cltipoproc->erro_msg; 
     }   
    
   db_fim_transacao($lSqlErro);
   
    if ( isset($sMsgErro) && $lSqlErro === true) {
      db_msgbox($sMsgErro);
  	} else {
  	  db_msgbox("Administrador: \\n - Alteração Efetuada com Sucesso!");
  	}   
  }
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $cltipoproc->sql_record($cltipoproc->sql_query(null,"*",null,"p51_codigo = $chavepesquisa 
                                                            and p51_instit=".db_getsession("DB_instit"))); 
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
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table width="80%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	  <?
	    include("forms/db_frmouvtipoproc.php");
	  ?>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<?
if (isset($oGet->chavepesquisa)) {
	
  echo "<script>
          parent.iframe_departamento.location.href='ouv1_aba2depto004.php?p51_codigo=".$oGet->chavepesquisa."&db_opcao=2';
          parent.iframe_formreclamacao.location.href='ouv1_aba3formrecl004.php?p51_codigo=".$oGet->chavepesquisa."&db_opcao=2';  
          parent.document.formaba.departamento.disabled   = false;
          parent.document.formaba.formreclamacao.disabled = false;              
        </script>"; 	
	
  if(isset($oPost->db_opcao) && $oPost->db_opcao == "Alterar"){	
    if($cltipoproc->erro_status == "0"){
      $db_botao=true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      if($cltipoproc->erro_campo != ""){
        echo "<script> document.form1.".$cltipoproc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$cltipoproc->erro_campo.".focus();</script>";
      }
    } else {
        echo "<script>
                parent.mo_camada('departamento');            
              </script>";  
    }
  }
}
?>