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
?>
<script>
  function mostraJanelaPesquisa() {
    F = document.form1;
	  if(F.numInscricao.value.length > 0) {
	    frameDadosInscricao.jan.location.href = 'iss3_consinscr003.php?numeroDaInscricao=' + F.numInscricao.value;
	    frameDadosInscricao.mostraMsg();
	    frameDadosInscricao.show();
      frameDadosInscricao.focus();
    } else if(F.referenciaanterior.value.length > 0) {
      frameDadosInscricao.jan.location.href = 'iss3_consinscr003.php?referenciaanterior=' + F.referenciaanterior.value;
      frameDadosInscricao.mostraMsg();
      frameDadosInscricao.show();
      frameDadosInscricao.focus();
	  } else if(F.razaoSocial.value.length > 0) {
      frameListaRazaoSocial.jan.location.href = 'func_nome.php?funcao_js=parent.mostraTodasInscricoes_PesquisaPorNome|0&nomeDigitadoParaPesquisa=' + F.razaoSocial.value;
	    frameListaRazaoSocial.mostraMsg();
	    frameListaRazaoSocial.show();
	    frameListaRazaoSocial.focus();
	  } else if(F.escritorio.value.length > 0) {
      frameEscritorio.jan.location.href = 'func_escritorio.php?funcao_js=parent.mostraTodasInscricoes_PesquisaEscritorio|0&nomeDigitadoParaPesquisa=' + F.escritorio.value;
	    frameEscritorio.mostraMsg();
	    frameEscritorio.show();
	    frameEscritorio.focus();
	  } else if(F.codRua.value.length > 0) {
      frameListaRuas.jan.location.href = 'func_ruas.php?funcao_js=parent.mostraTodasInscricoes_PesquisaRuas|0&codrua=' + F.codRua.value;
	    frameListaRuas.mostraMsg();
	    frameListaRuas.show();
	    frameListaRuas.focus();
	  } else if(F.nomeRua.value.length > 0) {
      frameListaRuas.jan.location.href = 'func_ruas.php?funcao_js=parent.mostraTodasInscricoes_PesquisaRuas|0&nomeRua=' + F.nomeRua.value;
	    frameListaRuas.mostraMsg();
	    frameListaRuas.show();
	    frameListaRuas.focus();
	  } else if(F.codBairro.value.length > 0) {
      frameListaBairros.jan.location.href = 'func_bairros.php?funcao_js=parent.mostraTodasInscricoes_PesquisaBairro|0&codbairro=' + F.codBairro.value;
	    frameListaBairros.mostraMsg();
	    frameListaBairros.show();
	    frameListaBairros.focus();
	  } else if(F.nomeBairro.value.length > 0) {
      frameListaBairros.jan.location.href = 'func_bairros.php?funcao_js=parent.mostraTodasInscricoes_PesquisaBairro|0&nomeBairro=' + F.nomeBairro.value;
	    frameListaBairros.mostraMsg();
	    frameListaBairros.show();
	    frameListaBairros.focus();
	  } else if(F.atividade.value.length > 0) {
      frameListaAtividades.jan.location.href = 'func_atividades.php?funcao_js=parent.mostraTodasInscricoes_PesquisaAtividades|0&nomeDigitadoParaPesquisa=' + F.atividade.value;
	    frameListaAtividades.mostraMsg();
	    frameListaAtividades.show();
	    frameListaAtividades.focus();
	  } else if(F.socios.value.length > 0) {
      frameListaSocios.jan.location.href = 'func_socios.php?funcao_js=parent.mostraTodasInscricoes_PesquisaSocios|0&nomeDigitadoParaPesquisa='+F.socios.value;
	    frameListaSocios.mostraMsg();
	    frameListaSocios.show();
	    frameListaSocios.focus();
	  } else if(F.fantasia.value.length > 0) {
      js_OpenJanelaIframe('top.corpo','frameListaFantasia','func_nomefantasia.php?funcao_js=parent.mostraTodasInscricoes_PesquisaFantasia|0&nomeDigitadoParaPesquisa='+F.fantasia.value,'Pesquisa',true,23);
		
      /*  frameListaFantasia.jan.location.href = 'func_nomefantasia.php?funcao_js=parent.mostraTodasInscricoes_PesquisaFantasia|0&nomeDigitadoParaPesquisa='+F.fantasia.value;
  		alert(F.fantasia.value);
  	  frameListaFantasia.mostraMsg();
  	  frameListaFantasia.show();
  	  frameListaFantasia.focus();*/
	  } else if(F.matriculaImovel.value.length > 0) {
      js_OpenJanelaIframe('top.corpo','frameListaMatriculaImovel','iss3_consinscr002.php?pesquisaMatriculaImovel=' + F.matriculaImovel.value,'Lista Matricula',true,23);
	  
	  } else if ((F.setor.value.length > 0) || (F.quadra.value.length > 0) || (F.lote.value.length > 0)) {
	  
	    js_OpenJanelaIframe('top.corpo','frameListaSetQuaLot','func_iptubase.php?'
    	                     +'funcao_js=parent.mostraTodasInscricoes_PesquisaSetQuaLot'
    	                     +'&j34_setor=' + F.setor.value 
    	                     +'&j34_quadra=' + F.quadra.value
    	                     +'&j34_lote=' + F.lote.value
    	                     +'&PesquisaSetQuaLot=1','Pesquisa',true,23);
	  }
	  F.reset();
  }

  function mostraDadosInscricao(numeroissqn){
    frameDadosInscricao.jan.location.href = 'iss3_consinscr003.php?numeroDaInscricao=' + numeroissqn;
    frameDadosInscricao.mostraMsg();
    frameDadosInscricao.show();
	  frameDadosInscricao.focus();
  }

  function mostraTodasInscricoes_PesquisaPorNome(numerocgm){
    frameListaInscricoes.jan.location.href = 'iss3_consinscr002.php?pesquisaPorNome=' + numerocgm;
    frameListaInscricoes.mostraMsg();
    frameListaInscricoes.show();
	  frameListaInscricoes.focus();
  }

  function mostraTodasInscricoes_PesquisaFantasia(numerocgm){
    frameListaInscricoes.jan.location.href = 'iss3_consinscr002.php?pesquisaPorNomeFantasia='+numerocgm;
    frameListaInscricoes.mostraMsg();
    frameListaInscricoes.show();
	  frameListaInscricoes.focus();
  }
	
  function mostraTodasInscricoes_PesquisaEscritorio(numerocgm){
    frameListaInscricoes.jan.location.href = 'iss3_consinscr002.php?pesquisaEscritorio=' + numerocgm;
    frameListaInscricoes.mostraMsg();
    frameListaInscricoes.show();
	  frameListaInscricoes.focus();
  }
  
  function mostraTodasInscricoes_PesquisaRuas(rua){
    frameListaInscricoes.jan.location.href = 'iss3_consinscr002.php?pesquisaRua=' + rua;
    frameListaInscricoes.mostraMsg();
    frameListaInscricoes.show();
	  frameListaInscricoes.focus();
  }
  
  function mostraTodasInscricoes_PesquisaAtividades(codAtividade){
    frameListaInscricoes.jan.location.href = 'iss3_consinscr002.php?pesquisaAtividade=' + codAtividade;
    frameListaInscricoes.mostraMsg();
    frameListaInscricoes.show();
	  frameListaInscricoes.focus();
  }
  
  function mostraTodasInscricoes_PesquisaSocios(cgmsocio){
    frameListaInscricoes.jan.location.href = 'iss3_consinscr002.php?pesquisaSocios=' + cgmsocio;
    frameListaInscricoes.mostraMsg();
    frameListaInscricoes.show();
	  frameListaInscricoes.focus();
  }
  
  function mostraTodasInscricoes_PesquisaBairro(bairro){
    frameListaBairros.jan.location.href = 'iss3_consinscr002.php?pesquisaBairro=' + bairro;
    frameListaBairros.mostraMsg();
    frameListaBairros.show();
	  frameListaBairros.focus();
  }
  
  function mostraTodasInscricoes_PesquisaSetQuaLot(numeroMatricula){
	  frameListaSetQuaLot.jan.location.href = 'iss3_consinscr002.php?pesquisaMatriculaImovel=' + numeroMatricula;
	  frameListaSetQuaLot.mostraMsg();
	  frameListaSetQuaLot.show();
	  frameListaSetQuaLot.focus();
	}
	 
