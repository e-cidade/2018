<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_cursoescola_classe.php");
require_once("classes/db_cursoedu_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clcurso                  = new cl_curso;
$clcursoescola            = new cl_cursoescola;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$db_opcao                 = 22;
$db_opcao1                = 3;
$db_botao                 = false;

if (isset($alterar)) {
	
  $db_opcao  = 2;
  $db_opcao1 = 3;
  $db_botao  = true;
  db_inicio_transacao();
  $clcurso->alterar($ed29_i_codigo);
  db_fim_transacao();
  
} else if (isset($chavepesquisa)) {
	
  $db_opcao  = 2;
  $db_opcao1 = 3;
  $db_botao  = true;
  $sSql      = $clcurso->sql_query($chavepesquisa);
  $rs        = $clcurso->sql_record($sSql);
  db_fieldsmemory($rs, 0);
  
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
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
   <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
   </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
     <?MsgAviso(db_getsession("DB_coddepto"), "escola");?>
     <br>
     <center>
      <fieldset style="width:95%"><legend><b>Alteração de Curso</b></legend>
       <?include("forms/db_frmcursoedu.php");?>
      </fieldset>
     </center>
    </td>
   </tr>
   <tr>
    <td valign="top" align="center">
     <?
       $chavepri= array("ed71_i_codigo"=>@$ed71_i_codigo, 
                        "ed71_i_escola"=>@$ed71_i_escola, 
                        "ed18_c_nome"=>@$ed18_c_nome, 
                        "ed71_i_curso"=>@$ed71_i_curso, 
                        "ed29_c_descr"=>@$ed29_c_descr, 
                        "ed71_c_situacao"=>@$ed71_c_situacao
                       );
       $cliframe_alterar_excluir->chavepri      = $chavepri;
       @$cliframe_alterar_excluir->sql          = $clcursoescola->sql_query("", "*", "ed18_c_nome", 
                                                                              " ed71_i_curso = $chavepesquisa" 
                                                                             );
       $cliframe_alterar_excluir->campos        = "ed71_i_escola, ed71_c_situacao, ed71_c_turmasala";
       $cliframe_alterar_excluir->legenda       = "Escolas vinculadas ao curso ".@$ed29_c_descr;
       $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
       $cliframe_alterar_excluir->textocabec    = "#DEB887";
       $cliframe_alterar_excluir->textocorpo    = "#444444";
       $cliframe_alterar_excluir->fundocabec    = "#444444";
       $cliframe_alterar_excluir->fundocorpo    = "#eaeaea";
       $cliframe_alterar_excluir->iframe_height = "200";
       $cliframe_alterar_excluir->iframe_width  = "100%";
       $cliframe_alterar_excluir->tamfontecabec = 9;
       $cliframe_alterar_excluir->tamfontecorpo = 9;
       $cliframe_alterar_excluir->formulario    = false;
       $cliframe_alterar_excluir->opcoes        = 4;
       $cliframe_alterar_excluir->iframe_alterar_excluir(1);
     ?>
    </td>
   </tr>
  </table>
   <?
     db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
             db_getsession("DB_anousu"), db_getsession("DB_instit")
            );
   ?>
 </body>
</html>
<?
if (isset($alterar)) {
	
  if ($clcurso->erro_status == "0") {
  	
    $clcurso->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clcurso->erro_campo != "") {
    	
      echo "<script> document.form1.".$clcurso->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcurso->erro_campo.".focus();</script>";
      
    }
  } else {
    $clcurso->erro(true, true);
  }
}

if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>