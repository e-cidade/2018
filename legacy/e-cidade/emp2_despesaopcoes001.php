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
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcelemento_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_SERVER_VARS);

// recebe a variavel db_input_retorno com o nome do objeto que será atualizado com os dados selecionados

if (isset($instit) && $instit!=""){
  // recebeu instituição
  $array_instit = split(',',$instit);
} else {
  $array_instit[0]= db_getsession("DB_instit");
  $instit= db_getsession("DB_instit");
}  
$sel_orgaos = " o58_instit in ($instit) ";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>

function js_atualiza_instit(){
  ob = document.forminstit.elements;
  lista='';
  virgula='';
  for(i=0;i< ob.length;i++){
    if(ob[i].type=='checkbox'){
      if(ob[i].checked==true){
        subname = ob[i].name.substr(7,ob[i].name.length);
        lista = subname+virgula+lista;
	virgula=',';
      }	
    }	
  }
  location.href='emp2_despesaopcoes001.php?instit='+lista;
  // alert(lista); 
}  
function js_marca_todos(tipoacao){
  if(document.getElementById('div_orgao').style.visibility == 'visible'){
    objetos = document.formorgao.elements;
    camada  = 'orgao';
  }
  if(document.getElementById('div_unidade').style.visibility == 'visible'){
    objetos = document.formunidade.elements;
     camada  = 'unidade';
  }
  if(document.getElementById('div_funcao').style.visibility == 'visible'){
    objetos = document.formfuncao.elements;
      camada  = 'funcao';
  }
  if(document.getElementById('div_subfuncao').style.visibility == 'visible'){
    objetos = document.formsubfuncao.elements;
     camada  = 'subfuncao';
  }
  if(document.getElementById('div_programa').style.visibility == 'visible'){
    objetos = document.formprograma.elements;
     camada  = 'programa';
  }
  if(document.getElementById('div_projativ').style.visibility == 'visible'){
    objetos = document.formprojativ.elements;
     camada  = 'projativ';
  }
  if(document.getElementById('div_elemento').style.visibility == 'visible'){
    objetos = document.formele.elements;
     camada  = 'elemento';
  }
  if(document.getElementById('div_recurso').style.visibility == 'visible'){
    objetos = document.formrecurso.elements;
    camada  = 'recurso';
  }
  if(document.getElementById('div_depart').style.visibility == 'visible'){
    objetos = document.formdepart.elements;
    camada  = 'depart';
  }
  if(document.getElementById('div_usuario').style.visibility == 'visible'){
    objetos = document.formusuario.elements;
    camada  = 'usuario';
  }
  if(document.getElementById('div_instit').style.visibility == 'visible'){
    objetos = document.forminstit.elements;
    camada  = 'instit';
  }


  js_troca(objetos,tipoacao);

  //for(i=0;i<objetos.length;i++){
  //  js_atualiza_variavel_retorno(camada,objetos[i]);
  //}

}
function js_marca_geral(tipoacao){

  objetos = document.formorgao.elements;
  js_troca(objetos,tipoacao);
  objetos = document.formunidade.elements;
  js_troca(objetos,tipoacao);
  objetos = document.formfuncao.elements;
  js_troca(objetos,tipoacao);
  objetos = document.formsubfuncao.elements;
  js_troca(objetos,tipoacao);
  objetos = document.formprograma.elements;
  js_troca(objetos,tipoacao);
  objetos = document.formprojativ.elements;
  js_troca(objetos,tipoacao);
  objetos = document.formele.elements;
  js_troca(objetos,tipoacao);
  objetos = document.formrecurso.elements;
  js_troca(objetos,tipoacao);
  objetos = document.formdepart.elements;
  js_troca(objetos,tipoacao);
  objetos = document.formusuario.elements;
  js_troca(objetos,tipoacao);
  objetos = document.forminstit.elements;
  js_troca(objetos,tipoacao);

}

function js_troca(objetos,tipoacao){
  for(i=0;i<objetos.length;i++){
    objetos[i].checked = tipoacao;
  }
}

