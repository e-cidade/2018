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
require_once("libs/db_utils.php");
require_once("classes/db_cursoescola_classe.php");
require_once("classes/db_cursoturno_classe.php");
require_once("classes/db_cursoato_classe.php");
require_once("classes/db_cursoatoserie_classe.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clcursoescola = new cl_cursoescola;
$clcursoturno = new cl_cursoturno;
$clcursoato = new cl_cursoato;
$clcursoatoserie = new cl_cursoatoserie;
$db_opcao = 1;
$db_botao = true;
$escola = db_getsession("DB_coddepto");
$ed18_c_nome = db_getsession("DB_nomedepto");
$result_ver = $clcursoescola->sql_record($clcursoescola->sql_query("","ed71_i_codigo as nada",""," ed71_i_curso = $ed71_i_curso AND ed71_i_escola = $escola"));
$linhas_ver = $clcursoescola->numrows;
if(isset($incluir)){
 db_inicio_transacao();
 $clcursoescola->incluir($ed71_i_codigo);
 db_fim_transacao();
 $db_botao = false;
}
if(isset($alterar)){
 db_inicio_transacao();
 $db_opcao = 2;
 $clcursoescola->alterar($ed71_i_codigo);
 db_fim_transacao();
 $db_botao = false;
}
if(isset($excluir)){

  $lErro = false;

  /* Verifico se este curso esta verificado a alguma base curricular */
  $oDaoCursoEscola = db_utils::getdao('cursoescola');
  $sSqlCursoEscola = $oDaoCursoEscola->sql_query($ed71_i_codigo);
  $rsCursoEscola   = $oDaoCursoEscola->sql_record($sSqlCursoEscola);

  if ($oDaoCursoEscola->numrows > 0) {

    $oDados = db_utils::fieldsmemory($rsCursoEscola, 0);

    /* Verifico se existe base vinculada */
    $oDaoBase    = db_utils::getdao('base');
    $sWhereBase  = "    cursoedu.ed29_i_codigo = ".$oDados->ed29_i_codigo." AND ";
    $sWhereBase .= "    escolabase.ed77_i_escola = $escola ";
    $sSqlBase    = $oDaoBase->sql_query_base("", "*", "", $sWhereBase);
    $rsBase      = $oDaoBase->sql_record($sSqlBase);
  
    if ($oDaoBase->numrows > 0) {
      $lErro = true;
    }

  }
  
  if (!$lErro) {

    db_inicio_transacao();
    $db_opcao = 3;
    $clcursoatoserie->excluir(""," ed216_i_codigo in (select ed216_i_codigo from cursoatoserie inner join cursoato on ed215_i_codigo = ed216_i_cursoato where ed215_i_cursoescola = $ed71_i_codigo)");
    $clcursoato->excluir(""," ed215_i_codigo in (select ed215_i_codigo from cursoato where ed215_i_cursoescola = $ed71_i_codigo)");
    $clcursoturno->excluir(""," ed85_i_escola = $escola AND ed85_i_curso = $ed71_i_curso");
    $clcursoescola->excluir($ed71_i_codigo);
    db_fim_transacao();
  
    $db_botao = false;

  } else {
    $sMensagem  = "Não é possível excluir este curso da escola pois o curso'+ \n";
    $sMensagem .= "'está vinculado a alguma Base Curricular e/ou Etapa.";
    echo "<script>\nalert('$sMensagem');\n</script>";
  }

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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Vincular curso <?=$ed29_c_descr?> na escola <?=$ed18_c_nome?></b></legend>
    <?include("forms/db_frmcursoescola.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
 if($clcursoescola->erro_status=="0"){
  $clcursoescola->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clcursoescola->erro_campo!=""){
   echo "<script> document.form1.".$clcursoescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clcursoescola->erro_campo.".focus();</script>";
  };
 }else{
  ?>
  <script>
   top.corpo.iframe_a3.location.href='edu1_cursoturno001.php?ed85_i_curso=<?=$ed71_i_curso?>&ed29_c_descr=<?=$ed29_c_descr?>';
   top.corpo.iframe_a4.location.href='edu1_cursoato001.php?ed71_i_curso=<?=$ed71_i_curso?>&ed29_c_descr=<?=$ed29_c_descr?>';
  </script>
  <?
  $clcursoescola->erro(true,true);
 };
};
if(isset($alterar)){
 if($clcursoescola->erro_status=="0"){
  $clcursoescola->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clcursoescola->erro_campo!=""){
   echo "<script> document.form1.".$clcursoescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clcursoescola->erro_campo.".focus();</script>";
  };
 }else{
  ?>
  <script>
   top.corpo.iframe_a3.location.href='edu1_cursoturno001.php?ed85_i_curso=<?=$ed71_i_curso?>&ed29_c_descr=<?=$ed29_c_descr?>';
   top.corpo.iframe_a4.location.href='edu1_cursoato001.php?ed71_i_curso=<?=$ed71_i_curso?>&ed29_c_descr=<?=$ed29_c_descr?>';
  </script>
  <?
  $clcursoescola->erro(true,true);
 };
};
if(isset($excluir)){
 if($clcursoescola->erro_status=="0"){
  $clcursoescola->erro(true,false);
 }else{
  ?>
  <script>
   top.corpo.iframe_a3.location.href='edu1_cursoturno001.php?ed85_i_curso=<?=$ed71_i_curso?>&ed29_c_descr=<?=$ed29_c_descr?>';
   top.corpo.iframe_a4.location.href='edu1_cursoato001.php?ed71_i_curso=<?=$ed71_i_curso?>&ed29_c_descr=<?=$ed29_c_descr?>';
  </script>
  <?
  $clcursoescola->erro(true,true);
 };
};
if(isset($cancelar)){
 echo "<script>location.href='".$clcursoescola->pagina_retorno."'</script>";
}
?>