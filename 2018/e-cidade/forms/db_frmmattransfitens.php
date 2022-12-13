<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: material
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clmatestoqueinimei->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");
$clrotulo->label("m60_codmater");
$clrotulo->label("m60_descr");
$clrotulo->label("m70_codigo");
$clrotulo->label("m71_codmatestoque");
$clrotulo->label("m71_quant");
$clrotulo->label("m80_matestoqueitem");
$clrotulo->label("m80_obs");
if(isset($departamentodestino) && trim($departamentodestino)!=""){
  $result_departamentodestino = $cldb_depart->sql_record($cldb_depart->sql_query_file($departamentodestino,"coddepto as departamentodestino,descrdepto as descrdepartamentodestino"));
  db_fieldsmemory($result_departamentodestino,0);
}
if(isset($departamentoorigem) && trim($departamentoorigem)!=""){
  $result_departamentoorigem = $cldb_depart->sql_record($cldb_depart->sql_query_file($departamentoorigem,"coddepto as departamentoorigem,descrdepto as descrdepartamentoorigem"));
  db_fieldsmemory($result_departamentoorigem,0);
}
if(isset($opcao) && ($opcao=='alterar' || $opcao=='excluir')){
	
  if(isset($m80_codigo)){
  	
    //echo "<br>".$clmatestoqueinimei->sql_query_matestoque(null,"matestoqueini.m80_codigo as valores,m60_codmater,m60_descr,sum(m82_quant) as quantlanc,(sum(m71_quant)-sum(m71_quantatend)) as quantdis,m70_codigo","matestoqueini.m80_codigo"," matestoqueini.m80_coddepto= ".@$departamentoorigem." and m83_coddepto=".@$departamentodestino." and m60_codmater = $m60_codmater and  matestoqueini.m80_codigo=$m80_codigo group by matestoqueini.m80_codigo,m60_codmater,m60_descr,m70_codigo ");
    $m80_codigo = (!empty($m80_codigo))?$m80_codigo:'null';
    $result_dadosaltexc = $clmatestoqueinimei->sql_record($clmatestoqueinimei->sql_query_matestoque(null,"matestoqueini.m80_codigo as valores,m60_codmater,m60_descr,sum(m82_quant) as quantlanc,(sum(m71_quant)-sum(m71_quantatend)) as quantdis,m70_codigo","matestoqueini.m80_codigo"," matestoqueini.m80_coddepto= ".@$departamentoorigem." and m83_coddepto=".@$departamentodestino." and m60_codmater = $m60_codmater and  matestoqueini.m80_codigo=$m80_codigo group by matestoqueini.m80_codigo,m60_codmater,m60_descr,m70_codigo "));
    if($clmatestoqueinimei->numrows>0){
      db_fieldsmemory($result_dadosaltexc,0);
      if($opcao=='alterar'){
        $quantestoque = $quantlanc;
        $quantdis += $quantlanc;
      }else{
        $quantestoque = $quantlanc;
        $quantdis = $quantlanc;
      }
    }
  }
}
if(isset($opcao) && $opcao=="excluir"){
  $db_opcao = 3;
}
?>
<form name="form1" method="post" action="">
<BR><BR>
<table>
<tr>
<td>
<fieldset><legend><b>Itens da Transferência</b></legend>
<table border="0">
  <tr>
    <td title="Departamento origem" align="right">
      <strong>Departamento origem:</strong>
    </td>
    <td align="left" nowrap colspan='3'>
       <? 
db_input('coddepto',10,$Idescrdepto,true,"text",3,"","departamentoorigem");
       ?>
       <? 
