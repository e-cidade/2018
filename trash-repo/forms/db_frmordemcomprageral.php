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

//MODULO: empenho
$clempempenho->rotulo->label();
$clcgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");
$clrotulo->label("m51_obs");
$clrotulo->label("m51_prazoent");
$m51_prazoent = 3;
$sWhere       = ''; 
$evtCLick     = '';
$coddepto     = db_getsession("DB_coddepto");
$rsDepto      = pg_query("select descrdepto from db_depart where coddepto = $coddepto");
$oDepto       = db_utils::fieldsMemory($rsDepto,0);
$descrdepto   = $oDepto->descrdepto;

if (isset($listagem_empenhos) && $listagem_empenhos!='' ){	  

   $sWhere        = " e60_numemp in (".str_replace('_',',',$listagem_empenhos).")";
   $sSqlEmpenhos  = " select e60_numemp,   ";
   $sSqlEmpenhos .= "        e60_codemp, ";
   $sSqlEmpenhos .= "        e62_sequencial, ";
   $sSqlEmpenhos .= "        e62_item, ";
   $sSqlEmpenhos .= "        pc01_descrmater, ";
   $sSqlEmpenhos .= "  		   e62_sequen, ";
   $sSqlEmpenhos .= "  		   e62_vlrun, ";
   $sSqlEmpenhos .= "  		   e62_descr, ";
   $sSqlEmpenhos .= "  		   e62_servicoquantidade, ";
	 $sSqlEmpenhos .= "    	   pc01_servico,";
   $sSqlEmpenhos .= "    	   pc01_fraciona,";
   $sSqlEmpenhos .= "	       (select rnsaldoitem  from  fc_saldoitensempenho(e60_numemp, e62_sequencial)) as e62_quant,";
   $sSqlEmpenhos .= "	       (select round(rnsaldovalor,2) from fc_saldoitensempenho(e60_numemp, e62_sequencial)) as e62_vltot";
   $sSqlEmpenhos .= "   from empempenho ";
	 $sSqlEmpenhos .=	"         inner join empempitem on e62_numemp       = e60_numemp ";
	 $sSqlEmpenhos .=	"		      inner join pcmater    on pc01_codmater    = e62_item";
	 $sSqlEmpenhos .= " 	      inner join pcsubgrupo on pc04_codsubgrupo = pc01_codsubgrupo";
	 $sSqlEmpenhos .= "		      inner join pctipo     on pc05_codtipo     = pc04_codtipo";
	 $sSqlEmpenhos .= "   where {$sWhere} ";
	 $sSqlEmpenhos .= "   order by e60_numemp,e62_sequen";
   $rsEmpenho     = $clempempenho->sql_record($sSqlEmpenhos);
}

