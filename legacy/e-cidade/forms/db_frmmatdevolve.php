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

//MODULO: material
$clatendrequi->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("descrdepto");
$clrotulo->label("m45_obs");
?>
<form name="form1" method="post" action="" onsubmit="js_buscavalores();" >
<table>
  <tr>
    <td>
      <fieldset>
        <legend>
           <b>Dados da Devolução</b>
        </legend>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Tm42_codigo?>">
              <?//=@$Lm40_codigo?>
              <b>Código do Atendimento: </b>
            </td>
            <td>
              <?
              db_input('m42_codigo',10,$Im42_codigo,true,'text',3,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tm42_data?>">
              <?=@$Lm42_data?>
            </td>
            <td>
              <?
              db_inputdata('m42_data',@$m42_data_dia,@$m42_data_mes,@$m42_data_ano,true,'text',3,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tm42_hora?>">
              <?=@$Lm42_hora?>
            </td>
            <td>
              <?
              db_input('m42_hora',5,$Im42_hora,true,'text',3,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tm42_login?>">
              <?=@$Lm42_login?>
            </td>
            <td>
              <?
              db_input('m42_login',10,$Im42_login,true,'text',3," ");
              db_input('nome',40,$Inome,true,'text',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tm42_depto?>">
            <?=@$Lm42_depto?>
            </td>
            <td>
              <?
              db_input('m42_depto',5,$Im42_depto,true,'text',3,"");
              db_input('descrdepto',40,$Idescrdepto,true,'text',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tm45_obs?>">
              <?=@$Lm45_obs?>
            </td>
            <td>
              <?
              db_textarea('m45_obs',0,70,$Im45_obs,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
<table>
  <tr>
    <td>
      <fieldset>
        <legend>
          <b>Materiais</b>
        </legend>
        <table>
          <tr>
            <td>
              <iframe name="itens" id="itens" src="mat1_matdevolveitens001.php?m42_codigo=<?=isset($m42_codigo)!=""?$m42_codigo:''?>"
                      width="720"
                      height="220"
                      marginwidth="0" marginheight="0" frameborder="0">
               </iframe>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
<input name="incluir" type="submit"  value="Confirma">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?
db_input('valores',100,'',true,'hidden',3);
?>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_atendrequi','func_atendrequi.php?devolucao=true&funcao_js=parent.js_preenchepesquisa|m42_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_atendrequi.hide();
  <?
//  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
//  }
  ?>
}
function js_buscavalores(){
  obj= itens.document.form1;
  valor="";
  for (i=0;i<obj.elements.length;i++){
    if (obj.elements[i].name.substr(0,6)=="quant_"){
      var objvalor=new Number(obj.elements[i].value);
      if (objvalor!=0){
        valor+=obj.elements[i].name+"_"+obj.elements[i].value;
      }
    }
  }
  document.form1.valores.value = valor;
}
</script>