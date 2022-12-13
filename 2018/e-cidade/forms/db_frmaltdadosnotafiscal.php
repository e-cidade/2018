<?
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

$oGet = db_utils::postMemory($_GET);
$opcaoA    = 3;
$opcaoB    = 3;
$lDesabled = true;
$sMsg      = "";

if (isset($oGet->chavepesquisa)) {

  $opcaoA    = 1;
  $opcaoB    = 1;
  $lDesabled = false;

  if (isset($e70_vlrliq) && $e70_vlrliq > 0) {

    $opcaoA = 3;
    $sMsg   = "** Não será permitida alteração de data quando a nota já foi liquidada e/ou paga a NF **";
  }
}

if ($lDisabled2) {
 $opcaoA = 3;
 $opcaoB = 3;       
}

$Tm51_codordem = isset($Tm51_codordem) ? $Tm51_codordem : null;
$Lm51_numcgm   = isset($Lm51_numcgm)   ? $Lm51_numcgm   : null;
$Tdescrdepto   = isset($Tdescrdepto)   ? $Tdescrdepto   : null;
$Lm51_depto    = isset($Lm51_depto)    ? $Lm51_depto    : null;
$Te11_cfop     = isset($Te11_cfop)     ? $Te11_cfop     : null;
$Tm51_valortotal = isset($Tm51_valortotal) ? $Tm51_valortotal : null;
$Ie11_basecalculoicms = isset($Ie11_basecalculoicms) ? $Ie11_basecalculoicms : null;
$Ie11_basecalculosubstitutotrib = isset($Ie11_basecalculosubstitutotrib) ? $Ie11_basecalculosubstitutotrib : null;

?>
<form name="form1" method="post" action="">
<center>

