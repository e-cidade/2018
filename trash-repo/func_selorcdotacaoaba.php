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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_SERVER_VARS,2);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>

function js_marca() {
    var org = '';
    var virg = '';
    var F = document.form1;
    if(document.form1.marca.value == 'Marca Todos'){
        var dis = true;
        document.form1.marca.value = 'Desmarca Todos';
    } else {
        var dis = false;
        document.form1.marca.value = 'Marca Todos';
    }
    for(i = 0;i < F.elements.length;i++) {
       if(F.elements[i].type == "checkbox"){
         F.elements[i].checked = dis;
         if(F.elements[i].id.substr(0,6)=='ultimo'){
//        alert(F.elements[i].id.substr(0,6));
           org = org+virg+F.elements[i].value;
           virg = '-';
	 }
       }
    }
    //alert(org);
    parent.document.form1.<?=(isset($qvernivel)?$qvernivel:"vernivel")?>.value = document.form1.nivel.value;
    parent.document.form1.<?=(isset($qorgaos)?$qorgaos:"orgaos")?>.value = org;					
  
}



function js_marcafilho(qpai){


  if(document.form1.elements[qpai].checked)
    acao = true;
  else
    acao = false;

  qpai = qpai + '_';
  
  tam = document.form1.elements.length;
  
  for(i=0;i<tam;i++){
     obj = document.form1.elements[i].value;
     if(obj.indexOf(qpai)!=-1){
        document.form1.elements[i].checked = acao;
     }
  }
  var org = '';
  var virg = '';
  var F = document.form1;
  for(i = 0;i < F.elements.length;i++) {
     if(F.elements[i].type == "checkbox" &&  F.elements[i].checked ){
//        alert(F.elements[i].id.substr(0,6));
        if(F.elements[i].id.substr(0,6)=='ultimo'){
          org = org+virg+F.elements[i].value;
          virg = '-';
	}
     }
  }
 parent.document.form1.<?=(isset($qvernivel)?$qvernivel:"vernivel")?>.value = document.form1.nivel.value;
 parent.document.form1.<?=(isset($qorgaos)?$qorgaos:"orgaos")?>.value = org;					
  
}
function js_marcapai(qpai){

  xqpai = qpai + '_';
  tam = document.form1.elements.length;
  entrou = false;
  for(i=0;i<tam;i++){
     obj = document.form1.elements[i].value;
     if(obj.indexOf(xqpai)!=-1){

       if(document.form1.elements[i].checked){
          document.form1.elements[qpai].checked = true;
	  entrou = true;
       }
     }
  }
  if(!entrou)
    document.form1.elements[qpai].checked = false;
     
  var org = '';
  var virg = '';
  var F = document.form1;
  for(i = 0;i < F.elements.length;i++) {
     if(F.elements[i].type == "checkbox" &&  F.elements[i].checked ){
//        alert(F.elements[i].id.substr(0,6));
        if(F.elements[i].id.substr(0,6)=='ultimo'){
          org = org+virg+F.elements[i].value;
          virg = '-';
	}
     }
  }
//  alert(org);
  parent.document.form1.<?=(isset($qvernivel)?$qvernivel:"vernivel")?>.value = document.form1.nivel.value;
  parent.document.form1.<?=(isset($qorgaos)?$qorgaos:"orgaos")?>.value = org;					
  
}



</script>


</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" action="post">
<table border="1"  align="center" cellspacing="1" bgcolor="#CCCCCC">
 <tr> 
    <td colspan="3" valign="middle">
      <input id="marca" type="button" value="Marca Todos" onclick="js_marca();return false" >
    </td>
  </tr>
<?
$xnumero = substr($nivel,0,1);
if ($xnumero == 1){
  $xtitulo = 'Nivel 1 - Órgao';
}elseif ($xnumero == 2){
  $xtitulo = 'Nivel 2 - Unidade';
}elseif ($xnumero == 3){
  $xtitulo = 'Nivel 3 - Função';
}elseif ($xnumero == 4){
  $xtitulo = 'Nivel 4 - Subfunção';
}elseif ($xnumero == 5){
  $xtitulo = 'Nivel 5 - Programa';
}elseif ($xnumero == 6){
  $xtitulo = 'Nivel 6 - Projeto/Atividade';
}elseif ($xnumero == 7){
  $xtitulo = 'Nivel 7 - Elemento';
}elseif ($xnumero == 8){
  $xtitulo = 'Nivel 8 - Recurso';
}
?>
  <tr>
    <td colspan="3" align="center"><strong><?=$xtitulo?></strong><td>
  <tr>
<?

$sel_orgaos = " o58_instit in (".str_replace('-',',',$db_selinstit).") ";

