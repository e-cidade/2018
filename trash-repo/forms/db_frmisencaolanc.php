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

//MODULO: tributario
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clisencaolanc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k09_cadtipoitemgrupo");
$clrotulo->label("v10_isencaotipo");
if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $v18_cadtipoitem = "";
     //$v18_isencao = "";
     $v18_dtini_dia = "";
     $v18_dtfim_dia = "";
     $v18_dtini_mes = "";
     $v18_dtfim_mes = "";
     $v18_dtini_ano = "";
     $v18_dtfim_ano = "";
     $k09_cadtipoitemgrupo = "";
     $v18_tipovalor = "";
     $v18_valor = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap>
    </td>
    <td> 
<?
db_input('v18_sequencial',10,$Iv18_sequencial,true,'hidden',3,"")
?>
    </td>
   <tr>
    <td nowrap title="<?=@$Tv18_isencao?>">
       <?
       db_ancora(@$Lv18_isencao,"js_pesquisav18_isencao(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('v18_isencao',10,$Iv18_isencao,true,'text',3,"")
?>
       <?
db_input('v10_isencaotipo',10,$Iv10_isencaotipo,true,'hidden',3,'')
       ?>
    </td>
  </tr>
  </tr>
  <tr>
    <td nowrap title="">
		   <b> Grupo de débito : </b>
    </td>
       <?
			  // caso alteracao: a varivel $origem nao existe
        if((!isset($origem) || $origem == '') && $v18_isencao != ''){
					$sqlBuscaOrigem  = " select case  ";
					$sqlBuscaOrigem .= "          when v15_isencao is not null then '3' ";
					$sqlBuscaOrigem .= "          when v12_isencao is not null then '1' ";
					$sqlBuscaOrigem .= "          when v16_isencao is not null then '2' ";
					$sqlBuscaOrigem .= "        end as origem ";
					$sqlBuscaOrigem .= "   from isencao ";
					$sqlBuscaOrigem .= "    left join isencaomatric on v15_isencao = v10_sequencial ";
					$sqlBuscaOrigem .= "    left join isencaocgm    on v12_isencao = v10_sequencial ";
					$sqlBuscaOrigem .= "    left join isencaoinscr  on v16_isencao = v10_sequencial ";
					$sqlBuscaOrigem .= " where v10_sequencial = $v18_isencao ";
					$rsBuscaOrigem = pg_query($sqlBuscaOrigem);
					if(pg_numrows($rsBuscaOrigem) > 0){
					  db_fieldsmemory($rsBuscaOrigem,0);
					}
				}
  			if(isset($k03_tipo) && $k03_tipo != ''){
					$k03_tipoant = $k03_tipo;
       	}  
				if(isset($origem) && $origem != ''){
					$sqlCadtipo  = " select k03_tipo, ";
					$sqlCadtipo .= "        k03_descr ";
					$sqlCadtipo .= "   from cadtipo ";
					$sqlCadtipo .= "        inner join cadtipoitem   on k03_tipo = k09_cadtipo ";
					$sqlCadtipo .= "    	  inner join cadtipoorigem on k03_tipo = k14_cadtipo ";
					$sqlCadtipo .= " where k14_cadorigem = $origem "; //(filtro escolhido para lancar a isencao)
					$rsCadtipo   = pg_query($sqlCadtipo);
					$intCadtipo  = pg_numrows($rsCadtipo);
					if($intCadtipo > 0){
						for($i=0;$i<$intCadtipo;$i++){
							db_fieldsmemory($rsCadtipo,$i);
							$arraycadtipo[$k03_tipo] = $k03_descr;
						}
						if(isset($k03_tipoant) && $k03_tipoant != ''){
							$k03_tipo = $k03_tipoant;
						}
						echo "<td>";
						db_select("k03_tipo",$arraycadtipo,true,$db_opcao,"onchange='js_ajaxRequest(this);'");
						echo "</td>";
					}else{
						echo "<td bgcolor='#FFFFFF'>";
						echo "<b>* Sem grupo de débito configurado para a Origem selecionada. </b>";	
						echo "</td>";
						$db_opcao = 3;
						$db_botao = false;
				  }
				}else{
						echo "<td bgcolor='#FFFFFF'>";
						echo "<b>* Sem grupo de débito configurado para a Origem selecionada. </b>";	
						echo "</td>";
						$db_opcao = 3;
						$db_botao = false;
				}
        ?>
  </tr>
  <tr>
    <td nowrap title="<?=@$v18_cadtipoitem?>">
		  <b> Item da isenção : </b>
    </td>
       <?
  			if(isset($v18_cadtipoitem) && $v18_cadtipoitem != ''){
					$v18_cadtipoitemant = $v18_cadtipoitem;
       	}  
				if (isset($k03_tipo) && $k03_tipo != ''){
				  $sqlItem   = " select k09_sequencial, ";
				  $sqlItem  .= "        k37_descr ";
				  $sqlItem  .= "   from cadtipo ";
				  $sqlItem  .= "        inner join cadtipoitem      on k03_tipo = k09_cadtipo ";
				  $sqlItem  .= "        inner join cadtipoitemgrupo on k09_cadtipoitemgrupo = k37_sequencial ";
				  $sqlItem  .= " where k09_cadtipo = $k03_tipo";
				  $rsItem    = pg_query($sqlItem);
				  $intItem   = pg_numrows($rsItem);
					if($intItem > 0){
				    for($iItem=0;$iItem<$intItem;$iItem++){
					    db_fieldsmemory($rsItem,$iItem);
					    $arrayItem[$k09_sequencial] = $k37_descr;
				    }
						if(isset($v18_cadtipoitemant) && $v18_cadtipoitemant != ''){
              $v18_cadtipoitem = $v18_cadtipoitemant;
				    }
            echo "<td>";
            db_select("v18_cadtipoitem",$arrayItem,true,$db_opcao);
            echo "</td>";
					}else{
            echo "<td bgcolor='#FFFFFF'>";
					  echo "<b>* Sem item de isenção configurado para a Origem selecionada. </b>";	
            echo "</td>";
						$db_opcao = 3;
						$db_botao = false;
					}					
			  }else{
          echo "<td bgcolor='#FFFFFF'>";
				  echo "<b>* Sem item de isenção configurado para a Origem selecionada. </b>";	
          echo "</td>";
					$db_opcao = 3;
					$db_botao = false;
				}
        ?>
  </tr>
 <tr>
    <td nowrap title="<?=@$Tv18_dtini?>">
       <?=@$Lv18_dtini?>
    </td>
    <td> 
<?
db_inputdata('v18_dtini',@$v18_dtini_dia,@$v18_dtini_mes,@$v18_dtini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv18_dtfim?>">
       <?=@$Lv18_dtfim?>
    </td>
    <td> 
<?
db_inputdata('v18_dtfim',@$v18_dtfim_dia,@$v18_dtfim_mes,@$v18_dtfim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv18_tipovalor?>">
       <?=@$Lv18_tipovalor?>
    </td>
    <td> 
<?
$x = array('1'=>'PERCENTUAL','2'=>'VALOR');
db_select('v18_tipovalor',$x,true,$db_opcao,"onChange='js_controlavalor();'");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv18_valor?>">
       <?=@$Lv18_valor?>
    </td>
    <td> 
<?
db_input('v18_valor',10,$Iv18_valor,true,'text',$db_opcao,"onChange='js_controlavalor();'")
?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("v18_sequencial"=>@$v18_sequencial);
	 $cliframe_alterar_excluir->chavepri    = $chavepri;
	 $cliframe_alterar_excluir->iframe_nome = "itenslanc";
	 $cliframe_alterar_excluir->sql     = $clisencaolanc->sql_query(null,'*',null," v18_isencao = $v18_isencao ");
	 $cliframe_alterar_excluir->campos  = "v18_sequencial,k37_descr,v18_dtini,v18_dtfim,k09_sequencial,v18_valor";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script>
if(!document.form1.v18_cadtipoitem.value){
//  js_ajaxRequest(document.form1.k03_tipo.value);
}

//v18_tipovalor
function js_controlavalor(){
	if(document.form1.v18_tipovalor.value == 1 && document.form1.v18_valor.value > 100){
		alert('Valor percentual não pode ser maior que 100 !');
		document.form1.v18_valor.value = '';
		document.form1.v18_valor.focus();
  }	
}
function js_ajaxRequest(obj){
//	alert(val);
  var url       = 'tri4_carregadadosisencao.php';
  var parametro = 'cadtipo='+obj.value;
  var objAjax   = new Ajax.Request (url,{method:'post',parameters:parametro, onComplete:carregaDadosSelect});
	document.form1.v18_cadtipoitem.disabled = true;
}
function carregaDadosSelectxxx(resposta){
//  alert(resposta.responseXML+" -- "+resposta.responseText);
  alert(xmlArvore(resposta.responseXML,""));
}
function carregaDadosSelect(resposta){
	document.form1.v18_cadtipoitem.disabled = false;
	js_limpaSelect(document.form1.v18_cadtipoitem);  
	js_addSelectFromStr(resposta.responseText,document.form1.v18_cadtipoitem);
}
function js_limpaSelect(obj){
  obj.length  = 0;	
}
function js_addSelectFromStr(str,obj){
  var linhas  = str.split("|");
  for(i=0;i<linhas.length;i++){
		if(linhas[i] != ''){
		  colunas = linhas[i].split("-");
      obj.options[i] = new Option();
      obj.options[i].value = colunas[0];
      obj.options[i].text  = colunas[1];
		}
  }	
}

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisav18_cadtipoitem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_isencaolanc','db_iframe_cadtipoitem','func_cadtipoitem.php?funcao_js=parent.js_mostracadtipoitem1|k09_sequencial|k09_cadtipoitemgrupo','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.v18_cadtipoitem.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_isencaolanc','db_iframe_cadtipoitem','func_cadtipoitem.php?pesquisa_chave='+document.form1.v18_cadtipoitem.value+'&funcao_js=parent.js_mostracadtipoitem','Pesquisa',false);
     }else{
       document.form1.k09_cadtipoitemgrupo.value = ''; 
     }
  }
}
function js_mostracadtipoitem(chave,erro){
  document.form1.k09_cadtipoitemgrupo.value = chave; 
  if(erro==true){ 
    document.form1.v18_cadtipoitem.focus(); 
    document.form1.v18_cadtipoitem.value = ''; 
  }
}
function js_mostracadtipoitem1(chave1,chave2){
  document.form1.v18_cadtipoitem.value = chave1;
  document.form1.k09_cadtipoitemgrupo.value = chave2;
  db_iframe_cadtipoitem.hide();
}
function js_pesquisav18_isencao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_isencaolanc','db_iframe_isencao','func_isencao.php?funcao_js=parent.js_mostraisencao1|v10_sequencial|v10_isencaotipo','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.v18_isencao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_isencaolanc','db_iframe_isencao','func_isencao.php?pesquisa_chave='+document.form1.v18_isencao.value+'&funcao_js=parent.js_mostraisencao','Pesquisa',false);
     }else{
       document.form1.v10_isencaotipo.value = ''; 
     }
  }
}
function js_mostraisencao(chave,erro){
  document.form1.v10_isencaotipo.value = chave; 
  if(erro==true){ 
    document.form1.v18_isencao.focus(); 
    document.form1.v18_isencao.value = ''; 
  }
}
function js_mostraisencao1(chave1,chave2){
  document.form1.v18_isencao.value = chave1;
  document.form1.v10_isencaotipo.value = chave2;
  db_iframe_isencao.hide();
}
</script>