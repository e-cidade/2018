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

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
if(isset($db_opcaoal)){
    $db_opcao=3;
      $db_botao=false;
}else{
  $db_botao=true;
}
if(isset($opcao) && $opcao=="alterar"){
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
    $db_opcao = 3;
    if(isset($db_opcaoal)){
	$db_opcao=33;
    }
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo)){
    //limpa campos
    }
    
} 
if(empty($trocando) && empty($excluir) && empty($alterar) && isset($opcao) && $opcao!="" && empty($db_opcaoal)){
  $result19=$cldb_permemp->sql_record($cldb_permemp->sql_query_file($db20_codperm));
  if($cldb_permemp->numrows>0){
    db_fieldsmemory($result19,0);
    if($db20_codele!=0){
      $result = $clorcelemento->sql_record($clorcelemento->sql_query_file(null,null,'o56_elemento',''," o56_codele ='$db20_codele' and o56_anousu=".db_getsession("DB_anousu")));
      db_fieldsmemory($result,0);  
    }  
  }  
}  
$cldb_permemp->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("id_usuario");
$clrotulo->label("nome");
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");

?>
<script>
 function js_atual(nome){
<?
  if(isset($opcao) && ($opcao=="alterar" || $opcao=="excluir")){
?>    
      var opcao = document.createElement("input");
      opcao.setAttribute("type","hidden");
      opcao.setAttribute("name","opcao");
      opcao.setAttribute("value",'alterar');
      document.form1.appendChild(opcao);
<?
  }
?>      
      var opcao = document.createElement("input");
      opcao.setAttribute("type","hidden");
      opcao.setAttribute("name","trocando");
      opcao.setAttribute("value",'trocando');
      document.form1.appendChild(opcao);

  //rotina para passar os selects a abaixo para o valor zero
  ordem = new Array("db20_orgao","db20_unidade","db20_funcao","db20_subfuncao","db20_programa","db20_projativ","o56_elemento","db20_codigo");
  for(i=(ordem.length-1); i>0; i--){
     if(ordem[i]==nome){
       break;
     }else{
       if(eval("document.form1."+ordem[i]+".options;")){
	 if(eval("document.form1."+ordem[i]+".options.length>'1';")){
	  eval("document.form1."+ordem[i]+".options[0].selected=true;");
	  eval("document.form1."+ordem[i]+"descr.options[0].selected=true;");
	 }else{
	   eval("document.form1."+ordem[i]+".options[0].value='0';");
	   eval("document.form1."+ordem[i]+".options[0].text='0';");
	  // eval("document.form1."+ordem[i]+"descr.options[0].value='0';");
	   //eval("document.form1."+ordem[i]+"descr.options[0].text='0';");
	 }
       }  	 
     }
  }


 
  document.form1.submit();

 } 
</script>
<form name="form1" method="post" action="">
<center>
<table border="0">
<?
if((isset($coddepto) && $coddepto!='')){
  $query="coddepto=$coddepto&descrdepto=$descrdepto";
?>  
  <tr>
    <td nowrap title="<?=@$Tcoddepto?>">
       <?=$Lcoddepto?>
    </td>
    <td> 
<?
db_input('coddepto',5,$Icoddepto,true,'text',3);
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'');
?>
    <td>
  </tr>
<?  
}else if(isset($id_usuario) && $id_usuario!='') {
   $query="id_usuario=$id_usuario&nome=".addslashes($nome);
?>
  <tr>
    <td nowrap title="<?=@$Tid_usuario?>">
       <?=$Lnome?>
    </td>
    <td> 
<?
db_input('id_usuario',5,$Iid_usuario,true,'text',3);
db_input('nome',40,$Inome,true,'text',3,'');
?>
    <td>
  </tr>
<?
}
?>
  <tr>
    <td>
<?
db_input('db20_codperm',5,$Idb20_codperm,true,'hidden',3);
if(empty($db20_anousu)){
  $db20_anosus=$anousu;
}
db_input('db20_anousu',5,$Idb20_anousu,true,'hidden',3);
?>
   </td>
  </tr>

  <tr>
  <td><?=$Ldb20_orgao?></td>
  <td>
  <?
