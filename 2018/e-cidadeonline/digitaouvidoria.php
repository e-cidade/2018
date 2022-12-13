<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
require_once("libs/db_utils.php");
include("classes/db_db_tipo_classe.php");
include("classes/db_db_uf_classe.php");
include("classes/db_db_ouvidoria_classe.php");
include("dbforms/db_funcoes.php");
@session_start();
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$result = pg_exec("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref 
                       WHERE m_arquivo = 'digitaouvidoria.php'
                       ORDER BY m_descricao
                       ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}
$db_verificaip = db_verifica_ip();
$cl_db_ouvidoria = new cl_db_ouvidoria;
$cl_db_tipo = new cl_db_tipo;
$cl_db_uf = new cl_db_uf;

$cl_db_ouvidoria->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("w03_tipo");

$ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];

$oConfigDBpref    = db_utils::getDao("configdbpref");
$rsConfigDBpref   = $oConfigDBpref->sql_record($oConfigDBpref->sql_query_file(db_getsession('DB_instit'),"w13_uploadarquivos"));
$upload_path  = db_utils::fieldsMemory($rsConfigDBpref,0)->w13_uploadarquivos;

mens_help();
db_mensagem("ouvidoria_cab","ouvidoria_rod");
db_postmemory($HTTP_POST_VARS);
if(isset($HTTP_POST_VARS["confirma"])) {  
$cl_db_ouvidoria->po01_data = date('Y-m-d', db_getsession('DB_datausu'));

$DB_MSG = "";
if(!empty($_FILES)) {
  $allowedExts = array("gif", "jpg", "jpeg", "png", "zip", "rar", "doc", "docx", "txt", "pdf");
  $fileTypes = array("image/gif", "image/jpeg", "image/pjpeg", "image/png", 
                     "application/x-compressed", "application/x-zip-compressed", 
                     "application/zip", "multipart/x-zip", "application/rar", 
                     "application/x-rar", "application/x-rar-compressed",
                     "application/msword", 
                     "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                     "text/plain", "application/pdf");

  foreach ($_FILES as $k => $v) {
  	if(!empty($_FILES[$k]["name"])) {
      $extension = end(explode(".", $_FILES[$k]["name"]));
      if (in_array($_FILES[$k]["type"], $fileTypes) && ($_FILES[$k]["size"] < 5242880) && in_array($extension, $allowedExts)) {
        if ($_FILES[$k]["error"] > 0) {
          $DB_MSG .= "<tr><td>Ocorreu um erro no upload do arquivo " . substr($k, -2) . ".</tr></td>";
        } else {
        	//verifica se o diretorio uploads existe, caso nao exista, cria o diretorio
        	if (!file_exists($upload_path)) {
        		mkdir($upload_path, 0775);
        	}	
        	
          //gera um nome unico aleatorio para o arquivo
          $uniquename = md5(uniqid(rand(), true));
          if($k=="po01_url01") {
            $url01 = $uniquename . "." . $extension;
            $cl_db_ouvidoria->po01_url01 = $url01;
            $file01 = $_FILES[$k]["tmp_name"];
          } else if($k=="po01_url02") {
            $url02 = $uniquename . "." . $extension;
            $cl_db_ouvidoria->po01_url02 = $url02;
            $file02 = $_FILES[$k]["tmp_name"];
          }
        }
      } else {
        $DB_MSG .= "<tr><td>Arquivo " . substr($k, -2) . " em formato ou tamanho inválido.</tr></td>";
      }
  	}
  }
}

if(empty($DB_MSG)) {
  $cl_db_ouvidoria->incluir(@$po01_sequencial);
  if($cl_db_ouvidoria->erro_status == '0') {
    $DB_MSG = "<tr><td>" . $cl_db_ouvidoria->erro_msg . "</tr></td>";
  } else {
  	if(!empty($url01)) {
  	  move_uploaded_file($file01, $upload_path . $url01);		
  	} 
  	if(!empty($url02)) {
  	  move_uploaded_file($file02, $upload_path . $url02);		
  	}
    echo "<script>location.href = 'digitaouvidoria.php?".base64_encode('mensagem="Mensagem enviada com sucesso."')."'</script>";
  }
}

}

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script language="JavaScript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function validate(email) {
   var reg = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
   if(!reg.test(email)) {
      return false;
   }
   return true;
}
	
