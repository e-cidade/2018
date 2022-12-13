<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

//MODULO: saude
$clunidademedicos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");
$clrotulo->label("fa54_i_cbos");

$clespecmedico->rotulo->label();

$clrotulo->label("sd52_i_vinculacao");
$clrotulo->label("sd52_v_descricao");
$clrotulo->label("sd53_v_descrvinculo");
$clrotulo->label("sd54_v_descricao");
$clrotulo->label("sd51_i_codigo");
$clrotulo->label("sd51_v_descricao");
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("descrdepto");
$clrotulo->label("z01_nome");

$db_botao1 = false;
$iPermiteAlteracao = 1;
if(isset($opcao) && $opcao=="alterar"){

	/*  
	 Na alteracao verifico pela codigo da unidademedicos, 
   pois pode ser que para certa ligacao unidademedicos - especmedico nao exista uma ligacao especmedico - prontproced, mas para outra exista
   e a alteracao dos campos unidade e especialidade desta ligacao unidademedicos - especmedico influenciam todas as outras ligacoes deste tipo, pois
   os registros da unidademedicos sao 1 : n com a especmedico 
  */
  $sSql      = $oDaoprontprofatend->sql_query_vinculo_profissional(null, 'count(*) as quant', null, " s104_i_profissional = $sd27_i_codigo ");
  $rsResult1 = $oDaoprontprofatend->sql_record($sSql);
  if ($oDaoprontprofatend->erro_status != "0") {
  	$oQuant1 = db_utils::fieldsmemory($rsResult1,0);
  } else {
  	$oQuant1->quant = 0;
  }
  $sSql      = $oDaoprontproced->sql_query_procedimentos(null, 'count(*) as quant', null, " sd04_i_codigo = $sd04_i_codigo "); 
  $rsResult2 = $oDaoprontproced->sql_record($sSql);
  if ($oDaoprontproced->erro_status != "0") {
    $oQuant2 = db_utils::fieldsmemory($rsResult2,0);
  } else {
  	$oQuant2->quant = 0;
  }
  /* Verifico se o vinculo (especmedico) ja tem algum prontuario, entao os campos unidade e especialidade nao podem ser alterados */
  if($oQuant1->quant > 0 || $oQuant1->quant > 0) {
    $iPermiteAlteracao = 3;
  }
  $db_opcao = 2;
  $db_botao1 = true;

}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 
  $iPermiteAlteracao = 3;
  $db_botao1 = true;
  $db_opcao = 3;

}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}



