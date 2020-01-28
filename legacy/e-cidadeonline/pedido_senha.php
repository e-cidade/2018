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

session_start();

require_once("libs/db_conecta.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sql.php");
require_once("classes/db_cgm_classe.php");
require_once("libs/db_mail_class.php");
require_once("libs/db_encriptacao.php");

$oGet       = db_utils::postmemory($_GET);
$sVr        = $oGet->eqm;
$clcgm      = new cl_cgm();
$db_opcao   = 1;
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_logs("","",0,"Pedido de Senha.");

if(isset($sVr) && $sVr != 0){

  $sTitulo = "Esqueci Minha Senha";
  $sChave  = "f";
  $sHeader = "E";
  $sEsq    = "t";
} else {

  $sTitulo = "Pedido de Senha";
  $sChave  = "t";
  $sHeader = "P";
  $sEsq    = "f";
}

?>
<html>
<head>
<link href="config/estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/db_script.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<?
if (isset($cgccpf)) {

  $cgccpf = str_replace(".","",$cgccpf);
  $cgccpf = str_replace("/","",$cgccpf);
  $cgccpf = str_replace("-","",$cgccpf);

  $sql    = "select z01_numcgm,z01_nome,z01_email,z01_cgccpf from cgm where trim(z01_cgccpf) = '$cgccpf'";
  $result = @db_query($sql) or die(@pg_errormessage());

  if(@pg_num_rows($result) == 0){

     db_logs("","",0,"Solicitação de senha para fornecedor: cgc ou cpf não encontrado. $cgccpf");
		   if($w13_liberaatucgm=="t"){

		    $sMsg  = "Dados informados NÃO encontrados/atualizados no cadastro da Prefeitura!";
		    $sMsg .= "Você será direcionado para realizar o pedido de cadastro ou atualização de CGM agora.";
		    msgbox($sMsg);
		    db_redireciona("atualizaendereco.php?w11_cgccpf=$cgccpf&w11_email=$email_contribuinte&cgmlogin=0");
		    db_redireciona("pedido_senha.php");
		   }else{

		    $sMsg  = "Dados informados NÃO encontrados no cadastro da Prefeitura!";
		    $sMsg .= "Procure o balcão da Prefeitura para realizar seu cadastro.";
		    msgbox($sMsg);
		   }
     exit;

  } else {

   db_logs("","",0,"Solicitação de senha para fornecedor: cgc ou cpf - $cgccpf");
   db_fieldsmemory($result,0);

	 //verifica se email confere com o cadastrado no cgm
	 if ($emailContr != $z01_email || empty($z01_email)) {

		 $sMsg  = "Seu e-mail cadastrado na Prefeitura NÃO confere ou está em branco.";
		 $sMsg .= "Entre em contato com a Prefeitura para atualizar seu cadastrado.";
		 msgbox($sMsg);

		 if ($w13_liberaatucgm=="t") {
		   db_redireciona("atualizaendereco.php?w11_cgccpf=$cgccpf&w11_email=$email_contribuinte&cgmlogin=0");
		 } else {
		   db_redireciona("pedido_senha.php?eqm=3");
		 }
     exit;
   }

   //gera senha para o usuário
   $sConso = 'bcdfghjklmnpqrstvwxyzbcdfghjklmnpqrstvwxyz';
   $sVogal = 'aeiou';
   $sNum   = '123456789';
   $passwd = '';
   $y = strlen($sConso)-1; //conta o nº de caracteres da variável $sConso
   $z = strlen($sVogal)-1; //conta o nº de caracteres da variável $sVogal
   $r = strlen($sNum)-1; //conta o nº de caracteres da variável $sNum

	 for ($x=0;$x<=1;$x++) {

		 $rand  = rand(0,$y); //Funçao rand() - gera um valor randômico
		 $rand1 = rand(0,$z);
		 $rand2 = rand(0,$r);
		 $str   = substr($sConso,$rand,1); // substr() - retorna parte de uma string
		 $str1  = substr($sVogal,$rand1,1);
		 $str2  = substr($sNum,$rand2,1);
		 $passwd .= $str.$str1.$str2;
	 }

   $passwd2 = Encriptacao::encriptaSenha( $passwd );

   $mailpref = "$emailContr";

   //verifica se usuário já é cadastrado
   $result1 = @db_query("select login from db_usuarios where login = '$z01_numcgm'");
   $linhas1 = @pg_num_rows($result1);

	 if ($linhas1==0) {

		 //cadastra novo usuário
		 $sqlusu     = "select nextval('db_usuarios_id_usuario_seq') as x";
		 $result     = db_query($sqlusu);
		 $id_usuario = pg_result($result,0,0);
     $datatoken  = date("Y-m-d");

		 $result2 = @db_query("insert into db_usuarios ( id_usuario,
		                                                 nome,
		                  login,
		                  senha,
		                  usuarioativo,
		                  email,
		                  usuext,
                      datatoken
                      )
		                  values (
		                  $id_usuario,
		                  '$z01_nome',
		                  '$z01_numcgm',
		                  '".(@$passwd==""?'':($passwd2))."',
		                  '1',
		                  '$z01_email',
		                  1,
                      '$datatoken')") or die("Erro(23) inserindo em db_usuarios: ".pg_errormessage());
		    $result3 = @db_query("insert into db_usuacgm (id_usuario,cgmlogin) values($id_usuario,$z01_numcgm)");

    		$sCpf = formataCpf($z01_cgccpf);
    		$mensagemDestinatario = "
    		                         $nomeinst\n
    		                         Pedido de Senha Internet - Prefeitura On-Line\n
    		                         --------------------------------------------------------\n
    		                         Nome:     $z01_nome\n
    		                         CPF/CNPJ: $sCpf\n
    		                         E-mail:   $z01_email\n
    														 \n
    		                         Login Internet: $z01_numcgm\n
    		                         Senha Internet: $passwd\n
    													   \n
    		                         Utilize Login e Senha para acessar suas informações no Portal da Prefeitura na Internet.\n
    														 \n
    		                         $url/dbpref/\n
    														 \n
    		                         Não responda este e-mail, ele foi gerado automaticamente pelo Servidor.\n
    														 \n
    		                         --------------------------------------------------------\n
    		                         ".date("d/m/Y - H:i:s")." - ".getenv("REMOTE_ADDR");
		   } else {

          /**
           * Se ja tiver senha cadastrada
           */
  		    msgbox("Você Já possui cadastro na Prefeitura!");
  		    $dados   = pg_fetch_array($result1);
  		    $result3 = @db_query("update db_usuarios set senha = '$passwd2' where login = '$dados[0]'");

      		$sCpf    = formataCpf($z01_cgccpf);
      		$mensagemDestinatario = "\n
      		                         $nomeinst\n
      		                         Pedido de Senha Internet - Prefeitura On-Line\n
      		                         --------------------------------------------------------\n
      		                         Nome:     $z01_nome\n
      		                         CPF/CNPJ: $sCpf\n
      		                         E-mail:   $z01_email\n
      														 \n
      		                         Atenção!\n
      		                         Alguém tentou realizar um novo pedido de senha com seus dados.\n
      		                         ".date("d/m/Y - H:i:s")." - ".getenv("REMOTE_ADDR")."\n
      		                         Uma nova senha foi gerada para acesso ao Portal.\n
      														 \n
      		                         Login Internet: $z01_numcgm\n
      		                         Senha Internet: $passwd\n
      														 \n
      		                         Utilize Login e Senha para acessar suas informações no Portal da Prefeitura na Internet.\n
      														 \n
      		                         $url/dbpref/\n
      														\n
      		                         Não responda este e-mail, ele foi gerado automaticamente pelo Servidor.\n
      														\n
      		                         --------------------------------------------------------\n
      		";

		   }

      $sConsulta = $clconfigdbpref->sql_record( $clconfigdbpref->sql_query_file(db_getsession('DB_instit'),"w13_emailadmin") );
      $rsConsultaConfigDBPref = $sConsulta;
      db_fieldsmemory($rsConsultaConfigDBPref,0);


      $oMail = new mail();
      $oMail->Send($mailpref,$w13_emailadmin,'Prefeitura On-Line - Pedido de Senha',$mensagemDestinatario);
      msgbox("Uma mensagem foi encaminhada para o e-mail: {$sEmailServ}");

  }
}

