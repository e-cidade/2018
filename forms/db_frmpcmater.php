<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: compras
$clpcmater->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc04_descrsubgrupo");
$clrotulo->label("o56_elemento");
$clrotulo->label("pc03_codgrupo");
$clrotulo->label("pc04_codsubgrupo");
$clrotulo->label("pc01_validademinima");
$clrotulo->label("pc01_obrigatorio");
$vaiIframe = "";
?>
<script>
function js_troca(){
  document.form1.submit();
}
function js_executaIframe(val) {
  ele = document.form1.codeles.value;
  mat = document.form1.pc01_codmater.value;
  opc = <?=$db_opcao?>;
  pcmater0011.location.href = 'com1_pcmater0011.php?db_opcao='+opc+'&codigomater='+mat+'&codsubgrupo='+val+'&codele='+ele;
}
</script>
<form name="form1" method="post" action="">
<input type="hidden" name="codeles" value=<?=@$coluna?> >
<center>
<fieldset>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc01_codmater?>"> <?=@$Lpc01_codmater?> </td>
    <td> <? //db_input('pc01_codmater',6,$Ipc01_codmater,true,'text',$db_opcao,"readonly")
           // carlos  
           db_input('pc01_codmater',6,$Ipc01_codmater,true,'text',3,"");
           $pc01_id_usuario = db_getsession("DB_id_usuario");
           db_input('pc01_id_usuario',6,$Ipc01_id_usuario,true,'hidden',3,"");
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc01_descrmater?>"> <?=@$Lpc01_descrmater?>    </td>
    <td> <? db_input('pc01_descrmater',78,$Ipc01_descrmater,true,'text',$db_opcao,"") ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc01_complmater?>">       <?=@$Lpc01_complmater?>    </td>
    <td> <? db_textarea('pc01_complmater',0,75,$Ipc01_complmater,true,'text',$db_opcao,"") ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc01_libaut?>">       <?=@$Lpc01_libaut?>    </td>
    <td nowrap> <?
    $arrlibaut_truefalse = array('t'=>'Sim','f'=>'Não');
    db_select("pc01_libaut",$arrlibaut_truefalse,true,$db_opcao);
    ?>
    <?=$Lpc01_ativo?> 
    <?
    $arr_truefalse = array('f'=>'Não','t'=>'Sim');
    db_select("pc01_ativo",$arr_truefalse,true,$db_opcao);
    ?>  
    <?=$Lpc01_servico?>
    <?
    $x = array("f"=>"Não","t"=>"Sim");
    db_select("pc01_servico",$x,true,$db_opcao);
    ?>
    <?=$Lpc01_veiculo?>
    <?
    $aVeic = array("f"=>"Não","t"=>"Sim");
    db_select("pc01_veiculo",$aVeic,true,$db_opcao);
    ?>
    
    </td>
    <tr>
      <td>
        <?
        echo $Lpc01_fraciona;
        ?> 
      </td>
      <td>
        <?
        $aFrac = array("f"=>"Não","t"=>"Sim");
        db_select("pc01_fraciona",$aFrac,true,$db_opcao);
        ?> 
      </td>
    </tr>
     
    <tr>
      <td nowrap>
        <?
        echo $Lpc01_validademinima;
        ?>
      </td>   
      <td nowrap>
       <?
        $aValMin = array("f"=>"Não","t"=>"Sim");
        db_select("pc01_validademinima",$aValMin,true,$db_opcao);
        ?>
     
     
        <?
        echo $Lpc01_obrigatorio;

        $aPObrigatorio = array("f"=>"Não","t"=>"Sim");
        db_select("pc01_obrigatorio", $aPObrigatorio ,true,$db_opcao);
        ?>
        
        <?
        echo $Lpc01_liberaresumo;

        $aLiberarResumo = array("t" => "Sim", 
                                "f" => "Não");
        db_select("pc01_liberaresumo", $aLiberarResumo, true, $db_opcao);
        ?>
      </td>
    
  </tr>
  <tr>
    <td><?=$Lpc03_codgrupo?> </td>
    <td align='left'>
        <?
	  //com query_file na classe
	  /*
	  if (!isset($pc013_codgrupo)){ 
             if(isset($pc01_codsubgrupo) &&  ($db_opcao == 2 || $db_opcao == 3)){
                 global $pc03_codgrupo;
                 // echo "<script>alert('".$clpcsubgrupo->sql_query($pc01_codsubgrupo,"pc04_codgrupo as pc01_codgrupo")."'</script>";
                 $result = $clpcsubgrupo->sql_record($clpcsubgrupo->sql_query($pc01_codsubgrupo,"pc04_codgrupo as pc03_codgrupo"));
                 if ($clpcsubgrupo->numrows > 0 ){  
	            db_fieldsmemory($result,0);
	         }  
              }
	  }	    
          $result = $clpcgrupo->sql_record($clpcgrupo->sql_query(null,"pc03_codgrupo,pc03_descrgrupo","pc03_descrgrupo"));
          @db_selectrecord("pc03_codgrupo",$result,true,$db_opcao,"","","","0","js_troca(this.value);");
  */
     if (!isset($pc01_codgrupo)){
	    if (!isset($pc03_codgrupo)){ 
                if(isset($pc01_codsubgrupo) &&  ($db_opcao == 2 || $db_opcao == 3)){
                   global $pc01_codgrupo;
                   $result = $clpcsubgrupo->sql_record($clpcsubgrupo->sql_query($pc01_codsubgrupo,"pc04_codgrupo as pc01_codgrupo",null,"pc04_codsubgrupo=$pc01_codsubgrupo and pc04_ativo is true"));
                   if ($clpcsubgrupo->numrows > 0 ){  
	             db_fieldsmemory($result,0);
	           }  
                }
	    }	    
	  }
          $result = $clpcgrupo->sql_record($clpcgrupo->sql_query(null,"pc03_codgrupo,pc03_descrgrupo","pc03_descrgrupo","pc03_ativo is true"));
          @db_selectrecord("pc01_codgrupo",$result,true,$db_opcao,"","","","0","js_troca(this.value);");
        ?>  
    
    </td>
  </tr> 
   <? if(isset($pc01_codgrupo) || $db_opcao != 1) { ?>
     <tr>
       <td> <?=$Lpc04_codsubgrupo?> </td>
       <td align='left'>
        <?
           $sWhere = "pc04_codgrupo = ".@$pc01_codgrupo." and pc04_ativo is true and (pc04_tipoutil=1 or pc04_tipoutil=3)";
           if (!isset($pc01_codgrupo)) {
             $sWhere = "pc04_ativo is true and (pc04_tipoutil=1 or pc04_tipoutil=3)";
           }
           $result = $clpcsubgrupo->sql_record(
	           $clpcsubgrupo->sql_query(null,"pc04_codsubgrupo as subgrupo,pc04_descrsubgrupo",
  	 		                              "pc04_descrsubgrupo",
 			                                $sWhere));
           if($clpcsubgrupo->numrows > 0 ){
             
             db_fieldsmemory($result,0);
             $pc04_codsubgrupo=$subgrupo;             
             if(isset($impmater)){             	
               $result_coluna = $clpcmaterele->sql_record($clpcmaterele->sql_query_file($impmater,null,"pc07_codele"));
               $numrows_coluna = $clpcmaterele->numrows;
               $separa = "";
               $coluna = "";
               for($i=0;$i<$numrows_coluna;$i++){
               	 db_fieldsmemory($result_coluna,$i);
               	 $coluna .= $separa.$pc07_codele;
               	 $separa  = "XX";                	 
               }
               //db_msgbox($subgrupo);
               $vaiIframe = "?db_opcao=$db_opcao&codigomater=".$impmater."&impmater=impmater&codsubgrupo=".$subgrupo."&codele=".$coluna;
             }else{
             	//db_msgbox($subgrupo);
       		    //db_msgbox($pc01_codmater);
       		     if (!isset($pc01_codmater)) {
       		       $pc01_codmater = '';
       		     }
               $vaiIframe = "?db_opcao=$db_opcao&codigomater=".$pc01_codmater."&codsubgrupo=".@$subgrupo."&codele=".@$coluna;
             }             
           }  
           @db_selectrecord("pc01_codsubgrupo",$result,true,$db_opcao,"","","","","js_executaIframe(this.value)"); 

        ?>  
      </td>
      </tr> 
   <? }    ?>

   <tr>
		 <td>&nbsp;<td>
	 </tr>
   <tr>
    <td colspan="2" align="center">
    <div align="left"><b>Lista de desdobramentos<b></div>    
      <iframe width="630" height="200" name="pcmater0011" src="com1_pcmater0011.php<?=$vaiIframe?>"></iframe>
    </td>
  </tr>
  <?    
    if($db_opcao!=1){?>
  <tr>
    <td colspan=2 bgcolor="#CCFF99" align="center"><strong>***   Elementos que não podem ser <?=$db_opcao==2?" alterados ":" excluídos "?> por estar na autorização de empenho.</strong></td>
  </tr>
  <?}?>
  </table>