?>
<form name="form1" method="post" action="">
<center>
<table border="0" cellspacing="0" cellpadding="0">
 <tr>
   <td>
       <tr>
         <td nowrap title="<?=@$Tsd04_i_codigo?>">
            <?=@$Lsd04_i_codigo?>
         </td>
         <td>
          <?
          db_input('sd04_i_codigo',10,$Isd04_i_codigo,true,'hidden',3,"");
          db_input('sd27_i_codigo',10,$Isd27_i_codigo,true,'text',3,"");
          ?>
         </td>
         <td rowspan=11>
               <table border="0">
                 <tr>
                    <td valign="top">
                      <fieldset><legend><b>Carga Horária Semanal</b></legend>
                      <table  width="90%"  border="0">
                      <tr>
                        <td nowrap align="right" title="<?=@$Tsd04_i_horaamb?>">
                           <?=@$Lsd04_i_horaamb?>
                        </td>
                        <td width="39%">
                         <?
                         db_input('sd04_i_horaamb',5,$Isd04_i_horaamb,true,'text',$db_opcao,"onKeyUp=mascara_hora(this.value,'sd04_i_horaamb',event,false)");
                         ?>
                        </td>
                      </tr>
                      <tr>
                        <td nowrap align="right" title="<?=@$Tsd04_i_horahosp?>">
                           <?=@$Lsd04_i_horahosp?>
                        </td>
                        <td>
                         <?
                         db_input('sd04_i_horahosp',5,$Isd04_i_horahosp,true,'text',$db_opcao,"onKeyUp=mascara_hora(this.value,'sd04_i_horahosp',event,false)");
                         ?>
                        </td>
                      </tr>
                      <tr>
                        <td nowrap align="right" title="<?=@$Tsd04_i_horaoutros?>">
                           <?=@$Lsd04_i_horaoutros?>
                        </td>
                        <td>
                         <?
                         db_input('sd04_i_horaoutros',5,$Isd04_i_horaoutros,true,'text',$db_opcao,"onKeyUp=mascara_hora(this.value,'sd04_i_horaoutros',event,false)");
                         ?>
                        </td>
                      </tr>
                      </table>
                      </fieldset>
                    </td>
                 </tr>
                 <tr>
                    <td width="100%" valign="top">
                      <fieldset><legend><b>Atendimento</b></legend>
                      <table border="0">
                       
                      <tr>
                        <td nowrap align="right" title="<?=@$Tsd27_c_situacao?>">
                           <?=@$Lsd27_c_situacao?>
                        </td>
                        <td width="42%">
                         <?
                         $x = array('A'=>'Ativo','D'=>'Desativado');
                         db_select('sd27_c_situacao',$x,true,$db_opcao,"");
                         ?>
                        </td>
                      </tr>
                      
                      <tr>
                        <td nowrap align="right" title="<?=@$Tsd04_c_sus?>">
                           <?=@$Lsd04_c_sus?>
                        </td>
                        <td>
                         <?
                         $x = array('N'=>'Não','S'=>'Sim');
                         db_select('sd04_c_sus',$x,true,$db_opcao,"");
                         ?>
                        </td>
                      </tr>
                      </table>
                      </fieldset>
                    </td>
                  </tr>

                 </table>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Tsd04_i_medico?>">
            <?
            db_ancora(@$Lsd04_i_medico,"js_pesquisasd04_i_medico(true);",3);
            ?>
         </td>
         <td>
          <?
          db_input('sd04_i_medico',10,$Isd04_i_medico,true,'text',3," onchange='js_pesquisasd04_i_medico(false);'")
          ?>
          <?
          db_input('z01_nome',60,$Iz01_nome,true,'text',3,'')
          ?>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Tsd04_i_unidade?>">
            <?
            db_ancora(@$Lsd04_i_unidade,"js_pesquisasd04_i_unidade(true);",$iPermiteAlteracao);
            ?>
         </td>
         <td>
          <?
          db_input('sd04_i_unidade',10,$Isd04_i_unidade,true,'text',$iPermiteAlteracao," onchange='js_pesquisasd04_i_unidade(false);'")
          ?>
          <?
          db_input('descrdepto',60,$Idescrdepto,true,'text',3,'')
          ?>
         </td>
       </tr>       
       <tr>
         <td nowrap title="<?=@$Tsd04_v_registroconselho?>">
            <?=@$Lsd04_v_registroconselho?>
         </td>
         <td>
            <?
            db_input('sd04_v_registroconselho',10,$Isd04_v_registroconselho,true,'text',$db_opcao,"");
            ?>
          </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Tsd04_v_registroconselho?>">
            <?
              db_ancora(@$Lsd04_i_orgaoemissor,"js_pesquisasd04_i_orgaoemissor(true);",$db_opcao);
            ?>
         </td>
         <td>
            <?
            db_input('sd04_i_orgaoemissor',10,$Isd04_i_orgaoemissor,true,'text',$db_opcao," onchange='js_pesquisasd04_i_orgaoemissor(false);'");
            db_input('sd51_v_descricao',60,$Isd51_v_descricao,true,'text',3,'');
            ?>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Tsd27_i_rhcbo?>">
            <?
            db_ancora(@$Lsd27_i_rhcbo,"js_pesquisasd04_i_cbo(true);",$iPermiteAlteracao);
            ?>
         </td>
         <td>
          <?
          db_input('sd27_i_rhcbo',10,$Isd27_i_rhcbo,true,'text',$iPermiteAlteracao," onchange='js_pesquisasd04_i_cbo(false);'")
          ?>
          <?
          db_input('rh70_estrutural',10,$Irh70_estrutural,true,'text',3,'');
          db_input('rh70_descr',46,$Irh70_descr,true,'text',3,'');
          ?>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Tsd27_b_principal?>">
            <?=@$Lsd27_b_principal?>
         </td>
         <td>
            <?
            $x = array('f'=>'Não','t'=>'Sim');
            db_select('sd27_b_principal',$x,true,$db_opcao,"");
            ?>
         </td>
       </tr>

       <tr>
         <td nowrap title="<?=@$Tfa54_i_cbos?>">
           <?=@$Lfa54_i_cbos?>
         </td>
         <td>
           <?
           $sSql = $oDaoFarCbos->sql_query_file();
           $rs   = $oDaoFarCbos->sql_record($sSql);
           $aX   = array();
           if ($oDaoFarCbos->numrows > 0) {
            
             for ($iCont = 0; $iCont < $oDaoFarCbos->numrows; $iCont++) {
     
               $oDados                     = db_utils::fieldsmemory($rs, $iCont);
               $aX[$oDados->fa53_i_codigo] = $oDados->fa53_c_descr;
     
             }

           }
           db_select('fa54_i_cbos', $aX, true, $db_opcao);
           ?>
         </td>
       </tr>

       
       <tr>
         <td nowrap title="<?=@$Tsd04_i_vinculo?>">
            <?
            db_ancora(@$Lsd04_i_vinculo,"js_pesquisasd04_i_vinculo(true);",$db_opcao);
            ?>
         </td>
         <td>
           <?
           db_input('sd04_i_vinculo',10,$Isd04_i_vinculo,true,'text',$db_opcao," onchange='js_pesquisasd04_i_vinculo(false);'")
           ?>
           <?
           db_input('sd52_v_descricao',60,$Isd52_v_descricao,true,'text',3,'')
           ?>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Tsd04_i_tipovinc?>">
            <?
            db_ancora(@$Lsd04_i_tipovinc,"js_pesquisasd04_i_tipovinc(true);",$db_opcao);
            ?>
         </td>
         <td>
          <?
          db_input('sd04_i_tipovinc',10,$Isd04_i_tipovinc,true,'text',$db_opcao," onchange='js_pesquisasd04_i_tipovinc(false);'")
          ?>
           <?
           db_input('sd53_v_descrvinculo',60,$Isd53_v_descrvinculo,true,'text',3,'')
           ?>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Tsd04_i_subtipovinc?>">
            <?
            db_ancora(@$Lsd04_i_subtipovinc,"js_pesquisasd04_i_subtipovinc(true);",$db_opcao);
            ?>
         </td>
         <td>
          <?
          db_input('sd04_i_subtipovinc',10,$Isd04_i_subtipovinc,true,'text',$db_opcao," onchange=js_pesquisasd04_i_subtipovinc(false);")
          ?>
           <?
           db_input('sd54_v_descricao',60,$Isd54_v_descricao,true,'text',3,'')
           ?>
         </td>
       </tr>
