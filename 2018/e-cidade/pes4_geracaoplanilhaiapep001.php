<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

	require_once ("libs/db_stdlib.php");
	require_once ("libs/db_conecta.php");
	require_once ("libs/db_sessoes.php");
	require_once ("libs/db_usuariosonline.php");
	require_once ("libs/db_app.utils.php");
	require_once ("libs/db_utils.php");
	require_once ("dbforms/db_funcoes.php");
	
	// Valores padrão do filtro
	$aDataAdmissao = array ( ''           => 'SELECIONE', 
													 '2012-12-20' => 'Até 20/12/2012', 
													 '2012-12-21' => 'Após 20/12/2012' 
												 );

  $aVinculos = array(
    '' => 'SELECIONE',
    'A,I,P' => 'Todos',
    'A' => 'Ativos',
    'I' => 'Inativos',
    'P' => 'Pensionistas'
  );
?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
	</head>

	<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
  <div class="container" style="width:400px !important;">
    
    <fieldset>
    
      <legend>Geração Planilhas do IAPEP</legend>

      <table border="0"  class="form-container">
      	
      	<tr>
            <td id="labelCompetencia" width="170px"></td>
            <td id="formularioCompetencia" width="500"></td>
        </tr>
          
      	<tr>
          <td><strong>Listar Servidores Admitidos:</strong></td>
          <td><?php db_select('dataAdmissao', $aDataAdmissao, true, 1,"style='width:172px;'") ?></td>
        </tr>

        <tr>
          <td><strong>Vínculo:</strong></td>
          <td><?php db_select('tipoVinculo', $aVinculos, true, 1,"style='width:172px;'") ?></td>
        </tr>
        
        <tr>
        	<td valign="top"><strong>Arquivos:</strong></td>
        	<td>
        		<input type="checkbox" id="tipoSalario" 		name="tipoSalario" 		 value="tipoSalario"  		 /><strong>Planilha de Salário</strong> <br/>
        		<input type="checkbox" id="tipoSalario13" 	name="tipoSalario13"   value="tipoSalario13"   /><strong>Planilha de 13º Salário</strong>    <br/>
        		<input type="checkbox" id="tipoTotalizador" name="tipoTotalizador" value="tipoTotalizador" /><strong>Relatório de Totalizadores</strong>
        	</td>        	        
        </tr>
       
 	    </table>
	  
	  </fieldset>
	  
	  <input type="submit" value="Gerar" onclick="js_processar();"/>
	  
	</div>
		
		<?php
			db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
		?>
	</body>
</html>

<script type="text/javascript">

  var sUrlRPC    = 'pes4_geracaoplanilhaiapep.RPC.php';

  /**
   * Auto-load do componente da Competência
   */
  (function() {
	  
    var oCompetenciaFolha = new DBViewFormularioFolha.CompetenciaFolha(true);
    oCompetenciaFolha.renderizaLabel($('labelCompetencia'));
    oCompetenciaFolha.renderizaFormulario($('formularioCompetencia'));
    
   })();
   
	/**
	 * Abrir Janela para Download do arquivo
	 * @param aArquivos - Array de objetos {url: '', nome: ''}
	 */
	function abrirDownload( aArquivos ) {
			
	  var oDownload = new DBDownload();

	  // Verifica se já existe o aux aberto, se existir apaga-o
    if( $('window01') ){
    	$('window01').outerHTML = '';
    }
    
	  if ( !aArquivos.length ) {
		  return;
		}
		
		for (var i = 0; i < aArquivos.length; i++) {
			
			if( aArquivos[i]['url'] != ''){
			  oDownload.addFile( "tmp/" + aArquivos[i]['url'], aArquivos[i]['nome'] );
			}
		}
		
	  oDownload.show();
	} 

	/**
    * Função responsavel pela validação e tratamento 
    * dos dados para geração do relatório.
    */
  function js_processar() {

    /**
     * Valida se o ano/competencia foram informados
    */
    if ( $F('ano') == '' || $F('mes') == '' ) {

      alert( _M('recursoshumanos.pessoal.pes4_geracaoplanilhaiapep.preenchimento_competencia_obrigatorio') );
      return false;
    }

    if ( $F('ano').length < 4 || $F('ano') == '0000' ) {
      
      alert( _M('recursoshumanos.pessoal.pes4_geracaoplanilhaiapep.preenchimento_ano_invalido') );
      return false;      
    }

    if ( $F('mes') == '0' || $F('mes') == '00' || $F('mes') > '12' ) {

      alert( _M('recursoshumanos.pessoal.pes4_geracaoplanilhaiapep.preenchimento_mes_invalido') );
      return false;      
    }
        
    if ( $F('dataAdmissao') == '' ) {

      alert( _M('recursoshumanos.pessoal.pes4_geracaoplanilhaiapep.preenchimento_dataAdmissao_obrigatorio') );
      return false;
    }

    /**
     * Valida se o ano/competencia foram informados
    */
    if ( $F('tipoVinculo') == '' ) {

      alert( _M('recursoshumanos.pessoal.pes4_geracaoplanilhaiapep.preenchimento_tipoVinculo_obrigatorio') );
      return false;
    }

    /**
     * Valida se pelo menos um tipo de folha foi selecionado
     */
    if ( $F('tipoSalario')     == null && 
    	   $F('tipoSalario13')   == null &&
    	   $F('tipoTotalizador') == null   ) {

      alert( _M('recursoshumanos.pessoal.pes4_geracaoplanilhaiapep.preenchimento_tipofolha_obrigatorio') );
      return false;
    }
    
		/**
		 * Geramos as planilhas de acordo com os filtros
		 */
    var oParametros                   = new Object();
    oParametros.sExecucao             = 'gerarPlanilhas';
    oParametros.iAno 				          = $('ano').value;
	  oParametros.iMes			            = $('mes').value;
	  oParametros.sDataAdmissao	        = $('dataAdmissao').value;
    oParametros.sTipoVinculo          = $('tipoVinculo').value;
	  oParametros.sTipoSalario 	        = $F('tipoSalario');
	  oParametros.sTipoSalario13        = $F('tipoSalario13');
	  oParametros.sTipoTotalizador      = $F('tipoTotalizador');

    var oDadosRequisicao              = new Object();
    oDadosRequisicao.method           = 'POST';
    oDadosRequisicao.asynchronous     = false;
    oDadosRequisicao.parameters       = 'json='+Object.toJSON(oParametros);
    oDadosRequisicao.onComplete       = function(oAjax){

      var oRetorno = eval("("+oAjax.responseText+")");
      if (oRetorno.iStatus == "2") {

        alert( oRetorno.sMensagem.urlDecode() );
        return;
      }

 	    abrirDownload( oRetorno.aArquivosPlanilha );
      return;	      
    }

    var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
   
  }
    
</script>