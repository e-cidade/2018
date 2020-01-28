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
?>
<script>
function js_verificaid(valor) {

  num = (document.form1.selid.options.length) - 1;
  for (i=1 ; i <= num; i++) {

    selid = document.form1.selid.options[i].value;

    if (valor == selid) {

      alert("Construção já cadastrada!");
      document.form1.j52_idcons.value="";
      document.form1.j52_idcons.focus();
      return false;
      break;
    }
  }
  if( document.form1.caracteristica.value=="X" || document.form1.caracteristica.value==""){
    alert("Informe as caracteristicas!");
    return false;
  }

  if ( empty(document.form1.j52_area.value) || document.form1.j52_area.value <= 0 ) {

    alert("A Área Construída não foi informada!");
    return false;
  }

}
function js_testacar2(){
  if( document.form1.caracteristica.value=="X" || document.form1.caracteristica.value==""){
    alert("Informe as caracteristicas!");
    return false;
  }
  if ( empty(document.form1.j52_area.value) || document.form1.j52_area.value <= 0 ) {

    alert("A Área Construída não foi informada!");
    return false;
  }
}
<?if(isset($j52_matric)){?>
function js_trocaid(valor){
  id_setor=document.form1.id_setor.value;
  id_quadra=document.form1.id_quadra.value;
  location.href="cad1_constrescralt.php?id_setor="+id_setor+"&id_quadra="+id_quadra+"&j52_matric=<?=$j52_matric?>&j52_idcons="+valor+"&z01_nome="+document.form1.z01_nome.value;

}
<?}?>
</script>