function js_marca_orgaos(tipo){
  if(tipo=='orgao')
    document.getElementById('div_orgao').style.visibility = document.getElementById('div_orgao').style.visibility='visible'; 
  else
    document.getElementById('div_orgao').style.visibility = document.getElementById('div_orgao').style.visibility='hidden'; 
  if(tipo=='unidade')
     document.getElementById('div_unidade').style.visibility = document.getElementById('div_unidade').style.visibility='visible'; 
  else 
     document.getElementById('div_unidade').style.visibility = document.getElementById('div_unidade').style.visibility='hidden'; 
  if(tipo=='funcao')
     document.getElementById('div_funcao').style.visibility = document.getElementById('div_funcao').style.visibility='visible'; 
  else
     document.getElementById('div_funcao').style.visibility = document.getElementById('div_funcao').style.visibility='hidden'; 
  if(tipo=='subfuncao')    
    document.getElementById('div_subfuncao').style.visibility = document.getElementById('div_subfuncao').style.visibility='visible'; 
  else  
    document.getElementById('div_subfuncao').style.visibility = document.getElementById('div_subfuncao').style.visibility='hidden'; 
  if(tipo=='programa')    
    document.getElementById('div_programa').style.visibility = document.getElementById('div_programa').style.visibility='visible'; 
  else
    document.getElementById('div_programa').style.visibility = document.getElementById('div_programa').style.visibility='hidden'; 
  if(tipo=='projativ')    
    document.getElementById('div_projativ').style.visibility = document.getElementById('div_projativ').style.visibility='visible'; 
  else
    document.getElementById('div_projativ').style.visibility = document.getElementById('div_projativ').style.visibility='hidden'; 
  if(tipo=='elemento')    
    document.getElementById('div_elemento').style.visibility = document.getElementById('div_elemento').style.visibility='visible'; 
  else
    document.getElementById('div_elemento').style.visibility = document.getElementById('div_elemento').style.visibility='hidden'; 
  if(tipo=='recurso')    
    document.getElementById('div_recurso').style.visibility = document.getElementById('div_recurso').style.visibility='visible'; 
  else
    document.getElementById('div_recurso').style.visibility = document.getElementById('div_recurso').style.visibility='hidden'; 
  if(tipo=='instit')    
    document.getElementById('div_instit').style.visibility = document.getElementById('div_instit').style.visibility='visible'; 
  else
    document.getElementById('div_instit').style.visibility = document.getElementById('div_instit').style.visibility='hidden'; 
  if(tipo=='depart')    
    document.getElementById('div_depart').style.visibility = document.getElementById('div_depart').style.visibility='visible'; 
  else
    document.getElementById('div_depart').style.visibility = document.getElementById('div_depart').style.visibility='hidden';
  if(tipo=='usuario')    
    document.getElementById('div_usuario').style.visibility = document.getElementById('div_usuario').style.visibility='visible'; 
  else
    document.getElementById('div_usuario').style.visibility = document.getElementById('div_usuario').style.visibility='hidden';

  
}
function js_despesas(desp,valor){
  elementodesp = document.formele.elements;
  for(i=0;i<elementodesp.length;i++){
    if(elementodesp[i].name.substr(0,3)==desp){
      if(valor){
        elementodesp[i].checked = true;
      }else{
        elementodesp[i].checked = false;
      }
    }
  }
}

function js_atualiza_variavel_retorno(div_camada,objeto){
  if(objeto.checked == true ){
    document.form1.db_input_retorno.value = document.form1.db_input_retorno.value + div_camada + '_' + objeto.value + '-';
  }else{
    var retira = document.form1.db_input_retorno.value.split('-');
    document.form1.db_input_retorno.value = null ;
    for(i=0;i<retira.length;i++){
      if( retira[i] != div_camada + '_' + objeto.value ){
	if(retira[i]!='')
          document.form1.db_input_retorno.value = document.form1.db_input_retorno.value + retira[i] + '-';
      }
    }
  }
}

