<?php
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


/**
 * Cadastro de ordem de compras por empenho
 * 
 * @package compras
 * @author dbluizmarcelo Revisão $Author: dbricardo.lopes $Author: dbricardo.lopes $
 * @version $Revision: 1.30 $
*/

//MODULO: empenho
include("classes/db_db_almox_classe.php");
include("classes/db_db_almoxdepto_classe.php");
$cldb_almox = new cl_db_almox;
$cldb_almoxdepto = new cl_db_almoxdepto;
$clempempenho->rotulo->label();
$clcgm->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");
$clrotulo->label("m51_obs");
$clrotulo->label("m51_prazoent");

$where   = " 1=1 ";
$where1  = "";
$where2  = "";
$pesqemp = false;

$coddepto = db_getsession("DB_coddepto");
$instit   = db_getsession("DB_instit");

$resultdepto = db_query("select descrdepto from db_depart where coddepto = $coddepto");
db_fieldsmemory($resultdepto,0);

$m51_prazoent = 3;

if (isset($e60_numcgm) && $e60_numcgm!=''){
  $where   = "e60_numcgm = $e60_numcgm ";
}
if (isset($e60_numemp) && $e60_numemp!='' ){
  $where1  = " and e60_numemp = $e60_numemp ";
  $pesqemp = true;
}

if (isset($e60codemp) && $e60_codemp){
  $where2  = " and e60_codemp = '$e60_codemp' ";
  $pesqemp = true;
}

if((isset($e60_numcgm) && $e60_numcgm!='')||(isset($e60_numemp) && $e60_numemp!='' )||(isset($e60_codemp) && $e60_codemp)){

  //rotina que traz os dados do empenho
  $result = $clempempenho->sql_record($clempempenho->sql_query_empnome(null,"*","","$where $where1 $where2")); 
  db_fieldsmemory($result,0,true);
  //fim  

}    

