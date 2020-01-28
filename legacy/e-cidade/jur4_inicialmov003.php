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
include("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_situacao_classe.php");
include("classes/db_inicialmov_classe.php");
include("classes/db_inicial_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");

db_postmemory($_POST);
db_postmemory($_GET);

$clsituacao   = new cl_situacao;
$clinicial    = new cl_inicial;
$clinicialmov = new cl_inicialmov;

$disable  = ""; 

$clrotulo = new rotulocampo;
$clrotulo->label("v50_inicial");
$clrotulo->label("v52_descr");
$clinicialmov->rotulo->label();
$clinicialmov->rotulo->tlabel();

if ( isset($excluir) ) {
	
	// faz a consulta para validar se a situação e número da inicial são válidos

  // situação
	$clsituacao->sql_record($clsituacao->sql_query_file($v56_codsit));      

  // inicial
	$sSql     = $clinicial->sql_query_file($v50_inicial,"v50_inicial, v50_codmov", null, "v50_inicial = $v50_inicial");
  $rResult  = $clinicial->sql_record($sSql);
  $oInicial = db_utils::fieldsMemory($rResult, 0);	

  // se existirem registro válidos continua a exclusão
	if ( ($clinicial->numrows > 0) and ($clsituacao->numrows > 0) ) {

    db_inicio_transacao();

   	// faz a consulta para obter a chave primária da movimentação da inicial que será excluída
		$sSql     = $clinicial->sql_query_file(null, "v50_codmov", null, "v50_inicial = {$v50_inicial}");
	 	$rResult  = $clinicial->sql_record($sSql);      
   	$oRetorno = db_utils::fieldsMemory($rResult, 0);

		// exclui a movimentação da inicial
		$clinicialmov->v56_codmov = $oRetorno->v50_codmov;
    $clinicialmov->excluir($oRetorno->v50_codmov);
	 
	  // consulta a última situação para a inicial
		$sSql     = " select max(v56_codmov) as	v56_codmov		 		  	";
		$sSql    .= "   from inicialmov														  ";
    $sSql    .= " where  v56_inicial = {$oInicial->v50_inicial} ";

		$rResult  = $clinicialmov->sql_record($sSql);
    $oRetorno = db_utils::fieldsMemory($rResult, 0);

		// atualiza a situação da inicial com a última movimentação dela
		
		$clinicial->v50_codmov= $oRetorno->v56_codmov;
    $clinicial->alterar($v50_inicial);
		
		if ( ( $clinicialmov->erro_status != 0 ) or ( $clinicial->erro_status != 0 ) ) {
      db_fim_transacao(false);
  	} else {
    	db_fim_transacao(true);	
		}	  	

	}  

// se a chave pesquisa estiver setada preenche os campos do formulário
} else if ( isset($chavepesquisa) ) {

  $v50_inicial = $chavepesquisa;

  // retorna o código do movimento da inicial
	$sSql     = $clinicial->sql_query($v50_inicial, "v50_codmov", null, "v50_inicial = $v50_inicial");
  $rResult  = $clinicial->sql_record($sSql);
  $oRetorno = db_utils::fieldsMemory($rResult, 0);

  // retorna os valores para preencher o formulário
  $sSql    = $clinicialmov->sql_query($oRetorno->v50_codmov, "v56_codsit, v52_descr, v56_obs");
	$rResult = $clinicialmov->sql_record($sSql);
  db_fieldsmemory($rResult, 0);

} else {
  $disable = "disabled";		
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
<table width="" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<center>
	<table width="" border="0" cellspacing="" cellpadding="">
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr> 
			<td height="" align="left" valign="top" bgcolor="#CCCCCC"> 
	  	<fieldset>
				<form name="form1" method="post" action="">
					<table> 
					
            <tr>
              <td>
                <input name="ini" type="hidden" value="<?=@$ini?>">
              </td> 
            </tr>
						
            <tr>
              <td nowrap title="<?=@$Tv50_inicial?>">
								<b> Inicial número: </b>
              </td>
              <td> 
	          		<?
	          		 db_input('v50_inicial',8,$Iv50_inicial,true,'text',3," onchange='js_pesquisav50_inicial(false);'")
	          		?>
              </td>
            </tr>
						
            <tr>
              <td nowrap title="<?=@$Tv56_codsit?>">
	          		<?
                  db_ancora(@$Lv56_codsit,"js_pesquisav56_codsit(true);",3);
                ?>
              </td>
              <td> 
	          		<?
	          		 db_input('v56_codsit',8,$Iv56_codsit,true,'text',3," onchange='js_pesquisav56_codsit(false);'");
	           		 db_input('v52_descr',40,$Iv52_descr,true,'text',3,'')
                ?>
              </td>
            </tr>
						
            <tr>
              <td nowrap title="<?=@$Tv56_codsit?>">
	           <?=@$Lv56_obs?>
              </td>
              <td> 
	          		<?
	          		 db_textarea('v56_obs',0,49,$Iv56_obs,true,'text',3) 
	          		?>
              </td>
            </tr>
						
            </table>
           </fieldset>
					  
					<center>
						<input name="excluir" type="submit"  value="Excluir" <?=$disable?>>
						<input name="pesquisar" type="button"  value="Pesquisar" onclick="js_pesquisa()">
					</center>

		</form>    
	</center>
	
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>
<script>

function js_pesquisa() {
	js_OpenJanelaIframe('top.corpo','db_iframe_inicial','func_inicialsit.php?funcao_js=parent.js_preenchepesquisa|v50_inicial','Pesquisa',true);
}

function js_preenchepesquisa(chave) {
  location.href = "jur4_inicialmov003.php?chavepesquisa="+chave;
	document.getElementById("v50_inicial").value = chave;
  db_iframe_inicial.hide();
}
	
function js_pesquisav56_codsit(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_situacao.php?funcao_js=parent.js_mostrasituacao1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  } else {
    db_iframe.jan.location.href = 'func_situacao.php?pesquisa_chave='+document.form1.v56_codsit.value+'&funcao_js=parent.js_mostrasituacao';
  }
}

function js_mostrasituacao(chave, erro){
  document.form1.v52_descr.value = chave; 
  if(erro==true){ 
    document.form1.v56_codsit.focus(); 
    document.form1.v56_codsit.value = ''; 
  }
}

function js_mostrasituacao1(chave1, chave2){
  document.form1.v56_codsit.value = chave1;
  document.form1.v52_descr.value = chave2;
  db_iframe.hide();
}

</script>
<?
// se a chave pesqusa não foi setada abre a janela para o usuário fazer a escolha do registro
// que ele deseja alterar
if ( !isset($chavepesquisa) ) {
	echo "<script> js_pesquisa(); </script>";
}

if ( isset($excluir) ) {
  	
  if ( $clinicialmov->erro_campo != "" ) {
    echo "<script> document.form1.".$clinicialmov->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clinicialmov->erro_campo.".focus();</script>";
  } else {
    $clinicialmov->erro(true,true);
  }
}  
?>