</table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >

<table width="100%">
 <tr>
  <td valign="top"><br>
  <?
   $chavepri= array("sd27_i_codigo"=>@$sd27_i_codigo );
   $cliframe_alterar_excluir->chavepri=$chavepri;
   //echo $clespecmedico->sql_query("","*",""," sd04_i_medico = $sd04_i_medico");
   //@$cliframe_alterar_excluir->sql = $clunidademedicos->sql_query("","*",""," sd04_i_medico = $sd04_i_medico");
   @$cliframe_alterar_excluir->sql = $clespecmedico->sql_query("","*",""," sd04_i_medico = $sd04_i_medico");
   @$cliframe_alterar_excluir->campos  ="sd27_i_codigo, rh70_estrutural, rh70_descr, sd27_c_situacao, sd04_i_unidade, sd52_v_descricao, sd04_i_horaamb, sd04_i_horahosp, sd04_v_registroconselho, sd51_v_descricao";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->iframe_height ="200";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  //$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao==2?22:$db_opcao==3?33:$db_opcao);
  ?>
  </td>
 </tr>
</table>


</form>
<script>

function js_pesquisasd04_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.sd04_i_unidade.value != ''){
        js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.sd04_i_unidade.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = '';
     }
  }
}
function js_mostraunidades(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.sd04_i_unidade.focus();
    document.form1.sd04_i_unidade.value = '';
  }
}
function js_mostraunidades1(chave1,chave2){
  document.form1.sd04_i_unidade.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_unidades.hide();
}

