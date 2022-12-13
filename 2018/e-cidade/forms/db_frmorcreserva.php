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

//MODULO: orcamento
$clorcreserva->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o58_orgao");
$clrotulo->label("DBtxtmes");
$clrotulo->label("DBtxtmesacumulado");
$clrotulo->label("DBtxtperiodoini");
$clrotulo->label("DBtxtperiodofim");

$anousu=db_getsession("DB_anousu");
$DBtxtmes = db_hora(db_getsession("DB_datausu"),"m");

$DBtxtperiodoini = $anousu."-".$DBtxtmes."-01";
if ($DBtxtmes+1>12) {
  $DBtxtperiodofim = "(".($anousu+1)."-01-01)-1";
} else {
  $DBtxtperiodofim = "(".$anousu."-".($DBtxtmes+1)."-01)-1";
}

if (!isset($op)){
  $op=1;  // teste
}
?>
<form name="form1" method="post" action="">
  <div class="container">
    <fieldset style="width: 700px">
      <legend class="bold">Reserva de Saldo</legend>
      <table border="0"> <!---  3 colunas --->

        <tr>
          <td nowrap title="<?=@$To80_codres?>"> <?=@$Lo80_codres?> </td>
          <td> <? db_input('o80_codres',8,$Io80_codres,true,'text',3,"") ?> </td>
          <td colspan="2" nowrap title="<?=@$To80_anousu?>">
            <? db_ancora(@$Lo80_anousu,"js_pesquisao80_anousu(true);",3 ); ?>
            <? $o80_anousu = db_getsession('DB_anousu'); db_input('o80_anousu',4,$Io80_anousu,true,'text',3,"")?>
          </td>
        </tr>

        <!--- dados da dotação --->
        <tr>
          <td nowrap title="<?=@$To80_coddot?>"> <? db_ancora(@$Lo80_coddot,"js_pesquisao80_coddot(true);",$op); ?> </td>
          <td><? db_input('o80_coddot',8,$Io80_coddot,true,'text',$op,"onchange='dot();'"); ?> </td>
          <td><input type="button" name="pesquisa_dot"  value="pesquisar" onclick="dot();" ></td>
        </tr>

        <?  /* busca dados da dotação  */
        if (isset($o80_coddot) and !$o80_coddot== "")
        {
          $instit=$GLOBALS["DB_instit"];
          $clorcdotacao->sql_record($clorcdotacao->sql_query_file("","","*","","o58_coddot=$o80_coddot and o58_instit=$instit"));
          if ($clorcdotacao->numrows >0)
          {
            $result = db_dotacaosaldo(8,2,2,true," o58_coddot = $o80_coddot and o58_anousu = $anousu",$anousu,$DBtxtperiodoini,$DBtxtperiodofim);

            if (!$result || pg_num_rows($result) == 0) {
              echo "<script> \nalert(' Dotação $o80_coddot  não encontrada '); location.href = 'orc1_orcreserva001.php'; \n </script>";
            } else {
              db_fieldsmemory($result, 0);
            }
          } else {
            echo "<script> \nalert(' Dotação $o80_coddot  não encontrada '); location.href = 'orc1_orcreserva001.php'; \n </script>";
          }

        }  else
        {
          $db_botao=false;
        }
        ?>
        <tr>
          <td nowrap title="<?=@$To58_orgao ?>">Orgão : </td>
          <td><? db_input('o58_orgao',8,"$Io58_orgao",true,'text',3,"");  ?> </td>
          <td colspan=3><? db_input('o40_descr',50,"",true,'text',3,"");  ?> </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To58_unidade ?>">Unidade  : </td>
          <td><? db_input('o58_unidade',8,"",true,'text',3,"");  ?> </td>
          <td colspan=3 ><? db_input('o41_descr',50,"",true,'text',3,"");  ?>  </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To58_funcao ?>">Função : </td>
          <td> <? db_input('o58_funcao',8,"",true,'text',3,"");  ?> </td>
          <td> <? db_input('o52_descr',40,"",true,'text',3,"");  ?>  </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To58_subfuncao ?>" >Sub-Função : </td>
          <td> <? db_input('o58_subfuncao',8,"",true,'text',3,"");  ?>  </td>
          <td><? db_input('o53_descr',40,"",true,'text',3,"");  ?></td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To58_programa ?>"    >Programa  : </td>
          <td><? db_input('o58_programa',8,"",true,'text',3,"");  ?> </td>
          <td><? db_input('o54_descr',40,"",true,'text',3,"");  ?>       </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To58_projativ ?>">Proj/Ativ  : </td>
          <td><? db_input('o58_projativ',8,"",true,'text',3,"");  ?> </td>
          <td><? db_input('o55_descr',40,"",true,'text',3,"");  ?>    </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To58_elemento ?>" >Elemento  : </td>
          <td> <? db_input('o58_elemento',15,"",true,'text',3,"");  ?>  </td>
          <td> <? db_input('o56_descr',40,"",true,'text',3,"");  ?>       </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To58_codigo ?>" >Recurso  : </td>
          <td> <? db_input('o58_codigo',8,"",true,'text',3,"");  ?> </td>
          <td> <? db_input('o15_descr',40,"",true,'text',3,"");  ?> </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To58_codigo ?>" >Saldo da Dotação  : </td>
          <td> <? db_input('atual_menos_reservado',15,"",true,'text',3,"");  ?> </td>
          <td> &nbsp; </td>
        </tr>

        <!---  // end dotação --->

        <tr>
          <td nowrap title="<?=@$To80_dtlanc?>"> <?=@$Lo80_dtlanc?> </td>
          <td><?
            if ($db_opcao=="1"  ) {
              $dt= getdate();
              $o80_dtlanc_dia = date("d",db_getsession("DB_datausu"));
              $o80_dtlanc_mes = date("m",db_getsession("DB_datausu"));
              $o80_dtlanc_ano = date("Y",db_getsession("DB_datausu"));

              $o80_dtini_dia = date("d",db_getsession("DB_datausu"));
              $o80_dtini_mes = date("m",db_getsession("DB_datausu"));
              $o80_dtini_ano = date("Y",db_getsession("DB_datausu"));

              $o80_dtfim_dia = "31";
              $o80_dtfim_mes = "12";
              $o80_dtfim_ano = date("Y",db_getsession("DB_datausu"));
            }
            db_inputdata('o80_dtlanc',@$o80_dtlanc_dia,@$o80_dtlanc_mes,@$o80_dtlanc_ano,true,'text',3,"") ?> </td>
          <td>  </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$To80_dtini?>"><?=@$Lo80_dtini?> </td>
          <td> <? db_inputdata('o80_dtini',@$o80_dtini_dia,@$o80_dtini_mes,@$o80_dtini_ano,true,'text',$op,"") ?> </td>
          <td>  </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To80_dtfim?>"><?=@$Lo80_dtfim?> </td>
          <td>
            <?
            if ($db_opcao=="2"  ) {
              $tody = getdate();
              if (($o80_dtfim_ano < $tody["year"]) or ($o80_dtfim_ano == $tody["year"])) {
                if ($o80_dtfim_mes < $tody["mon"] or $o80_dtfim_mes == $tody["mon"]) {
                  if ($o80_dtfim_dia < $tody["wday"]) {
                    db_inputdata('o80_dtfim',@$o80_dtfim_dia,@$o80_dtfim_mes,@$o80_dtfim_ano,true,'text',$op,"");
                  }else{
                    db_inputdata('o80_dtfim',@$o80_dtfim_dia,@$o80_dtfim_mes,@$o80_dtfim_ano,true,'text',$db_opcao,"");
                  }
                }else{
                  db_inputdata('o80_dtfim',@$o80_dtfim_dia,@$o80_dtfim_mes,@$o80_dtfim_ano,true,'text',$db_opcao,"");
                }
              }else{
                db_inputdata('o80_dtfim',@$o80_dtfim_dia,@$o80_dtfim_mes,@$o80_dtfim_ano,true,'text',$db_opcao,"");
              }
            }else{
              db_inputdata('o80_dtfim',@$o80_dtfim_dia,@$o80_dtfim_mes,@$o80_dtfim_ano,true,'text',$db_opcao,"");
            }
            ?> </td>
          <td>  </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To80_valor?>"><?=@$Lo80_valor?>  </td>
          <td> <? db_input('o80_valor',15,$Io80_valor,true,'text',$db_opcao,"") ?>  </td>
          <td> Valor Original:
            <? if (isset($o80_valor)) {
              $original = $o80_valor ; //  echo "<script> alert('$o80_valor');  </script>";
            }
            db_input('original',15,"",true,'text',3,"") ?> </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To80_descr?>"> <?=@$Lo80_descr?> </td>
          <td colspan="2"><? db_textarea('o80_descr',1,72,$Io80_descr,true,'text',$db_opcao,"") ?> </td>
        </tr>
      </table>
    </fieldset>
  </div>

  <div class="container">
  <input name="db_opcao" type="button" id="db_opcao"
         value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
    <?=($db_botao==false?"disabled":"")?>  onclick="critica_form();" >

  <?php
  if(!isset($momenulibera)){
    ?>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <?
    if(isset($o80_codres) && $o80_codres > 0){
      ?>
      <input name="imprimir" type="button" id="imprimir" value="Imprimir" onclick="js_imprime();" >
    <?
    }
  }else{
    db_input('momenulibera',15,"",true,'hidden',3,"");
    ?>
    <input name="fechar" type="button" id="fechar" value="Fechar" onclick="top.corpo.db_iframe_orcreservaalt.hide();" >
  <?
  }
  ?>
  </div>

