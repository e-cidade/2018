<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

$clpontofx->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("rh27_limdat");
$clrotulo->label("rh27_descr");
$clrotulo->label("r29_tpp");
$clrotulo->label("r70_descr");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");

if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
  
  try {
    
    switch ($ponto) {
      
      case "fa":
        FolhaPagamentoAdiantamento::verificaLiberacaoDBPref();
        break;
      
      case "fr":
        FolhaPagamentoRescisao::verificaLiberacaoDBPref();
        break;
      
      case "f13":
        FolhaPagamento13o::verificaLiberacaoDBPref();
        break;
    }
  } catch (BusinessException $e) {

    $db_opcao     = 3;
    db_msgbox($e->getMessage());
  }
}

if($ponto == "fx"){
  $dponto = " Ponto fixo";
}else if($ponto == "fs"){
  $dponto = " Ponto de salário";
}else if($ponto == "fa"){
  $dponto = " Ponto de adiantamento";
}else if($ponto == "com"){
  $dponto = " Ponto Complementar por Código";
  try{
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
      
      if ( FolhaPagamentoComplementar::hasFolhaAberta( new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha()) ) ) {
        
        $oFolhaPagamentoComplementar = FolhaPagamentoComplementar::getFolhaAberta();
        $rh141_codigo                = $oFolhaPagamentoComplementar->getNumero();
        $rh141_descricao             = $oFolhaPagamentoComplementar->getDescricao();
        $rh141_anoref                = $oFolhaPagamentoComplementar->getCompetenciaReferencia()->getAno();
        $rh141_mesref                = $oFolhaPagamentoComplementar->getCompetenciaReferencia()->getMes();
      } else {

        db_msgbox( _M(FolhaPagamentoComplementar::MENSAGENS . "fechamento_folha_fechada") );
        $db_opcao = 3;
      }

    }
  } catch (Exception $ex) {
    db_msgbox($ex->getMessage());
    $db_opcao = 3;
  }
}else if($ponto == "f13"){
  $dponto = " Ponto de décimo terceiro";
}else if($ponto == "fe"){
  $dponto = " Ponto de férias";
}else if($ponto == "fr"){
  $dponto = " Ponto de rescisão";
}
?>
<form name="form1" class="container">
<fieldset>
  <legend><?= $dponto ?></legend>
  <?php
  if ($ponto == "com" && DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
    
    echo '<fieldset id="toogle">';
    echo "  <legend>Dados da Folha Complementar</legend>";      
    $sDBOpcaoAnterior            = $db_opcao;
    $db_opcao                    = 3;
    include(modification("forms/db_frmrhfolhapagamento.php"));
    $db_opcao                    = $sDBOpcaoAnterior;
    echo "</fieldset>";
    
  }
  ?>
  <fieldset>
    <legend>Dados do Servidor</legend>
    <table>
      <tr>
        <td title="Digite o Ano / Mes de competência">
          <strong>Competência:</strong> 
        </td>
        <td>
          <?php
          db_input('DBtxt23', 4, $IDBtxt23, true, 'text', 3, "", 'r90_anousu');
          echo " / ";
          db_input('DBtxt25', 2, $IDBtxt25, true, 'text', 3, "", 'r90_mesusu');
          db_input('ponto', 10, 0, true, 'hidden', 3, '');
          db_input('rubricas_selecionadas_enviar', 40, 0, true, 'hidden', 3, '');
          db_input('quantidade_rubricas_selecionadas_enviar', 40, 0, true, 'hidden', 3, '');
          db_input('valores_rubricas_selecionadas_enviar', 40, 0, true, 'hidden', 3, '');
          db_input('datlim_rubricas_selecionadas_enviar', 40, 0, true, 'hidden', 3, '');
          db_input('tpp_rubricas_selecionadas_enviar', 40, 0, true, 'hidden', 3, '');
          db_input('lotacao_matricula', 40, 0, true, 'hidden', 3, '');
          db_input('admissa_matricula', 40, 0, true, 'hidden', 3, '');
          db_input('repassar_rubricas', 40, 0, true, 'hidden', 3, '');
          db_input('opcoes_rubricas', 40, 0, true, 'hidden', 3, '');

          $qry = "rubricas_selecionadas_enviar=".$rubricas_selecionadas_enviar;
          $qry.= "&ponto=".$ponto;
          if(isset($repassar_rubricas)){
            $qry.= "&repassar_rubricas=".$repassar_rubricas;
          }
  
          if(isset($r90_regist) && trim($r90_regist)!=""){
            $qry .= "&registro=".$r90_regist;
          }
         ?>
        </td>
      </tr>
      <tr>
        <td title="<? echo isset($Tr90_regist) ? $Tr90_regist : '' ?>">
          <?php
            db_ancora(@$Lr90_regist, "js_pesquisar90_regist(true);", $db_opcao);
          ?>
        </td>
        <td>
          <?php
          db_input('r90_regist', 8, $Ir90_regist, true, 'text', $db_opcao, " onchange='js_pesquisar90_regist(false);' onfocus='document.getElementById(\"caixa_de_texto\").innerHTML = \"\";'");
          db_input('z01_nome', 60, $Iz01_nome, true, 'text', 3, " onfocus='document.getElementById(\"caixa_de_texto\").innerHTML = \"\";'");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>  