<fieldset>

  <legend><b>Construções já Cadastradas</b></legend>

  <table border="0" width="790">

    <tr>
      <td>
        <input type="hidden" name="j52_dtlan_dia" value="<?=$j52_dtlan_dia?>">
        <input type="hidden" name="j52_dtlan_mes" value="<?=$j52_dtlan_mes?>">
        <input type="hidden" name="j52_dtlan_ano" value="<?=$j52_dtlan_ano?>">
        <input type="hidden" name="id_setor"      value="<?=@$id_setor?>">
        <input type="hidden" name="id_quadra"     value="<?=@$id_quadra?>">
        <?=$Lj52_matric?>
      </td>

      <td>
        <?
        db_input('j52_matric',10,0,true,'text',3,"onchange='js_matri(false)'");
        db_input('z01_nome',75,0,true,'text',3,"");
        ?>
      </td>
    </tr>

    <tr>
      <td>
        <?=$Lj52_idcons?>
      </td>
      <td>
        <?
        db_input('j52_idcons',10,$Ij52_idcons,true,'text',$db_opcaoid,"");
        ?>
      </td>
      <td rowspan="8" valign="top">
        <table>
          <tr>
            <td align="center">
              <?
                  if(isset($j52_matric)){

                    if(!isset($incluir)){
                      $result = $clconstrescr->sql_record($clconstrescr->sql_query_file($j52_matric,"","j52_idcons","",""));
                    }

                    $num=$clconstrescr->numrows?$num=$clconstrescr->numrows:$num=0;

                    if($num!=0){

                      echo "<select name='selid' onchange='js_trocaid(this.value)'  size='".($num>7?8:($num+1))."'>";
                      echo "<option value='nova' ".(!isset($j52_idcons)?"selected":"").">Nova</option>";
                      $idcons=$j52_idcons;
                      $testasel=true;
                      for($i=0;$i<$num;$i++){

                        db_fieldsmemory($result,$i);
                        if($j52_idcons!=$idcons){
                          echo "<option  value='".$j52_idcons."' ".($j52_idcons==$idcons?"selected":"").">$j52_idcons</option>";
                        }
                    }
                  }
              }
              ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
        <tr>
          <td>
            <?=$Lj52_ano?>
          </td>
          <td>
            <?
            db_input('j52_ano',10,$Ij52_ano,true,'text',1,"");
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <?=$Lj52_area?>
          </td>
          <td>
            <?
            db_input('j52_area',10,4,true,'text',1,"");
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <?=$Lj52_areap?>
          </td>
          <td>
            <?
            db_input('j52_areap',10,4,true,'text',1,"");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj52_codigo?>">
            <?
            db_ancora(@$Lj52_codigo,"js_pesquisaj52_codigo(true);",$db_opcao);
            ?>
          </td>
          <td>
            <?
            db_input('j52_codigo',10,$Ij52_codigo,true,'text',$db_opcao," onchange='js_pesquisaj52_codigo(false);'");
            db_input('j14_nome',75,$Ij14_nome,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <?=$Lj52_numero?>
          </td>
          <td>
            <?
            db_input('j52_numero',10,$Ij52_numero,true,'text',1,"");
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <?=$Lj52_compl?>
          </td>
          <td>
            <?
            db_input('j52_compl',88,$Ij52_compl,true,'text',1,"");
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <b><? db_ancora("Características","js_mostracaracteristica();",1); ?></b>
          </td>
          <td>
            <?
            db_input('caracteristica',15,1,true,'hidden',1,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj52_dtdemo?>">
            <?=@$Lj52_dtdemo?>
          </td>
          <td>
          <?
            db_inputdata('j52_dtdemo',@$j52_dtdemo_dia,@$j52_dtdemo_mes,@$j52_dtdemo_ano,true,'text',$db_opcao,"")
          ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj52_idaument?>">
            <?=@$Lj52_idaument?>
          </td>
          <td>
          <?
            db_input('j52_idaument',10,$Ij52_idaument,true,'text',$db_opcao,"")
          ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="left">
          </td>
        </tr>

      </table>
    </fieldset>
    <br />

    <input name="<?=($db_botao==1?"incluir":"alterar")?>" type="submit" value="<?=($db_botao==1?"Incluir":"Alterar")?>" <?=($testasel==true?"onclick=\"return js_verificaid(document.form1.j52_idcons.value)\"":"onclick=\"return js_testacar2()\"")?>>

  <script>
  function js_matri(mostra){
    var matri=document.form1.j52_matric.value;
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo.iframe_constrescr','db_iframe','func_iptubase.php?funcao_js=parent.js_mostra|0|1','Pesquisa',true,0);
    }else{
      js_OpenJanelaIframe('top.corpo.iframe_constrescr','db_iframe','func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostra1','Pesquisa',false,0);
    }
  }
function js_mostra(chave1,chave2){
  document.form1.j52_matric.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_mostra1(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.j52_matric.focus();
    document.form1.j52_matric.value = '';
  }
}

function js_mostracaracteristica(){
  caracteristica=document.form1.caracteristica.value;
  if(caracteristica!=""){
    js_OpenJanelaIframe('top.corpo.iframe_constrescr','db_iframe','cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&caracteristica='+caracteristica+'&tipogrupo=C','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_constrescr','db_iframe','cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&tipogrupo=C&codigo='+document.form1.j52_idcons.value,'Pesquisa',true,0);
  }
}
function js_pesquisaj52_codigo(mostra){
  idsetor=document.form1.id_setor.value;
  idquadra=document.form1.id_quadra.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_constrescr','db_iframe','func_ruasconstr.php?idsetor='+idsetor+'&idquadra='+idquadra+'&funcao_js=parent.js_mostraruas1|0|1','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_constrescr','db_iframe','func_ruasconstr.php?idsetor='+idsetor+'&idquadra='+idquadra+'&pesquisa_chave='+document.form1.j52_codigo.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false,0);
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.j52_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro==true){
    document.form1.j52_codigo.focus();
    document.form1.j52_codigo.value = '';
  }
}

/**
 * Mostra texto de ajuda no campo id da construção
 */
var oDbHint = new DBHint('oDbHint');
    oDbHint.setText('Caso o campo não seja preenchido, <BR>O código será gerado automaticamente');
    oDbHint.setShowEvents( new Array("onFocus", "onMouseOver") );
    oDbHint.setHideEvents( new Array("onBlur", "onMouseOut") );
    oDbHint.make($('j52_idcons'));
</script>