function js_retorno(div_camada){
  obj = div_cama.elements;
  for(i=0;i<sizeof(obj);i++){
    if(obj[i].checked == true){
      document.form1.db_input_retorno.value = document.form1.db_input_retorno.value + div_camada + '_' + obj[i].value + '-'
    }
  }
}

function js_atualiza_variavel_retorno(objeto){
  var camada = new Array('instit','orgao','unidade','funcao','subfuncao','programa','projativ','ele','recurso','depart','usuario');    
  selecionados = '';
  for(i=0;i<camada.length;i++){
    qcamada = eval('document.form'+camada[i]+'.elements');
    textrec = "";
    virgula = "";
    for(ii=0;ii<qcamada.length;ii++){
      if(qcamada[ii].checked==true){
        textrec += virgula + qcamada[ii].value;
        virgula  = ",";
      }
    }
    eval('parent.iframe_g1.document.form1.' + camada[i] + '.value = "' + textrec + '";');
  }
}

</script>


</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="1"  align="center" cellspacing="1" bgcolor="#CCCCCC">
 <tr> 
    <td valign="middle">
      <form name="form1" method="post">
      <input id="marca" type="button" value="Marca Todos" onclick="js_marca_todos(true);return false" >
      <input id="desmarca" type="button" value="Desmarca Todos" onclick="js_marca_todos(false);return false" >
      &nbsp&nbsp&nbsp&nbsp&nbsp
      <input id="geralmarca" type="button" value="Marca Geral" onclick="js_marca_geral(true);return false" >
      <input id="geraldesmarca" type="button" value="Desmarca Geral" onclick="js_marca_geral(false);return false" >
       &nbsp&nbsp&nbsp&nbsp&nbsp
      <input id="instituicao" type="button" value="Instituições" onclick="js_marca_orgaos('instit');return false" >

      </form>
    </td>

  </tr>
 <tr> 
    <td colspan="3" valign="middle">
      <input id="orgaos" type="button" value="Orgãos" onclick="js_marca_orgaos('orgao');return false" >
      <input id="unidade" type="button" value="Unidade" onclick="js_marca_orgaos('unidade');return false" >
      <input id="funcao" type="button" value="Função" onclick="js_marca_orgaos('funcao');return false" >
      <input id="subfuncao" type="button" value="Sub-Função" onclick="js_marca_orgaos('subfuncao');return false" >
      <input id="programa" type="button" value="Programa" onclick="js_marca_orgaos('programa');return false" >
      <input id="prjativ" type="button" value="Proj/Atividade" onclick="js_marca_orgaos('projativ');return false" >
      <input id="elemento" type="button" value="Elemento" onclick="js_marca_orgaos('elemento');return false" >
      <input id="recurso" type="button" value="Recurso" onclick="js_marca_orgaos('recurso');return false" >
      <input id="depart" type="button" value="Departamento" onclick="js_marca_orgaos('depart');return false" >
      <input id="usuario" type="button" value="Usuários" onclick="js_marca_orgaos('usuario');return false" >
    </td>
  </tr>

  <?
  $clorcdotacao = new cl_orcdotacao;  
  $clorcelemento = new cl_orcelemento;  
  ?>
  </table>
  <table>
  <tr>
  <td align='center'>
  
  <form name="formorgao" action="post">
  <div id='div_orgao' style='position:absolute; visibility:hidden' >
   <table border=1 cellspacing=0 width="100%">
     <?
     // echo($clorcdotacao->sql_query(null,null," distinct o58_orgao,orcorgao.o40_descr","o58_orgao"," o58_anousu = ".db_getsession("DB_anousu")." and $sel_orgaos "));
     $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null," distinct o58_orgao,orcorgao.o40_descr","o58_orgao"," o58_anousu = ".db_getsession("DB_anousu")." and $sel_orgaos "));
     for($i=0;$i<$clorcdotacao->numrows;$i++){
       db_fieldsmemory($result,$i);
       ?>
       <tr>
       <td align='right'> <input type='checkbox' name='orgao_<?=$o58_orgao?>' value='<?=$o58_orgao?>' > </td>
       <td align='right'> <?=$o58_orgao?> </td>
       <td align='left' ><strong><?=$o40_descr?></strong></td>
       <tr>
       <?
     }
     ?>
   </table>
  </div>
  </form>
  <form name="formunidade" action="post">
  <div id='div_unidade' style='position:absolute; visibility:hidden' >
   <table border=1 cellspacing=0 >
    <?
     $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null," distinct o58_orgao,orcorgao.o40_descr,o58_unidade,orcunidade.o41_descr","o58_orgao,o58_unidade"," o58_anousu = ".db_getsession("DB_anousu")." and $sel_orgaos "));
     $orgaoant = "";
     for($i=0;$i<$clorcdotacao->numrows;$i++){
       db_fieldsmemory($result,$i);
       if($o58_orgao != $orgaoant){
       	 if($orgaoant!=""){
       	   echo "<tr><td colspan='3'>&nbsp;</td></tr>";
       	 }
       	 echo "<tr>
                 <td colspan='2' align='right'><b>$o58_orgao</b></td>
                 <td align='left'><b>$o40_descr</b></td>
               </tr>";
       	 $orgaoant = $o58_orgao;
       }
       ?>
       <tr>
       <td ><input type='checkbox' name='unidade_<?=$o58_orgao?>_<?=$o58_unidade?>' value='<?=$o58_orgao?>_<?=$o58_unidade?>'></td>
       <!--
       <td  align='right'> <?=$o58_orgao?> </td>
       <td align='left' ><strong><?=$o40_descr?></strong></td>
       -->
       <td  align='right'> <?=$o58_unidade?> </td>
       <td align='left' ><strong><?=$o41_descr?></strong></td>
       <tr>
       <?
     }
     ?>
   </table>
  </div>
  </form>
  <form name="formfuncao" action="post">
  <div id='div_funcao' style='position:absolute; visibility:hidden' >
   <table border=1 cellspacing=0 >
    <?
     $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null," distinct o58_funcao,orcfuncao.o52_descr","o58_funcao"," o58_anousu = ".db_getsession("DB_anousu")." and $sel_orgaos "));
     for($i=0;$i<$clorcdotacao->numrows;$i++){
       db_fieldsmemory($result,$i);
       ?>
       <tr>
       <td > <input type='checkbox' name='funcao_<?=$o58_funcao?>' value='<?=$o58_funcao?>'></td>
       <td  align='right'> <?=$o58_funcao?> </td>
       <td align='left' ><strong><?=$o52_descr?></strong></td>
       <tr>
       <?
     }
     ?>
   </table>
  </div>
  </form>
  <form name="formsubfuncao" action="post">
  <div id='div_subfuncao' style='position:absolute; visibility:hidden' >
   <table border=1 cellspacing=0 >
    <?
     $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null," distinct o58_subfuncao,orcsubfuncao.o53_descr","o58_subfuncao"," o58_anousu = ".db_getsession("DB_anousu")." and $sel_orgaos "));
     for($i=0;$i<$clorcdotacao->numrows;$i++){
       db_fieldsmemory($result,$i);
       ?>
       <tr>
       <td > <input type='checkbox' name='subfuncao_<?=$o58_subfuncao?>' value='<?=$o58_subfuncao?>'></td>
       <td  align='right'> <?=$o58_subfuncao?> </td>
       <td align='left' ><strong><?=$o53_descr?></strong></td>
       <tr>
       <?
     }
     ?>
   </table>
  </div>
  </form>
  <form name="formprograma" action="post">
  <div id='div_programa' style='position:absolute; visibility:hidden' >
   <table border=1 cellspacing=0 >
    <?
     $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null," distinct o58_programa,orcprograma.o54_descr","o58_programa"," o58_anousu = ".db_getsession("DB_anousu")." and $sel_orgaos "));
     for($i=0;$i<$clorcdotacao->numrows;$i++){
       db_fieldsmemory($result,$i);
       ?>
       <tr>
       <td > <input type='checkbox' name='programa_<?=$o58_programa?>' value='<?=$o58_programa?>'></td>
       <td  align='right'> <?=$o58_programa?> </td>
       <td align='left' ><strong><?=$o54_descr?></strong></td>
       <tr>
       <?
     }
     ?>
   </table>
  </div>
  </form>
  <form name="formprojativ" action="post">
  <div id='div_projativ' style='position:absolute; visibility:hidden' >
   <table border=1 cellspacing=0 >
    <?
     $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null," distinct o58_projativ,orcprojativ.o55_descr","o58_projativ"," o58_anousu = ".db_getsession("DB_anousu")." and $sel_orgaos "));
     for($i=0;$i<$clorcdotacao->numrows;$i++){
       db_fieldsmemory($result,$i);
       ?>
       <tr>
       <td > <input type='checkbox' name='projativ_<?=$o58_projativ?>' value='<?=$o58_projativ?>'></td>
       <td  align='right'> <?=$o58_projativ?> </td>
       <td align='left' ><strong><?=$o55_descr?></strong></td>
       <tr>
       <?
     }
     ?>
   </table>
  </div>
  </form>
  <form name="formele" action="post">
  <div id='div_elemento' style='position:absolute; visibility:hidden' >
   <table border=1 cellspacing=0 >
    <?
     $ind = 0;
     $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null," distinct o56_codele,o56_elemento,orcelemento.o56_descr","o56_elemento"," o58_anousu = ".db_getsession("DB_anousu")." and $sel_orgaos "));
     $tipodesp = array() ;
     $tipod = "";
     for($i=0;$i<$clorcdotacao->numrows;$i++){
       db_fieldsmemory($result,$i);
       $ele = substr($o56_elemento,0,3);
       $tipodd = " o56_elemento = '$ele'";
       if(array_search($ele,$tipodesp)==0){
	 $srec = $clorcelemento->sql_record($clorcelemento->sql_query_file(null,null,'o56_descr',null," o56_anousu=".db_getsession("DB_anousu")." and o56_elemento = '".$ele."0000000000' order by o56_elemento limit 1 "));
	 if($clorcelemento->numrows!=0){
           db_fieldsmemory($srec,0);
	   $tipodesp["$ele"] = substr($o56_descr,0,20);
	 }
       }
       $tipod = " and ";
     }
     for($i=0;$i<$clorcdotacao->numrows;$i++){
       db_fieldsmemory($result,$i);
       ?>
       <tr>
       <td > <input type='checkbox' name='<?=$o56_elemento?>_elemento_<?=$o56_codele?>' value='<?=$o56_codele?>'></td>
       <td  align='right'> <?=$o56_elemento?> </td>
       <td align='left' ><strong><?=$o56_descr?></strong></td>
       <?
       if($ind<sizeof($tipodesp)){
         echo "<td align='left' ><input name='' type='checkbox' onclick='js_despesas(\"".substr(key($tipodesp),0,3)."\",this.checked)' value='0' >".$tipodesp[key($tipodesp)]."</td>";
	 $ind ++;
	 next($tipodesp);
       }else
         echo "<td align='left' >&nbsp</td>";
       ?>

       <tr>
       <?
     }
     ?>
   </table>
  </div>
  </form>
  <form name="formrecurso" action="post">
  <div id='div_recurso' style='position:absolute; visibility:hidden' >
   <table border=1 cellspacing=0 >
    <?
     $dbwhere  = " o15_datalimite is null or o15_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."'";
     $sQuery   = " o58_anousu = ".db_getsession("DB_anousu")." and {$sel_orgaos} and {$dbwhere} ";
     $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null," distinct o58_codigo,orctiporec.o15_descr","o58_codigo",$sQuery));
     
     for($i=0;$i<$clorcdotacao->numrows;$i++){
       db_fieldsmemory($result,$i);
       ?>
       <tr>
       <td > <input type='checkbox' name='recurso_<?=$o58_codigo?>' value='<?=$o58_codigo?>'></td>
       <td  align='right'> <?=$o58_codigo?> </td>
       <td align='left' ><strong><?=$o15_descr?></strong></td>
       <tr>
       <?
     }
     ?>
   </table>
  </div>
  </form>

  <form name="formdepart" action="post">
  <div id='div_depart' style='position:absolute; visibility:hidden' >
   <table border=1 cellspacing=0 >
    <?
     //echo ($clorcdotacao->sql_query_autoriza(null,null," distinct coddepto,descrdepto","coddepto"," o58_anousu = ".db_getsession("DB_anousu")." and $sel_orgaos "));
     $result = $clorcdotacao->sql_record($clorcdotacao->sql_query_autoriza(null,null," distinct db_depart.coddepto,db_depart.descrdepto","db_depart.coddepto"," o58_anousu = ".db_getsession("DB_anousu")." and $sel_orgaos "));
     for($i=0;$i<$clorcdotacao->numrows;$i++){
       db_fieldsmemory($result,$i);
       ?>
       <tr>
       <td > <input type='checkbox' name='depart_<?=$coddepto?>' value='<?=$coddepto?>'></td>
       <td  align='right'> <?=$coddepto?> </td>
       <td align='left' ><strong><?=$descrdepto?></strong></td>
       <tr>
       <?
     }
     //echo $clorcdotacao->numrows;
     ?>
   </table>
  </div>
  </form>

  <form name="formusuario" action="post">
  <div id='div_usuario' style='position:absolute; visibility:hidden' >
   <table border=1 cellspacing=0 >
    <?
     $result = $clorcdotacao->sql_record($clorcdotacao->sql_query_autoriza(null,null," distinct db_usuarios.id_usuario,db_usuarios.nome","db_usuarios.id_usuario"," o58_anousu = ".db_getsession("DB_anousu")." and $sel_orgaos "));
     for($i=0;$i<$clorcdotacao->numrows;$i++){
       db_fieldsmemory($result,$i);
       ?>
       <tr>
       <td > <input type='checkbox' name='usuario_<?=$id_usuario?>' value='<?=$id_usuario?>'></td>
       <td  align='right'> <?=$id_usuario?> </td>
       <td align='left' ><strong><?=$nome?></strong></td>
       <tr>
       <?
     }
     // echo $clorcdotacao->numrows;
     ?>
   </table>
  </div>
  </form>

  <form name="forminstit" action="post">
  <div id='div_instit' style='position:absolute; visibility:hidden' >
   <table border=1 cellspacing=0 align="center">
    <?
     $result = $clorcdotacao->sql_record("select codigo, nomeinst,prefeitura from db_config order by codigo"); 
     $todas=false;
     for($i=0;$i<$clorcdotacao->numrows;$i++){
       db_fieldsmemory($result,$i);
       if($prefeitura=='t' && $codigo == db_getsession("DB_instit")){
	 $todas = true;
       }
     }
     for($i=0;$i<$clorcdotacao->numrows;$i++){
       db_fieldsmemory($result,$i);
       if($todas == true || ( $todas == false and $codigo == db_getsession("DB_instit") ) ){
	 ?>
	 <tr>
	 <td><input type='checkbox' name='instit_<?=$codigo ?>' value='<?=$codigo?>' 
	       <? if (in_array($codigo,$array_instit))
		    echo "checked"
	       ?>
	       onchange="js_atualiza_instit(); ";></td>
	 <td align='right'><?=$codigo?> </td>
	 <td align='left' ><strong><?=$nomeinst ?></strong></td>
	 <tr>
	 <?
       }
     }
     ?>
   </table>
  </div>
  </form>






  </td>
  </tr>
  </table>
 
  <?
?>
</table>
</body>
</html>