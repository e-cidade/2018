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

//MODULO: protocolo
$clandpadrao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p51_descr");
$clrotulo->label("descrdepto");
$clrotulo->label("nomeinstabrev");
include(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$grupo = empty($grupo) ? "" : $grupo;

if(isset($opcao) && $opcao == "alterar"){
  echo "<script>location.href='pro1_andpadrao002.php?chavepesquisa=$p53_codigo&chavepesquisa1=$p53_ordem&grupo={$grupo}'</script>";}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>location.href='pro1_andpadrao003.php?chavepesquisa=$p53_codigo&chavepesquisa1=$p53_ordem&grupo={$grupo}'</script>";
}
?>
  <form name="form1" method="post" action="">
    <center>
      <fieldset style="width:748px; margin-top:10px;">
        <legend><strong>Andamento padrão</strong></legend>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Tp53_codigo?>">
              <?
              if (!isset($aba)) {
                db_ancora(@$Lp53_codigo,"js_pesquisap53_codigo(true);",$db_opcao);
              } else {
                echo "<b>Código do tipo:</b>";
              }
              ?>
            </td>
            <td>
              <?
              db_input('p53_codigo',10,$Ip53_codigo,true,'text',$db_opcao," onchange='js_pesquisap53_codigo(false);'");
              db_input('p53_codigo',40,$Ip53_codigo,true,'hidden',$db_opcao,"",'p53_codigo_old');
              ?>
              <?
              db_input('p51_descr',60,$Ip51_descr,true,'text',3,'');
              if(!isset($p53_codigo)){
                $db_opcao = 3;
                $db_botao = false;
              }
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tp53_coddepto?>">
              <?
              db_ancora(@$Lp53_coddepto,"js_pesquisap53_coddepto(true);",$db_opcao);
              ?>
            </td>
            <td>
              <?
              db_input('p53_coddepto',10,$Ip53_coddepto,true,'text',$db_opcao," onchange='js_pesquisap53_coddepto(false);'");
              db_input('p53_coddepto',40,$Ip53_coddepto,true,'hidden',$db_opcao,"",'p53_coddepto_old');
              if($db_opcao == 2){
                echo "
        <script>
	  document.form1.p53_coddepto_old.value = '$p53_coddepto';
	  document.form1.p53_codigo_old.value = '$p53_codigo';
	</script>";
              }
              ?>
              <?
              db_input('descrdepto',60,$Idescrdepto,true,'text',3,'')
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tp53_dias?>">
              <?=@$Lp53_dias?>
            </td>
            <td>
              <?
              db_input('p53_dias',10,$Ip53_dias,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tp53_ordem?>">
              <?=@$Lp53_ordem?>
            </td>
            <td>
              <?
              if(isset($p53_codigo) && $p53_codigo != "" && $db_opcao == 1){
                $result = $clandpadrao->sql_record($clandpadrao->sql_query($p53_codigo,"","max(p53_ordem) + 1 as p53_ordem"));
                if($clandpadrao->numrows > 0){
                  db_fieldsmemory($result,0);
                  $p53_ordem = ($p53_ordem == ""?1:$p53_ordem);
                }
              }
              db_input('p53_ordem',10,$Ip53_ordem,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <table>
        <tr>
          <td align="center" colspan="2">
            <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
          </td>
        </tr>
        <tr>
          <td align="top" colspan="2">
            <?
            $chavepri = array("p53_codigo"=>@$p53_codigo,"p53_ordem"=>@$p53_ordem);
            $cliframe_alterar_excluir->chavepri=$chavepri;
            $cliframe_alterar_excluir->campos="p53_codigo,nomeinst,descrdepto,p53_dias,p53_ordem";
            $cliframe_alterar_excluir->sql=$clandpadrao->sql_query("","","*","p53_ordem"," p53_codigo = ".@$p53_codigo."");
            $cliframe_alterar_excluir->legenda="Andamento Padrão";
            $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum andamento Cadastrado!</font>";
            $cliframe_alterar_excluir->textocabec ="darkblue";
            $cliframe_alterar_excluir->textocorpo ="black";
            $cliframe_alterar_excluir->fundocabec ="#aacccc";
            $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
            $cliframe_alterar_excluir->iframe_height ="170";
            $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
            ?>
          </td>
        </tr>
      </table>
    </center>
    <input type='hidden' name='grupo' value='<?=$grupo?>'>
    <?php
    if (isset($aba)) {
      echo "<input type='hidden' name='aba' value='true'>";
    }
    ?>
  </form>
  <script>
    function js_pesquisap53_codigo(mostra){
      if(mostra==true){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pctipoproc','func_tipoproc.php?grupo='+document.form1.grupo.value+'&funcao_js=parent.js_mostratipoproc1|0|1','Pesquisa',true);
      }else{
//    db_iframe.jan.location.href = 'func_tipoproc.php?pesquisa_chave='+document.form1.p53_codigo.value+'&funcao_js=parent.js_mostratipoproc';
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pctipoproc','func_tipoproc.php?grupo='+document.form1.grupo.value+'&pesquisa_chave='+document.form1.p53_codigo.value+'&funcao_js=parent.js_mostratipoproc','Pesquisa',false);
      }
    }
    function js_mostratipoproc(chave,erro){
      document.form1.p51_descr.value = chave;
      if(erro==true){
        document.form1.p53_codigo.focus();
        document.form1.p53_codigo.value = '';
      }else{
        location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?p53_codigo="+document.form1.p53_codigo.value+"&p51_descr="+chave;
      }
    }
    function js_mostratipoproc1(chave1,chave2){
      document.form1.p53_codigo.value = chave1;
      document.form1.p51_descr.value = chave2;
      location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?p53_codigo="+chave1+"&p51_descr="+chave2;
      db_iframe.hide();
    }
    function js_pesquisap53_coddepto(mostra){
      if(mostra==true){
        js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart_transferencias.php?todasinstit=1&funcao_js=parent.js_mostradb_depart1|0|1','Pesquisa',true);
//    db_iframe.jan.location.href = 'func_db_depart.php?funcao_js=parent.js_mostradb_depart1|0|1';
//    db_iframe.mostraMsg();
//    db_iframe.show();
//    db_iframe.focus();
      }else{
//  db_iframe.jan.location.href = 'func_db_depart.php?pesquisa_chave='+document.form1.p53_coddepto.value+'&funcao_js=parent.js_mostradb_depart';
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_depart','func_db_depart_transferencias.php?pesquisa_chave='+document.form1.p53_coddepto.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
      }
    }
    function js_mostradb_depart(chave,erro){
      document.form1.descrdepto.value = chave;
      if(erro==true){
        document.form1.p53_coddepto.focus();
        document.form1.p53_coddepto.value = '';
      }
    }
    function js_mostradb_depart1(chave1,chave2){
      document.form1.p53_coddepto.value = chave1;
      document.form1.descrdepto.value = chave2;
      db_iframe_db_depart.hide();
    }
    function js_pesquisa(){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_andpadrao','func_andpadrao.php?funcao_js=parent.js_preenchepesquisa|0|1','Pesquisa',true);
//  db_iframe.jan.location.href = 'func_andpadrao.php?funcao_js=parent.js_preenchepesquisa|0|1';
//  db_iframe.mostraMsg();
//  db_iframe.show();
//  db_iframe.focus();
    }
    function js_preenchepesquisa(chave,chave1){
      db_iframe.hide();
      location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave+"&chavepesquisa1="+chave1;
    }
  </script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>