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

include ("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clpcandpadrao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc46_depart");
$clrotulo->label("pc44_descr");
$clrotulo->label("descrdepto");
if (isset ($opcao) && $opcao == "incluir") {
	$db_opcao = 1;
}else if (isset ($opcao) && $opcao == "alterar") {
	if (isset($pc45_codigo)&&$pc45_codigo!=""){
	   $result_info=$clpcandpadrao->sql_record($clpcandpadrao->sql_query($pc45_codigo));
	   db_fieldsmemory($result_info,0);
	    $result_depto=$clpcandpadraodepto->sql_record($clpcandpadraodepto->sql_query($pc45_codigo));
	    if ($clpcandpadraodepto->numrows>0){
	    	db_fieldsmemory($result_depto,0);
	    }	   
	}
	  
		$db_opcao = 2;
}else if (isset ($opcao) && $opcao == "excluir") {
	if (isset($pc45_codigo)&&$pc45_codigo!=""){
	   $result_info=$clpcandpadrao->sql_record($clpcandpadrao->sql_query($pc45_codigo));
	   db_fieldsmemory($result_info,0);
	    $result_depto=$clpcandpadraodepto->sql_record($clpcandpadraodepto->sql_query($pc45_codigo));
	    if ($clpcandpadraodepto->numrows>0){
	    	db_fieldsmemory($result_depto,0);
	    }	   
	}
			$db_opcao = 3;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc45_codigo?>">
       <?=@$Lpc45_codigo?>
    </td>
    <td> 
<?


		db_input('pc45_codigo', 5, $Ipc45_codigo, true, 'text', 3, "");
?>      
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc45_pctipoandam?>">
       <?=@$Lpc45_pctipoandam?>
    </td>
    <td> 
<?


		$result_pctipoandam = $clpctipoandam->sql_record($clpctipoandam->sql_query_file());
		if (isset ($pc45_pctipoandam) && $pc45_pctipoandam != "") {
			echo "<script>document.form1.pc45_pctipoandam.selected=$pc45_pctipoandam</script>";
		}
		db_selectrecord('pc45_pctipoandam', $result_pctipoandam, true, $db_opcao);
?>      
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc46_depart?>">
       <?


		db_ancora(@ $Lpc46_depart, "js_pesquisapc46_depart(true);", $db_opcao);
?>
    </td>
    <td> 
    <?


		db_input('pc46_depart', 5, $Ipc46_depart, true, 'text', $db_opcao, " onchange='js_pesquisapc46_depart(false);'");
?>
       <?



		db_input('descrdepto', 40, $Idescrdepto, true, 'text', 3, '')
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc45_dias?>">
       <?=@$Lpc45_dias?>
    </td>
    <td> 
<?

 db_input('pc45_dias', 5, $Ipc45_dias, true, 'text', $db_opcao, "")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc45_ordem?>">
       <?=@$Lpc45_ordem?>
    </td>
    <td> 
<?

 db_input('pc45_ordem', 5, $Ipc45_ordem, true, 'text', $db_opcao, "")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc45_instit?>">
       <?=@$Lpc45_instit?>
    </td>
    <td> 
<?
 $result_instit=$cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit")));
 db_fieldsmemory($result_instit,0);
 $pc45_instit=$codigo;
 db_input('pc45_instit', 5, $Ipc45_instit, true, 'text', 3, "");
 db_input('nomeinst', 40, "", true, 'text', 3, "");
?>
    </td>
  </tr>
  
  <tr>
    <td align="center" colspan="2">
      <input name="dbopcao" type="submit" id="dbopcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?

 $chavepri = array ("pc45_codigo" => @ $pc45_codigo, "pc45_ordem" => @ $pc45_ordem,"pc46_depart"=>@$pc46_depart);
		$cliframe_alterar_excluir->chavepri = $chavepri;
		$cliframe_alterar_excluir->campos = "pc45_codigo,pc46_depart,descrdepto,pc45_dias,pc45_ordem,pc45_pctipoandam,pc44_descr";
		$cliframe_alterar_excluir->sql = $clpcandpadrao->sql_query_depto("",  "*", "pc45_ordem", "pc45_instit = $pc45_instit");
		$cliframe_alterar_excluir->legenda = "Andamento Padrão";
		$cliframe_alterar_excluir->msg_vazio = "<font size='1'>Nenhum andamento Cadastrado!</font>";
		$cliframe_alterar_excluir->textocabec = "darkblue";
		$cliframe_alterar_excluir->textocorpo = "black";
		$cliframe_alterar_excluir->fundocabec = "#aacccc";
		$cliframe_alterar_excluir->fundocorpo = "#ccddcc";
		$cliframe_alterar_excluir->iframe_height = "170";
		$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
?>
   </td>
 </tr>  
  </table>
  </center>
</form>
<script>
function js_pesquisapc46_depart(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostrapc46_depart1|coddepto|descrdepto','Pesquisa',true);
    }else{
      pc46_depart = document.form1.pc46_depart.value;
      if(pc46_depart!=""){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+pc46_depart+'&funcao_js=parent.js_mostrapc46_depart','Pesquisa',false);
      }else{ 	
	document.form1.descrdepto.value='';
      } 	
    }
  }
  function js_mostrapc46_depart1(chave1,chave2){
    document.form1.pc46_depart.value = chave1;
    document.form1.descrdepto.value = chave2;
    db_iframe_db_depart.hide();
  }
  function js_mostrapc46_depart(chave,erro){
    document.form1.descrdepto.value = chave; 
    if(erro==true){ 
      document.form1.pc46_depart.focus(); 
      document.form1.pc46_depart.value = ''; 
  }
}
</script>