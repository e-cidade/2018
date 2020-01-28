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
include("classes/db_liclicita_classe.php");
include("classes/db_liclicitasituacao_classe.php");

$clrotulo             = new rotulocampo;
$clrotulo->label("l20_codigo");
$clrotulo->label("l11_obs");
$clliclicita          = new cl_liclicita;
$clliclicitasituacao  = new cl_liclicitasituacao;

db_postmemory($HTTP_POST_VARS);

$db_botao = true;
$db_name  = "enviar";
$btnFc    = '';
if (isset($revoga)){
    
  db_inicio_transacao();
  $sqlerro=false;
  $clliclicita->l20_licsituacao = '2';
  $clliclicita->alterar($l20_codigo);
	if ($clliclicita->erro_status == 0){

      $erromsg = $cl_liclicita->erro_msg;
			$sqlerro = true;		
	}
	if (!$sqlerro){

		$l11_sequencial = '';
    $clliclicitasituacao->l11_id_usuario  = DB_getSession("DB_id_usuario");
    $clliclicitasituacao->l11_licsituacao = '2';
    $clliclicitasituacao->l11_liclicita   = $clliclicita->l20_codigo;
		$clliclicitasituacao->l11_obs         = $l11_obs;
    $clliclicitasituacao->l11_data        = date("Y-m-d",DB_getSession("DB_datausu"));
    $clliclicitasituacao->l11_hora        = DB_hora();
	  $clliclicitasituacao->incluir($l11_sequencial);
    if ($clliclicitasituacao->erro_status == 0){
			$erro_msg = $clliclicitasituacao->erro_msg;
      $sqlerro = true;
	  }
	}
	db_fim_transacao($sqlerro);
	if ($sqlerro == true){
      db_msgbox($erromsg);
			//viar ;
			$db_name = "revoga";

	}else{
    db_msgbox("Licitação Revogada com Sucesso!");
    $l20_codigo = null;
	}

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_enviar(){
  if(document.form1.l20_codigo.value == ""){
      document.form1.l20_codigo.focus();
      alert("Informe o código da Licitação");
			return false;
  } else {
		  if (confirm('Confirma revogaçao da licitação?')){

				 return true;
			}else{
				return false;
			}
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.l20_codigo.focus();" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post" action="">
<table border='0'>
  <tr height="20px">
    <td ></td>
    <td ></td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tl20_codigo?>">
    <b>
    <?db_ancora('Licitação',"js_pesquisa_liclicita(true);",1);?>&nbsp;:
    </b> 
    </td>
    <td align="left" nowrap>
      <? 
        db_input("l20_codigo",6,$Il20_codigo,true,"text",4,"onblur='js_pesquisa_liclicita(false)'");
      ?>
    </td>
  </tr>
  <tr height="20px">
	<?
    if (isset($enviar)){
     $db_name  = "revoga";
		 $db_botao = false;
		 $btnFc    = "return js_enviar()";


	?>
    <td><b><?=$Ll11_obs;?></b></td>
    <td><?
		
		db_textarea("l11_obs",5,30,$Il11_obs,true,'text',1);
		
		?>
		</td>
		<?

		}
		?>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="<?=$db_name;?>" type="submit" onclick="<?=$btnFc;?>;" value="Enviar dados" <?=($db_botao == true?"disabled":"")?>>
    </td>
  </tr>
</table>
</form>
</center>
<? 
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_pesquisa_liclicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicitemanu.php?funcao_js=parent.js_mostraliclicita1|l20_codigo','Pesquisa',true);
  }else{
     if(document.form1.l20_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicitemanu.php?pesquisa_chave='+document.form1.l20_codigo.value+'&funcao_js=parent.js_mostraliclicita','Pesquisa',false);
     }else{
       document.form1.l20_codigo.value = ''; 
     }
  }
}
function js_mostraliclicita(chave,erro){
  if(erro==true){ 
      document.form1.<?=$db_name;?>.disabled  = true;
      alert("Licitacao ja julgada,revogada  ou com autorizacao ativa.");
      document.form1.l20_codigo.value = ''; 
      document.form1.l20_codigo.focus(); 
  } else {
      document.form1.<?=$db_name;?>.disabled  = false;
      document.form1.l20_codigo.value = chave; 
  }
}
function js_mostraliclicita1(chave1){
   document.form1.l20_codigo.value = chave1;  
   db_iframe_liclicita.hide();
   document.form1.<?=$db_name;?>.disabled  = false;
}
</script>
</body>
</html>