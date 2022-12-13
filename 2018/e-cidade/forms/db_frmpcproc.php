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

$clrotulo = new rotulocampo;
$clrotulo->label("pc10_numero");
$clrotulo->label("pc10_data");
$clrotulo->label("pc10_resumo");
$clrotulo->label("pc80_resumo");
$clrotulo->label("pc80_codproc");
$clrotulo->label("pc80_tipoprocesso");
$clrotulo->label("descrdepto");

$val = false;

?>

<form name="form1" method="post" action="">
  <fieldset style="width:775px;">
    <legend>Dados do Processo de Compras</legend>
    <table border="0"  width="100%">

      <table border="0"  width="100%">
        <tr>
          <td align="left" nowrap title="<?=@$Tpc10_numero?>">
            <strong>Solicitação: </strong>
          </td>
          <td align="left">
            <?
            $desabilita = false;
            $arr_numero = array();
            $arr_index  = array();

            $where_liberado = "";
            $selecionalibera = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_liberado"));
            if($clpcparam->numrows>0){
              db_fieldsmemory($selecionalibera,0);
              if($pc30_liberado=='f'){
                $where_liberado = " and pc11_liberado='t' ";
              }
            }
            $sWhereSolicitaAnulada = " not exists (select 1 from solicitaanulada where pc67_solicita = pc10_numero) ";
            $sql_solicita = $clsolicitem->sql_record($clsolicitem->sql_query_pcmater(null,"distinct pc10_numero,pc10_data,pc10_resumo,descrdepto","pc10_numero desc","pc81_solicitem is null $where_liberado and pc10_correto='t' and {$sWhereSolicitaAnulada} and pc10_solicitacaotipo in(1,2) and extract(year from pc10_data) >= ".db_getsession("DB_anousu")));
            for ($i=0;$i<$clsolicitem->numrows;$i++) {

              db_fieldsmemory($sql_solicita,$i,true);
              $arr_numero[$pc10_numero] = $pc10_numero;
              $arr_index[$pc10_numero]  = $i;
              $arr_data = split("/",$pc10_data);

              if ($i == 0) {
                $pc10_data_dia = $arr_data[0];
                $pc10_data_mes = $arr_data[1];
                $pc10_data_ano = $arr_data[2];
              }
            }
            if($clsolicitem->numrows>0){
              db_fieldsmemory($sql_solicita,0,true);
            }else{
              $desabilita = true;
            }
            if (isset($cod) && $cod!="" && isset($arr_index[$cod])) {

              $pc10_numero=$cod;
              $val = true;
            }
            db_select('pc10_numero',$arr_numero,true,1,"onchange='js_mudasolicita();'");
            if($val==true){
              echo "<script>
                        var_obj = document.getElementById('pc10_numero').length;
                  for(i=0;i<var_obj;i++){
                    if(document.getElementById('pc10_numero').options[i].value==$cod){
                      document.getElementById('pc10_numero').options[i].selected = true;
                    }
                  }
                </script>";
              db_fieldsmemory($sql_solicita,$arr_index[$cod]);
              $arr_data = split("-",$pc10_data);

              $pc10_data_dia = $arr_data[2];
              $pc10_data_mes = $arr_data[1];
              $pc10_data_ano = $arr_data[0];
            }
            $result_pcproc = $clpcproc->sql_record('select last_value+1 as pc80_codproc from pcproc_pc80_codproc_seq');
            if($clpcproc->numrows>0){
              //db_fieldsmemory($result_pcproc,0);
            }else{
              //$pc80_codproc = 1;
            }
            ?>
          </td>
          <td align="left" nowrap title="<?=@$Tpc80_codproc?>">
            <strong>Processo de Compra: </strong>
          </td>
          <td align="left" nowrap>
            <?
            db_input('pc80_codproc',8,$Ipc80_codproc,true,'text',3);
            ?>
          </td>
        </tr>
        <tr>
          <td align="left" nowrap title="<?php echo $Tpc10_data; ?>">
            <label class="bold" for="pc10_data" id="lbl_pc10_data">Data da Solicitação:</label>
          </td>
          <td align="left" nowrap>
            <?
            db_inputdata('pc10_data',@$pc10_data_dia,@$pc10_data_mes,@$pc10_data_ano,true,'text',3);
            ?>
          </td>
          <td align="left" nowrap title="<?=@$Tdescrdepto?>">
            <strong>Departamento: </strong>
          </td>
          <td align="left" nowrap>
            <?
            db_input('descrdepto',40,$Idescrdepto,true,'text',3);
            ?>
          </td>
        </tr>
        <tr>
          <td align="left">
            <strong>Situação: </strong>
          </td>
          <td>
            <?php

            $aOpcoesSituacao = array(
              2 => 'Autorizado',
              1 => 'Análise'
            );
            db_select('pc80_situacao',$aOpcoesSituacao,true,'', 'style="width:100%"');
            ?>
          </td>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td align="left">
            <label class="bold" for="pc80_tipoprocesso" id="lbl_pc80_tipoprocesso"><?php echo $Spc80_tipoprocesso; ?>:</label>
          </td>
          <td>
            <?php

            $aTipos = array( 1 => 'Item',
                             2 => 'Lote' );

            db_select('pc80_tipoprocesso', $aTipos, true, '', 'style="width:100%"');
            ?>
          </td>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td align="left" nowrap title="<?=@$Tpc10_resumo?>" colspan="4">
            <fieldset>
              <legend>Resumo do Processo de Compras</legend>
              <?
              db_textarea('pc10_resumo', 5, 70, $Ipc10_resumo, true, 'text', 1, "", "", "", 735);
              ?>
            </fieldset>
          </td>
        </tr>
      </table>
    </table>
  </fieldset>

  <table border="0" align="center" width="800">
    <tr align="center">
      <td nowrap colspan="10">
        <fieldset>
          <legend><strong>Itens da Solicitação</strong></legend>
          <iframe name="iframe_solicitem" id="solicitem" marginwidth="0" marginheight="0"
                  frameborder="0" src="com1_gerasolicitem.php" width="100%"></iframe>
        </fieldset>
      </td>
    </tr>
  </table>

  <?php
  db_input('valores',50,0,true,'hidden',3);
  db_input('importa',50,0,true,'hidden',3);
  ?>

  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>

  <?php

  /**
   * Buscamos o parâmetro pc30_liberado
   * Caso este parâmetro esteja TRUE, o usuário pode cadastrar pendências para uma solicitação de compras, do contrários
   * o botão que permite o cadastro não irá aparecer.
   */
  $sSqlLiberadoPcParam = $clpcparam->sql_query_file(db_getsession('DB_instit'), "pc30_liberado");
  $rsPcParam           = $clpcparam->sql_record($sSqlLiberadoPcParam);
  $lDadoLiberado       = false;
  if ($clpcparam->numrows > 0) {
    $lDadoLiberado = db_utils::fieldsMemory($rsPcParam, 0)->pc30_liberado == "f" ? true : false;
  }

  if ($lDadoLiberado) {
    echo "<input type='button' name='btnPendenciaSolicitacao' id='btnPendenciaSolicitacao' value='Pendência' onclick='js_openWindowPendencia();'>";
  }


  $result_pcproc = $clpcproc->sql_record($clpcproc->sql_query_file(null,"pc80_codproc"));
  $enviadados = false;
  if($clpcproc->numrows>0){
    $enviadados = true;
    echo '<input name="juntaropcao" type="button" id="juntaropcao" value="Juntar" '.($db_botao==false?"disabled":"").' onclick="js_juntaropcao();">&nbsp;&nbsp;&nbsp;&nbsp;';
    echo "<script>
              function js_juntaropcao(){
    	    numele = iframe_solicitem.document.form1.length;
    	    cont = 0;
    	    imp  = 0;
    	    for(i=0;i<numele;i++){
    	      if(iframe_solicitem.document.form1.elements[i].type=='checkbox'){
    		  if(iframe_solicitem.document.form1.elements[i].checked==true){
    		      elemento = iframe_solicitem.document.form1.elements[i].name;
    		      arr_chk  = elemento.split('_');
    		      if (arr_chk.length == 3){
    		           cont++;
    		      } else {
    			   imp++;
    		      }
    		  }
    	      }
    	    }
    	    if (cont == 0 && imp == 0){
    	         alert('Usuário:\\n\\nSelecione um item para prosseguir.\\n\\nAdministrador:');
    	    } else {
     	         if(cont != numele || imp != numele){


                 if (cont > 0){
                   js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcproc','func_pcproc.php?funcao_js=parent.js_preenchepesquisa|pc80_codproc&lBuscarProcessoAutomatico=true','Pesquisa',true,'20');
                 }

    	           if (imp > 0){
    	             js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcproc','func_pcproc.php?funcao_js=parent.js_preenchepesquisa|pc80_codproc&imp=true&lBuscarProcessoAutomatico=true','Pesquisa',true,'20');
    	           }
    	         }
    	    }
    	  }
    	  function js_preenchepesquisa(chave){
    	    var opcao = document.createElement('input');
    	    opcao.setAttribute('type','hidden');
    	    opcao.setAttribute('name','juntar');
    	    opcao.setAttribute('value','true');
    	    document.form1.appendChild(opcao);
    	    document.form1.juntar.value=chave;
    	    db_iframe_pcproc.hide();
                document.form1.submit();
    	  }
            </script>
    	";
  }
  ?>
