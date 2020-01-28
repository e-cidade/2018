<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_conrelinfo_classe.php");
include("classes/db_conrelvalor_classe.php");
include("classes/db_orcparamrel_classe.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clconrelinfo  = new cl_conrelinfo;
$clconrelvalor = new cl_conrelvalor;
$clorcparamrel = new cl_orcparamrel;

$clrotulo   = new rotulocampo;
$clrotulo->label('c83_codrel');
$clrotulo->label('c83_codigo');
$clrotulo->label('c83_variavel');
$clrotulo->label('c83_informacao');
$clrotulo->label('o42_descrrel');
$clrotulo->label('c83_variavel');
$clrotulo->label("c83_periodo");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$db_opcao = 1;
$db_botao = true;
$instit   = db_getsession("DB_instit");
$sqlerro  = false; 
$erro_msg = "";
$anousu=db_getsession("DB_anousu");
$res = $clorcparamrel->sql_record($clorcparamrel->sql_query($c83_codrel));
if ($clorcparamrel->numrows>0) {
  db_fieldsmemory($res,0);
}

if (isset($novo) && trim(@$novo) != ""){
  $c83_periodo_ant = "";
}

if (isset($opcao) && ($opcao == "alterar" || $opcao == "excluir")) {
  $dbwhere = "";
  if (isset($c83_periodo) && trim(@$c83_periodo)!=""){
    $dbwhere = "and conrelvalor.c83_periodo = '".$c83_periodo."'";
    $c83_periodo_ant = $c83_periodo;
  }
  $res = $clconrelinfo->sql_record($clconrelinfo->sql_query("",$instit,"*","","c83_codrel=$c83_codrel and conrelinfo.c83_codigo=$c83_codigo $dbwhere"));
  if ($clconrelinfo->numrows > 0){
    db_fieldsmemory($res,0);
  }

  if ($opcao == "alterar"){
    $db_opcao = 2;
  }

  if ($opcao == "excluir"){
       $db_opcao = 3;
  }
}

if (isset($excluir)){
  db_inicio_transacao();

  $clconrelvalor->excluir($c83_codigo,$instit,$c83_periodo);
  $erro_msg = $clconrelvalor->erro_msg;
  if ($clconrelvalor->erro_status == "0"){
    $sqlerro = true;
  }

  $db_opcao = 1;
  db_fim_transacao($sqlerro);
}

if (isset($alterar)) {
  db_inicio_transacao();
  $clconrelvalor->c83_informacao = "$c83_informacao";
  $clconrelvalor->c83_instit     = "$instit";
  $clconrelvalor->c83_periodo    = "$c83_periodo";
  $clconrelvalor->c83_anousu     = "$anousu";

  $res = $clconrelvalor->sql_record($clconrelvalor->sql_query_file($c83_codigo,$instit,$c83_periodo_ant));
  if ($clconrelvalor->numrows > 0){
    $clconrelvalor->excluir($c83_codigo,$instit,$c83_periodo_ant);
    $clconrelvalor->incluir($c83_codigo,$instit,$c83_periodo); 
    //$clconrelvalor->alterar($c83_codigo,$instit); 
  } else {
    $clconrelvalor->incluir($c83_codigo,$instit,$c83_periodo); 
  }

  $erro_msg = $clconrelvalor->erro_msg;
  if ($clconrelvalor->erro_status == "0"){
    $sqlerro = true;
  }
  $db_opcao = 1;
  db_fim_transacao($sqlerro);
}

if (isset($incluir)){
  db_inicio_transacao();
  $clconrelvalor->c83_instit     = "$instit";
  $clconrelvalor->c83_informacao = "$c83_informacao";
  $clconrelvalor->c83_periodo    = "$c83_periodo";
  $clconrelvalor->c83_anousu     = "$anousu";
  $clconrelvalor->incluir($c83_codigo,$instit,$c83_periodo); 
  $erro_msg = $clconrelvalor->erro_msg;
  if ($clconrelvalor->erro_status == "0"){
    $sqlerro = true;
  }
  $db_opcao = 1;
  db_fim_transacao($sqlerro);
}

if ($db_opcao==1) {
  if ($sqlerro == false){
  // limpa campos ;
    $c83_variavel   = "";
    $c83_informacao = "";
    $c83_codigo     = "";
    $c83_periodo    = "";
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<form name="form1" method="post" action="" >
 <table  align="center" border=0>
 <tr><td align=left width=20%><?=@$Lc83_codrel?></td>
     <td><? db_input('c83_codrel',5,$Ic83_codrel,true,'text',3,"")?>
         <? db_input('o42_descrrel',40,$Io42_descrrel,true,'text',3,"")?></td>
 </tr>
 <tr><td><? db_ancora(@$Lc83_codigo,"",3);  ?></td>
     <td><? db_input('c83_codigo',5,$Ic83_codigo,true,'text',3,"") ?></td>
 </tr>
 <tr><td><? db_ancora(@$Lc83_variavel,"",3);  ?></td>
     <td><? 
            if ($db_opcao != 1) {
              db_input('c83_variavel',50,$Ic83_variavel,true,'text',3,""); 
            } else {
              $res_conrelinfo = $clconrelinfo->sql_record($clconrelinfo->sql_query_file("","c83_codigo,c83_variavel","c83_codigo","c83_codrel=$c83_codrel"));
              db_selectrecord("c83_codigo",$res_conrelinfo,true,$db_opcao,"","","","","",1);
            }
         ?></td>
 </tr>
 <tr>
    <td><? db_ancora(@$Lc83_informacao,"",3);  ?></td>
    <td>
    <? db_input('c83_informacao',30,$Ic83_informacao,true,'text',$db_opcao,"onKeyUp=this.value=this.value.replace(',','.')") ?></td>
 </tr>
 <tr>
    <td><?=$Lc83_periodo?></td>
    <td>
      <?
         db_input("c83_periodo_ant",10,0,true,"hidden",3,"");
         $matriz = array("0"=>"Nenhum",
                         "1B"=>"Primeiro Bimestre",
                         "2B"=>"Segundo Bimestre",
                         "3B"=>"Terceiro Bimestre",
                         "4B"=>"Quarto Bimestre",
                         "5B"=>"Quinto Bimestre",
                         "6B"=>"Sexto Bimestre",
                         "1Q"=>"Primeiro Quadrimestre",
                         "2Q"=>"Segundo  Quadrimestre",
                         "3Q"=>"Terceiro Quadrimestre",
                         "1S"=>"Primeiro Semestre",
                         "2S"=>"Segundo Semestre");
/*         
         if ($db_opcao == 1){
           $db_opcao02 = $db_opcao;   
         }

         if ($db_opcao != 1){
           if (isset($c83_informacao) && trim(@$c83_informacao)==""){
             $db_opcao02 = $db_opcao;  
           } else {
             $db_opcao02 = 3;  
           }
         }
*/
         db_select("c83_periodo",$matriz,true,$db_opcao);
      ?>
    </td>
 </tr>
 <tr><td colspan=2 align=center>
      <input 
          name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
          type="submit" id="db_opcao" 
          value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
          <?=($db_botao==false?"disabled":"")?> ><?

          if ($db_opcao != 1){
      ?>
         <input name="novo" type="submit" value="Novo">
      <?
          }
      ?>
     </td>
 </tr>    
 
 <tr>
 <td colspan=2>
 <?
   $chavepri= array("c83_codigo"=>@$c83_codigo,"c83_codrel"=>@$c83_codrel,"c83_periodo"=>@$c83_periodo);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   $cliframe_alterar_excluir->sql   = $clconrelinfo->sql_query(null,$instit,"*","conrelinfo.c83_codigo,conrelvalor.c83_periodo","c83_codrel=$c83_codrel");
//   $cliframe_alterar_excluir->campos = "c83_codigo,c83_codrel,c83_variavel,c83_informacao,c83_periodo";
   $cliframe_alterar_excluir->campos = "c83_variavel,c83_informacao,c83_periodo";
   $cliframe_alterar_excluir->legenda= "Variaveis";     
   $cliframe_alterar_excluir->iframe_height ="240";
   $cliframe_alterar_excluir->iframe_width  ="1090";
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);

   if (trim(@$erro_msg) != ""){
     db_msgbox($erro_msg);
   }
 ?>
  </td>
 </tr>
 </table>
</form>
</body>
</html>