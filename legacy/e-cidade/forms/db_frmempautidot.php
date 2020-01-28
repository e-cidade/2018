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

//MODULO: empenho
$clempautidot->rotulo->label();
$clrotulo = new rotulocampo;
$clorcsuplemval->rotulo->label();
$clorcdotacao->rotulo->label();
$clrotulo->label("o58_orgao");
$clrotulo->label("o46_codlei");
$clrotulo->label("o47_anousu");
$clrotulo->label("c53_descr");
$clrotulo->label("e54_valor");
$clrotulo->label("o56_elemento");

$result = $clempautitem->sql_record($clempautitem->sql_query_file($e56_autori));
if($clempautitem->numrows==0){
  $db_opcao_item = 33;
  $db_botao = false;
}else{
  $db_opcao_item = 1;
}
?>
  <form name="form1" method="post" action="">
    <center>
      <table border="0">
        <tr>
          <td nowrap title="<?=@$Te56_autori?>">
            <?=@$Le56_autori?>
          </td>
          <td colspan='2'>
            <?
            db_input('e56_autori',8,$Ie56_autori,true,'text',3);
            $result=$clorcreservaaut->sql_record( $clorcreservaaut->sql_query_file(null,"o83_codres","","o83_autori=$e56_autori"));
            if($clorcreservaaut->numrows>0){
              echo "<b>Já existe reserva para esta autorização</b>";
            }else{
              echo "<b>Não existe reserva para esta autorização</b>";
            }
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Te56_anousu?>">
            <?=$Le56_anousu?>
          </td>
          <td>
            <?
            if(empty($e56_anousu)){
              $e56_anousu = db_getsession('DB_anousu');
            }
            db_input('e56_anousu',4,$Ie56_anousu,true,'text',3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To47_coddot?>"> <? db_ancora(@$Lo47_coddot,"js_pesquisao47_coddot(true);",$db_opcao_item); ?> </td>
          <td><? db_input('o47_coddot',8,$Io47_coddot,true,'text',3); ?> </td>
          <td><input type="button" name="dot"  value="Processar" onclick="js_dot();"<?=($db_botao==false?"disabled":"")?> > </td>
          <td> &nbsp;  </td>
          <td> &nbsp;  </td>
          <td> &nbsp;  </td>
        </tr>

        <?    /* busca dados da dotação  */
        if((isset($o47_coddot) && !$o47_coddot== "") && empty($confirmar) && empty($cancelar)){
          $instit = db_getsession('DB_instit');
          $clorcdotacao->sql_record($clorcdotacao->sql_query_file("","","*","","o58_coddot=$o47_coddot and o58_instit=$instit"));
          if($clorcdotacao->numrows >0){
            $result= db_dotacaosaldo(8,2,2,"true","o58_coddot=$o47_coddot" ,db_getsession("DB_anousu")) ;

            db_fieldsmemory($result,0);
            $atual=number_format(floatval($atual),2,",",".");
            $reservado=number_format(floatval($reservado),2,",",".");
            $atudo=number_format(floatval($atual_menos_reservado),2,",",".");
          }else{
            $nops=" Dotação $o47_coddot  não encontrada ";
          }

        }
        ?>
        <tr>
          <td nowrap title="<?=@$To58_orgao ?>"><?=@$Lo58_orgao ?> </td>
          <td><? db_input('o58_orgao',8,"$Io58_orgao",true,'text',3,"");  ?> </td>
          <td colspan=3><? db_input('o40_descr',50,"",true,'text',3,"");  ?> </td>
          <td> </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To58_unidade ?>"><?=@$Lo58_unidade ?> </td>
          <td><? db_input('o58_unidade',8,"",true,'text',3,"");  ?> </td>
          <td colspan=3 ><? db_input('o41_descr',50,"",true,'text',3,"");  ?>  </td>
          <td>  </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To58_funcao ?>"><?=@$Lo58_funcao ?> </td>
          <td> <? db_input('o58_funcao',8,"",true,'text',3,"");  ?> </td>
          <td> <? db_input('o52_descr',50,"",true,'text',3,"");  ?>  </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To58_subfuncao ?>" ><?=@$Lo58_subfuncao ?> </td>
          <td> <? db_input('o58_subfuncao',8,"",true,'text',3,"");  ?>  </td>
          <td><? db_input('o53_descr',50,"",true,'text',3,"");  ?></td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To58_programa ?>"    ><?=@$Lo58_programa ?> </td>
          <td><? db_input('o58_programa',8,"",true,'text',3,"");  ?> </td>
          <td><? db_input('o54_descr',50,"",true,'text',3,"");  ?>       </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To58_projativ ?>"><?=@$Lo58_projativ ?> </td>
          <td><? db_input('o58_projativ',8,"",true,'text',3,"");  ?> </td>
          <td><? db_input('o55_descr',50,"",true,'text',3,"");  ?>    </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To56_elemento ?>" ><?=@$Lo56_elemento ?> </td>
          <td> <? db_input('o58_elemento',8,"",true,'text',3,"");  ?>  </td>
          <td> <? db_input('o56_descr',50,"",true,'text',3,"");  ?>       </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To58_codigo ?>" ><?=@$Lo58_codigo ?> </td>
          <td> <? db_input('o58_codigo',8,"",true,'text',3,"");  ?> </td>
          <td> <? db_input('o15_descr',50,"",true,'text',3,"");  ?> </td>
        </tr>
        <tr>
          <?
          if (isset($o47_coddot)) {

            $rsDotacao = $clorcdotacao->sql_record($clorcdotacao->sql_query(db_getsession("DB_anousu"), $o47_coddot,"o15_tipo"));
            if ($clorcdotacao->numrows > 0 ) {

              $oDotacao = db_utils::fieldsMemory($rsDotacao, 0);

              if ($oDotacao->o15_tipo == 1) {
                /*
                 * Buscamos as contrapartidas da dotacao
                 */
                $oDaoDotacaocontr = db_utils::getDao("orcdotacaocontr");
                $oDaoTipoRec      = db_utils::getDao("orctiporec");
                echo "<td><b>Contrapartida:</b></td><td colspan=5>";
                /*
                 * Procuramos contrapartidas cadastradas que estão ativas para a dotacao, caso nao encontramos nenhuma,
                 * trazemos todos os recursos cadastrados.
                 */
                $rsContrapartidas = $oDaoDotacaocontr->sql_record($oDaoDotacaocontr->sql_query_convenios (
                  $o47_coddot, db_getsession("DB_anousu"),
                  date("Y-m-d",db_getsession("DB_datausu")),
                  null,"o15_codigo,o15_descr")
                );

                $iNumRows         = $oDaoDotacaocontr->numrows;
                if ($oDaoDotacaocontr->numrows == 0) {

                  $rsContrapartidas = $oDaoTipoRec->sql_record($oDaoTipoRec->sql_query_convenios(
                    date("Y-m-d",db_getsession("DB_datausu")),
                    null,
                    "o15_codigo,o15_descr")
                  );
                  $iNumRows         = $oDaoTipoRec->numrows;

                }
                db_selectrecord("e56_orctiporec",$rsContrapartidas,true,$db_opcao,"", "", "","0-Selecione");
              }
            }
            echo "</td>";
          }
          ?>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan='2'>
            <table>
              <tr>
                <td>Saldo da dotação:</td>
                <td><? db_input('atual',13,"",true,'text',3,""); ?></td>
              </tr>
              <td>Valor reservado:</td>
              <td>  <? db_input('reservado',13,"",true,'text',3,""); ?></td>
              </tr>
              <tr>
                <td>Valor disponível: </td>
                <td><? db_input('atudo',13,"",true,'text',3,"");?></td>
              </tr>
              <tr>
                <td><?=$RLe54_valor?></td>
                <?
                $result = $clempautitem->sql_record($clempautitem->sql_query_file($e56_autori,null,"sum(e55_vltot) as e54_valor"));
                db_fieldsmemory($result,0);
                if(isset($atual_menos_reservado)&&isset($e54_valor)){
                  $tot= number_format((floatval($atual_menos_reservado) - floatval($e54_valor)),2,",",".");
                }
                $e54_valor=number_format(floatval($e54_valor),2,",",".");

                ?>
                <td><? db_input('e54_valor',13,"",true,'text',3,""); ?></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </center>
    <input name="confirmar" type="submit" id="db_opcao" value="<?=($db_botao_c==false?"Incluir":"Atualizar")?>" onclick="return js_verifica();" <?=($db_botao==false?"disabled":"")?> >
    <input name="cancelar" type="submit" id="db_opcao" value="Cancelar" <?=($db_botao_c==false?"disabled":"")?>  >
    <?
    $permissao_lancar = db_permissaomenu(db_getsession("DB_anousu"),398,3489);
    if($permissao_lancar == "true"){
      ?>
      <input name="lancemp" type="button" id="lancemp" value="Lançar Empenho" onclick="(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_empautoriza.js_lanc_empenho();"  <?=($db_botao==false||$db_botao_c==false?"disabled":"")?>>
    <?
    }
    ?>
    <input name="relatorio" type="button" id="db_opcao" value="Relatório de autorização" onclick="(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_prazos.js_relatorio();"  <?=($db_botao==false||$db_botao_c==false?"disabled":"")?>>
  </form>
<?
if(isset($nops)){
  db_msgbox($nops);
}

if(isset($tot) && $tot<0 && empty($cancelar) && isset($pesquisa_dot)){
  echo "
  <script>
    document.form1.confirmar.disabled = true;
  </script>
  ";
  db_msgbox('Dotação sem saldo disponível!');
}
?>
  <script>
    function js_calc(e54_valor){
      <?
        if(isset($atual_menos_reservado)){
          echo "atum = $atual_menos_reservado;\n";
          echo "
           tot=new Number(atum - e54_valor);\n
           document.form1.atudo.value= tot;\n
           conv=document.form1.atudo.value;\n
           t=conv.replace(\".\",\",\");\n
           document.form1.atudo.value= t;\n";
        }
      ?>

      document.form1.e54_valor.value=e54_valor;
    }
    function js_dot(){


      var opcao = document.createElement("input");
      opcao.setAttribute("type","hidden");
      opcao.setAttribute("name","pesquisa_dot");
      opcao.setAttribute("value","true");
      document.form1.appendChild(opcao);
      document.form1.submit();
    }
    function js_verifica(){
      if(document.form1.o47_coddot.value==''){
        alert('Código da dotação inválida!');
        return false;
      }
      tot=document.form1.atudo.value;

      while(tot.search(/\./)!='-1'){
        tot=tot.replace(/\./,'');
      }
      toti=tot.replace(",",".");
      tot=new Number(toti);

      if(isNaN(tot) || tot<0){
        alert('Dotação sem saldo disponível!');
        document.form1.confirmar.disabled = true;
        return false;
      }else{
        document.form1.confirmar.disabled = false;
      }

      if(document.form1.atudo.value==''){
        alert('Primeiro clique em pesquisar para calcular os valores!');
        return false;
      }
    }
    function js_pesquisao47_coddot(mostra){
      elemento=(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_empautitem.document.form1.elemento01.value;
      query='';
      if(elemento!=''){
        query="elemento="+elemento+"&";
      }

      if(mostra==true){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_empautidot','db_iframe_orcdotacao','func_permorcdotacao.php?'+query+'funcao_js=parent.js_mostraorcdotacao1|o58_coddot','Pesquisa',true,0);
      }else{
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_empautidot','db_iframe_orcdotacao','func_permorcdotacao.php?'+query+'pesquisa_chave='+document.form1.o47_coddot.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
      }
    }
    function js_mostraorcdotacao(chave,erro){
      if(erro==true){
        document.form1.o47_coddot.focus();
        document.form1.o47_coddot.value = '';
      }
    }

    function js_mostraorcdotacao1(chave1){
      document.form1.o47_coddot.value = chave1;
      js_dot();


      db_iframe_orcdotacao.hide();
    }
    function js_pesquisa(){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empautidot','func_empautidot.php?funcao_js=parent.js_preenchepesquisa|e56_autori','Pesquisa',true);
    }
    function js_preenchepesquisa(chave){
      db_iframe_empautidot.hide();
      <?
      if($db_opcao!=1){
        echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
      }
      ?>
    }
  </script>
<?php

if (isset($e56_autori) && $e56_autori != "") {

  $oDaoEmpAutItem = new cl_empautitem();

  $sSql = $oDaoEmpAutItem->sql_query_file(null, null, "e55_codele", null, "e55_autori = {$e56_autori}");

  $rsOrcDotacao = $oDaoEmpAutItem->sql_record($sSql);
  if ($oDaoEmpAutItem->numrows > 0) {

    $iElemento = db_utils::fieldsMemory($rsOrcDotacao, 0)->e55_codele;
    echo "<script> (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_empautoriza.completaElemento(".$iElemento.");</script>";

  }


}
?>