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

//MODULO: pessoal
$clcfpess->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db77_descr");
$clrotulo->label("i01_descr");
$clrotulo->label("r08_descr");
$clrotulo->label("c50_descr");
$clrotulo->label("db149_descricao");

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <?
  $r11_anousu = db_anofolha();
  $r11_mesusu = db_mesfolha();
  db_input('r11_anousu',4,$Ir11_anousu,true,'hidden',$db_opcao,"");
  db_input('r11_mesusu',2,$Ir11_mesusu,true,'hidden',$db_opcao,"");
  ?>
  <tr>
    <td>
      <fieldset>
        <legend align="left"><b>Dados de Tabelas</b></legend>
        <table>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_tbprev?>">
              <?=@$Lr11_tbprev?>
            </td>
            <td> 
              <?
              $result_tbprev = $clinssirf->sql_record($clinssirf->sql_query_file(null,db_getsession("DB_instit")," distinct cast(r33_codtab as integer)-2 as r33_codtab,r33_nome","r33_codtab","r33_codtab between 3 and 6 and r33_mesusu=$r11_mesusu and r33_anousu=$r11_anousu "));
              db_selectrecord("r11_tbprev",$result_tbprev,true,$db_opcao,"","","","0-Nenhum...");
              ?>
            </td>
            <td>
            </td>
            </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_pctemp?>">
              <?=@$Lr11_pctemp?>
            </td>
            <td> 
              <?
              db_input('r11_pctemp',8,$Ir11_pctemp,true,'text',$db_opcao,"")
              ?>
            </td>
            <td nowrap align="left" title="<?=@$Tr11_peactr?>">
              <?=@$Lr11_peactr?>
            </td>
            <td> 
              <?
              db_input('r11_peactr',8,$Ir11_peactr,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_pcterc?>">
              <?=@$Lr11_pcterc?>
            </td>
            <td> 
              <?
              db_input('r11_pcterc',8,$Ir11_pcterc,true,'text',$db_opcao,"")
              ?>
            </td>
            <td nowrap align="left" title="<?=@$Tr11_codaec?>">
              <?=@$Lr11_codaec?>
            </td>
            <td> 
              <?
              db_input('r11_codaec',8,$Ir11_codaec,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_natest?>">
              <?=@$Lr11_natest?>
            </td>
            <td> 
              <?
              db_input('r11_natest',8,$Ir11_natest,true,'text',$db_opcao,"")
              ?>
            </td>
            <td nowrap align="left" title="<?=@$Tr11_cdfpas?>">
              <?=@$Lr11_cdfpas?>
            </td>
            <td> 
              <?
              db_input('r11_cdfpas',8,$Ir11_cdfpas,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_cdcef?>">
              <?=@$Lr11_cdcef?>
            </td>
            <td> 
              <?
              db_input('r11_cdcef',8,$Ir11_cdcef,true,'text',$db_opcao,"")
              ?>
            </td>
            <td nowrap align="left" title="<?=@$Tr11_cdfgts?>">
              <?=@$Lr11_cdfgts?>
            </td>
            <td> 
              <?
              db_input('r11_cdfgts',8,$Ir11_cdfgts,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_cdactr?>">
              <?=@$Lr11_cdactr?>
            </td>
            <td> 
              <?
              db_input('r11_cdactr',8,$Ir11_cdactr,true,'text',$db_opcao,"")
              ?>
            </td>
            <td nowrap align="left" title="<?=@$Tr11_fgts12?>">
              <?=@$Lr11_fgts12?>
            </td>
            <td> 
              <?
              $x = array("1"=>"CGC","2"=>"CEI");
              db_select('r11_fgts12',$x,true,$db_opcao," style='width: 68px;'");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset>
        <legend align="left"><b>Dados de Configurações</b></legend>
        <table>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_codestrut?>">
              <?
              db_ancora(@$Lr11_codestrut,"js_pesquisar11_codestrut(true);",$db_opcao);
              ?>
            </td>
            <td> 
              <?
              db_input('r11_codestrut',8,$Ir11_codestrut,true,'text',$db_opcao," onchange='js_pesquisar11_codestrut(false);'")
              ?>
              <?
              db_input('db77_descr',40,$Idb77_descr,true,'text',3,'')
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_localtrab?>">
              <?
              db_ancora(@$Lr11_localtrab,"js_pesquisar11_localtrab(true);",$db_opcao);
              ?>
            </td>
            <td> 
              <?
              db_input('r11_localtrab',8,$Ir11_localtrab,true,'text',$db_opcao," onchange='js_pesquisar11_localtrab(false);'")
              ?>
              <?
              db_input('db77_descr',40,$Idb77_descr,true,'text',3,'',"db77_descr1")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr11_infla?>" align="left">
              <?
              db_ancora(@$Lr11_infla,"js_pesquisar11_infla(true);",1);
              ?>
            </td>
            <td align="left"> 
              <?
              db_input('r11_infla',8,$Ir11_infla,true,'text',1," onchange='js_pesquisar11_infla(false);'")
              ?>
              <?
              db_input('i01_descr',40,$Ii01_descr,true,'text',3,'')
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr11_baseipe?>" align="left">
              <?
              db_ancora(@$Lr11_baseipe,"js_pesquisar11_baseipe(true);",1);
              ?>
            </td>
            <td align="left"> 
              <?
              db_input('r11_baseipe',8,$Ir11_baseipe,true,'text',1," onchange='js_pesquisar11_baseipe(false);'");

                if (!empty($r11_baseipe)) {

                  $r08_descr = BaseRepository::getBase(
                    $r11_baseipe, 
                    DBPessoal::getCompetenciaFolha(), 
                    InstituicaoRepository::getInstituicaoSessao()
                  )->getNome();
                }

                db_input('r08_descr',40,$Ir08_descr,true,'text',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr11_histslip?>" align="left">
              <?
                db_ancora(@$Lr11_histslip,"js_pesquisar11_histslip(true);",1);
              ?>
            </td>
            <td align="left"> 
              <?
                db_input('r11_histslip',8,$Ir11_histslip,true,'text',1," onchange='js_pesquisar11_histslip(false);'");
                db_input('c50_descr',40,'',true,'text',3,'');
              ?>
            </td>
          </tr>          
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_geraretencaoempenho?>">
              <?=@$Lr11_geraretencaoempenho?>
            </td>
            <td> 
              <?
              $x = array("t"=>"SIM","f"=>"NÃO");
              db_select('r11_geraretencaoempenho',$x,true,$db_opcao," style='width: 135px;' ");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_arredn?>">
              <?=@$Lr11_arredn?>
            </td>
            <td> 
              <?
              $x = array(0=>"Sem arredondar",1=>"2a casa decimal",2=>"1a casa decimal",3=>"Unidade",5=>"Dezena");
              db_select('r11_arredn',$x,true,$db_opcao," style='width: 135px;'");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_concatdv?>">
              <?=@$Lr11_concatdv?>
            </td>
            <td> 
              <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('r11_concatdv',$x,true,$db_opcao," style='width: 135px;'");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_sald13?>">
              <?=@$Lr11_sald13?>
            </td>
            <td> 
              <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('r11_sald13',$x,true,$db_opcao," style='width: 135px;' ");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_mes13?>">
              <?=@$Lr11_mes13?>
            </td>
            <td> 
              <?
              db_input('r11_mes13',4,$Ir11_mes13,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_txadm?>">
              <?=@$Lr11_txadm?>
            </td>
            <td> 
              <?
              db_input('r11_txadm',4,$Ir11_txadm,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_modanalitica?>">
              <?=@$Lr11_modanalitica?>
            </td>
            <td> 
              <?
              db_input('r11_modanalitica',4,$Ir11_modanalitica,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_mensagempadraotxt?>">
              <?=@$Lr11_mensagempadraotxt?>
            </td>
            <td> 
              <?
                db_input('r11_mensagempadraotxt',4,$Ir11_mensagempadraotxt,true,'text',$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?php echo $Tr11_datainiciovigenciarpps; ?>">
              <?php echo $Lr11_datainiciovigenciarpps; ?>
            </td>
            <td>
              <?php 
                db_inputdata( 'r11_datainiciovigenciarpps', 
                              $r11_datainiciovigenciarpps_dia, 
                              $r11_datainiciovigenciarpps_mes, 
                              $r11_datainiciovigenciarpps_ano, 
                              true, 'text', $db_opcao );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?php echo $Tr11_suplementar; ?>">
              <?php echo $Lr11_suplementar; ?>
            </td>
            <td>
              <?php if ( isset($r11_suplementar) && ($r11_suplementar == 't' || $r11_suplementar === true || $r11_suplementar == '1' ) ): ?>
                <input type="text" value="Ativada" readonly="" size="28" title="<?php echo $Tr11_suplementar; ?>"style="background-color:#DEB887;" >
              <?php else: ?>
                <input type="text" value="Desativada" readonly="" size="28" title="<?php echo $Tr11_suplementar; ?>"style="background-color:#DEB887;" >
              <?php endif; ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>

  <tr>
    <td>
      <fieldset id="modeloRelatorios">
        <legend><strong>Modelo de Impressão de Relatórios</strong></legend>
        <table>

          <tr>
            <td width="237" title="<?php echo $Tr11_relatoriocontracheque; ?>">
              <strong>Relatório Contra Cheque: </strong>
            </td>
            <td>
              <?php db_input('r11_relatoriocontracheque', 4, $Ir11_relatoriocontracheque, true, 'text', $db_opcao, ""); ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo $Tr11_relatorioempenhofolha; ?>">
              <strong>Relatório Empenho Folha: </strong>
            </td>
            <td>
              <?php db_input('r11_relatorioempenhofolha', 4, $Ir11_relatorioempenhofolha, true, 'text', $db_opcao, ""); ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo $Tr11_relatoriocomprovanterendimentos; ?>">
              <strong>Relatório Comprovante Rendimentos: </strong>
            </td>
            <td>
              <?php db_input('r11_relatoriocomprovanterendimentos', 4, $Ir11_relatoriocomprovanterendimentos, true, 'text', $db_opcao, ""); ?>
            </td>
          </tr>
          
          <tr>
            <td nowrap title="<?php echo $Tr11_relatoriotermorescisao; ?>">
              <strong>Relatório Termo Rescisão:</strong>
            </td>
            <td>
              <?php db_input('r11_relatoriotermorescisao', 4, $Ir11_relatoriotermorescisao, true, 'text', $db_opcao, ""); ?>
            </td>
          </tr>

        </table>
      </fieldset>
    </td>
  </tr>

  <tr>
    <td>
      <fieldset>
        <legend>RAIS</legend>

        <table>
          <tr>
            <td nowrap title="<?php echo $Tr11_sistemacontroleponto; ?>">
              <?php echo $Lr11_sistemacontroleponto; ?>
            </td>
            <td>
              <?php 

                $aOptions = array(
                  ''   => "Selecione",
                  '01' => "Estabelecimento não adotou sistema de controle de ponto porque em nenhum mês do ano-base possuía mais de 10 trabalhadores celetistas ativos",
                  '02' => "Estabelecimento adotou sistema manual",
                  '03' => "Estabelecimento adotou sistema mecânico",
                  '04' => "Estabelecimento adotou Sistema de Registro Eletrônico de Ponto - SREP (Portaria 1.510/2009)",
                  '05' => "Estabelecimento adotou sistema não eletrônico alternativo previsto no art.1º da Portaria 373/2011",
                  '06' => "Estabelecimento adotou sistema eletrônico alternativo previsto na Portaria 373/2011"
                );

                db_select('r11_sistemacontroleponto', $aOptions, true, $db_opcao, " style='width: 400px;'");

              ?>
            </td>
          </tr>
        </table>
      
      </fieldset>
    </td>
  </tr>

  <tr>
    <td>
      <fieldset>
        <legend>Tabelas IRRF</legend>
        <table>
          <tr>
            <td nowrap title="<?php echo $Tr11_tabelavaloresrra; ?>"  width="215">
              <label id="lbl_r11_tabelavaloresrra" for="r11_tabelavaloresrra">
                <?php 
                  if(!isset($Lr11_tabelavaloresrra)) {
                    $Lr11_tabelavaloresrra = '';
                  }
                  db_ancora($Lr11_tabelavaloresrra,"js_pesquisar11_tabelavaloresrra(true);",$db_opcao);
                ?>
              </label>
            </td>
            <td>
              <?php 
                db_input('r11_tabelavaloresrra', 10, $Ir11_tabelavaloresrra, true, "text", $db_opcao, "onchange='js_pesquisar11_tabelavaloresrra(false);'"); 
                db_input('db149_descricao', 50, $Idb149_descricao, true, "text", 3); 
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>

</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
/**
 * Toogle do fieldset dos modelos de impressão de relatórios
 */   
var oToogleRelatorios = new DBToogle('modeloRelatorios', false);

function js_pesquisar11_baseipe(mostra) {

  if ( mostra==true ) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bases','func_bases.php?funcao_js=parent.js_mostrabases1|r08_codigo|r08_descr','Pesquisa',true);
  } else {

    if (document.form1.r11_baseipe.value != '') { 
      js_OpenJanelaIframe('','db_iframe_bases','func_bases.php?pesquisa_chave='+document.form1.r11_baseipe.value+'&funcao_js=parent.js_mostrabases','Pesquisa',false);
    } else {
      document.form1.r08_descr.value = ''; 
    }
  }
}


function js_mostrabases(chave,erro){

  document.form1.r08_descr.value = chave; 

  if ( erro == true ) {
 
    document.form1.r11_baseipe.focus(); 
    document.form1.r11_baseipe.value = ''; 
  }
  
}

function js_mostrabases1(chave1, chave2) {

  document.form1.r11_baseipe.value = chave1;
  document.form1.r08_descr.value  = chave2;
  db_iframe_bases.hide();
}

function js_pesquisar11_infla(mostra) {

  if ( mostra==true ) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_inflan','func_inflan.php?funcao_js=parent.js_mostrainflan1|i01_codigo|i01_descr','Pesquisa',true);
  } else {

    if (document.form1.r11_infla.value != '') { 
      js_OpenJanelaIframe('','db_iframe_inflan','func_inflan.php?pesquisa_chave='+document.form1.r11_infla.value+'&funcao_js=parent.js_mostrainflan','Pesquisa',false);
    } else {
      document.form1.i01_descr.value = ''; 
    }
  }
}

function js_mostrainflan( chave, erro ) {

  document.form1.i01_descr.value = chave; 

  if (erro==true) {
 
    document.form1.r11_infla.focus(); 
    document.form1.r11_infla.value = ''; 
  } 
}

function js_mostrainflan1(chave1, chave2) {

  document.form1.r11_infla.value = chave1;
  document.form1.i01_descr.value = chave2;
  db_iframe_inflan.hide();
}

function js_pesquisar11_localtrab(mostra){

  if (mostra==true) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_estrutlocal','func_db_estrutura.php?funcao_js=parent.js_mostradb_estrutlocal1|db77_codestrut|db77_descr','Pesquisa',true);
  } else {

    if (document.form1.r11_localtrab.value != '') { 
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_estrutlocal','func_db_estrutura.php?pesquisa_chave='+document.form1.r11_localtrab.value+'&funcao_js=parent.js_mostradb_estrutlocal','Pesquisa',false);
    } else {
      document.form1.db77_descr1.value = ''; 
    }
  }
}

function js_mostradb_estrutlocal(chave, erro) {

  document.form1.db77_descr1.value = chave; 

  if ( erro==true ) { 
    document.form1.r11_localtrab.focus(); 
    document.form1.r11_localtrab.value = ''; 
  }
}

function js_mostradb_estrutlocal1(chave1, chave2) {

  document.form1.r11_localtrab.value = chave1;
  document.form1.db77_descr1.value = chave2;
  db_iframe_db_estrutlocal.hide();
}

function js_pesquisar11_codestrut(mostra) {

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_estrutura','func_db_estrutura.php?funcao_js=parent.js_mostradb_estrutura1|db77_codestrut|db77_descr','Pesquisa',true);
  }else{

    if(document.form1.r11_codestrut.value != ''){ 
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_estrutura','func_db_estrutura.php?pesquisa_chave='+document.form1.r11_codestrut.value+'&funcao_js=parent.js_mostradb_estrutura','Pesquisa',false);
    }else{
      document.form1.db77_descr.value = ''; 
    }
  }
}

function js_mostradb_estrutura(chave, erro) {

  document.form1.db77_descr.value = chave; 

  if ( erro==true ) { 

    document.form1.r11_codestrut.focus(); 
    document.form1.r11_codestrut.value = ''; 
  }
}

function js_mostradb_estrutura1(chave1, chave2) {

  document.form1.r11_codestrut.value = chave1;
  document.form1.db77_descr.value = chave2;
  db_iframe_db_estrutura.hide();
}

function js_pesquisar11_histslip(mostra){

  if (mostra==true) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_hist','func_conhist.php?funcao_js=parent.js_mostrahist1|c50_codhist|c50_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_hist','func_conhist.php?pesquisa_chave='+document.form1.r11_histslip.value+'&funcao_js=parent.js_mostrahist','Pesquisa',false);
  }
}

function js_mostrahist(chave,erro) {

  document.form1.c50_descr.value = chave;

  if ( erro==true ) {
    document.form1.r11_histslip.focus();
    document.form1.r11_histslip.value = '';
  }
}

function js_mostrahist1(chave1,chave2) {

  document.form1.r11_histslip.value = chave1;
  document.form1.c50_descr.value    = chave2;
  db_iframe_hist.hide();
}


function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cfpess','func_cfpess.php?funcao_js=parent.js_preenchepesquisa|r11_anousu|r11_mesusu','Pesquisa',true);
}

function js_preenchepesquisa(chave,chave1){

  db_iframe_cfpess.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}

function js_pesquisar11_tabelavaloresrra(mostra) {

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_faixavaloresirrf','func_db_tabelavalores.php?funcao_js=parent.js_mostrafaixavaloresirrf1|db149_sequencial|db149_descricao','Pesquisa',true);
  }else{

    if(document.form1.r11_tabelavaloresrra.value != ''){ 
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_faixavaloresirrf','func_db_tabelavalores.php?pesquisa_chave='+document.form1.r11_tabelavaloresrra.value+'&funcao_js=parent.js_mostrafaixavaloresirrf','Pesquisa',false);
    }else{
      document.form1.db149_descricao.value = ''; 
    }
  }
}

function js_mostrafaixavaloresirrf(chave, erro) {

  document.form1.db149_descricao.value = chave; 

  if ( erro==true ) { 

    document.form1.r11_tabelavaloresrra.focus(); 
    document.form1.r11_tabelavaloresrra.value = ''; 
  }
}

function js_mostrafaixavaloresirrf1(chave1, chave2) {

  document.form1.r11_tabelavaloresrra.value = chave1;
  document.form1.db149_descricao.value = chave2;
  db_iframe_faixavaloresirrf.hide();
}
</script>
