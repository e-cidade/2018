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

//MODULO: saude
$oDaoLabFechamento->rotulo->label();
$clrotulo = new rotulocampo;

?>
<form name="form1" method="post" action="">
<center>
<fieldset style="width:80%"><legend><b>Fechamento de Competência</b></legend>
<table border="0" align="left">
   <tr>
    <td nowrap title="<?=@$Tla54_i_compmes?>">
       <b>Competência Mês/Ano:</b>
       <? db_input('la54_i_codigo', 15, $Ila54_i_codigo, true, 'hidden', $db_opcao2, "");?>
    </td> 
    <td> 
     <? db_input('la54_i_compmes', 15, $Ila54_i_compmes, true, 'text', $db_opcao, " onchange=\"js_descr();\" ");?>
	 </td>
	 <td>/</td>
	 <td>
	  <? db_input('la54_i_compano', 15, $Ila54_i_compano, true, 'text', $db_opcao, " onchange=\"js_descr();\" ");?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla54_d_ini?>">
       <b>Período de Fechamento :</b>
    </td>
    <td> 
     <? db_inputdata('la54_d_ini', @$la54_d_ini_dia, @$la54_d_ini_mes, @$la54_d_ini_ano, true, 'text', $db_opcao, 
                     "onchange=\"js_validadata();\"" ,"", "", "parent.js_validadata();");?>
	 </td>
	 <td>A</td>
	 <td>
	  <? db_inputdata('la54_d_fim', @$la54_d_fim_dia, @$la54_d_fim_mes, @$la54_d_fim_ano, true, 'text', $db_opcao,
	                  "onchange=\"js_validadata();\"", "", "", "parent.js_validadata();");?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla54_d_data?>">
       <?=@$Lla54_d_data?>
    </td>
    <td colspan="3"> 
      <? db_inputdata('la54_d_data',@$la54_d_data_dia,@$la54_d_data_mes,@$la54_d_data_ano,true,'text',3,"");?>
    </td>
  </tr>
  <tr>
    <td><b>Tipo Financiamnto:</b></td>
    <td colspan="3">
      <?$x = array();
        $sWhere = "sd65_i_anocomp=(select max(sd65_i_anocomp) from sau_financiamento) and sd65_i_mescomp=( 
                   select max(sd65_i_mescomp) from sau_financiamento where sd65_i_anocomp=(
                   select max(sd65_i_anocomp) from sau_financiamento))";
        $oDaoSauFinanciamento = db_utils::getdao('sau_financiamento');
        $sSql    = $oDaoSauFinanciamento->sql_query_file(null,
                                                         "sd65_i_codigo,sd65_c_financiamento||' - '||sd65_c_nome as descr",
                                                         "",
                                                         $sWhere);
        $rsDados = $oDaoSauFinanciamento->sql_record($sSql);
        $x[0]    = 'Todos';
        for ($iX = 0; $iX < $oDaoSauFinanciamento->numrows; $iX++) {
          
           $oDados                    = db_utils::fieldsmemory($rsDados,$iX);
           $x[$oDados->sd65_i_codigo] = $oDados->descr;

        }
        db_select('la54_i_financiamento',$x,true,$db_opcao,"");?></td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla54_c_descr?>">
       <?=@$Lla54_c_descr?>
    </td>
    <td colspan="3"> 
      <? db_input('la54_c_descr',35,$Ila54_c_descr,true,'text',$db_opcao,"");?>
    </td>
  </tr>
</table>
</fieldset>
</center>
 <center>
 <table>  
   <tr><td  width="30%">
   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
          type="submit" id="db_opcao" 
          value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
          <?=($db_botao==false?"disabled":"")?> >
   </td><td width="30%">
   <input name="cancelar" type="button" id="cancelar" value="Cancelar" 
     <?=($db_opcao==1||isset($incluir)?"disabled":"")?> onClick='location.href="lab4_fechacomp.php"'>
   </td></tr>
 </table>
 </center>
<center>
<table>
 <tr>
  <td valign="top">
  <?
        $chavepri= array("la54_i_codigo"=>@$la54_i_codigo);
        $sCampos = "la54_i_codigo,la54_i_compmes||'/'||la54_i_compano as la54_i_compmes,la54_d_ini,".
        $sCampos = "la54_d_fim,la54_i_financiamento,sd65_c_nome,la54_c_descr,la54_d_data,la54_c_hora,nome as la54_i_login "; 
        $cliframe_alterar_excluir->chavepri=$chavepri;
        $cliframe_alterar_excluir->sql = $oDaoLabFechamento->sql_query("",
                                                                      $sCampos,
                                                                      "la54_i_codigo desc");

        $cliframe_alterar_excluir->campos  ="la54_i_compmes,la54_d_ini,la54_d_fim,sd65_c_nome,la54_c_descr,la54_d_data,la54_c_hora,la54_i_login ";
        $cliframe_alterar_excluir->legenda="Registros";
        $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
        $cliframe_alterar_excluir->textocabec ="#DEB887";
        $cliframe_alterar_excluir->textocorpo ="#444444";
        $cliframe_alterar_excluir->fundocabec ="#444444";
        $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
        $cliframe_alterar_excluir->tamfontecabec = 9;
        $cliframe_alterar_excluir->tamfontecorpo = 9;
        $cliframe_alterar_excluir->formulario = false;
		$cliframe_alterar_excluir->iframe_width  = "630";
        $cliframe_alterar_excluir->iframe_height = "130";
		$cliframe_alterar_excluir->opcoes = 2;
        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
       ?>
  </td>
 </tr>
</table>
</center>
</form>
<script>
F=document.form1;
<?if(!isset($la54_c_descr)){?>
    js_descr();
<?}?>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sau_fechamento','func_sau_fechamento.php?funcao_js=parent.js_preenchepesquisa|sd97_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_fechamento.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_validadata(){
   if((F.la54_d_ini.value!='')&&(F.la54_d_fim.value!='')){
	   //verificar se uma data é maior que a outra
   }
}
function js_descr(){
	if((F.la54_i_compmes.value!='')&&(F.la54_i_compano.value!='')){
		if(parseInt(F.la54_i_compmes.value,10)>12){
            alert('Mês invalido!');
            F.la54_c_descr.value='';
			F.la54_i_compmes.value='';
			F.la54_i_compmes.focus();
			return false
		}
		aMes = new Array('JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ');
		F.la54_c_descr.value='COMP '+aMes[parseInt(F.la54_i_compmes.value,10)-1]+'/'+F.la54_i_compano.value;
	}
}
</script>