function formataCpf($sCpf){

	if(strlen(trim($sCpf)) == 11){

		$cpf       = str_replace("-", "", $sCpf);
		$cpf1      = substr($cpf, -11,3);
		$cpf2      = substr($cpf, -8,3);
		$cpf3      = substr($cpf, -5,3);
		$cpf4      = substr($cpf, -2,2);
		$sRetorno  = $cpf1.".".$cpf2.".".$cpf3."-".$cpf4;

	} else if (strlen(trim($sCpf)) == 14) {

	  $sCnpj     = str_replace("-", "", $sCpf);
	  $sCnpj1    = substr($sCnpj, 0,2);
	  $sCnpj2    = substr($sCnpj, 2,3);
	  $sCnpj3    = substr($sCnpj, 5,3);
	  $sCnpj4    = substr($sCnpj, 8,4);
	  $sCnpj5    = substr($sCnpj, 12,2);
	  $sRetorno  = $sCnpj1.".".$sCnpj2.".".$sCnpj3."/".$sCnpj4."-".$sCnpj5;
	}
	return $sRetorno;
}
?>

<table width="80%" align="center" border="0" cellpadding="1" cellspacing="1">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post" target="CentroPref">
<table width="80%" align="center" border="0" cellpadding="1" cellspacing="1" bgcolor="<?$w01_corbody?>" class="bold10">
  <tr bgcolor="<?=$w01_corbody?>" class="bold3">
    <td colspan="2"><?= $sTitulo; ?></td>
    <td>&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td width="13%" colspan="2" align='left'><b>CONTRIBUINTE/SERVIDOR:&nbsp;</b></td>
    <td width="60%" colspan="2" align="left">
    <?
     $sSeleciona = array ("1" => "Contribuinte",
                          "2" => "Servidor Municipal");
     db_select('srvctb', $sSeleciona, true, 1,"onChange='js_redireciona(this.value);'");
     ?>
   </td>
  </tr>
