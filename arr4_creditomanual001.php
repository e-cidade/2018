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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

$clrotulo = new rotulocampo;

$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('k155_sequencial');
$clrotulo->label('k155_descricao');
$clrotulo->label('k156_observacao');
$clrotulo->label('k125_valor');
$clrotulo->label('k125_datalanc');
$clrotulo->label('p58_codproc');
$clrotulo->label('k160_abatimento');
$clrotulo->label('k160_data');
$clrotulo->label('k160_nometitular');
$clrotulo->label('k160_numeroprocesso');
$clrotulo->label('k160_sequencial');

$k125_datalanc_dia = date('d');
$k125_datalanc_mes = date('m');
$k125_datalanc_ano = date('Y');

?>

<html>
<head>
<?php 
  db_app::load("scripts.js, prototype.js, strings.js, estilos.css"); 
?>
</head>

<body bgcolor="#CCCCCC">

<form name="form1" method="post" action="">

  <fieldset style="margin: 25px auto; width: 600px;">
    <legend><strong>Dados do Crédito</strong></legend>
    
    <table align="center">
    
      <tr>
        <td title="<?php echo $Tz01_nome; ?>">
          <?php
            db_ancora($Lz01_nome, 'js_pesquisaNome(true)', 1);
          ?>
        </td>
        
        <td>
          <?php
            db_input('z01_numcgm', 10, $Iz01_numcgm, true, 'text', 1, 'onchange="js_pesquisaNome(false)"');
  
            db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3);
          ?>
        </td>
        
      </tr>
      
      <tr>
        <td title="<?php echo $Tk125_datalanc?>">
          <?php 
            echo $Lk125_datalanc;
          ?>
        </td>
        <td title="<?php echo $Tk125_datalanc?>">
          <?php
            db_inputdata('k125_datalanc', @$k125_datalanc_dia, @$k125_datalanc_mes, @$k125_datalanc_ano, true, 'text', 1)
          ?>
        </td>
      </tr>
      
      <tr>
        <td title="<?php echo $Tk155_descricao; ?>">
          <?php
            
            $sLabelRegra = '<strong>Regra Compensação:</strong>';
            
            db_ancora($sLabelRegra, 'js_pesquisaRegraCompensacao(true)', 1);
            
          ?>
        </td>
        <td>
          <?php
        
            db_input('k155_sequencial', 10, $Ik155_sequencial, true, 'text', 1, 'onchange="js_pesquisaRegraCompensacao(false)"');
  
            db_input('k155_descricao', 40, $Ik155_descricao, true, 'text', 3);
            
          ?>
        </td>
      </tr>
      
      <tr>
        <td title="<?php echo $Tk125_valor?>">
          <?php 
            echo $Lk125_valor;
          ?>
        </td>
        <td title="<?php echo $Tk125_valor?>">
          <?php
            db_input('k125_valor', 10, $Ik125_valor, true, 'text', 1);
          ?>
        </td>
      </tr>
      
      <tr>
        <td title="Processo registrado no protocolo do sistema">
          <strong>Processo do Sistema</strong>
        </td>
        <td title="Sim = Processo registrado no protocolo, Não = Processo externo">
          <?php
            db_select('lProcessoSistema', array(''=>'SELECIONE', 'S' => 'SIM', 'N' => 'NÃO'), true, 1, 'onchange="js_processoSistema(this.value)" style="width: 93px;"')
          ?>
        </td>
      </tr>  
      
      <tr>
        <td colspan="2">
        
          <div id="processoSistemaInterno">
          
            <fieldset><legend><strong>Dados do Processo</strong></legend>
            
            <table align="center">
              <tr>
                <td title="<?=@$Tp58_codproc?>">
                <?
                  db_ancora($Lp58_codproc, 'js_pesquisaProcesso(true)', 1)
                ?>
                </td>
                <td>
                <? 
                
                  db_input('p58_codproc', 10, $Ip58_codproc, true, 'text', 1, "onchange='js_pesquisaProcesso(false)'");
                  db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, "", 'z01_nomeprocesso');
                  
                ?>
                </td>
              </tr>
            </table>
             
            </fieldset>
            
          </div>
          
        </td>
      </tr>
      
      <tr>
        <td colspan="2">
        
          <div id="processoSistemaExterno">
          
            <fieldset><legend><strong>Dados do Processo</strong></legend>
            
            <table align="center">
              <tr>
                <td title="<?=@$Tk160_numeroprocesso?>">
                <?php
                  echo $Lk160_numeroprocesso;
                ?>
                </td>
                <td>
                <? 
                  db_input('k160_numeroprocesso', 40, $Ik160_numeroprocesso, true, 'text', 1);
                ?>
                </td>
              </tr>
              
              <tr>
                <td title="<?=@$Tk160_nometitular?>">
                <?php
                  echo $Lk160_nometitular;
                ?>
                </td>
                <td>
                <? 
                  db_input('k160_nometitular', 40, $Ik160_nometitular, true, 'text', 1);
                ?>
                </td>
              </tr>
              
              <tr>
                <td title="<?=@$Tk160_data?>">
                <?php
                  echo $Lk160_data;
                ?>
                </td>
                <td>
                <?php 
                  db_inputdata('k160_data', @$k160_data, @$k160_mes, @$k160_ano, 'text', true, 1);
                  
                ?>
                </td>
              </tr>
              
            </table>
             
            </fieldset>
            
          </div>
          
        </td>
      </tr>
      
      <tr>
        <td title="<?php echo $Tk156_observacao?>" colspan="2">
          <fieldset>
            <legend><strong><?php echo $Lk156_observacao?></strong></legend>
            <?php 
               db_textarea('k156_observacao', 10, 66, $Ik156_observacao, true, 'text', 1)
            ?>
          </fieldset>      
        </td>
      </tr>
    
    </table>
  </fieldset>
  
  <center>
    <input type="button" value="Salvar" onclick="js_salvar()" />
  </center>
   