?>
<form name="form1" method="post" action="" onsubmit="js_buscavalores();">
<center>
<table border='0'>
  <tr>
    <td>
     <fieldset><legend><b>Dados da Ordem</b></legend>
      <table border="0">
	  <tr>
	    <td nowrap align="right" title="<?=@$Tm51_data?>"><b>Data:</b></td>
	    <td><?if(empty($m51_data_dia)){
		  $m51_data_dia =  date("d",db_getsession("DB_datausu"));
		  $m51_data_mes =  date("m",db_getsession("DB_datausu"));
		  $m51_data_ano =  date("Y",db_getsession("DB_datausu"));
		}
		db_inputdata('m51_data',@$m51_data_dia,@$m51_data_mes,@$m51_data_ano,true,'text',3);?>
	    </td>
	    <td nowrap align="right" title="<?=@$Tm51_prazoent?>"><?=@$Lm51_prazoent?></td>
	    <td><?db_input('m51_prazoent',6,$Im51_prazoent,true,'text',1)?></td>
	  <tr>
				<?
					$rsMatparam = $clmatparam->sql_record($clmatparam->sql_query_file());
					$oMatparam    = db_utils::fieldsMemory($rsMatparam,0);
					
					if ($oMatparam->m90_tipocontrol == "F") {

						if ($oMatparam->m90_almoxordemcompra == "2") {
								
							$aListaEmp = explode("_",$listagem_empenhos);	 						
							
							for ($i = 0; $i < count($aListaEmp); $i++) {
								$sSqlOrigemEmpenho = "select * from fc_origem_empenho({$aListaEmp[$i]})";
								$rsOrigemEmpenho   = pg_query($sSqlOrigemEmpenho) or die($sSqlOrigemEmpenho);
								for ($ii = 0; $ii < pg_num_rows($rsOrigemEmpenho); $ii++) {
									 $oOrigemEmpenho = db_utils::fieldsMemory($rsOrigemEmpenho,$ii);
									 $aDeptoEmp[]    = $oOrigemEmpenho->ridepto;
								}
							}			
								
							$rsAlmoxDepto = $cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query(null,null,"distinct m91_depto,a.descrdepto",null," m92_depto in (".implode(",",array_unique($aDeptoEmp)).")"));
							
							if ($cldb_almoxdepto->numrows > 1) {
								$rsAlmoxDepto = $cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query(null,null,"'0' as m91_depto, 'Nenhum' as descrdepto union all select distinct m91_depto,a.descrdepto",null," m92_depto in (".implode(",",array_unique($aDeptoEmp)).")"));
							}
							
							$iLinhasAlmox = $cldb_almoxdepto->numrows;
						
						}else{
							$rsAlmoxDepto = $cldb_almox->sql_record($cldb_almox->sql_query(null,"m91_depto,descrdepto"));
							$iLinhasAlmox = $cldb_almox->numrows;
						}

						if ($iLinhasAlmox == 0){
							db_msgbox("Sem Almoxarifados cadastrados!!");
							echo "<script>location.href='emp4_ordemcomprageral01.php';</script>";
						}
				?>	 
	    <td nowrap align="right" title="<?=@$descrdepto?>">
				<b><?=$Lcoddepto?></b>				
			</td>
	    <td>
				<?	 
					 db_selectrecord("coddepto",$rsAlmoxDepto,true,1);
				?>
			</td>
				<?
					}else{
				?>			
			<td nowrap align="right" title="<?=@$descrdepto?>">
				<?
						db_ancora(@$Lcoddepto,"js_coddepto(true);",1);
				?>
			</td>
			<td>
				<?			
						db_input('coddepto',6,$Icoddepto,true,'text',1," onchange='js_coddepto(false);'");
						db_input('descrdepto',35,$Idescrdepto,true,'text',3,'');
				  }
				?>
	    </td>
				<?
         $result_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit")));
         if ($clpcparam->numrows > 0) {
	           db_fieldsmemory($result_pcparam, 0);
						 if ($pc30_emiteemail == 't'){
							   echo "<script>\n";
								 echo " function testaEmail(){\n";
								 echo "     if (document.getElementById('manda_email').checked == true){\n";
                 echo "         alert('Os mails somente serão enviados para aqueles fornecedores devidamente\\n inscritos como usuários externos da Prefeitura');";
								 echo "     }\n";
								 echo "}\n";
								 echo "</script>\n";
								 $evtCLick = "testaEmail();";
								 ?>

              <td align="right"><input id='manda_email' name="manda_mail" type="checkbox" value="X"></td>
              <td nowrap><label for='manda_email'><b>Mandar e-mail para o fornecedor.</b></label></td>         
           <?//end if parametro
								}
							}
				?>		 
	  </tr>
	  <tr> 
	  <td align='right'><b>Obs:</b></td>
	    <td colspan='3' align='left'>
	   <? 
	   db_textarea("m51_obs","","110",$Im51_obs,true,'text',1);	 
	   ?>
	    </td>
	  </tr>  
    </table>
    </fieldset>
    </td>
    </tr>
	  <tr>
	    <td colspan='4' align='center'>
      <input name="incluir" type="submit"   value="Incluir" onclick=" return js_valida()">
		  <input name="voltar" type="button" value="Voltar" onclick="location.href='emp4_ordemcomprageral01.php';" >
	    </td>
	  </tr>
    <tr>
      <td>
         <fieldset><legend><b>Itens</b></legend>
           <table style='border:2px inset white' width='100%' cellspacing='0'>
             <tr>
               <td class='table_header' title='Marca/desmarca todos' align='center'>
                 <input type='checkbox'  style='display:none' id='mtodos' onclick='js_marca()'>
                	<a onclick='js_marca()' style='cursor:pointer'>M</a></b>
               </td>
               <td class='table_header' align='center'><b>Número</b></td>
               <td class='table_header' align='center'><b>Código</b></td>
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
             $sClassName = 'normal';
             $sChecked   = '';
             if ($clempempenho->numrows == 1) {
        
              $sChecked   = " checked ";
              $sClassName = " marcado ";
          
             }
             for ($i = 0; $i < $clempempenho->numrows; $i++) {

               $disabled    = null;
               $iOpcao      = 1;
               $sClassName  = "normal";
               $oEmpenho    = db_utils::fieldsmemory($rsEmpenho,$i,true);
               if ($oEmpenho->e62_vltot == 0  && $oEmpenho->e62_quant == 0) {

                 $disabled    = " disabled ";
                 $sChecked    =  '';
                 $iOpcao      = 3;
                 $sClassName  = "disabled";

               }
               
							 $pc01_fraciona = $oEmpenho->pc01_fraciona == 'f' ? "false" : "true";
               echo "<tr id='trchk{$oEmpenho->e62_sequencial}' class='{$sClassName}' style='height:1em'>";	    
               echo "  <td class='linhagrid' title='Inverte a marcação' align='center'>";
               echo "  <input type='checkbox' {$sChecked} {$disabled} id='chk{$oEmpenho->e62_sequencial}' class='itensEmpenho'";
               echo "    name='itensOrdem[]' value='{$oEmpenho->e62_sequencial}' onclick='js_marcaLinha(this)'></td>";
							 echo "  <td class='linhagrid' align='center'>";
												 db_ancora($oEmpenho->e60_codemp,"js_pesquisaEmpenho({$oEmpenho->e60_numemp});","1");
							 echo "  </td>";
               echo "  <td class='linhagrid'id='empenho{$oEmpenho->e62_sequencial}' align='center'>{$oEmpenho->e60_numemp}</td>";
               echo "  <td class='linhagrid' align='center'><small>{$oEmpenho->e62_item}</small></td>";		    
               echo "  <td class='linhagrid' nowrap align='left' title='$oEmpenho->pc01_descrmater'><small>".substr($oEmpenho->pc01_descrmater,0,20)."&nbsp;</small></td>";
               echo "  <td class='linhagrid' id='sequen{$oEmpenho->e62_sequencial}' align='center'>$oEmpenho->e62_sequen</td>";
               echo "  <td class='linhagrid' nowrap align='left' title='$oEmpenho->e62_descr'><small>".substr($oEmpenho->e62_descr,0,20)."&nbsp;</small></td>";
               echo "  <td class='linhagrid' align='center'>$oEmpenho->e62_quant</td>";
               echo "  <td class='linhagrid' align='center'>$oEmpenho->e62_vltot</td>";
               echo "  <td class='linhagrid' id='e62_vluni{$oEmpenho->e62_sequencial}'align='center'>";
               echo "  <input type='text' style='border:0px' readonly id='vlrunitario{$oEmpenho->e62_sequencial}' ";
               echo "         size='6' name='vlrunitario{$oEmpenho->e62_sequencial}' value='{$oEmpenho->e62_vlrun}'</td>";
               ${"quantidade{$oEmpenho->e62_sequencial}"} =  $oEmpenho->e62_quant;
               ${"valor{$oEmpenho->e62_sequencial}"}      =  $oEmpenho->e62_vltot;
               if ($oEmpenho->pc01_servico == 'f') {

                 echo "<td class='linhagrid' align='center'>";
                 db_input("quantidade{$oEmpenho->e62_sequencial}",6,0,true,
                           'text',$iOpcao,"onkeyPress='return js_validaFracionamento(event,{$pc01_fraciona},this)'
                            onchange='js_verifica($oEmpenho->e62_quant,this.value,this.name,$oEmpenho->e62_vlrun,$oEmpenho->e60_numemp,$oEmpenho->e62_sequencial);'"
                            ,'','','text-align:right');
                 echo "</td>
                      <td class='linhagrid' align='center'>";
                      db_input("valor{$oEmpenho->e62_sequencial}",6,0,true,'text',3,
                      "onkeyPress='return js_teclas(event)'",'','','text-align:right');
                  echo "</td>";
                  echo "</tr> ";
               } else {

                 /**
                  * Verifica se o serviço é controlado pela quantidade
                  */
                 if ($oEmpenho->e62_servicoquantidade == 't') {
                   $iControlaQuantidade = 1;
                   $iControlaValor      = 3;
                 } else {
                   $iControlaQuantidade = 3;
                   $iControlaValor      = 1;

                   if ($oEmpenho->e62_vltot <= 0  || $oEmpenho->e62_quant <= 0) {
                     $iControlaValor = $iOpcao;
                   }
                 }

                  echo"<td class='linhagrid' align='center'><small>";
                  db_input("quantidade{$oEmpenho->e62_sequencial}",6,0,true,'text',$iControlaQuantidade,"onkeyPress='return js_validaFracionamento(event,{$pc01_fraciona},this)'
                            onchange='js_verifica($oEmpenho->e62_quant,this.value,this.name,$oEmpenho->e62_vlrun,$oEmpenho->e60_numemp,$oEmpenho->e62_sequencial);'");
                  echo "</small></td>
                  <td class='linhagrid' align='center'><small>";
                  db_input("valor{$oEmpenho->e62_sequencial}",6,0,true,'text',$iControlaValor,"onkeyPress='return js_teclas(event)'",'','','text-align:right');
                  echo "</small></td>";
                  echo "</tr> ";

                }
            }
            ?>
            <tr style='height: auto'><td>&nbsp;</td>
            </tr>
            </tbody>
         </table>
      </td>
 </table>
</center>

</form>
<script>
  
	function js_pesquisaEmpenho(iNumEmp){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho001.php?e60_numemp='+iNumEmp,'Pesquisa',true);
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
    if(erro==true){ 
      document.form1.coddepto.focus(); 
      document.form1.coddepto.value = ''; 
    }
  }
  function js_buscavalores(){
   obj= itens.document.form1;
   valor="";
   valoritem='';
   for (i=0;i<obj.elements.length;i++){
     if (obj.elements[i].name.substr(0,6)=="quant_"){
       cheke=obj.elements[i].name.split("_");
       if (eval("obj.CHECK_"+cheke[1]+"_"+cheke[2]+".checked")==true){
	 var objvalor=new Number(obj.elements[i].value);
	 if (objvalor!=0){
	   valor+=obj.elements[i].name+"_"+obj.elements[i].value;
	 } 
       }else{
	 continue;
       }
     }
     if (obj.elements[i].name.substr(0,6)=="valor_"){
       objvaloritem=new Number(obj.elements[i].value);
       if (objvaloritem!=0){
	 valoritem+=obj.elements[i].name+"_"+obj.elements[i].value;
       } 
     }
   }
   document.form1.valores.value=valor;
   document.form1.val.value=valoritem;
  }
  function js_valida(){
  
		if (document.form1.coddepto.value == 0){
			alert('Escolha algum Almoxarifado!');
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
        nValor = new Number($('valor' + iItem).value);
        nQuantidade = new Number($('quantidade' + iItem).value);
        if (nValor == 0 || nQuantidade == 0) {
        
          iSequen  = $('sequen' + iItem).innerHTML
          iEmpenho = $('empenho' + iItem).innerHTML
          alert("Item " + iSequen + " do Empenho " + iEmpenho + " possui valores/quantidade inválidas.\nVerifique");
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
      
      $("valor"+sequencia).value = quan*valoruni;
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