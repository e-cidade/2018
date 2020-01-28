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

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_db_usuacgm_classe.php");
include("libs/db_sql.php");
include("classes/db_cgm_classe.php");
include("libs/db_mail_class.php");
require_once("libs/db_encriptacao.php");

$cldb_usuarios = new cl_db_usuarios;
$cldb_usuacgm  = new cl_db_usuacgm;
$clcgm         = new cl_cgm;

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_logs("","",0,"Pedido de Senha.");

$oGet  = db_utils::postmemory($_GET);
$oPost = db_utils::postmemory($_POST);

$sMatric    = $oGet->nummatricula;
$sNome      = $oGet->nome;
$sCgcCpf    = formataCpfBanco($oGet->numcpf);
$sDataNasc  = formataDataNascBanco($oGet->datansc);
$sNomeMae   = $oGet->nomemae;
$sEmailServ = $oGet->emailsrv;
$sPassword  = Encriptacao::encriptaSenha( $oPost->senhasrv );
$sHeader    = $oGet->header;
$mostar = 1;

// pedido de senha e executado se chave == 't'
if(isset($oGet->chave) && $oGet->chave == 't') {

  $sql = " select rh01_regist,
                  z01_numcgm,
                  z01_nome,
                  z01_cgccpf,
                  z01_nasc,
                  z01_mae,
                  z01_email,
                  email,
                  senha,
                  login
             from rhpessoal
                  inner join cgm on rh01_numcgm = z01_numcgm
                  left join db_usuarios on db_usuarios.nome  =  cgm.z01_nome
            where rh01_regist = '{$sMatric}'
              and z01_nasc is not null
              and z01_mae is not null limit 1 ";

  $result = db_query($sql);
  $total  = pg_num_rows($result);

	if($total == 0){
	   db_logs("","",0,"Solicitação de senha para fornecedor: cgc ou cpf não encontrado. {$sCgcCpf}");
	}

	if($total > 0){
	  db_fieldsmemory($result,0);
	}

  if( $total > 0 ) {

    db_logs("","",0,"Solicitação de senha para fornecedor: cgc ou cpf - {$sCgcCpf}");

		// $mailpref = "$email_contribuinte,$email";
		$mailpref = "{$sEmailServ}";

		//verifica se usuário já é cadastrado
		$sqlUsuario = " select login from db_usuarios where login = '{$z01_numcgm}' and usuext = '1' ";

		$queryUsuario = db_query($sqlUsuario);
		$total        = pg_num_rows($queryUsuario);

	  if($total == 0) {

		  $sqlerro=false;
		  db_inicio_transacao();

		  //cadastra novo usuário
		  $sqlUsu     = " select nextval('db_usuarios_id_usuario_seq') as x ";
		  $result     = db_query($sqlUsu);

		  db_query("SET CLIENT_ENCODING TO 'LATIN1';");

		  $id_usuario = pg_result($result,0,0);

		  if ($sqlerro==false) {

		    $cldb_usuarios->id_usuario    = $id_usuario;
		    $cldb_usuarios->nome          = $z01_nome;
		    $cldb_usuarios->login         = $z01_numcgm;
		    $cldb_usuarios->senha         = $sPassword;
		    $cldb_usuarios->usuarioativo  = "1";
		    $cldb_usuarios->email         = $sEmailServ;
		    $cldb_usuarios->usuext        = "1";
		    $cldb_usuarios->administrador = "0";
		    $cldb_usuarios->incluir($id_usuario);

		    if ($cldb_usuarios->erro_status == 0) {

		      $sqlerro   = true;
				  $erro_msg = $cldb_usuarios->erro_msg;
		    }

		  }

		  if ($sqlerro == false) {

			  $cldb_usuacgm->id_usuario = $id_usuario;
			  $cldb_usuacgm->cgmlogin   = $z01_numcgm;
			  $cldb_usuacgm->incluir($id_usuario);

			  if ($cldb_usuacgm->erro_status == 0) {

			    $sqlerro   = true;
			    $erro_msg  = $cldb_usuacgm->erro_msg;
			  }
			}

		  db_fim_transacao($sqlerro);

			//verifica se email
      if (isset($sEmailServ) && $sEmailServ == '') {

        if ($sqlerro == true) {
          db_msgbox($erro_msg);
        } else {

				  $sMsg  = "Entre em contato com a Prefeitura para atualizar seu cadastrado.";
				  msgbox($sMsg);
        }
      } else {

  			$sCpf   = $oGet->numcpf;
  			$sSenha = $oPost->senhasrv;
  			$mensagemDestinatario = "$nomeinst
			                           Pedido de Senha Internet - Prefeitura On-Line
			                           --------------------------------------------------------
			                           Nome:     {$z01_nome}
			                           CPF:      {$sCpf}
			                           E-mail:   {$sEmailServ}

			                           Login Internet: {$z01_numcgm}
			                           Senha Internet: {$sSenha}

			                           Utilize Login e Senha para acessar suas informações no Portal da Prefeitura na Internet.

			                           $url/dbpref/

			                           Não responda este e-mail, ele foi gerado automaticamente pelo Servidor.

			                           --------------------------------------------------------
			                           ".date("d/m/Y - H:i:s")." - ".getenv("REMOTE_ADDR");

			  if($sqlerro==true) {
			    db_msgbox($erro_msg);
			  } else {

			    if(isset($sEmailServ) && $sEmailServ != '') {

			       $msg   = "E-mail enviado com sucesso.\\n";
			       $msg  .= "Suas informações foram enviadas para o e-mail: {$sEmailServ}";
			       msgbox($msg);

			       $sTring = $clconfigdbpref->sql_record($clconfigdbpref->sql_query_file(db_getsession('DB_instit'),"w13_emailadmin"));
			       $rsConsultaConfigDBPref = $sTring;
			       db_fieldsmemory($rsConsultaConfigDBPref,0);

			       $oMail = new mail();
			       $oMail->Send($mailpref,$w13_emailadmin,'Prefeitura On-Line - Pedido de Senha', $mensagemDestinatario);
			    }

			  }
			}

    } else {

			 // se ja tiver senha cadastrada........
			 msgbox("Você Já possui cadastro na Prefeitura!");

		   if (isset($sCgcCpf)) {

			   if (isset($sEmailServ) && $sEmailServ == '') {

					  $sEmailUpdate = "";
	          $sMsg = "Entre em contato com a Prefeitura para atualizar seu cadastrado.";
	          msgbox($sMsg);
				 } else {
					 $sEmailUpdate = ", email = '{$sEmailServ}' ";
			   }

			   $sSqlUpdate  = " update db_usuarios set senha = '$sPassword'
			                                           {$sEmailUpdate}
			                     where login = '{$z01_numcgm}' and usuext = '1' ";

			   //die($sSqlUpdate);
			   $queryUpdate = db_query($sSqlUpdate);

			   $sCpf   = $oGet->numcpf;
			   $sSenha = $oPost->senhasrv;

			   $mensagemDestinatario = "
			                            $nomeinst
			                            Pedido de Senha Internet - Prefeitura On-Line
			                            --------------------------------------------------------
			                            Nome:     {$z01_nome}
			                            CPF:      {$sCpf}
			                            E-mail:   {$sEmailServ}

			                            Atenção!
			                            Alguém tentou realizar um novo pedido de senha com seus dados.
			                            ".date("d/m/Y - H:i:s")." - ".getenv("REMOTE_ADDR")."
			                            Uma nova senha foi gerada para acesso ao Portal.

			                            Login Internet: {$z01_numcgm}
			                            Senha Internet: {$sSenha}

			                            Utilize Login e Senha para acessar suas informações no Portal da Prefeitura na Internet.

			                            $url/dbpref/

			                            Não responda este e-mail, ele foi gerado automaticamente pelo Servidor.

			                            --------------------------------------------------------
			                           ";

		   }

       if(isset($sEmailServ) && $sEmailServ != ''){

	       $sTring = $clconfigdbpref->sql_record($clconfigdbpref->sql_query_file(db_getsession('DB_instit'),"w13_emailadmin"));
	       $rsConsultaConfigDBPref = $sTring;
	       db_fieldsmemory($rsConsultaConfigDBPref,0);

	       $oMail = new mail();
	       $oMail->Send($mailpref,$w13_emailadmin,'Prefeitura On-Line - Pedido de Senha',$mensagemDestinatario);

	       msgbox("Uma mensagem foi encaminhada para o e-mail: {$sEmailServ}");

	     }

		 }

  } else {

		  $sMsg = "Dados informados NÃO encontrados no cadastro da Prefeitura!\\n
		           Procure o balcão da Prefeitura para realizar seu cadastro.";
		  msgbox($sMsg);

		  db_redireciona("centro_pref.php");
  }

// esqueci minha senha e executado se chave == 'f'
} else if(isset($oGet->chave) && $oGet->chave == 'f') {

				  // $mailpref = "$email_contribuinte,$email";
				  $mailpref = "{$sEmailServ}";

				  $sql = " select rh01_regist,
				                  z01_numcgm,
				                  z01_nome,
				                  z01_cgccpf,
				                  z01_nasc,
				                  z01_mae,
				                  z01_email,
				                  email,
				                  senha,
				                  login
				             from rhpessoal
				                  inner join cgm on rh01_numcgm = z01_numcgm
				                  left join db_usuarios on db_usuarios.nome  =  cgm.z01_nome
				            where rh01_regist = '{$sMatric}'
				              and z01_nasc is not null
				              and z01_mae is not null limit 1 ";

				  $result = db_query($sql);
				  $total  = pg_num_rows($result);

				  if ( $total == 0) {
				    db_logs("","",0,"Esqueci minha senha: cgc ou cpf não encontrado. {$sCgcCpf}");
				  }

				  if( $total > 0 ) {
				    db_fieldsmemory($result,0);
				  }

  				if( $total > 0 ) {

             //verifica se usuário existe.
            $sqlUsu = " select nextval('db_usuarios_id_usuario_seq') as x ";

            //die($sqlUsu);
            $result = db_query($sqlUsu);
            $totusu = pg_num_rows($result);

            if( $totusu > 0 ) {

  			      if(isset($sEmailServ) && $sEmailServ == ''){
                $sEmailUpdate = "";
              } else {
                $sEmailUpdate = ", email = '{$sEmailServ}' ";
              }

  			      $sSqlUpdate  = " update db_usuarios set senha = '$sPassword'
  			                                              {$sEmailUpdate}
  			                        where login = '{$z01_numcgm}' and usuext = '1' ";

  						$queryUpdate = db_query($sSqlUpdate);

  						$sCpf   = $oGet->numcpf;
  						$sSenha = $oPost->senhasrv;

              //verifica se email
              if ( isset($sEmailServ) && $sEmailServ != '') {

                $mensagemDestinatario = "
                                     $nomeinst
                                     Esqueci Minha Senha - Prefeitura On-Line
                                     --------------------------------------------------------
                                     Nome:     {$z01_nome}
                                     CPF:      {$sCpf}
                                     E-mail:   {$sEmailServ}

                                     Atenção!
                                     Alguém tentou realizar um novo pedido de senha com seus dados.
                                     ".date("d/m/Y - H:i:s")." - ".getenv("REMOTE_ADDR")."
                                     Uma nova senha foi gerada para acesso ao Portal.

                                     Login Internet: {$z01_numcgm}
                                     Senha Internet: {$sSenha}

                                     Utilize Login e Senha para acessar suas informações no Portal da Prefeitura na Internet.

                                     $url/dbpref/

                                     Não responda este e-mail, ele foi gerado automaticamente pelo Servidor.

                                     --------------------------------------------------------
                ";

                $sTring = $clconfigdbpref->sql_record($clconfigdbpref->sql_query_file(db_getsession('DB_instit'),"w13_emailadmin"));
                $rsConsultaConfigDBPref = $sTring;
                db_fieldsmemory($rsConsultaConfigDBPref,0);

                $oMail = new mail();
                $oMail->Send($mailpref,$w13_emailadmin,'Prefeitura On-Line - Esqueci Minha Senha',$mensagemDestinatario);
                msgbox("Uma mensagem foi encaminhada para o e-mail: {$sEmailServ}");

                db_logs("","",0,"Esqueci minha senha: cgc ou cpf - {$sCgcCpf}");
              } else {

    				    $sMsg = "Entre em contato com a Prefeitura para atualizar seu cadastrado.";
    				    msgbox($sMsg);
  				    }
  				  } else {

              $sMsg = "Dados informados NÃO encontrados no cadastro da Prefeitura!\\n
                       Procure o balcão da Prefeitura para realizar seu cadastro.";
              msgbox($sMsg);
              db_redireciona("centro_pref.php");
            }
  		  } else {
          $sMsg = "Dados informados NÃO encontrados no cadastro da Prefeitura!\\n
                   Procure o balcão da Prefeitura para realizar seu cadastro.";
          msgbox($sMsg);

          db_redireciona("centro_pref.php");
  		 }
	}
