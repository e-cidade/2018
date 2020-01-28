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

//include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clsolicitem->rotulo->label();
$clpcdotac->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o56_elemento");
$clrotulo->label("descrdepto");
$clrotulo->label("pc13_coddot");
$clrotulo->label("pc11_numero");
$clrotulo->label("pc16_codmater");

if(isset($codigo) && trim($codigo)!=""){
  $pc13_codigo=$codigo;
}

$result_servico = $clpcmater->sql_record($clpcmater->sql_query($pc16_codmater,"pc05_servico","pc01_codmater"));
db_fieldsmemory($result_servico,0);

$result_valorquant = $clsolicitem->sql_record($clsolicitem->sql_query_file($pc13_codigo,"pc11_vlrun,pc11_quant"));
if($clsolicitem->numrows>0){
  db_fieldsmemory($result_valorquant,0);  
}
$result_dotacvlqnt = $clpcdotac->sql_record($clpcdotac->sql_query_file($pc13_codigo,null,null,"sum(pc13_quant) as pc13_quantmax,sum(pc13_valor) as pc13_valormax"));
if($clpcdotac->numrows>0){
  db_fieldsmemory($result_dotacvlqnt,0);
  $pc13_quantmax = $pc11_quant - $pc13_quantmax;
  $pc13_valormax = ($pc11_vlrun*$pc11_quant) - $pc13_valormax;
}else if(isset($pc11_quant) && isset($pc11_vlrun)){
  $pc13_quantmax = $pc11_quant;
  $pc13_valormax = $pc11_vlrun;
}
if(isset($opcao) && ($opcao=="alterar" || $opcao=="excluir")){
  $pc13_quantmax += $pc13_quant;
  $pc13_valormax += $pc13_valor;
}

if($pc13_quantmax==0 || ($pc13_valormax==0 && $pc11_vlrun>0)){
  if($db_opcao!=2 && $db_opcao!=3){
    $db_botao=false;
  }
}else{
  $db_botao=true;
}
?>
<form name="form1">
<? $pc13_anousu = db_getsession("DB_anousu"); ?>
<? db_input('pc13_anousu',4,$Ipc13_anousu,true,'hidden',3); ?>
<? db_input('pc11_numero',4,$Ipc11_numero,true,'hidden',3); ?>
<? db_input('pc16_codmater',10,$Ipc16_codmater,true,'hidden',3); ?>
<?
   $where_codmater = "";
   if(isset($pc16_codmater) && trim($pc16_codmater)!=""){
     $where_codmater = " pc01_codmater=$pc16_codmater ";
   }
   $sql_record = $clpcmaterele->sql_record($clpcmaterele->sql_query(null,null,"o56_elemento,substr(o56_descr,1,30) as o56_descr","",$where_codmater));
   $numrows_materele = $clpcmaterele->numrows;
   $dad_select = array();
   for($i=0;$i<$numrows_materele;$i++){
     db_fieldsmemory($sql_record,$i);
     $dad_select[$o56_elemento] = $o56_descr;
   }
   if(isset($pc13_codele) && trim($pc13_codele)!=""){
     $o56_elemento = $pc13_codele;
   }
   