<fieldset style="margin-top: 50px; width: 700px;">
<legend>Alteração de Notas Fiscais</legend>
<div >

          <table align="center" border='0'>
          <tr>
           <td nowrap title="<?= $Tm51_codordem ?>">
             <b> <?db_ancora("Ordem de Compra:","js_consultaordemcompra(\$F('m51_codordem'));",1);?></b>
           </td>
           <td> 
           <?
             db_input('m51_codordem',10,'',true,'text');
           ?>
           </td>
           <td nowrap title="<?=$Te69_numero?>">
            <b>Nota:</b>
           </td>
           <td> 
            <? db_input('e69_numero', 15, 1, true, 'text', $opcaoB); ?>
           </td>           
           </tr>
          <!--[Extensao ContratosPADRS] campo serie nota -->
           <tr>
           <td nowrap title="Fornecedor">
            <?= $Lm51_numcgm ?>
          </td>
          <td> 
          <?
           db_input('m51_numcgm',10,$Im51_numcgm,true,'text');
           db_input('z01_nome',30,$Iz01_nome,true,'text');
          ?>
          </td>
           <td nowrap title="<?=$Te69_dtnota?>">
            <?=$Le69_dtnota?>
           </td>
           <td> 
          <?                   
          if (!isset($e69_dtnota_dia)) {
            $e69_dtnota_dia = null;
          }          
          if (!isset($e69_dtnota_mes)) {
            $e69_dtnota_mes = null;
          }           
          if (!isset($e69_dtnota_ano)) {
            $e69_dtnota_ano = null;
          }           
           db_inputdata('e69_dtnota',$e69_dtnota_dia,$e69_dtnota_mes,$e69_dtnota_ano,true,'text',$opcaoA);
          ?>
          </td>            
         </tr> 
         <tr>
          </td>
           <td nowrap title="<?= $Tdescrdepto ?>">
            <?= $Lm51_depto ?>
          </td>
          <td> 
          <?
           db_input('m51_depto',10,$Im51_depto,true,'text');
           db_input('descrdepto',30,$Idescrdepto,true,'text');
          ?>
          </td>
           <td nowrap title="<?=$Te69_dtrecebe?>">
            <?=$Le69_dtrecebe?>
           </td>
           <td> 
          <?          
          if (!isset($e69_dtrecebe_dia)) {
          	$e69_dtrecebe_dia = null;
          }
          if (!isset($e69_dtrecebe_mes)) {
            $e69_dtrecebe_mes = null;
          }          
          if (!isset($e69_dtrecebe_ano)) {
            $e69_dtrecebe_ano = null;
          }          
           db_inputdata('e69_dtrecebe',$e69_dtrecebe_dia,$e69_dtrecebe_mes,$e69_dtrecebe_ano,true,'text',$opcaoA);
          ?>
          </td>           
        </tr>
         <tr>
          </td>
           <td nowrap title="<?= $Tm51_valortotal ?>">
            <?=$Lm51_valortotal?>
          </td>
          <td> 
          <?
           db_input('m51_valortotal',10,$Im51_valortotal,true,'text');
          ?>
          </td>
           <td nowrap title="<?=$Te70_valor;?>">
            <?=$Le70_valor;?>
          </td>
          <td> 
          <?
           db_input('e70_valor',10,$Ie70_valor,true,'text');
          ?>
          </td>          
        </tr>
        <?
        if ($iControlaPit == 1) {
            
          ?>
          <tr id='controlepit' style='display: <?=$iControlaPit==1?"":"none"?>'>
            <td><b>Tipo da Entrada: </b></td>
            <td colspan="4">
            <?
            $oDaoDocumentoFiscais = db_utils::getDao("tipodocumentosfiscal");
            $rsDocs = $oDaoDocumentoFiscais->sql_record($oDaoDocumentoFiscais->sql_query(null, "*", "e12_sequencial"));
            $aItens[0] = "selecione"; 
            for($i = 0; $i < $oDaoDocumentoFiscais->numrows; $i ++) {
              
              $oItens = db_utils::fieldsMemory($rsDocs, $i);
              $aItens [$oItens->e12_sequencial] = $oItens->e12_descricao;
            
            }
            db_select('e69_tipodocumentosfiscal', $aItens, true, $db_opcao  );
            ?>
            <a href='#' onclick='js_abreNotaExtra()' style='display: none' 
               id='dadosnotacomplementar'>Outros Dados</a>
           </td>
          </tr>  
          <?
          }
          ?>
          <tr>
             <td nowrap title="<?= $Te11_cfop ?>">
               <?
               db_ancora("<b>CPOF</b>","js_pesquisae11_cfop(true);",$db_opcao);
               ?>
             </td>
             <td nowrap colspan='3' > 
              <?
              db_input('e11_cfop',10,$Ie11_cfop,true,'text',3," onchange='js_pesquisae11_cfop(false);'");
              db_input('e10_cfop',10,$Ie10_cfop,true,'text', $db_opcao," onchange='js_pesquisae11_cfop(false);'");
              db_input('e10_descricao',40,$Ie10_descricao,true,'text',3,'')
               ?>
             </td>
           </tr> 
           <tr>
             <td  nowrap>
                <b>Série:</b>
             </td>
             <td  nowrap>
             <? 
             db_input('e11_seriefiscal',10,$Ie11_seriefiscal,true,'text',$db_opcao,'');
             ?>
             </td>
           </tr>
            <tr>
             <td  nowrap>
                <b>Inscrição Subst.Fiscal:</b>
             </td>
             <td  nowrap>
             <? 
             db_input('e11_inscricaosubstitutofiscal',10,$Ie11_inscricaosubstitutofiscal,true,'text',$db_opcao,'');
             ?>
             </td>
           </tr>  
           <tr>
             <td  nowrap>
                <b>Base Calculo ICMS:</b>
             </td>
             <td  nowrap>
             <? 
             db_input('e11_basecalculoicms',10, $Ie11_basecalculoicms ,true,'text',$db_opcao,'');
             ?>
             </td>
           </tr> 
           <tr>
             <td  nowrap>
                <b>Valor ICMS:</b>
             </td>
             <td  nowrap>
             <? 
             db_input('e11_valoricms',10,$Ie11_valoricms,true,'text',$db_opcao,'');
             ?>
             </td>
           </tr>
           <tr>
             <td  nowrap>
                <b>Base Calculo ICMS Substituto:</b>
             </td>
             <td  nowrap>
             <? 
             db_input('e11_basecalculosubstitutotrib',10, $Ie11_basecalculosubstitutotrib, true,'text',$db_opcao,'');
             ?>
             </td>
           </tr> 
           <tr>
             <td  nowrap>
                <b>Valor ICMS Substituto:</b>
             </td>
             <td  nowrap>
             <? 
             db_input('e11_valoricmssubstitutotrib',10,$Ie11_valoricmssubstitutotrib,true,'text',$db_opcao,'');
             ?>
             </td>
           </tr>        
      </table>
