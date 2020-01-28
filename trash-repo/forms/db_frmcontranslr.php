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

//MODULO: contabilidade
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clcontranslr->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c46_codhist");
$clrotulo->label("c61_reduz");
$clrotulo->label("e54_codcom");
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
    if(isset($novo) || isset($alterar) ||   isset($excluir_geral)||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $c47_seqtranslr = "";
     $c47_debito = "";
     $c60_descr = "";
     $c60_descr_credito = "";
     $c47_credito = "";
     $c47_obs = "";
     $c47_ref = "";
     $c47_anousu = "";
     $c47_instit=db_getsession("DB_instit");
     $nomeinst='';
     $c47_compara='0';
     $c47_tiporesto='';
     $cod = 0;
   }
} 
?>
<script>
function js_verifica(){
  credito = document.form1.c47_credito.value;
  debito  = document.form1.c47_debito.value;
  if(credito=="" && debito==""){
    alert("Informe o lançamento de credito ou de debito!");
    return false;
  }
  if(credito==""){
   document.form1.c47_credito.value='0';
  }
  if(debito==""){
   document.form1.c47_debito.value='0';
  }
  return true;
}
function js_troca(codigo){
  if(codigo == 0 ){
    document.form1.cod.value = codigo;
    document.form1.submit();
  }else{
    if( document.form1.cod.value == 0){
      document.form1.cod.value = codigo;
      document.form1.submit();
    }
  }
}
</script>
<form name="form1" method="post" action="">
<table border="0" cellpadding='0' cellspacing='0' width='100%' height="100%">
  <tr>
    <td align=right nowrap title="<?=@$Tc47_seqtranslan?>">
       <?=@$Lc47_seqtranslan ?>
       <?db_input('cod',8,0,true,'hidden',1);?>

    </td>
    <td> 
<?
db_input('c47_seqtranslan',8,$Ic47_seqtranslan,true,'text',3)
?>
       <?=@$Lc47_seqtranslr?>