db_input('descrdepto',43,$Im60_descr,true,"text",3,"","descrdepartamentoorigem");
       ?>
    </td>
  </tr>
  <tr>
    <td title="Departamento destino" align="right">
      <strong>Departamento destino:</strong>
    </td>
    <td align="left" nowrap colspan='3'>
       <? 
	     db_input('coddepto',10,$Idescrdepto,true,"text",3,"","departamentodestino");
       ?>
       <? 
		 db_input('descrdepto',43,$Im60_descr,true,"text",3,"","descrdepartamentodestino");
       ?>
    </td>
  </tr>
  <tr>
    <td align='right' nowrap title="<?=@$Tm60_codmater?>">
       <?
       db_ancora(@$Lm60_codmater,"js_pesquisam60_codmater(true);",(isset($opcao)&&($opcao=='alterar'||$opcao=='excluir')?"3":"1"));
       ?>
    </td>
    <td align='left' nowrap colspan='3'>
       <?
         db_input('m60_codmater',10,$Im60_codmater,true,'text',(isset($opcao)&&($opcao=='alterar'||$opcao=='excluir')?"3":"1")," onchange='js_pesquisam60_codmater(false);'")
       ?>
       <?
         db_input('m60_descr',43,$Im60_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <?
  if(isset($m60_codmater) || (isset($opcao) && isset($m80_codigo))){
    if(isset($m60_codmater) && !isset($opcao)){
      $result_quantidades = $clmatestoqueitem->sql_record($clmatestoqueitem->sql_query(null,"m70_codigo,(sum(m71_quant)-sum(m71_quantatend)) as quantdis",""," m70_codmatmater=$m60_codmater and m70_coddepto=$departamentoorigem group by m70_codigo"));
      if($clmatestoqueitem->numrows>0){
	    db_fieldsmemory($result_quantidades,0);
      }
     $clmatmater = new cl_matmater();
     $rsMaterial   = $clmatmater->sql_record($clmatmater->sql_query_file($m60_codmater)); 
	 $oMaterial    = db_utils::fieldsMemory($rsMaterial, 0);
	
     if ($oMaterial->m60_controlavalidade == 1 || $oMaterial->m60_controlavalidade == 2) {
       
       echo "<tr>";
       
        echo "<td align='right'><b>Lotes do Material:</b></td>";
        echo "<td><b><a href='' onclick='js_mostraLotes({$m60_codmater}, ".db_getsession("DB_coddepto").");return false;'>Ver Lotes</a></b>";
       echo "<tr>";
     }
    }

    if(isset($quantdis) && $quantdis>0){
      $quantlanc = $quantdis;
      echo "
      <tr>
	<td nowrap title='Quantidade disponível' align='right'>
      ";
      db_ancora("<strong>Quantidade disponível:</strong>","",3);
      echo "
	</td>
	<td>
      ";
      db_input('quantdis',10,$Im71_quant,true,'text',3,'');
      echo "
	</td>
	<td nowrap title='Quantidade a lançar' align='left'>
      ";
      db_ancora("<strong>Quantidade a lançar:</strong>","",3);
      db_input('quantlanc',10,$Im71_quant,true,'text',$db_opcao,'onchange="js_verificaquantidade(this.value);"');
      echo "
	</td>
      </tr>
      ";
      echo "
      <tr>
	<td nowrap title='".@$Tm80_obs."' align='right'>
	   ".@$Lm80_obs."
	</td>
	<td colspan='3'>
      ";
      db_textarea('m80_obs',2,55,$Im80_obs,true,'text',$db_opcao,"");
      echo "
	</td>
      </tr>
      ";
	  db_input('m82_codigo',10,0,true,'hidden',3,'');
      db_input('m70_codigo',10,$Im70_codigo,true,'hidden',3);
      db_input('m71_codlanc',10,0,true,'hidden',3);
      db_input('quantestoque',10,0,true,'hidden',3);
    }else{
      echo "
      <tr>
	<td colspan='4' align='center'><strong>Departamento sem estoque disponível para transferir.</strong></td>
      </tr>
      ";
    }
    $db_botao = false;
  }else{
    $db_botao = true;
    echo "
    <tr>
      <td colspan='2' align='center'>&nbsp;</td>
    </tr>
    ";
  }
  ?>
  </table>
  </fieldset>
  </td>
  </tr>
  <?
  echo "
  <tr>
    <td colspan='4' align='center'>
      <input name='".(!isset($opcao)?"incluir":((isset($opcao)&&trim($opcao)=='alterar')?"alterar":"excluir"))."' type='submit' id='db_opcao'  value='".(!isset($opcao)?"Incluir":((isset($opcao)&&trim($opcao)=='alterar')?"Alterar":"Excluir"))."' ".($db_botao==false?"":"disabled").">
      &nbsp;&nbsp;
      <input name='emitirTermo' type='button' id='db_emitirTermo' value='Emite Termo' onclick='js_emiteTermo({$valores});' ".($lBotaoTermo==true?"":"disabled").">
  ";
  $where_opcao = "";
  if(isset($opcao) && ($opcao=='alterar' || $opcao=='excluir')){
    //$where_opcao = " and  m82_codigo<>$m82_codigo ";
	$where_opcao = " and  m60_codmater<>$m60_codmater ";
    echo "
      <input name='novo' type='button' id='novo' value='Novo' onclick= 'document.location.href=\"mat1_mattransfitens001.php?departamentoorigem=$departamentoorigem&departamentodestino=$departamentodestino&valores=".@$valores."\"'>
    ";
  }
  echo "
    </td>
  </tr>
  ";

  echo "
    <tr>
      <td align='center' colspan='4'><BR>
  ";
  
       $sql = $clmatestoqueinimei->sql_query_matestoque(null,"matestoqueini.m80_codigo,m60_codmater,m60_descr,sum(m82_quant) as m82_quant","m80_codigo"," matestoqueini.m80_coddepto= ".@$departamentoorigem." and m83_coddepto=".@$departamentodestino." $where_opcao and matestoqueini.m80_codtipo<>8 and matestoqueinill.m87_matestoqueini is null and matestoqueini.m80_codigo=".@$valores." group by matestoqueini.m80_codigo,m60_codmater,m60_descr ");
	   //echo "$sql<br>";
       $chavepri= array("m80_codigo"=>@$m80_codigo ,"m60_codmater"=>@$m60_codmater);
       $cliframe_alterar_excluir->chavepri= $chavepri;
       $cliframe_alterar_excluir->sql     = $sql;
       $cliframe_alterar_excluir->campos  = "m80_codigo,m60_codmater,m60_descr,m82_quant";
       $cliframe_alterar_excluir->legenda ="ITENS LANÇADOS";
       $cliframe_alterar_excluir->iframe_height ="150";
       $cliframe_alterar_excluir->iframe_width ="712";
       $cliframe_alterar_excluir->textocabec ="black";
       $cliframe_alterar_excluir->textocorpo ="black";
       $cliframe_alterar_excluir->fundocabec ="#999999";
       $cliframe_alterar_excluir->fundocorpo ="#cccccc";
       $cliframe_alterar_excluir->opcoes = 3;
       $cliframe_alterar_excluir->fieldset = true;
       $cliframe_alterar_excluir->iframe_alterar_excluir(1);//$db_opcao;
  echo "
      </td>
    </tr>
  ";
 
db_input('nosetmaterial',10,0,true,'hidden',3);
db_input('valores',10,0,true,'hidden',3,'');

  ?>
</table>
</form>
<script>
/**
  * Função que abre a janela para emisão de termo de transferência
  */
function js_emiteTermo (transferId) {

  var sTransferUrl = 'ini='+transferId+'&fim='+transferId;
  jan = window.open('mat2_transfermater002.php?'+sTransferUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_verificaquantidade(valor){
  valor = new Number(valor); 
  VALOR = new Number(document.form1.quantdis.value);
  if(valor>VALOR){
    alert('Quantidade não disponível.');
    document.form1.quantlanc.value = VALOR;
    document.form1.quantlanc.select();
  }else if(valor<=0){
    alert('Quantidade inválida.');
    document.form1.quantlanc.value = VALOR;
    document.form1.quantlanc.select();
  }
}
function js_pesquisam60_codmater(mostra){
  qry  = "&codigododepartamento=<?=($departamentoorigem)?>";
  <?
  if(!isset($opcao)){
    if(isset($valores)){

      if (!empty($valores)) {
      $result_materiais = $clmatestoqueinimei->sql_record($clmatestoqueinimei->sql_query(null,"distinct m70_codmatmater","","m82_matestoqueini=".@$valores));
      $codigos = "";
      $vir = "";
      for($i=0;$i<$clmatestoqueinimei->numrows;$i++){
	  	db_fieldsmemory($result_materiais,$i);
		$codigos .= $vir.$m70_codmatmater;
		$vir = ",";
      }
      echo "qry += '&nosetmaterial=".@$codigos."';";
    }
    }
  }
  ?>
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_itens','db_iframe_matmater','func_matmaterdepto.php?funcao_js=parent.js_mostramatmater1|m60_codmater|m60_descr'+qry,'Pesquisa',true);
  }else{
     if(document.form1.m60_codmater.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_itens','db_iframe_matmater','func_matmaterdepto.php?pesquisa_chave='+document.form1.m60_codmater.value+'&funcao_js=parent.js_mostramatmater'+qry,'Pesquisa',false);
     }else{
       document.form1.m60_descr.value = '';
     }
  }
}
function js_mostramatmater(chave,erro){
   document.form1.m60_descr.value = chave;
  if(erro==true){
    document.form1.m60_codmater.focus();
    document.form1.m60_codmater.value = '';
  }else{
    document.form1.submit();
  }
}
function js_mostramatmater1(chave1,chave2){
  document.form1.m60_codmater.value = chave1;
  document.form1.m60_descr.value = chave2;
  db_iframe_matmater.hide();
  document.form1.submit();
}
function js_mostraLotes(iItem, iCodEstoque) {
  
  iCodItem      = new Number(iItem);//código do material
  nValor        = new Number($F('quantlanc'));//Quantidade digitada pelo usuário
  nValorReqItem = new Number($F('quantlanc'));
  if (nValor  == 0) {
    alert('Informe a quantidade');
  } else {
  
    sUrl  = 'mat4_mostraitemlotes.php?iCodMater='+iCodItem+'&iCodDepto='+iCodEstoque+'&nValor='+nValor;
    sUrl += '&nValorSolicitado='+nValorReqItem+'&updateField=quantlanc';
    js_OpenJanelaIframe('top.corpo.iframe_itens','db_iframe_lotes',sUrl,'Lotes ',true);
    
  }
  
}
</script>