?>
<center>
<table height="20" border="0">
  <tr>
    <td nowrap title="<?=@$Tpc13_coddot?>">
      <?db_ancora(@$Lpc13_coddot,"js_pesquisapc13_coddot(true);",($db_opcao!=1?"3":"1"));?>
    </td>
    <td nowrap>
      <? db_input('pc13_coddot',8,$Ipc13_coddot,true,'text',3); ?>
    </td>
    <td nowrap title="<?=@$Tpc13_codigo?>">
     <? db_ancora(@$Lpc11_codigo,"",3); ?>
    </td>
    <td nowrap>
     <? db_input('pc13_codigo',8,$Ipc13_codigo,true,'text',3); ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc13_depto?>">
       <?
       db_ancora(@$Lpc13_depto,"",3);
       ?>
    </td>
    <td nowrap colspan="3">
       <?
       db_input('pc13_depto',8,$Ipc13_depto,true,'text',3)
       ?>
       <?
       db_input('descrdepto',30,$Idescrdepto,true,'text',3,'');
       ?>
    </td>
  </tr>
       <?
       if($numrows_materele>0){
       ?>
  <tr>
    <td nowrap title="<?=@$To56_elemento?>">
       <?
       db_ancora($Lo56_elemento,"",3);
       ?>
    </td>
    <td nowrap colspan="3">
       <?
       db_select("o56_elemento",$dad_select,true,2);
       ?>
    </td>
  </tr>
       <?}?>
  <tr>
    <td nowrap title="Quantidade não incluída em dotações">
     <? db_ancora("<b>Quant. disp.:</b>","",3); ?>
    </td>
    <td nowrap>
     <? db_input('pc13_quantmax',10,0,true,'text',3); ?>
    </td>
    <td nowrap title="Valor não incluído em dotações">
     <? db_ancora("<b>Val. disp.:</b>","",3); ?>
    </td>
    <td nowrap>
     <? db_input('pc13_valormax',10,0,true,'text',3); ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc13_quant?>">
      <?=@$Lpc13_quant?>
    </td>
    <td>
      <?
      db_input('pc13_quant',10,$Ipc13_quant,true,'text',$db_opcao,"onchange='js_calcquant(this.value,this.name);'");
      ?>
    </td>
    <td nowrap title="<?=@$Tpc13_valor?>">
      <?=@$Lpc13_valor?>
    </td>
    <td nowrap>
      <?
      db_input('pc13_valor',10,$Ipc13_valor,true,'text',3,"onchange='js_calcvalor(this.value,this.name);");      
      ?>
      <?
      if($db_opcao!=1){
        echo "<input name='novo' type='button' id='novo' value='Novo' onclick='document.location.href=\"com1_seldotac001.php?pc13_codigo=$pc13_codigo&pc11_numero=$pc11_numero&pc16_codmater=$pc16_codmater\"'>";
      }
      ?>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>
      <input name="voltar" type="button" id="db_opcao" value="Voltar" onClick='location.href="com1_liberasol001.php?codigo=<?=@$numero?>"' >
      <?
      if($db_opcao!=1){
        echo "<input name='novo' type='button' id='novo' value='Novo' onclick='document.location.href=\"com1_seldotac001.php?pc13_codigo=$pc13_codigo&pc11_numero=$pc11_numero&pc16_codmater=$pc16_codmater\"'>";
      }
      ?>
    </td>
  </tr>
  <?
  $result_gerareserva = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_gerareserva"));
  db_fieldsmemory($result_gerareserva,0);
  if($pc30_gerareserva=='t'){
  ?>  
  <tr>
    <td nowrap><strong>Saldo da dotação: </strong></td>
    <td nowrap>
       <?
       db_input('atual',10,0,true,'text',3)
       ?>
    </td>
    <td nowrap><strong>Saldo reservado: </strong></td>
    <td nowrap>
       <?
       db_input('reservado',10,0,true,'text',3)
       ?>
    </td>
    <td nowrap><strong>Valor disponível: </strong></td>
    <td nowrap>
       <?
       db_input('atual_menos_reservado',10,0,true,'text',3);
       ?>
       <?
       if($db_opcao!=1 && isset($pc13_coddot) && isset($pc13_codigo)){
	 $result_pesq_pcdotac = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null,null,"o82_codres","","o82_solicitem=$pc13_codigo and o80_coddot=$pc13_coddot and o80_anousu=$pc13_anousu"));
	 if($clorcreservasol->numrows>0){	   
	   echo "<strong>Saldo reservado</strong>";
	 }else{
	   echo "<strong>Saldo não reservado</strong>";
	 }
       }
       ?>
    </td>
  </tr>
  <?
  }
  ?>
  
