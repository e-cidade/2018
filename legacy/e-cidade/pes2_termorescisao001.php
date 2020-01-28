<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
variavel = 1;
function js_emite(){

  if(document.form1.selregist){
    for(i=0; i< document.form1.selregist.length; i++){
      document.form1.selregist.options[i].selected = true;
    }
  }

  jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  document.form1.action = "pes2_termorescisao002.php";
  document.form1.method = "post";
  document.form1.target = 'safo' + variavel ++;
  document.form1.submit();
  document.form1.action = "";
  document.form1.method = "";
  document.form1.target = "";

  // jan = window.open('pes2_termorescisao002.php?&ano='+document.form1.DBtxt23.value+'&mes='+document.form1.DBtxt25.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  // jan.moveTo(0,0);
}
function js_setacampo(){
  if(document.form1.registro1){
    js_tabulacaoforms("form1","registro1",true,1,"registro1",true);
  }else if(document.form1.rh01_regist){
    js_tabulacaoforms("form1","rh01_regist",true,1,"rh01_regist",true);
  }else if(document.form1.tipofil){
    js_tabulacaoforms("form1","tipofil",true,1,"tipofil",true);
  }else{
    js_tabulacaoforms("form1","anofolha",true,1,"anofolha",true);
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_setacampo();" bgcolor="#cccccc">

<style type="text/css">

	#fieldTermoRescisao {
		width:300px;margin:30px auto 0 auto;
	}

	#fieldTermoRescisao td {
		text-align: left;
	}
	 
	select {
		width:100px;
	}
</style>
	
<form name="form1" method="post" action="">
	 
	<fieldset id="fieldTermoRescisao">
		 
		<legend><strong>Termo de Rescisão</strong></legend>
		 
		<table>
			
			<?php
				include("dbforms/db_classesgenericas.php");
				$geraform               = new cl_formulario_rel_pes;
				$geraform->usaregi      = true;
				$geraform->strngtipores = "gm";
				$geraform->onchpad      = true;
				$geraform->gera_form(db_anofolha(), db_mesfolha());
			?>

			<tr>
				<td>
					<strong>Homolognet: </strong>
				</td>
				<td>
					<?php db_select('homolognet', array('true' => 'Sim', '0' => 'Não'), true, 2); ?>
				</td>
			</tr>
			
		</table>
	</fieldset>
	 
	<br />
			
	<center>
		<input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" onblur="js_tabulacaoforms('form1','anofolha',true,1,'anofolha',true);">
	</center>
	 
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>