function js_pesquisasd04_i_cbo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_rhcbo','func_rhcbosaude.php?funcao_js=parent.js_mostrarhcbo1|rh70_sequencial|rh70_estrutural|rh70_descr','Pesquisa',true);
  }else{
     if(document.form1.sd27_i_rhcbo.value != ''){ 
         //js_OpenJanelaIframe('','db_iframe_rhcbo','func_rhcbosaude.php?pesquisa_chave='+document.form1.sd27_i_rhcbo.value+'&funcao_js=parent.js_mostrarhcbo','Pesquisa',false);
         js_OpenJanelaIframe('','db_iframe_rhcbo','func_rhcbosaude.php?chave_rh70_sequencial='+document.form1.sd27_i_rhcbo.value+'&funcao_js=parent.js_mostrarhcbo1|rh70_sequencial|rh70_estrutural|rh70_descr','Pesquisa',true);
     }else{
       document.form1.rh70_estrutural.value = '';
       document.form1.rh70_descr.value = '';
     }
  }
}
function js_mostrarhcbo(chave1, chave2, chave3,erro){
  document.form1.rh70_estrutural.value = chave1;
  document.form1.rh70_descr.value = chave2;
  document.form1.sd27_i_rhcbo.value = chave3;
  if(erro==true){
    document.form1.sd27_i_rhcbo.focus(); 
    document.form1.sd27_i_rhcbo.value = ''; 
  }
}
function js_mostrarhcbo1(chave1,chave2,chave3){
  document.form1.sd27_i_rhcbo.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_descr.value = chave3;
  db_iframe_rhcbo.hide();
}
function js_pesquisasd04_i_vinculo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_modvinculo','func_sau_modvinculo.php?funcao_js=parent.js_mostrasau_modvinculo1|sd52_i_vinculacao|sd52_v_descricao','Pesquisa',true);
  }else{
     if(document.form1.sd04_i_vinculo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_sau_modvinculo','func_sau_modvinculo.php?pesquisa_chave='+document.form1.sd04_i_vinculo.value+'&funcao_js=parent.js_mostrasau_modvinculo','Pesquisa',false);
     }else{
       document.form1.sd52_v_descricao.value = '';
       document.form1.sd04_i_tipovinc.value = '';
       document.form1.sd53_v_descricao.value = '';
       document.form1.sd04_i_subtipovinc.value = '';
       document.form1.sd54_v_descricao.value = '';
     }
  }
}
function js_mostrasau_modvinculo(chave,erro){
  document.form1.sd52_v_descricao.value = chave;
  if(erro==true){ 
    document.form1.sd04_i_vinculo.focus(); 
    document.form1.sd04_i_vinculo.value = ''; 
    document.form1.sd04_i_tipovinc.value = '';
    document.form1.sd53_v_descrvinculo.value = '';
    document.form1.sd04_i_subtipovinc.value = '';
    document.form1.sd54_v_descricao.value = '';
  }
    document.form1.sd04_i_tipovinc.value = '';
    document.form1.sd53_v_descrvinculo.value = '';
    document.form1.sd04_i_subtipovinc.value = '';
    document.form1.sd54_v_descricao.value = '';
}
function js_mostrasau_modvinculo1(chave1,chave2){
  document.form1.sd04_i_vinculo.value = chave1;
  document.form1.sd52_v_descricao.value = chave2;
    document.form1.sd04_i_tipovinc.value = '';
    document.form1.sd53_v_descrvinculo.value = '';
    document.form1.sd04_i_subtipovinc.value = '';
    document.form1.sd54_v_descricao.value = '';
  db_iframe_sau_modvinculo.hide();
}
function js_pesquisasd04_i_tipovinc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_tpmodvinculo','func_sau_tpmodvinculo.php?chave_sd53_i_vinculacao='+document.form1.sd04_i_vinculo.value+'&funcao_js=parent.js_mostrasau_tpmodvinculo1|sd53_i_tpvinculo|sd53_v_descrvinculo','Pesquisa',true);
  }else{
     if(document.form1.sd04_i_tipovinc.value != ''){
        js_OpenJanelaIframe('','db_iframe_sau_tpmodvinculo','func_sau_tpmodvinculo.php?chave_sd53_i_vinculacao='+document.form1.sd04_i_vinculo.value+'&pesquisa_chave='+document.form1.sd04_i_tipovinc.value+'&funcao_js=parent.js_mostrasau_tpmodvinculo','Pesquisa',false);
     }else{
       document.form1.sd04_i_tipovinc.value = '';
       document.form1.sd53_v_descrvinculo.value = '';
       document.form1.sd04_i_subtipovinc.value = '';
       document.form1.sd54_v_descricao.value = '';
     }
  }
}
function js_mostrasau_tpmodvinculo(chave,erro){
  document.form1.sd53_v_descrvinculo.value = chave;
  if(erro==true){
    document.form1.sd04_i_tipovinc.focus();
    document.form1.sd04_i_tipovinc.value = '';
  }
  document.form1.sd04_i_subtipovinc.value = '';
  document.form1.sd54_v_descricao.value = '';
}
function js_mostrasau_tpmodvinculo1(chave1,chave2){
  document.form1.sd04_i_tipovinc.value = chave1;
  document.form1.sd53_v_descrvinculo.value = chave2;
  document.form1.sd04_i_subtipovinc.value = '';
  document.form1.sd54_v_descricao.value = '';
  db_iframe_sau_tpmodvinculo.hide();
}
function js_pesquisasd04_i_subtipovinc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_subtpmodvinculo','func_sau_subtpmodvinculo.php?chave_sd54_i_vinculacao='+document.form1.sd04_i_vinculo.value+'&chave_sd54_i_tpvinculo='+document.form1.sd04_i_tipovinc.value+'&funcao_js=parent.js_mostrasau_subtpmodvinculo1|sd54_i_tpsubvinculo|sd54_v_descricao','Pesquisa',true);
  }else{
     if(document.form1.sd04_i_subtipovinc.value != ''){
        js_OpenJanelaIframe('','db_iframe_sau_subtpmodvinculo','func_sau_subtpmodvinculo.php?chave_sd54_i_vinculacao='+document.form1.sd04_i_vinculo.value+'&chave_sd54_i_tpvinculo='+document.form1.sd04_i_tipovinc.value+'&pesquisa_chave='+document.form1.sd04_i_subtipovinc.value+'&funcao_js=parent.js_mostrasau_subtpmodvinculo','Pesquisa',false);
     }else{
       document.form1.sd04_i_subtipovinc.value = '';
       document.form1.sd54_v_descricao.value = '';
     }
  }
}
function js_mostrasau_subtpmodvinculo(chave,erro){
  document.form1.sd54_v_descricao.value = chave;
  if(erro==true){
    document.form1.sd04_i_subtipovinc.value = focus();
    document.form1.sd04_i_subtipovinc.value = '';
  }
}
function js_mostrasau_subtpmodvinculo1(chave1,chave2){
  document.form1.sd04_i_subtipovinc.value = chave1;
  document.form1.sd54_v_descricao.value = chave2;
  db_iframe_sau_subtpmodvinculo.hide();
}