</table>
<center>
<table width="100%" height="20" border="0">
  <tr>
    <td colspan="4">
      <center>
      <table border = "0">
        <tr align="center">
	  <td align="center">
	  <?
	  $where_coddot = "";
	  if(isset($pc13_coddot) && $pc13_coddot && !isset($incluir) && !isset($alterar) && !isset($excluir)){
	    $where_coddot = " and pc13_coddot<>$pc13_coddot";
	  }
	  $chavepri= array("pc13_anousu"=>$pc13_anousu,"pc13_coddot"=>@$pc13_coddot);
	  $cliframe_alterar_excluir->chavepri= $chavepri;
	  $cliframe_alterar_excluir->sql     = $clpcdotac->sql_query_descrdot(null,null,null,"pc13_codigo,pc13_coddot,o56_descr,pc13_anousu,pc13_quant,pc13_valor","pc13_codigo"," pc13_codigo =".@$pc13_codigo." and pc13_anousu=".@$pc13_anousu.$where_coddot);
	  $cliframe_alterar_excluir->campos  = "pc13_codigo,pc13_coddot,o56_descr,pc13_anousu,pc13_quant,pc13_valor";
	  $cliframe_alterar_excluir->legenda = "DOTAÇÕES LANÇADAS";
	  $cliframe_alterar_excluir->iframe_height = 270;
	  $cliframe_alterar_excluir->iframe_width  = "712";
	  $cliframe_alterar_excluir->opcoes  = 1;
	  $cliframe_alterar_excluir->fieldset  = false;
	  $cliframe_alterar_excluir->iframe_alterar_excluir(1);//$db_opcao;
	  ?>
	  </td>
        </tr>
      </table>
      </center>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_dot(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","pesquisa_dot");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisapc13_coddot(mostra){
  qry= 'obriga_depto=sim';
  <?if($numrows_materele>0){?>
  qry+= '&elemento='+document.form1.o56_elemento.value;
  <?}?>
  qry+= '&departamento=<?=(db_getsession("DB_coddepto"))?>';
  qry+= '&retornadepart=true';
  if(mostra==true){
    qry+= '&funcao_js=parent.js_mostraorcdotacao1|o58_coddot';
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_permorcdotacao.php?'+qry,'Pesquisa',true);
  }else{
    qry+= '&pesquisa_chave='+document.form1.pc13_coddot; 
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_permorcdotacao.php?'+qry+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
  }
}
function js_mostraorcdotacao1(chave1,chave2){
  document.form1.pc13_coddot.value = chave1;
  document.form1.pc13_depto.value = chave2;
  js_dot();
  db_iframe_orcdotacao.hide();
}

function js_mostraorcdotacao(chave1){
  document.form1.pc13_coddot.value = chave1;
  db_iframe_orcdotacao.hide();
}
function js_pesquisapc13_depto(){
  if(document.form1.pc13_depto.value != ''){
     js_OpenJanelaIframe('top.corpo','db_iframe_depart','func_db_depart.php?pesquisa_chave='+document.form1.pc13_depto.value+'&funcao_js=parent.lanc_dotac.js_mostradb_depart','Pesquisa',false,'0','1','790','405');
  }else{
    document.form1.descrdepto.value = '';
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.pc13_depto.focus();
    document.form1.pc13_depto.value = '';
  }
}
function js_calcquant(valor,nome){
  /*
  max   = parseFloat(document.form1.);
  if(valor.indexOf(',') != -1){
    document.form1.pc13_quant.value = document.form1.pc13_quant.value.replace(',','.');
    valor = document.form1.pc13_quant.value;
  }
  valor = parseFloat(valor);
  if(max!=0 && (valor>max)){
    alert("A quantidade informada na dotação deve ser inferior ou igual a "+max+" (Quantidade restante do item).");
    document.form1.pc13_quant.value = "";
    document.form1.pc13_valor.value = "";
    document.form1.pc13_quant.focus();
  }else if(max==0){
    alert("Todos os materiais deste lançamento já foram incluídos em dotações.");
    document.form1.pc13_quant.value = "";
    document.form1.pc13_quant.focus();
  }
  */
}
function js_calcquant(valor,nome){
}



servico ='<?=($pc05_servico)?>';

</script>