</table>
<table width="80%" align="center" border="0" cellpadding="1" cellspacing="1">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<table id="contribuinte" width="80%" align="center" border="0" cellpadding="5" cellspacing="1"
       bgcolor="<?$w01_corbody?>" class="bold4">
  <tr class="pequeno3">
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="20%">CPF/CNPJ:</td>
    <td width="20%">
      <input type="text" id="cgccpf" name="cgccpf" size="30" maxlength="18"
             onChange='js_teclas(event);'
             onKeyPress='FormataCPFeCNPJ(this,event); return js_teclas(event);'>
    </td>
    <th><div id="msgcpfcnpj" align="left"></div></th>
  </tr>
  </tr>
  <tr>
    <td width="20%">E-mail:</td>
    <td width="20%">
      <input type="text" id="email_contribuinte" name="email_contribuinte" size="30" maxlength="50">
    </td>
    <th><div id="msgctremail" align="left"></div></th>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="right">
      <input type="button" name="enviar" value="Enviar" onClick="js_valida_campos_crt('1');">
      <input type="button" name="limpar" value="Limpar" onClick="js_limpar_campos('1');">
    </td>
  </tr>
</table>

<table id="servidor" width="80%" align="center" border="0"
       cellpadding="5" cellspacing="1" bgcolor="<?$w01_corbody?>"
       class="bold4" style="display: none">
  <tr>
    <td width="15%">Matricula:</td>
    <td width="1%"><span><font color='#E9000'> * </font></span></td>
    <td width="32%">
      <input type="text" id="matricula" name="matricula" size="8" maxlength="10"
             onKeyPress='return js_teclas(event);'
             onChange="js_pesquisa(this.value,'1');">
      <input type="text" id="nome" name="nome" size="29" maxlength="255"
             align="left" onChange="js_pesquisa(this.value,'2');" onKeyUp="js_Maiusculo(this,'t',event);">
      <th><div id="msgmatnome" align="left"></div></th>
    </td>
  </tr>
  <tr>
    <td width="15%">CPF:</td>
    <td width="1%"><span><font color='#E9000'> * </font></span></td>
    <td width="32%">
      <input type="text" id="cpf" name="cpf" size="15" maxlength="14"
             onChange='js_teclas(event);'
             onKeyPress='FormataCPF(this,event); return js_teclas(event);'>
    </td>
    <th><div id="msgcpf" align="left"></div></th>
  </tr>
  <tr>
  <tr>
    <td width="15%">Data Nascimento:</td>
    <td width="1%"><span><font color='#E9000'> * </font></span></td>
    <td width="10%" colspan="9">
    <?
        if(@$z01_nasc != ""){
          $z01_nasc_dia = substr($z01_nasc,8,2);
          $z01_nasc_mes = substr($z01_nasc,5,2);
          $z01_nasc_ano = substr($z01_nasc,0,4);
        }

        db_inputdata("z01_nasc",@$z01_nasc_dia,@$z01_nasc_mes,@$z01_nasc_ano ,true,
                     'text', 1,"onKeyPress='return js_teclas(event);'");
    ?>
      <span id="msgdatansc" align="left"></span>
    </td>
  </tr>
  <tr>
    <td width="15%">Nome da Mãe:</td>
    <td width="1%"><span><font color='#E9000'> * </font></span></td>
    <td width="32%">
      <input type="text" id="nomemae" name="nomemae" size="41" maxlength="255"
             onKeyUp="js_Maiusculo(this,'t',event);">
    </td>
    <th><div id="msgnomemae" align="left"></div></th>
  </tr>
  <tr>
    <td width="15%">E-mail:</td>
    <td width="1%"></td>
    <td width="32%">
      <input type="text" id="emailsrv" name="emailsrv" size="41" maxlength="50">
    </td>
  </tr>
  <tr id="rdsenha" style="display: none">
    <td width="15%">Informe sua senha:</td>
    <td width="1%"><span><font color='#E9000'> * </font></span></td>
    <td width="32%">
      <input type="password" id="senhasrv" name="senhasrv" size="41" maxlength="20">
    </td>
    <th><div id="msgsenhasrv" align="left"></div></th>
  </tr>
  <tr id="confrdsenha" style="display: none">
    <td width="15%">Confirme sua senha:</td>
    <td width="1%"><span><font color='#E9000'> * </font></span></td>
    <td width="32%">
      <input type="password" id="confsenhasrv" name="confsenhasrv" size="41" maxlength="20">
    </td>
    <th><div id="msgconfsenhasrv" align="left"></div></th>
  </tr>
  <tr>
    <td colspan="3" align="right">
      <input type="button" id="btnsub"    name="continuar" value="Continuar" onClick="js_valida_campos_srv('2','t');">
      <input type="submit" id="btnenviar" name="enviar"    value="Enviar"    style="display: none;"
             onClick="return js_verifica_integridade_senha('S');">
      <input type="button" id="btnlmp"    name="limpar"    value="Limpar" onClick="js_limpar_campos('2');">
    </td>
  </tr>
  <tr>
  <td id="msg" colspan="10">
    <div align="left">
      <span><font color='#E9000'> PREENCHIMENTO OBRIGATÓRIO(*) </font></span>
    </div>
  </td>
  </tr>
  <th id="msgerro" colspan="10" style="display: none;" align="left"></th>
  <th id="msgerrosenha" colspan="10" style="display: none;" align="left"></th>
