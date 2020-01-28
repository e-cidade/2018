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



?>
<form action="" name="form1" > 
  <fieldset style="width: 280px;">
    <legend class='label'>Numero do Sequencial</legend>
    <table >
      <tr title="<?=@$Tp07_ano?>">
        <td class='label' nowrap >
          <?=@$Lp07_ano?>
          </td>
        <td class='info'>
          <?
            db_input('p07_ano',15,$Ip07_ano,true,'text',$db_opcao,"");
            db_input('p07_sequencial',15,$Ip07_sequencial,true,'hidden',3,"");
            db_input('p07_instit',15,$Ip07_instit,true,'hidden',3,"");
          ?>
        </td>
      </tr>
      <tr  title="<?=@$Tp07_proximonumero?>">
        <td class='label' nowrap>
          <?=@$Lp07_proximonumero?>
        </td>
        <td class='info'>
          <?
            db_input('p07_proximonumero',15,$Ip07_proximonumero,true,'text',$db_opcao,"");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <div align="center">
    <input id="botao" type="submit" name='opcao' value="<?=$sBotao?>" />
  </div>
  <br />
  <div align="center" style="width: 500px;">
    <?
      $sAnoUso = db_getsession("DB_anousu");
      $sWhere = "p07_ano = " . $sAnoUso; 
      $iOpcoes = 4;
      if ($iTipoParamGlobal == 2 ){
        
        $sInstit = db_getsession("DB_instit");
        $sWhere .= " and p07_instit = " . $sInstit; 
        $iOpcoes = 2;
        
      }
      $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;  
      
      $chavepri = array("p07_sequencial"=>@$p07_sequencial);
      $cliframe_alterar_excluir->chavepri      = $chavepri;
      $cliframe_alterar_excluir->campos        = "p07_sequencial,p07_instit,p07_ano,p07_proximonumero";
      $cliframe_alterar_excluir->sql           = $clprotprocessonumeracao->sql_query_file(null,"*","p07_sequencial"," {$sWhere}");
      $cliframe_alterar_excluir->legenda       = "Numerações Cadastradas";
      $cliframe_alterar_excluir->msg_vazio     ="<font size='1'>Nenhum andamento Cadastrado!</font>";
      $cliframe_alterar_excluir->textocabec    ="darkblue";
      $cliframe_alterar_excluir->iframe_width  = 500;
      $cliframe_alterar_excluir->textocorpo    ="black";
      $cliframe_alterar_excluir->fundocabec    ="#aacccc";
      $cliframe_alterar_excluir->fundocorpo    ="#ccddcc";
      $cliframe_alterar_excluir->iframe_height ="170";
      $cliframe_alterar_excluir->opcoes        = $iOpcoes;
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);   
  
    ?> 
  
  </div>
</form>

<script type="text/javascript">
/*
function comparaValor(proximoNumero){
  var numeroAntigo = <?=@$p07_proximonumero?>;
  
  if (numeroAntigo > proximoNumero.value) {
    alert("Próximo número não pode ser menor do que o número do ultimo protocolo cadastrado: "+numeroAntigo);
    proximoNumero.value = numeroAntigo;
  }
 
}
*/
</script>