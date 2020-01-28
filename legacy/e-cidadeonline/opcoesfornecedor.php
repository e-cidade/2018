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
include("libs/db_sql.php");
include("libs/db_mail_class.php");
require_once("libs/db_encriptacao.php");

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
postmemory($HTTP_POST_VARS);
//ALTERA A SENHA QUANDO JÁ EXISTE CADASTRO
if(isset($HTTP_POST_VARS["alt_senha"])) {
  postmemory($HTTP_POST_VARS);
  if($senha_c1 == '' || $senha_c2 == '') {
    msgbox('Nova senha não pode ser em branco.');
   redireciona("digitafornecedor.php");
   exit;
  }
  $result = @db_query("select senha from db_usuarios where senha = '$senha' ");
  if(@pg_num_rows($result) == 0 ) {
    msgbox("ERRO: Senha Inválida.");
   redireciona("digitafornecedor.php");
   exit;
  }
  $result = @db_query("update db_usuarios set senha = '" . Encriptacao::encriptaSenha( $senha_c1 ) . "' where login = '$cgccpf'") or die(@pg_errormessage());
  if(@pg_cmdtuples($result) > 0) {
    db_logs("","",0,"Solicitação de senha: senha alterada: $cgccpf");
   msgbox("Senha Alterada com sucesso");
   redireciona("digitafornecedor.php");
   exit;
  } else
    db_logs("","",0,"Solicitação de senha: erro alterando senha: $cgccpf");
//MANDA UM E-MAIL DE CONFIRMAÇÃO
} else if(isset($HTTP_POST_VARS["cria_senha"])) {
  postmemory($HTTP_POST_VARS);
  if($email == "") {
    msgbox("Email em branco");
    redireciona("digitafornecedor.php");
   exit;
  }
  if($cgc != "" && $cpf != "") {
    msgbox("Erro: digite seu cgc ou cpf.");
   redireciona(-1);
   exit;
  } else if($cpf != "")
    $cgccpf = $cpf;
  else if($cgc != "")
    $cgccpf = $cgc;
  else {
    msgbox("CPF e CGC em branco.");
   redireciona(-1);
   exit;
  }
  $cgccpf = str_replace(".","",$cgccpf);
  $cgccpf = str_replace("/","",$cgccpf);
  $cgccpf = str_replace("-","",$cgccpf);
  $result = @db_query("select z01_nome,z01_email,z01_cgccpf from cgm where trim(z01_cgccpf) = '$cgccpf'") or die(@pg_errormessage());
  if(@pg_num_rows($result) == 0) {
    db_logs("","",0,"Solicitação de senha para fornecedor: cgc ou cpf inválido. $cgccpf");
   msgbox("CGC ou CPF Inválido.");
   redireciona(-1);
   exit;
  } else
    db_logs("","",0,"Solicitação de senha para fornecedor: cgc ou cpf valido. $cgccpf");
   if($cgccpf == "00000000000" || @pg_result($result,0,"z01_cgccpf") == "00000000000000" || @pg_result($result,0,"z01_cgccpf") == "              ") {
     msgbox("Seu cgc/cpf esta zerado, atualize com a prefeitura para criar senha");
     redireciona("index.php");
     exit;
   }
  fieldsmemory($result,0);
  $result = @db_query("select login from db_usuarios where login = '$cgccpf'");
  //criptografia
  include("libs/CBC.php");
  srand((double)microtime()*32767);
  $rand = rand(1, 32767);
  $rand = pack('i*', $rand);
  $key = "alapuchatche";
  $md = new Crypt_HCEMD5($key, $rand);
  $enc = $md->encodeMimeSelfRand("conf_email=".$email."&conf_cgccpf=".$cgccpf);
  //
 $corpo_email =
 "
 <html>
<head>
<title>Solicita&ccedil;&atilde;o de senha</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<style type=\"text/css\">
<!--
.arial {
   font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
</head>

<body leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">
<table width=\"633\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td align=\"left\" valign=\"top\" nowrap><table width=\"79%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
        <tr>
          <td>
          <div align=\"center\">
          <img src=\"imagens/topo_alegrete.gif\">
          </div>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td align=\"left\" valign=\"top\" nowrap>&nbsp; </td>
  </tr>
  <tr>
    <td> <img src=\"".$URL_ABS."imagens/logo_boleto_extrato.gif\" width=\"365\" height=\"93\">
    </td>
  </tr>
  <tr>
    <td height=\"30\" class=\"arial\" style=\"color:#006633;font-weight: bold;font-size:14px\">&raquo;&raquo; confirma&ccedil;&atilde;o de email</td>
  </tr>
  <tr>
    <td height=\"200\" align=\"left\" valign=\"top\" class=\"arial\" style=\"font-size:12px\"><span style=\"color:#006633\">Caro
      Usu&aacute;rio:</span> <p style=\"font-size:11px\"> Voce esta um passo de
        concluir seu cadastro<br>
        junto ao prefeitura on-line.<br>
        Clique no link abaixo para receber sua senha.<br>
        <br>
      <a href=\"".$URL_ABS."criasenha.php?".$enc."\">Criar Senha</a>
      </p></td>
  </tr>
</table>
</body>
</html>
";

  if(@pg_num_rows($result) == 0)
    $login = 1;
  if($login == 1) {

	$oMail = new mail();
    $oMail->Send($email,$w13_emailadmin,'Confirmação de e-mail para recebimento de senha',$corpo_email);
    msgbox("Um e-mail de confirmação foi enviado para: $email. Clique no link para confirmar o e-mail e receber sua senha.");

   redireciona("digitafornecedor.php");
   exit;
  } else if($login != 1 && $z01_email == $email){
   echo "
   <html>
   <script>
   function js_submeter() {
       if(document.form1.senha.value == '') {
       alert('Campo senha não pode ser vazio!');
      document.form1.senha.focus()
      return false;
     }
     if(document.form1.senha_c1.value != document.form1.senha_c2.value) {
       alert('As senhas estão diferentes!');
      document.form1.senha_c1.select();
      return false;
     }
     if(document,form1.senha_c1.value == '') {
       alert('A sua nova senha não pode ser em branco');
      document.form1.senha_c1.select();
      return false;
     }
     return true;
   }
   </script>
   <body bgcolor=\"#FFFFFF\" background=\"imagens/azul_ceu_O.jpg\" text=\"#000000\" >
   Email já cadastrado. Informe sua senha e nova senha pra alteração.
   <center>
   <form name=\"form1\" method=\"post\" onsubmit=\"return js_submeter()\">
    <table border=0>
     <tr><Td>Senha:</td><td><input type=\"password\" name=\"senha\"></td></tr>
     <tr><td>Nova Senha:</td><td><input type=\"password\" name=\"senha_c1\"></td></tr>
     <tr><Td>Confirma Nova Senha:</td><td><input type=\"password\" name=\"senha_c2\"></td></tr>
     <input type=\"hidden\" name=\"cgccpf\" value=\"$cgccpf\">
     <tr><td colspan=2><input type=\"submit\" name=\"alt_senha\" value=\"clique aqui para alterar sua senha\"></td></tr>
    </table>
   </form>
   </center>
   </body>
   </html>
   ";
  exit;
  } else if($z01_email != $email) {
    msgbox("Email não cadastrado, favor entrar em contado com a prefeitura para alteração ou cadastro");
   redireciona("index.php");
   exit;
  }
}

  if($cgc != "" && $cpf != "") {
    msgbox("Erro: digite seu cgc ou cpf.");
   redireciona(-1);
   exit;
  } else if($cpf != "")
    $cgccpf = $cpf;
  else if($cgc != "")
    $cgccpf = $cgc;
  else {
    msgbox("CPF e CGC em branco.");
   redireciona(-1);
   exit;
  }
  $cgccpf = str_replace(".","",$cgccpf);
  $cgccpf = str_replace("/","",$cgccpf);
  $cgccpf = str_replace("-","",$cgccpf);
  if(!isset($HTTP_POST_VARS["cria_senha"]))
  {
    $result = @db_query("select senha from db_usuarios where login = '$cgccpf' and senha != '' ");

    if(@pg_num_rows($result) == 0){
      msgbox("Login inválido");
      redireciona("digitafornecedor.php");
      exit;
    }
    else
   {
      if( ( Encriptacao::hash ( $DB_senha ) != @pg_result($result,0,0) ) ){

       msgbox("ERRO: Senha Incorreta.");
       redireciona("digitafornecedor.php");
       exit;
      }
    }
  }


//reenvio de senha

      if( ( Encriptacao::hash ( $DB_senha ) != @pg_result($result,1,0) )   )

$result = @db_query("select login,senha from db_usuarios where trim(login) = '$cgccpf' ");
  if(@pg_num_rows($result) == 0 ) {
    msgbox("ERRO: REGISTRO NÃO ENCONTRADO.");
    exit;
  }

//////////////////////////////////////////////////////////////
db_mensagem("opcoesfornecedor_cab","opcoesfornecedor_rod");
mens_help();
?>
<html>
<head>
<title>opcoesimovel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" src="javascript/db_script.js"></script>
<script>
js_verificapagina("digitafornecedor.php,index.php");
</script>
</head>
<?

db_logs("","",0,"Fornecedor Pesquisado. CGCCPF: $cgccpf");
$dblink="digitafornecedor.php";
?>
<body bgcolor="#00436e" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"   <? mens_OnHelp() ?> >
<?
mens_div();
include("processando.php");
?>
<table width="657" height="100%" border="0" cellpadding="0" cellspacing="0" background="imagens/azul_ceu_O.jpg">
  <tr>
    <td height="13">
      <?include("retornar.php")?>
    </td>
  </tr>
  <tr>
    <td align="center"  valign="top"> <table border="0" width="100%">
        <tr>
          <td><div align="<?=$DB_align1?>">
              <?=$DB_mens1?>
            </div></td>
        </tr>
      </table>

    </td>
  </tr>
  <tr>
    <td height="173" valign="top">
        <?
         //pega número do cgm
         $result = @db_query("SELECT z01_numcgm FROM cgm where Z01_CGCCPF='$cgccpf'");
         $dados = @pg_fetch_row( $result, 0 );
         $cgm = $dados[0];
         //realiza a pesquisa de empenhos
          $result = @db_query("select Z01_CGCCPF, Z01_NUMCGM, Z01_NOME,
                               E01_NUMEMP, E01_REDUZ, to_char(E01_EMISS, 'dd/mm/yyyy'),
                               E01_ORDEMC, E01_NUMERL, round(E01_VLREMP,2),
                               round(E01_VLRLIQ,2), round(E01_VLRPAG,2), round(E01_VLRANU,2),
                               E01_NUMCGM
                             from CGM, EMPENHO
                             where Z01_CGCCPF = '$cgccpf'
                                and E01_NUMCGM = $cgm
                              order by  E01_EMISS desc ");
         $linhas = @pg_num_rows($result);
         if($linhas == 0)
          {echo "<center><font face=\"verdana\" size=\"2\">Nenhum empenho para CNPJ/CPF: <b>" . $cgccpf . "</b></font></center>";}
         else
          {
           echo "<font face=\"Verdana\" size=\"2\">";
                  $dados = @pg_fetch_row( $result, 0 );
                  $nome = $dados[2];
                  $cpf = $dados[0];
            echo "Nome do contribuinte: <b>" . $nome;
            echo "<br></b> CNPJ/CPF: <b>" . $cpf;
            echo "<br><br></font>";
            $cor = "#cccccc";
            $cor2 = "white";
            $cor3 = "#ff6666";
            echo "<font face=\"verdana\" size=\"1\">";
            echo "<table bgcolor=black align=center width=100% border=0>";
            echo "<th bgcolor=$cor><font size=\"1\">&nbsp;Empenho&nbsp;</th>";
            echo "<th bgcolor=$cor><font size=\"1\">&nbsp;Dotação&nbsp;</th>";
            echo "<th bgcolor=$cor><font size=\"1\">&nbsp;Data de emissão</th>";
            echo "<th bgcolor=$cor width=80%><font size=\"1\">Ordem de compra</th>";
            echo "<th bgcolor=$cor><font size=\"1\">&nbsp;Nº licitação</th>";
            echo "<th bgcolor=$cor width=80%><font size=\"1\">Valor empenhado</th>";
            echo "<th bgcolor=$cor width=80%><font size=\"1\">Valor liquidado</th>";
            echo "<th bgcolor=$cor width=80%><font size=\"1\">Valor pago</th>";
            echo "<th bgcolor=$cor width=80%><font size=\"1\">Valor anulado</th>";
            echo "</font>";
            $cor2="gray";
            $campos = @pg_numfields($result);
           for( $linha = 0; $linha < $linhas; $linha++ )
             {
               if( $cor2 == "white" )
                 $cor2 = "#99ccff";
               else
                 $cor2 = "white";
               echo "<tr bgcolor=$cor2>";
               $dados = @pg_fetch_row( $result, $linha );
               $dados[8] = "R$" . number_format($dados[8],2,',','.');
               $dados[9] = "R$" . number_format($dados[9],2,',','.');
               $dados[10] = "R$" . number_format($dados[10],2,',','.');
               $dados[11] = "R$" . number_format($dados[11],2,',','.');
               for( $campo = 3; $campo < 12 ; $campo++ )
                 {
                   $end = "empenho.php?empenho=" . $dados[3];
                   echo "<td align=\"center\" valign=\"middle\"><font face=\"Verdana\" size=\"2\">
                        <a href=\"$end\" class=\"menu\">" . $dados[ $campo ] .
                        "</a></td>";
                 }
               echo "</tr>";
             }
             echo "</table></font>";
             echo "<center><font color=\"black\" face=\"Verdana\" size=\"2\">
                  Obs.: Selecione um dos registros acima para visualizar seu empenho.";
          }

         ?>
      </td>
  </tr>
  <tr>
    <td align="center"  valign="top"><table border="0" width="100%">
        <tr>
          <td> <div align="<?=$DB_align2?>">
              <?=$DB_mens2?>
            </div></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>