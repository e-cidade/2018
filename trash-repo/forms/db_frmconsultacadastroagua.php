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

$clrotulo = new rotulocampo;

$clrotulo->label("x01_matric");
$clrotulo->label("z01_nome");
$clrotulo->label("j14_codigo");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_codi");
$clrotulo->label("j13_descr");



?>
<script>
	function mostraJanelaPesquisa() {
		F = document.form1;
		if (F.x01_matric.value.length > 0) {
			VisualizacaoMatricula.jan.location.href = 'agu3_conscadastro_002.php?cod_matricula=' + F.x01_matric.value;
			VisualizacaoMatricula.mostraMsg();
			VisualizacaoMatricula.show();
			VisualizacaoMatricula.focus();
		} else if (F.z01_nome.value.length > 0) {
			VisualizacaoProprietario.jan.location.href = 'func_nome.php?funcao_js=parent.mostraTodasMatCad|0&nomeDigitadoParaPesquisa=' + F.z01_nome.value;
			VisualizacaoProprietario.mostraMsg();
			VisualizacaoProprietario.show();
			VisualizacaoProprietario.focus();	  
		} else if(F.j14_codigo.value.length > 0) {
			VisualizacaoRuas.jan.location.href = 'func_ruas.php?funcao_js=parent.mostraTodasMatriculas_PesquisaRuas|0&codrua=' + F.j14_codigo.value;
			VisualizacaoRuas.mostraMsg();
			VisualizacaoRuas.show();
			VisualizacaoRuas.focus();	  
		} else if(F.j14_nome.value.length > 0) {
			VisualizacaoNomeRuas.jan.location.href='func_ruas.php?funcao_js=parent.mostraTodasMatriculas_PesquisaRuas|0&nomerua='+ F.j14_nome.value;
			VisualizacaoNomeRuas.mostraMsg();
			VisualizacaoNomeRuas.show();
			VisualizacaoNomeRuas.focus();	  
		} else if(F.j13_codi.value.length > 0) {
			VisualizacaoBairros.jan.location.href = 'func_bairros.php?funcao_js=parent.mostraTodasMatriculas_PesquisaBairro|0&codbairro=' + F.j13_codi.value;
			VisualizacaoBairros.mostraMsg();
			VisualizacaoBairros.show();
			VisualizacaoBairros.focus();	  
		} else if(F.j13_descr.value.length > 0) {
			VisualizacaoNomeBairro.jan.location.href = 'func_bairros.php?funcao_js=parent.mostraTodasMatriculas_PesquisaBairro|0&nomeBairro=' + F.j13_descr.value;
			VisualizacaoNomeBairro.mostraMsg();
			VisualizacaoNomeBairro.show();
			VisualizacaoNomeBairro.focus();	  
		}
		F.reset();
	}
  
	function mostraTodasMatCad(numerocgm){
		VisualizacaoTodasMatCad.jan.location.href = 'agu3_conscadastro_003.php?pesquisaPorNome=' + numerocgm;
		VisualizacaoTodasMatCad.mostraMsg();
		VisualizacaoTodasMatCad.show();
		VisualizacaoTodasMatCad.focus();
	}
	
	function mostraJanelaDadosImovel(numeroMat){
		VisualizacaoMatricula.jan.location.href = 'agu3_conscadastro_002.php?cod_matricula=' + numeroMat;
		VisualizacaoMatricula.mostraMsg();
		VisualizacaoMatricula.show();
		VisualizacaoMatricula.focus();	  
	}

	function mostraTodasMatriculas_PesquisaRuas(rua){
		VisualizacaoRuas.jan.location.href = 'agu3_conscadastro_003.php?pesquisaRua=' + rua;
		VisualizacaoRuas.mostraMsg();
		VisualizacaoRuas.show();
		VisualizacaoRuas.focus();	  
	}
	
	function mostraTodasMatriculas_PesquisaBairro(bairro){
		VisualizacaoBairros.jan.location.href = 'agu3_conscadastro_003.php?pesquisaBairro=' + bairro;
		VisualizacaoBairros.mostraMsg();
		VisualizacaoBairros.show();
		VisualizacaoBairros.focus();	  
	}
	function submitEnter(event) {
		key = event.keyCode;
		if(key == 13)
		{
			mostraJanelaPesquisa();
		}	
	}