function js_submit() {
	document.getElementById("error-list").innerHTML = ("");
	
	var retorno = true;
	var erro = '<table border="0" cellspacing="0" cellpadding="0" class="texto"><tr><td><em>Favor corrigir as seguintes informações:</em></td></tr>';
	if(document.form1.po01_nome.value.trim() == "") {
		erro += "<tr><td>Campo Nome não informado.</td></tr>";
		retorno = false;
	}

	if(!validate(document.form1.po01_email.value)) {
		erro += "<tr><td>Campo E-mail inválido.</td></tr>";
		retorno = false;
	}

	if(document.form1.po01_tipo.value.trim() == "") {
		erro += "<tr><td>Campo Categoria não informado.</td></tr>";
		retorno = false;
	}

	if(document.form1.po01_db_uf.value.trim() == "") {
		erro += "<tr><td>Campo Estado não informado.</td></tr>";
		retorno = false;
	}

	if(document.form1.po01_assunto.value.trim() == "") {
		erro += "<tr><td>Campo Assunto não informado.</td></tr>";
		retorno = false;
	}

	if(document.form1.po01_mensagem.value.trim() == "") {
		erro += "<tr><td>Campo Mensagem não informado.</td></tr>";
		retorno = false;
	}

	if(document.form1.po01_tiporesposta.value == "1" && 
	   document.form1.po01_enderecoresidencial.value.trim() == "" && 
	   document.form1.po01_enderecocomercial.value.trim() == "") {
		erro += "<tr><td>Para o Tipo de resposta Carta é necessário informar o Endereço residencial ou o Endereço comercial.</td></tr>";
		retorno = false;
	}

	if(document.form1.po01_tiporesposta.value == "2" && 
	   document.form1.po01_telefone.value.trim() == "" && 
	   document.form1.po01_celular.value.trim() == "") {
		erro += "<tr><td>Para o Tipo de resposta Telefone é necessário informar o Telefone fixo com DDD ou o Telefone celular com DDD.</td></tr>";
		retorno = false;
	}

	if(!retorno)
		document.getElementById("error-list").innerHTML = (erro + "</table>");

	return retorno;
}