</fieldset>
<?php
if($db_opcao != 3){  
?>
  <input type="button" name="enviar" value="Enviar dados" onclick="js_receber_dados_inclui();" onBlur="document.form1.voltar.focus();" onfocus="document.getElementById('caixa_de_texto').innerHTML = '';">
  <input type="button" name="voltar" value="Selecionar rubricas" onClick="location.href='pes1_pontoRfx001.php?ponto=<?=$ponto?>'"  onBlur="rubricas_dados_enviar.js_setar_foco_campo();" onfocus="document.getElementById('caixa_de_texto').innerHTML = '';">
<?php
}
?>
</form>
<br />
<div style="width:90%; height:100%; margin-right:auto; margin-left:auto;">
  <iframe name="rubricas_dados_enviar" id="rubricas_dados_enviar" marginwidth="0" marginheight="0" frameborder="0" src="pes1_pontoRfx0011_iframe.php?<?=$qry?>" width="98%" height="95%"></iframe>   
</div>

<div id="caixa_de_texto"></div>

<script src="scripts/widgets/DBToogle.widget.js"></script>
<script>
var oToogle = new DBToogle('toogle', true);

/**
 * Variavel global para testar regra do ponto
 */
var lTestarRegraPonto;

function js_testarRegraPonto() {

  lTestarRegraPonto = true;

  js_divCarregando('Processando...', 'msgBox');
   
  var oParametros  = new Object();

  oParametros.sExecucao            = 'testarRegistroPonto';
  oParametros.iMatricula           = $('r90_regist').value;
  oParametros.sTipoPonto           = $('ponto').value;
  oParametros.aRubricas            = $('rubricas_selecionadas_enviar').value.split(',');
  oParametros.aRubricasQuantidade  = $('quantidade_rubricas_selecionadas_enviar').value.split(',');
  oParametros.aRubricasValor       = $('valores_rubricas_selecionadas_enviar').value.split(',');
  oParametros.aRubricasQuantidadeValor = [];

  for (var iIndRubricas = 0; iIndRubricas < oParametros.aRubricas.length; iIndRubricas++) {
    var oRubrica = {
      sRubrica    : oParametros.aRubricas[iIndRubricas],
      nQuantidade : oParametros.aRubricasQuantidade[iIndRubricas],
      nValor      : oParametros.aRubricasValor[iIndRubricas]
    };
    oParametros.aRubricasQuantidadeValor.push(oRubrica);
  };

	var oAjax = new Ajax.Request(
		'pes1_rhrubricas.RPC.php', 
		{
      asynchronous: false,
			method     : 'post',
			parameters : 'json=' + Object.toJSON(oParametros),
      onComplete : js_retornoTestarRegraPonto
    }
	);   

  return lTestarRegraPonto;
}

