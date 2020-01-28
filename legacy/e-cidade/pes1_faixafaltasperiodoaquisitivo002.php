<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_classesgenericas.php");

require_once("dbforms/db_funcoes.php");

$oRequest = db_utils::postmemory($_REQUEST);
$db_opcao = 1;
$oRotulo  = new rotulocampo;
$oRotulo->label('rh52_regime');
$oRotulo->label('rh52_descr');
$oRotulo->label('rh125_faixainicial');
$oRotulo->label('rh125_faixafinal');
$oRotulo->label('rh125_diasdesconto');

$sShowBotaoNovo    = 'display:none;';
$sShowBotaoAlterar = 'display:none;';
$sShowBotaoIncluir = '';
$sShowBotaoExcluir = 'display:none;';
$iRh125_sequencial  = null;

$clrhcadregimefaltasperiodoaquisitivo = new cl_rhcadregimefaltasperiodoaquisitivo();

if( isset($oRequest->rh125_sequencial) ) {
  $iRh125_sequencial = $oRequest->rh125_sequencial;
}

if( isset($oRequest->opcao) ) {

  $sSql              = $clrhcadregimefaltasperiodoaquisitivo->sql_query_file ($oRequest->rh125_sequencial);
  $rsResultados      = $clrhcadregimefaltasperiodoaquisitivo->sql_record( $sSql ); 
  db_fieldsmemory($rsResultados,0);

  if ( $oRequest->opcao == 'alterar' ) {

    $sShowBotaoAlterar = '';
    $sShowBotaoIncluir = 'display:none;';
    $sShowBotaoNovo    = '';

  } elseif ( $oRequest->opcao == 'excluir' ) {

  	$db_opcao 				 = 3;
    $sShowBotaoExcluir = '';
    $sShowBotaoIncluir = 'display:none;';
    $sShowBotaoNovo    = '';
  }
}