</form>

<script>

js_processoSistema($F('lProcessoSistema'));            

function js_processoSistema(lProcessoSistema) {

  if(lProcessoSistema == 'S') {

    $('processoSistemaInterno').style.display = '';
    $('processoSistemaExterno').style.display = 'none';


  } else if (lProcessoSistema == 'N'){

    $('processoSistemaInterno').style.display = 'none';
    $('processoSistemaExterno').style.display = '';
    
  } else {

    $('processoSistemaInterno').style.display = 'none';
    $('processoSistemaExterno').style.display = 'none';
    
  }

}            

sUrl = 'arr4_creditomanual.RPC.php'; 

function js_salvar() {

  if($F('z01_numcgm') == "") {

    alert("CGM não informado!");
    return false;
  }

  if($F('k155_sequencial') == ""){
    
    alert("Regra de Compensação não informado!");
    return false;
  }

	var oParam                         = new Object();
	oParam.sExec                       = 'novoCredito';
	oParam.iCodigoCgm                  = $F('z01_numcgm');
	oParam.iCodigoRegraCompensacao     = $F('k155_sequencial');
	oParam.fValor                      = $F('k125_valor');
	oParam.lProcessoSistema            = $F('lProcessoSistema');
  oParam.iCodigoProcessoSistema      = $F('p58_codproc');
  oParam.sNumeroProcessoExterno      = $F('k160_numeroprocesso');
  oParam.sNomeTitularProcessoExterno = encodeURIComponent(tagString($F('k160_nometitular')));
  oParam.dDataProcessoExterno        = $F('k160_data');
	oParam.sObservacao                 = encodeURIComponent(tagString($F('k156_observacao')));

	js_divCarregando('Pesquisando, aguarde.', 'msgbox');

	var oAjax = new Ajax.Request(sUrl,
			                        {
	                             method    : 'POST',
                               parameters: 'json='+Object.toJSON(oParam), 
                               onComplete: js_confirma
                              });
	
}            

function js_confirma(oAjax){

  js_removeObj('msgbox');
  
  var sExpReg  = new RegExp('\\\\n','g');
  
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.iStatus == 1) {
    
    sMensagem = "Crédito lançado com sucesso para o cgm : " + $F('z01_numcgm');

    alert(sMensagem);
    
    window.location = 'arr4_creditomanual001.php';
    
  } else { 

    alert(oRetorno.sMessage.urlDecode().replace(sExpReg,'\n'));

    return false;
    
  }
  
}

function js_pesquisaNome(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_nome.php?funcao_js=parent.js_mostraNome|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostraNomeHide','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}

function js_mostraNomeHide(erro, chave){
  
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
  
}

function js_mostraNome(chave1,chave2){
  
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_nomes.hide();
  
}

function js_pesquisaRegraCompensacao(mostra){
	/*variável tiporegracompensacao define as regras de compensação que podem aparecer na tela de pesquisa
		7 = LANÇAMENTO MANUAL
		exemplo: &tiporegracompensacao=7
	*/
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_regracompensacao','func_regracompensacao.php?funcao_js=parent.js_mostraRegraCompensacao|k155_sequencial|k155_descricao&tiporegracompensacao=7','Pesquisa',true);
  }else{
     if(document.form1.k155_sequencial.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_regracompensacao','func_regracompensacao.php?pesquisa_chave='+document.form1.k155_sequencial.value+'&funcao_js=parent.js_mostraRegraCompensacaoHide&tiporegracompensacao=7','Pesquisa',false);
     }else{
       $('k155_descricao').value  = '';
     }
  }
}

function js_mostraRegraCompensacaoHide(chave, erro){

	document.form1.k155_descricao.value = chave;
   
  if(erro==true){ 
    document.form1.k155_sequencial.focus(); 
    document.form1.k155_sequencial.value = '';
  }

}

function js_mostraRegraCompensacao(chave1,chave2){
  
  document.form1.k155_sequencial.value = chave1;
  document.form1.k155_descricao.value  = chave2;
  db_iframe_regracompensacao.hide();
  
}

function js_pesquisaProcesso(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_protprocesso.php?funcao_js=parent.js_mostraProcesso|p58_codproc|z01_nome&sCampoPesquisa=p58_codproc','Pesquisa',true);
  }else{
     if(document.form1.p58_codproc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_protprocesso.php?pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostraProcessoHide&sCampoPesquisa=p58_codproc','Pesquisa',false);
     }else{
       document.form1.z01_nomeprocesso.value = ''; 
     }
  }
}

function js_mostraProcessoHide(erro, chave){
  
  document.form1.z01_nomeprocesso.value = chave; 
  if(erro==true){ 
    document.form1.p58_codproc.focus(); 
    document.form1.p58_codproc.value = ''; 
  }
  
}

function js_mostraProcesso(chave1,chave2){
  
  document.form1.p58_codproc.value 			= chave1;
  document.form1.z01_nomeprocesso.value = chave2;
  db_iframe_nomes.hide();
  
}

</script>

<?php 
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); 
?>
</body>
</html>