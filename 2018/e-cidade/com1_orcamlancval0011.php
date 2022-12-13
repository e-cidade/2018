<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_pcorcamitem_classe.php"));
include(modification("classes/db_pcorcamval_classe.php"));
include(modification("classes/db_empparametro_classe.php"));
include(modification("classes/db_pcmater_classe.php"));

$clempparametro = new cl_empparametro;
$clpcorcamitem  = new cl_pcorcamitem;
$clpcorcamval   = new cl_pcorcamval;
$clrotulo       = new rotulocampo;
$clpcmater      = new cl_pcmater;

$clpcorcamitem->rotulo->label();
$clrotulo->label('pc23_valor');
$clrotulo->label('pc23_vlrun');
$clrotulo->label('pc23_quant');
$clrotulo->label('pc23_obs');
$clrotulo->label('pc23_validmin');
$clrotulo->label('pc11_vlrun');
$clrotulo->label('pc11_quant');
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$arr_vlnomesitens = Array();
$arr_valoresitens = Array();
$arr_quantitens = Array();
$arr_vtnomesitens = Array();
$arr_nomesBDI = Array();
$arr_nomesEncargos = Array();
$arr_taxasEstimadas = Array();
$res_empparametro = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu"),"e30_numdec"));
if ($clempparametro->numrows > 0){
     db_fieldsmemory($res_empparametro,0);
     if (trim($e30_numdec) == "" || $e30_numdec == 0){
          $numdec = 2;
     } else {
          $numdec = $e30_numdec;
     }
} else {
     $numdec = 2;
}


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<?
  if ($db_opcao==1){
    $flag=1;
  }
  if ($db_opcao==2){
    $flag=2;  
  }
?>
<script>

function js_verquant(nome,val,max,param,flag){

  val  = new Number(val);
  max  = new Number(max);
  flag = new Number(flag);
  erro = 0;
 
  if(val < max){
    erro++;
    alert('Quantidade orçada deve ser igual que a quantidade do pedido.Este item será declassificado.');
  }
  
  if(val > max){
    erro++;
    alert('Quantidade orçada não pode ser maior que quantidade do pedido!');
    eval("document.form1."+nome+".value='"+max+"';");
    eval("document.form1."+nome+".focus();");
    return false;
  }  
  
  if(val<0){
    erro++;
    alert('Usuário: \n\nQuantidade orçada deve ser maior que 0 (zero).\n\nAdministrador:');
  }
  
  if(erro > 0){
  
    eval("document.form1."+nome+".value='"+val+"';");
    eval("document.form1."+nome+".focus();");
    
  } else {
  
    valorunit  = eval("document.form1.vlrun_"+param+".value");  
    valortotal = eval("document.form1.valor_"+param+".value");
    verpos = 0;
    vltot  = 0;
    
    if(valorunit!=""){
      valorunit = valorunit.replace(',','.');
      verpos = 1;    
    }else if(valortotal!=""){
      valortotal = valortotal.replace(',','.');
      verpos = 2;
    }

    if(verpos==1){
      valpos = valorunit;
    }else if(verpos==2){
      valpos = valortotal;
    }

    dec = 2;
    if(verpos!=0){
      pos = valpos.indexOf('.');
      if(pos!=-1){
  tam = new Number(valpos.length);
  qts = valpos.slice((pos+1),tam);
  dec = 2;
      }
      if(dec<=1){
  dec = 2;
      }
      valpos = new Number(valpos);
    }
    if(verpos==1){
      vltot = new Number(valpos*val);
      eval("document.form1.valor_"+param+".value='"+vltot.toFixed(2)+"'");
    }else if(verpos==2){
      vltot = new Number(valpos/val);
      eval("document.form1.vlrun_"+param+".value='"+vltot.toFixed(<?=$numdec?>)+"'");
    }
  }
}

