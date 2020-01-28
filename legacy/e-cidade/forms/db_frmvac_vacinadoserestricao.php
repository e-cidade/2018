<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: Vacinas
$oDaoVacVacinadoserestricao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("vc02_i_codigo");
$clrotulo->label("vc08_i_vacinadose");
$clrotulo->label("vc07_c_nome");
$clrotulo->label("vc06_i_codigo");
$clrotulo->label("vc06_c_descr");
$clrotulo->label("vc07_i_vacina");

?>
<fieldset style='width: 75%;'> <legend><b>Restrições</b></legend>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr style="display: none;">
    <td nowrap>
       <?=@$Lvc07_i_vacina?>
    </td>
    <td> 
      <?
        db_input('vc06_i_codigo',10,$Ivc06_i_codigo,true,'text',3,"");
        db_input('vc06_c_descr',30,$Ivc06_c_descr,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc07_i_vacina?>">
      <?=@$Lvc08_i_vacinadose?>
    </td>
    <td> 
      <?
        db_input('vc08_i_vacinadose',10,$Ivc08_i_vacinadose,true,'text',3,"");
        db_input('vc07_c_nome',30,$Ivc07_c_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <table border="0" width="100%">
        <tr>
          <td nowrap width="50%" valign="top" align="right">
            <b>Restrições:</b><br>
            <select multiple id="restricoes" name="restricoes" style=" width: 100%;" size="10" 
                    onDblClick="js_moveDireita();">
            <?
            $sWhere  = 'vc02_i_codigo not in (select vc08_i_restricao from vac_vacinadoserestricao ';
            $sWhere .= ' where vc08_i_vacinadose = '.$vc08_i_vacinadose.')';
            $sSql    = $oDaovac_restricao->sql_query_file(null, 'vc02_i_codigo, vc02_c_nome', 'vc02_c_nome', $sWhere);
            $rs      = $oDaovac_restricao->sql_record($sSql);

            for ($iCont = 0; $iCont < $oDaovac_restricao->numrows; $iCont++) {

              $oDados = db_utils::fieldsmemory($rs, $iCont);
              echo '<option value="'.$oDados->vc02_i_codigo.'">'.$oDados->vc02_c_nome.'</option>';

            }
            ?>
            </select>
          </td>
          <td nowrap align="center">
            <input type="button" onclick="js_moveDireita();" value=">">
            <br><br>
            <input type="button" onclick="js_selecionarTudo($('restricoes')); js_moveDireita();" value=">>">
            <br><br>
            <input type="button" onclick="js_selecionarTudo($('restricoesAdicionadas')); js_moveEsquerda();" value="<<">
            <br><br>
            <input type="button" onclick="js_moveEsquerda();" value="<">
          </td>
          <td nowrap width="50%" valign="top"> 
            <b>Restrições Adicionadas:</b><br>
            <select multiple id="restricoesAdicionadas" name="restricoesAdicionadas[]" style=" width: 100%;" size="10"
                    onDblClick="js_moveEsquerda();">
            <?
            $sWhere  = 'vc02_i_codigo in (select vc08_i_restricao from vac_vacinadoserestricao ';
            $sWhere .= ' where vc08_i_vacinadose = '.$vc08_i_vacinadose.')';
            $sSql    = $oDaovac_restricao->sql_query_file(null, 'vc02_i_codigo, vc02_c_nome', 'vc02_c_nome', $sWhere);
            $rs      = $oDaovac_restricao->sql_record($sSql);

            for ($iCont = 0; $iCont < $oDaovac_restricao->numrows; $iCont++) {

              $oDados = db_utils::fieldsmemory($rs, $iCont);
              echo '<option value="'.$oDados->vc02_i_codigo.'">'.$oDados->vc02_c_nome.'</option>';

            }
            ?>
            </select>
          </td> 
        </tr>
      </table>
    </td>
  </tr>
</table>
</center>
<input name="confirmar" type="submit" id="confirmar" value="Confirmar" 
       onclick=" return js_selecionarTudo($('restricoesAdicionadas'));">
</form>
</fieldset>

<script>

function js_selecionarTudo(oSel) {

  for(iCont = 0; iCont < oSel.length; iCont++) {
    
    oSel.options[iCont].selected = true;

  }

  return true;

}


function js_moveDireita() {

  var iCont;
  var oEsq = $('restricoes');
  var oDir = $('restricoesAdicionadas');

  for (iCont = 0; iCont < oEsq.length; iCont++) {

    if (oEsq.options[iCont].selected) {
  
      oDir.options[oDir.length] = oEsq.options[iCont];
      iCont--;

    }

  }

}

function js_moveEsquerda() {

  var iCont;
  var oEsq = $('restricoes');
  var oDir = $('restricoesAdicionadas');

  for (iCont = 0; iCont < oDir.length; iCont++) {

    if (oDir.options[iCont].selected) {

      oEsq.options[oEsq.length] = oDir.options[iCont];
      iCont--;

    }

  }

}

</script>