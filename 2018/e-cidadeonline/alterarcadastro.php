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
require("libs/db_encriptacao.php");

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref
                       WHERE m_arquivo = 'digitaaidof.php'
                       ORDER BY m_descricao
                       ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}
mens_help();

if(isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($HTTP_POST_VARS);
  $result = db_query("select senha as senhaantiga from db_usuarios where id_usuario = $id_usuario");
  db_fieldsmemory($result,0);
  if( $senhaantiga == Encriptacao::encriptaSenha($senhaatual) ) {

    db_query("BEGIN");
    db_query("update db_usuarios set
                                     senha = '". Encriptacao::encriptaSenha($senha) ."'
                               where id_usuario = $id_usuario") or die("Erro(38) alterando db_usuarios: ".pg_errormessage());
    db_query("COMMIT");
  }else{

    db_msgbox("campo senha atual não confere!");
    db_redireciona($HTTP_SERVER_VARS['PHP_SELF']."?".base64_encode("id_usuario=".$id_usuario));
  }
  if(isset($enviaemail) && ($enviaemail == "sim")){
    $mensagemDestinatario = "
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda.</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
</head>

<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">
<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
    <td nowrap align=\"center\" valign=\"top\"><table width=\"100%\" height=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
        <tr>
          <td width=\"146\" nowrap bgcolor=\"#6699CC\"><font color=\"#FFFFFF\" ></font></td>
          <td height=\"60\"  nowrap align=\"left\" bgcolor=\"#6699CC\"><font color=\"#FFFFFF\"><strong>&nbsp;&nbsp;&nbsp;.: Senha Alterada :.
        </tr>
        <tr>
          <td colspan=\"2\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Você alterou sua senha no site Prefeitura-OnLine,<br> este e-mail foi enviado conforme solicitado no site para verificação</li>
                  </ul></td>
              </tr>
              <tr>
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr>
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr>
                <td><ul>
                    <li>Usuário : <font size=\"2\" face=\"Arial, Helvetica, sans-serif\"><strong>$nome</strong></font></li>
                  </ul></td>
              </tr>
              <tr>
                <td><ul>
                    <li>Login : <font size=\"2\" face=\"Arial, Helvetica, sans-serif\"><strong>$login</strong></font></li>
                  </ul></td>
              </tr>
              <tr>
                <td><ul>
                    <li>Nova Senha : <font size=\"2\" face=\"Arial, Helvetica, sans-serif\"><strong>$senha</strong></font></li>
                  </ul></td>
              </tr>
              <tr>
                <td><ul>
                    <li>Data da alteração : <font size=\"2\" face=\"Arial, Helvetica, sans-serif\"><strong>".db_formatar(date("Y-m-d"),'d')."</strong></font></li>
                  </ul></td>
              </tr>
              <tr>
                <td><ul>
                    <li>Hora : <font size=\"2\" face=\"Arial, Helvetica, sans-serif\"><strong>".$hora = db_getsession("hora")."</strong></font></li>
                  </ul></td>
              </tr>
              <tr>
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align=\"center\"><p><font size=\"1\">Este e-mail foi enviado automaticamente
                    por favor não responda-o</font></p></td>
              </tr>
              <tr>
                <td align=\"center\"><p><a href=\"http://200.102.214.168\"><font size=\"1\">DBSeller Inform&aacute;tica
                    Ltda.</font></a></p></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>

</body>
</html>";

  $rsConsultaConfigDBPref = $clconfigdbpref->sql_record($clconfigdbpref->sql_query_file(db_getsession('DB_instit'),"w13_emailadmin"));
  db_fieldsmemory($rsConsultaConfigDBPref,0);

  $oMail = new mail();
  $oMail->Send($email,$w13_emailadmin,'Alteração de senha do site Prefeitura On-Line',$mensagemDestinatario);

  }
  db_msgbox("Senha alterada com sucesso!");
  db_redireciona("centro_pref.php");
}
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<style type="text/css">
<?db_estilosite();?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
</body>