function js_calcvaltot(valor,param,nome){
  if(!isNaN(valor)){
    dec = 2;
    pos = valor.indexOf('.');
    if(pos!=-1){
      tam = new Number(valor.length);
      qts = valor.slice((pos+1),tam);
      dec = qts.length;      
    }
    if(dec<=1){
      dec = 2;
    }
    quant = eval("document.form1.qtde_"+param+".value");
    valortotal = new Number(eval("document.form1.valor_"+param+".value"));
    if(valor!='' && quant!=''){
      valor = new Number(valor);
      quant = new Number(quant);    
      valortotal = new Number(quant*valor);
    }
    if(valor==""){
      valor = 0;
    }

    eval("document.form1.valor_"+param+".value='"+valortotal.toFixed(2)+"'");
    eval("document.form1."+nome+".value='"+valor.toFixed(<?=$numdec?>)+"'");
    if(valortotal==0){      
      eval("document.form1."+nome+".value='0.00'");
    }
  }else{
    eval("document.form1.vlrun_"+param+".value=''");
  }
}
function js_calcvalunit(valor,param,nome){
  if(!isNaN(valor)){
    dec = 2;
    pos = valor.indexOf('.');
    if(pos!=-1){
      tam = new Number(valor.length);
      qts = valor.slice((pos+1),tam);
      dec = qts.length;      
    }
    if(dec<=1){
      dec = 2;
    }
    quant = eval("document.form1.qtde_"+param+".value");
    valorunit = new Number(eval("document.form1.vlrun_"+param+".value"));
    if(valor!='' && quant!=''){
      valor = new Number(valor);
      quant = new Number(quant);
      valorunit = new Number(valor/quant);
    }
    if(valor==""){
      valor = 0;
    }
    eval("document.form1.vlrun_"+param+".value='"+valorunit.toFixed(<?=$numdec?>)+"'");
    eval("document.form1."+nome+".value='"+valor.toFixed(2)+"'");
    if(valorunit==0){
      eval("document.form1.vlrun_"+param+".value='0.00'");
    }
  }else{
    eval("document.form1.valor_"+param+".value=''")
  }
}
function js_passacampo(campo,vsubtr){
  index = 0;
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type=='text'){      
      if(document.form1.elements[i].name==campo){
  index = i;
  break;
      }
    }
  }
  if(index != 0){
    if((index+4)<=document.form1.length){
      document.form1.elements[index+4].select();
      document.form1.elements[index+4].focus();
    }else{
      if(vsubtr=="vlrun_"){
        document.form1.elements[2].select();
        document.form1.elements[2].focus();
      }else{
        document.form1.elements[3].select();
        document.form1.elements[3].focus();
      }
    }
  }
}
function js_somavalor(){
  obj=document.form1;
  somavalor=0;
  for (i=0;i<obj.elements.length;i++){
    if (obj.elements[i].name.substr(0,6)=="valor_"){
        if (obj.elements[i].value!=""&&obj.elements[i].value!=0){
          var objvalor=new Number(obj.elements[i].value);      
          somavalor+=objvalor;
        }
      }
    }
  
  document.form1.somavalor.value=somavalor.toFixed(2);
}

