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

include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;
$clpcorcamitem->rotulo->label();
$db_altexc = false;
$checked   = false;

$sql_itens = $clpcorcamitemsol->sql_query(null,null," distinct pc11_codigo ","","pc11_numero=".@$pc10_numero." and pc22_codorc=".@$pc22_codorc);
$result_itens = $clpcorcamitemsol->sql_record($sql_itens);
if($clpcorcamitemsol->numrows>0){
  $db_altexc = true;
  $checked   = true;
}
$db_botao=true;
if((isset($db_opcaoal) && $db_opcaoal==33) || (!isset($pc10_numero) && !isset($pc22_codorc))){
  $db_altexc = false;
  $db_botao=false;
}
$select = $pc22_codorc;
if(isset($pc22_codorc) && $pc22_codorc=="" || !isset($pc22_codorc)){
  $select = "-1";
}

$result_pcorcam = $clpcorcam->sql_record($clpcorcam->sql_query_file($select,"pc20_codorc"));

$where_numero = " pc10_numero=".@$pc10_numero." and pc81_solicitem is null";
$where_codorc = " pc11_numero=".@$pc10_numero." and pc22_codorc = ".@$pc22_codorc;

$sql = $clsolicitem->sql_query_pcmater(null,"distinct pc11_seq,pc11_codigo,pc01_descrmater,pc11_resum","pc11_seq",$where_numero);
$result_sql = $clsolicitem->sql_record($sql);
$numrows_sql = $clsolicitem->numrows;
if($numrows_sql==0){
  $db_botao=false;
  $result_process = $clpcprocitem->sql_record($clpcprocitem->sql_query_file(null,"pc81_solicitem","","pc81_solicitem in (select distinct pc11_codigo from solicitem where pc11_numero= ".@$pc10_numero.")"));
  if($clpcprocitem->numrows>0){
    $impok = true;  		   
  }
}
?>
<style>
<?$cor="#999999"?>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #cccccc;
}
</style>
<form name="form1" method="post" action="">
<center>
<table border="0" cellpadding='0' cellspacing='0' width="100%">
  <tr>
    <td align="center">
      <table>
	<tr>
	  <td nowrap title="<?=@$Tpc22_codorc?>">
	     <?=@$Lpc22_codorc?>
	  </td>
	  <td> 
      <?
      db_input('pc22_codorc',6,$Ipc22_codorc,true,'text',3);
      ?>
	  </td>
	  <td>
      <?     
        if($db_altexc == false){
          echo '<input name="incluir" type="submit" id="incluir" value="Incluir" onclick="return js_verif();" '.($db_botao==false?"disabled":"").' >';
	}else{
          echo '<input name="alterar" type="submit" id="alterar" value="Alterar" onclick="return js_verif();" '.($db_botao==false?"disabled":"").' >';
        }
      ?>
	  </td>
	</tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align='center' colspan="3">
    <?    
    if($numrows_sql!=0){
      $cliframe_seleciona->textocabec ="black";
      $cliframe_seleciona->textocorpo ="black";
      $cliframe_seleciona->fundocabec ="#999999";
      $cliframe_seleciona->fundocorpo ="#cccccc";
      $cliframe_seleciona->iframe_height ="265";
      $cliframe_seleciona->iframe_width ="90%";
      $cliframe_seleciona->iframe_nome ="notas";
      $cliframe_seleciona->fieldset =false;
      $cliframe_seleciona->marcador = true;
      $cliframe_seleciona->campos  = "pc11_seq,pc11_codigo,pc01_descrmater,pc11_resum";
      $cliframe_seleciona->sql = $sql;
      if($checked==true){
        $cliframe_seleciona->sql_marca = $sql_itens;
      }else{
        $cliframe_seleciona->sql_marca = $sql;
      }
      $cliframe_seleciona->input_hidden = true;
      $cliframe_seleciona->chaves ="pc11_codigo";
      $disabled = 1;
      if($db_botao==false){
	$disabled = 33;
      }
      $cliframe_seleciona->iframe_seleciona($disabled);
    }
    ?>
    </td>
  </tr>
</table>
</center>
<?
db_input('valores',6,0,true,'hidden',3);
db_input('pc10_numero',6,0,true,'hidden',3);
?>
</form>
<script>
function js_verif(){
  chaves =  js_retorna_chaves();
  dad_arr = "";
  if(chaves != ""){
    arr = chaves.split("#");
    vir = "";
    for(i=0; i<arr.length; i++){
      dad_arr += vir+arr[i];
      vir = ",";
    }
  }  
  document.form1.valores.value = dad_arr;
}
function js_gerarel(){
  jan = window.open('com2_solorc002.php?pc20_codorc='+document.form1.pc22_codorc.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<?
if(isset($impok)){
  echo "<script>
          alert('Usuário:\\n\\nItens desta solicitação estão em processo de compras!\\n\\nAdministrador:');";
  echo "  top.corpo.iframe_orcam.location.href = 'com1_selsolic001.php?op=incluir&sol=true';";
  echo "</script>";  
}
?>