<?
db_input('c47_seqtranslr',8,$Ic47_seqtranslr,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td align=right nowrap title="<?=@$Tc47_anousu?>">
       <?=@$Lc47_anousu?>
    </td>
    <td> 
<?
if(empty($c47_anousu)){
  $c47_anousu=db_getsession("DB_anousu");
} 
db_input('c47_anousu',8,$Ic47_anousu,true,'text',$db_opcao);
?>
    </td>
  </tr>
  <tr>
    <td align=right nowrap title="<?=@$Tc47_debito?>">
       <?
       db_ancora(@$Lc47_debito,"js_pesquisac47_debito(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c47_debito',6,$Ic47_debito,true,'text',$db_opcao," onchange='js_pesquisac47_debito(false);'");
db_input('c60_descr',50,$Ic61_reduz,true,'text',3,"c60_descr_debito");
       ?>
    </td>
  </tr>
  <tr>
    <td align=right nowrap title="<?=@$Tc47_credito?>">
       <?
       db_ancora(@$Lc47_credito,"js_pesquisac47_credito(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c47_credito',6,$Ic47_credito,true,'text',$db_opcao," onchange='js_pesquisac47_credito(false);'");
db_input('c60_descr',50,$Ic61_reduz,true,'text',3,'',"c60_descr_credito");
?>
    </td>
  </tr>
  <tr>
    <td align=right nowrap title="<?=@$Tc47_instit?>">
       <?=$Lc47_instit?>
    </td>
    <td> 
<?
if(isset($c47_instit) && $c47_instit==''){
  $nomeinst='';
}
  $result=$cldb_config->sql_record($cldb_config->sql_query_file(null,"codigo,nomeinst",""," codigo = " . db_getsession("DB_instit")));
  db_selectrecord("c47_instit",$result,true,$db_opcao,"","","");

?>
    </td>
  </tr>
  <tr>
    <td align=right nowrap title="<?=@$Tc47_tiporesto?>">
       <?=@$Lc47_tiporesto?>
  
    </td>
    <td> 
<?
  $result=$clemprestotipo->sql_record($clemprestotipo->sql_query_file(null,"e90_codigo,e90_descr"));
  db_selectrecord("c47_tiporesto",$result,true,$db_opcao,"","","","0");
?>

    </td>
  </tr>
  <tr>
    <td align=right>

       <?=@$Lc47_compara?>
<?
  $db_o = 1;
  if(isset($opcao) && ($opcao=='alterar' || $opcao == 'excluir')){
    $db_o = 3;
  }
?>
  </td>
  <td>
<?
  $xy = array("0"=>"Não","1"=>"Débito","2"=>"Crédito","3"=>"Elemento");
  db_select('c47_compara',$xy,true,$db_o,"onchange='js_troca(this.value);'");
?>
    </td>
  </tr>

<?if( (( isset($cod) &&  $cod != 0)  ||  ( isset($c47_compara) && $c47_compara != 0  ))  && empty($novo) ){?>

<?
    if(isset($opcao) && ($opcao=='alterar' || $opcao == 'excluir')){

   ?>

    <tr>
      <td nowrap title="Estrutural do elemento">
	 <b>Elemento</b>
      </td>
      <td> 
	 <?

	 //parein aki
	 $result = $clconplano->sql_record($clconplano->sql_query_reduz($c47_ref,"c60_descr as descr","","c60_anousu=".db_getsession("DB_anousu")));
	 if($clconplano->numrows>0){
	   db_fieldsmemory($result,0);
	 }
	 
	 db_input('c47_ref',15,1,true,'text',3);?>
	 <?db_input('descr',30,1,true,'text',3);?>
      </td>
    </tr>
 <?}else{?>
    <tr>
      <td nowrap title="Estrutural do elemento">
	 <b>Estrutural</b>
      </td>
      <td> 
	 <?db_input('estrutural',15,1,true,'text',1);?>
      </td>
    </tr>
  <?}?>
<?}else{?>
  <tr>
    <td align=right owrap title="<?=@$Te54_codcom?>">
       <?=$Le54_codcom?>
    </td>
    <td > 
      <?
      if(isset($e54_codcom) && $e54_codcom==''){
	$pc50_descr='';
      }
	$result=$clpctipocompra->sql_record($clpctipocompra->sql_query_file(null,"pc50_codcom as e54_codcom,pc50_descr"));
	db_selectrecord("c47_ref",$result,true,$db_opcao,"","","","0","js_reload(this.value)");
      ?>
    </td>
  </tr>
<?}?>

  <tr>
    <td align=right nowrap title="<?=@$Tc47_obs?>">
       <?=@$Lc47_obs?>
    </td>
    <td> 
<?
db_textarea('c47_obs',1,60,$Ic47_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=($db_opcao==1||$db_opcao==2?"onclick='return js_verifica();'":"")?> >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <input name="excluir_geral" type="submit" value="Excluir todos" onclick="return confirm('Deseja realmente excluir?')"  <?=($db_opcao==1?"":"style='visibility:hidden;'")?> >
    </td>
  </tr>
  <tr>
    <td colspan=2 align="center" height="80%">  
    <?
	 $chavepri= array("c47_seqtranslan"=>@$c47_seqtranslan,"c47_seqtranslr"=>@$c47_seqtranslr);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clcontranslr->sql_query_file(null,'*','c47_seqtranslr',"c47_seqtranslan=$c47_seqtranslan");
	 $cliframe_alterar_excluir->campos  ="c47_seqtranslan,c47_seqtranslr,c47_debito,c47_credito,c47_anousu,c47_obs,c47_ref,c47_instit,c47_compara,c47_tiporesto";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ='100%';
	 $cliframe_alterar_excluir->legenda = false;
	 $cliframe_alterar_excluir->iframe_width ='100%';
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
</table>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.cod.value='';
  
  document.form1.submit();
}



function js_pesquisac47_credito(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_contranslr','db_iframe_conplanoexe_credito','func_conplanoexe.php?funcao_js=parent.js_mostraconplanoexe1_credito|c62_reduz|c60_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.c47_credito.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_contranslr','db_iframe_conplanoexe_credito','func_conplanoexe.php?pesquisa_chave='+document.form1.c47_credito.value+'&funcao_js=parent.js_mostraconplanoexe_credito','Pesquisa',false);
     }else{
       document.form1.c60_descr_credito.value = ''; 
     }
  }
}
function js_mostraconplanoexe_credito(chave,erro){
  document.form1.c60_descr_credito.value = chave; 
  
  if(erro==true){ 
    document.form1.c47_credito.focus(); 
    document.form1.c47_credito.value = ''; 
  }
}
function js_mostraconplanoexe1_credito(chave1,chave2){
  document.form1.c47_credito.value = chave1;
  document.form1.c60_descr_credito.value = chave2;
  db_iframe_conplanoexe_credito.hide();
}


function js_pesquisac47_debito(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_contranslr','db_iframe_conplanoexe','func_conplanoexe.php?funcao_js=parent.js_mostraconplanoexe1|c62_reduz|c60_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.c47_debito.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_contranslr','db_iframe_conplanoexe','func_conplanoexe.php?pesquisa_chave='+document.form1.c47_debito.value+'&funcao_js=parent.js_mostraconplanoexe','Pesquisa',false);
     }else{
       document.form1.c60_descr.value = ''; 
     }
  }
}
function js_mostraconplanoexe(chave,erro){
  document.form1.c60_descr.value = chave; 
  if(erro==true){ 
    document.form1.c47_debito.focus(); 
    document.form1.c47_debito.value = ''; 
  }
}
function js_mostraconplanoexe1(chave1,chave2){
  document.form1.c47_debito.value = chave1;
  document.form1.c60_descr.value = chave2;
  db_iframe_conplanoexe.hide();
}
</script>