function js_processar_lote(elemento){
  var frm = document.form1;
  var taxaEstimada = isNaN(parseFloat(elemento.value)) ? "" : parseFloat(elemento.value);
  var className = elemento.className.split(" ", 1)[0];

  if (className.split("_", 1)[0] == "lote") {
      var itensLote = document.getElementsByClassName(className);

      for (var i =  0; i < itensLote.length; i++) {
          itensLote[i].value = taxaEstimada;
      }
  }

}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?$cor="#999999";?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
<?$cor="999999"?>
.bordas_corp{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
       }
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post">
<table  border="0" cellspacing="0" cellpadding="0" width="100%" style="empty-cells: show">
  <tr> 
    <td  align="center" valign="top" bgcolor="#CCCCCC"> 
      <table border='1' cellspacing="0" cellpadding="0">   
      <?
        $camposelected = "";
        if(isset($pc20_codorc) && trim($pc20_codorc) != ''){
    $ok = false;

    if(isset($pc21_orcamforne) && trim($pc21_orcamforne)!=''){
      $ok = true;
    }

    if(isset($sol) && $sol=="true"){

      $result_itens = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmatersol(null,"pc11_seq, 1 as tipo_julga, '' as lote , pc11_resum,pc11_codigo,pc11_vlrun,pc11_quant,pc01_codmater,pc01_descrmater,pc22_orcamitem","pc22_orcamitem","pc20_codorc=$pc20_codorc"));
    } else if(isset($sol) && $sol=="false"){

      $result_itens = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterproc(null,"pc11_seq, pc80_tipoprocesso as tipo_julga, pc68_nome as lote, pc11_resum,pc11_codigo,pc11_vlrun,pc11_quant,pc01_codmater,pc01_descrmater,pc22_orcamitem","pc11_seq","pc20_codorc=$pc20_codorc"));
    }

    $numrows_itens = $clpcorcamitem->numrows;
    if($numrows_itens > 0) {

      $lMostraLote = db_utils::fieldsMemory($result_itens, 0)->tipo_julga == 2;
      ?>
      <tr class='bordas'>
        <td nowrap class='bordas' align='center'><b>Item</b></td>
        <? if ($lMostraLote) { ?> <td nowrap class='bordas' align = 'center' ><b > Lote</b ></td ><? } ?>
        <td nowrap class='bordas' align='center'><b>Descrição</b></td>
        <td nowrap class='bordas' align='center'><b>Resumo</b></td>
        <td nowrap class='bordas' align='center'><b>Obs/Marca</b></td>
        <td nowrap class='bordas' align='center'><b><?=$RLpc23_validmin?></b></td>
        <td nowrap class='bordas' align='center'><b>Quantidade</b></td>
        <td nowrap class='bordas' align='center'><b>Qtde. orçada</b></td>
        <td nowrap class='bordas' align='center'><b>Valor Unit.</b></td>
        <td nowrap class='bordas' align='center'><b>Valor total</b></td>
        <td nowrap class='bordas' align='center'><b>BDI</b></td>
        <td nowrap class='bordas' align='center'><b>Encargos Sociais</b></td>
        <td nowrap class='bordas' align='center'><b>Taxa Estimada</b></td>
      </tr>

      <?
        for($i=0;$i<$numrows_itens;$i++){
          db_fieldsmemory($result_itens,$i);
          
          if($ok == true){

            $result_lancados = $clpcorcamval->sql_record($clpcorcamval->sql_query_file(@$pc21_orcamforne,@$pc22_orcamitem,"pc23_quant ,pc23_valor as valor_$pc22_orcamitem,pc23_vlrun as vlrun_$pc22_orcamitem,pc23_obs as obs_$pc22_orcamitem,pc23_validmin  as pc23_validmin_$pc22_orcamitem, pc23_bdi as bdi_$pc22_orcamitem, pc23_encargossociais as encargossociais_$pc22_orcamitem, pc23_taxaestimada as taxaestimada_$pc22_orcamitem","pc23_orcamitem"));

            if ( $clpcorcamval->numrows > 0 ) {
              db_fieldsmemory($result_lancados,0);
            } else{
              $pc23_quant                           = 0;
              ${"valor_$pc22_orcamitem"}            = 0;
              ${"vlrun_$pc22_orcamitem"}            = 0; 
              ${"obs_$pc22_orcamitem"}              = null; 
              ${"pc23_validmin_$pc22_orcamitem"}    = null;
              ${"bdi_$pc22_orcamitem"}              = null;
              ${"encargossociais_$pc22_orcamitem"}  = null;
              ${"taxaestimada_$pc22_orcamitem"}     = null;
  			}
          }
        
          if(trim($pc01_descrmater)==""){

            $pc01_descrmater = $pc11_resum;
          }

          if($i==0){

            $camposelected = "vlrun_$pc22_orcamitem";
          }

      ?>
        <tr class='bordas_corp' width='15%'>
          <td align='center'  class='bordas_corp' width='15%'><?=$pc11_seq?></td>
          <? if ($lMostraLote) { ?>
            <td align='center'  class='bordas_corp' width='15%'><?= $lote ?></td>
          <? } ?>
          <td align='left'    class='bordas_corp' width='55%'><?=$pc01_descrmater?></td>
          <td align='left'    class='bordas_corp' width='70%'><?=stripslashes($pc11_resum)?></td>
					<td align='center'  class='bordas_corp'><? db_input("obs_$pc22_orcamitem",30,$Ipc23_obs,true,'text',$db_opcao,"onchange='document.form1.vlrun_$pc22_orcamitem.select();'"); ?> </td> 

      <?
        $dia = "pc23_validmin_".$pc22_orcamitem."_dia";
        $mes = "pc23_validmin_".$pc22_orcamitem."_mes";
        $ano = "pc23_validmin_".$pc22_orcamitem."_ano";

        $result_mater = $clpcmater->sql_record($clpcmater->sql_query_file(@$pc01_codmater,"pc01_obrigatorio,pc01_validademinima",null,""));
        
        if($clpcmater->numrows>0){

            db_fieldsmemory($result_mater,0);
        }

        $pc11_resum = stripslashes($pc11_resum) ? stripslashes($pc11_resum) : "&nbsp";

        ?>

         <td align='center' nowrap  class='bordas_corp'> 

        <?
          if ($pc01_validademinima == "t" and $pc01_obrigatorio == "t"){ ?>
              <table  width='100%'>
              <tr>
                <td nowrap width='10%'>*</td>
                <td nowrap> <? db_inputdata("pc23_validmin_$pc22_orcamitem",@$$dia,@$$mes,@$$ano,true,"text",$db_opcao); ?></td>
              <tr>
              </table>
              <script>document.form1.pc23_validmin_<?=$pc22_orcamitem ?>.className = 'valida';</script>";

        <? } elseif ($pc01_validademinima == "t" and $pc01_obrigatorio == "f"){
        ?>
              <table  width='100%'>
              <tr>
                <td nowrap width='10%' ></td>
                <td nowrap  ><? db_inputdata("pc23_validmin_$pc22_orcamitem",@$$dia,@$$mes,@$$ano,true,"text",$db_opcao); ?> </td>
              <tr>
              </table>
       <? } else {
        ?>
              <table  width='100%'>
                <tr >
                  <td nowrap  width='10%'>&nbsp;</td>
                  <td nowrap  > <? db_inputdata("pc23_validmin_$pc22_orcamitem",@$$dia,@$$mes,@$$ano,true,"text",$db_opcao); ?></td>
                </tr>
              </table>
        <? } ?>
        </td> 

        <?
            $qtd       = "qtde_$pc22_orcamitem";
            $vlrun     = "vlrun_$pc22_orcamitem";
            $valor     = "valor_$pc22_orcamitem";
            $qtdorcada = "pc23_quant_$pc22_orcamitem" ;

            $arr_vlnomesitens[$i]   = "vlrun_$pc22_orcamitem";
            $arr_valoresitens[$i]   = $pc11_vlrun;
            $arr_quantitens[$i]     = $pc11_quant;
            $arr_vtnomesitens[$i]   = "valor_$pc22_orcamitem";
            $arr_nomesBDI[$i]       = "bdi_$pc22_orcamitem";
            $arr_nomesEncargos[$i]  = "encargossociais_$pc22_orcamitem";
            $arr_taxasEstimadas[$i] = "taxaestimada_$pc22_orcamitem";

            if($clpcorcamval->numrows > 0 )
             if(strpos($$valor,".") == ""){
                $$valor .= ".00";
            }

            if(!isset($$qtd) || isset($$qtd) && $$qtd==''){
              $$qtd = $pc11_quant;
            }

            if ($db_opcao==2){

              if (!isset($somavalor)){
                $somavalor  = 0;
              }

              if (isset($$valor)){
                $somavalor += $$valor;
                $somavalor  = number_format($somavalor, $numdec, ".", "");
              }

              if (!isset($pc23_quant)){
                 $pc23_quant="";
              }

              ${"qtde_$pc22_orcamitem"} = $pc23_quant;
         
            }

        ?>
          <td align='center'  class='bordas_corp' width='15%'><?=$pc11_quant?></td>
          <td align='center'  class='bordas_corp' >
            <? db_input("qtde_$pc22_orcamitem",10,$Ipc23_quant,true,'text',$db_opcao,"onchange='js_verquant(this.name,this.value,$pc11_quant,$pc22_orcamitem,$flag);js_somavalor();'"); ?>
          </td>       
          <td align='center'  class='bordas_corp'>
            <? db_input("vlrun_$pc22_orcamitem",10,$Ipc23_valor,true,'text',$db_opcao,"onchange='js_calcvaltot(this.value,$pc22_orcamitem,this.name);js_passacampo(this.name,this.name.substr(0,6));js_somavalor();'"); ?>
          </td>       
          <td align='center'  class='bordas_corp' width='15%'>
            <? db_input("valor_$pc22_orcamitem", 10, $Ipc23_valor, true, 'text', $db_opcao, "onchange=\"js_calcvalunit(this.value, $pc22_orcamitem,this.name);js_passacampo(this.name,'obs_');js_somavalor();\""); ?>
          </td>
          <td align='center'  class='bordas_corp' width='15%'>
            <? db_input("bdi_$pc22_orcamitem",10,4,true,'text',$db_opcao,''); ?>
          </td>
          <td align='center'  class='bordas_corp' width='15%'>
            <? db_input("encargossociais_$pc22_orcamitem",10,$Ipc23_valor,true,'text',$db_opcao); ?>
          </td>
          <td align='center'  class='bordas_corp' width='15%'>
            <? db_input("taxaestimada_$pc22_orcamitem",6,4,true,'text',$db_opcao, "class='" . (!empty($lote) ? "lote_{$lote}" : "") . " taxa-extimada' onBlur='js_processar_lote(this);'"); ?>
          </td>
        </tr>
  <?
      }
    } else{
      ?>
        <tr>
          <td nowrap align='center'><b> Não existem itens para este orçamento. </b></td>
        </tr>
      <?
    }
  }
  
  ?>
      </table>
      <table border=1 align='right' >
        <tr>
          <td  class="bordas" align='right'>
            <b>Total:</b>
            <?
              if (isset($somavalor)){ 
                $somavalor = db_formatar($somavalor,"p","e","2");
              }
              db_input("somavalor",10,"",true,'text',3,"");
            ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<?
if(isset($camposelected) && $camposelected != ""){
  echo "
    <script>
      document.form1.$camposelected.select();
      document.form1.$camposelected.focus();
    </script>
  ";
}
?>
<script>
function js_importar(TouF){
  <?
  for($i=0;$i<count($arr_vlnomesitens);$i++){ ?>
    if(TouF==true){
      document.form1.<?=$arr_vlnomesitens[$i]?>.value = <?= trim($arr_valoresitens[$i]) ?>;
      document.form1.<?=$arr_vtnomesitens[$i]?>.value = <?= trim((round($arr_valoresitens[$i],2)*$arr_quantitens[$i])) ?>;
    }else{
      document.form1.<?=$arr_nomesBDI[$i]?>.value = '0.00';
      document.form1.<?=$arr_nomesEncargos[$i]?>.value = '0.00';
      document.form1.<?=$arr_vlnomesitens[$i]?>.value = '0.00';
      document.form1.<?=$arr_vtnomesitens[$i]?>.value = '0.00';
    }

  
  <? } ?>
}
</script>
</body>
</html>
