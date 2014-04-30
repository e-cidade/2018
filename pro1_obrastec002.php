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
include("libs/db_utils.php");
include("classes/db_obrastec_classe.php");
include("classes/db_obrashabite_classe.php");
include("classes/db_obrastecnicos_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

db_postmemory($HTTP_POST_VARS);

$clobrastec			 = new cl_obrastec;
$clobrastecnicos = new cl_obrastecnicos;
$clobrashabite	 = new cl_obrashabite;

$db_opcao  = 22;
$db_botao  = false;
$lContinue = true;

if(isset($alterar)){
  
  $db_opcao = 2;

	$rsObrasTecAnt = $clobrastec->sql_record($clobrastec->sql_query_file($ob15_sequencial));
  
	$oObrasTecAnt  = db_utils::fieldsMemory($rsObrasTecAnt,0);
	
	if ($oObrasTecAnt->ob15_tipo != $ob15_tipo) {
		
		if ($ob15_tipo == 2) {	
			
			$rsObrasTecnicos = $clobrastecnicos->sql_record($clobrastecnicos->sql_query_file(null,"*",null," ob20_obrastec = $ob15_sequencial"));  
			if ($clobrastecnicos->numrows > 0) {
				 db_msgbox(_M('tributario.projetos.db_frmobrastec.tecnico_em_obra'));
				 $lContinue = false;
			}

		} else if ($ob15_tipo == 1) {	
			
			$rsObrasHabite = $clobrashabite->sql_record($clobrashabite->sql_query_file(null,"*",null," ob09_engprefeitura = $ob15_sequencial"));	
			if ($clobrashabite->numrows > 0) {
				 db_msgbox(_M('tributario.projetos.db_frmobrastec.tecnico_em_habitese'));
				 $lContinue = false;
			}

		}
	
	}

  if ($lContinue) {
		db_inicio_transacao();
			$clobrastec->alterar($ob15_sequencial);
		db_fim_transacao();
	}else{
		$db_opcao = 22;
	}

}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clobrastec->sql_record($clobrastec->sql_query($chavepesquisa)); 
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
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
	<?
	include("forms/db_frmobrastec.php");
	?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($clobrastec->erro_status=="0"){
    $clobrastec->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clobrastec->erro_campo!=""){
      echo "<script> document.form1.".$clobrastec->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clobrastec->erro_campo.".focus();</script>";
    };
  }else{
    $clobrastec->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>