</script>

<fieldset style="margin-top: 20px;">
  <legend><b>Consulta Cadastro Municipal</b></legend>
  <table  align="center" width="91%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="left" valign="top">
      <form name="form1" method="post" action="">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td colspan="3" nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td width="25%" align="left" nowrap>
				      <strong>Inscri&ccedil;&atilde;o :</strong> </td>
            <td nowrap>
	  		      <input name="numInscricao" type="text" id="matricula3" onBlur="js_ValidaCamposText(this,1);" size="10" maxlength="8">
		  		  </td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td width="25%" align="left" nowrap>
						    <strong>Referencia anterior:</strong> </td>
            <td nowrap>
						    <input name="referenciaanterior" type="text" id="referenciaanterior" size="10" maxlength="10">
						</td>
          </tr>
          <tr> 
            <td width="15%" nowrap>&nbsp;</td>
            <td width="20%" align="left" nowrap>
	            <strong>Raz&atilde;o social</strong>:
	  			  </td>
            <td width="60%" nowrap>
					    <input name="razaoSocial" type="text" id="razaoSocial" size="50" maxlength="40">
					  </td>
          </tr>
          <!-- coloquei novo campo no filtro para filtrar por nome fantasia "Robson"-->
          <tr> 
            <td width="15%" nowrap>&nbsp;</td>
            <td width="20%" align="left" nowrap>
					    <strong>Nome fantasia</strong>:
					  </td>
            <td width="60%" nowrap>
					    <input name="fantasia" type="text" id="fantasia" size="50" maxlength="40">
						</td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td align="left" nowrap>
					    <strong>Escrit&oacute;rio de contabilidade:</strong></td>
            <td nowrap>
						  <input name="escritorio" type="text" id="escritorio" size="50" maxlength="40">
					  </td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td align="left" nowrap>
					    <strong>C&oacute;digo da rua :</strong>
						</td>
            <td nowrap>
					    <input name="codRua" type="text" id="codRua" onBlur="js_ValidaCamposText(this,1)" size="8" maxlength="7">
					  </td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td align="left" nowrap>
 					    <strong>Nome da rua :</strong>
  				  </td>
            <td nowrap>
		 				  <input name="nomeRua" type="text" id="nomeRua" size="50" maxlength="40">
			  		 </td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td align="left" nowrap>
		  		    <strong>C&oacute;digo do bairro :</strong>
		  			</td>
            <td nowrap>
				  	  <input name="codBairro" type="text" id="codBairro" onBlur="js_ValidaCamposText(this,1)" size="5" maxlength="4">
				  	</td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td align="left" nowrap>
			        <strong>Nome do bairro:</strong>
					  </td>
            <td nowrap>
						  <input name="nomeBairro" type="text" id="nomeBairro" size="50" maxlength="40">
					  </td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td align="left" nowrap>
					    <strong>Matrícula do imóvel:</strong>
					  </td>
            <td nowrap>
						  <input name="matriculaImovel" type="text" id="matriculaImovel" onBlur="js_ValidaCamposText(this,1);" size="10" maxlength="8">
					  </td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td align="left" nowrap>
						   <strong>Setor/Quadra/Lote:</strong>
						</td>
            <td nowrap>
						   <input name="setor"  type="text" id="setor"  size="5" maxlength="4"> /
						   <input name="quadra" type="text" id="quadra" size="5" maxlength="4"> /
						   <input name="lote"   type="text" id="lote"   size="5" maxlength="4">
					  </td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td align="left" nowrap>
				 	    <strong>Atividade:</strong>
				  	</td>
            <td nowrap>
						  <input name="atividade" type="text" id="atividade">
				    </td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td align="left" nowrap>
					    <strong>S&oacute;cios:</strong>
					  </td>
            <td nowrap>
						  <input name="socios" type="text" id="socios" size="50" maxlength="40">
						</td>
          </tr>
        </table>
        </form>
      </td>
    </tr>
  </table>
</fieldset>

<table align="center">
  <tr>
    <td>
      <input name="pesquisar" type="button" onClick="mostraJanelaPesquisa()" id="pesquisar" value="Pesquisar">
    </td>
  </tr>
</table>