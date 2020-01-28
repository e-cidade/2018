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

require_once("libs/db_stdlib.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("classes/db_ouvidoriaatendimento_classe.php");

$oGet = db_utils::postMemory($_GET);

$oDaoOuvidoriaAtend = new cl_ouvidoriaatendimento();
$sCamposAtendimentoLocal = "ov01_sequencial,";
$sCamposAtendimentoLocal .= "p51_codigo ||' - '|| p51_descr   as tipo_processo,";
$sCamposAtendimentoLocal .= "ov01_numero ||' / '|| ov01_anousu as numero_atendimento,";
$sCamposAtendimentoLocal .= "ov01_solicitacao,";
$sCamposAtendimentoLocal .= "ov01_executado,";
$sCamposAtendimentoLocal .= "nome as nome_ouvidor,";
$sCamposAtendimentoLocal .= "ov01_dataatend as data_atendimento,";
$sCamposAtendimentoLocal .= "ov01_horaatend as hora_atendimento,";
$sCamposAtendimentoLocal .= "ov01_requerente as nome_requerente,";
$sCamposAtendimentoLocal .= "ov25_sequencial ||' - '|| ov25_descricao as local,";
$sCamposAtendimentoLocal .= "ov01_depart ||' - '|| descrdepto as departamento_inicial,";
$sCamposAtendimentoLocal .= "(select procarquiv.p67_dtarq ";
$sCamposAtendimentoLocal .= "   from arqproc ";
$sCamposAtendimentoLocal .= "        inner join procarquiv on p68_codarquiv = p67_codarquiv ";
$sCamposAtendimentoLocal .= " where  p68_codproc = p58_codproc) as data_arquivamento, ";
$sCamposAtendimentoLocal .= "p58_codproc as numero_processo";

$sWhereAtendimentoLocal = "ouvidoriaatendimento.ov01_sequencial = {$oGet->iAtendimento}";
$sSqlOuvAtend = $oDaoOuvidoriaAtend->sql_query_atendimento_processo(null, $sCamposAtendimentoLocal, null, $sWhereAtendimentoLocal);
$rsOuviAtendimento = $oDaoOuvidoriaAtend->sql_record($sSqlOuvAtend);

if ($oDaoOuvidoriaAtend->erro_status == "0") {

  db_msgbox("Não foi possível localizar o atendimento desejado.");
  db_redireciona("digitaconsultaouvidorianovo.php");
  exit;
}

$oAtendimento = db_utils::fieldsMemory($rsOuviAtendimento, 0);

$sDataArquivamento = "";
if ($oAtendimento->data_arquivamento != "") {
  $sDataArquivamento = db_formatar($oAtendimento->data_arquivamento, "d");
}
$oAtendimento->data_arquivamento = $sDataArquivamento;

$sNumeroProcesso = "";
if ($oAtendimento->numero_processo != "") {
  $sNumeroProcesso = $oAtendimento->numero_processo;
}
$oAtendimento->numero_processo = $sNumeroProcesso;
?>
<html>
<head>
	<title>Consulta Atendimento Ouvidoria</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="include/estilodai.css" >
	<script language="JavaScript" src="scripts/db_script.js"></script>
	<script language="JavaScript" src="scripts/prototype.js"></script>
	<style type="text/css">
    <?php
    db_estilosite();
    ?>
  	
  	.valores{
  	  width: 100%;
  	}
  	
  	.textarea {
  	  width: 100%;
  	  height: 160px;
  	}
  	
  	.buttonSpan {
  	   width:  300px;
  	   height: 25px;
  	   text-align: center;
  	   color: #000;
  	   border-top:    2px solid #FFF;
  	   border-left:   2px solid #FFF;
  	   border-right:  2px solid #000;
  	   border-bottom: 2px solid #000;
  	   background-color: #CCCCCC;
  	   cursor: pointer;
  	}
  	
  	.buttonIn {
  	
  	   width:            300px;
  	   height:           25px;
  	   text-align:       center;
  	   font-weight:      bold;
  	   color:            #000;
  	   cursor:           pointer;
  	   background-color: #CCCCCC;
  	   border-bottom:    1px solid #FFF;
  	   border-right:     1px solid #FFF;
  	   border-left:      1px solid #000;
  	   border-top:       1px solid #000;
  	}
  	
  	.buttonDisabled {
  	
  	   width:            300px;
  	   height:           25px;
  	   text-align:       center;
  	   font-weight:      bold;
  	   color:            #000;
  	   cursor:           pointer;
  	   background-color: #FFFFFF;
  	   border-top:    2px solid #FFF;
  	   border-left:   2px solid #FFF;
  	   border-right:  2px solid #000;
  	   border-bottom: 2px solid #000;
  	}
  	
	</style>
	</head>
	<body bgcolor="<?=$w01_corbody ?>">
  
    <table width="100%" border="0" id="tabelaAtendimento">
    	<tr>
    	  <td style="width: 40%;" valign="top">
        	<fieldset>
          <legend class="titulo">
            <b>Dados do Atendimento</b>
          </legend>
            <table border="0" width="100%">
              <tr>
                <td align="left" width="180px">
               	 <span class='texto'><b>Atendimento:</b></span>
                </td>
                <td id='atendimento'>
                  <input class="valores" name="input" value="<?=$oAtendimento->numero_atendimento; ?>" readonly="readonly" />
                </td> 
                <td align="right">
                  <span class='texto'><b>Processo:</b></span>
                </td>
                <td  id='processo'>
                  <input class="valores" name="input" value="<?=$oAtendimento->numero_processo; ?>" readonly="readonly" />
                </td>
              </tr>
              <tr>
                <td>
                  <span class='texto'><b>Data do Processo:</b></span>
                </td>
                <td >
                  <input class="valores" name="input" value="<?=db_formatar($oAtendimento->data_atendimento, 'd'); ?>" readonly="readonly" />
                </td>
                <td align="right" nowrap="nowrap">
                  <span class='texto'><b>Hora Inclusão:</b></span>
                </td>
                <td>
                  <input class="valores" name="input" value="<?=$oAtendimento->hora_atendimento; ?>" readonly="readonly" />
                </td>             
              </tr>
              <tr>
                <td><span class='texto'><b>Ouvidor:</b></span></td>
                <td colspan="3">
                  <input class="valores" name="input" value="<?=$oAtendimento->nome_ouvidor; ?>" readonly="readonly" />
                </td>
              </tr>
              <tr>
                <td>
                  <span class='texto'><b>Departamento Inicial:</b></span>
                </td>
                <td colspan="3">
                  <input class="valores" name="input" value="<?=$oAtendimento->departamento_inicial; ?>" readonly="readonly" />
                </td>
              </tr>            
              <tr>
                <td>
                  <span class='texto'><b>Tipo de Processo:</b></span>
                </td>
                <td colspan="3">
                  <input class="valores" name="input" value="<?=$oAtendimento->tipo_processo; ?>" readonly="readonly" />
                </td>
              </tr>
              <tr>
                <td>
                 <span class='texto'><b>Requerente:</b></span>
                </td>
                <td colspan="3">
                  <input class="valores" name="input" value="<?=$oAtendimento->nome_requerente; ?>" readonly="readonly" />
                </td>
              </tr>                       
              <tr>
                <td>
                 <span class='texto'><b>Local:</b></span>
                </td>
                <td colspan="3">
                  <input class="valores" name="input" value="<?=$oAtendimento->local; ?>" readonly="readonly" />
                </td>
              </tr>             
              <tr>
                <td>
                  <span class='texto'><b>Data de arquivamento:</b></span>
                </td>
                <td colspan="3">
                  <input class="valores" name="input" value="<?=$oAtendimento->data_arquivamento; ?>" readonly="readonly" />
                </td>
              </tr>           
            </table>   
          </fieldset>
    	  </td>
    	  <td style="width: 30%;">
    	    <fieldset>
    	      <legend class="titulo"><b>Solicitação</b></legend>
    	      <textarea class="textarea" readonly="readonly"><?=$oAtendimento->ov01_solicitacao; ?></textarea>
    	    </fieldset>
    	  </td>
    	  <td style="width: 30%;">
    	    <fieldset>
    	      <legend class="titulo"><b>Executado</b></legend>
    	      <textarea class="textarea" readonly="readonly"><?=$oAtendimento->ov01_executado; ?></textarea>
    	    </fieldset>
    	  </td>
    	</tr>
    </table>
    <fieldset >
      <legend class="titulo">Informações Adicionais</legend>
      
      <table id="globalInformacaoAdicional" >
        <tr>
          <td valign="top" width="20%">
            <table class="texto" border="0" cellspacing="0">
              <tr>
                <td id="btnAtendimentoVinculado" width="300px;" class="buttonSpan">
                  <span onclick="js_atualizaFrame('btnAtendimentoVinculado');">Atendimento Vinculado</span>
                </td>
              </tr>
              <tr>
                <td id="btnDespachos" width="300px;" class="buttonSpan">
                  <span onclick="js_atualizaFrame('btnDespachos');">Despachos</span>
                </td>
              </tr>
              <tr>
                <td id="btnRetornosEfetuados" width="300px;" class="buttonSpan">
                  <span onclick="js_atualizaFrame('btnRetornosEfetuados');">Retornos Efetuados</span>
                </td>
              </tr>
              <tr>
                <td id="btnInformacoesRequerente" width="300px;" class="buttonSpan">
                  <span onclick="js_atualizaFrame('btnInformacoesRequerente');">Informações Requerente</span>
                </td>
              </tr>
            </table>
          </td>
          <td valign="top" width="97%">
            <iframe id="frameAtendimentoOuvidoria" name="frameAtendimentoOuvidoria" src="centro_pref.php" width="100%" height="300px;" style="border:hidden;"></iframe>
          </td width="3%"> 
          <td>&nbsp;</td>
        </tr>
      </table>
    </fieldset>
    
    
    <p align="center">
      <input type="button" value="Voltar" id="btnVoltar" onclick="js_retornarConsultaAnterior();"/>
    </p>
    
  </body>
</html>
<script>

  var iNumeroProcesso = '<?php echo $oAtendimento->numero_processo;?>';
  function js_atualizaFrame(sNomeBotao) {

	  var sArquivoAbrir = "";

	  $('btnAtendimentoVinculado').removeClassName('buttonIn');
	  $('btnDespachos').removeClassName('buttonIn');
	  $('btnRetornosEfetuados').removeClassName('buttonIn');
	  $('btnInformacoesRequerente').removeClassName('buttonIn');

	  if (iNumeroProcesso != "") {
		  
  	  switch(sNomeBotao) {
  
       case 'btnAtendimentoVinculado':
  
         $('btnAtendimentoVinculado').addClassName('buttonIn');
         sArquivoAbrir = "func_detalheatendimentoouvidoria.php?iCodProcesso="+iNumeroProcesso;
         break;
  
       case 'btnDespachos':
           
         sArquivoAbrir = "func_detalhedespachosouvidoria.php?iCodProcesso="+iNumeroProcesso;
         $('btnDespachos').addClassName('buttonIn');
         break;
  
       case 'btnRetornosEfetuados':
           
      	 sArquivoAbrir = "func_detalheretornosouvidoria.php?iCodProcesso="+iNumeroProcesso;
      	 $('btnRetornosEfetuados').addClassName('buttonIn');
         break;
         
       case 'btnInformacoesRequerente':
           
      	 $('btnInformacoesRequerente').addClassName('buttonIn');
      	 sArquivoAbrir = "func_informacoesrequerente.php?iAtendimento="+<?php echo $oAtendimento->ov01_sequencial; ?>;
         break;
  	  }
      $('frameAtendimentoOuvidoria').src = sArquivoAbrir;
	  }
  }


  function js_validaNumeroProcesso() {

	  if (iNumeroProcesso == "") {
		  
		  $('btnAtendimentoVinculado').addClassName('buttonDisabled');
		  $('btnDespachos').addClassName('buttonDisabled');
		  $('btnRetornosEfetuados').addClassName('buttonDisabled');
		  $('btnInformacoesRequerente').addClassName('buttonDisabled');
	  }
  }
  js_validaNumeroProcesso();


  function js_retornarConsultaAnterior() {
    location.href = "digitaconsultaouvidorianovo.php?lRetornoAutomatico=true";
  }
</script>