if ($lBloquear) {
	$pesqemp = false;
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
<form name="form1" method="post" action="" >
<center>
<table border='0'>
<tr>
<td>
  <fieldset><legend><b>Dados da Ordem</b></legend>
<table border="0">
<tr>
<td nowrap align="right" title="<?=@$Te60_numcgm?>"><?=@$Le60_numcgm?></td>
<td><?db_input('e60_numcgm',20,$Ie60_numcgm,true,'text',3)?></td>
<td nowrap align="right" title="<?=@$z01_nome?>"><?=@$Lz01_nome?></td>
<td><?db_input('z01_nome',45,$Iz01_nome,true,'text',3)?></td>
</tr>
<tr>
<td nowrap align="right" title="<?=@$Tz01_cgccpf?>"><?=@$Lz01_cgccpf?></td>
<td><?db_input('z01_cgccpf',20,$Iz01_cgccpf,true,'text',3)?></td>
<td nowrap align="right" title="<?=@$z01_email?>"><?=@$Lz01_email?></td>
<td nowrap><?db_input('z01_email',45,$Iz01_email,true,'text',3)?>
<input name="Alterar CGM" type="button" id="alterarcgm" value="Alterar CGM" 
       onclick="js_AlteraCGM(document.form1.e60_numcgm.value);" <?=$sDisable?>>
</td>                    
</tr>
<?
$result_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit")));
if ($clpcparam->numrows > 0) {
  db_fieldsmemory($result_pcparam, 0);
  
  if(($pc30_importaresumoemp == 't') and ($clempempenho->numrows == 1)) {
    $m51_obs = $e60_resumo;
  }
  
  if ($pc30_emiteemail == 't'){
    $sSql = "select usuext 
      from db_usuarios u
      inner join db_usuacgm c on u.id_usuario = c.id_usuario
      where cgmlogin = $z01_numcgm";
    $rs = db_query($sSql);						
    if (pg_num_rows($rs) > 0){
      db_fieldsmemory($rs,0);
      if ($usuext == 1){

        ?>

          <tr>
          <td nowrap></td>
          <td nowrap></td>
          <td align="right"><input id='manda_email' name="manda_mail" type="checkbox" value="X"></td>
          <td nowrap><label for='manda_email'><b>Mandar e-mail para o fornecedor.</b></label></td>         
          </tr>
          <?//end if parametro
      }
    }
  }
}
?>		 
<tr>
<td nowrap align="right" title="<?=@$z01_ender?>"><?=@$Lz01_ender?></td>
<td><?db_input('z01_ender',30,"$Iz01_ender",true,'text',3);if (@$z01_numero!=0){db_input('z01_numero',4,@$Iz01_numero,true,'text',3);}?></td>
<td nowrap align="right"   title="<?=@$Tz01_compl?>"><?=@$Lz01_compl?></td>
<td><?db_input('z01_compl',20,$Iz01_compl,true,'text',3)?></td>
</tr>
<tr>
<td nowrap align="right" title="<?=@$Tz01_munic?>"><?=@$Lz01_munic?></td>
<td><?db_input('z01_munic',30,$Iz01_munic,true,'text',3)?></td>
<td nowrap align="right"   title="<?=@$Tz01_cep?>"><?=@$Lz01_cep?></td>
<td><?db_input('z01_cep',20,$Iz01_cep,true,'text',3)?></td>
</tr>
<tr>
<td nowrap align="right" title="<?=@$Tz01_telef?>"><?=@$Lz01_telef?></td>
<td><?db_input('z01_telef',20,$Iz01_telef,true,'text',3)?></td>
<td nowrap align="right" title="<?=@$Tm51_prazoent?>"><?=@$Lm51_prazoent?></td>
<td><?db_input('m51_prazoent',6,$Im51_prazoent,true,'text',$dbopcao)?></td>
</tr>
<tr>
<td nowrap align="right" title="<?=@$Tm51_data?>"><b>Data:</b></td>
<td><?if(empty($m51_data_dia)){
  $m51_data_dia =  date("d",db_getsession("DB_datausu"));
  $m51_data_mes =  date("m",db_getsession("DB_datausu"));
  $m51_data_ano =  date("Y",db_getsession("DB_datausu"));
}
db_inputdata('m51_data',@$m51_data_dia,@$m51_data_mes,@$m51_data_ano,true,'text',3);?>
</td>
<?
$result_matparam=$clmatparam->sql_record($clmatparam->sql_query_file());
if ($clmatparam->numrows>0){
  db_fieldsmemory($result_matparam,0);
  if($m90_tipocontrol=='F'){

    echo "<td nowrap align='right' title='Almox'><b>Almoxarifado :</b></td>";
    
		if ($m90_almoxordemcompra == "2") {
			
			$sSqlOrigemEmpenho = "select * from fc_origem_empenho($e60_numemp)";
			$rsOrigemEmpenho   = db_query($sSqlOrigemEmpenho) or die($sSqlOrigemEmpenho);
			
			for ($i = 0; $i < pg_num_rows($rsOrigemEmpenho); $i++) {
				$oOrigemEmpenho = db_utils::fieldsMemory($rsOrigemEmpenho,$i);
			  $aDeptoEmp[]	  = $oOrigemEmpenho->ridepto; 		
			}
			
			$rsAlmox = $cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query(null,null,"distinct m91_depto,a.descrdepto",null," m92_depto in (".implode(",",array_unique($aDeptoEmp)).") and a.instit = $instit")); 

			if ($cldb_almoxdepto->numrows > 1){			
				$rsAlmox    = $cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query(null,null,"'0' as m91_depto, 'Nenhum' as descrdepto union all select distinct m91_depto,a.descrdepto",null," m92_depto in (".implode(",",array_unique($aDeptoEmp)).") and a.instit = $instit")); 
			}
			
			$iLinhasAlmox = $cldb_almoxdepto->numrows;
		
		}else{
			
			$rsAlmox			= $cldb_almox->sql_record($cldb_almox->sql_query(null,"m91_depto,descrdepto",null,"db_depart.instit = $instit"));
			$iLinhasAlmox = $cldb_almox->numrows;
		
		}
		
    if ($iLinhasAlmox == 0){
      db_msgbox("Sem Almoxarifados cadastrados!!");
      echo "<script>location.href='emp4_ordemCompra001.php';</script>";
    }
    
		echo "<td>";
			db_selectrecord("coddepto",$rsAlmox,true,$dbopcao);
    echo "</td>";

  }else{
    ?>
      <td nowrap align="right" title="<?=@$descrdepto?>"><?db_ancora(@$Lcoddepto,"js_coddepto(true);",1);?></td>
      <td><?db_input('coddepto',6,$Icoddepto,true,'text',$dbopcao," onchange='js_coddepto(false);'");
    db_input('descrdepto',35,$Idescrdepto,true,'text',3,'');?>
      </td>
      <?
  }
}else{
  ?>
    <td nowrap align="right" title="<?=@$descrdepto?>"><?db_ancora(@$Lcoddepto,"js_coddepto(true);",$dbopcao);?></td>
    <td><?db_input('coddepto',6,$Icoddepto,true,'text',$dbopcao," onchange='js_coddepto(false);'");
  db_input('descrdepto',35,$Idescrdepto,true,'text',3,'');?>
    </td>
    <?}?>
    </tr>
    <tr> 
    <td align='right'><b>Obs:</b></td>
    <td colspan='3' align='left'>
    <? 
    db_textarea("m51_obs","","110",$Im51_obs,true,'text',$dbopcao);

    ?>
    </td>

    </tr>  
    <tr> 
    <td colspan='4' align='center'></td>
    </tr> 
    </table>
    </fieldset>
    </td>
    </tr>
    <tr>
    <td colspan='4' align='center'>
    <?if ($e60_numcgm!=""){
      $result=db_query("select * from empempenho inner join empempitem on e62_numemp = e60_numemp inner join pcmater on pc01_codmater = e62_item where e60_numcgm=$e60_numcgm");
      if (pg_numrows($result)>0){?>
        <input name="incluir" type="submit"  value="Incluir" onclick=" return js_valida()" <?=$sDisable?>>
          <input name="voltar" type="button" value="Voltar" onclick="location.href='emp4_ordemCompra001.php';" <?=$sDisable?>>
          <?}else{?>
            <input name="incluir" type="submit" disabled  value="Incluir" onclick=" return js_valida()" <?=$sDisable?>>
              <input name="voltar" type="button" value="Voltar" onclick="location.href='emp4_ordemCompra001.php';" <?=$sDisable?>>
              <?}
    }else{?>
      <input name="incluir" type="submit" disabled  value="Incluir" onclick=" return js_valida()" <?=$sDisable?>>
        <input name="voltar" type="button" value="Voltar" onclick="location.href='emp4_ordemCompra001.php';" <?=$sDisable?>><?}?>
        </td>
      
    </tr>