</table>
</form>
</body>
</html>
<script>
// inicio javascript

var msg = "<span><font color='#E9000'> * </font></span>";

// muda os formularios contribuinte e servidor
function js_redireciona(tipo){

  if(tipo == 1){
    document.getElementById('contribuinte').style.display = ''
    document.getElementById('servidor').style.display     = 'none';
  }
  if(tipo == 2){
    document.getElementById('contribuinte').style.display  = 'none';
    document.getElementById('servidor').style.display      = '';
    document.getElementById('msgerrosenha').style.display = 'none';
  }
}

// valida os campos do formulario dos servidores
function js_valida_campos_srv(tipo,chave){

  var nome          = document.getElementById("nome").value;
  var matricula     = document.getElementById("matricula").value;
  var cpf           = document.getElementById("cpf").value;
  var datansc_dia   = document.getElementById('z01_nasc_dia').value;
  var datansc_mes   = document.getElementById('z01_nasc_mes').value;
  var datansc_ano   = document.getElementById('z01_nasc_ano').value;
  var nomemae       = document.getElementById("nomemae").value;
  var email         = document.getElementById("emailsrv").value;
  var tipo          = tipo;
  var chave         = chave;
  var chaveSqm      = '<?= $sEsq; ?>';

  if ( matricula == '' || nome == ''){
     return false;
  }

  if ( cpf == ''){
     return false;
  }

  if ( datansc_dia == ''){
     return false;
  } else if ( datansc_mes == ''){
     return false;
  } else if ( datansc_ano == ''){
     return false;
  }

  if ( nomemae == ''){
     return false;
  }

  if(tipo == 2){

     js_OpenJanelaIframe('','db_valida_campos',
                            'func_valida_senha_servidor.php?chave='+chave
                                                                   +'&nummatric='+matricula
                                                                   +'&z01_nome='+nome
                                                                   +'&z01_cgccpf='+cpf
                                                                   +'&z01_nasc='+datansc_dia
                                                                   +'/'+datansc_mes
                                                                   +'/'+datansc_ano
                                                                   +'&z01_mae='+nomemae
                                                                   +'&sqms='+chaveSqm
                                                                   +'&email='+email,'',false);
  }

}

