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

//MODULO: configuracoes
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cldb_layoutlinha->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db50_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb51_codigo?>">
      <?=@$Ldb51_codigo?>
    </td>
    <td colspan="3"> 
	  <?
	 	db_input('db51_codigo',10,$Idb51_codigo,true,'text',3,"");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb51_layouttxt?>">
      <?
        db_ancora(@$Ldb51_layouttxt,"js_pesquisadb51_layouttxt(true);",3);
      ?>
    </td>
    <td colspan="3"> 
	  <?
	  
		db_input('db51_layouttxt',6,$Idb51_layouttxt,true,'text',3," onchange='js_pesquisadb51_layouttxt(false);'");
	    db_input('db50_descr',50,$Idb50_descr,true,'text',3,'');
	    
		if($importalinha == true){
		  db_input('codigoimporta',5,0,true,'hidden',3,'');
		}
		
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb51_descr?>">
      <?=@$Ldb51_descr?>
    </td>
    <td colspan="3"> 
	  <?
	    db_input('db51_descr',59,$Idb51_descr,true,'text',$db_opcao,"");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb51_tipolinha?>">
      <?=@$Ldb51_tipolinha?>
    </td>
    <td> 
	  <?
		$x = array('1'=>'1 - Header de arquivo','2'=>'2 - Header de lote','3'=>'3 - Registro','4'=>'4 - Trailler de lote','5'=>'5 - Trailler de arquivo');
		db_select('db51_tipolinha',$x,true,$db_opcao,"");
	  ?>
    </td>
    <td nowrap title="<?=@$Tdb51_tamlinha?>">
      <?=@$Ldb51_tamlinha?>
    </td>
    <td> 
	  <?
	    db_input('db51_tamlinha',6,$Idb51_tamlinha,true,'text',$db_opcao,"");
	  ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tdb51_separador?>">
      <?=@$Ldb51_separador?>
    </td>
    <td> 
	  <?
	    db_input('db51_separador',6,$Idb51_separador,true,'text',$db_opcao,"");
	  ?>
    </td>
    <td nowrap title="<?=@$Tdb51_compacta?>">
      <?=@$Ldb51_compacta?>
    </td>
    <td> 
	  <?
	  
	  	if (isset($db51_compacta)) {
	  	  if ($db51_compacta == "f") {
	  		$db51_compacta = 0;
	  	  } else {
	  	  	$db51_compacta = 1;
	  	  }
	  	}
	  
		$aCompacta = array('0'=>'Não',
				  		   '1'=>'Sim');
		
		db_select('db51_compacta',$aCompacta,true,$db_opcao,"");
		
	  ?>
    </td>    
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tdb51_obs?>">
      <?=@$Ldb51_obs?>
    </td>
    <td colspan="3"> 
	  <?
		db_textarea('db51_obs',4,56,$Idb51_obs,true,'text',$db_opcao,"");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb51_linhasantes?>">
      <?=@$Ldb51_linhasantes?>
    </td>
    <td> 
	  <?
	    db_input('db51_linhasantes',6,$Idb51_linhasantes,true,'text',$db_opcao,"");
	  ?>
    </td>
    <td nowrap title="<?=@$Tdb51_linhasdepois?>">
      <?=@$Ldb51_linhasdepois?>
    </td>
    <td> 
	  <?
	    db_input('db51_linhasdepois',6,$Idb51_linhasdepois,true,'text',$db_opcao,"");
	  ?>
    </td>
  </tr>
  <tr>
    <td colspan="4" align="center">
      <input name="<?=($db_opcao==1||$importalinha==true?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1||$importalinha==true?($importalinha==false?"Incluir":"Importar linha"):($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
      <?if($db_opcao==1 && $importalinha == false){?>
      <input name="importar" type="button" id="importar" value="Importar linha" onclick="js_importarlinha();" >
      <?}else{?>
        <?if($importalinha == true){?>
        <input name="importarcampos" type="submit" id="importarcampos" value="Importar linha / campos">
        <?}?>
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();">
      <?}?>
    </td>
  </tr>
</table>
<table>
  <tr>
    <td valign="top"  align="center">  
    <?
    $dbwhere = " db51_layouttxt = ".@$db51_layouttxt;
    if(isset($db51_codigo) && trim($db51_codigo) != ""){
      $dbwhere .= " and db51_codigo <> ".$db51_codigo;
    }
    $chavepri= array("db51_codigo"=>@$db51_codigo);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->sql     = $cldb_layoutlinha->sql_query_file(null,
                                                                        "
                                                                         db51_codigo,
           							         db51_descr,
																 db51_obs,
           							         case db51_tipolinha when 1 then '".$x[1]."'
           							                             when 2 then '".$x[2]."'
           							                             when 3 then '".$x[3]."'
           							                             when 4 then '".$x[4]."'
           							                             when 5 then '".$x[5]."'
           							         end as db51_tipolinha,
                                                                         db51_tamlinha
           							        ",
           							        "db51_tipolinha",
           							        $dbwhere);
    $val = 1;
    if($db_opcao==3){
      $val = 4;
    }
    $cliframe_alterar_excluir->opcoes = $val;
    $cliframe_alterar_excluir->campos  ="db51_descr,db51_tipolinha,db51_tamlinha,db51_obs";
    $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
    $cliframe_alterar_excluir->iframe_height ="160";
    $cliframe_alterar_excluir->iframe_width ="700";

    $cliframe_alterar_excluir->iframe_alterar_excluir(1);
    ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_cancelar(){
  location.href = 'con1_db_layoutlinha001.php?db51_layouttxt=<?=@$db51_layouttxt?>';
}
function js_pesquisadb51_layouttxt(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_layoutlinha','db_iframe_db_layouttxt','func_db_layouttxt.php?funcao_js=parent.js_mostradb_layouttxt1|db50_codigo|db50_descr','Pesquisa',true);
  }else{
    if(document.form1.db51_layouttxt.value != ''){ 
      js_OpenJanelaIframe('top.corpo.iframe_db_layoutlinha','db_iframe_db_layouttxt','func_db_layouttxt.php?pesquisa_chave='+document.form1.db51_layouttxt.value+'&funcao_js=parent.js_mostradb_layouttxt','Pesquisa',false);
    }else{
      document.form1.db50_descr.value = ''; 
    }
  }
}
function js_mostradb_layouttxt(chave,erro){
  document.form1.db50_descr.value = chave; 
  if(erro==true){ 
    document.form1.db51_layouttxt.focus(); 
    document.form1.db51_layouttxt.value = ''; 
  }
}
function js_mostradb_layouttxt1(chave1,chave2){
  document.form1.db51_layouttxt.value = chave1;
  document.form1.db50_descr.value = chave2;
  db_iframe_db_layouttxt.hide();
}
function js_importarlinha(){
  js_OpenJanelaIframe('top.corpo.iframe_db_layoutlinha','db_iframe_db_layoutlinha','func_db_layoutlinha.php?funcao_js=parent.js_mostradb_layoutlinha|db51_codigo','Pesquisa',true,0);
}
function js_mostradb_layoutlinha(chave){
  location.href = "con1_db_layoutlinha001.php?db51_layouttxt=<?=$db51_layouttxt?>&chave_pesquisa="+chave;
}
</script>