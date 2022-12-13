<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_ouvidoriaatendimento_classe.php");
require_once("classes/db_ouvidoriaatendimentoretornoender_classe.php");
require_once("classes/db_ouvidoriaatendimentoretornotelefone_classe.php");
require_once("classes/db_ouvidoriaatendimentoretornoemail_classe.php");
require_once("classes/db_telefonetipo_classe.php");

require_once("classes/db_db_usuarios_classe.php");
require_once("classes/db_db_depart_classe.php");


$clTelefoneTipo = new cl_telefonetipo();

$clOuvidoriaAtendimento = new cl_ouvidoriaatendimento();
$clOuvidoriaAtendimento->rotulo->label();

$clOuvidoriaAtendimentoRetornoEnder = new cl_ouvidoriaatendimentoretornoender();
$clOuvidoriaAtendimentoRetornoEnder->rotulo->label();

$clOuvidoriaAtendimentoRetornoTelef = new cl_ouvidoriaatendimentoretornotelefone();
$clOuvidoriaAtendimentoRetornoTelef->rotulo->label();

$clOuvidoriaAtendimentoRetornoEmail = new cl_ouvidoriaatendimentoretornoemail();
$clOuvidoriaAtendimentoRetornoEmail->rotulo->label();

$clrotulo = new rotulocampo;

$clrotulo->label("ov09_protprocesso");
$clrotulo->label("ov24_ouvidoriacadlocal");
$clrotulo->label("ov25_descricao");

$oDBUsuarios = new cl_db_usuarios();
$oDBDepart   = new cl_db_depart();
if  ( $db_opcao == 1 ) {
	
	// Consulta nome e ID do usuário que está logado
	
	$rsConsultUsuario = $oDBUsuarios->sql_record($oDBUsuarios->sql_query_file(db_getsession('DB_id_usuario')));
	
	if ( $oDBUsuarios->numrows > 0 ) {
		$oUsuario     = db_utils::fieldsMemory($rsConsultUsuario,0);
		$ov01_usuario = $oUsuario->id_usuario;
		$nome         = $oUsuario->nome;
	}
	
	// Consulta código e descrição do depto que está logado
	
	$rsConsultaDepto = $oDBDepart->sql_record($oDBDepart->sql_query_file(db_getsession('DB_coddepto')));
	
	if ( $oDBDepart->numrows > 0 ) {
	  
	  $oDepto = db_utils::fieldsMemory($rsConsultaDepto,0);
	  $ov01_depart = $oDepto->coddepto;
		$descrdepto  = $oDepto->descrdepto;
	}
	
	$ov01_dataatend_dia = date('d',db_getsession('DB_datausu'));
	$ov01_dataatend_mes = date('m',db_getsession('DB_datausu'));
	$ov01_dataatend_ano = date('Y',db_getsession('DB_datausu'));
	$ov01_horaatend     = db_hora();

}

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<?
	db_app::load("scripts.js");
	db_app::load("prototype.js");
	db_app::load("datagrid.widget.js");
	db_app::load("strings.js");
	db_app::load("grid.style.css");
	db_app::load("estilos.css");
?>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1; js_verificaRotina(<?=$db_opcao?>);" bgcolor="#cccccc">

