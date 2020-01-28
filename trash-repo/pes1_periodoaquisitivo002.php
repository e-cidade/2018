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
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once('model/pessoal/ferias/PeriodoAquisitivoFerias.model.php');
require_once('model/pessoal/Servidor.model.php');

$clrhferias   = new cl_rhferias;
$clrhferias->rotulo->label();
$oRotulo  = new rotulocampo;
$oRotulo->label('z01_nome');
$db_opcao     = 1;
$db_botao     = true;

$rh109_periodoaquisitivoinicial_dia = "";
$rh109_periodoaquisitivoinicial_mes = "";
$rh109_periodoaquisitivoinicial_ano = "";
$rh109_periodoaquisitivofinal_dia   = "";
$rh109_periodoaquisitivofinal_mes   = "";
$rh109_periodoaquisitivofinal_ano   = "";

$oRequest         = db_utils::postMemory($_REQUEST);
$rh109_regist     = isset($oRequest->rh109_regist)     ? $oRequest->rh109_regist     : null;
$rh109_sequencial = isset($oRequest->rh109_sequencial) ? $oRequest->rh109_sequencial : null;
$sOpcao           = isset($oRequest->opcao)            ? $oRequest->opcao            : null;

if (isset($processar)) {

  $oRequest = db_utils::postMemory($_POST);
  
  try {
  
    db_inicio_transacao();
    
    $iDiasDireito       = ($oRequest->rh109_possuidiasdireito == '1'? $oRequest->rh109_diasdireito : 0 );
    $oPeriodoAquisitivo = new PeriodoAquisitivoFerias($rh109_sequencial);
    
    $oDataInicial = null;
    if ($oRequest->rh109_periodoaquisitivoinicial != '') {
    	$oDataInicial = new DBDate($oRequest->rh109_periodoaquisitivoinicial);
    }
    $oPeriodoAquisitivo->setDataInicial($oDataInicial);
    
    $oDataFinal = null;
    if ($oRequest->rh109_periodoaquisitivofinal != '') {
    	$oDataFinal = new DBDate($oRequest->rh109_periodoaquisitivofinal);
    }
    $oPeriodoAquisitivo->setDataFinal($oDataFinal);
    
    //$oPeriodoAquisitivo->setDiasDireito($iDiasDireito);
    $oPeriodoAquisitivo->setFaltasPeriodoAquisitivo($oRequest->rh109_faltasperiodoaquisitivo);
    $oPeriodoAquisitivo->setServidor(new Servidor($oRequest->rh109_regist));
    $oPeriodoAquisitivo->setObservacao($oRequest->rh109_observacao);
    
    $oPeriodoAquisitivo->salvar();
    
    db_fim_transacao();
    
    db_msgbox( _M(PeriodoAquisitivoFerias::MENSAGENS . "alterar") );
    db_redireciona('pes1_periodoaquisitivo002.php?rh109_regist=' . $oRequest->rh109_regist . "&z01_nome=" . $oRequest->z01_nome);
    exit;
  
  } catch (Exception $eErro) {
  	db_msgbox(str_replace("\n", '\n', $eErro->getMessage()));
    db_fim_transacao(true);
  }
  
} else if (isset($sOpcao) and $sOpcao == 'alterar') {
	
	$oPeriodoAquisitivo = new PeriodoAquisitivoFerias($rh109_sequencial);
	$rh109_periodoaquisitivoinicial_dia = $oPeriodoAquisitivo->getDataInicial()->getDia();
	$rh109_periodoaquisitivoinicial_mes = $oPeriodoAquisitivo->getDataInicial()->getMes();
	$rh109_periodoaquisitivoinicial_ano = $oPeriodoAquisitivo->getDataInicial()->getAno();
	$rh109_periodoaquisitivofinal_dia   = $oPeriodoAquisitivo->getDataFinal()->getDia();
	$rh109_periodoaquisitivofinal_mes   = $oPeriodoAquisitivo->getDataFinal()->getMes();
	$rh109_periodoaquisitivofinal_ano   = $oPeriodoAquisitivo->getDataFinal()->getAno();
	$rh109_diasdireito                  = $oPeriodoAquisitivo->getDiasDireito();
	$rh109_faltasperiodoaquisitivo      = $oPeriodoAquisitivo->getFaltasPeriodoAquisitivo();
	$rh109_observacao                   = $oPeriodoAquisitivo->getObservacao();
} else {
	$db_opcao     = 3;
}
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/dates.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>

  <body bgcolor="#cccccc" style="margin-top:30px;" onLoad="a=1;" >
  
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
          <center>
            <form method="post" name="form1" id="form1">
              
              <?php db_input('rh109_sequencial', 10, 1, true, 'hidden'); ?>
              <?php db_input('rh109_regist', 10, 1, true, 'hidden'); ?>
            
              <fieldset style="width:650px">
                <legend><strong>Per�odo Aquisitivo</strong></legend>
                
                  <table width="100%" >
                    <tr>
              		    <td title="<?php echo $Trh109_regist; ?>">
              		      <?php
              		      db_ancora($Lrh109_regist, "js_pesquisarh109_regist(true);", 3);
              		      ?>
              		    </td>
              		    <td> 
              		      <?php
              			      db_input('rh109_regist', 10, $Irh109_regist, true, 'text', 3, " onchange='js_pesquisarh109_regist(false);'");      
              			      db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3);
              		      ?>
              		    </td>
              		  </tr>
                    <tr>
                      <td nowrap title="<?=$Trh109_periodoaquisitivoinicial?>" width="35%">
                        <?= $Lrh109_periodoaquisitivoinicial; ?>
                      </td>
                      <td>
                        <?php 
                        db_inputdata('rh109_periodoaquisitivoinicial',
                                      $rh109_periodoaquisitivoinicial_dia,
                                      $rh109_periodoaquisitivoinicial_mes,
                                      $rh109_periodoaquisitivoinicial_ano,
                                      true, 'text', $db_opcao, "");                          
                        ?>
                      </td>
                    </tr>
                    
                    <tr>
                      <td nowrap title="<?=$Trh109_periodoaquisitivofinal?>">
                        <?=$Lrh109_periodoaquisitivofinal; ?>                      
                      </td>
                      <td>
                        <?php
                          db_inputdata('rh109_periodoaquisitivofinal', 
                                       $rh109_periodoaquisitivofinal_dia, 
                                       $rh109_periodoaquisitivofinal_mes, 
                                       $rh109_periodoaquisitivofinal_ano, 
                                       true, 'text', $db_opcao, "");
                        ?>
                      </td>
                    </tr>
                    
                    <tr>
                      <td>
                        <?php echo $Lrh109_diasdireito; ?>
                      </td>
                      <td>
                        <?php db_input('rh109_diasdireito', 10, 1, true, 'text', 3); ?>
                      </td>
                    </tr>
                    
                    <tr>
                      <td nowrap title="<?=$Trh109_faltasperiodoaquisitivo?>">
                        <?=$Lrh109_faltasperiodoaquisitivo?>
                      </td>
                      <td>
                        <?php
                          db_input('rh109_faltasperiodoaquisitivo', 10, $Irh109_faltasperiodoaquisitivo, true, 'text', $db_opcao, 'onchange="js_calculaDiasDireito();"');
                        ?>
                      </td>
                    </tr>
                    
                    <tr>
                      <td nowrap>
                        <strong><?php echo 'Tem direito a f�rias:'; ?></strong>
                      </td>
                      <td>
                        <?php 
                          $aDiasDeDireito                     = array('1' => 'Sim', '2' => 'N�o');
                          $GLOBALS['rh109_possuidiasdireito'] = (!isset($rh109_diasdireito) || $rh109_diasdireito > 0) ? '1': '2';
                          
                          db_select('rh109_possuidiasdireito', $aDiasDeDireito, true, $db_opcao, "");
                        ?>
                      </td>
                    </tr>
                    
                    <tr>
                      <td nowrap title="<?php echo @$Trh109_observacao; ?>" colspan="2" >
                        <fieldset>
                          <legend><?=@$Lrh109_observacao?></legend>
                          <?php db_textarea('rh109_observacao', 3, 80, ($rh109_possuidiasdireito =='1' ? 0 : 2),true, 'textarea', $db_opcao); ?>
                        </fieldset>
                      </td>
                    </tr>
                    
                    <tr>
							      	<td colspan="2">
							      	<?php
							      	
							           if (!empty($oRequest->rh109_regist)){
							      
							             $chavepri= array("rh109_sequencial" => "");
							             
							             $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
							             
							             $sCampos  = "rh109_sequencial,              ";
													 $sCampos .= "rh109_periodoaquisitivoinicial,";
													 $sCampos .= "rh109_periodoaquisitivofinal,  ";
													 $sCampos .= "rh109_diasdireito,             ";
													 $sCampos .= "rh109_faltasperiodoaquisitivo  ";
													 
                           $sWhere   = "     rh109_regist = {$oRequest->rh109_regist}";
                           $sWhere  .= " and not exists ( select 1 from rhferiasperiodo where rh109_sequencial = rh110_rhferias ) ";

													 if (!empty($rh109_sequencial)) {
                             $chavepri= array("rh109_sequencial"=> $rh109_sequencial);
													 }
                           if (!empty($oRequest->rh109_sequencial)) {                            
                             $sWhere .= " and rh109_sequencial <> {$oRequest->rh109_sequencial}";
                           }

									         $sSql     = $clrhferias->sql_query_file (null, $sCampos, 'rh109_periodoaquisitivoinicial, rh109_periodoaquisitivofinal', $sWhere);
							             $rsSql    = db_query($sSql);

                            if ( !$rsSql ) {
                              throw new Exception("Error Processing Request", 1);
                            }

                            if ( pg_num_rows($rsSql) == 1 && !isset($oRequest->rh109_sequencial) ) {

                              $rh109_sequencial =  db_utils::fieldsMemory($rsSql, 0)->rh109_sequencial;
                              db_redireciona('pes1_periodoaquisitivo002.php?lRedireciona=false&rh109_regist=' . $oRequest->rh109_regist . '&rh109_sequencial='.$rh109_sequencial.'&z01_nome=' . $oRequest->z01_nome . '&opcao=alterar');
                              exit;
                            }


							             $cliframe_alterar_excluir->chavepri      = $chavepri;
							             $cliframe_alterar_excluir->sql           = $sSql;
							             $cliframe_alterar_excluir->campos        = $sCampos;
							             $cliframe_alterar_excluir->legenda       = "Per�odos Aquisitivos";
                           $cliframe_alterar_excluir->iframe_nome   = "iframe_periodos";
							             $cliframe_alterar_excluir->iframe_height = "100%";
							             $cliframe_alterar_excluir->iframe_width  = "100%";
							             $cliframe_alterar_excluir->alignlegenda  = "left";
                           //$cliframe_alterar_excluir->opcoes        = 2;
							             $cliframe_alterar_excluir->iframe_alterar_excluir(1);
							             
							           }
							         ?>
							         
							      
							      	</td>
							      </tr>
                    
                  </table>
                  
              </fieldset>
              
              <?php 
                if ($db_opcao != 3) {
              ?>
                <input name="processar" type="submit" id="db_opcao" value="Processar" onclick="return js_processar()" />
              <?php 
                }
              ?>
              <input name="voltar" type="button" id="voltar" value="Voltar" onclick="js_voltar()" />
            
            </form>
          </center>
        </td>
      </tr>
    </table>
    <?php 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
    
  </body>

   <script type="text/javascript">

     function js_preencheDadosFormulario(chave) {
              
       db_iframe_rhperiodoaquisitivo.hide();

       var msgDiv = "Carregando dados do formul�rio \n Aguarde ...";
       js_divCarregando(msgDiv,'msgBox');
       
       <?php
         echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?rh109_sequencial='+chave";
       ?>
     }
     
     function js_voltar() {
       location.href = 'pes1_periodoaquisitivo001.php';
     }

     function js_processar() {

			 if ($F('rh109_periodoaquisitivoinicial') == '') {
				 alert('Per�odo aquisitivo inicial n�o informado.');
				 return false;
			 }

			 if ($F('rh109_periodoaquisitivofinal') == '') {
				 alert('Per�odo aquisitivo final n�o informado.');
				 return false;
			 }	          

       if ($F('rh109_possuidiasdireito') == 2 && !$F('rh109_observacao'))  { 
         alert('Campo Observa��o deve ser preenchido.');
         return false;
       }

       /**
        * Realiza a valida��o da data inicial e data final.
        */
       var sDiferencaDatas = js_diferenca_datas(
                                                 js_formatar($F("rh109_periodoaquisitivoinicial"),'d'), 
                                                 js_formatar($F("rh109_periodoaquisitivofinal"),'d'), 
                                                 3
                                               );
       
       if ( sDiferencaDatas && sDiferencaDatas != 'i'){

        alert('Data Inicial n�o pode ser maior que a Data Final');
        return false;
       }
       
       return true;
     }

     /**
      * Seta o n�mero de dias de direito de acordo com as faltas
      * C�lculo baseado em dias de gozo de 30 dias
      */
     function js_calculaDiasDireito() {

       var iDias   = $F('rh109_diasdireito');
       var oFaltas = $('rh109_faltasperiodoaquisitivo');
       var iFaltas = +oFaltas.value;
       var oRegex  = /^[0-9]+$/;
       
       if ( !oRegex.test(iFaltas) ) {
         return false;  
       }
       var oParametros               = new Object();
     	 var oDadosRequisicao    		   = new Object();
     	 
       oParametros.sExecucao         = 'getDiasDireito';
       oParametros.iMatricula        = $F('rh109_regist');
       oParametros.iFaltas           = $F('rh109_faltasperiodoaquisitivo');
       
     	 oDadosRequisicao.method 		   = 'post';
     	 oDadosRequisicao.asynchronous = false;
     	 oDadosRequisicao.parameters   = 'json=' + JSON.stringify(oParametros);
     	 oDadosRequisicao.onComplete   = function(oAjax) {
     	   
     	  var oRetorno = JSON.parse(oAjax.responseText);
     	  
     	  if (oRetorno.iStatus == 2) {

     		  alert( oRetorno.sMensagem.urlDecode() );
     	    return;
     	  }
     	  iDias = oRetorno.iDiasDireito;
     	};

      new Ajax.Request('pes4_ferias.RPC.php', oDadosRequisicao);

       /**
        * Muda valor do combo para sim ou n�o, de acordo com os dias de direito
        * 1 - Sim
        * 2 - N�o
        */
       $('rh109_possuidiasdireito').value = iDias == 0 ? 2 : 1;
       $('rh109_diasdireito').value       = iDias;
     }
     
     //top.corpo.iframe_periodos.onload = js_load();

     document.onreadystatechange = function () {

       if (document.readyState == "complete") {
         js_load();
       }
     }

     /**
      * Oculta o link excluir da tabela
      * 
      * @return void
      */
     function js_load() {

       var aLinks  = top.corpo.iframe_periodos.document.querySelectorAll('a:last-child');
       var iLimite = 0;

       for ( var iIndice in aLinks ) {
     
         var oElemento = aLinks[iIndice];
         if(!oElemento.style) {
          continue;
         }
         oElemento.style.display = "none";
       };
     }

   </script>
</html>