// valida os campos do formulario dos contribuintes
function js_valida_campos_crt(tipo){

  var cpfcnpj   = document.getElementById("cgccpf").value;
  var ctremail  = document.getElementById("email_contribuinte").value;
  var tipo      = tipo;

  if ( cpfcnpj == ''){
    document.getElementById("msgcpfcnpj").innerHTML = msg;
    return false;
  } else {
    document.getElementById("msgcpfcnpj").innerHTML = '';
  }

  if ( ctremail == ''){
    document.getElementById("msgctremail").innerHTML = msg;
    return false;
  } else {
    document.getElementById("msgctremail").innerHTML = '';
  }

  if(tipo == 1){
    location.href = "pedido_senha.php?cgccpf="+cpfcnpj+"&emailContr="+ctremail+"&eqm=3";
  }

}

// pesquisa por matricula ou por nome do servidor
function js_pesquisa(chave1,chave2){

  var chave1 = chave1;
  var chave2 = chave2;

  if(chave2 == 1){
     js_OpenJanelaIframe('','db_iframe_busca_matricula',
                            'func_busca_matr.php?numcgm='+chave1+'&chave='+chave2,'',false);
  }
  if(chave2 == 2){
     js_OpenJanelaIframe('','db_iframe_busca_matricula',
                            'func_busca_matr.php?z01_nome='+chave1+'&chave='+chave2,'',false);
  }
}

// limpa os campos digitados
function js_limpar_campos(tipo){

  var tipo = tipo;

  if(tipo == 1){

     document.getElementById("cgccpf").value              = '';
     document.getElementById("email_contribuinte").value  = '';
     document.getElementById("msgcpfcnpj").innerHTML      = '';
     document.getElementById("msgctremail").innerHTML     = '';
  }

  if(tipo == 2){

     document.getElementById("nome").value           = '';
     document.getElementById("matricula").value      = '';
     document.getElementById("cpf").value            = '';
     document.form1.z01_nasc_dia.value               = '';
     document.form1.z01_nasc_mes.value               = '';
     document.form1.z01_nasc_ano.value               = '';
     document.getElementById("nomemae").value        = '';
     document.getElementById("emailsrv").value       = '';
     document.getElementById("msgerro").innerHTML    = '';
  }
}

// formata letra minuscula do campo nomemae para maiusculo
function js_Maiusculo(obj,maiusculo,evt){

  evt = (evt)?evt:(event)?event:'';
  if(maiusculo =='t'){
    var maiusc = new String(obj.value);
        obj.value = maiusc.toUpperCase();
  }
}

