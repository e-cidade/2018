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

//MODULO: educação
$cledu_parametros->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed18_c_nome");
$clrotulo->label("ed233_apresentarnotaproporcional");
$ed233_i_escola = db_getsession("DB_coddepto");
$ed18_c_nome    = db_getsession("DB_nomedepto");
?>
<form name="form1" method="post" action="" class="container">
  <fieldset>
    <legend>Parâmetros</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Ted233_i_codigo?>">
          <?=@$Led233_i_codigo?>
        </td>
        <td>
         <?db_input('ed233_i_codigo',20,$Ied233_i_codigo,true,'text',3,"class='field-size2'")?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted233_i_escola?>">
          <?=$Led233_i_escola?>
        </td>
        <td>
          <?php
            db_input('ed233_i_escola',20,$Ied233_i_escola,true,'text',3,"class='field-size2' onchange='js_pesquisaed233_i_escola(false);'");
            db_input('ed18_c_nome',50,@$Ied18_c_nome,true,'text',3,"class='field-size7'")
          ?>
        </td>
      </tr>
      <tr style="display: none">
        <td nowrap title="<?=@$Ted233_c_decimais?>">
          <?=@$Led233_c_decimais?>
        </td>
        <td>
          <?
            $ed233_c_decimais = 'N';
            db_select('ed233_c_decimais',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted233_c_notabranca?>">
          <?=@$Led233_c_notabranca?>
        </td>
        <td>
          <?
            $x = array('N'=>'NÃO','S'=>'SIM');
            db_select('ed233_c_notabranca',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted233_f_medidaaluno?>">
          <?=@$Led233_f_medidaaluno?>
        </td>
        <td>
          <?
            $ed233_f_medidaaluno = isset($ed233_f_medidaaluno)&&$ed233_f_medidaaluno!=""?number_format($ed233_f_medidaaluno,2,".","."):"";
            db_input('ed233_f_medidaaluno',10,@$Ied233_f_medidaaluno,true,'text',$db_opcao," onchange='js_valida(this);' class='field-size1'")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted233_c_limitemov?>">
          <?=@$Led233_c_limitemov?>
        </td>
        <td>
          <? db_input('ed233_c_limitemov',5,@$Ied233_c_limitemov,true,'text',$db_opcao, "class='field-size1'")?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted233_c_database?>">
          <?=@$Led233_c_database?>
        </td>
        <td>
          <? db_input('ed233_c_database',5,@$Ied233_c_database,true,'text',$db_opcao, "class='field-size1'")?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted233_c_consistirmat?>">
          <?=@$Led233_c_consistirmat?>
        </td>
        <td>
          <?
            $x = array('S'=>'SIM','N'=>'NÃO');
            db_select('ed233_c_consistirmat',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo $Led233_reclassificaetapaanterior; ?>
        </td>
        <td>
          <?php
            $aReclassificao = array( 't' => 'SIM', 'f' => 'NÃO');
            db_select('ed233_reclassificaetapaanterior', $aReclassificao, true, $db_opcao );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted233_c_avalalternativa?>">
          <?=@$Led233_c_avalalternativa?>
        </td>
        <td>
         <?
           $x = array('S'=>'SIM','N'=>'NÃO');
           db_select('ed233_c_avalalternativa',$x,true,$db_opcao,"");
         ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted233_i_idadevotacao?>">
          <?=@$Led233_i_idadevotacao?>
        </td>
        <td>
          <?
            db_input('ed233_i_idadevotacao',5,@$Ied233_i_idadevotacao,true,'text',$db_opcao, "class='field-size1'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted233_i_habilitaordemalfabeticaturma?>">
          <?=@$Led233_i_habilitaordemalfabeticaturma?>
        </td>
        <td>
          <?
            $r = array('1' => 'SIM', '2' => 'NÃO');
            db_select('ed233_i_habilitaordemalfabeticaturma',$r,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted233_formalancamentoparecer?>">
           <?=@$Led233_formalancamentoparecer?>
        </td>
        <td>
          <?
            $aFormaLancParecer = getValoresPadroesCampo('ed233_formalancamentoparecer');
            db_select('ed233_formalancamentoparecer', $aFormaLancParecer, true, $db_opcao, "");
          ?>
       </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted233_bloqueioalteracaoavaliacao?>">
          <?=@$Led233_bloqueioalteracaoavaliacao?>
        </td>
        <td>
          <?
            $x = array('t'=>'SIM','f'=>'NÃO');
            db_select('ed233_bloqueioalteracaoavaliacao',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td colspan='2'>

          <fieldset class="separator">
            <legend>Diário de Classe</legend>
            <table class="subtable">
              <tr>
                <td style="width: 224px" rel="ignore-css" nowrap title="<?=@$Ted233_deslocamentocursor?>">
                 <?=@$Led233_deslocamentocursor?>
                </td>
                <td rel="ignore-css">
                  <?
                    $aDeslocamentoCursor = array('1' => 'NOTA > FALTA', '2' => 'NOTA > NOTA');
                    db_select('ed233_deslocamentocursor',$aDeslocamentoCursor,true,$db_opcao," class='field-size-max' rel='ignore-css'");
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                    <label for="ed233_apresentarnotaproporcional"> <?=$Led233_apresentarnotaproporcional?> </label>
                </td>
                <td>
                  <?php
                    db_select('ed233_apresentarnotaproporcional', array('t'=>'SIM','f'=>'NÃO'), true, $db_opcao);
                  ?>
                </td>
              </tr>

            </table>
          </fieldset>

        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
         type="submit" id="db_opcao" onclick="return js_validarSubmit()";
         value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
                <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_valida(campo) {

  if (campo.value == 0) {

    alert("Quantidade inválida!");
    campo.value = "";
    campo.focus();

  }
}

var oMaskedDataBase = new MaskedInput("#ed233_c_database",
    '99/99' ,
    {placeholder:"_"});

var oMaskedDataLimite = new MaskedInput("#ed233_c_limitemov",
    '99/99' ,
    {placeholder:"_"});


function js_validarSubmit() {

  var dtDataBase   = $F('ed233_c_database');
  var dtDataLimite = $F('ed233_c_limitemov');
  if (dtDataBase == '' || dtDataBase == '__/__') {

    alert('Informe a data base');
    return false;
  }
  if (dtDataLimite == '' || dtDataLimite == '__/__') {

    alert('Informe a data limite para movimentação!');
    return false;
  }
  return true;
}
</script>