function js_retornoTestarRegraPonto(oAjax) {

  js_removeObj('msgBox');

  var oRetorno  = eval("("+oAjax.responseText+")");
	var sMensagem = oRetorno.sMensagem.urlDecode().replace(/\\n/g, "\n");

  /**
   * Erro no RPC 
   */
  if ( oRetorno.iStatus > 1 ) {

    alert(sMensagem);
    return false;
  }

  if ( oRetorno.sMensagensBloqueio != '' ) {

    lTestarRegraPonto = false;
    alert( oRetorno.sMensagensBloqueio.urlDecode().replace(/\n/g, "\n") );
    return false;
  }

  if ( oRetorno.sMensagensAviso != '' ) {

    lConfirmarAviso = confirm( oRetorno.sMensagensAviso.urlDecode().replace(/\n/g, "\n") );

    /**
     * Clicou em cancelar 
     * - Nao submita form
     */
    if ( !lConfirmarAviso ) {
      lTestarRegraPonto = false;
    }
  }
}

function js_receber_dados_inclui() {

  if ( document.form1.r90_regist.value == "" ) {

    alert("Informe a matrícula");
    document.form1.r90_regist.focus();
    return false;
  }

  /**
   * Valida campos 
   */
  recebe = rubricas_dados_enviar.js_enviar_dados_inclui();

  if ( recebe ) {

    /**
     * Valida regras do ponto 
     */
    lTestarRegistroPonto = js_testarRegraPonto();

    /**
     * Regra do ponto é um bloqueio ou usuario clicou em cancelar do aviso 
     */
    if ( !lTestarRegistroPonto ) {
      return false;
    }

    js_divCarregando('Processando...', 'msgBox');

    obj=document.createElement('input');
    obj.setAttribute('name','incluir');
    obj.setAttribute('type','hidden');
    obj.setAttribute('value','incluir');
    document.form1.appendChild(obj);
    document.form1.submit();
    
    js_removeObj("msgBox");
  }

}
function js_pesquisar90_regist(mostra){
  document.getElementById('caixa_de_texto').innerHTML = "";
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=<?=($ponto == "fs" ? "raf" : "ra")?>&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>&chave_r01_mesusu='+document.form1.r90_mesusu.value+'&chave_r01_anousu'+document.form1.r90_anousu.value,'Pesquisa Matrícula',true);
  }else{
     if(document.form1.r90_regist.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=<?=($ponto == "fs" ? "raf" : "ra")?>&pesquisa_chave='+document.form1.r90_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa Matrícula',false);
     }else{
       document.form1.z01_nome.value = '';
       rubricas_dados_enviar.location.href = "pes1_pontoRfx0011_iframe.php?rubricas_selecionadas_enviar=<?=$rubricas_selecionadas_enviar?>&ponto=<?=$ponto?>&repassar_rubricas="+document.form1.repassar_rubricas.value; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.r90_regist.focus(); 
    document.form1.r90_regist.value = ''; 
  }else{
  	rubricas_dados_enviar.location.href = "pes1_pontoRfx0011_iframe.php?rubricas_selecionadas_enviar=<?=$rubricas_selecionadas_enviar?>&ponto=<?=$ponto?>&registro="+document.form1.r90_regist.value+"&repassar_rubricas="+document.form1.repassar_rubricas.value;
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.r90_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
  rubricas_dados_enviar.location.href = "pes1_pontoRfx0011_iframe.php?rubricas_selecionadas_enviar=<?=$rubricas_selecionadas_enviar?>&ponto=<?=$ponto?>&registro="+chave1+"&repassar_rubricas="+document.form1.repassar_rubricas.value;
}
</script>
