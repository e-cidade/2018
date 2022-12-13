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

//MODULO: compras
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clpcfornemov->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc60_dtlanc");
$clrotulo->label("nome");
$clrotulo->label("z01_nome");
if(isset($db_opcaoal)){
  $db_opcao=33;
  $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
  $db_botao=true;
  $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
  $db_opcao = 3;
  $db_botao=true;
}else{
  $db_opcao = 1;
  $db_botao=true;
  if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
    $pc62_dtlanc = "";
    $pc62_hist = "";
    $pc62_bloqueia = "";
    $pc62_id_usuario = "";
  }
}
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend class="bold">Cadastro de Movimento</legend>
    <table border="0" style="width: 100%;">
      <tr>
        <td nowrap >
        </td>
        <td>
          <?
          db_input('pc62_codmov',6,$Ipc62_codmov,true,'hidden',3,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tpc62_numcgm?>">
          <?
          db_ancora(@$Lpc62_numcgm,"js_pesquisapc62_numcgm(true);",3);
          ?>
        </td>
        <td>
          <?
          db_input('pc62_numcgm',8,$Ipc62_numcgm,true,'text',3," onchange='js_pesquisapc62_numcgm(false);'");
          db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tpc62_dtlanc?>">
          <?=@$Lpc62_dtlanc?>
        </td>
        <td>
          <?
          if(!isset($pc62_dtlanc_dia)){
            $pc62_dtlanc_dia = date("d",db_getsession("DB_datausu"));
            $pc62_dtlanc_mes = date("m",db_getsession("DB_datausu"));
            $pc62_dtlanc_ano = date("Y",db_getsession("DB_datausu"));
          }
          db_inputdata('pc62_dtlanc',@$pc62_dtlanc_dia,@$pc62_dtlanc_mes,@$pc62_dtlanc_ano,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2" nowrap title="<?=@$Tpc62_hist?>">
          <fieldset>
            <legend class="bold">Histórico:</legend>
            <?
            db_textarea('pc62_hist',2,60,$Ipc62_hist,true,'text',$db_opcao,"")
            ?>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td nowrap >
        </td>
        <td>
          <?
          $pc62_id_usuario = db_getsession("DB_id_usuario");
          db_input('pc62_id_usuario',5,$Ipc62_id_usuario,true,'hidden',$db_opcao," onchange='js_pesquisapc62_id_usuario(false);'")
          ?>
        </td>
      </tr>
    </table>
  </fieldset>

  <div class="center">
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
    <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    <!-- Extensão Cadastro de Fornecedor - Botao -->
  </div>
    <tr>
      <td valign="top"  align="center">
        <?
        $chavepri= array("pc62_codmov"=>@$pc62_codmov);
        $cliframe_alterar_excluir->chavepri=$chavepri;
        $cliframe_alterar_excluir->sql     = $clpcfornemov->sql_query_file(null,'*','pc62_codmov'," pc62_numcgm = $pc62_numcgm");
        $cliframe_alterar_excluir->campos  ="pc62_codmov,pc62_numcgm,pc62_dtlanc,pc62_hist,pc62_id_usuario";
        $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
        $cliframe_alterar_excluir->iframe_height ="160";
        $cliframe_alterar_excluir->iframe_width ="700";
        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
        ?>
      </td>
    </tr>
  </table>
</form>

<!-- Extensão Cadastro de Fornecedor - Programa -->


<script>

  $('pc62_hist').style.width = '100%';
  $('pc62_hist').style.height = '60px';

  function js_cancelar(){

    $('pc62_codmov').value = '';
    $('pc62_hist').value = '';
    $('pc62_codmov').value = '';
    var opcao = document.createElement("input");
    opcao.setAttribute("type","hidden");
    opcao.setAttribute("name","novo");
    opcao.setAttribute("value","true");
    document.form1.appendChild(opcao);
    document.form1.submit();
  }
  function js_pesquisapc62_numcgm(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo.iframe_pcfornemov','db_iframe_pcforne','func_pcforne.php?funcao_js=parent.js_mostrapcforne1|pc60_numcgm|pc60_dtlanc','Pesquisa',true,'0','1','775','390');
    }else{
      if(document.form1.pc62_numcgm.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_pcfornemov','db_iframe_pcforne','func_pcforne.php?pesquisa_chave='+document.form1.pc62_numcgm.value+'&funcao_js=parent.js_mostrapcforne','Pesquisa',false);
      }else{
        document.form1.pc60_dtlanc.value = '';
      }
    }
  }
  function js_mostrapcforne(chave,erro){
    document.form1.pc60_dtlanc.value = chave;
    if(erro==true){
      document.form1.pc62_numcgm.focus();
      document.form1.pc62_numcgm.value = '';
    }
  }
  function js_mostrapcforne1(chave1,chave2){
    document.form1.pc62_numcgm.value = chave1;
    document.form1.pc60_dtlanc.value = chave2;
    db_iframe_pcforne.hide();
  }
  function js_pesquisapc62_id_usuario(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo.iframe_pcfornemov','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,'0','1','775','390');
    }else{
      if(document.form1.pc62_id_usuario.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_pcfornemov','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.pc62_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
      }else{
        document.form1.nome.value = '';
      }
    }
  }
  function js_mostradb_usuarios(chave,erro){
    document.form1.nome.value = chave;
    if(erro==true){
      document.form1.pc62_id_usuario.focus();
      document.form1.pc62_id_usuario.value = '';
    }
  }
  function js_mostradb_usuarios1(chave1,chave2){
    document.form1.pc62_id_usuario.value = chave1;
    document.form1.nome.value = chave2;
    db_iframe_db_usuarios.hide();
  }

</script>