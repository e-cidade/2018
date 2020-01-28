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

require_once("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

if (isset($opcao) && $opcao == "alterar") {

  $db_botao = true;
  $db_opcao = 2;
  
} else if(isset($opcao) && $opcao == "excluir") {

  $db_opcao = 3;
  $db_botao = true;
  
} else {  
  
  $db_botao = $db_opcao == 33 ? false : true;
  
  if((isset($novo) || isset($alterar) || isset($excluir) || isset($incluir)) && @$sqlerro == false ) {
    
    $k140_sequencial = '';
    $k140_multa      = '';
    $k140_faixa      = '';
  }
  
} 
?>
<style>
#k02_dtfrac {width:100px !important;}
</style>
<center>
  <fieldset style="width:700px;">
    <legend align="left"><strong>Multas</strong></legend>

    <form name="form1" method="post" action="">
      
      <table>
        <tr>
          <td colspan="2" width="165"><strong>Tipo de Multa:</strong> </td>
          <td>
          <?php  
            $aTipoMulta = array('0' => 'Selecione', '1' => 'Multa Diária', '2' => 'Multa Mensal');
            db_select('tipoMulta', $aTipoMulta, true, 1, 'onchange="js_tipoMulta(this.value)"');
          ?>
          </td>
        </tr>
      </table>
  
      <div style="display: none;" id="multa_diaria">
        <fieldset >
          <legend align="left"><b> Multa Diária</b></legend>
          
          <table>
            <tr>
              <td nowrap title="<?=@$Tk140_tabrecjm?>">
                <?php echo $Lk140_tabrecjm;  ?>
              </td>
              <td nowrap title="<?=@$Tk140_tabrecjm?>">
                <?php db_input('k140_tabrecjm', 11, $Ik140_tabrecjm, true, 'text', 3, ''); ?>
              </td>
            </tr>
            <tr>
              <td width="165"><strong>Fra&ccedil;&atilde;o Multa:</strong> </td>
              <td><? db_input('k02_mulfra',11,$Ik02_mulfra,true,'text',$db_opcao,"onChange=\" \"")?>%</td>
            </tr>
            <tr>
              <td><strong>Limite:</strong></td>
              <td><? db_input('k02_limmul',11,$Ik02_limmul,true,'text',$db_opcao,"onChange=\" \"")?>%</td>
            </tr>

            <tr>
              <td align="left"><strong>Data inicial:</strong></td>
              <td align="left" nowrap title="<?php echo $Tk02_dtfrac; ?>"> 
                <?php
                  db_inputdata('k02_dtfrac', $k02_dtfrac_dia, $k02_dtfrac_mes, $k02_dtfrac_ano, 
                                true, 'text', $db_opcao, "");
                ?>
              </td>
            </tr>  
        

          </table>
        </fieldset>
        <br />
        <input type="submit" name="salvar" id="salvar" value="Salvar">
      </div>
      
                  
      <fieldset style="display: none;" id="multa_mensal">
        <legend><b>Multa Mensal</b></legend>

        <table border="0">
          <tr>
            <td width="184" nowrap title="<?=@$Tk140_sequencial?>">
              <?php echo $Lk140_sequencial;  ?>
            </td>
            <td nowrap title="<?=@$Tk140_sequencial?>">
              <?php db_input('k140_sequencial', 10, $Ik140_sequencial, true, 'text', 3, ''); ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tk140_tabrecjm?>">
              <?php echo $Lk140_tabrecjm;  ?>
            </td>
            <td nowrap title="<?=@$Tk140_tabrecjm?>">
              <?php db_input('k140_tabrecjm', 10, $Ik140_tabrecjm, true, 'text', 3, ''); ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tk140_multa?>">
               <?php echo $Lk140_multa;  ?>
            </td>
            <td nowrap title="<?=@$Tk140_multa?>">
              <?php db_input('k140_multa',10,$Ik140_multa,true,'text', $db_opcao, ''); ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tk140_faixa?>">
               <?php echo $Lk140_faixa;  ?>
            </td>
            <td nowrap title="<?=@$Tk140_faixa?>">
              <?php db_input('k140_faixa',10,$Ik140_faixa,true,'text', $db_opcao, ''); ?>
            </td>
          </tr>
        </table>
        
        <br />
       
        <table>
          <tr>
            <td>
              <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
              <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal) || $db_opcao == 3 ?"style='display:none;'":"")?> >
              <input type="hidden" name="tipo_multa_atual" value="<?php echo $tipoMulta; ?>" />
            </td>
          </tr>
        </table>
        
        <br />
        
        <table>
          <tr>
            <td valign="top" align="center">  
            <?php
              $cliframe_alterar_excluir->chavepri      = array("k140_sequencial" => @$k140_sequencial);
              $cliframe_alterar_excluir->sql           = $oDaoTabrecjmmulta->sql_query(null, "*", "k140_multa", "k140_tabrecjm = $k140_tabrecjm");
              $cliframe_alterar_excluir->campos        = "k140_sequencial, k140_tabrecjm, k140_multa, k140_faixa";
              $cliframe_alterar_excluir->legenda       = "Faixas de multas";
              $cliframe_alterar_excluir->opcoes        = $db_opcao; 
              $cliframe_alterar_excluir->iframe_height = "160";
              $cliframe_alterar_excluir->iframe_width  = "600";
              $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
            ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </form>
  </fieldset>
</center>

<script>
var iTipoMultaAtual = document.form1.tipo_multa_atual.value;

js_tipoMulta(document.form1.tipoMulta.value);
  
document.form1.onsubmit = function() {

  var iTipoMultaSelecionada = document.form1.tipoMulta.value;
  var sMensagem             = "";
  var lMostraConfirm        = false;


  if(iTipoMultaSelecionada == 1 && document.form1.k02_dtfrac.value == '') {

    alert('Campo Data Inicial não informado');
    return false;
  }

  if (iTipoMultaAtual != iTipoMultaSelecionada && iTipoMultaAtual != 0) {

    if (iTipoMultaSelecionada == 1) {
      sMensagem      = "A regra está configurada com tipo de multa mensal. \nCaso confirme, as configurações de multa mensal serão perdidas. \nDeseja confirmar?";
    } else if (iTipoMultaSelecionada == 2) {
      sMensagem      = "A regra está configurada com tipo de multa diária. \nCaso confirme, as configurações de multa diária serão perdidas. \nDeseja confirmar?";
    }  
    lMostraConfirm = true;
  }

  if(lMostraConfirm) {
    if(!confirm(sMensagem)) {
      return false;      
    }        
  }  
}

function js_tipoMulta(iTipo) {  

  document.getElementById('multa_diaria').style.display = 'none';
  document.getElementById('multa_mensal').style.display = 'none';

  if(iTipo == 1) {
    document.getElementById('multa_diaria').style.display = 'block';
    document.getElementById('multa_mensal').style.display = 'none';
  }  
  
  if(iTipo == 2) {
    document.getElementById('multa_diaria').style.display = 'none';
    document.getElementById('multa_mensal').style.display = 'block';
  }
  
}
  
function js_cancelar(){
  
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

</script>