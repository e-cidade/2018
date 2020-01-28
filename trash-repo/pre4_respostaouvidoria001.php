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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_mail_class.php");
require_once("libs/db_utils.php");
require_once("classes/db_db_ouvidoria_classe.php");
require_once("dbforms/db_funcoes.php");
$cl_db_ouvidoria = new cl_db_ouvidoria();

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

$oPost = db_utils::postMemory($HTTP_POST_VARS);

$oConfigDBpref    = db_utils::getDao("configdbpref");
$rsConfigDBpref   = $oConfigDBpref->sql_record($oConfigDBpref->sql_query_file(db_getsession('DB_instit'),"w13_emailadmin"));
$email_remetente  = db_utils::fieldsMemory($rsConfigDBpref,0)->w13_emailadmin; 

if(isset($oPost->enviar)) {
	
	$email          = $oPost->email;
  $assunto        = $oPost->tipo;
  $corpo_email    = $oPost->corpo_email;
  $id_ouvidoria   = $oPost->id_ouvidoria;
  $email_remetente = $oPost->email_remetente;
  
  $oMail    = new mail();
  $oMail->setUserMail($email_remetente);
  $oMail->setsEmailTo($email);
  $oMail->setsSubject($assunto);
  $oMail->setsMsg(nl2br($corpo_email));
  $sMsgMail = $oMail->Send();
  if (substr($sMsgMail,0,1) != "0") {
    db_msgbox($sMsgMail);
  
	  db_inicio_transacao();

	  $cl_db_ouvidoria->po01_sequencial = $id_ouvidoria;
	  $cl_db_ouvidoria->po01_revisado   = date("Y-m-d");
	  $cl_db_ouvidoria->po01_id_usuario = db_getsession("DB_id_usuario");
	  $cl_db_ouvidoria->po01_texto      = "{$email_remetete} \n {$corpo_email}";
	  $cl_db_ouvidoria->alterar($id_ouvidoria);
    if ($cl_db_ouvidoria->erro_status == "0")	 {
    	db_fim_transacao(true);
    	echo "<br><br><Br>Erro atualizando a tabela db_ouvidoria<br>";
    	exit;    	
    } else {
	   db_fim_transacao();
		}
		
  } else {
  	db_msgbox($sMsgMail);
  }
  
  db_redireciona();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript">
function js_emite(){
  qry  = 'idOuvidoria='+document.form1.id_ouvidoria.value;
  jan  = window.open('pre2_ouvidoria002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_download(url){
	qry  = 'filename='+url;
	var ifrme = document.getElementById("downloadFrame");
  ifrme.src = "pre4_downloadouvidoria001.php?"+qry;
}
</script>
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<br />
<br />
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<?
	if(isset($retorno)) {
		$sCampos  = "to_char(po01_data,'DD-MM-YYYY') 						as data,                        ";
	  $sCampos .= "po01_nome                       						as nome,w03_tipo as categoria,  ";
	  $sCampos .= "to_char(po01_datanascimento,'DD-MM-YYYY')	as datanascimento,              ";
	  $sCampos .= "po01_sexo                       						as sexo,                        ";
	  $sCampos .= "po01_profissao                  						as profissao,                   ";
	  $sCampos .= "po01_escolaridade               						as escolaridade,                ";
	  $sCampos .= "po01_cpf                        						as cpf,                         ";
	  $sCampos .= "po01_rg                         						as rg,                          ";
	  $sCampos .= "po01_telefone                   						as telefone,                    ";
	  $sCampos .= "po01_celular                    						as celular,                     ";
	  $sCampos .= "po01_enderecoresidencial        						as enderecoresidencial,         ";
	  $sCampos .= "po01_enderecocomercial          						as enderecocomercial,           ";
	  $sCampos .= "po01_cidade                     						as cidade,                      ";
	  $sCampos .= "db12_uf                         						as estado,                      ";
	  $sCampos .= "po01_sigilo                     						as sigilo,                      ";
	  $sCampos .= "po01_resposta                   						as resposta,                    ";
	  $sCampos .= "po01_tiporesposta               						as tiporesposta,                ";
	  $sCampos .= "po01_assunto                    						as assunto,                     ";
	  $sCampos .= "po01_mensagem                   						as comentario,                  ";
	  $sCampos .= "po01_url01                      						as url01,                       ";
	  $sCampos .= "po01_url02                      						as url02,                       ";
		$sCampos .= "po01_email                      						as email                        ";
		$sSql    = $cl_db_ouvidoria->sql_query($retorno, $sCampos);
		$result  = $cl_db_ouvidoria->sql_record($sSql);
    db_fieldsmemory($result,0);
  ?>
  <center>
  <iframe id="downloadFrame" style="display:none"></iframe>
  <table border="1" cellspacing="0" cellpadding="0">
    <tr> 
      <td align="center" valign="middle"> 
        <form method="post" name="form1">
          <input type="hidden" name="id_ouvidoria" value="<?=@$retorno?>">
	      <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr align="center" valign="middle"> 
              <td height="40" colspan="2"><u><em><strong>Solicita&ccedil;&atilde;o:</strong></em></u></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Data:</strong></td>
              <td width="87%"><?=@$data?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Nome:</strong></td>
              <td width="87%"><?=@$nome?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>E-mail:</strong></td>
              <td width="87%"><?=@$email?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Categoria:</strong></td>
              <td width="87%"><?=@$categoria?></td>
            </tr>
            <tr> 
              <? $x = array('f'=>'Não','t'=>'Sim');?>
              <td width="13%" height="20" nowrap><strong>Deseja manter o nome e<br /> dados em sigilo?</strong></td>
              <td width="87%"><?=@$x[$sigilo]?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Deseja receber resposta?</strong></td>
              <td width="87%"><?=@$x[$resposta]?></td>
            </tr>
            <? $x = array('0'=>'Carta','1'=>'E-mail','2'=>'Telefone');?>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Tipo da resposta?</strong></td>
              <td width="87%"><?=@$x[$tiporesposta]?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Data de nascimento:</strong></td>
              <td width="87%"><?=@($datanascimento!='null')?$datanascimento:''?></td>
            </tr>
            <tr> 
              <? $x = array('F'=>'Feminino','M'=>'Masculino');?>
              <td width="13%" height="20" nowrap><strong>Sexo:</strong></td>
              <td width="87%"><?=@$x[$sexo]?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Profissão:</strong></td>
              <td width="87%"><?=@$profissao?></td>
            </tr>
            <tr> 
              <? $x = array('0'=>'Não alfabetizado','1'=>'Nível fundamental','2'=>'Nível médio','3'=>'Graduado');?>
              <td width="13%" height="20" nowrap><strong>Escolaridade:</strong></td>
              <td width="87%"><?=@$x[$escolaridade]?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>CPF:</strong></td>
              <td width="87%"><?=@$cpf?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>RG:</strong></td>
              <td width="87%"><?=@$rg?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Telefone:</strong></td>
              <td width="87%"><?=@$telefone?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Celular:</strong></td>
              <td width="87%"><?=@$celular?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Endereço residencial:</strong></td>
              <td width="87%"><?=@$enderecoresidencial?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Endereço comercial:</strong></td>
              <td width="87%"><?=@$enderecocomercial?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Cidade:</strong></td>
              <td width="87%"><?=@$cidade?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Estado:</strong></td>
              <td width="87%"><?=@$estado?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Assunto:</strong></td>
              <td width="87%"><?=@$assunto?></td>
            </tr>
            <tr> 
              <td width="13%" height="20" nowrap><strong>Mensagem:</strong></td>
              <td width="87%"><?=@nl2br($comentario)?></td>
            </tr>
            <? if(!empty($url01)){ ?>
            <tr> 
              <td width="13%" height="20" nowrap><strong></strong></td>
              <td width="87%"><a href="javascript:void(0);" onclick="js_download('<?=@$url01?>');">Anexo 1</a></td>
            </tr>
            <?}?>
            <? if(!empty($url02)){ ?>
            <tr> 
              <td width="13%" height="20" nowrap><strong></strong></td>
              <td width="87%"><a href="javascript:void(0);" onclick="js_download('<?=@$url02?>');">Anexo 2</a></td>
            </tr>
            <?}?>
            <tr align="center" valign="middle"> 
              <td height="40" colspan="2" nowrap><u><em><strong>Resposta:</strong></em></u></td>
            </tr>
            <tr> 
              <td height="20" nowrap><strong>De:</strong></td>
              <td><input name="email_remetente" type="text" id="email_remetete" value="<?=@$email_remetente?>" size="50" maxlength="50" readonly></td>
            </tr>
            <tr> 
              <td height="20" nowrap><strong>Para:</strong></td>
              <td><input name="email" style="border:none" type="text" id="email" value="<?=@$email?>" size="50" maxlength="50" readonly></td>
            </tr>
            <tr> 
              <td height="20" nowrap><strong>Assunto:</strong></td>
              <td><input name="tipo" type="text" id="tipo" value="RE: <?=@$assunto?>" size="50" maxlength="50"></td>
            </tr>
            <tr align="left" valign="middle"> 
              <td height="20" colspan="2" nowrap> 
                <textarea name="corpo_email" cols="80" rows="10" id="corpo_email"></textarea>
              </td>
            </tr>
            <tr> 
              <td height="20" nowrap>&nbsp;</td>
              <td>
                <input type="submit" name="enviar" value="Enviar">
                <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
              </td>
            </tr>
          </table>
		</form>
		</td>      
    </tr>
	</table>
	</center>
	<?
	} else {
      $query = "SELECT po01_sequencial as dl_ouvidoria, 
      								 w03_tipo        as dl_categoria, 
      								 po01_nome       as dl_nome,
      								 po01_email      as dl_email, 
      								 po01_assunto    as dl_assunto, 
      								 to_char(po01_data,'DD-MM-YYYY')::varchar as data 
                FROM db_ouvidoria 
                	INNER JOIN db_tipo ON db_tipo.w03_codtipo = db_ouvidoria.po01_tipo
                WHERE po01_revisado IS NULL
                ORDER BY po01_sequencial ASC";
  echo "<center>";
	  db_lovrot($query,10,"pre4_respostaouvidoria001.php");
  echo "</center>";
	}//final do isset($retorno)
	?>
	<?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
	</td>
  </tr>
</table>
</center>
</body>
</html>