function js_pesquisasd04_i_orgaoemissor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_orgaoemissor','func_sau_orgaoemissor.php?funcao_js=parent.js_mostrasau_orgaoemissor1|sd51_i_codigo|sd51_v_descricao','Pesquisa',true);
  }else{
     if(document.form1.sd04_i_orgaoemissor.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_sau_orgaoemissor','func_sau_orgaoemissor.php?pesquisa_chave='+document.form1.sd04_i_orgaoemissor.value+'&funcao_js=parent.js_mostrasau_orgaoemissor','Pesquisa',false);
     }else{
       document.form1.sd51_v_descricao.value = '';
     }
  }
}
function js_mostrasau_orgaoemissor(chave,erro){
  document.form1.sd51_v_descricao.value = chave;
  if(erro==true){ 
    document.form1.sd04_i_orgaoemissor.focus(); 
    document.form1.sd04_i_orgaoemissor.value = ''; 
  }
}
function js_mostrasau_orgaoemissor1(chave1,chave2){
  document.form1.sd04_i_orgaoemissor.value = chave1;
  document.form1.sd51_v_descricao.value = chave2;
  db_iframe_sau_orgaoemissor.hide();
}
function js_pesquisasd04_i_medico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|sd03_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.sd04_i_medico.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd04_i_medico.value+'&funcao_js=parent.js_mostramedicos','Pesquisa',false);
     }else{
       document.form1.sd03_i_codigo.value = ''; 
     }
  }
}
function js_mostramedicos(chave,erro){
  document.form1.sd03_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.sd04_i_medico.focus(); 
    document.form1.sd04_i_medico.value = ''; 
  }
}
function js_mostramedicos1(chave1,chave2){
  document.form1.sd04_i_medico.value = chave1;
  document.form1.sd03_i_codigo.value = chave2;
  db_iframe_medicos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_unidademedicos','func_unidademedicos.php?funcao_js=parent.js_preenchepesquisa|sd04_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_unidademedicos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>