<form name="form1" method="post" action="">
<table align="center" style="padding-top:28px;">
  <tr>
    <td colspan="2"> 
      <fieldset>
        <legend>
          <b>Dados Atendimento</b>
        </legend>
	      <table>
	        <tr>
	          <td nowrap>
	            <b>Usuário:</b>
	          </td>
	          <td colspan="5">
              <?
                db_input('ov01_sequencial',10,'',true,'hidden',3,'');
                db_input('db_opcao'       ,10,'',true,'hidden',3,'');
                db_input('ov01_usuario'   ,10,'',true,'text',3,'');
                db_input('nome'           ,55,'',true,'text',3,'');
              ?>
            </td>
	        </tr>
          <tr>
            <td nowrap>
              <b>Departamento:</b>
            </td>
            <td colspan="5">
              <?
                db_input('ov01_depart',10,'',true,'text',3,'');
                db_input('descrdepto',55,'',true,'text',3,'');
              ?>
            </td>
          </tr>	 
          <tr>
            <td nowrap>
              <b>Nº Atendimento:</b>
            </td>
            <td>
              <?
                db_input('ov01_numero',10,'',true,'text',3,'');
              ?>
            </td>
            <td nowrap>
              <b>Data Atendimento:</b>
            </td>
            <td align="right">
              <?
                db_inputdata('ov01_dataatend',@$ov01_dataatend_dia,@$ov01_dataatend_mes,@$ov01_dataatend_ano,true,'text',3,'');
                echo "<b>Hora Atendimento:</b>";
                db_input('ov01_horaatend',10,'',true,'text',3,'');
              ?>
            </td>
          </tr>  
          </tr>                                                  
        </table>
      </fieldset>
    </td>
  </tr>
  <tr align="center">
    <td width="50%">  
      <fieldset>
        <legend>
          <b>Solicitação</b>
        </legend>
        <?
          db_textarea('ov01_solicitacao',8,40,$Iov01_solicitacao,true,'text',$db_opcao,'');
        ?>
      </fieldset>
    </td>
    <td>
      <fieldset>
        <legend>
          <b>Executado</b>
        </legend>
        <?
          db_textarea('ov01_executado',8,40,$Iov01_executado,true,'text',$db_opcao,'');
        ?>              
      </fieldset>
    </td>          
  </tr>  
  <tr id="dadosAtendimento">
    <td colspan="2"> 
      <fieldset>
        <table>
          <tr>
            <td class="telaInclusao"  nowrap>
              <?
                db_ancora($Lov01_tipoprocesso,'js_pesquisaTipoProcesso(true);',$db_opcao,'');
              ?>
            </td>
            <td class="telaAlteracao" nowrap>
              <?=$Lov01_tipoprocesso?>
            </td>            
            <td>
              <?
                db_input('ov01_tipoprocesso',10,'',true,'text',$db_opcao,"onChange='js_pesquisaTipoProcesso(false);'");
                db_input('p51_descr'        ,50,'',true,'text',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <b>Data Estimada:</b>
            </td>            
            <td>
              <?
                db_input('dataprevista',10,'',true,'text',3,"");
              ?>
            </td>
          </tr>          
          <tr id="docTipoProcesso" style="display:none">
            <td colspan="2">
              <fieldset>
                <legend>
                  <b>Documentos</b>
                </legend>
                <table id="listaDocTipoProcesso">
                </table>
              </fieldset>
            </td>
          </tr>
          <tr >
            <td nowrap>
              <b>Forma Reclamação:</b>
            </td>
            <td class="telaInclusao"  id="listaFormaReclamacao" >
            </td>            
            <td class="telaAlteracao" style="display:none" >
              <?
                db_input('ov01_formareclamacao',10,'',true,'text'  ,3,'');
                db_input('p42_descricao'       ,50,'',true,'text'  ,3,'');
              ?>
            </td>            
          </tr>
          <tr>
            <td nowrap>
              <b>Tipo Requerente:</b>
            </td>
            <td class="telaInclusao" >
              <input type="radio" name="radiotiporequerente" class="tiporequerente" value="2" checked onChange="js_telaRequerente();"/>
              <label><b>Identificado&nbsp&nbsp;&nbsp;</b></label>
              <input type="radio" name="radiotiporequerente" class="tiporequerente" value="1" onChange="js_telaRequerente();"/>
              <label><b>Anônimo</b></label>
            </td>
            <td  class="telaAlteracao" style="display:none" >
              <?
                db_input('ov01_tipoidentificacao',10,'',true,'text'  ,3,'');
                db_input('ov05_descricao'       ,50,'',true,'text'   ,3,'');
              ?>
            </td>            
          </tr>
          
          <tr class="dadosRequerente">
            <td class="telaInclusao">
              <?
                db_ancora('<b>Titular do Atendimento:</b>','js_pesquisaTitularAtendimento();',$db_opcao,'');
              ?>
            </td>
            <td class="telaAlteracao">
              <b>Titular do Atendimento</b>
            </td>            
            <td>
              <?
                db_input('titular'       ,10,'',true,'text'  ,3,'');
                db_input('nometitular'   ,50,'',true,'text'  ,3,'');
                db_input('tipotitular'   ,10,'',true,'hidden',3,'');
                db_input('seqtitular'    ,10,'',true,'hidden',3,'');
                db_input('comretorno'    ,10,'',true,'hidden',3,'');
                db_input('tiposderetorno',10,'',true,'hidden',3,'');
              ?>
              <input type="button" id="processosExistentes" value="Processos Existentes" onClick="js_mostraProcessosExistentes();" disabled/>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <b>Requerente:</b>
            </td>
            <td>
              <?
                db_input('ov01_requerente',64,$Iov01_requerente,true,'text',$db_opcao,'');
              ?>
            </td>
          </tr>
          <tr class='telaInclusao' id='usaEnderecoCgmasLocal' style='display:none'>
             <td>
                 &nbsp;          
             </td>     
             <td>
                <input type="checkbox" id='usarenderecocgm' onclick='js_liberaCadastroLocal()'>
                <label for="usarenderecocgm">
                  <b>Utilizar endereço do CGM</b>
                </label> 
             </td>       
          </tr>
          <tr id='linhaLocal'>
            <td class="telaInclusao" nowrap>
              <?
                db_ancora('<b>Local:</b>','js_pesquisaLocal(true);',$db_opcao,'');
              ?>
            </td>
            <td class="telaAlteracao" nowrap>
              <b>Local:</b>
            </td>          
            <td>
              <?
                db_input('ov24_ouvidoriacadlocal',10,$Iov24_ouvidoriacadlocal,true,'text',1,"onChange='js_pesquisaLocal(false);'");
                db_input('ov25_descricao'        ,50,$Iov25_descricao,true,'text',1,'');
              ?>
            </td>
          </tr>                                                  
	      </table>
      </fieldset>
    </td>
  </tr>
  <tr align="center"  id="retornoIdentificado">
    <td  colspan="2"> 
      <fieldset>
        <legend>
          <b>Tipo de Retorno</b>
        </legend>
        <table align="center">
          <tr>
            <td id="listaTipoRetorno" align="center">
            </td>
          </tr>
          <tr>
            <td>
              <table align="center"  id="listaRetorno"  width="550px;">
              </table>
            </td>
          </tr>   
        </table>
      </fieldset>
    </td>
  </tr>
  <tr align="center" id="retornoAnonimo" style="display:none">
    <td  colspan="2">
      <table width="100%">
        <tr>
          <td>
            <fieldset>
             <legend>
               <input type="checkbox" class="tipoRetornoClass" value="1"/>
 	             <a style='-moz-user-select: none;cursor: pointer' onClick="js_esconderRetorno('enderAnonimo');">
               <b>Retorno por Carta/Pessoalmente</b>
               <img src="imagens/seta.gif" id="enderAnonimotogglefiltros">               
             </legend>
             <table id="enderAnonimo" style="display:none">
               <tr>
                 <input type="checkbox" style="display:none"  id="enderRetornoAnonimo" class="enderRetorno" value="" checked>
                 <td nowrap title="<?=@$Tov12_endereco?>">
                   <?
                     db_ancora(@$Lov12_endereco,"js_pesquisaov12_endereco(true);",$db_opcao);
                   ?>
                 <td>
                   <?
                     db_input('ov12_endereco',50,$Iov12_endereco,true,'text',$db_opcao,"");
                   ?>
                 </td>
                 <td nowrap title="<?=@$Tov12_numero?>" align="right">
                   <?=@$Lov12_numero?>
                 </td>
                 <td>
                   <?
                     db_input('ov12_numero',10,$Iov12_numero,true,'text',$db_opcao,"");
                   ?>
                 </td>
               </tr>
               <tr>
                 <td nowrap title="<?=@$Tov12_bairro?>">
                   <? 
                     db_ancora(@$Lov12_bairro,"js_pesquisaov12_bairro(true);",$db_opcao);
                   ?>
                 </td>
                 <td> 
                   <?
                     db_input('ov12_bairro',50,$Iov12_bairro,true,'text',$db_opcao,"");
                   ?>
                 </td>
                 <td nowrap title="<?=@$Tov12_compl?>">
                   <?=@$Lov12_compl?>
                 </td>
                 <td>
                   <?
                     db_input('ov12_compl',10,$Iov12_compl,true,'text',$db_opcao,"");
                   ?>
                 </td>           
               </tr>
               <tr>
                 <td nowrap title="<?=@$Tov12_munic?>"><?=@$Lov12_munic?></td>
                 <td> 
                   <?
                     db_input('ov12_munic',30,$Iov12_munic,true,'text',$db_opcao,"");
                   ?>
                   <?=@$Lov12_uf?>
                   <?
                     db_input('ov12_uf',2,$Iov12_uf,true,'text',$db_opcao,"");
                   ?>
                 </td>
                 <td nowrap title="<?=@$Tov12_cep?>" align="right"><?=@$Lov12_cep?></td>
                 <td> 
                   <?
                     db_input('ov12_cep',10,$Iov12_cep,true,'text',$db_opcao,"")
                   ?>
                 </td>
               </tr>
             </table>
           </fieldset>
         </td>
       </tr>  
       <tr>
         <td>
           <fieldset>
             <legend>
               <input type="checkbox" class="tipoRetornoClass" value="3"/>
               <a style='-moz-user-select: none;cursor: pointer' onClick="js_esconderRetorno('emailAnonimo');">
               <b>Retorno por Email</b>
               <img src="imagens/seta.gif" id="emailAnonimotogglefiltros">               
             </legend>
             <table id="emailAnonimo" style="display:none" width="600px;" align="center">
               <tr>
                 <td nowrap title="<?=@$Tov13_email?>">
                    <?=@$Lov13_email?>
                 </td>
                 <td>
                   <input type="hidden" id="alteraEmail" value="" name="alteraEmail"> 
                   <?
                     db_input('ov13_email',60,$Iov13_email,true,'text',$db_opcao,"");
                   ?>
                   <span id="btnEmail">
                     <input name="incluiEmail" type="button" id="incluiEmail" value="Incluir" onclick="js_incluirEmailTela();" >
                     <input name="novoEmail" type="button" style="display: none;" id="novoEmail" value="Novo" onclick="js_NovoEmail();" >
                   </span>
                 </td>
               </tr>
               <tr>
                 <td colspan="2">
                   <fieldset>
                     <legend>
                       <b>Lista Emails</b>
                     </legend>
                     <div id="listaemails">
                     </div>
                   </fieldset>
                 </td>
               </tr>
             </table>
           </fieldset>
         </td>
         </tr>
         <tr>
           <td>
             <fieldset>
               <legend>
                 <input type="checkbox" class="tipoRetornoClass" value="4"/>
                 <a style='-moz-user-select: none;cursor: pointer' onClick="js_esconderRetorno('telefoneAnonimo');">
                 <b>Retorno por Telefone/Fax</b>
                  <img src="imagens/seta.gif" id="telefoneAnonimotogglefiltros">                 
               </legend>
               <table width="90%"  id="telefoneAnonimo" style="display:none"  align="center">
                 <tr>
                   <td nowrap title="<?=@$Tov14_tipotelefone?>"><?=@$Lov14_tipotelefone?></td>
                   <td> 
                     <?
                       $rsTipoTelefone = $clTelefoneTipo->sql_record($clTelefoneTipo->sql_query_file());
                       db_selectrecord('ov14_tipotelefone',$rsTipoTelefone,true,$db_opcao,'','','','','',1);
                     ?>
                   </td>
                 </tr>
                 <tr>
                   <td nowrap title="<?=@$Tov14_ddd?>"><?=@$Lov14_ddd?></td>
                   <td> 
                     <?
                       db_input('ov14_ddd',10,$Iov14_ddd,true,'text',$db_opcao,"");
                     ?>
                   </td>
                   <td nowrap title="<?=@$Tov14_numero?>" align="right"><?=@$Lov14_numero?></td>
                   <td align="right"> ouv1_cadatendimento001.php
                     <?
                       db_input('ov14_numero',10,$Iov14_numero,true,'text',$db_opcao,"");
                       db_input('alteraTelefone',10,'',true,'hidden',1,'');
                     ?>
                   </td>
                   <td nowrap title="<?=@$Tov14_ramal?>" align="right"><?=@$Lov14_ramal?></td>
                   <td align="right"> 
                     <?
                       db_input('ov14_ramal',10,$Iov14_ramal,true,'text',$db_opcao,"");
                     ?>
                   </td>
                 </tr>
                 <tr>
                   <td nowrap title="<?=@$Tov14_obs?>"><?=@$Lov14_obs?></td>
                   <td colspan="5"> 
                     <?
                       db_textarea('ov14_obs',3,68,$Iov14_obs,true,'text',$db_opcao,"");
                     ?>
                   </td>
                 </tr>
                 <tr align="center">                 
                   <td align="right" colspan="3">
                     <input name="incluitelefone" type="button" id="incluiTelefone" value="Incluir" onclick="js_incluirTelefoneTela();">
                   </td>
                   <td align="left" >
                     <input name="incluitelefone" type="button" id="novoTelefone" value="Novo" style="display:none" onclick="js_incluirTelefoneTela();">
                   </td>
                 </tr>                  
                 <tr align="center">
                   <td colspan="6">
                     <fieldset>
                       <legend>
                         <b>Lista Telefones</b>
                       </legend>
                       <div id="listatelefones">
                       </div>
                     </fieldset>
                   </td>
                 </tr>
               </table>
             </fieldset>
           </td>
         </tr>              
       </table>
    </td>
  </tr>      
  <tr class="telaInclusao">
    <td  colspan="2"> 
      <fieldset>
        <legend>
          <b>Processo Existente</b>
        </legend>
        <table>
          <tr>
            <td id="idAnexaProcessoAncora">
              <?
                db_ancora('<b>Código do Processo:</b>','js_pesquisaProcesso(true);',$db_opcao,'');
              ?>
            </td>
            <td id="idAnexaProcesso" style="display:none">
              <b>Código do Processo:</b>
            </td>            
            <td>
              <?
                db_input('ov09_protprocesso',10,$Iov09_protprocesso,true,'text',$db_opcao,"onChange='js_pesquisaProcesso(false);'"); 
                db_input('requerprocesso',40,'',true,'text',3,'');
              ?>
              <input type="button" name="anexar" id="anexar" class="botao" value="Anexar ao Processo"  onClick="js_incluirAtendimento('anexaProcesso');" disabled ></input>
            </td>            
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>  
  <tr class="telaInclusao">
    <td align="center" colspan="2">
      <span id="spanBtnBotoesPadrao">
        <input type="button" name="finalizar" class="botao" id="finalizar" value="Finalizar Atendimento" onClick="js_incluirAtendimento('finaliza');"></input>
	      <input type="button" name="gerar"     class="botao" id="gerar"     value="Gerar Processo"        onClick="js_incluirAtendimento('geraProcesso');"></input>
	      <input type="button" name="novo"                    id="novo"      value="Novo Atendimento"      onClick="js_novoAtendimento(1);"></input>
	    </span>
	    <span id="spanBtnBotaoAlteracao" style="display:none;">
        <input type="button" name="finalizar" class="botao" id="alterar" value="Alterar Atendimento" onClick="js_incluirAtendimento('alterar');"></input>
	    </span>
	    <span id="spanBtnPesquisarAtendimento" style="display:none;">
        <input type="button" name="btnPesquisarAtendimento" class="botao" id="btnPesquisarAtendimento" value="Pesquisar Atendimento" onClick="js_pesquisaAtendimento();"></input>
	    </span>
    </td>
  </tr>
  <tr class="telaAlteracao">
    <td align="center" colspan="2">
      <input type="button" name="alterar"                 id="alterar"   value="Alterar"   onClick="js_alteraAtendimento();"></input>
      <input type="button" name="pesquisar"               id="pesquisar" value="Pesquisa"  onClick="js_pesquisaAtendimento();"></input>
    </td>
  </tr>
</table>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

  document.form1.ov25_descricao.style.width = '375px';

  oAutoComplete = new dbAutoComplete(document.form1.ov25_descricao,'ouv4_pesquisalocal.RPC.php');
  oAutoComplete.setTxtFieldId(document.getElementById('ov24_ouvidoriacadlocal'));
  oAutoComplete.show();
  
  var sUrl = 'ouv1_atendimento.RPC.php';
  var aLinhasEmails         = new Array();
  var aLinhasTelefones      = new Array();
  var aTipoRetornoAlteracao = new Array(); 
 
  function js_consultaDadosTipoProcesso( iCodTipoProc ){
  
    js_divCarregando('Aguarde...','msgBox');
   
    var sQuery  = 'sMethod=consultaDadosTipoProcesso';
        sQuery += '&iCodTipoProc='+iCodTipoProc;

    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoConsultaDadosTipoProcesso
                                          }
                                  );      
  
  }



  function js_retornoConsultaDadosTipoProcesso(oAjax){

    js_removeObj("msgBox");
   
    var aRetorno = eval("("+oAjax.responseText+")");
     
    js_validaIdentidicacao( aRetorno.lIdentificado );
    js_montaListaDocumentos( aRetorno.aListaDocumentos );
    js_validaFormaReclamacao( aRetorno.aListaFormaReclamacao );
    js_consultaDataPrevista();
  }
  
  function js_consultaDataPrevista(){
  
    js_divCarregando('Aguarde...','msgBox');
   
    var sQuery  = 'sMethod=consultaDataPrevista';
        sQuery += '&iCodAtendimento='+$F('ov01_sequencial');
        sQuery += '&iCodProc='+$F('ov09_protprocesso');
        sQuery += '&iCodTipoProc='+$F('ov01_tipoprocesso');
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoDataPrevista
                                          }
                                  );      
  
  }



  function js_retornoDataPrevista(oAjax){

    js_removeObj("msgBox");
   
    var aRetorno = eval("("+oAjax.responseText+")");
    $('dataprevista').value =  aRetorno.sDataPrevista;
    
  }  
 
 
  function js_consultaDadosRequerente( iCodRequerente, sTipo, sCallBack){
  
    js_divCarregando('Aguarde...','msgBox');
   
    var sQuery  = 'sMethod=consultaDadosRequerente';
        sQuery += '&iCodRequerente='+iCodRequerente;
        sQuery += '&iSeq='+$F('seqtitular');
        sQuery += '&sTipoRequerente='+sTipo;

    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: sCallBack
                                          }
                                  );      
  
  }



  function js_retornoConsultaDadosRequerente(oAjax){

    js_removeObj("msgBox");
    var aRetorno = eval("("+oAjax.responseText+")");
    
    $('tiposderetorno').value = Object.toJSON(aRetorno.aListaTipoRetorno);
    
    if ( eval(aRetorno.lTemProcessos) ) {
      $('processosExistentes').disabled = false;
    } else {
      $('processosExistentes').disabled = true;
    }
    
    js_montaTipoRetorno(aRetorno.aListaEnder,
                        aRetorno.aListaEmail,
                        aRetorno.aListaTelefone);
                        
  } 
 

  function js_montaTipoRetorno(aListaEnder,aListaEmail,aListaTelefone){
  
    var aElemTipoRetorno = $$('#retornoIdentificado input.tipoRetornoClass');
    var sHtml            = '';
    
    aElemTipoRetorno.each(
      function ( elemTipoRetorno, iInd ) {
        elemTipoRetorno.disabled = true;
        elemTipoRetorno.checked  = false;
      }
    );  
    
    if ($('semretorno') != undefined) {
      $('semretorno').disabled = false;
    }

    if ( aListaEnder.length > 0 ) {
    
      aElemTipoRetorno[0].disabled = false;
      aElemTipoRetorno[1].disabled = false;
      aElemTipoRetorno[0].checked  = true;
      aElemTipoRetorno[1].checked  = true;
      
      aListaEnder.each(
      
        function ( oEnder, iInd ){
        
          var iSeq    = ++iInd;
          var iId     = iSeq+'ender';
          var jsonEnd = Object.toJSON(oEnder);
          
          with(oEnder){
	          sHtml += '<tr class="enderretornoRow" width="100%;">';
	          sHtml += '  <td> ';
	          sHtml += '    <fieldset>';
	          sHtml += '      <legend> ';
            sHtml += "        <input type='checkbox' class='enderRetorno' value='"+jsonEnd+"'checked />";
            sHtml += "        <a style='-moz-user-select: none;cursor: pointer' onClick='js_esconderRetorno(\""+iId+"\");'>";
            sHtml += '        <b> '+iSeq+' - Endereço</b>';
            sHtml += '        <img src="imagens/setabaixo.gif" id="'+iId+'togglefiltros">';
            sHtml += '      </legend> ';
            sHtml += '      <table align="center" id="'+iId+'"> ';
						sHtml += '<tr>                                                                                       ';
						sHtml += '  <td><b>Endereço:</b></td>                                                                ';
						sHtml += '  <td colspan="5"><input type="text" size="60px;" value="'+ov12_endereco.urlDecode()+'" ></td>';                     
						sHtml += '</tr>                                                                                      ';
						sHtml += '<tr>                                                                                       ';
						sHtml += '  <td><b>Número:</b></td>                                                                  ';
						sHtml += '  <td><input type="text"   size="10px;"value="'+ov12_numero+'"><b>Complemento:</b></td>';                     
						sHtml += '  <td colspan="4"><input type="text" size="26px;"value="'+ov12_compl.urlDecode()+'"></td>';           
						sHtml += '</tr>                                                                                      ';
						sHtml += '<tr>                                                                                       ';
						sHtml += '  <td><b>Bairro:</b></td>                                                                  ';
						sHtml += '  <td colspan="5"><input type="text" size="60px;"value="'+ov12_bairro.urlDecode()+'"></td>';                     
						sHtml += '</tr>                                                                                      ';
						sHtml += '<tr>                                                                                       ';
						sHtml += '  <td><b>Município:</b></td>                                                               ';
						sHtml += '  <td><input type="text"   size="30px;"value="'+ov12_munic.urlDecode()+'"></td>';                     
						sHtml += '  <td><b>UF:</b></td>                                                                      ';
						sHtml += '  <td><input type="text"   size="3px;"value="'+ov12_uf+'"></td>     ';
						sHtml += '  <td><b>CEP:</b></td>                                                                     ';
						sHtml += '  <td align="right"><input type="text" size="10px;" value="'+ov12_cep+'"></td>';                                                               
						sHtml += '</tr>                                                                                                   ';
						sHtml += '</table></fieldset></td></tr>                                                                           ';
          }
        }
      );
      
    }

    if ( aListaEmail.length > 0 ) {
      aElemTipoRetorno[2].disabled = false;
      aElemTipoRetorno[2].checked  = true;
            
      aListaEmail.each(
        function ( oEmail, iInd ){
          
          var iSeq      = ++iInd;
          var iId       = iSeq+'email';
          var jsonEmail =  Object.toJSON(oEmail);
          with(oEmail){
	          sHtml += '<tr class="emailretornoRow"><td><fieldset> ';
	          sHtml += '  <legend>         ';
            sHtml += "    <input type='checkbox' class='emailRetorno' value='"+jsonEmail+"' checked />";
            sHtml += "    <a style='-moz-user-select: none;cursor: pointer' onClick='js_esconderRetorno(\""+iId+"\");'>";
	          sHtml += '    <b>'+iSeq+' - Email</b> ';
            sHtml += '    <img src="imagens/setabaixo.gif" id="'+iId+'togglefiltros">';	          
            sHtml += '	</legend>        ';
	          sHtml += '  <table id="'+iId+'">                            ';
	          sHtml += '    <tr>                                          ';
	          sHtml += '      <td><b>Email:</b></td>                      ';
	          sHtml += '      <td><input type="text" size="60px" value="'+ov13_email.urlDecode()+'"/></td>';
	          sHtml += '    </tr>                                         ';
	          sHtml += '  </table>                                        ';
	          sHtml += '</fieldset></td></tr>                             ';
          } 
        }
      );
      
    }

    if ( aListaTelefone.length > 0 ) {
      aElemTipoRetorno[3].disabled = false;
      aElemTipoRetorno[3].checked  = true;

      aListaTelefone.each(
        function ( oTelefone, iInd ){
        
          var iSeq    = ++iInd;
          var iId     = iSeq+'telefone';
          var jsonTel =  Object.toJSON(oTelefone);        
          
          with(oTelefone){
	          sHtml += '<tr class="telefoneretornoRow">';
	          sHtml += '  <td>';
	          sHtml += '    <fieldset>';
	          sHtml += '      <legend>';
            sHtml += "        <input type='checkbox' class='telefoneRetorno' value='"+jsonTel+"' checked />";            	          
            sHtml += "        <a style='-moz-user-select: none;cursor: pointer' onClick='js_esconderRetorno(\""+iId+"\");'>";
	          sHtml += '        <b>'+iSeq+' - Telefone</b>';
            sHtml += '        <img src="imagens/setabaixo.gif" id="'+iId+'togglefiltros">';	          
	          sHtml += '      </legend>';
	          sHtml += '      <table id="'+iId+'">';
	          sHtml += '        <tr>';
	          sHtml += '          <td><b>Número:</b></td>';
	          sHtml += '          <td><input type="text" size="10px;" value="'+ov14_numero+'"></td>';
	          sHtml += '          <td><b>DDD:</b></td>';
	          sHtml += '          <td><input type="text" size="10px;" value="'+ov14_ddd+'"></td>';
	          sHtml += '        </tr>';
	          sHtml += '        <tr>';
	          sHtml += '          <td><b>Tipo Telefone:</b></td>';
	          sHtml += '          <td><input type="hidden" size="10px;" value="'+ov14_tipotelefone+'">';
	          sHtml += '              <input type="text" size="10px;"   value="'+ov23_descricao.urlDecode()+'"></td>';
	          sHtml += '          <td><b>Ramal:</b></td>';
	          sHtml += '          <td><input type="text" size="10px;"   value="'+ov14_ramal+'"></td>';
	          sHtml += '        </tr>                         ';
	          sHtml += '        <tr>                          ';
	          sHtml += '          <td><b>Observações:</b></td>';
	          sHtml += '          <td colspan="3"><textarea rows="3" cols="28">'+ov14_obs.urlDecode()+'</textarea></td>';                        
	          sHtml += '        </tr>';
	          sHtml += '      </table>';              
	          sHtml += '    </fieldset>';
	          sHtml += '  </td>';
	          sHtml += '</tr>';
          }
        }
      );
      
                          
    }    
    
    if ( aListaTelefone.length > 0 || aListaEmail.length > 0 || aListaEnder.length > 0 ){

      if ($('semretorno') != undefined) {
        $('semretorno').checked   = false;
      }

      $('comretorno').value     = true;
    } else {

      if ($('semretorno') != undefined) {
        $('semretorno').checked   = true;
      }

      $('comretorno').value     = false;
    }
    
    $('listaRetorno').innerHTML = sHtml;
    
    js_desabilitaCamposRetorno(true);
    
  }

 
  function js_montaListaDocumentos( aListaDocumentos ){
  
    var iNroDoc  = aListaDocumentos.length;
    
    if ( iNroDoc > 0 ) {
      
      $('docTipoProcesso').style.display  = '';
     
      var sHtml = '<tr>';
      
      aListaDocumentos.each(
        function ( oDocumento, iInd ) {
          sHtml += '<td>';
          sHtml += ' <input type="checkbox" class="procdoc" value="'+oDocumento.p56_coddoc+'"/>';
          sHtml += ' <label><b>'+oDocumento.p56_descr.urlDecode()+'</b></label>';
          sHtml += '</td>';
        }
      ); 
      
      sHtml += '</tr>';
      
      $('listaDocTipoProcesso').innerHTML = sHtml;
       
    } else {
    
      $('docTipoProcesso').style.display  = 'none';
      $('listaDocTipoProcesso').innerHTML = '';
       
    }
    
  
  }
 
   
  function js_telaRequerente(){

    var aElemRequerente     = $$('tr.dadosRequerente');
    var aElemTipoRequerente = $$('input.tiporequerente');
    var eAnonimo            = $('retornoAnonimo');
    var eIdentificado       = $('retornoIdentificado');
              
    aElemTipoRequerente.each(
      function ( eElem ,  iInd) {
        if ( eElem.checked ) {
          sTipoRequerente = eElem.value;
        }
      }    
    );
    
    if ( sTipoRequerente == 1 ) {
      eAnonimo.style.display      = '';
      eIdentificado.style.display = 'none'; 
    } else {
      eAnonimo.style.display      = 'none';
      eIdentificado.style.display = '';     
    }
    
    
    aElemRequerente.each(
      function ( eElem ,  iInd) {
		    if ( sTipoRequerente == '1') {
		      eElem.style.display = 'none';
		    } else {
		      eElem.style.display = '';
		    }
      }
    );
  
  } 
   
   
  function js_validaIdentidicacao( lIdentifica ){
    
    var eRadioAnonimo      = $$('input.tiporequerente[value="1"] ');
        eRadioAnonimo      = eRadioAnonimo[0];
    var eRadioIdentificado = $$('input.tiporequerente[value="2"] ');
        eRadioIdentificado = eRadioIdentificado[0];
    
    if ( lIdentifica ) { 
      eRadioIdentificado.checked = true;
      eRadioAnonimo.disabled     = true;   
    } else {
      eRadioAnonimo.disabled     = false;
    }
    
    js_telaRequerente();
  
  } 
   
  function js_validaFormaReclamacao( aListaFormaReclamacao ) {
    
    var aElemFormaReclamcacao = $$('input.formareclamacao');
    var lChk = true;
    
    aElemFormaReclamcacao.each(
      function ( elemFormaReclamacao, iIndEle ) {    
        elemFormaReclamacao.disabled = true;    
        elemFormaReclamacao.checked  = false;
		    aListaFormaReclamacao.each(
		      function ( oFormaReclamacao, iIndObj ) {
            if ( oFormaReclamacao.p42_sequencial == elemFormaReclamacao.value ) {
              if ( lChk ) {
                elemFormaReclamacao.checked = true;
                lChk = false;
              }
              elemFormaReclamacao.disabled  = false;
            }		    
		      }
		    );
      }
    );
  
  } 
     
  function js_validaTipoRetorno( eChkTipoRetorno ) {
  
    var aElemTelef  = $$('tr.telefoneretornoRow'); 
    var aElemEmail  = $$('tr.emailretornoRow');
    var aElemEnder  = $$('tr.enderretornoRow');
    var lComRetorno = eval($F('comretorno'));
    
    var aElemChkTipoRetorno        = $$('#retornoIdentificado input.tipoRetornoClass[value!="0"]');
    var aElemChkTipoRetornoPessoal = $$('#retornoIdentificado input.tipoRetornoClass[value=1]');
    var aElemChkTipoRetornoCarta   = $$('#retornoIdentificado input.tipoRetornoClass[value=2]');
    var aElemChkTodosTipoRetorno   = $$('#retornoIdentificado input.tipoRetornoClass');
    var lValidaTodosCampos         = false;
    
    if ( $('tiposderetorno').value.trim() != '' ) {
      var aListaTipoRetorno = ($('tiposderetorno').value).evalJSON();
    } else {
      var aListaTipoRetorno = new Array();
    }

    if ( eChkTipoRetorno.value != "0" ) {
	    aElemChkTodosTipoRetorno.each(
	      function ( eTipoRetorno, iInd) {
	        if ( eTipoRetorno.checked ) {
	          lValidaTodosCampos = true;
	        } 
	      }
	    );
	    if ( !lValidaTodosCampos ) {
	      $('semretorno').checked = true;
	      js_validaTipoRetorno($('semretorno'));
	      return false;
	    }
    }
    
	  if ( eChkTipoRetorno.value == "1" || eChkTipoRetorno.value == "2") {  
		  aElemEnder.each(
		    function ( elemEnder, iInd ) {
		      if ( eChkTipoRetorno.checked ) {
		        elemEnder.style.display = '';
		      } else {
		        if ( ( eChkTipoRetorno.value == '1' && !aElemChkTipoRetornoCarta[0].checked ) || ( eChkTipoRetorno.value == '2' && !aElemChkTipoRetornoPessoal[0].checked ) ) {
		          elemEnder.style.display = 'none';
		        }
		      }     
		    }
		 );
	  } else if ( eChkTipoRetorno.value == "3") { 
	    aElemEmail.each(
	      function ( elemEmail, iInd ) {
	        if ( eChkTipoRetorno.checked ) {
	          elemEmail.style.display = '';
	        } else {
	          elemEmail.style.display = 'none';
	        }     
	      }
	    );  
	  } else if ( eChkTipoRetorno.value == "4" ) {       
		  aElemTelef.each(
		    function ( elemTelef, iInd ) {
		      if ( eChkTipoRetorno.checked ) {
		        elemTelef.style.display = '';
		      } else {
		        elemTelef.style.display = 'none';
		      }     
		    }
		  );
    } else if ( eChkTipoRetorno.value == "0" ) {
      if ( eChkTipoRetorno.checked ) {
        $('listaRetorno').style.display = 'none';
        aElemChkTipoRetorno.each(
          function ( eChk, iInd ) {
            eChk.disabled = true;
          }
        );        
      } else {
        if ( lComRetorno ) {
	        $('listaRetorno').style.display = '';
	        aElemChkTipoRetorno.each(
	          function ( eChk, iInd ) {
	            aListaTipoRetorno.each(
	              function ( oTipoRetorno, iIndTipoRet ){
	                if ( eChk.value == oTipoRetorno.ov04_tiporetorno ) {
	                 eChk.disabled = false;
	                 eChk.checked  = true;
	                 js_validaTipoRetorno(eChk);
	                } 
	              }
	            );
	          }
	        );         
        } else {
          eChkTipoRetorno.checked = true;
        }      
      }
    }		    

  } 
   
   
   
	function js_pesquisaTipoProcesso( lMostra ){
	  
	  if( lMostra ){
	    js_OpenJanelaIframe('top.corpo','db_iframe_tipoproc','func_tipoprocdepto.php?depto='+$F('ov01_depart')+'&grupo=2&funcao_js=parent.js_mostraTipoProcesso1|p51_codigo|p51_descr','Tipo de Processo',true);
	  }else{
	     if( $F('ov01_tipoprocesso') != '' ){ 
	       js_OpenJanelaIframe('top.corpo','db_iframe_tipoproc','func_tipoprocdepto.php?depto='+$F('ov01_depart')+'&grupo=2&pesquisa_chave='+$F('ov01_tipoprocesso')+'&funcao_js=parent.js_mostraTipoProcesso','Tipo de Processo',false);
	     }else{
	       document.form1.p51_descr.value = ''; 
	     }
	  }
	  
	}
	
	function js_mostraTipoProcesso(chave,lErro){
	  
	  document.form1.p51_descr.value = chave;
    
    $('dataprevista').value      = '';	  
	  $('ov09_protprocesso').value = '';
	  $('requerprocesso').value    = '';
	  $('anexar').disabled         = true;
	   
	  if( lErro ){ 
	    document.form1.ov01_tipoprocesso.focus(); 
	    document.form1.ov01_tipoprocesso.value = '';
	    
      js_validaIdentidicacao( new Array() );
	    js_montaListaDocumentos( new Array() );
	    js_validaFormaReclamacao( new Array() );
	    return false;
	     
	  } else {
	    js_consultaDadosTipoProcesso(document.form1.ov01_tipoprocesso.value);
	  }
	  
	}
	
	function js_mostraTipoProcesso1(chave1,chave2){
	  document.form1.ov01_tipoprocesso.value = chave1;
	  document.form1.p51_descr.value         = chave2;
    
    $('dataprevista').value      = '';    
    $('ov09_protprocesso').value = '';
    $('requerprocesso').value    = '';	
    $('anexar').disabled         = true;  
	  
	  db_iframe_tipoproc.hide();
	  js_consultaDadosTipoProcesso(chave1);
	}
  

  function js_alteraCadastro(iCodigo,sTipo,iSeq){
    db_iframe_detalhes.hide();
    js_OpenJanelaIframe('top.corpo','db_iframe_cadcidadao','ouv1_cadcidadaoatendimento001.php?iCodigo='+iCodigo+'&iSeq='+iSeq+'&sTipo='+sTipo,'Detalhes',true);
  }
  
  function js_confirmaSelecao(iCodigo,sTipo,iSeq,sNome){
    db_iframe_detalhes.hide();
    js_mostraTitularAtend(iCodigo,iSeq,sNome,sTipo);
  }
  
  function js_confirmaCadastro(iCodigo,iSeq,sNome){
    db_iframe_cadcidadao.hide();
    js_mostraTitularAtend(iCodigo,iSeq,sNome,'Cidadao');    
  }  


  function js_pesquisaTitularAtendimento(){
    js_OpenJanelaIframe('top.corpo','db_iframe_titularatend','func_cgmcidadao.php?funcao_js=parent.js_mostraTitularAtend|codigo|db_seq|nome|tipo','Titular do Atendimento',true);
  }

  function js_mostraTitularAtend(iCodigo,iSequencial,sNome,sTipo){
  
    document.form1.titular.value         = iCodigo;
    document.form1.seqtitular.value      = iSequencial;
    document.form1.nometitular.value     = sNome.urlDecode();
    document.form1.ov01_requerente.value = sNome.urlDecode(); 
    document.form1.tipotitular.value     = sTipo;
    db_iframe_titularatend.hide();
    $('usaEnderecoCgmasLocal').style.display='none';
    if (sTipo == 'CGM') {
      $('usaEnderecoCgmasLocal').style.display='';  
    }
    js_consultaDadosRequerente(iCodigo, sTipo, js_retornoConsultaDadosRequerente);
    
  }

  function js_mostraProcessosExistentes(){
    
    var sQuery  = '?iCodigo='+$F('titular');
        sQuery += '&sTipoTitular='+$F('tipotitular');
        sQuery += '&iGrupo=2';

    js_OpenJanelaIframe('top.corpo','db_iframe_listaProc','func_processoouvidoriacidadao.php'+sQuery,'Processo Existente',true);
    
  }


  function js_pesquisaLocal( lMostra ){
    
    if( lMostra ){
      js_OpenJanelaIframe('','db_iframe_local','func_ouvidoriacadlocalatend.php?funcao_js=parent.js_mostraLocal1|ov25_sequencial|ov25_descricao','Pesquisa Local',true);
    } else {
      if( $F('ov24_ouvidoriacadlocal') != '' ){ 
        js_OpenJanelaIframe('','db_iframe_local','func_ouvidoriacadlocal.php?pesquisa_chave='+$F('ov24_ouvidoriacadlocal')+'&funcao_js=parent.js_mostraLocal','Pesquisa Local',false);
      }else{
        document.form1.ov25_descricao.value = '';
      }
    }
    
  }
  
  function js_mostraLocal(chave,lErro){
    
    document.form1.ov25_descricao.value = chave;
    if( lErro ){ 
      document.form1.ov24_ouvidoriacadlocal.focus(); 
      document.form1.ov24_ouvidoriacadlocal.value = '';
      return false; 
    }
    
  }
  
  function js_mostraLocal1(chave1,chave2){
    document.form1.ov24_ouvidoriacadlocal.value = chave1;
    document.form1.ov25_descricao.value         = chave2;
    db_iframe_local.hide();
  }




  function js_pesquisaProcesso( lMostra ){
    
    if ( $F('ov01_tipoprocesso') == '' ) {
      alert('Escolha um tipo de processo!');
      return false;
    }    
    
    if( lMostra ){
      js_OpenJanelaIframe('top.corpo','db_iframe_proc','func_protprocessoouvidoria.php?grupo=2&tipo='+$F('ov01_tipoprocesso')+'&funcao_js=parent.js_mostraProcesso1|p58_codproc|p58_requer','Processo Existente',true);
    } else {
      if( $F('ov09_protprocesso') != '' ){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_proc','func_protprocessoouvidoria.php?grupo=2&tipo='+$F('ov01_tipoprocesso')+'&pesquisa_chave='+$F('ov09_protprocesso')+'&funcao_js=parent.js_mostraProcesso','Processo Existente',false);
      }else{
        document.form1.requerprocesso.value = '';
        $('anexar').disabled                = true; 
      }
    }
    
  }
  
  function js_mostraProcesso(chave,chave1,lErro){
    document.form1.requerprocesso.value = chave1;
    if( lErro ){ 
      document.form1.ov09_protprocesso.focus(); 
      document.form1.ov09_protprocesso.value = '';
      $('anexar').disabled = true;
    } else {
      js_consultaDataPrevista();
	    $('anexar').disabled = false;
    }
    
  }
  
  function js_mostraProcesso1(chave1,chave2){
    document.form1.ov09_protprocesso.value = chave1;
    document.form1.requerprocesso.value    = chave2;
    $('anexar').disabled                   = false;
    db_iframe_proc.hide();
    js_consultaDataPrevista();
  }



  function js_consultaDadosTela(){
  
    js_divCarregando('Aguarde...','msgBox');
   
    var sQuery  = 'sMethod=consultaDadosTela';

    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoConsultaDadosTela
                                          }
                                  );  
  
  }
  
  
  
  
  function js_retornoConsultaDadosTela(oAjax){
  
    js_removeObj("msgBox");
    var aRetorno = eval("("+oAjax.responseText+")");
    
    js_montaFormaReclamacaoPadrao(aRetorno.aListaFormaReclamacao);
    js_montaTipoRetornoPadrao(aRetorno.aListaTipoRetorno);
    
        
  }
  
  
  
  function js_montaFormaReclamacaoPadrao( aListaFormaReclamacao ){
  
    var sHtml = '';
    
    aListaFormaReclamacao.each(
      function ( oFormaReclamacao, iInd ) {
        with(oFormaReclamacao){
	        sHtml += '<input type="radio" class="formareclamacao" name="radioformareclamacao" id="radioformareclamacao" value="'+p42_sequencial+'"/>';
	        sHtml += '<label><b>'+p42_descricao.urlDecode()+'</b></label>';
        } 
      }
    );
  
    $('listaFormaReclamacao').innerHTML = sHtml;
     
  }
  
  
  function js_montaTipoRetornoPadrao( aListaTipoRetorno ){
  
    var sHtml = '';
    
    aListaTipoRetorno.each(
      function ( oTipoRetorno, iInd ) {
        with(oTipoRetorno){
          sHtml += '<input type="checkbox" class="tipoRetornoClass" name="chktiporetorno" value="'+ov22_sequencial+'" disabled onChange="js_validaTipoRetorno(this)"/>';
          sHtml += '<label><b>'+ov22_descricao.urlDecode()+'</b></label>';                   
        } 
      }
    );
    
    sHtml += '<input type="checkbox" class="tipoRetornoClass" id="semretorno" name="chktiporetorno" value="0" checked onChange="js_validaTipoRetorno(this) />';
    sHtml += '<label><b>Sem Retorno</b></label>';
    
    $('listaTipoRetorno').innerHTML = sHtml;
     
  }  
  
  function js_novoAtendimento(sTipo){
	  <?
	    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?db_opcao='+sTipo;";
	  ?>
  }

  
  function js_incluirAtendimento(sTipo){
    
    js_desabilitaBotoes(true);
    
    var iTipoIdentificacao = js_getTipoIdentificacao();
        
    if ( iTipoIdentificacao == 1 ) {
      
      var oEnder = new Object();
		      oEnder.ov12_endereco = $F('ov12_endereco');
		      oEnder.ov12_numero   = $F('ov12_numero');
		      oEnder.ov12_compl    = $F('ov12_compl');
		      oEnder.ov12_bairro   = $F('ov12_bairro');
		      oEnder.ov12_munic    = $F('ov12_munic');
		      oEnder.ov12_uf       = $F('ov12_uf');
		      oEnder.ov12_cep      = $F('ov12_cep');
		      
		  $('enderRetornoAnonimo').value = Object.toJSON(oEnder);
		        
    }
    
    if ( !js_validaCamposTela() ){
      js_desabilitaBotoes(false);
      return false;
    }
    
    var oAtendimento = js_getDadosAtendimento();
    var aDocumentos  = js_getDocumentos();
    var oRetorno     = js_getDadosRetorno();
    
    js_divCarregando('Aguarde...','msgBox');
    oAtendimento.usarenderecocgm  = $('usarenderecocgm').checked;
    var sQuery  = 'sMethod=incluirAtendimento';
        sQuery += '&oAtendimento='+Object.toJSON(oAtendimento);
        sQuery += '&aDocumento='+Object.toJSON(aDocumentos);
        sQuery += '&oRetorno='+Object.toJSON(oRetorno);
        sQuery += '&sTipo='+sTipo;
        sQuery += '&iCodProc='+$F('ov09_protprocesso');
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoIncluirAtendimento
                                          }
                                  );          
    
    
  }
  
  
  function js_retornoIncluirAtendimento(oAjax){
  
    js_removeObj("msgBox");
   
    var aRetorno = eval("("+oAjax.responseText+")");
    var sExpReg  = new RegExp('\\\\n','g');
  
    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));

    if ( aRetorno.lErro ) {
      js_desabilitaBotoes(false);
      return false;
    } else {
	    $('ov09_protprocesso').disabled          = true; 
	    $('idAnexaProcessoAncora').style.display = 'none';
	    $('idAnexaProcesso').style.display       = '';
    }

    if (!confirm("Deseja emitir a ficha de atendimento?")) {
			return false;
    }
    
    var sLocation   = "ouv2_fichaatendimentoagata002.php?ov01_numero="+aRetorno.iAtendimento+"&ov01_anousu="+aRetorno.iAno;
    jan = window.open(sLocation, '', 
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
  
  }
  
  /**
   * Retorna o tipo de identificação do Requerente do processo
   * os tipos validos sao: 1 - Cidadao, 2 - CGM
   */
  function js_getTipoIdentificacao(){
    
    var aElemTipoIdentificacao = $$('input.tiporequerente');
    var iTipoIdentificacao     = '';
    
    aElemTipoIdentificacao.each(
      function ( eTipoIdentificacao, iInd ){
        if ( eTipoIdentificacao.checked ) {
          iTipoIdentificacao = eTipoIdentificacao.value;
        }       
      }      
    );
    
    return iTipoIdentificacao;     
  }


  function js_getDadosRetorno(){
    
    var aTipoRetorno       = new Array();
    var aRetornoEndereco   = new Array();
    var aRetornoEmail      = new Array();
    var aRetornoTelefone   = new Array();
    var iTipoIdentificacao = js_getTipoIdentificacao();

    if ( iTipoIdentificacao == 1 ) {  
      var sIdTipo = 'retornoAnonimo';
    } else {
      var sIdTipo = 'retornoIdentificado';      
    }

    var aElemTipoRetorno = $$('#'+sIdTipo+' input.tipoRetornoClass');
     
    aElemTipoRetorno.each(
      function ( eTipoRetorno, iInd ) {
      
	      if ( eTipoRetorno.value != '0' && eTipoRetorno.checked && !eTipoRetorno.disabled ) {
	      
		    var oTipoRetorno = new Object();
				    oTipoRetorno.ov17_tiporetorno = eTipoRetorno.value;
				    aTipoRetorno.push(oTipoRetorno);
		    
	        if ( eTipoRetorno.value == '1' || eTipoRetorno.value == '2' ) {
	          var aElemEnderecoRetorno = $$('#'+sIdTipo+' input.enderRetorno');
	          aElemEnderecoRetorno.each(
	            function ( eEnderecoRetorno, iIndEndRet ) {
	              if ( eEnderecoRetorno.checked ) {
	                aRetornoEndereco.push((eEnderecoRetorno.value).evalJSON());
	              }
	            }            
	          );
	        } else if ( eTipoRetorno.value == '3' ) {
	          var aElemEmailRetorno    = $$('#'+sIdTipo+' input.emailRetorno');
	          aElemEmailRetorno.each(
	            function ( eEmailRetorno, iIndEmailRet ) {
	              if ( eEmailRetorno.checked ) {
	                aRetornoEmail.push(eEmailRetorno.value.evalJSON());
	              }
	            }            
	          );            
	        } else if ( eTipoRetorno.value == '4' ) {
	          var aElemTelefoneRetorno = $$('#'+sIdTipo+' input.telefoneRetorno');
            aElemTelefoneRetorno.each(
              function ( eTelefoneRetorno, iIndTelefRet ) {
                if ( eTelefoneRetorno.checked ) {
                  aRetornoTelefone.push(eTelefoneRetorno.value.evalJSON());
                }
              }            
            );               
          }
        }
      }
    );
	    
    var oRetorno = new Object();
    oRetorno.aTipoRetorno     = aTipoRetorno;  
    oRetorno.aRetornoEndereco = aRetornoEndereco;
    oRetorno.aRetornoEmail    = aRetornoEmail;
    oRetorno.aRetornoTelefone = aRetornoTelefone;
    
    return oRetorno;
  
  }

  
  function js_getDadosAtendimento(){
                                           
    var aElemFormaReclamacao   = $$('input.formareclamacao');
    var iFormaReclamacao       = '';
    var iTipoIdentificacao     = js_getTipoIdentificacao();
    
    aElemFormaReclamacao.each(
      function ( eFormaReclamacao, iInd ) {
         if ( eFormaReclamacao.checked ) {
            iFormaReclamacao = eFormaReclamacao.value;
         }
      }
    );
    

    var oTitular = new Object();
    oTitular.iCodigo = $F('titular');        
    oTitular.sNome   = $F('nometitular');
    oTitular.sTipo   = $F('tipotitular');
    oTitular.iSeq    = $F('seqtitular');    
    
    var oAtendimento = new Object();
    
	  oAtendimento.ov01_formareclamacao   = iFormaReclamacao; 
	  oAtendimento.ov01_tipoidentificacao = iTipoIdentificacao;
	  oAtendimento.ov01_tipoprocesso      = $F('ov01_tipoprocesso');
	  oAtendimento.ov01_usuario           = $F('ov01_usuario');
	  oAtendimento.ov01_depart            = $F('ov01_depart');
	  oAtendimento.ov01_numero            = $F('ov01_numero');
	  oAtendimento.ov01_dataatend         = $F('ov01_dataatend'); 
	  oAtendimento.ov01_horaatend         = $F('ov01_horaatend');
	  oAtendimento.ov01_requerente        = $F('ov01_requerente');
	  oAtendimento.ov01_solicitacao       = encodeURIComponent(tagString($F('ov01_solicitacao')));
	  oAtendimento.ov01_executado         = encodeURIComponent(tagString($F('ov01_executado')));
    oAtendimento.ov01_sequencial        = $F('ov01_sequencial'); 
    oAtendimento.oTitularAtendimento    = oTitular;
    oAtendimento.ov24_ouvidoriacadlocal = $F('ov24_ouvidoriacadlocal');

	  return oAtendimento;             
  }  

  function js_getDocumentos(){
      
    var aElemDocumento   = $$('input.procdoc');    
    var aDocumento       = new Array();    
    aElemDocumento.each(
      function ( eDocumento, iInd ) {
        var oDocumento = new Object();
        oDocumento.ov19_procdoc  = eDocumento.value;
        if ( eDocumento.checked ){
          oDocumento.ov19_entregue = 'true';
        } else {
          oDocumento.ov19_entregue = 'false';
        }
        aDocumento.push(oDocumento);
      }    
    );
    
    return aDocumento;
    
  }

	function js_esconderRetorno( sId ){
	
	  var oNode = $(sId);
	  
	  if (oNode.style.display == '') {
	    oNode.style.display = 'none';
	    $(sId+'togglefiltros').src='imagens/seta.gif';
	  } else if (oNode.style.display == 'none') {
	    oNode.style.display = '';
	    $(sId+'togglefiltros').src='imagens/setabaixo.gif';
	  }
	}

  function js_desabilitaCamposRetorno(lDesabilita){
     
    var aElemInput    = $$('#listaRetorno input[type="text"]','#listaRetorno textarea');
  
    aElemInput.each(
      function ( eElemInput, iInd ) {
        if ( lDesabilita ) {
	        eElemInput.readOnly = true;
	        eElemInput.style.backgroundColor = "#deb887";
        } else {
          eElemInput.readOnly = false;
          eElemInput.style.backgroundColor = "#FFFFFF";        
        }
      }   
    );
     
  }

  function js_validaCamposTela(){
    
    var lRetorno           = true;
    var iTipoIdentificacao = js_getTipoIdentificacao();
    
    if ( $F('ov01_tipoprocesso') == '' ) {
      alert('Tipo de Processo não informado!');
      return false;      
    }

    if ( $F('ov01_solicitacao') == '' ) {
      alert('Solicitação não informada!');
      return false;      
    }
    
    if ( $('db_opcao').value == 1 ){
		  var aElemFormaReclamacao = $$('input.formareclamacao');
		  var lFormaReclamacao     = false;
		  aElemFormaReclamacao.each(
		    function ( eFormaReclamacao, iInd ) {
		      if ( eFormaReclamacao.checked ) {
		        lFormaReclamacao = true;
		      }
		    }
		  );
		    
		  if ( !lFormaReclamacao ) {
		    alert('Forma de reclamação não informada!');
	      return false;      
	    }
	  }  
	  
    if ( iTipoIdentificacao == 2  ) {
      
      var sIdRetorno = 'retornoIdentificado';
      
      if ( $F('titular') == '') {
         alert('Titular não informado!');
         return false;
      }
                    
    } else {
      var sIdRetorno = 'retornoAnonimo';
    }
    
    if ( $F('ov01_requerente') == '') {
      alert('Requerente não informado!');
      return false;
    }

    if ($F('ov24_ouvidoriacadlocal') == "") {

      alert("Informe o local do requerente.");
      return false;
    }
    
    var aElemTipoRetorno = $$('#'+sIdRetorno+' input.tipoRetornoClass');
    
    aElemTipoRetorno.each(
      function ( eTipoRequerente, iInd ) {
        if ( ( eTipoRequerente.value == '1' || eTipoRequerente.value == '2' ) && eTipoRequerente.checked && lRetorno ) {
          if ( iTipoIdentificacao == 2 ) {
	          var aChkEnderRetorno = $$('#'+sIdRetorno+' input.enderRetorno');
	          var lEnderRetorno    = false;
	          aChkEnderRetorno.each(
	            function ( eChkEnder, iIndChkEnderRet ) {
	              if ( eChkEnder.checked ) {
	                lEnderRetorno = true;
	              }
	            }          
	          );
				    if ( !lEnderRetorno ) {
				      alert('Nenhum Endereço de Retorno informado!');
	  	        lRetorno = false;
				    }
			    } else {
            if ( $F('ov12_endereco') == '' || $F('ov12_numero') == '' || 
                 $F('ov12_bairro')   == '' || $F('ov12_munic')  == '' || $('ov12_uf').value.trim() == '') {
              alert('Dados do endereço de retorno não informado!');
              lRetorno = false;                 			    
			      }
			    }
        } else if ( eTipoRequerente.value == '3' && eTipoRequerente.checked && lRetorno ) {
          var aChkEmailRetorno = $$('#'+sIdRetorno+' input.emailRetorno');
          var lEmailRetorno    = false;
          aChkEmailRetorno.each(
            function ( eChkEmail, iIndChkEmailRet ) {
              if ( eChkEmail.checked ) {
                lEmailRetorno = true;
              }
            }          
          );
			    if ( !lEmailRetorno ) {
			      alert('Nenhum Email de Retorno informado!');
			      lRetorno = false;
			    }        
        } else if ( eTipoRequerente.value == '4' && eTipoRequerente.checked && lRetorno ) {
          var aChkTelefoneRetorno = $$('#'+sIdRetorno+' input.telefoneRetorno');
          var lTelefoneRetorno    = false;
          aChkTelefoneRetorno.each(
            function ( eChkTelefone, iIndChkTelefoneRet ) {
              if ( eChkTelefone.checked ) {
                lTelefoneRetorno = true;
              }
            }          
          );
			    if ( !lTelefoneRetorno ) {
			      alert('Nenhum Telefone de Retorno informado!');
			      lRetorno = false;
			    }        
        }
      }
    );
    
    if ( !lRetorno ) {
      return false;
    } else {
      return true;
    }    
    
  }
  
  
  function js_pesquisaov12_endereco(){
    js_OpenJanelaIframe('','db_iframe','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome','Pesquisa',true);
  }

	function js_mostraruas1(chave1,chave2){
	  $('ov12_endereco').value = chave2;
	  db_iframe.hide();
	}
	
	function js_pesquisaov12_bairro(){
    js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr','Pesquisa',true);
	}
	
	function js_mostrabairro1(chave1,chave2){
	  $('ov12_bairro').value = chave2;
	  db_iframe_bairro.hide();
	}

	function js_frmListaEmails(){
    oDBGridListaEmails = new DBGrid('emails');
	  oDBGridListaEmails.nameInstance = 'oDBGridListaEmails';
	  oDBGridListaEmails.setHeader(new Array('Descrição','Ações','Objeto'));
	  oDBGridListaEmails.setHeight(70);
	  oDBGridListaEmails.setCellAlign(new Array('left','right','center'));
	  oDBGridListaEmails.setCellWidth(new Array(303,79,1));
	  oDBGridListaEmails.aHeaders[2].lDisplayed = false;
	  oDBGridListaEmails.show($('listaemails'));
    js_RenderGridEmails();
	}

  function js_RenderGridEmails(){
    
    oDBGridListaEmails.clearAll(true);
    
    var iNumRows = aLinhasEmails.length;
    
    if(iNumRows > 0){
      aLinhasEmails.each(
        function (oEmail,iInd){
          var aRow    = new Array();
              aRow[0] = oEmail.ov13_email;
              aRow[1] = oEmail.sAcoes;
              aRow[2] = "<input type='checkbox' value='"+Object.toJSON(oEmail)+"' class='emailRetorno' checked >";
              oDBGridListaEmails.addRow(aRow);
        }
      );
    }
    
    oDBGridListaEmails.renderRows();
    
  }


  function js_incluirEmailTela(){
  
    if( $F('ov13_email') == ""){
      alert('Email não Informado!\n\nInclusão Abortada\n\n');
      $('ov13_email').focus();
      return false;
    } 
       
    var oEmail = new Object();
        oEmail.ov13_email = $F('ov13_email');
        
    js_incluirEmail(new Array(oEmail));    
  
  }

  function js_incluirEmail(aEmail){
    
   
    var iNumRows = aLinhasEmails.length;
    
    aEmail.each(
      function ( oEmail, iInd ) {
  	    if(iNumRows != 0){
		      iNumRows -=1 ;
		    }
			   
		    var sAcoes  = '<input type="button" value="Alterar" onclick="js_alterarEmail('+iNumRows+')">';
		        sAcoes += '<input type="button" value="Excluir" onclick="js_excluirEmail('+iNumRows+')">';
		   
		    oEmail.sAcoes  = sAcoes;
		    aLinhasEmails.push(oEmail);
      }
    );   

   
    js_RenderGridEmails(); 
   
    var aBotoes = $$('#listaemails input[type="button"]');
    aBotoes.each(
      function ( eBotao ) {
        eBotao.disabled = false; 
      }
    );   

    $('ov13_email').value = '';
    $('ov13_email').focus();
  
    $('incluiEmail').value       = 'Incluir';
    $('novoEmail').style.display = 'none'; 
         
  }
  
  function js_alterarEmail( iPosicao ){
  
    var oEmailAltera = new Object();
  	    oEmailAltera.ov13_email  = aLinhasEmails[iPosicao].ov13_email;
  	    oEmailAltera.sAcoes      = aLinhasEmails[iPosicao].sAcoes;
    $('ov13_email').value        = aLinhasEmails[iPosicao].ov13_email;
    $('alteraEmail').value       = Object.toJSON(oEmailAltera);
    $('incluiEmail').value       = 'Alterar';
    $('novoEmail').style.display = '';
    $('ov13_email').focus();
    
    js_readGridEmails(2,iPosicao);	       
    js_RenderGridEmails(); 
    
  }  
  
  
  function js_excluirEmail( iPosicao ){
  
    js_readGridEmails(1,iPosicao);
    js_RenderGridEmails();
    
  }  
  
  
  //1 - Exclui a linha selecionada
  //2 - Altera a linha selecionada
	function js_readGridEmails(sAcao,iLinhaExcluir){
	  
	  if( oDBGridListaEmails.getNumRows() > 0 ){
	  
	    aTempEmails  = new Array();
	    
	    var iNumRows = oDBGridListaEmails.aRows.length;
	    var iIndice  = 0;
	        
      for (var iInd=0; iInd < iNumRows; iInd++) {
	        
	      if ( iLinhaExcluir != iInd ) {
	        
          var oEmail = new Array();
              oEmail.ov13_email = oDBGridListaEmails.aRows[iInd].aCells[0].getValue();
	          
	        if( sAcao == 1 ) {
	          
            var sAcoes  = '<input type="button" value="Alterar"  onclick="js_alterarEmail('+iIndice+')">';
	              sAcoes += '<input type="button" value="Excluir"  onclick="js_excluirEmail('+iIndice+')">';
	          
	        } else {
	          
            var sAcoes  = '<input type="button" value="Alterar" disabled onclick="js_alterarEmail('+iIndice+')">';
                sAcoes += '<input type="button" value="Excluir" disabled onclick="js_excluirEmail('+iIndice+')">';
	         
          }
          
          oEmail.sAcoes = sAcoes;
  
          iIndice++;
          aTempEmails.push(oEmail);
           
        }
      }
	    aLinhasEmails = aTempEmails;
	  }
	}
	  
  function js_NovoEmail(){

	  var oEmail   = eval('('+$('alteraEmail').value+')');
	  var iNumRows = aLinhasEmails.length;
	  
	  if(iNumRows !=0){
	    iNumRows -=1;
	  }
	  
	  var sAcoes  = '<input type="button" value="Alterar" onclick="js_alterarEmail('+iNumRows+')">';
	      sAcoes += '<input type="button" value="Excluir" onclick="js_excluirEmail('+iNumRows+')">';
	   
	  oEmail.sAcoes = sAcoes;
	     
	  aLinhasEmails.push(oEmail);
	  
	  js_RenderGridEmails(); 
	  
	  var aBotoes = $$('#listaemails input[type="button"]');
	  aBotoes.each(
	    function ( eBotao ) {
	      eBotao.disabled = false; 
	    }
	  );
	  
	  $('ov13_email').value = '';
	  $('ov13_email').focus();
	  
	  $('incluiEmail').value       = 'Incluir';
	  $('novoEmail').style.display = 'none';  
	  
	}
  

  function js_frmListaTelefones(){
  
    oDBGridListaTelefones = new DBGrid('telefones');
    oDBGridListaTelefones.nameInstance = 'oDBGridListaTelefones';
    oDBGridListaTelefones.setHeader(new Array('Descrição','DDD','Número','Ramal','Ações','obs','tipotelefone','check'));
    oDBGridListaTelefones.setHeight(70);
    oDBGridListaTelefones.aHeaders[5].lDisplayed = false;
    oDBGridListaTelefones.aHeaders[6].lDisplayed = false;
    oDBGridListaTelefones.aHeaders[7].lDisplayed = false;
    oDBGridListaTelefones.setCellWidth(new Array(120,100,100,100,111));
    oDBGridListaTelefones.setCellAlign(new Array('left','right','right','center','right','right','right','center'));
    oDBGridListaTelefones.show($('listatelefones'));
    oDBGridListaTelefones.clearAll(true);
    oDBGridListaTelefones.renderRows();
    
  }


  function js_incluirTelefoneTela(){
  
    if($F('ov14_numero') == ""){
	    alert('Usuário:\n\nNúmero de telefone não Informado!\n\n');
	    $('ov14_numero').focus();
	    return false;
	  }
	  
	  var oTelefone = js_getObjTelefoneTela();
	  js_incluirTelefone(new Array(oTelefone) ); 
  
  }

  function js_incluirTelefone(aTelefone){
  
    var iNumLinhas = oDBGridListaTelefones.getNumRows();

    aTelefone.each(
      function ( oTelefone, iInd ) {
		    var sAcoes = ' <input type="button" value="Alterar" onclick="js_alterarTelefone('+iNumLinhas+')">';
		        sAcoes += '<input type="button" value="Excluir" onclick="js_excluirTelefone('+iNumLinhas+')">';
		        
		    oTelefone.sAcoes = sAcoes;
		    aLinhasTelefones.push(oTelefone);
		    ++iNumLinhas;
	    }
    );

    js_RenderGridTelefones();
	  
    var aBotoes = $$('#listatelefones input[type="button"]');
    aBotoes.each(
      function ( eBotao ) {
        eBotao.disabled = false; 
      }
    ); 	  
	  
	  $('ov14_numero').value   = '';
	  $('ov14_ddd').value      = '';
	  $('ov14_ramal').value    = '';
	  $('ov14_obs').value      = '';
	  $('ov14_tipotelefone').value = 1;
	  $('ov14_numero').focus();
	  
    $('incluiTelefone').value       = 'Incluir';
    $('novoTelefone').style.display = 'none';
	          
	}
	
	function js_excluirTelefone( iPosicao ){
	  
    js_readGridTelefones(1,iPosicao);
	  js_RenderGridTelefones();
	  
	}
	 
  function js_novoTelefone(){

    var oTelefone = eval('('+$('alteraTelefone').value+')');
    var iNumRows  = aLinhasTelefone.length;
    
    if(iNumRows !=0){
      iNumRows -=1;
    }
    
    var sAcoes  = '<input type="button" value="Alterar" onclick="js_alterarEmail('+iNumRows+')">';
        sAcoes += '<input type="button" value="Excluir" onclick="js_excluirEmail('+iNumRows+')">';
     
    oTelefone.sAcoes = sAcoes;
       
    aLinhasTelefone.push(oTelefone);
    
    js_RenderGridTelefones(); 
    
    var aBotoes = $$('#listatelefones input[type="button"]');
    aBotoes.each(
      function ( eBotao ) {
        eBotao.disabled = false; 
      }
    );
    
    
    $('ov14_numero').value   = '';
    $('ov14_ddd').value      = '';
    $('ov14_ramal').value    = '';
    $('ov14_obs').value      = '';
    $('ov14_tipotelefone').value = 1;
    $('ov14_numero').focus();
    
    $('incluiTelefone').value       = 'Incluir';
    $('novoTelefone').style.display = 'none';
    
  }	 
	 
	function js_alterarTelefone( iPosicao ){
	
    var oTelefoneAltera = new Object();
        oTelefoneAltera.descricao         = aLinhasTelefones[iPosicao].descricao;
        oTelefoneAltera.ov14_ddd          = aLinhasTelefones[iPosicao].ov14_ddd;
        oTelefoneAltera.ov14_numero       = aLinhasTelefones[iPosicao].ov14_numero;
        oTelefoneAltera.ov14_ramal        = aLinhasTelefones[iPosicao].ov14_ramal;
        oTelefoneAltera.ov14_obs          = aLinhasTelefones[iPosicao].ov14_obs;
        oTelefoneAltera.ov14_tipotelefone = aLinhasTelefones[iPosicao].ov14_tipotelefone;
        
    for ( var iInd=0; iInd < $('ov14_tipotelefone').options.length; iInd++ ) {
      if ( $('ov14_tipotelefone').options[iInd].value == oTelefoneAltera.ov14_tipotelefone ) {
        $('ov14_tipotelefone').options[iInd].selected = true;
      }
    } 
    
    $('ov14_ddd').value          = oTelefoneAltera.ov14_ddd;
    $('ov14_numero').value       = oTelefoneAltera.ov14_numero;
    $('ov14_ramal').value        = oTelefoneAltera.ov14_ramal;
    $('ov14_obs').value          = oTelefoneAltera.ov14_obs;
    
    
    $('alteraTelefone').value       = Object.toJSON(oTelefoneAltera);
    $('incluiTelefone').value       = 'Alterar';
    $('novoTelefone').style.display = '';
    
    js_readGridTelefones(2,iPosicao);
             
    js_RenderGridTelefones();	
	  
	}
	
	function js_getObjTelefoneTela(){
	
    var oTelefone = new Object();
      
    oTelefone.ov14_numero        = $F('ov14_numero'); 
    oTelefone.ov14_ddd           = $F('ov14_ddd');
    oTelefone.ov14_ramal         = $F('ov14_ramal');
    oTelefone.ov14_obs           = $F('ov14_obs');
    oTelefone.ov14_tipotelefone  = $F('ov14_tipotelefone');
    oTelefone.descricao          = $('ov14_tipotelefone').options[$('ov14_tipotelefone').selectedIndex].innerHTML;	
	  
	  return oTelefone;
	
	}
	
	
	
	function js_RenderGridTelefones(){
	
	  oDBGridListaTelefones.clearAll(true);
	  var iNumRows = aLinhasTelefones.length;
	  if(iNumRows > 0){
	    aLinhasTelefones.each(
	      function (oTelefone,iInd){
	        aRow = new Array();
	        aRow[0] = oTelefone.descricao;
	        aRow[1] = oTelefone.ov14_ddd;
	        aRow[2] = oTelefone.ov14_numero;    
	        aRow[3] = oTelefone.ov14_ramal;   
	        aRow[4] = oTelefone.sAcoes;
	        aRow[5] = oTelefone.ov14_obs;     
	        aRow[6] = oTelefone.ov14_tipotelefone;
	        aRow[7] = "<input type='checkbox' value='"+Object.toJSON(oTelefone)+"' class='telefoneRetorno' checked >";
	        oDBGridListaTelefones.addRow(aRow);
	      }
	    );
	  }
	    
	  oDBGridListaTelefones.renderRows();
	
	}  
  
  //1 - Exclui a linha selecionada
  //2 - Altera a linha selecionada
  function js_readGridTelefones(sAcao,iLinhaExcluir){
     
    if( oDBGridListaTelefones.getNumRows() > 0 ){
    
      aTempTelefones  = new Array();
      
      var iNumRows = oDBGridListaTelefones.aRows.length;
      var iIndice  = 0;
          
      for (var iInd=0; iInd < iNumRows; iInd++) {
          
        if ( iLinhaExcluir != iInd ) {
              
          var oTelefone = new Object();
              oTelefone.descricao         = oDBGridListaTelefones.aRows[iInd].aCells[0].getValue();
              oTelefone.ov14_ddd          = oDBGridListaTelefones.aRows[iInd].aCells[1].getValue();
              oTelefone.ov14_numero       = oDBGridListaTelefones.aRows[iInd].aCells[2].getValue();
              oTelefone.ov14_ramal        = oDBGridListaTelefones.aRows[iInd].aCells[3].getValue();
              oTelefone.ov14_obs          = oDBGridListaTelefones.aRows[iInd].aCells[5].getValue();
              oTelefone.ov14_tipotelefone = oDBGridListaTelefones.aRows[iInd].aCells[6].getValue();
                         
          if( sAcao == 1 ) {
            var sAcoes  = '<input type="button" value="Alterar"  onclick="js_alterarTelefone('+iIndice+')">';
                sAcoes += '<input type="button" value="Excluir"  onclick="js_excluirTelefone('+iIndice+')">';
          } else {
            var sAcoes  = '<input type="button" value="Alterar" disabled onclick="js_alterarTelefone('+iIndice+')">';
                sAcoes += '<input type="button" value="Excluir" disabled onclick="js_excluirTelefone('+iIndice+')">';
          }
          
          oTelefone.sAcoes = sAcoes;
  
          iIndice++;
          aTempTelefones.push(oTelefone);
           
        }
      }
      aLinhasTelefones = aTempTelefones;
    }
  }  
  
  
  js_frmListaTelefones();
  js_frmListaEmails(); 

  function js_desabilitaTodosCampos(){
    
    aCamposText   = $$('#dadosAtendimento input[type="text"]');
    aCamposRadio  = $$('#dadosAtendimento input[type="radio"]');
    
    aCamposText.each(
      function ( eCampo, iInd ) {
        eCampo.readOnly = true;
        eCampo.style.backgroundColor = "#deb887";
      }
    );    
    
    aCamposRadio.each(
      function ( eCampo, iInd ){
        eCampo.disabled = true;  
      }
    );
      
  }
  
  
  function js_verificaRotina(iTipo){
     
    var aTelaInclusao   = $$('.telaInclusao'); 
    var aTelaAlteracao  = $$('.telaAlteracao');
    $('db_opcao').value = iTipo;
    
    js_consultaDadosTela();
    
    if ( iTipo == 2 ) {
    
	    aTelaInclusao.each(
	      function ( eTela, iInd ) {
	        eTela.style.display = 'none';     
	      }      
	    );
	     
	    aTelaAlteracao.each(
	      function ( eTela, iInd ) {
          eTela.style.display = ''; 
	      }
	    );         
      
      $('alterar').disabled = true;
      js_desabilitaTodosCampos();
      
    } else {
      
      aTelaInclusao.each(
        function ( eTela, iInd ) {
          
          if (eTela.id != 'usaEnderecoCgmasLocal') {
            eTela.style.display = '';     
          }
        }      
      );
       
      aTelaAlteracao.each(
        function ( eTela, iInd ) {
          eTela.style.display = 'none'; 
        }
      );      
      
    }
     
  }  
  
  function js_pesquisaAtendimento() {
    js_OpenJanelaIframe('','db_iframe','func_ouvidoriaatendimentoprocesso.php?tramite=true&deptoatual=true&situacao=1&tramiteinicial=false&funcao_js=parent.js_mostraAtendimento|ov01_sequencial','Pesquisa Atendimento',true);
  }
  

  function js_mostraAtendimento( iCodAtendimento ){
    js_consultaDadosAtendimento(iCodAtendimento);
    $('alterar').disabled = false;
    db_iframe.hide();
  }


  function js_consultaDadosAtendimento(iCodAtendimento){
  
    js_divCarregando('Aguarde...','msgBox');
   
    var sQuery  = 'sMethod=consultaAtendimento';
        sQuery += '&iCodAtendimento='+iCodAtendimento;
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoConsultaDadosAtendimento
                                          }
                                  );          
    
  }
  
  
  function js_retornoConsultaDadosAtendimento(oAjax){
  
    js_removeObj("msgBox");
   
    var aRetorno = eval("("+oAjax.responseText+")");
    var sExpReg  = new RegExp('\\\\n','g');
  
    if ( aRetorno.lErro ) {
	    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
      return false;
    } else {
      js_carregaDadosAlteracao( aRetorno.oAtendimento,
                                aRetorno.aListaDoc,
                                aRetorno.aListaTipoRetorno,
                                aRetorno.aListaEnder,
                                aRetorno.aListaEmail,
                                aRetorno.aListaTelefone );
    }
  
  }  
  
  
  function js_carregaDadosAlteracao( oAtendimento,aListaDocumentos,aTipoRetorno,aListaEnder,aListaEmail,aListaTelefone ){

    $('ov01_sequencial').value        = oAtendimento.ov01_sequencial;
    $('ov01_usuario').value           = oAtendimento.ov01_usuario; 
    $('nome').value                   = oAtendimento.nome.urlDecode();
    $('ov01_depart').value            = oAtendimento.ov01_depart;
    $('descrdepto').value             = oAtendimento.descrdepto.urlDecode();
    $('ov01_numero').value            = oAtendimento.ov01_numero;
    $('ov24_ouvidoriacadlocal').value = oAtendimento.ov24_ouvidoriacadlocal;
    $('ov25_descricao').value         = oAtendimento.ov25_descricao.urlDecode();
    $('ov01_dataatend').value         = js_formatar(oAtendimento.ov01_dataatend,'d');
    $('ov01_horaatend').value         = oAtendimento.ov01_horaatend.urlDecode();
    $('ov01_tipoprocesso').value      = oAtendimento.ov01_tipoprocesso;
    $('p51_descr').value              = oAtendimento.p51_descr.urlDecode();
    $('ov01_formareclamacao').value   = oAtendimento.ov01_formareclamacao;
    $('p42_descricao').value          = oAtendimento.p42_descricao.urlDecode();
    $('ov01_tipoidentificacao').value = oAtendimento.ov01_tipoidentificacao;
    $('ov05_descricao').value         = oAtendimento.ov05_descricao.urlDecode();
    $('ov01_requerente').value        = oAtendimento.ov01_requerente.urlDecode();
    $('ov01_solicitacao').value       = oAtendimento.ov01_solicitacao.urlDecode();
    $('ov01_executado').value         = oAtendimento.ov01_executado.urlDecode();
    
    var iCodTitular  = '';
    var sNomeTitular = '';
    var iSeq         = '';
    
    if ( oAtendimento.ov11_cgm.trim() != ''  ) {

      sTipoIdent   = 'CGM'; 
      iCodTitular  = oAtendimento.ov11_cgm;
      sNomeTitular = oAtendimento.z01_nome.urlDecode();
      
    } else {
      iCodTitular  = oAtendimento.ov10_cidadao;
      iSeq         = oAtendimento.ov10_seq;
      sNomeTitular = oAtendimento.ov02_nome.urlDecode();
      sTipoIdent   = 'Cidadão';
    }

    $('titular').value      = iCodTitular;
    $('nometitular').value  = sNomeTitular.urlDecode();
    $('seqtitular').value   = iSeq;
    $('tipotitular').value  = sTipoIdent;

    js_montaListaDocumentos(aListaDocumentos);

    var aChkDoc = $$('#listaDocTipoProcesso input[type="checkbox"]');
    aListaDocumentos.each(
      function ( oDocumento, iIndDoc ) {
		    aChkDoc.each(
		      function ( eChkDoc, iIndChk ) {
		        if ( eChkDoc.value == oDocumento.ov19_procdoc && oDocumento.ov19_entregue == 't') {
  		        eChkDoc.checked = true;
		        }
		      }
		    );
      }
    );
    var aFormasDeReclamacao = document.getElementsByName("radioformareclamacao");
    for(var iRowReclamacao = 0; iRowReclamacao < aFormasDeReclamacao.length; iRowReclamacao++) {

      if (aFormasDeReclamacao[iRowReclamacao].value == oAtendimento.ov01_formareclamacao) {
        
        aFormasDeReclamacao[iRowReclamacao].checked = true;
        break;
      }
    }
    
    var aElemTipoRequerente = $$('input.tiporequerente');
    aElemTipoRequerente.each(
      function ( eTipoRequerente, iInd ) {
        if ( eTipoRequerente.value == oAtendimento.ov01_tipoidentificacao ) {
          eTipoRequerente.checked = true;
        }
      }
    );
    
    js_telaRequerente();
    if ( oAtendimento.ov01_tipoidentificacao == 1 ) {
    
      var aElemTipoRetorno = $$('#retornoAnonimo input.tipoRetornoClass');
      aElemTipoRetorno.each(
        function ( eTipoRetorno, iIndTipo ) {
        
		      if ( aListaEnder.length > 0 && ( eTipoRetorno.value == '1' || eTipoRetorno.value == '2' ) ) {
            eTipoRetorno.checked = true;		
		        $('ov12_endereco').value = aListaEnder[0].ov12_endereco;
		        $('ov12_numero').value   = aListaEnder[0].ov12_numero;
		        $('ov12_compl').value    = aListaEnder[0].ov12_compl;
		        $('ov12_bairro').value   = aListaEnder[0].ov12_bairro;
		        $('ov12_munic').value    = aListaEnder[0].ov12_munic;
		        $('ov12_uf').value       = aListaEnder[0].ov12_uf;
		        $('ov12_cep').value      = aListaEnder[0].ov12_cep;
		      }      
		      
		      if ( aListaEmail.length > 0 &&  eTipoRetorno.value == '3' ) {
		        eTipoRetorno.checked = true;
		        js_incluirEmail(aListaEmail);
		        js_RenderGridEmails();
		      }
		         
		      if ( aListaTelefone.length > 0 && eTipoRetorno.value == '4') {
		        eTipoRetorno.checked = true;       
		        js_incluirTelefone(aListaTelefone);
		        js_RenderGridTelefones();
		      }
		      
        }
      );
      
    } else {
      aTipoRetornoAlteracao = aTipoRetorno;    
      js_consultaDadosRequerente(iCodTitular,sTipoIdent,js_retornoConsultaDadosRequerenteAltera);
    }

    $('spanBtnBotoesPadrao').style.display = 'none';
    $('spanBtnBotaoAlteracao').style.display = '';

    if (oAtendimento.hasTramiteInicial) {

      var sMsgTramiteInicial  = "Existe tramite inicial para o processo "+oAtendimento.ov09_protprocesso+", ";
      sMsgTramiteInicial     += "por este motivo este atendimento não pode ser alterado.";
      alert(sMsgTramiteInicial);
      $('spanBtnPesquisarAtendimento').style.display = '';
    }
  }

   
  function js_retornoConsultaDadosRequerenteAltera(oAjax){

    js_removeObj("msgBox");
    var aRetorno = eval("("+oAjax.responseText+")");
    
    $('tiposderetorno').value = Object.toJSON(aRetorno.aListaTipoRetorno);

    js_montaTipoRetorno(aRetorno.aListaEnder,
                        aRetorno.aListaEmail,
                        aRetorno.aListaTelefone);

    var aElemTipoRetorno = $$('#retornoIdentificado input.tipoRetornoClass');
    
    var aDisableTipo  = new Array;
    
    aRetorno.aListaTipoRetorno.each(
      function ( oListaTiposRetorno, iIndListaTipos ) {
        lIncluir = true;
        aTipoRetornoAlteracao.each(
          function ( oTipoAlteracao, iIndAlt ) {
            if ( oTipoAlteracao.ov17_tiporetorno == oListaTiposRetorno.ov04_tiporetorno ) {
              lIncluir = false; 
            }
          }
        ); 
        if ( lIncluir ) {
          aDisableTipo.push(oListaTiposRetorno.ov04_tiporetorno);
        }
      }
    );
    
    aElemTipoRetorno.each(
	    function ( elemTipoRetorno, iIndTipo ) {
	      aDisableTipo.each(
	        function ( iCod, iInd ) {
	          if ( elemTipoRetorno.value == iCod ) {
				      elemTipoRetorno.disabled = false;
				      elemTipoRetorno.checked  = false;
				      js_validaTipoRetorno(elemTipoRetorno);
	          }
	        }
	      );
	    }
	  );  
	    
  }  
  
  function js_alteraAtendimento(){

    js_desabilitaBotoes(true);
    var iTipoIdentificacao = js_getTipoIdentificacao();
        
    if ( iTipoIdentificacao == 1 ) {
      
      var oEnder = new Object();
          oEnder.ov12_endereco = $F('ov12_endereco');
          oEnder.ov12_numero   = $F('ov12_numero');
          oEnder.ov12_compl    = $F('ov12_compl');
          oEnder.ov12_bairro   = $F('ov12_bairro');
          oEnder.ov12_munic    = $F('ov12_munic');
          oEnder.ov12_uf       = $F('ov12_uf');
          oEnder.ov12_cep      = $F('ov12_cep');
          
      $('enderRetornoAnonimo').value = Object.toJSON(oEnder);
            
    }
    
    if ( !js_validaCamposTela() ){
      js_desabilitaBotoes(false);
      return false;
    }
    
    var oAtendimento = js_getDadosAtendimento();
    var aDocumentos  = js_getDocumentos();
    var oRetorno     = js_getDadosRetorno();
    
    js_divCarregando('Aguarde...','msgBox');
   
    var sQuery  = 'sMethod=alterarAtendimento';
        sQuery += '&iCodAtendimento='+$F('ov01_sequencial');
        sQuery += '&oAtendimento='+Object.toJSON(oAtendimento);
        sQuery += '&aDocumento='+Object.toJSON(aDocumentos);
        sQuery += '&oRetorno='+Object.toJSON(oRetorno);
        sQuery += '&iCodProc='+$F('ov09_protprocesso');
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoAlteraAtendimento
                                          }
                                  );          
    
    
  }
  
  
  function js_retornoAlteraAtendimento(oAjax){
  
    js_removeObj("msgBox");
   
    var aRetorno = eval("("+oAjax.responseText+")");
    var sExpReg  = new RegExp('\\\\n','g');
  
    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
    
    if ( aRetorno.lErro ) {
      js_desabilitaBotoes(false);
      return false;
    } else {
      js_novoAtendimento(2);
    }
  
  }  
  function js_liberaCadastroLocal() {
   
    if ($('usarenderecocgm').checked) {
      $('linhaLocal').style.display = 'none';  
    } else {
      $('linhaLocal').style.display = '';
    }
  }
  function js_desabilitaBotoes(lDesabilita){
    var aBotoes = $$('input.botao');
        aBotoes.each(
	        function ( eBotao ) {
	          eBotao.disabled = lDesabilita;
	        }
	      );  
  }
  <?
    if ( $db_opcao == 2 || (isset($lAlteracao) && $lAlteracao)) {
    	echo "js_pesquisaAtendimento();";
    }
  ?>
</script>