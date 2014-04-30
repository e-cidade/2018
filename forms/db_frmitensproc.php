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
require_once("libs/db_utils.php");
$cliframe_seleciona = new cl_iframe_seleciona;
$clpcorcamitem->rotulo->label();
$db_altexc = false;
$checked   = false;
$sql_itens = $clpcorcamitemproc->sql_query(null,null," distinct pc81_codprocitem ","pc81_codprocitem","pc80_codproc=".@$pc80_codproc." and pc22_codorc=".@$pc22_codorc);
$result_itens = $clpcorcamitemproc->sql_record($sql_itens);
if($clpcorcamitemproc->numrows>0){
  $db_altexc = true;
  $checked   = true;
}
$db_botao=true;
if((isset($db_opcaoal) && $db_opcaoal==33) || (!isset($pc80_codproc) && !isset($pc22_codorc))){
  $db_altexc = false;
  $db_botao=false;
}
$select = $pc22_codorc;
if(isset($pc22_codorc) && $pc22_codorc=="" || !isset($pc22_codorc)){
  $select = "-1";
}

$result_pcorcam = $clpcorcam->sql_record($clpcorcam->sql_query_file($select,"pc20_codorc"));

$where_numero = " pc80_codproc=".@$pc80_codproc." and (e54_autori is null or (e54_autori is not null and e54_anulad is not null)) ";
$where_codorc = " pc81_codproc=".@$pc80_codproc." and pc22_codorc=$pc22_codorc ";

$sql = $clpcprocitem->sql_query_pcmater(null,"distinct pc81_codprocitem,pc01_descrmater,pc11_resum","pc81_codprocitem",$where_numero);
$result_sql = $clpcprocitem->sql_record($sql);
$numrows_sql = $clpcprocitem->numrows;

if($numrows_sql==0){
  $db_botao=false;
  $result_autoriz = $clempautitem->sql_record($clempautitem->sql_query_autoridot(null,null,"e55_autori","","e55_sequen in (select distinct pc81_codprocitem from pcprocitem where pc81_codproc=$pc80_codproc) and e54_autori is null"));
  if($clempautitem->numrows>0){
    $impok = true;
  }
}
$iNumeroAcordo = '';
if (isset($pc80_codproc)) {
  
  $oDaoAcordoPcprocitem = db_utils::getDao("acordopcprocitem");
  $sSqlDadosAcordo      = $oDaoAcordoPcprocitem->sql_query_acordo(null,
                                                          "ac26_acordo",
                                                           null,
                                                          "pc80_codproc = {$pc80_codproc}
                                                           and (ac16_acordosituacao  not in (2,3))"
                                                           );
  $rsDadosAcordo = $oDaoAcordoPcprocitem->sql_record($sSqlDadosAcordo);
  if ($oDaoAcordoPcprocitem->numrows > 0) {
    
    $iNumeroAcordo = db_utils::fieldsMemory($rsDadosAcordo, 0)->ac26_acordo;
    $db_botao      = false;
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
<table border="0" cellpadding='0' cellspacing='0'>
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
      $cliframe_seleciona->iframe_width ="600";
      $cliframe_seleciona->iframe_nome ="notas";
      $cliframe_seleciona->fieldset =false;
      $cliframe_seleciona->marcador = true;
      $cliframe_seleciona->campos  = "pc81_codprocitem,pc01_descrmater,pc11_resum";
      $cliframe_seleciona->sql = $sql;
      if($checked==true){
        $cliframe_seleciona->sql_marca = $sql_itens;
      }else{
        $cliframe_seleciona->sql_marca = $sql;
      }
      $cliframe_seleciona->input_hidden = true;
      $cliframe_seleciona->chaves ="pc81_codprocitem";
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
db_input('pc80_codproc',6,0,true,'hidden',3);
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
/*
function js_gerarel(){
  jan = window.open('com2_solorc002.php?pc20_codorc='+document.form1.pc22_codorc.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
*/
</script>
<?
if(isset($impok) && isset($db_chama)){
  echo "<script>
          alert('Usuário:\\n\\nAlguns itens incluídos neste orçamento estão em autorização de empenho!\\n\\nAdministrador:');";
  if($db_chama=="alterar"){
    echo "  top.corpo.iframe_orcam.location.href = 'com1_processo005.php';";
  }else if($db_chama=="excluir"){
    echo "  top.corpo.iframe_orcam.location.href = 'com1_processo006.php';";
  }
  echo "</script>";
}
?>