</form>
<script>

  function js_pesquisao80_anousu(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_anousu|o58_orgao','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.o80_anousu.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
    }
  }
  function js_mostraorcdotacao(chave,erro){
    document.form1.o58_orgao.value = chave;
    if(erro==true){
      document.form1.o80_anousu.focus();
      document.form1.o80_anousu.value = '';
    }
  }
  function js_mostraorcdotacao1(chave1,chave2){
    document.form1.o80_anousu.value = chave1;
    document.form1.o58_orgao.value = chave2;
    db_iframe_orcdotacao.hide();
  }
  function js_pesquisao80_anousu(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_coddot|o58_orgao','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.o80_anousu.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
    }
  }
  function js_mostraorcdotacao(chave,erro){
    document.form1.o58_orgao.value = chave;
    if(erro==true){
      document.form1.o80_anousu.focus();
      document.form1.o80_anousu.value = '';
    }
  }
  function js_mostraorcdotacao1(chave1,chave2){
    document.form1.o80_anousu.value = chave1;
    document.form1.o58_orgao.value = chave2;
    db_iframe_orcdotacao.hide();
  }
  function js_pesquisao80_coddot(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_anousu|o58_orgao','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.o80_coddot.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
    }
  }
  function js_mostraorcdotacao(chave,erro){
    document.form1.o58_orgao.value = chave;
    if(erro==true){
      document.form1.o80_coddot.focus();
      document.form1.o80_coddot.value = '';
    }
  }
  function js_mostraorcdotacao1(chave1,chave2){
    document.form1.o80_coddot.value = chave1;
    document.form1.o58_orgao.value = chave2;
    db_iframe_orcdotacao.hide();
  }
  function js_pesquisao80_coddot(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_coddot|o58_orgao','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.o80_coddot.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
    }
  }
  function js_mostraorcdotacao(chave,erro){
    document.form1.o58_orgao.value = chave;
    if(erro==true){
      document.form1.o80_coddot.focus();
      document.form1.o80_coddot.value = '';
    }
  }
  function js_mostraorcdotacao1(chave1,chave2){
    document.form1.o80_coddot.value = chave1;
    document.form1.o58_orgao.value = chave2;
    db_iframe_orcdotacao.hide();
  }


  function js_imprime(){
    qry = "";
    qry += "res="+document.form1.o80_codres.value;
    qry += "&ano="+document.form1.o80_anousu.value;
    jan = window.open('orc2_reservamanual002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }


  function js_pesquisa(){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreserva','func_orcreserva.php?funcao_js=parent.js_preenchepesquisa|o80_codres','Pesquisa',true);
  }
  function js_preenchepesquisa(chave){
    db_iframe_orcreserva.hide();
    <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>
  }
</script>