</fieldset>
  </center>

<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" disabled="disabled" <?=($db_opcao==2||$db_opcao==1?"onclick='return js_coloca();'":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  <?
  
  if($db_opcao==1){
  	$result_pcmater = $clpcmater->sql_record($clpcmater->sql_query_file());
  	if($clpcmater->numrows>0){
  	  echo "<input name='importar' type='button' id='importar' value='Importar material' onclick='js_janelaimporta();' >";
  	}
  }
  ?>
</form>
<script>
  function js_coloca(codele){
	obj = pcmater0011.document.form1;
	var coluna='';
	var sep=''; 

	for(i=0; i<obj.length; i++){
	  nome = obj[i].name.substr(0,10);  
	  if(nome=="o56_codele" && obj[i].checked==true){
	    coluna += sep+obj[i].value;
	    sep= "XX";
	  }
	} 
	document.form1.codeles.value = coluna;
	return true;
	//return coluna ;
  }

function js_pesquisapc01_codsubgrupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcsubgrupo','func_pcsubgrupo.php?funcao_js=parent.js_mostrapcsubgrupo1|pc04_codsubgrupo|pc04_descrsubgrupo','Pesquisa',true);
  }else{
     if(document.form1.pc01_codsubgrupo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcsubgrupo','func_pcsubgrupo.php?pesquisa_chave='+document.form1.pc01_codsubgrupo.value+'&funcao_js=parent.js_mostrapcsubgrupo','Pesquisa',false);
     }else{
       document.form1.pc04_descrsubgrupo.value = ''; 
     }
  }
}
function js_mostrapcsubgrupo(chave,erro){
  document.form1.pc04_descrsubgrupo.value = chave; 
  if(erro==true){ 
    document.form1.pc01_codsubgrupo.focus(); 
    document.form1.pc01_codsubgrupo.value = ''; 
  }
}
function js_mostrapcsubgrupo1(chave1,chave2){
  document.form1.pc01_codsubgrupo.value = chave1;
  document.form1.pc04_descrsubgrupo.value = chave2;
  db_iframe_pcsubgrupo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?funcao_js=parent.js_preenchepesquisa|pc01_codmater&vertudo=true','Pesquisa',true);
}
function js_preenchepesquisa(chave){

  document.form1.pc01_codmater.value = chave; 
  db_iframe_pcmater.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_janelaimporta(){
  qry = "&enviadescr=true";
  if(document.form1.pc01_descrmater.value!="" ){
    qry += "&chave_pc01_descrmater="+document.form1.pc01_descrmater.value;
  }
  js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?funcao_js=parent.js_enviacodmater|pc01_codmater|pc01_descrmater&vertudo=true'+qry,'Pesquisa',true);
}
function js_enviacodmater(chave,descr){
  db_iframe_pcmater.hide();
  if(chave!=""){
      obj=document.createElement('input');
      obj.setAttribute('name','impmater');
      obj.setAttribute('type','hidden');
      obj.setAttribute('value',chave);
      document.form1.appendChild(obj);
      document.form1.submit();
  }  
} 
  
  js_executaIframe(document.form1.pc01_codsubgrupo.value);
  
  <?
  if(isset($vaiIframe) && trim($vaiIframe)!=""){  	
  	//echo "pcmater0011.location.href = 'com1_pcmater0011.php".$vaiIframe."';";  	
  	echo "pcmater0011.document.form1.codsubgrupo.value=document.form1.pc01_codsubgrupo.value;
          pcmater0011.document.form1.submit();";
  }
  ?>
  
</script>