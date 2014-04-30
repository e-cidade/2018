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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_base_classe.php");
require_once("classes/db_turma_classe.php");
require_once("classes/db_regencia_classe.php");
require_once("classes/db_baseserie_classe.php");
require_once("classes/db_basemps_classe.php");
require_once("classes/db_basediscglob_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clbase         = new cl_base;
$clturma        = new cl_turma;
$clregencia     = new cl_regencia;
$clbaseserie    = new cl_baseserie;
$clbasemps      = new cl_basemps;
$clbasediscglob = new cl_basediscglob;
$db_opcao       = 22;
$db_opcao1      = 3;
$db_botao       = false;

if (isset($alterar)) {

  /**
   * Buscamos os codigos das etapas permitidas para base curricular selecionada, e validamos se a etapa sera
   * compativel com o permitido
   */
  $lSerieInicialCompativel = false;
  $lSerieFinalCompativel   = false;
  $oDaoSerieRegimeMat      = db_utils::getDao("serieregimemat");
  $sWhereSerieRegimeMat    = " ed11_i_ensino = {$ed29_i_ensino} and ed223_i_regimemat = {$ed31_i_regimemat} ";
  $sSqlSerieRegimeMat      = $oDaoSerieRegimeMat->sql_query(null, "ed11_i_codigo", null, $sWhereSerieRegimeMat);
  $rsSerieRegimeMat        = $oDaoSerieRegimeMat->sql_record($sSqlSerieRegimeMat);
  $iTotalSerieRegimeMat    = $oDaoSerieRegimeMat->numrows;
  
  if ($iTotalSerieRegimeMat > 0) {
    
    for ($iContador = 0; $iContador < $iTotalSerieRegimeMat; $iContador++) {

      $iSerie = db_utils::fieldsMemory($rsSerieRegimeMat, $iContador)->ed11_i_codigo;
      if ($ed87_i_serieinicial == $iSerie) {
        $lSerieInicialCompativel = true;
      }
      
      if ($ed87_i_seriefinal == $iSerie) {
        $lSerieFinalCompativel = true;
      }
    }
  }
  
  if (!$lSerieInicialCompativel) {
    
    $sMensagem = "Etapa inicial não permitida para esta base curricular (Etapa selecionada: {$ed87_i_serieinicial}).";
    db_msgbox($sMensagem);
    db_redireciona("edu1_base002.php?chavepesquisa=$ed31_i_codigo");
    break;
  } else if (!$lSerieFinalCompativel) {
    
    $sMensagem = "Etapa final não permitida para esta base curricular (Etapa selecionada: {$ed87_i_seriefinal}).";
    db_msgbox($sMensagem);
    db_redireciona("edu1_base002.php?chavepesquisa=$ed31_i_codigo");
    break;
  }
  
  $db_opcao = 2;
  $db_botao = true;
  db_inicio_transacao();
  $clbase->alterar($ed31_i_codigo);
  $clbaseserie->ed87_i_codigo = $ed31_i_codigo;
  $clbaseserie->alterar($ed31_i_codigo);
  if (@$ed31_c_contrfreq == "G") {
    
    $result = $clbasediscglob->sql_record($clbasediscglob->sql_query("","*",""," ed89_i_codigo = $ed31_i_codigo"));
    if ($clbasediscglob->numrows == 0) {
     $clbasediscglob->incluir($ed31_i_codigo);
    } else {
      
      $clbasediscglob->ed89_i_codigo = $ed31_i_codigo;
      $clbasediscglob->alterar($ed31_i_codigo);
    }
  } else {
    $clbasediscglob->excluir($ed31_i_codigo);
  }
  db_fim_transacao();
} elseif (isset($chavepesquisa)) {
  
  $db_opcao  = 2;
  $db_opcao1 = 3;
  $campos    = "base.*,
                baseserie.*,
                basediscglob.*,
                regimemat.*,
                si.ed11_c_descr as ed11_c_descrini,
                sf.ed11_c_descr as ed11_c_descrfim,
                caddisciplina.ed232_c_descr,
                disciplina.*,
                cursoedu.*,
                ensino.*
               ";
  $result = $clbase->sql_record($clbase->sql_query_base("",$campos,""," ed31_i_codigo = $chavepesquisa "));
  db_fieldsmemory($result,0);
  $db_botao = true;
 ?>
 <script>
  parent.document.formaba.a2.disabled = false;
  parent.document.formaba.a3.disabled = false;
  parent.document.formaba.a4.disabled = false;
  <?if($ed31_c_contrfreq=="G"){?>
   top.corpo.iframe_a2.location.href='edu1_basempsabas001.php?ed34_i_base=<?=$ed31_i_codigo?>&ed31_c_descr=<?=$ed31_c_descr?>&curso=<?=$ed31_i_curso?>&discglob=<?=$ed89_i_disciplina?>&qtdper=<?=$ed89_i_qtdperiodos?>';
  <?}else{?>
   top.corpo.iframe_a2.location.href='edu1_basempsabas001.php?ed34_i_base=<?=$ed31_i_codigo?>&ed31_c_descr=<?=$ed31_c_descr?>&curso=<?=$ed31_i_curso?>&discglob=0&qtdper=0';
  <?}?>
  top.corpo.iframe_a3.location.href='edu1_escolabase001.php?ed77_i_base=<?=$ed31_i_codigo?>&ed31_c_descr=<?=$ed31_c_descr?>';
  top.corpo.iframe_a4.location.href='edu1_baseato001.php?ed77_i_base=<?=$ed31_i_codigo?>&ed31_c_descr=<?=$ed31_c_descr?>';
 </script>
 <?
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Alteração de Base Curricular</b></legend>
    <?include("forms/db_frmbase.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($chavepesquisa)){
 if($ed218_c_divisao=="S"){
  ?>
  <script>js_divisoes(<?=$ed31_i_regimemat?>,"A");</script>
  <?
 }
}
if(isset($alterar)){
 $temerro = false;
 if($clbase->erro_status=="0"){
  $clbase->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clbase->erro_campo!=""){
   echo "<script> document.form1.".$clbase->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clbase->erro_campo.".focus();</script>";
  };
  $temerro = true;
 }
 if(@$clbaseserie->erro_status=="0"){
  $clbaseserie->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clbaseserie->erro_campo!=""){
   echo "<script> document.form1.".$clbaseserie->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clbaseserie->erro_campo.".focus();</script>";
  };
  $temerro = true;
 }
 if(@$clbasediscglob->erro_status=="0"){
  $clbasediscglob->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clbasediscglob->erro_campo!=""){
   echo "<script> document.form1.".$clbasediscglob->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clbasediscglob->erro_campo.".focus();</script>";
  };
  $temerro = true;
 }
 if($temerro==false){
  $result2 = $clbaseserie->sql_record($clbaseserie->sql_query("","si.ed11_i_sequencia as seqini,sf.ed11_i_sequencia as seqfim, si.ed11_i_ensino as ensino",""," ed87_i_codigo = $ed31_i_codigo"));
  db_fieldsmemory($result2,0);
  $result3 = $clbasemps->sql_record($clbasemps->sql_query("","ed34_i_codigo",""," ed11_i_ensino = $ensino AND ed34_i_base = $ed31_i_codigo AND (ed11_i_sequencia < $seqini OR ed11_i_sequencia > $seqfim)"));
  $linhas3 = $clbasemps->numrows;
  for($a=0;$a<$linhas3;$a++){
   db_fieldsmemory($result3,$a);
   $clbasemps->excluir($ed34_i_codigo);
  }
  $clbase->erro(true,false);
  ?>
  <script>
   parent.document.formaba.a2.disabled = false;
   parent.document.formaba.a3.disabled = false;
   parent.document.formaba.a4.disabled = false;
   <?
   if($ed31_c_contrfreq=="G"){
    $sql = "UPDATE basemps SET
             ed34_i_qtdperiodo = 0
            WHERE ed34_i_base = $ed31_i_codigo";
    $query = pg_query($sql);
    $sql = "UPDATE basemps SET
             ed34_i_qtdperiodo = $ed89_i_qtdperiodos,
             ed34_c_condicao = 'OB'
            WHERE ed34_i_base = $ed31_i_codigo
            AND ed34_i_disciplina = $ed89_i_disciplina";
    $query = pg_query($sql);
    ?>
    top.corpo.iframe_a2.location.href='edu1_basempsabas001.php?ed34_i_base=<?=$ed31_i_codigo?>&ed31_c_descr=<?=$ed31_c_descr?>&curso=<?=$ed31_i_curso?>&discglob=<?=$ed89_i_disciplina?>&qtdper=<?=$ed89_i_qtdperiodos?>';
   <?}else{?>
    top.corpo.iframe_a2.location.href='edu1_basempsabas001.php?ed34_i_base=<?=$ed31_i_codigo?>&ed31_c_descr=<?=$ed31_c_descr?>&curso=<?=$ed31_i_curso?>&discglob=0&qtdper=0';
   <?}?>
   top.corpo.iframe_a3.location.href='edu1_escolabase001.php?ed77_i_base=<?=$ed31_i_codigo?>&ed31_c_descr=<?=$ed31_c_descr?>';
   top.corpo.iframe_a4.location.href='edu1_baseato001.php?ed77_i_base=<?=$ed31_i_codigo?>&ed31_c_descr=<?=$ed31_c_descr?>';
   top.corpo.iframe_a1.location.href='edu1_base002.php?chavepesquisa=<?=$ed31_i_codigo?>';
  </script>
  <?
 }
};
if($db_opcao==22){
 echo "<script>document.form1.pesquisar.click();</script>";
}
?>