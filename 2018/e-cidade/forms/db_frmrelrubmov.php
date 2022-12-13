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

//MODULO: pessoal
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrelrubmov->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh45_descr");
$clrotulo->label("rh27_descr");
if(isset($db_opcaoal)){
    $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $rh46_seq = "";
     $rh46_rubric = "";
     $rh27_descr = "";
     $rh46_quantval = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh46_seq?>">
       <?=@$Lrh46_seq?>
    </td>
    <td> 
<?
db_input('rh46_seq',10,$Irh46_seq,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh46_codigo?>">
       <?
       db_ancora(@$Lrh46_codigo,"js_pesquisarh46_codigo(true);", 3);
       ?>
    </td>
    <td> 
<?
db_input('rh46_codigo',6,$Irh46_codigo,true,'text',3," onchange='js_pesquisarh46_codigo(false);'")
?>
       <?
db_input('rh45_descr',40,$Irh45_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh46_rubric?>">
       <?
       db_ancora(@$Lrh46_rubric, "js_pesquisarh46_rubric(true);", $db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh46_rubric',6,$Irh46_rubric,true,'text',$db_opcao,"onchange='js_pesquisarh46_rubric(false);'");
db_input('rh27_descr', 40, $Irh27_descr, true, 'text', 3, '');
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh46_quantval?>">
       <?=@$Lrh46_quantval?>
    </td>
    <td> 
<?
$arr_quantval = array('V'=>'Valor','Q'=>'Quantidade');
db_select("rh46_quantval",$arr_quantval,true,$db_opcao);
?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table width="90%">
  <tr>
    <td valign="top"  align="center" width="100%">  
    <?
   $dbwhere  = "";
   $dbwhere1 = "";
   $and = "";
   if ( isset($_GET['rh46_seq'] )){
     $rh46_codigo = $_GET['rh46_seq'];
   }
   if(isset($rh46_codigo) && trim($rh46_codigo) != ""){
     $dbwhere = "rh46_codigo = ".$rh46_codigo;
     $and = " and ";
   }
   if(isset($rh46_seq) && trim($rh46_seq) != ""){
     $dbwhere1.= $and." rh46_seq <> ".$rh46_seq;
   }

   $sql = $clrelrubmov->sql_query(null,"rh46_seq, rh46_codigo, rh46_rubric||'-'||rh27_descr as rh46_rubric,case when rh46_quantval='V' then 'Valor' else 'Quantidade' end as rh46_quantval","rh46_seq",$dbwhere);
	 $chavepri= array("rh46_seq"=>@$rh46_seq);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $sql;
	 $cliframe_alterar_excluir->campos  ="rh46_seq,rh46_rubric,rh46_quantval";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="300";
	 $cliframe_alterar_excluir->iframe_width ="100%";
   $trancaopcao = 1;
   if(isset($db_opcaoal)){
     $trancaopcao = 4;
   }
	 $cliframe_alterar_excluir->opcoes = $trancaopcao;
	 $cliframe_alterar_excluir->iframe_alterar_excluir(1);

   $tranca_button = false;
   if($db_opcao == 1){
     $result_quantos_inc = $clrelrubmov->sql_record($sql);
     if($clrelrubmov->numrows >= 10){
       $tranca_button = true;
     }
   }
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script>
function js_pesquisarh46_rubric(mostra){
  if(mostra==true){
    // js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricasnovo.php?funcao_js=parent.js_mostrarubricas1|rh27_rubric|rh27_descr|rh27_limdat|formula|rh27_obs&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
    js_OpenJanelaIframe('top.corpo.iframe_relrubmov','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarubricas1|rh27_rubric|rh27_descr&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true,'0');
  }else{
     if(document.form1.rh46_rubric.value != ''){
       js_completa_rubricas(document.form1.rh46_rubric);
       // js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricasnovo.php?pesquisa_chave='+document.form1.r90_rubric.value+'&funcao_js=parent.js_mostrarubricas&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
       js_OpenJanelaIframe('top.corpo.iframe_relrubmov','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.rh46_rubric.value+'&funcao_js=parent.js_mostrarubricas&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
     }else{
       document.form1.rh27_descr.value = '';
     }
  }
}
function js_mostrarubricas(chave,erro){
  document.form1.rh27_descr.value  = chave;
  if(erro==true){
    document.form1.rh46_rubric.value = '';
    document.form1.rh46_rubric.focus();
  }
}
function js_mostrarubricas1(chave1,chave2){
  document.form1.rh46_rubric.value = chave1;
  document.form1.rh27_descr.value  = chave2;
  db_iframe_rhrubricas.hide();
}
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisarh46_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_relrubmov','db_iframe_relrub','func_relrub.php?funcao_js=parent.js_mostrarelrub1|rh45_codigo|rh45_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.rh46_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_relrubmov','db_iframe_relrub','func_relrub.php?pesquisa_chave='+document.form1.rh46_codigo.value+'&funcao_js=parent.js_mostrarelrub','Pesquisa',false);
     }else{
       document.form1.rh45_descr.value = ''; 
     }
  }
}
function js_mostrarelrub(chave,erro){
  document.form1.rh45_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh46_codigo.focus(); 
    document.form1.rh46_codigo.value = ''; 
  }
}
function js_mostrarelrub1(chave1,chave2){
  document.form1.rh46_codigo.value = chave1;
  document.form1.rh45_descr.value = chave2;
  db_iframe_relrub.hide();
}
<?
if($tranca_button == true){
  echo "
        document.getElementById('db_opcao').disabled = true;
       ";
}
?>
</script>