//  echo $clorcorgao->sql_query(null,null,"o40_orgao as db20_orgao,o40_descr","o40_orgao","o40_anousu=".db_getsession("DB_anousu")." and o40_instit=".db_getsession("DB_instit"));
  $result = $clorcorgao->sql_record($clorcorgao->sql_query(null,null,"o40_orgao as db20_orgao,o40_descr","o40_orgao","o40_anousu=".db_getsession("DB_anousu")." and o40_instit=".db_getsession("DB_instit")));
  db_selectrecord("db20_orgao",$result,true,$db_opcao,"","","","0",$onchange=" js_atual('db20_orgao');");
  if($db_opcao==1){
  ?>
  <input name='incluir_todos' type="submit" value="Incluir todos">
 <?
   }
 ?> 
  </td>
  </tr>
  <tr>
  <td><?=$Ldb20_unidade?></td>
  <td>
  <?
  if(isset($db20_orgao) ){
    if($db20_orgao!='' && $db20_orgao!="0"){
      $result = $clorcunidade->sql_record($clorcunidade->sql_query(null,null,null,"o41_unidade as db20_unidade,o41_descr","o41_unidade","o41_anousu=".db_getsession("DB_anousu")."  and o41_orgao=$db20_orgao " ));
//      echo $clorcunidade->sql_query(null,null,null,"o41_unidade as db20_unidade,o41_descr","o41_unidade","o41_anousu=".db_getsession("DB_anousu")."  and o41_orgao=$db20_orgao " );
      db_selectrecord("db20_unidade",$result,true,$db_opcao,"","","","0",$onchange=" js_atual('db20_unidade');");
    }else{
      $db20_unidade="0"; 
      db_input("db20_unidade",6,0,true,"text",3);
      unset($db20_unidade);
    }       
  }else{
      $db20_unidade="0"; 
    db_input("db20_unidade",6,0,true,"hidden",0);
  }
  ?>
  </td>
  	</tr>

  <tr>
  <td><?=$Ldb20_funcao?></td>
  <td>
  <?
  $dbwhere = "";
  if(isset($db20_orgao) && $db20_orgao > 0 ){
    $dbwhere .= " and o58_orgao= $db20_orgao ";
  }
  if(isset($db20_unidade) && $db20_unidade > 0 ){
    $dbwhere .= " and o58_unidade = $db20_unidade ";
  }
  $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null,"distinct o52_funcao as db20_funcao,o52_descr","o52_funcao","o58_anousu=".db_getsession("DB_anousu")." and o58_instit=".db_getsession("DB_instit")." $dbwhere"));
  db_selectrecord("db20_funcao",$result,true,$db_opcao,"","","","0",$onchange=" js_atual('db20_funcao');");
  ?>
  </td>
  </tr>
  <tr>
  <td><?=$Ldb20_subfuncao?></td>
  <td>
  <?
  $dbwhere = "";
  if(isset($db20_orgao) && $db20_orgao > 0 ){
    $dbwhere .= " and o58_orgao= $db20_orgao ";
  }
  if(isset($db20_unidade) && $db20_unidade > 0 ){
    $dbwhere .= " and o58_unidade = $db20_unidade ";
  }

  if(isset($db20_funcao) && $db20_funcao > 0 ){
    $dbwhere .= " and  o58_funcao = $db20_funcao ";
  }

  $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null,"distinct o53_subfuncao as db20_subfuncao,o53_descr","o53_subfuncao","o58_anousu=".db_getsession("DB_anousu")." and o58_instit=".db_getsession("DB_instit")." $dbwhere"));
  db_selectrecord("db20_subfuncao",$result,true,$db_opcao,"","","","0",$onchange=" js_atual('db20_subfuncao');");
  ?>
  </td>
  </tr>

  <tr>
  <td><?=$Ldb20_programa?></td>
  <td>
  <?
  $dbwhere = "";
  if(isset($db20_orgao) && $db20_orgao > 0 ){
    $dbwhere .= "  and o58_orgao= $db20_orgao ";
  }
  if(isset($db20_unidade) && $db20_unidade > 0 ){
    $dbwhere .= " and o58_unidade = $db20_unidade ";
  }
  if(isset($db20_funcao) && $db20_funcao > 0 ){
    if($dbwhere!="")
    $dbwhere .= "  and o58_funcao = $db20_funcao ";
  }

  if(isset($db20_subfuncao) && $db20_subfuncao > 0 ){
    $dbwhere .= "  and o58_subfuncao = $db20_subfuncao ";
  }

  $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null,"distinct o54_programa as db20_programa,o54_descr","o54_programa","o58_anousu=".db_getsession("DB_anousu")." and o58_instit=".db_getsession("DB_instit")." $dbwhere"));
  db_selectrecord("db20_programa",$result,true,$db_opcao,"","","","0",$onchange=" js_atual('db20_programa');");
  ?>
  </td>
  </tr>

  <tr>
  <td><?=$Ldb20_projativ?></td>
  <td>
  <?
  $dbwhere = "";
  if(isset($db20_orgao) && $db20_orgao > 0 ){
    $dbwhere .= "  and o58_orgao= $db20_orgao ";
  }
  if(isset($db20_unidade) && $db20_unidade > 0 ){
    $dbwhere .= " and o58_unidade = $db20_unidade ";
  }
  if(isset($db20_funcao) && $db20_funcao > 0 ){
    $dbwhere .= "  and o58_funcao = $db20_funcao ";
  }
  if(isset($db20_subfuncao) && $db20_subfuncao > 0 ){
    $dbwhere .= "  and o58_subfuncao = $db20_subfuncao ";
  }
  if(isset($db20_programa) && $db20_programa > 0 ){
    $dbwhere .= "  and o58_programa = $db20_programa ";
  }
  $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null,"distinct o55_projativ as db20_projativ,o55_descr","o55_projativ","o58_anousu=".db_getsession("DB_anousu")." and o58_instit=".db_getsession("DB_instit")." $dbwhere"));
  db_selectrecord("db20_projativ",$result,true,$db_opcao,"","","","0",$onchange=" js_atual('db20_projativ');");
  ?>
  </td>
  </tr>

  <tr>
  <td><?=$Ldb20_codele?></td>
  <td>
  <?
  $dbwhere = "";
  if(isset($db20_orgao) && $db20_orgao > 0 ){
    $dbwhere .= " and o58_orgao= $db20_orgao ";
  }
  if(isset($db20_unidade) && $db20_unidade > 0 ){
    $dbwhere .= " and o58_unidade = $db20_unidade ";
  }
  if(isset($db20_funcao) && $db20_funcao > 0 ){
    $dbwhere .= " and o58_funcao = $db20_funcao ";
  }
  if(isset($db20_subfuncao) && $db20_subfuncao > 0 ){
    $dbwhere .= " and  o58_subfuncao = $db20_subfuncao ";
  }
  if(isset($db20_programa) && $db20_programa > 0 ){
    $dbwhere .= " and o58_programa = $db20_programa ";
  }
  if(isset($db20_projativ) && $db20_projativ > 0 ){
    $dbwhere .= " and  o58_projativ = $db20_projativ ";
  }
  $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null,"distinct o56_elemento,o56_descr","o56_elemento","o58_anousu=".db_getsession("DB_anousu")." and o58_instit=".db_getsession("DB_instit")." $dbwhere"));
  db_selectrecord("o56_elemento",$result,true,$db_opcao,"","","","0","js_atual('o56_elemento'); ");
  ?>
  </td>
  </tr>

  <tr>
  <td><?=$Ldb20_codigo?></td>
  <td>
  <?
  $dbwhere = "";
  if(isset($db20_orgao) && $db20_orgao > 0 ){
    $dbwhere .= " and o58_orgao= $db20_orgao ";
  }
  if(isset($db20_unidade) && $db20_unidade > 0 ){
    $dbwhere .= " and o58_unidade = $db20_unidade ";
  }
  if(isset($db20_funcao) && $db20_funcao > 0 ){
    $dbwhere .= " and o58_funcao = $db20_funcao ";
  }
  if(isset($db20_subfuncao) && $db20_subfuncao > 0 ){
    $dbwhere .= " and o58_subfuncao = $db20_subfuncao ";
  }
  if(isset($db20_programa) && $db20_programa > 0 ){
    $dbwhere .= " and o58_programa = $db20_programa ";
  }
  if(isset($db20_projativ) && $db20_projativ > 0 ){
    $dbwhere .= " and o58_projativ = $db20_projativ ";
  }
  if(isset($db20_elemento) && $db20_elemento > 0 ){
    $dbwhere .= " and o56_elemento = $o56_elemento ";
  }
  $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null,"distinct o15_codigo as db20_codigo,o15_descr","o15_codigo","o58_anousu=".db_getsession("DB_anousu")." and o58_instit=".db_getsession("DB_instit")." $dbwhere"));
  db_selectrecord("db20_codigo",$result,true,$db_opcao,"","","","0"," js_atual('db20_codigo');");
  ?>
  </td>
  </tr>
  
  <tr>
  <td><?=$Ldb20_tipoperm ?></td>
  <td>
   <?    
      $matriz = array("M"=>"Manutencao","C"=>"Consulta");
      db_select("db20_tipoperm",$matriz,true,$db_opcao);
   ?>
  </td>
  </tr>

  <tr>
    <td colspan='2' align='center'>
	<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
	<input name="limpa" type="button" onclick='js_limpa();'  value="Cancelar">
	<input name="limpa" type="button" onclick='js_voltar();'  value="Retornar">
    </td>  	
  </tr>
  </table>
  <table >
    <tr>
      <td  >  
   <?