</div>

</fieldset>
      
      
  <div style="margin-top: 10px;">
  
     <input name="alterar"    id='alterar' type="submit" value="Atualizar" onclick='return validar_campos();'>
     <input name="pesquisar"               type="button" value="Pesquisar" onclick="js_pesquisa_empnota(true)">
     <? db_input('e69_codnota',10,$Ie69_codnota,true,'hidden'); ?>
  
  </div>
  
  </center>
  </form>
  <table border="0" width="55%">
    <tr>
      <td><?= $sMsg; ?></td>
    </tr>
  </table>
 <script>
function js_consultaordemcompra(codordem){
  if(codordem != ""){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_ordemcompra002','com3_ordemdecompra002.php?m51_codordem='+codordem,'Consulta Ordem de Compra',true);  
  }
}

function js_pesquisa_empnota(){
 js_reset();
 js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empnota',
                     'func_empnota.php?funcao_js=parent.js_mostraempnota1|e69_codnota&lNaoTrazerAnuladas=1&lm72_codordem=1',
                     'Pesquisa',true);
}

function js_mostraempnota1(chave1){
   location.href='mat1_altDadosNotaFiscal001.php?chavepesquisa='+chave1;
}
  

function js_reset(){

  $('z01_nome').value        = '';
  $('m51_numcgm').value      = '';
  $('m51_codordem').value    = '';
  $('e69_numero').value      = '';
  $('e69_dtnota').value      = '';
  $('e69_dtrecebe').value    = '';
  $('e70_valor').value       = '';
  $('m51_valortotal').value  = '';
  $('m51_depto').value       = '';
  $('descrdepto').value      = '';

}

function js_alterar_ordemcompra(){
  location.href = "mat1_altDadosNotaFiscal001.php"; 
}

function validar_campos() {

 if ($F('e69_tipodocumentosfiscal') == "") {
   
   alert('Informe o Tipo da nota');
   return false;
   
 }
 
 if ($F('e69_tipodocumentosfiscal') == 50 && $F('e11_cfop') == "") {
   
   alert('Informe a CFOP!');
   return false;
   
 }

/** [Extensao ContratosPADRS] valida serie nota */

if ($('e69_dtnota').value == ""){
   alert('Campo data nota não informado!');
   return false;
 } else if ($('e69_dtnota').value == ""){
   alert('Campo data do recebimento não informado!');
   return false;
 } else {
   if( !confirm('Deseja fazer a alteração?') ){
     return false;
   }
 }
}

<?
 if (isset($sPesquisa) && $sPesquisa == true){
 	echo "js_pesquisa_empnota();";
 }

 if ($lDesabled){
    echo "$('alterar').disabled = true;";  
 }
?>
function js_abreNotaExtra() {

  if ($F('e69_tipodocumentosfiscal') == 50) {
      $('dadosnotacomplementar').style.display='';
  } else {
    $('dadosnotacomplementar').style.display='none';
  }
}
function js_pesquisae11_cfop(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_cfop', 
                        'func_cfop.php?funcao_js=parent.js_mostracfop1|e10_sequencial|e10_descricao|e10_cfop',
                        'Pesquisa CFOP',true);
  }else{
     if($('e10_cfop').value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo',
                            'db_iframe_cfop',
                            'func_cfop.php?pesquisa_chave='+$('e10_cfop').value+'&funcao_js=parent.js_mostracfop',
                            'Pesquisa CFOP',false);
     }else{
       $('e10_descricao').value = ''; 
     }
  }
}
function js_mostracfop(chave,chave2, erro){

  $('e10_descricao').value = chave; 
  $('e11_cfop').value      = chave2; 
  if(erro==true){ 
    $('e10_cfop').focus(); 
    $('e10_cfop').value = ''; 
  }
}
function js_mostracfop1(chave1,chave2, chave3){

  $('e11_cfop').value = chave1;
  $('e10_descricao').value = chave2;
  $('e10_cfop').value = chave3;
  db_iframe_cfop.hide();
  
}
</script>