<tr>
<td align='center' valign='top' colspan='2'>
<fieldset><legend><b>Dados da Ordem</b></legend>
<?
if($pesqemp == true){
  ?>  
    <table border='0' cellspacing="0" cellpadding="0" 
    style='border:2px inset white' width='100%' bgcolor="white">
    <tr class=''>
    <td class='table_header' title='Marca/desmarca todos' align='center'>
     <input type='checkbox'  style='display:none' id='mtodos' onclick='js_marca()'>
     	<a onclick='js_marca()' style='cursor:pointer'>M</a></b></td>
    <td class='table_header' align='center'><b>Número do Empenho</b></td>
    <td class='table_header' align='center'><b>Seq .Empenho</b></td>
    <td class='table_header' align='center'><b>Cod. Item</b></td>
    <td class='table_header' align='center'><b>Item</b></td>
    <td class='table_header' align='center'><b>Sequencia</b></td>
    <td class='table_header' align='center'><b>Descrição</b></td>
    <td class='table_header' align='center'><b>Quantidade</b></td>
    <td class='table_header' align='center'><b>Valor Total</b></td>
    <td class='table_header' align='center'><b>Vlr. Uni.</b></td>
    <td class='table_header' align='center'><b>Quantidade</b></td>
    <td class='table_header' align='center'><b>Valor</b></td>         
    <td class='table_header' style='width:18px' align='center'><b>&nbsp;</b></td> 
    </tr>
    <tbody id='dados' style='height:150;width:95%;overflow:scroll;overflow-x:hidden;background-color:white'>
    <?


    if ((isset($e60_numcgm) && $e60_numcgm!= "")){

      $where="";
      $where1=""; 
      if (isset($e60_numemp)){
        $where = "and e60_numemp = $e60_numemp";
      }

      if (isset($e60_codemp)){
        $where1 = "and e60_codemp = '$e60_codemp'";
      }    
      $sSQLemp  = "select e60_numemp, ";
      $sSQLemp .= "       e60_codemp, ";
      $sSQLemp .= "       e62_item, ";
      $sSQLemp .= "       pc01_descrmater, ";
      $sSQLemp .= "       e62_sequen, ";
      $sSQLemp .= "	      e62_descr, ";
      $sSQLemp .= "	      e62_vlrun, ";
      $sSQLemp .= "       e62_sequencial,";   
      $sSQLemp .= "	      pc01_servico,";
      $sSQLemp .= "	      pc01_fraciona,";
      $sSQLemp .= "	     (select rnsaldoitem  from  fc_saldoitensempenho(e60_numemp, e62_sequencial)) as e62_quant,";
      $sSQLemp .= "	     (select round(rnsaldovalor,2) from fc_saldoitensempenho(e60_numemp, e62_sequencial)) as e62_vltot,";
      $sSQLemp .= "	      e62_servicoquantidade";
      $sSQLemp .= "  from empempenho ";
      $sSQLemp .= "       inner join empempitem on e62_numemp       = e60_numemp ";
      $sSQLemp .= "       inner join pcmater    on pc01_codmater    = e62_item";
      $sSQLemp .= "       inner join pcsubgrupo on pc04_codsubgrupo = pc01_codsubgrupo";
      $sSQLemp .= "       inner join pctipo     on pc05_codtipo     = pc04_codtipo";
      $sSQLemp .= " where e60_numcgm = {$e60_numcgm} {$where} {$where1}";
      $sSQLemp .="  order by e60_numemp";
      $result   = db_query($sSQLemp);
      $numrows  = pg_num_rows($result);
      $sClassName = 'normal';
      $sChecked   = '';
      if ($numrows == 1) {
        
        $sChecked   = " checked ";
        $sClassName = " marcado ";
      }
      
      for ($i = 0; $i < $numrows; $i++) {

        $disabled    = null;
        $iOpcao      = 1;
        $sClassName  = "normal";
        db_fieldsmemory($result,$i);

        if ($e62_vltot <= 0  || $e62_quant <= 0){
          
          $disabled   = " disabled ";
          $sChecked   =  '';
          $iOpcao     = 3;
          $sClassName  = "disabled";
        }
        echo "<tr id='trchk{$e62_sequencial}' class='{$sClassName}'>";	    
        echo "  <td class='linhagrid' title='Inverte a marcação' align='center'>";
        echo "  <input type='checkbox' {$sChecked} {$disabled} id='chk{$e62_sequencial}' class='itensEmpenho'";
        echo "    name='itensOrdem[]' value='{$e62_sequencial}' onclick='js_marcaLinha(this)'></td>";
        echo "  <td class='linhagrid' align='center'>";
					 				db_ancora($e60_codemp,"js_pesquisaEmpenho({$e60_numemp});","1"); 
				echo "	</td>";
        echo "  <td class='linhagrid'id='empenho{$e62_sequencial}' align='center'>$e60_numemp</td>";
        echo "  <td class='linhagrid' align='center'><small>$e62_item  </small></td>";		    
        echo "  <td class='linhagrid' id='e62_descr{$e62_sequencial}' nowrap align='left' title='$pc01_descrmater'><small>".substr($pc01_descrmater,0,20)."&nbsp;</small></td>";
        echo "  <td class='linhagrid' id='sequen{$e62_sequencial}' align='center'>$e62_sequen</td>";
        echo "  <td class='linhagrid' nowrap align='left' title='$e62_descr'><small>".substr($e62_descr,0,20)."&nbsp;</small></td>";
        echo "  <td class='linhagrid' id='e62_quant{$e62_sequencial}' align='center'>$e62_quant</td>";
        echo "  <td class='linhagrid' id='e62_vltot{$e62_sequencial}'align='center'>$e62_vltot</td>";
        echo "  <td class='linhagrid' id='e62_vluni{$e62_sequencial}'align='center'>";
        echo "  <input type='text' style='border:0px' readonly id='vlrunitario{$e62_sequencial}' ";
        echo "         size='6' name='vlrunitario{$e62_sequencial}' value='{$e62_vlrun}'</td>";
        ${"quantidade{$e62_sequencial}"} =  $e62_quant;
        ${"valor{$e62_sequencial}"}      =  $e62_vltot;
        if ($pc01_servico == 'f') {
          
          $pc01_fraciona = $pc01_fraciona == 'f' ? "false" : "true";
          echo"<td class='linhagrid' align='center'>";
          db_input("quantidade{$e62_sequencial}",6,0,true,
              'text',$iOpcao,"onkeyPress='return js_validaFracionamento(event,{$pc01_fraciona},this)'
               onchange='js_verifica($e62_quant,this.value,this.name,$e62_vlrun,$e60_numemp,$e62_sequencial);'"
              ,'','','text-align:right');
          echo "</td>
            <td class='linhagrid' align='center'>";
          db_input("valor{$e62_sequencial}",6,0,true,'text',3,
              "onkeyPress='return js_teclas(event)' 
              onchange='js_verifica($e62_vltot,this.value,this.name,$e62_vlrun,$e60_numemp,$e62_sequencial)'",'','','text-align:right');
          echo "</td>";
          echo "</tr> ";
        } else {

          $sStyle = 'text-align:right';
          /**
           * Verifica se o serviço é controlado pela quantidade
           */
          if ($e62_servicoquantidade == 't') {
            $iControlaQuantidade = 1;
            $iControlaValor      = 3;
          } else {
            $iControlaQuantidade = 3;
            $iControlaValor      = 1;

            if ($e62_vltot <= 0  || $e62_quant <= 0) {
              $iControlaValor = $iOpcao;
            }
          }
          
          if ($e62_vltot <= 0  || $e62_quant <= 0){
          	$iControlaQuantidade = 3;
          	$iControlaValor      = 3;
          }

          echo"<td class='linhagrid' align='center'><small>";
          db_input("quantidade{$e62_sequencial}",6,0,true,'text',$iControlaQuantidade, "onchange='js_verifica($e62_quant,this.value,this.name,$e62_vlrun,$e60_numemp,$e62_sequencial);'", '', '', $sStyle);
          echo "</small></td>
          <td class='linhagrid' align='center'><small>";
          db_input("valor{$e62_sequencial}",6,0,true,'text',$iControlaValor,
                   "onkeyPress='return js_teclas(event)';",'','','text-align:right');
          echo "</small></td>";
          echo "</tr> ";
        }
       }

    }
    ?>
    <tr style='height: auto'><td>&nbsp;</td>
            </tr>
     </tbody>   
     </table>
     
     <?
    }
  ?>  
    </td>
    </tr>
    </table>
    </center>
    <?
    db_input("valores",100,0,true,"hidden",3);
    db_input("val",100,0,true,"hidden",3);
  ?>
    </form>
</center>
    <script>


	function js_pesquisaEmpenho(iNumEmp){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho001.php?e60_numemp='+iNumEmp,'Pesquisa',true);
  }

	function js_AlteraCGM(cgm) {
      js_OpenJanelaIframe('','db_iframe_altcgm','prot1_cadcgm002.php?chavepesquisa='+cgm+'&testanome=true&autoc=true','Altera Cgm',true);
  }
  
	function js_coddepto(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostracoddepto1|coddepto|descrdepto','Pesquisa',true);
    }else{
      coddepto = document.form1.coddepto.value;
      if(coddepto!=""){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+coddepto+'&funcao_js=parent.js_mostracoddepto','Pesquisa',false);
      }else{ 	
        document.form1.descrdepto.value='';
      } 	
    }
  }
  function js_mostracoddepto1(chave1,chave2){
    document.form1.coddepto.value = chave1;
    document.form1.descrdepto.value = chave2;
    db_iframe_db_depart.hide();
  }
  function js_mostracoddepto(chave,erro){
    
    document.form1.descrdepto.value = chave; 
    if (erro){ 
    
      document.form1.coddepto.focus(); 
      document.form1.coddepto.value = ''; 
      
    }
  }
  function js_valida(){
      
    
		if (document.form1.coddepto.value == 0){
			alert("Favor escolha algum almoxarifado!");
			return false;
		}
		
    //buscamos todos os itens marcados pelo usuário, e validos ele.
	var itensOrdem = js_getElementbyClass(form1,"itensEmpenho");
    
    itensMarcados  = new Number(0);
    for (i = 0; i < itensOrdem.length; i++) {
      
      if (itensOrdem[i].checked) {
      
        //codigo do item
        iItem = itensOrdem[i].value;
        //valor do item (identificamos pela string "valor" seguido do sequencial do empenho.
       var nValor        = new Number($('valor' + iItem).value);
       var nQuantidade   = new Number($('quantidade' + iItem).value);
       var nValorEmpenho = new Number($('e62_vltot' + iItem).innerHTML);
       var nQteEmpenho   = new Number($('e62_quant' + iItem).innerHTML);
       if ( nValor > nValorEmpenho || nQuantidade > nQteEmpenho || (nValor == 0 || nQuantidade == 0) ) {
        
          var iSequen  = js_stripTags($('e62_descr' + iItem).innerHTML);
          var iEmpenho = $('empenho' + iItem).innerHTML
           alert("Item (" + iSequen + ") do Empenho " + iEmpenho + " possui valores/quantidade inválidas.\nVerifique");
           return false;
        } else {
          itensMarcados++;
        }
      }  
    }
    if (itensMarcados == 0) {
      alert("Não há itens Selecionados.\nVerifique.");
      return false;
    } else {
      return true;
    }
  

	}
  
  function js_verifica(max,quan,nome,valoruni,numemp,sequencia){
    if (max<quan){
      
      alert("Informe uma quantidade valida!!");
      eval("document.form1."+nome+".value='';");
      eval("document.form1."+nome+".focus();");
      
    } else{
      $("valor"+sequencia).value = round(new Number(quan * valoruni), 2);
    }
  }
function js_marca(){
  
	 obj = document.getElementById('mtodos');
	 if (obj.checked){
		 obj.checked = false;
	}else{
		 obj.checked = true;
	}
   itens = js_getElementbyClass(form1,'itensEmpenho');
	 for (i = 0;i < itens.length;i++){
     if (itens[i].disabled == false){
        if (obj.checked == true){
					itens[i].checked=true;
          js_marcaLinha(itens[i]);
       }else{
					itens[i].checked=false;
          js_marcaLinha(itens[i]);
			 }
     }
	 }
}
function js_marcaLinha(obj){
 
  if (obj.checked){
   $('tr'+obj.id).className='marcado';
  }else{
   $('tr'+obj.id).className='normal';
  }

}
</script>