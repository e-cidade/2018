<?php
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

//MODULO: biblioteca
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acervo_classe.php");
require_once("classes/db_biblioteca_classe.php");
require_once("classes/db_exemplar_classe.php");
require_once("classes/db_bib_parametros_classe.php");
require_once("classes/db_localizacao_classe.php");
require_once("classes/db_localacervo_classe.php");
require_once("classes/db_autor_classe.php");
require_once("classes/db_assunto_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clacervo         = new cl_acervo;
$clautor          = new cl_autor;
$classunto        = new cl_assunto;
$clbiblioteca     = new cl_biblioteca;
$clexemplar       = new cl_exemplar;
$clbib_parametros = new cl_bib_parametros;
$cllocalizacao    = new cl_localizacao;
$cllocalacervo    = new cl_localacervo;
$clcolecaoacervo  = new cl_colecaoacervo;

$clacervo->rotulo->label("bi06_titulo");
$clacervo->rotulo->label("bi06_seq");
$clautor->rotulo->label("bi01_nome");
$classunto->rotulo->label("bi15_assunto");
$cllocalizacao->rotulo->label("bi09_nome");
$clcolecaoacervo->rotulo->label("bi29_sequencial");
$clcolecaoacervo->rotulo->label("bi29_nome");

$depto = db_getsession("DB_coddepto");
$sql1 = "SELECT bi17_codigo,bi17_nome FROM biblioteca WHERE bi17_coddepto = $depto";
$result1 = db_query($sql1);
$linhas1 = pg_num_rows($result1);
if($linhas1!=0){
 db_fieldsmemory($result1,0);
 $result1 = $clbib_parametros->sql_record($clbib_parametros->sql_query("","bi26_leitorbarra",""," bi26_biblioteca = $bi17_codigo"));
 if($clbib_parametros->numrows>0){
  db_fieldsmemory($result1,0);
 }else{
  $bi26_leitorbarra = "N";
 }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
  db_app::load("scripts.js, prototype.js, strings.js");
  db_app::load("estilos.css");
?>
<script>
function js_pesquisaColecao(lMostra) {

  var sUrl = 'func_colecaoacervo.php?';
  if(lMostra) {

    sUrl += 'funcao_js=parent.js_mostraColecao1|bi29_sequencial|bi29_nome';
    js_OpenJanelaIframe('', 'db_iframe_colecaoacervo', sUrl, 'Pesquisa Coleção', true);
  } else  {

    if($F('bi29_sequencial') != '') {

      sUrl += 'pesquisa_chave='+$F('bi29_sequencial');
      sUrl += '&funcao_js=parent.js_mostraColecao';
      js_OpenJanelaIframe('','db_iframe_colecaoacervo', sUrl,'Pesquisa Coleção',false);
    } else {
      $('bi29_sequencial').value = "";
    }
  }
}

function js_mostraColecao(sNome, lErro ) {

  $('bi29_nome').value = sNome;
  if (lErro) {

    $('bi29_sequencial').value  = '';
    $('bi29_nome').value = sNome;
    $('bi29_nome').focus();
  }
}

function js_mostraColecao1(iColecao, sNome) {

  $('bi29_sequencial').value  = iColecao;
  $('bi29_nome').value = sNome;
  db_iframe_colecaoacervo.hide();
}
</script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" valign="top">
   <table width="35%" border="0" cellspacing="0">
    <form name="form1" method="post" action="" >
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tbi06_seq?>">
      <?=$Lbi06_seq?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("bi06_seq",10,$Ibi06_seq,true,"text",4,"","chave_bi06_seq");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tbi06_titulo?>">
      <?=$Lbi06_titulo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("bi06_titulo",50,$Ibi06_titulo,true,"text",4,"","chave_bi06_titulo");?>
     </td>
    </tr>
     <tr>
     <td width="4%" align="right" nowrap title="<?=$Tbi15_assunto?>">
      <?=$Lbi15_assunto?>
     </td>
     <td width="96%" align="left" nowrap>
      <?//db_textarea("bi15_assunto",1,48,$Ibi15_assunto,true,'text',4,"chave_bi15_assunto");?>
      <?db_input("bi15_assunto",50,$Ibi15_assunto,true,"text",4,"","chave_bi15_assunto");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tbi01_nome?>">
      <b>Nome do Autor:</b>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("bi01_nome",50,$Ibi01_nome,true,"text",4,"","chave_bi01_nome");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tbi09_nome?>">
      <b>Localização:</b>
     </td>
     <td width="96%" align="left" nowrap>
      <?
      $result_loc = $cllocalizacao->sql_record($cllocalizacao->sql_query_file("","bi09_codigo,bi09_nome","bi09_nome"," bi09_biblioteca = $bi17_codigo"));
      db_selectrecord("chave_bi20_localizacao",$result_loc,"","","","chave_bi20_localizacao","","  ","",1);
      ?>
     </td>
    </tr>
    <?if($bi26_leitorbarra=="S"){?>
    <tr>
     <td width="4%" align="right">
      <b>Código de Barras:</b>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("bi23_codbarras",20,@$Ibi23_codbarras,true,"text",4,"","chave_bi23_codbarras");?>
     </td>
    </tr>
    <?}?>

    <tr>
      <td align="right" nowrap title="Coleção">
       <? db_ancora("Coleção: ","js_pesquisaColecao(true);",1);?>
      </td>
      <td align="left" nowrap title="Coleção">
       <?
        db_input("bi29_sequencial", 10, $Ibi29_sequencial, true, "text", 1, "onchange='js_pesquisaColecao(false);'");
        db_input("bi29_nome", 36, '', true, "text", 3, '');
       ?>
      </td>
    </tr>

    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_acervo.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?php
   $depto = db_getsession("DB_coddepto");
   $result = $clbiblioteca->sql_record($clbiblioteca->sql_query("","bi17_codigo,bi17_nome",""," bi17_coddepto = $depto"));
   if($clbiblioteca->numrows!=0){
    db_fieldsmemory($result,0);
        $where = " bi06_biblioteca = $bi17_codigo AND bi17_coddepto = $depto AND ";
        $where1 = " bi06_biblioteca = $bi17_codigo";
        if(isset($campos)==false){
         if(file_exists("funcoes/db_func_acervo.php")==true){
          include("funcoes/db_func_acervo.php");
         }else{
          $campos = "acervo.*";
         }
        }
        if(!isset($pesquisa_chave) && !isset($pesquisa_chave2) && !isset($pesquisa_chave3) ){
         if(isset($chave_bi06_seq) && (trim($chave_bi06_seq)!="") ){
          $sql = $clacervo->sql_query("",$campos,"bi06_titulo",$where." bi06_seq = $chave_bi06_seq");
         }else if(isset($chave_bi06_titulo) && (trim($chave_bi06_titulo)!="") ){
            $sql = $clacervo->sql_query("",$campos,"bi06_titulo",$where." bi06_titulo like '$chave_bi06_titulo%' ");
         }else if(isset($chave_bi15_assunto) && (trim($chave_bi15_assunto)!="") ){
            $sql = $classunto->sql_query_consacervo("",$campos,"bi15_assunto",$where." (bi06_titulo like '%$chave_bi15_assunto%' or bi15_assunto like '%$chave_bi15_assunto%') ");
         }else if(isset($chave_bi01_nome) && (trim($chave_bi01_nome)!="") ){
          $sql = "select DISTINCT $campos
                  from autoracervo
                   inner join acervo on bi06_seq = bi21_acervo
                   inner join autor on bi01_codigo = bi21_autor
                   left join localacervo  on  localacervo.bi20_acervo = acervo.bi06_seq
                   left join localizacao  on  localizacao.bi09_codigo = localacervo.bi20_localizacao
                  where $where1 and bi01_nome like '$chave_bi01_nome%'
                 ";
         }else if(isset($chave_bi20_localizacao) && (trim($chave_bi20_localizacao)!="") ){
          $sql = $cllocalacervo->sql_query("",$campos,"bi20_sequencia",$where." bi20_localizacao = $chave_bi20_localizacao");
         }else if(isset($chave_bi23_codbarras) && (trim($chave_bi23_codbarras)!="") ){
          $sql = $clexemplar->sql_query("",$campos,"bi06_titulo",$where." bi23_codbarras = ".trim($chave_bi23_codbarras)."");
         }else if(isset($bi29_sequencial) && (trim($bi29_sequencial)!="") ){
          $sql = $clacervo->sql_query("",$campos,"bi29_nome",$where." bi29_sequencial = ".trim($bi29_sequencial)."");
         }else{
          //$sql = $clacervo->sql_query("",$campos,"bi06_titulo",$where1);
         }
         $repassa = array();
         if(isset($chave_bi10_codigo)){
          $repassa = array("chave_bi06_seq"=>$chave_bi06_seq,
                           "chave_bi06_titulo"=>$chave_bi06_titulo,
                           "chave_bi01_nome"=>$chave_bi01_nome,
                           "chave_bi23_codbarras"=>$chave_bi23_codbarras,
                           "chave_bi20_localizacao"=>$chave_bi20_localizacao,
                           "chave_bi29_nome"=>$bi29_nome);
         }
         db_lovrot(@$sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        }else{
         if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clacervo->sql_record($clacervo->sql_query("",$campos,"",$where." bi06_seq = $pesquisa_chave"));
          if($clacervo->numrows!=0){
           db_fieldsmemory($result,0);
           echo "<script>".$funcao_js."('$bi06_titulo',false);</script>";
          }else{
           echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
         }elseif($pesquisa_chave2!=null && $pesquisa_chave2!=""){
          $result = $clacervo->sql_record($clacervo->sql_query("",$campos,"",$where." bi06_seq = $pesquisa_chave2"));
          if($clacervo->numrows!=0){
           db_fieldsmemory($result,0);
           echo "<script>".$funcao_js."('$bi06_titulo',$bi06_seq,false);</script>";
          }else{
           echo "<script>".$funcao_js."('Chave(".$pesquisa_chave2.") não Encontrado',true);</script>";
          }
         }elseif($pesquisa_chave3!=null && $pesquisa_chave3!=""){
          $result = $clacervo->sql_record($clacervo->sql_query("",$campos,"",$where." bi06_seq = $pesquisa_chave3"));
          if($clacervo->numrows!=0){
           db_fieldsmemory($result,0);
           echo "<script>".$funcao_js."('$bi06_titulo',false);</script>";
          }else{
           echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
         }else{
          echo "<script>".$funcao_js."('',false);</script>";
         }
        }
     }else{
      echo "<script>alert('Erro na pesquisa.  Biblioteca não cadastrada.');</script>";
     }
     ?>
  </td>
 </tr>
</table>
</body>
</html>
<script>
 <?if($bi26_leitorbarra=="S"){?>
  js_tabulacaoforms("form1","chave_bi23_codbarras",true,1,"chave_bi23_codbarras",true);
 <?}else{?>
  js_tabulacaoforms("form1","chave_bi06_titulo",true,1,"chave_bi06_titulo",true);
 <?}?>
</script>