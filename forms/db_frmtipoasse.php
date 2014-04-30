<?
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

//MODULO: recursos humanos
$cltipoasse->rotulo->label();
$clportariatipo->rotulo->label();

$clrotulo->label("h42_descr");
$clrotulo->label("h37_modportariaindividual");
$clrotulo->label("h38_modportariacoletiva");

?>
<br />
<form name="form1" method="post" action="">

  <center>
    <fieldset style="width:700px;">
      <Legend align="left"><strong>Assentamentos/Afastamentos</strong></Legend>
      <fieldset style="width:700px;">
        <legend align="left"><strong>Dados de assentamento</strong></legend>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Th12_assent?>">
              <?=@$Lh12_assent?>
            </td>
            <td>
              <?
              db_input('h12_codigo',5,$Ih12_codigo,true,'hidden',3,"");
              db_input('h12_assent',5,$Ih12_assent,true,'text',$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Th12_descr?>">
              <?=@$Lh12_descr?>
            </td>
            <td colspan="3">
              <?
              db_input('h12_descr',40,$Ih12_descr,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Th12_dias?>">
              <?=@$Lh12_dias?>
            </td>
            <td>
              <?
              db_input('h12_dias',6,$Ih12_dias,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Th12_reltot?>">
              <?=@$Lh12_reltot?>
            </td>
            <td>
              <?
              $x = array(
                         0=>"0 - Nao Soma",
                         1=>"1 - Tempo Municipal",
                         2=>"2 - Tempo Empresa Privada",
                         3=>"3 - Tempo Exercito Nacional",
                         4=>"4 - Tempo Federal",
                         5=>"5 - Tempo Estadual",
                         6=>"6 - Tempo Municipal Averbado",
                         9=>"9 - Tempo Convertido"
                        );
              db_select('h12_reltot',$x,true,$db_opcao,"");
              ?>
            </td>
            <td nowrap title="<?=@$Th12_relgra?>">
              <?=@$Lh12_relgra?>
            </td>
            <td>
              <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('h12_relgra',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Th12_tipo?>">
              <?=@$Lh12_tipo?>
            </td>
            <td>
              <?
              $x = array(
                         "A"=>"A - Afastamento",
                         "S"=>"S - Assentamento"
                        );
              db_select('h12_tipo',$x,true,$db_opcao,"");
              ?>
            </td>
            <td nowrap title="<?=@$Th12_graefe?>">
              <?=@$Lh12_graefe?>
            </td>
            <td>
              <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('h12_graefe',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Th12_efetiv?>">
              <?=@$Lh12_efetiv?>
            </td>
            <td>
              <?
              $x = array(
                         "I"=>"I - Inicio",
                         "F"=>"F - Fim",
                         "+"=>"+ - Soma",
                         "-"=>"- - Diminui",
                         "N"=>"N - Desconsidera",
                         "D"=>"D - Tempo Dobrado",
                         "S"=>"S - Nao Soma Tempo"
                        );
              db_select('h12_efetiv',$x,true,$db_opcao,"");
              ?>
            </td>
            <td nowrap title="<?=@$Th12_tipefe?>">
              <?=@$Lh12_tipefe?>
            </td>
            <td>
              <?
              $x = array(
                         "I"=>"INSS",
                         "P"=>"Prefeitura",
                         "C"=>"Convertida"
                        );
              db_select('h12_tipefe',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>
          <!--
          <tr>
            <td nowrap title="<?=@$Th12_relvan?>">
              <?=@$Lh12_relvan?>
            </td>
            <td>
              <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('h12_relvan',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Th12_relass?>">
              <?=@$Lh12_relass?>
            </td>
            <td>
              <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('h12_relass',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>
          -->
          <tr>
            <td nowrap title="<?=@$Th12_regenc?>">
              <?=@$Lh12_regenc?>
            </td>
            <td>
              <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('h12_regenc',$x,true,$db_opcao,"");
              ?>
            </td>

            <td nowrap title="<?=@$Th12_vinculaperiodoaquisitivo?>">
              <?=@$Lh12_vinculaperiodoaquisitivo?>
            </td>
            <td>
              <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('h12_vinculaperiodoaquisitivo',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>

        </table>

      </fieldset>

      <?php
        if ( !empty($h12_codigo) ) {

          $res_portariatipo = $clportariatipo->sql_record($clportariatipo->sql_query_file(null,"*","h30_tipoasse","h30_tipoasse = ".@$h12_codigo));

          if ($clportariatipo->numrows > 0){

            db_fieldsmemory($res_portariatipo,0);
            db_input("h30_sequencial",10,@$Ih30_sequencial,true,"hidden",3);
          }
        }

        $res_portariaenvolv = $clportariaenvolv->sql_record($clportariaenvolv->sql_query_file(@$h30_portariaenvolv,"h42_descr"));
      ?>

      <fieldset style="width:700px;">

        <legend align="left"><strong>Dados tipo de portaria</strong></legend>

        <table border="0">
          <tr>
            <td nowrap colspan="3" title="<?=@$Th30_portariaenvolv?>"><strong>
            <?
                db_ancora(@$Lh30_portariaenvolv,"js_pesquisa_h30_portariaenvolv(true)",$db_opcao);
            ?>
            </strong></td>
            <td nowrap>
            <?
                db_input("h30_portariaenvolv",10,@$Ih30_portariaenvolv,true,"text",$db_opcao,"onchange='js_pesquisa_h30_portariaenvolv(false);'");
                db_input("h42_descr",40,@$Ih42_descr,true,"text",3);
            ?>
            </td>
          </tr>
          <tr>
          <td nowrap colspan="3">
            <?
                db_ancora(@$Lh37_modportariaindividual,"js_pesquisaModIndividual(true)",$db_opcao);
              ?>
          </td>
          <td>
            <?
              db_input("h37_modportariaindividual",10,@$Ih37_modportariaindividual,true,"text",$db_opcao,"onchange='js_pesquisaModIndividual(false);'");
              db_input("descrModIndividual",40,"",true,"text",3);
            ?>
          </td>
          </tr>
          <tr>
          <td nowrap colspan="3">
            <?
                db_ancora($Lh38_modportariacoletiva,"js_pesquisaModColetiva(true)",$db_opcao);
              ?>
          </td>
          <td>
            <?
              db_input("h38_modportariacoletiva",10,@$Ih38_modportariacoletiva,true,"text",$db_opcao,"onchange='js_pesquisaModColetiva(false);'");
              db_input("descrModColetiva",40,"",true,"text",3);
            ?>
          </td>
          </tr>

          <tr>
            <td nowrap colspan="3" title="<?=@$Th30_portariatipoato?>"><?=@$Lh30_portariatipoato?></td>
            <td nowrap>
            <?
               $res_portariatipoato = $clportariatipoato->sql_record($clportariatipoato->sql_query_file(null,"h41_sequencial,h41_descr"));
               db_selectrecord("h30_portariatipoato",$res_portariatipoato,true,$db_opcao);
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap colspan="3" title="<?=@$Th30_portariaproced?>"><?=@$Lh30_portariaproced?></td>
            <td nowrap>
            <?
               $res_portariaproced = $clportariaproced->sql_record($clportariaproced->sql_query_file(null,"h40_sequencial,h40_descr"));
               db_selectrecord("h30_portariaproced",$res_portariaproced,true,$db_opcao);
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap colspan="3" title="<?=@$Th30_amparolegal?>"><?=@$Lh30_amparolegal?></td>
            <td nowrap>
            <?
               db_textarea('h30_amparolegal',5,40,@$Ih30_amparolegal,true,'text',$db_opcao,"")
            ?>
            </td>
          </tr>
        </table>
      </fieldset>

    </fieldset>

  </center>

  <br />
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" onClick="return js_validaCampos();" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >

  <?php if ($db_opcao != 1) : ?>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  <?php endif; ?>

</form>
<script type="text/javascript">

/**
 * Validação para quando for selecionado a opção "Vincula Periodo aquisitivo" obrigar 
 * o preenchimento do procedimento de portaria
 * @return boolean
 */
function js_validaCampos(){
  
  if (document.form1.h12_vinculaperiodoaquisitivo.value == 't' && !document.form1.h30_portariaproced.value){

    alert(_M('recursoshumanos.rh.rec1_tipoassenta.procedimento_portaria'));
    return false;
  }

  if (document.form1.h12_vinculaperiodoaquisitivo.value == 't' && !document.form1.h30_portariaenvolv.value){

    alert(_M('recursoshumanos.rh.rec1_tipoassenta.portaria_envolvida'));
    return false;
  }

  if (document.form1.h12_vinculaperiodoaquisitivo.value == 't' && !document.form1.h30_portariatipoato.value){

    alert(_M('recursoshumanos.rh.rec1_tipoassenta.ato_portaria'));
    return false;
  }
  return true;
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tipoasse','func_tipoasse.php?funcao_js=parent.js_preenchepesquisa|h12_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tipoasse.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
function js_pesquisa_h30_portariaenvolv(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_portariaenvolv','func_portariaenvolv.php?funcao_js=parent.js_mostrah30_portariaenvolv1|h42_sequencial|h42_descr|h42_amparolegal','Pesquisa',true);
  }else{
     if(document.form1.h30_portariaenvolv.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_portariaenvolv','func_portariaenvolv.php?pesquisa_chave='+document.form1.h30_portariaenvolv.value+'&funcao_js=parent.js_mostrah30_portariaenvolv','Pesquisa',false);
     }else{
       document.form1.h30_portariaenvolv.value = '';
     }
  }
}
function js_mostrah30_portariaenvolv(chave1,erro,chave2,chave3){
  if(erro==true){
      document.form1.h30_portariaenvolv.value = '';
      document.form.h30_portariaenvolv.focus();
  } else {
      document.form1.h30_portariaenvolv.value = chave1;
      document.form1.h42_descr.value          = chave2;
      if (document.form1.h30_amparolegal.value == ""){
           document.form1.h30_amparolegal.value = chave3;
      }
  }
}
function js_mostrah30_portariaenvolv1(chave1,chave2,chave3){
   document.form1.h30_portariaenvolv.value = chave1;
   document.form1.h42_descr.value          = chave2;
   if (document.form1.h30_amparolegal.value == ""){
        document.form1.h30_amparolegal.value = chave3;
   }
   db_iframe_portariaenvolv.hide();
}


function js_pesquisaModIndividual(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_relatorio','func_db_relatorio.php?funcao_js=parent.js_mostraModIndividual1|db63_sequencial|db63_nomerelatorio','Pesquisa',true);
  }else{
     if(document.form1.h37_modportariaindividual.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_relatorio','func_db_relatorio.php?pesquisa_chave='+document.form1.h37_modportariaindividual.value+'&funcao_js=parent.js_mostraModIndividual','Pesquisa',false);
     }else{
       document.form1.descrModIndividual.value = '';
     }
  }
}

function js_mostraModIndividual(chave,erro){
  document.form1.descrModIndividual.value = chave;
  if(erro==true){
    document.form1.h37_modportariaindividual.focus();
    document.form1.h37_modportariaindividual.value = '';
  }
}

function js_mostraModIndividual1(chave1,chave2){
  document.form1.h37_modportariaindividual.value = chave1;
  document.form1.descrModIndividual.value    = chave2;
  db_iframe_db_relatorio.hide();
}

function js_pesquisaModColetiva(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_relatorio','func_db_relatorio.php?funcao_js=parent.js_mostraModColetiva1|db63_sequencial|db63_nomerelatorio','Pesquisa',true);
  }else{
     if(document.form1.h38_modportariacoletiva.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_relatorio','func_db_relatorio.php?pesquisa_chave='+document.form1.h38_modportariacoletiva.value+'&funcao_js=parent.js_mostraModColetiva','Pesquisa',false);
     }else{
       document.form1.descrModColetiva.value = '';
     }
  }
}

function js_mostraModColetiva(chave,erro){
  document.form1.descrModColetiva.value = chave;
  if(erro==true){
    document.form1.h38_modportariacoletiva.focus();
    document.form1.h38_modportariacoletiva.value = '';
  }
}

function js_mostraModColetiva1(chave1,chave2){
  document.form1.h38_modportariacoletiva.value = chave1;
  document.form1.descrModColetiva.value      = chave2;
  db_iframe_db_relatorio.hide();
}

</script>