if(substr($nivel,1,1) == 'B'){
  $result = db_dotacaosaldo(substr($nivel,0,1),3,2,true,$sel_orgaos,db_getsession("DB_anousu"));
//  db_criatabela($result);
  for($i=0;$i<pg_numrows($result);$i++){
    db_fieldsmemory($result,$i);
//  db_criatabela($result);exit;
    if ($xnumero == 1){
      $xnivel  = db_formatar($o58_orgao,'orgao');
      $descr   = $o40_descr;
      $xtitulo = 'Nivel 1 - Órgao';
    }elseif ($xnumero == 2){
      $xnivel = db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao');
      $descr  = $o41_descr;
      $xtitulo = 'Nivel 2 - Unidade';
    }elseif ($xnumero == 3){
      $xnivel = $o58_funcao;
      $descr  = $o52_descr;
      $xtitulo = 'Nivel 3 - Função';
    }elseif ($xnumero == 4){
      $xnivel = $o58_subfuncao;
      $descr  = $o53_descr;
      $xtitulo = 'Nivel 4 - Subfunção';
    }elseif ($xnumero == 5){
      $xnivel = $o58_programa;
      $descr  = $o54_descr;
      $xtitulo = 'Nivel 5 - Programa';
    }elseif ($xnumero == 6){
      $xnivel = $o58_projativ;
      $descr  = $o55_descr;
      $xtitulo = 'Nivel 6 - Projeto/Atividade';
    }elseif ($xnumero == 7){
      $xnivel = $o58_elemento;
      $descr  = $o56_descr;
      $xtitulo = 'Nivel 7 - Elemento';
    }elseif ($xnumero == 8){
      $xnivel = $o58_codigo;
      $descr  = $o15_descr;
      $xtitulo = 'Nivel 8 - Recurso';
    }
?>
  <tr>
    <td colspan="3">&nbsp;<td>
  <tr>
  <tr> 
    <td colspan="3" valign="middle"><input type="checkbox" id="ultimo_<?=$xnivel?>" value="pai_<?=$xnivel?>" onclick="js_marcafilho('pai_<?=$xnivel?>');" name="pai_<?=$xnivel?>">&nbsp;&nbsp;<strong><?=$xnivel?></strong>&nbsp;&nbsp;-&nbsp;&nbsp;<strong><?=$descr?></strong></td>
  </tr>

<?
 }
}else{

if($nivel >= '1A'){
  $sql1 = "select 	distinct 
                	o41_orgao,
       	 	 	o40_descr 
          from orcunidade 
	       inner join orcorgao on o41_orgao = o40_orgao  and o40_anousu = ".db_getsession("DB_anousu")."
	       inner join (select distinct o58_orgao 
	                   from orcdotacao 
			   where ".$sel_orgaos." 
	                       and o58_anousu = ".db_getsession("DB_anousu").") as x
	                           on o40_orgao = o58_orgao
         where o41_anousu = ".db_getsession("DB_anousu");
//echo $sql1;
$result1 = pg_exec($sql1);
for($i1=0;$i1<pg_numrows($result1);$i1++){
   db_fieldsmemory($result1,$i1);
?>
  <tr>
    <td colspan="3">&nbsp;<td>
  <tr>
  <tr> 
    <td colspan="3" valign="middle"><input type="checkbox" value="pai_<?=$o41_orgao?>" onclick="js_marcafilho('pai_<?=$o41_orgao?>');" name="pai_<?=$o41_orgao?>" id="<?=$nivel=='1A'?'ultimo_'.$o41_orgao:'primeiro_'.$o41_orgao?>" >&nbsp;&nbsp;<strong><?=$o41_orgao?></strong>&nbsp;&nbsp;-&nbsp;&nbsp;<strong><?=$o40_descr?></strong></td>
  </tr>
  <?
   if ($nivel >= '2A'){
      $sql2 = "select 	distinct 
                 	o41_unidade,
     	 		o41_descr 
           from orcunidade 
	        inner join orcorgao on o41_orgao = $o41_orgao 
	                           and o41_anousu = ".db_getsession("DB_anousu");
      $result2 = pg_exec($sql2);
       for($i2=0;$i2<pg_numrows($result2);$i2++){
         db_fieldsmemory($result2,$i2);
   ?>
  <tr>
    <td>
        <img src="imagens/alinha.gif" width="15">
      <input type="checkbox"  onclick="js_marcapai('pai_<?=$o41_orgao?>');" value="pai_<?=$o41_orgao?>_<?=$o41_unidade?>" name="pai_<?=$o41_orgao?>_<?=$o41_unidade?>" id="<?=$nivel=='2A'?'ultimo_'.$o41_unidade:'primeiro_'.$o41_unidade?>">&nbsp;&nbsp;<?=$o41_unidade?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=$o41_descr?></td>
  </tr>
    <?
  
      if ($nivel >= '3A'){
        $sql3 = "select 	distinct 
         	          	o52_funcao,
     	 			o52_descr 
                 from orcdotacao 
   	              inner join orcfuncao on o58_funcao = o52_funcao
	         where o58_orgao 	= $o41_orgao 
	           and o58_unidade 	= $o41_unidade 
	           and o58_anousu = ".db_getsession("DB_anousu");
        $result3 = pg_exec($sql3);
        for($i3=0;$i3<pg_numrows($result3);$i3++){
           db_fieldsmemory($result3,$i3);
       ?>
  <tr>
    <td>
        <img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
      <input type="checkbox"  onclick="js_marcapai('pai_<?=$o41_orgao?>_<?=$o41_unidade?>');" value="pai_<?=$o41_orgao?>_<?=$o41_unidade?>_<?=$o52_funcao?>" name="pai_<?=$o41_orgao?>_<?=$o41_unidade?>_<?=$o52_funcao?>" id="<?=$nivel=='3A'?'ultimo_'.$o52_funcao:'primeiro_'.$o52_funcao?>">&nbsp;&nbsp;<?=$o52_funcao?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=$o52_descr?></td>
  </tr>
  <?
   if ($nivel >= '4A'){
      $sql4 = "  select	distinct 
                 	o53_subfuncao,
     	 		o53_descr 
                 from orcdotacao 
   	              inner join orcsubfuncao on o58_subfuncao = o53_subfuncao
	         where o58_orgao 	= $o41_orgao 
	           and o58_unidade 	= $o41_unidade 
		   and o58_funcao       = $o52_funcao
	           and o58_anousu = ".db_getsession("DB_anousu");
      $result4 = pg_exec($sql4);
       for($i4=0;$i4<pg_numrows($result4);$i4++){
         db_fieldsmemory($result4,$i4);
   ?>
  <tr>
    <td>
        <img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
      <input type="checkbox"  onclick="js_marcapai('pai_<?=$o41_orgao?>');" value="pai_<?=$o41_orgao?>_<?=$o41_unidade?>_<?=$o52_funcao?>_<?=$o53_subfuncao?>" name="pai_<?=$o41_orgao?>_<?=$o41_unidade?>_<?=$o52_funcao?>_<?=$o53_subfuncao?>" id="<?=$nivel=='4A'?'ultimo_'.$o53_subfuncao:'primeiro_'.$o53_subfuncao?>" >&nbsp;&nbsp;<?=$o53_subfuncao?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=$o53_descr?></td>
  </tr>
  <?
   if ($nivel >= '5A'){
      $sql5 = "  select	distinct 
                 	o54_programa,
     	 		o54_descr 
                 from orcdotacao 
   	              inner join orcprograma on o58_programa = o54_programa
		                          and o54_anousu = ".db_getsession("DB_anousu")."
	         where o58_orgao 	= $o41_orgao 
	           and o58_unidade 	= $o41_unidade 
		   and o58_funcao       = $o52_funcao
		   and o58_subfuncao    = $o53_subfuncao
	           and o58_anousu = ".db_getsession("DB_anousu");
      $result5 = pg_exec($sql5);
       for($i5=0;$i5<pg_numrows($result5);$i5++){
         db_fieldsmemory($result5,$i5);
   ?>
  <tr>
    <td>
        <img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
      <input type="checkbox"  onclick="js_marcapai('pai_<?=$o41_orgao?>');" value="pai_<?=$o41_orgao?>_<?=$o41_unidade?>_<?=$o52_funcao?>_<?=$o53_subfuncao?>_<?=$o54_programa?>" name="pai_<?=$o41_orgao?>_<?=$o41_unidade?>_<?=$o52_funcao?>_<?=$o53_subfuncao?>_<?=$o54_programa?>" id="<?=$nivel=='5A'?'ultimo_'.$o54_programa:'primeiro_'.$o54_programa?>" >&nbsp;&nbsp;<?=$o54_programa?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=$o54_descr?></td>
  </tr>
  <?
   if ($nivel >= '6A'){
      $sql6 = "  select	distinct 
                 	o55_projativ,
     	 		o55_descr 
                 from orcdotacao 
   	              inner join orcprojativ on o58_projativ = o55_projativ
				and o55_anousu = ".db_getsession("DB_anousu")."
	         where o58_orgao 	= $o41_orgao 
	           and o58_unidade 	= $o41_unidade 
		   and o58_funcao       = $o52_funcao
		   and o58_subfuncao    = $o53_subfuncao
		   and o58_programa     = $o54_programa
	           and o58_anousu = ".db_getsession("DB_anousu");
      $result6 = pg_exec($sql6);
       for($i6=0;$i6<pg_numrows($result6);$i6++){
         db_fieldsmemory($result6,$i6);
   ?>
  <tr>
    <td>
        <img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
      <input type="checkbox"  onclick="js_marcapai('pai_<?=$o41_orgao?>');" value="pai_<?=$o41_orgao?>_<?=$o41_unidade?>_<?=$o52_funcao?>_<?=$o53_subfuncao?>_<?=$o54_programa?>_<?=$o55_projativ?>" name="pai_<?=$o41_orgao?>_<?=$o41_unidade?>_<?=$o52_funcao?>_<?=$o53_subfuncao?>_<?=$o54_programa?>_<?=$o55_projativ?>" id="<?=$nivel=='6A'?'ultimo_'.$o55_projativ:'primeiro_'.$o55_projativ?>">&nbsp;&nbsp;<?=$o55_projativ?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=$o55_descr?></td>
  </tr>
  <?
   if ($nivel >= '7A'){
      $sql7 = "  select	distinct
                        o56_codele,
                 	o56_elemento,
     	 		o56_descr 
                 from orcdotacao 
   	              inner join orcelemento on o58_codele = o56_codele and o56_anousu = o58_anousu
	         where o58_orgao 	= $o41_orgao 
	           and o58_unidade 	= $o41_unidade 
		   and o58_funcao       = $o52_funcao
		   and o58_subfuncao    = $o53_subfuncao
		   and o58_programa     = $o54_programa
		   and o58_projativ     = $o55_projativ
	           and o58_anousu = ".db_getsession("DB_anousu");
      $result7 = pg_exec($sql7);
       for($i7=0;$i7<pg_numrows($result7);$i7++){
         db_fieldsmemory($result7,$i7);
   ?>
  <tr>
    <td><img src="imagens/alinha.gif" width="15">
        <img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
      <input type="checkbox"  onclick="js_marcapai('pai_<?=$o41_orgao?>');" value="pai_<?=$o41_orgao?>_<?=$o41_unidade?>_<?=$o52_funcao?>_<?=$o53_subfuncao?>_<?=$o54_programa?>_<?=$o55_projativ?>_<?=$o56_elemento?>" name="pai_<?=$o41_orgao?>_<?=$o41_unidade?>_<?=$o52_funcao?>_<?=$o53_subfuncao?>_<?=$o54_programa?>_<?=$o55_projativ?>_<?=$o56_elemento?>" id="<?=$nivel=='7A'?'ultimo_'.$o56_elemento:'primeiro_'.$o56_elemento?>">&nbsp;&nbsp;<?=$o56_elemento?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=$o56_descr?></td>
  </tr>
  <?
   if ($nivel >= '8A'){
      $sql8 = "  select	distinct 
                 	o15_codigo,
     	 		o15_descr 
                 from orcdotacao 
   	              inner join orctiporec on o58_codigo = o15_codigo
	         where o58_orgao 	= $o41_orgao 
	           and o58_unidade 	= $o41_unidade 
		   and o58_funcao       = $o52_funcao
		   and o58_subfuncao    = $o53_subfuncao
		   and o58_programa     = $o54_programa
		   and o58_projativ     = $o55_projativ
		   and o58_codele       = $o56_codele
		   and o58_anousu = ".db_getsession("DB_anousu");
      $result8 = pg_exec($sql8);
       for($i8=0;$i8<pg_numrows($result8);$i8++){
         db_fieldsmemory($result8,$i8);
   ?>
  <tr>
    <td><img src="imagens/alinha.gif" width="15">
        <img src="imagens/alinha.gif" width="15">
        <img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
	<img src="imagens/alinha.gif" width="15">
      <input type="checkbox"  onclick="js_marcapai('pai_<?=$o41_orgao?>');" value="pai_<?=$o41_orgao?>_<?=$o41_unidade?>_<?=$o52_funcao?>_<?=$o53_subfuncao?>_<?=$o54_programa?>_<?=$o55_projativ?>_<?=$o56_elemento?>_<?=$o15_codigo?>" name="pai_<?=$o41_orgao?>_<?=$o41_unidade?>_<?=$o52_funcao?>_<?=$o53_subfuncao?>_<?=$o54_programa?>_<?=$o55_projativ?>_<?=$o56_elemento?>_<?=$o15_codigo?>" id="<?=$nivel=='8A'?'ultimo_'.$o15_codigo:'primeiro_'.$o15_codigo?>">&nbsp;&nbsp;<?=$o15_codigo?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=$o15_descr?></td>
  </tr>
<?
   }}

   }}

   }}

   }}

   }}

   }}

   }}
  
   }}
}
?>
<input type="hidden" value="<?=$nivel?>" name="nivel" id="nivel">
</table>
</form>
</body>
</html>