</script>
<table width="80%" border="0" align="left" >
	<tr>
		<td align="left" valign="top">
		<form name="form1" method="post" action="">
		<table width="107%" border="0" >
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td nowrap>&nbsp;</td>
				<td nowrap title="<?=@$Tx01_matric?>"><?=@$Lx01_matric?></td>
				<td nowrap>
					<input type="text" name="x01_matric" id="x01_matric" value="" size="10" maxlength="10" autocomplete= "off" title="Matr�cula do Im�vel Campo:x01_matric" onkeyUp="js_ValidaCampos(this,1,'Matr�cula','f','f',event);" onkeyDown="return submitEnter(event);" />
				</td>
			</tr>
			<tr>
				<td width="3%" nowrap>&nbsp;</td>
				<td nowrap title="<?=@$Tz01_nome?>"><?=@$Lz01_nome?></td>
				<td nowrap>
					<input type="text" name="z01_nome" id="z01_nome" value="" size="50" maxlength="40" autocomplete="off" title="Nome da pessoa ou Razao Social se for Empresa Campo:z01_nome" onblur="js_ValidaMaiusculo(this,'t',event);" onkeyup="js_ValidaCampos(this,3,'Nome/Raz�o Social','f','t',event);" onkeydown="return submitEnter(event);" style="text-transform: uppercase;" />
				</td>
			</tr>
			<tr>
				<td nowrap>&nbsp;</td>
				<td nowrap title="<?=@$Tj14_codigo?>"><?=@$Lj14_codigo?></td>
				<td nowrap>
					<input type="text" name="j14_codigo" id="j14_codigo" value="" size="10" autocomplete="off" maxlength="7" title="C�digo do logradouro cadastrado no sistema Campo:j14_codigo" onkeydown="return submitEnter(event);" onkeyup="js_ValidaCampos(this,1,'C�digo Logradouro','f','f',event);" />
				</td>
			</tr>
			<tr>
				<td nowrap>&nbsp;</td>
				<td nowrap title="<?=@$Tj14_nome?>"><?=@$Lj14_nome?></td>
				<td nowrap>
					<input type="text" name="j14_nome" id="j14_nome" value="" size="50" maxlength="40" autocomplete="off" title="Descricao do logradouro do municipio Campo:j14_nome" onblur="js_ValidaMaiusculo(this,'t',event);" onkeydown="return submitEnter(event);" onkeyup="js_ValidaCampos(this,0,'Logradouro','f','t',event);" style="text-transform: uppercase;" />
				</td>
			</tr>
			<tr>
				<td nowrap>&nbsp;</td>
				<td nowrap title="<?=@$Tj13_codi?>"><?=@$Lj13_codi?></td>
				<td nowrap>
					<input type="text" name="j13_codi" id="j13_codi" value="" size="10" maxlength="6" autocomplete="off" onkeydown="return submitEnter(event);" onkeyup="js_ValidaCampos(this,1,'C�d. do Bairro','t','f',event);" style="background-color: rgb(230, 228, 241);" title="C�digo do bairro Campo:j13_codi" />
				</td>
			</tr>
			<tr>
				<td nowrap>&nbsp;</td>
				<td nowrap title="<?=@$Tj13_descr?>"><?=@$Lj13_descr?></td>
				<td nowrap>
				<?
				  //db_input('j13_descr',50,$Ij13_descr,true,'text',1);
				?>
				<input type="text" name="j13_descr" id="j13_descr" value="" size="50" maxlength="40" autocomplete="off" onkeydown="return submitEnter(event);" onkeyup="js_ValidaCampos(this,0,'Bairro','f','t',event);" onblur="js_ValidaMaiusculo(this,'t',event);" style="text-transform: uppercase;" title="Descri��o do bairro Campo:j13_descr"/>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="left" valign="top" nowrap>&nbsp;</td>
			</tr>
			<tr align="center">
				<td colspan="3">
					<input name="pesquisar" type="button" onClick="mostraJanelaPesquisa()" id="pesquisar" value="Pesquisar">
				</td>
			</tr>
		</table>
		</form>
		</td>
	</tr>
</table>