?>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="expires" content="0">
    <?php
      db_app::load("estilos.css");
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js"); 
      db_app::load("datagrid.widget.js");
    ?>
  </head>
  <body bgcolor="#cccccc">
    <div class="container">
      <fieldset>
        <legend>
         Faixas de Faltas:
        </legend>
				<table>
				  <tr>
				    <td nowrap title="<?=@$Lrh52_regime?>">
				      <?=@$Lrh52_regime?>
				    </td>
				    <td> 
							<?
							  db_input('rh125_sequencial', 10, $iRh125_sequencial,true,'hidden');
							  db_input('rh52_regime', 10, $Irh52_regime,true,'text', 3, "");
							  db_input('rh52_descr' , 54, $Irh52_descr ,true,'text', 3, "");
							?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$Lrh125_faixainicial?>">
				      <?=@$Lrh125_faixainicial?>
				    </td>
				    <td> 
							<?
							  db_input('rh125_faixainicial', 10, $Irh125_faixainicial,true,'text', $db_opcao, "");
							?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$Lrh125_faixafinal?>">
				      <?=@$Lrh125_faixafinal?>
				    </td>
				    <td> 
							<?
							  db_input('rh125_faixafinal', 10, $Irh125_faixafinal,true,'text', $db_opcao, "");
							?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$Lrh125_diasdesconto?>">
				      <?=@$Lrh125_diasdesconto?>
				    </td>
				    <td> 
							<?
							  db_input('rh125_diasdesconto', 10, $Irh125_diasdesconto,true,'text', $db_opcao, "");
							?>
				    </td>
				  </tr>
			  </table>
      </fieldset>

      <input type="button" id="btnAcao1" class='btnLista' value="Alterar" onClick="js_envia( this.value );" style="<? echo $sShowBotaoAlterar; ?>">
      <input type="button" id="btnAcao2" class='btnLista' value="Incluir" onClick="js_envia( this.value );" style="<? echo $sShowBotaoIncluir;    ?>">
      <input type="button" id="btnAcao3" class='btnLista' value="Excluir" onClick="js_envia( this.value );" style="<? echo $sShowBotaoExcluir; ?>">
      <input type="button" id="btnAcao4" class='btnLista' value="Novo"    onClick="js_redireciona();"       style="<? echo $sShowBotaoNovo; ?>">

			<?php
			  
			  if (!empty($oRequest->rh52_regime)){
          
          $sWhereNaoVisualiza                      = '';

          if ( isset ($oRequest->rh125_sequencial) ){
            $sWhereNaoVisualiza = " and rh125_sequencial <> ".$oRequest->rh125_sequencial;
          }

			    $chavepri= array("rh109_sequencial" => "");
			    
			    $cliframe_alterar_excluir                = new cl_iframe_alterar_excluir;
			    
			    $sCampos                                 = "rh125_sequencial,                           ";
			 	  $sCampos                                .= "rh125_faixainicial,                         ";
			 	  $sCampos                                .= "rh125_faixafinal,                           ";
			 	  $sCampos                                .= "rh125_diasdesconto                          ";
          $sWhere                                  = "rh125_rhcadregime = {$oRequest->rh52_regime}";
          $sWhere                                 .= $sWhereNaoVisualiza;
          $sChavePrimaria                          = array("rh125_sequencial"=> $oRequest->rh52_regime);
          $sSql                                    = $clrhcadregimefaltasperiodoaquisitivo->sql_query_file (null, $sCampos, 'rh125_sequencial', $sWhere);

			    $cliframe_alterar_excluir->chavepri      = $sChavePrimaria;
			    $cliframe_alterar_excluir->sql           = $sSql;
			    $cliframe_alterar_excluir->campos        = $sCampos;
			    $cliframe_alterar_excluir->legenda       = "Itens Lançados";
          $cliframe_alterar_excluir->iframe_nome   = "iframe_periodos";
			    $cliframe_alterar_excluir->iframe_height = "100%";
			    $cliframe_alterar_excluir->iframe_width  = "100%";
			    $cliframe_alterar_excluir->alignlegenda  = "left";
			    $cliframe_alterar_excluir->iframe_alterar_excluir(1);
			    
			  }
      ?>

      <input name="fechar" type="button"  value="Voltar" onclick="window.location.href = 'pes1_faixafaltasperiodoaquisitivo001.php'">
    </div>

    <?php
    db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
           );
    ?>

    <script type="text/javascript">
          
      $('rh125_faixainicial').focus();

      function js_envia( sExecucao ){

        var sCaminhoMensagem               = 'recursoshumanos.pessoal.pes1_faixafaltasperiodoaquisitivo.';
        var oParametros                    = new Object();
            oParametros.sExecucao          = sExecucao.toLowerCase();
            oParametros.rh125_sequencial   = $F('rh125_sequencial');
            oParametros.rh52_regime      = $F('rh52_regime');
            oParametros.rh125_faixainicial = $F('rh125_faixainicial');
            oParametros.rh125_faixafinal   = $F('rh125_faixafinal');
            oParametros.rh125_diasdesconto = $F('rh125_diasdesconto');

        try{
          
			    if ( oParametros.rh125_faixainicial == '' ) {
				    throw( _M( sCaminhoMensagem + 'preenchimento_faixainicial_obrigatorio') );
          }

			    if( oParametros.rh125_faixafinal == '' ) {
				    throw( _M( sCaminhoMensagem + 'preenchimento_faixafinal_obrigatorio') );
          }

          if ( oParametros.rh125_diasdesconto == '' ) {
				    throw( _M( sCaminhoMensagem + 'preenchimento_diasdesconto_obrigatorio') );
          }

			    if( oParametros.rh125_diasdesconto.value > 30 ) {
				    throw( _M( sCaminhoMensagem + 'diasdesconto_nao_superior_30') );
          }

			    if( oParametros.rh125_faixainicial.value > oParametros.rh125_faixafinal.value ) {
				    throw( _M( sCaminhoMensagem + 'faixa_inicial_maior_faixa_final') );
          }

        } catch (oException) {
  
          alert(oException);
 				  return false;
			  }

        if ( oParametros.sExecucao != 'incluir' ) {

   			  if( !confirm(_M( sCaminhoMensagem + 'deseja_prosseguir' ) ) ) {
				    return false;
          }
        }

        var sUrlRPC    = 'pes1_faixafaltasperiodoaquisitivo.RPC.php';

      	var oDadosRequisicao    		   = new Object();
  	    oDadosRequisicao.method 		   = 'post';
        oDadosRequisicao.asynchronous  = false;
        oDadosRequisicao.parameters    = 'json='+Object.toJSON(oParametros);
        oDadosRequisicao.onComplete    = function(oAjax) {
  	    
    	    var oRetorno = eval("("+oAjax.responseText+")");
          if (oRetorno.iStatus == "2") {

            alert( oRetorno.sMensagem.urlDecode() );
            return;
          }

          alert( _M( sCaminhoMensagem + 'processamento_sucesso' ) );
          js_redireciona();

        }
	      var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
      }
      
      function js_redireciona() {
      
        window.location.href = 'pes1_faixafaltasperiodoaquisitivo002.php?rh52_regime=' + $F('rh52_regime') + 
    	                                                                 '&rh52_descr='  + $F('rh52_descr');
        return;
      }
    </script>		 
  </body>
</html>