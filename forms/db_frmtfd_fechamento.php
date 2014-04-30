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
$oDaoTfdFechamento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");

?>
	<form class="container" name="form1" method="post" action="">
<center>
		<fieldset><legend><b>Fechamento de Competência</b></legend>
<table class="form-container">
				<tr>
					<td nowrap title="<?=@$Ttf32_i_mescompetencia?>">
					<b>Competência Mês/Ano:</b>
					<? db_input('tf32_i_codigo', 15, $Itf32_i_codigo, true, 'hidden', $db_opcao2, "");?>
					</td> 
						<td> 
						<? db_input('tf32_i_mescompetencia', 2, $Itf32_i_mescompetencia, true, 'text', $db_opcao, 
						" onchange=\"js_descr();\" ");?>
						/
						<? db_input('tf32_i_anocompetencia', 4, $Itf32_i_anocompetencia, true, 'text', $db_opcao, 
						"onchange=\"js_descr();\" ");?>
						</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Ttf32_d_datainicio?>">
					<b>Período de Fechamento :</b>
					</td>
						<td> 
						<? db_inputdata('tf32_d_datainicio', @$tf32_d_datainicio_dia, @$tf32_d_datainicio_mes, @$tf32_d_datainicio_ano,
						true, 'text', $db_opcao, "onchange=\"js_validadata();\"","","","parent.js_validadata();"
						);?>
						À
<?
	    				db_inputdata('tf32_d_datafim', @$tf32_d_datafim_dia, @$tf32_d_datafim_mes, @$tf32_d_datafim_ano, true, 'text',
	    				$db_opcao, "onchange=\"js_validadata();\"", "", "", "parent.js_validadata();"
	    				);
?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Ttf32_d_datasistema?>">
					<?=@$Ltf32_d_datasistema?>
					</td>
						<td colspan="3"> 
						<? db_inputdata('tf32_d_datasistema',@$tf32_d_datasistema_dia,@$tf32_d_datasistema_mes,@$tf32_d_datasistema_ano,
						true,'text',3,""
						);?>
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
          
										$oDados                    = db_utils::fieldsmemory($rsDados, $iX);
										$x[$oDados->sd65_i_codigo] = $oDados->descr;
        }
										db_select('tf32_i_financiamento',$x,true,$db_opcao,"");?></td>
				</tr>
				<tr>
						<td nowrap title="<?=@$Ttf32_c_descr?>">
						<?=@$Ltf32_c_descr?>
						</td>
							<td colspan="3"> 
							<? db_input('tf32_c_descr',64,$Itf32_c_descr,true,'text',$db_opcao,"");?>
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
   <input name="cancelar" 
          type="button" 
          id="cancelar" 
          value="Cancelar" 
          <?=($db_opcao==1||isset($incluir)?"disabled":"")?> 
          onClick='location.href="tfd4_tfd_fechamento001.php"'>
   </td></tr>
</table>
</center>
<center>
<table>
 <tr>
  <td valign="top">
  <?
    $chavepri                     = array("tf32_i_codigo"=>@$tf32_i_codigo);
    $sCampos                      = " tf32_i_mescompetencia||'/'||tf32_i_anocompetencia as tf32_i_mescompetencia,";
    $sCampos                     .= " tf32_d_datafim,tf32_c_descr,tf32_i_codigo,tf32_i_financiamento,sd65_c_nome, "; 
    $sCampos                     .= " nome,tf32_d_datasistema,tf32_c_horasistema,tf32_d_datainicio ";
    $oIframeAltExc->chavepri      = $chavepri;
    $oIframeAltExc->sql           = $oDaoTfdFechamento->sql_query("", $sCampos, "tf32_i_codigo desc limit 1");
    $oIframeAltExc->campos        = "tf32_i_mescompetencia,sd65_c_nome,tf32_d_datainicio,tf32_d_datafim,";
    $oIframeAltExc->campos       .= "tf32_c_descr,tf32_d_datasistema,tf32_c_horasistema,nome ";
    $oIframeAltExc->legenda       ="Registros";
    $oIframeAltExc->msg_vazio     ="Não foi encontrado nenhum registro.";
    $oIframeAltExc->textocabec    ="#DEB887";
    $oIframeAltExc->textocorpo    ="#444444";
    $oIframeAltExc->fundocabec    ="#444444";
    $oIframeAltExc->fundocorpo    ="#eaeaea";
    $oIframeAltExc->tamfontecabec = 9;
    $oIframeAltExc->tamfontecorpo = 9;
    $oIframeAltExc->formulario    = false;
    $oIframeAltExc->iframe_width  = "630";
    $oIframeAltExc->iframe_height = "130";
    $oIframeAltExc->opcoes        = 2;
    $oIframeAltExc->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</center>
</form>
<script>
<?if (!isset($tf32_c_descr)) {?>
    js_descr();
<?}?>
function js_validadata() {

   if ((document.form1.tf32_d_datainicio.value!='')&&(document.form1.tf32_d_datafim.value!='')) {
	   //verificar se uma data é maior que a outra
   }

}

function js_descr() {

	if ((document.form1.tf32_i_mescompetencia.value!='')&&(document.form1.tf32_i_anocompetencia.value!='')) {

		if (parseInt(document.form1.tf32_i_mescompetencia.value,10)>12) {  

      alert('Mês invalido!');
      document.form1.tf32_c_descr.value='';
			document.form1.tf32_i_mescompetencia.value='';
			document.form1.tf32_i_mescompetencia.focus();
			return false;

		}
		aMes = new Array('JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ');
		
    /*Retorna a descrição*/
    document.form1.tf32_c_descr.value='COMP '+aMes[parseInt(document.form1.tf32_i_mescompetencia.value, 10)-1]+
                                      '/'+document.form1.tf32_i_anocompetencia.value;
	}
}
</script>