if(isset($coddepto) && $coddepto!=''){
  $dbwhere="  db22_coddepto = $coddepto ";  
}else if(isset($id_usuario) && $id_usuario!=''){
  $dbwhere="  db21_id_usuario = $id_usuario ";  
}
  $dbwhere .= "and db20_anousu=".db_getsession("DB_anousu");
  
    $chavepri= array("db20_anousu"=>$anousu,"db20_codperm"=>@$db20_codperm);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->sql     = $cldb_permemp->sql_query_origem(null,"db20_codperm,db20_anousu,db20_orgao,db20_unidade,db20_funcao,db20_subfuncao,db20_programa,db20_projativ,db20_codele,db20_codigo",'db20_orgao',"$dbwhere");
    $cliframe_alterar_excluir->campos  ="db20_anousu,db20_orgao,db20_unidade,db20_funcao,db20_subfuncao,db20_programa,db20_projativ,db20_codele,db20_codigo";
   // $cliframe_alterar_excluir->legenda="";
    $cliframe_alterar_excluir->iframe_height ="180";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
   ?>
   </td>
  </tr>
  </table>
  </center>
</form>
<script>
function js_voltar(){
  location.href="con1_db_permempusu001.php";
}  
function js_limpa(){
 location.href='con1_db_permempusu001.php?<?=$query?>'; 
}
</script>