function js_tipo_resposta() {
	if(document.form1.po01_resposta.value=='f') {
		document.getElementById("po01_tiporesposta_row").style.display = "none";
	} else if(document.form1.po01_resposta.value=='t') {
		document.getElementById("po01_tiporesposta_row").style.display = "table-row";
	}
}
</script>
<style type="text/css">
em {
	color: red;
}
<?db_estilosite();?>
</style>
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<br>
<center>
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
  <tr>
   <td height="50" align="<?=$DB_align1?>">
    <?=$DB_mens1?>
    <h3>Por favor use o formulário a seguir para enviar uma mensagem à Ouvidoria. Campos obrigatórios estão marcados com <em>*</em></h3>
   </td>
  </tr>
  <tr align="center">
    <td id="error-list">
      <? if(!empty($DB_MSG)) { ?>
      <table border="0" cellspacing="0" cellpadding="0" class="texto"><tr><td><em>Favor corrigir as seguintes informações:</em></td></tr>
      	<?=@$DB_MSG; ?>
      </table>
    <?}?>
    </td>
  <tr>
    <td align="center" valign="middle">
      <form name="form1" method="post" enctype="multipart/form-data" onsubmit="return js_submit();">
       <table border="0" cellspacing="0" cellpadding="0" class="texto">
        <tr>
          <td><?=@$Lpo01_nome?><em>*</em></td>
          <td>
            <input name="po01_nome" type="text" value="<?=@$po01_nome?>" id="po01_nome" size="50" maxlength="100" />
          </td>
        </tr>
        <tr>
          <td><?=@$Lpo01_email?><em>*</em></td>
          <td>
            <input name="po01_email" type="text" value="<?=@$po01_email?>" id="po01_email" size="50" maxlength="255" />
          </td>
        </tr>
        <tr>
          <td><?=@$Lpo01_tipo?><em>*</em></td>
          <td> 
            <select name="po01_tipo" id="po01_tipo">
              <option value="">-</option>
            <?
              $results = pg_fetch_all($cl_db_tipo->sql_record($cl_db_tipo->sql_query("","*","","")));
              foreach ($results as $result) {
            ?>
              <option value="<?=@$result['w03_codtipo']?>" <?=@($result['w03_codtipo']==$po01_tipo?"selected='selected'":"")?>><?=@$result['w03_tipo']?></option>
            <?}?>
            </select>
          </td>
        <tr>
        <tr>
          <td><?=@$Lpo01_sigilo?></td>
          <td> 
            <select name="po01_sigilo" id="po01_sigilo">
            <?
              $x = array('f'=>'Não','t'=>'Sim');
              foreach ($x as $k=>$v) {
            ?>
              <option value="<?=@$k?>" <?=@((isset($po01_sigilo) && $k==$po01_sigilo)?"selected='selected'":"")?>><?=@$v?></option>
            <?}?>
            </select>
          </td>
        </tr>
        <tr>
          <td><?=@$Lpo01_resposta?></td>
          <td> 
            <select name="po01_resposta" id="po01_resposta" onchange="js_tipo_resposta()">
            <?
              $x = array('f'=>'Não','t'=>'Sim');
              foreach ($x as $k=>$v) {
            ?>
              <option value="<?=@$k?>" <?=@((isset($po01_resposta) && $k==$po01_resposta)?"selected='selected'":"")?>><?=@$v?></option>
            <?}?>
            </select>
          </td>
        </tr>
        <tr id="po01_tiporesposta_row" style="display:none">
          <td><?=@$Lpo01_tiporesposta?></td>
          <td> 
            <select name="po01_tiporesposta" id="po01_tiporesposta">
            <?
              $x = array('0'=>'E-mail','1'=>'Carta','2'=>'Telefone');
              foreach ($x as $k=>$v) {
            ?>
              <option value="<?=@$k?>" <?=@((isset($po01_sigilo) && $k==$po01_sigilo)?"selected='selected'":"")?>><?=@$v?></option>
            <?}?>
            </select>
          </td>
        </tr>
        <tr>
          <td><?=@$Lpo01_datanascimento?></td>
          <td> 
            <input name="po01_datanascimento" type="text" value="<?=@$po01_datanascimento?>" id="po01_datanascimento" size="10" maxlength="10" />
          </td>
        </tr>
        <tr>
          <td><?=@$Lpo01_sexo?></td>
          <td> 
            <select name="po01_sexo" id="po01_sexo">
            <?
              $x = array('' => '-', 'F'=>'Feminino','M'=>'Masculino');
              foreach ($x as $k=>$v) {
            ?>
              <option value="<?=@$k?>" <?=@((isset($sexo) && $k==$sexo)?"selected='selected'":"")?>><?=@$v?></option>
            <?}?>
            </select>
          </td>
        </tr>
        <tr>
          <td><?=@$Lpo01_profissao?></td>
          <td> 
            <input name="po01_profissao" type="text" value="<?=@$po01_profissao?>" id="po01_profissao" size="20" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td><?=@$Lpo01_escolaridade?></td>
          <td> 
            <select name="po01_escolaridade" id="po01_escolaridade">
            <?
              $x = array('' => '-', '0'=>'Não alfabetizado','1'=>'Nível fundamental','2'=>'Nível médio','3'=>'Graduado');
              foreach ($x as $k=>$v) {
            ?>
              <option value="<?=@$k?>" <?=@((isset($po01_escolaridade) && $k==$po01_escolaridade)?"selected='selected'":"")?>><?=@$v?></option>
            <?}?>
            </select>
          </td>
        </tr>
        <tr>
          <td><?=@$Lpo01_cpf?></td>
          <td> 
            <input name="po01_cpf" type="text" value="<?=@$po01_cpf?>" id="po01_cpf" size="20" maxlength="20" />
          </td>
        </tr>
        <tr>
          <td><?=@$Lpo01_rg?></td>
          <td> 
            <input name="po01_rg" type="text" value="<?=@$po01_rg?>" id="po01_rg" size="20" maxlength="20" />
          </td>
        </tr>
        <tr>
          <td><?=@$Lpo01_telefone?></td>
        <td> 
          <input name="po01_telefone" type="text" value="<?=@$po01_telefone?>" id="po01_telefone" size="20" maxlength="20" />
        </td>
      </tr>
      <tr>
        <td><?=@$Lpo01_celular?></td>
        <td> 
          <input name="po01_celular" type="text" value="<?=@$po01_celular?>" id="po01_celular" size="20" maxlength="20" />
        </td>
      </tr>
      <tr>
        <td><?=@$Lpo01_enderecoresidencial?></td>
        <td> 
          <input name="po01_enderecoresidencial" type="text" value="<?=@$po01_enderecoresidencial?>" id="po01_enderecoresidencial" size="50" maxlength="255" />
        </td>
      </tr>
      <tr>
        <td><?=@$Lpo01_enderecocomercial?></td>
        <td> 
          <input name="po01_enderecocomercial" type="text" value="<?=@$po01_enderecocomercial?>" id="po01_enderecocomercial" size="50" maxlength="255" />
        </td>
      </tr>
      <tr>
        <td><?=@$Lpo01_cidade?></td>
        <td> 
          <input name="po01_cidade" type="text" id="po01_cidade" value="<?=@$po01_cidade?>" size="30" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td><?=@$Lpo01_db_uf?><em>*</em></td>
        <td> 
          <select name="po01_db_uf" id="po01_db_uf">
            <option value="">-</option>
          <?
            $results = pg_fetch_all($cl_db_uf->sql_record($cl_db_uf->sql_query("","*","db12_uf","")));
            foreach ($results as $result) {
          ?>
            <option value="<?=@$result['db12_codigo']?>" <?=@($result['db12_codigo']==$po01_db_uf?"selected='selected'":"")?>><?=@$result['db12_uf']?></option>
          <?}?>
          </select>
        </td>
      </tr>
      <tr>
        <td><?=@$Lpo01_assunto?><em>*</em></td>
        <td> 
          <input name="po01_assunto" type="text" value="<?=@$po01_assunto?>" id="po01_assunto" size="50" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td>
         <?=@$Lpo01_mensagem?><em>*</em>
        </td>
        <td> 
          <textarea name="po01_mensagem" id="po01_mensagem" rows="3" cols="50"><?=@$po01_mensagem?></textarea>
        </td>
      </tr>
      <tr>
        <td><b>Anexos:</b></td>
        <td>
          <input name="po01_url01" type="file" id="po01_url01" size="25" maxlength="255" /><br />
          <input name="po01_url02" type="file" id="po01_url02" size="25" maxlength="255" />
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          Tipos de arquivos aceitos: *.gif, *.jpg, *.png, *.zip, *.rar, *.doc, *.docx, *.txt, *.pdf
          <br />
          Tamanho máximo de arquivos: 5124 Kb (5.00 Mb) 
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <br />
          Antes de enviar por favor certifique-se do seguinte
          <br />
          <ul>
      	    <li>
      	      Todas informações necessárias foram preenchidas corretamente.
      	    </li>
      	    <li>
              Todas informações estão corretas e livres de erros.
      	    </li>
          </ul> 
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          Nós registraremos:
        <br />
        <ul>
      	  <li>
      	    <input name="po01_ip" type="hidden"  value="<?=@$ip ?>" >
      	    <?=@$ip ?> como seu IP
      	  </li>
      	  <li>
            a data de envio
      	  </li>
        </ul> 
      </td>
    </tr>
    <tr>
      <td align="right" valign="middle">
        <input type="submit" class="botao" name="confirma" value="Enviar">
        <input type="reset" name="cancela" class="botao" value="Limpar">
      </td>
    </tr>
  </table>
</form>
</td>
</tr>
<tr>
 <td align="<?=$DB_align2?>">
  <?=$DB_mens2?>
 </td>
</tr>
</table>
</center>
<?
db_logs("","",0,"Acesso a Ouvidoria.");
if(isset($mensagem))
  echo "<script>alert('".$mensagem."'); location.href= 'digitaouvidoria.php'</script>\n";
?>