</form>
<script>

  function js_mudasolicita() {
    location.href = 'com1_pcproc001.php?cod='+document.form1.pc10_numero.value;

  }
  if (document.form1.pc10_numero.value!=""){
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_solicitem.location.href= 'com1_gerasolicitem.php?solicita='+document.form1.pc10_numero.value+'&pc10_numero='+document.form1.pc10_numero.value;
  }
  <?
    if($desabilita==true){
    echo "
      numele = parent.document.form1.length;
      cont = 0;
      for(i=0;i<numele;i++){
        if((window.CurrentWindow || parent.CurrentWindow).corpo.document.form1.elements[i].type=='submit' || (window.CurrentWindow || parent.CurrentWindow).corpo.document.form1.elements[i].type=='button'){
          (window.CurrentWindow || parent.CurrentWindow).corpo.document.form1.elements[i].disabled=true;
        }
      }
      ";
    }else{
    echo "
      numele = (window.CurrentWindow || parent.CurrentWindow).corpo.document.form1.length;
      cont = 0;
      for(i=0;i<numele;i++){
        if((window.CurrentWindow || parent.CurrentWindow).corpo.document.form1.elements[i].type=='submit' || (window.CurrentWindow || parent.CurrentWindow).corpo.document.form1.elements[i].type=='button'){
          (window.CurrentWindow || parent.CurrentWindow).corpo.document.form1.elements[i].disabled=false;
        }
      }
      ";
    }
   ?>

  document.getElementById('pc10_numero').style.width = '100%';
  document.getElementById('pc10_resumo').style.width = '100%';
  document.getElementById('pc10_data').style.width = '100%';

  /**
   * Função que abre a janela para cadastro de pendência para uma solicitação de compras.
   */
  function js_openWindowPendencia() {

    var iCodigoSolicitacao  = $F('pc10_numero');
    // a flag 'cadastroprocessodecompras' foi adiciona para indicar ao programa que essa é a origem da opreração
    var sUrlPendencia       = "com4_cadpendencias002.php?pc10_numero="+iCodigoSolicitacao+"&cadastroprocessodecompras=true";
    var sTituloJanelaIframe = "Cadastro de Pendência da Solicitação: "+iCodigoSolicitacao;
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cadpendencia', sUrlPendencia, sTituloJanelaIframe ,true);
  }
</script>