// Verifica integridade da senha e valida campos de senha
function js_verifica_integridade_senha(tipo){

   sMsg = "SUA SENHA DEVE CONTER NO MÍNIMO 6 CARACTERES, LETRAS E NÚMEROS!";
   str  = "<span><font color='#E9000'> " + sMsg + " </font></span>";

   var msgerro      = "<span><font color='#E9000'> SENHAS NÃO CONFEREM, VERIFICAR CAMPOS(*)! </font></span>";
   var msgerrosenha = str;
   var sT           = tipo;
   var senha        = document.getElementById("senhasrv").value;
   var confsenha    = document.getElementById("confsenhasrv").value;

   var tamSenha     = senha.length;
   var tamConfsenha = confsenha.length;
   var exprChar     = new RegExp("[A-Za-z]");
   var exprNum      = new RegExp("[0-9]");

// parametro para verificar caracteres digitados
if(sT == 'S'){

  if(senha.match(exprChar) == null || senha.match(exprNum) == null) {

     if(tamSenha < 6 && tamConfsenha < 6){
      document.getElementById('msgerrosenha').innerHTML = msgerrosenha;
      alert(sMsg);
      return false;
     } else {
      document.getElementById('msgerrosenha').innerHTML = msgerrosenha;
      alert(sMsg);
      return false;
     }
    } else {
     if(tamSenha < 6 && tamConfsenha < 6){
      document.getElementById('msgerrosenha').innerHTML = msgerrosenha;
      alert(sMsg);
      return false;
     } else {
      document.getElementById('msgerro').style.display      = 'none';
      document.getElementById('msgerrosenha').style.display = 'none';
      document.getElementById('msgerro').innerHTML          = '';
      document.getElementById('msgerrosenha').innerHTML     = '';

        if ( senha == ''){

           document.getElementById('msgerrosenha').style.display = '';
           document.getElementById('msgerrosenha').innerHTML = msgerrosenha;
           alert(sMsg);
           return false;
        }

        if ( confsenha == ''){

           document.getElementById('msgerrosenha').style.display = '';
           document.getElementById('msgerrosenha').innerHTML = msgerro;
           alert('SENHAS NÃO CONFEREM, VERIFICAR CAMPOS(*)!')
           return false;
        }

        // se senhas forem iguais faz a consulta
        if (senha == confsenha){

           var matricula     = document.getElementById("matricula").value;
           var nome          = document.getElementById("nome").value;
           var cpf           = document.getElementById("cpf").value;
           var datansc_dia   = document.getElementById('z01_nasc_dia').value;
           var datansc_mes   = document.getElementById('z01_nasc_mes').value;
           var datansc_ano   = document.getElementById('z01_nasc_ano').value;
           var nomemae       = document.getElementById("nomemae").value;
           var emailsrv      = document.getElementById("emailsrv").value;
           var chave         = '<?= $sChave; ?>';
           var sHeader       = '<?= $sHeader; ?>';
           var sGet          = "?nummatricula="+matricula+
                               "&nome="+nome+
                               "&numcpf="+cpf+
                               "&datansc="+datansc_dia+
                               "/"+datansc_mes+
                               "/"+datansc_ano+
                               "&nomemae="+nomemae+
                               "&emailsrv="+emailsrv+
                               "&chave="+chave+
                               "&header="+sHeader;

           document.getElementById('msgerro').style.display = 'none';
           document.getElementById('msgerrosenha').style.display = 'none';
           document.getElementById('msgerro').innerHTML = '';
           document.getElementById('msgerrosenha').innerHTML = '';
           document.form1.action = "conf_senhas_servidor.php"+sGet;
           return true;

        } else {

            document.getElementById('msgerro').style.display = 'none';
            document.getElementById('msgerrosenha').style.display = '';
            document.getElementById('msgerro').innerHTML = '';
            document.getElementById('msgerrosenha').innerHTML = msgerro;
            alert(sMsg);
           return false;
        }
       return false;
     }
     return false;
   }
   return false;
 }
  return false;
}
</script>