function formataDataNascBanco($sData){

	$data         = str_replace("/", "", $sData);
	$datanasc_dia = substr($data, -8,2);
	$datanasc_mes = substr($data, -6,2);
	$datanasc_ano = substr($data, -4);
	$datanasc     = $datanasc_ano."-".$datanasc_mes."-".$datanasc_dia;

	return $datanasc;
}

function formataCpfBanco($sCpf){

 $cpf = str_replace(".", "", $sCpf);
 $cpf = str_replace("-", "", $cpf);

 return $cpf;
}

if(isset($mostar) && $mostar == 1){
?>
<html>
<head>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<br />
<br />
<br />
<form name="form1" method="post" action="" target="CentroPref">
<input type="hidden" id="senha" name="senha" value="">
<table id="servidor" width="50%" align="center" border="0"
       cellpadding="5" cellspacing="1" bgcolor="<?$w01_corbody?>"
       class="bold4">
  <tr>
    <td width="5%">Informações Servidor:</td>
    <td width="32%">
      <input type="text" id="matricula" name="matricula" value="<?= $sMatric; ?>" size="8" maxlength="10" disabled>
      <input type="text" id="nome" name="nome" size="29" value="<?= $sNome; ?>" maxlength="30" align="left" disabled>
    </td>
  </tr>
	<?
	 if(isset($sEmailServ) && $sEmailServ != ''){
	?>
  <tr>
    <td width="20%">E-mail Informado:</td>
    <td width="32%">
      <input type="text" id="emailsrv" name="emailsrv" value="<?= $sEmailServ; ?>" size="41" maxlength="50" disabled>
    </td>
  </tr>
	<?
	 }
	?>
  <tr id="rdsenha">
    <td width="20%">Seu Login:</td>
    <td width="32%">
      <input type="text" id="loginsrv" name="loginsrv" value="<?= $z01_numcgm; ?>" size="41" maxlength="10" disabled>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    <?
      $sSmatricula = $rh01_regist;
      $sSnome      = $z01_nome;
      $sScpf       = $z01_cgccpf;
      $sSdatansc   = $sDataNasc;
      $sSemailsrv  = $sEmailServ;
      $sSheader    = $sHeader;
    ?>
      <input type="button" id="btnimprimir" name="imprimir" value="Imprimir"
             onClick="js_imprimir_dados('<?= $sSmatricula; ?>',
                                        '<?= $sSnome; ?>',
                                        '<?= $sScpf; ?>',
                                        '<?= $sSdatansc; ?>',
                                        '<?= $sSemailsrv; ?>',
                                        '<?= $sSheader; ?>');">
    </td>
  </tr>
</table>
</form>
</body>
</html>

<script>
function js_imprimir_dados(matricula,nome,numcpf,datansc,emailsrv,header) {

 jan=window.open('emite_dados_servidor.php?nummatricula='+matricula+
                                           '&nome='+nome+
                                           '&numcpf='+numcpf+
                                           '&datansc='+datansc+
                                           '&emailsrv='+emailsrv+
                                           '&header='+header,
                                           '',
                                           'width='+(screen.availWidth-5)+
                                           ',height='+(screen.availHeight-40)+
                                           ',scrollbars=1,location=0');
 jan.moveTo(0,0);
}
</script>
<?
}
?>
