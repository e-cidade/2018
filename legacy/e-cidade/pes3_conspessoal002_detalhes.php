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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/db_libpessoal.php"));
include(modification("libs/db_sql.php"));
include(modification("classes/db_cadferia_classe.php"));
include(modification("classes/db_rhpessoal_classe.php"));
include(modification("classes/db_rhpessoalmov_classe.php"));
include(modification("classes/db_rhpesdoc_classe.php"));
include(modification("classes/db_rhpesfgts_classe.php"));
include(modification("classes/db_cgm_classe.php"));
include(modification("classes/db_rhdepend_classe.php"));
include(modification("classes/db_afasta_classe.php"));
include(modification("classes/db_vtffunc_classe.php"));
include(modification("classes/db_vtfdias_classe.php"));
include(modification("classes/db_vtfempr_classe.php"));
include(modification("classes/db_rhpeslocaltrab_classe.php"));
include(modification("classes/db_pesdiver_classe.php"));
include(modification("classes/db_rhbases_classe.php"));
include(modification("classes/db_rhemissaochequeitem_classe.php"));

$clrhemitechequeitem = new cl_rhemissaochequeitem();
$clrhemitechequeitem->rotulo->label();

$clrhbases = new cl_rhbases;
$clrhbases->rotulo->label();

$clpesdiver = new cl_pesdiver;
$clpesdiver->rotulo->label();

$clrotulo = new rotulocampo;

$clcadferia = new cl_cadferia;
$clcadferia->rotulo->label();

$clrhpessoal = new cl_rhpessoal;
$clrhpessoal->rotulo->label();

$clrhpessoalmov = new cl_rhpessoalmov;
$clrhpessoalmov->rotulo->label();

$clcgm = new cl_cgm;
$clcgm->rotulo->label();

$clrhdepend = new cl_rhdepend;
$clrhdepend->rotulo->label();

$clafasta = new cl_afasta;
$clafasta->rotulo->label();

$clrhpesdoc = new cl_rhpesdoc;
$clrhpesdoc->rotulo->label();

$clrhpesfgts = new cl_rhpesfgts;
$clrhpesfgts->rotulo->label();

$clvtffunc = new cl_vtffunc;
$clvtffunc->rotulo->label();

$clvtfdias = new cl_vtfdias;
$clvtfdias->rotulo->label();

$clvtfempr = new cl_vtfempr;
$clvtfempr->rotulo->label();

$clrhpeslocaltrab = new cl_rhpeslocaltrab;
$clrhpeslocaltrab->rotulo->label();

$clgera_sql_folha = new cl_gera_sql_folha;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<style>
.db_area {
  font-family : courier; 
}
.bordasAqui{
  border: 1px solid #cccccc;
  border-top-color: orange;
  border-right-color: orange;
  border-left-color: orange;
  border-bottom-color: orange;
  background-color: #FFCC66;
}
.bordasCab{
  border: 1px solid #cccccc;
}
.bordasCor2{
  border-top-color: #orange;
  border-right-color: #orange;
  border-left-color: #orange;
  border-bottom-color: #orange;
  background-color: #EFE029;
}
.bordasCor1{
  border-top-color: orange;
  border-right-color: orange;
  border-left-color: orange;
  border-bottom-color: orange;
  background-color: #E4F471;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php
  db_postmemory($HTTP_GET_VARS,0);
  if ($solicitacao == "Ferias") {
    $sql_cadferia = $clcadferia->sql_query_file(null,"*","r30_perai","r30_anousu = ".$ano." and r30_mesusu = ".$mes." and r30_regist = ".$parametro);
    $result = $clcadferia->sql_record($sql_cadferia);
  /////////////////////////////////////////////////////////////////////////////////////////////////////
?>

<table border="0" align="center" cellpadding="0" cellspacing="2" border="80%">
  <tr>
    <td>
      <fieldset>
        <legend><strong>Gozo de férias</strong></legend>
	<table border=0>
	  <tr>
      <td align="left"   width="32%" class="bordasAqui" style="font-size:12px" nowrap colspan="2"><b><?=$RLr30_perai?> </b></td>
	    <td align="center" width="17%" class="bordasAqui" style="font-size:12px" nowrap colspan="2"><b><?=$RLr30_ndias?> </b></td>
	    <td align="center" width="17%" class="bordasAqui" style="font-size:12px" nowrap            ><b><?=$RLr30_abono?> </b></td>
	    <td align="center" width="17%" class="bordasAqui" style="font-size:12px" nowrap            ><b><?=$RLr30_faltas?></b></td>
	    <td align="center" width="17%" class="bordasAqui" style="font-size:12px" nowrap colspan="2"><b><?=$RLr30_ponto?> </b></td>
	    <td align="center" width="17%" class="bordasAqui" style="font-size:12px" nowrap colspan="2"><b>Férias Mes Anterior</b></td>
	  </tr>
	  <tr>
            <td align="center" class="bordasCor1" nowrap title="<?=$Tr30_per1i?>"><?=$RLr30_per1i?></td>
            <td align="center" class="bordasCor1" nowrap title="<?=$Tr30_proc1?>"><?=$RLr30_proc1?></td>
            <td align="center" class="bordasCor1" nowrap title="<?=$Tr30_dias1?>"><?=$RLr30_dias1?></td>
            <td align="center" class="bordasCor1" nowrap title="<?=$Tr30_tip1?> "><?=$RLr30_tip1?> </td>
            <td align="center" class="bordasCor1" nowrap title="<?=$Tr30_per2i?>"><?=$RLr30_per2i?></td>
            <td align="center" class="bordasCor1" nowrap title="<?=$Tr30_proc2?>"><?=$RLr30_proc2?></td>
            <td align="center" class="bordasCor1" nowrap title="<?=$Tr30_dias2?>"><?=$RLr30_dias2?></td>
            <td align="center" class="bordasCor1" nowrap title="<?=$Tr30_tip2?>" ><?=$RLr30_tip2?> </td>
            <td align="center" class="bordasCor1" nowrap title="<?=$Tr30_tip2?>" ><?=$RLr30_paga13?> </td>
	  </tr>
          <?
          for($i=0; $i<$clcadferia->numrows; $i++){
            db_fieldsmemory($result,$i);
            $bordas = "bordasCor1";

            $taman = "";
            $value = "";
            if($i == 0){
              $taman = "height='13'";
              $value = "";
            }
          ?>

          <tr><td <?=$taman?> colspan="8"><?=$value?></td></tr>
          
          <tr align="center"> 
          
            <td align="left" class="bordasAqui" style="font-size:12px" nowrap colspan="2">
              <b><?=db_formatar($r30_perai,'d').' - '.db_formatar($r30_peraf,'d')?></b>
            </td>
            
            <td align="center" class="bordasAqui" style="font-size:12px" nowrap colspan="2">
              <b><?=$r30_ndias?></b>
            </td>
            
            <td align="center" class="bordasAqui" style="font-size:12px" nowrap>
              <b><?=$r30_abono?></b>
            </td>
            
            <td align="center" class="bordasAqui" style="font-size:12px" nowrap>
              <b><?=$r30_faltas?></b>
            </td>
            
            <td align="left" class="bordasAqui" style="font-size:12px" nowrap colspan="2">
              <b><?=($r30_ponto == "C"?"Complementar":"Salário")?></b>
            </td>
            
            <td align="center" class="bordasAqui" style="font-size:12px" nowrap colspan="2">
              <b><?=$r30_vfgt1?></b>
            </td>
            
            <td align="center" class="bordasAqui" style="font-size:12px" nowrap colspan="2">
              <b>Observações</b>
            </td>    
                    
	  </tr>
	  <tr>
	  
	  
	  
            <td align="center" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC">
              <?=db_formatar($r30_per1i,'d').' - '.db_formatar($r30_per1f,'d')?>
            </td>
            
            <td align="center" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$r30_proc1?>
            </td>
            
            <td align="center" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$r30_dias1?>
            </td>
            
            <td align="center" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$r30_tip1?>
            </td>
            
            <td align="center" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=db_formatar($r30_per2i,'d').' - '.db_formatar($r30_per2f,'d')?>
            </td>
            
            <td align="center" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$r30_proc2?>
            </td>
            
            <td align="center" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$r30_dias2?>
            </td>
            
            <td align="center" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$r30_tip2?>
            </td>
            
            <td align="center" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC" colspan="2"> 
              <?=($r30_paga13 == 't'?"Sim":"Não")?>
            </td>
            
            <td id='td_<?=$i ?>' align="center" class="<?=$bordas?>" onmouseover="oHint.showHint('td_<?=$i ?>', 'obs_<?=$i ?>')" nowrap bgcolor="#CCCCCC" > 
              <? echo substr($r30_obs, 0, 25) ?>
            </td>    
      
				 </tr>
				 
				 
         <tr>
             <td align="right" nowrap bgcolor="#CCCCCC" colspan="11" >
              <div style="display: none; "  id="obsd_<?=$i ?>">
                <textarea id='obs_<?=$i ?>'  readonly="readonly" rows="3" cols="50"><?=$r30_obs ?></textarea>   
              </div>
            </td>   
          </tr>
          
          
          <?
          }
          ?>
	</table>
      </fieldset>
    </td>
  </tr>
</table>

<script>

/**
 * Cria uma div semelhante a um Hint contendo textos etc; 
 * @param {STRING} sEstancia Nome da instancia do OBJ
*/
oDbHint = function(sEstancia) {

  var me = this;
  me.sNameInstance  = sEstancia;
  
 /*
  * ao instanciar o OBJ criamos uma div oculta com o text area que recebera o texto
 */ 
  var sHtmlDiv  = "<div id='div_hint' style='position:absolute; display:none; '>                          ";
      sHtmlDiv += "  <textarea style='background-color: #6699CC;' readonly='readonly;'                    "; 
      sHtmlDiv += "    rows='5' cols='45' onmouseout='"+me.sNameInstance+".hideHint();' id='txt_obs' >    ";
      sHtmlDiv += "  </textarea>                                                                          ";
      sHtmlDiv += "</div>                                                                                 ";
      document.write(sHtmlDiv);
      
 /*
  * função a ser chamada que mostra o hint
  * @param {string} sIdAlvo      id do elemeto onde devera aparecer o hint, geralmente um td da tabela
  * @param {string} sComponente  id do componente que contem o texto, algo como um text area oculto
      tratamos assim pois pode haver textos com quebras de linha \n logo daria problemas ao passar o texto
      como parametro 
  */      
  me.showHint = function(sIdAlvo, sComponente){
  
    var iAltura = $(sIdAlvo).offsetTop + "px";
    var iLeft   = ($(sIdAlvo).offsetLeft + 70 )+ "px";
    var sValor = $F(sComponente);

    $('div_hint').style.top     = iAltura;  // setamos a altura que o hint aparecera
    $('div_hint').style.left    = iLeft;    // setamos o left
    $('txt_obs').value          = sValor;   // definimos o valor
    $("div_hint").style.display = 'inLine'; // mudamos o style para visivel 
  }
  
  /*
   * função para ocultar a div no mouseout da div que contem o texto
   *
  */ 
  me.hideHint = function(){
  
    $('txt_obs').value          = '';     // limpamos o valor antigo
    $("div_hint").style.display = 'none'; // ocultamos novamente o hint 
  }

      
}

oHint  = new oDbHint("oHint");


</script>  

<?
  }elseif ($solicitacao == "Efetividade") {
    $sql = $clrhpeslocaltrab->sql_query_rhpessoalmov(null,
                                        "distinct 
                                         rh56_localtrab, 
                                         rh55_estrut,
                                         rh55_descr,
                                         case when rh56_princ = true then 'Sim' else 'Não' end as principal",
                                        "rh55_descr",
                                        "    rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")."
                                         and rhpessoalmov.rh02_regist = ".$parametro."
                                         and rhpessoalmov.rh02_anousu = {$ano} 
                                         and rhpessoalmov.rh02_mesusu = {$mes}
                                         and rh56_princ is true");
                                         
  $result = $clrhpeslocaltrab->sql_record($sql);
  /////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<table  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td>
      <fieldset>
        <legend><strong>Local de Trabalho</strong></legend>
	<table border=0>
	  <tr>
            <td align="center" class="bordasAqui" nowrap title="Código"  ><strong>Código </strong></td>
            <td align="center" class="bordasAqui" nowrap title="Estrutural do local de trabalho"><strong>Estrutural</strong></td>
            <td align="center" class="bordasAqui" nowrap title="Descrição"><strong>Descrição</strong></td>
            <td align="center" class="bordasAqui" nowrap title="Se local de trabalho é principal"><strong>Principal</strong></td>
	  </tr>
	  <?
          $bordas = "bordasCor1";
	  for($i=0; $i<pg_numrows($result); $i++){
            db_fieldsmemory($result, $i);
	  ?>
	  <tr>
            <td align="center" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$rh56_localtrab?>
            </td>
            <td align="left" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$rh55_estrut?>
            </td>
            <td align="left" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$rh55_descr?>
            </td>
            <td align="left"   class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$principal?>
            </td>
          </tr>
	  <?
	  }
	  ?>
	</table>
      </fieldset>
    </td>
  </tr>
</table>
<?
  }elseif ($solicitacao == "Dependentes") {
    $sql = $clrhdepend->sql_query_file(
                                       null,
				       "
                                        rh31_nome,
                                        rh31_dtnasc,
                                        case rh31_gparen 
                                             when 'C' then 'Conjuge'
                                             when 'F' then 'Filho'
                                             when 'P' then 'Pai'
                                             when 'M' then 'Mãe'
                                             when 'A' then 'Avó'
                                        else 'Outros'
                                        end
                                        as rh31_gparen
                                        ,
                                        case when rh31_depend='C' then
                                             'Cálculo'
                                             else case when rh31_depend='S' then
                                                  'Sempre dependente'
                                                   else 
                                                  'Não dependente'
                                             end
                                        end
                                        as rh31_depend,
                                        case rh31_irf
                                             when '0' then 'Não dependente'
                                               when '1' then 'Cônjuge,Companheiro(a)'
                                               when '2' then 'Filho(a)/Enteado(a), até 21 anos de idade'
                                               when '3' then 'Filho(a) ou enteado(a),  24 anos de idade cursando ensino superior'
                                               when '4' then 'Irmão(ã), neto(a) ou bisneto(a),  até 21 anos'
                                               when '5' then 'Irmão(ã), neto(a) ou bisneto(a), de 21 a 24 anos c/ensino superior'
                                               when '6' then 'Pais, avós e bisavós'
                                               when '7' then 'Menor pobre até 21 anos, com a guarda judicia'
                                          else 'Pessoa absolutamente incapaz'
                                        end as rh31_irf
                                        ,
                                        case when rh31_especi='C' then
                                             'Cálculo'
                                             else case when rh31_especi='S' then
                                                  'Sempre dependente'
                                                   else 
                                                  'Não dependente'
                                             end
                                        end
                                        as rh31_especi
                                       ",
                                       "rh31_nome",
                                       "rh31_regist = ".$parametro
                                      );

  $result = $clrhdepend->sql_record($sql);
  /////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<table  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td>
      <fieldset>
        <legend><strong>Dependentes</strong></legend>
	<table border=0>
	  <tr>
            <td align="center" class="bordasAqui" nowrap title="<?=$Trh31_nome?>"  ><strong><?=$RLrh31_nome?>  </strong></td>
            <td align="center" class="bordasAqui" nowrap title="<?=$Trh31_dtnasc?>"><strong><?=$RLrh31_dtnasc?></strong></td>
            <td align="center" class="bordasAqui" nowrap title="<?=$Trh31_gparen?>"><strong><?=$RLrh31_gparen?></strong></td>
            <td align="center" class="bordasAqui" nowrap title="<?=$Trh31_depend?>"><strong><?=$RLrh31_depend?></strong></td>
            <td align="center" class="bordasAqui" nowrap title="<?=$Trh31_irf?>"   ><strong><?=$RLrh31_irf?>   </strong></td>
            <td align="center" class="bordasAqui" nowrap title="<?=$Trh31_especi?>"><strong><?=$RLrh31_especi?></strong></td>
	  </tr>
	  <?
          $bordas = "bordasCor1";
	  for($i=0; $i<$clrhdepend->numrows; $i++){
            db_fieldsmemory($result, $i);
	  ?>
	  <tr>
            <td align="left" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$rh31_nome?>
            </td>
            <td align="center" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=db_formatar($rh31_dtnasc,"d")?>
            </td>
            <td align="left"   class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$rh31_gparen?>
            </td>
            <td align="left"   class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$rh31_depend?>
            </td>
            <td align="left"   class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$rh31_irf?>
            </td>
            <td align="left"   class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$rh31_especi?>
            </td>
          </tr>
	  <?
	  }
	  ?>
	</table>
      </fieldset>
    </td>
  </tr>
</table>
<?
  }elseif ($solicitacao == "Afastamentos") {
    $sql = $clafasta->sql_query_file(
                                     null,
                                     "
                                      case r45_situac
                                           when '2' then 'Sem Remuneração'
                                           when '3' then 'Acidente de trabalho'
                                           when '4' then 'Serviço Militar'
                                           when '5' then 'Licença Gestante'
                                           when '6' then 'Doença'
                                           when '7' then 'Sem Vencimentos/Sem Ônus'
                                           when '8' then 'Doença'
                                      end as r45_situac,
                                      r45_dtafas,
                                      r45_dtreto,
                                      r45_dtlanc,
                                      r45_obs,
                                      r45_dtreto - r45_dtafas + 1 as dias
                                     ",
                                     "r45_dtafas",
                                     "
                                          r45_regist = $parametro
                                      and r45_anousu = $ano
                                      and r45_mesusu = $mes
                                     "
                                    );
  $bordas = "bordasCor1";
  $result = $clafasta->sql_record($sql);
?>
<table  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td>
      <fieldset>
        <legend><strong>Afastamentos</strong></legend>
	<table border=0>
	  <tr>
            <td align="center" class="bordasAqui" nowrap title="<?=$Tr45_situac?>"                               ><strong><?=@$RLr45_situac?></strong></td>
            <td align="center" class="bordasAqui" nowrap title="<?=$Tr45_dtafas?>"                               ><strong><?=@$RLr45_dtafas?></strong></td>
            <td align="center" class="bordasAqui" nowrap title="<?=$Tr45_dtreto?>"                               ><strong><?=@$RLr45_dtreto?></strong></td>
            <td align="center" class="bordasAqui" nowrap title="Número de dias que o funcionário ficou afastado."><strong>Dias               </strong></td>
            <td align="center" class="bordasAqui" nowrap title="<?=$Tr45_dtlanc?>"                               ><strong><?=@$RLr45_dtlanc?></strong></td>
            <td align="center" class="bordasAqui" title="<?=$Tr45_obs?>"><strong><?=@$RLr45_obs?></strong></td>
	  </tr>
          <?
          for ($i = 0;$i < $clafasta->numrows;$i++){
            db_fieldsmemory($result,$i);
          ?>
	  <tr>
            <td align="center" valign="top" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$r45_situac?>
            </td>
            <td align="center" valign="top" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=db_formatar($r45_dtafas,"d")?>
            </td>
            <td align="center" valign="top" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=db_formatar($r45_dtreto,"d")?>
            </td>
            <td align="center" valign="top" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC">
              <?=$dias?>
            </td>
            <td align="center" valign="top" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=db_formatar($r45_dtlanc,'d')?>
            </td>
            <td align="left" valign="top" class="<?=$bordas?>"  bgcolor="#CCCCCC">
              <?=nl2br($r45_obs)?>
            </td>            
	  </tr>
          <?}?>
	</table>
      </fieldset>
    </td>
  </tr>
</table>
<?
  }else if($solicitacao == "Documentos" || $solicitacao == "Outros"){
    if($solicitacao == "Documentos"){
      $sql = $clrhpesdoc->sql_query_file($parametro);
      $result = $clrhpesdoc->sql_record($sql);
      if($clrhpesdoc->numrows > 0){
        db_fieldsmemory($result,0);
?>
<table  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td>
      <fieldset>
        <legend><strong>Documentos</strong></legend>
	<table border=0>
	  <tr>
            <td align="right" class="bordasAqui" nowrap title="">
	      <strong><?=$RLrh16_titele?> / <?=$RLrh16_zonael?> / <?=$RLrh16_secaoe?>:</strong>
	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?=$rh16_titele?> / <?=$rh16_zonael?> / <?=$rh16_secaoe?>
            </td>
            <td align="right" class="bordasAqui" nowrap title="">
	      <strong><?=$RLrh16_carth_n?> / <?=$RLr16_carth_cat?> / <?=$RLrh16_carth_val?>:</strong>
	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?=$rh16_carth_n?> / <?=$r16_carth_cat?> / <?=$rh16_carth_val?>
            </td>
	  </tr>
	  <tr>
            <td align="right" class="bordasAqui" nowrap title="">
	      <strong><?=$RLrh16_ctps_n?>:</strong>
	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?=$rh16_ctps_n?> - <?=$rh16_ctps_s?> / <?=$rh16_ctps_d?>
            </td>
            <td align="right" class="bordasAqui" nowrap title="">
	      <strong><?=$RLrh16_pis?>:</strong>
	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?=$rh16_pis?>
            </td>
	  </tr>
	  <tr>
            <td align="right" class="bordasAqui" nowrap title="">
	      <strong><?=$RLrh16_reserv?>/<?=$RLrh16_catres?>:</strong>
	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?=$rh16_reserv?> / <?=$rh16_catres?>
            </td>
            <td align="right" class="bordasAqui" nowrap title="">
	      <strong><?=$RLz01_cgccpf?>:</strong>
	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?
	      $result_cgccpf = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($rh16_regist,"z01_cgccpf,z01_ident"));
	      if($clrhpessoal->numrows > 0){
                db_fieldsmemory($result_cgccpf, 0);
		if(strlen($z01_cgccpf) == 11){
		  echo db_formatar($z01_cgccpf,"cpf");
		}else{
		  echo db_formatar($z01_cgccpf,"cnpj");
		}
              }
	      ?>
            </td>
	  </tr>
	  <tr>
            <td align="right" class="bordasAqui" nowrap title="">
	      <strong><?=$RLz01_ident?>:</strong>
	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?=@$z01_ident?>
	    </td>
            <td align="left" class="bordasAqui" nowrap title=""><strong>&nbsp;</strong></td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">&nbsp;</td>
	  </tr>
	</table>
      </fieldset>
    </td>
  </tr>
</table>
    <?
      }
    }elseif($solicitacao == "Outros"){
      $clgera_sql_folha->usar_pes = true;
      $sql = $clgera_sql_folha->gerador_sql("", $ano, $mes, $parametro);
      $result = $clrhpessoal->sql_record($sql);
      if($clrhpessoal->numrows > 0){
    	 db_fieldsmemory($result, 0);
	     $arr_tipsal = array('M'=>'Mensal','Q'=>'Quinzenal','D'=>'Diário','H'=>'Hora');
	     $arr_folha = array('M'=>'Mensal','S'=>'Semanal','Q'=>'Quinzenal');
	     $arr_sexo = array('M'=>'Masculino','F'=>'Feminino');
    ?>
<table  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td>
      <fieldset>
        <legend><strong>Outros dados</strong></legend>
					<table border=0>
					  <tr>
				      <td align="right" class="bordasAqui" nowrap title="">
					      <strong><?=$RLrh01_trienio?>:</strong>
					    </td>
				      <td align="left" class="bordasCor1" nowrap bgcolor="#CCCCCC">
				        <?=db_formatar($rh01_trienio,"d")?>
				      </td>
				      <td align="right" class="bordasAqui" nowrap title="">
					      <strong><?=$RLrh01_progres?>:</strong>
					    </td>
				      <td align="left" class="bordasCor1" nowrap bgcolor="#CCCCCC">
				        <?=db_formatar($rh01_progres,"d")?>
				      </td>
					  </tr>
					  <tr>
				      <td align="right" class="bordasAqui" nowrap title="">
					      <strong><?=$RLrh02_tipsal?>:</strong>
					    </td>
				      <td align="left" class="bordasCor1" nowrap bgcolor="#CCCCCC">
				        <?=$rh02_tipsal?> - <?=$arr_tipsal[$rh02_tipsal]?>
				      </td>
				      <td align="right" class="bordasAqui" nowrap title="">
					      <strong><?=$RLrh01_ponto?>:</strong>
					    </td>
				      <td align="left" class="bordasCor1" nowrap bgcolor="#CCCCCC">
				        <?=$rh01_ponto?>
				      </td>
					  </tr>
					  <tr>
				      <td align="right" class="bordasAqui" nowrap title="">
					      <strong><?=$RLrh02_folha?>:</strong>
					    </td>
				      <td align="left" class="bordasCor1" nowrap bgcolor="#CCCCCC">
				        <?=$rh02_folha?> - <?=$arr_folha[$rh02_folha]?>
				      </td>
				      <td align="right" class="bordasAqui" nowrap title="">
					      <strong><?=$RLrh02_instit?>:</strong>
					    </td>
				      <td align="left" class="bordasCor1" nowrap bgcolor="#CCCCCC">
				        <?
				          db_sel_instit($rh02_instit,"codigo, nomeinst");
					        echo $codigo. " - " .$nomeinst;
					      ?>
					    </td>
					  </tr>
            <tr>
              <td align="right" class="bordasAqui" nowrap title="">
                <strong><?=$RLrh02_portadormolestia?>:</strong>
              </td>
              <td align="left" class="bordasCor1" nowrap bgcolor="#CCCCCC">
                <?=($rh02_portadormolestia == 't'?"Sim":"Não")?>
              </td>
              <td align="right" class="bordasAqui" nowrap title="">
                <strong><?=$RLrh02_deficientefisico?>:</strong>
              </td>
              <td align="left" class="bordasCor1" nowrap bgcolor="#CCCCCC">
                <?=($rh02_deficientefisico == 't'?"Sim":"Não")?>
              </td>
            </tr>
					</table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset><legend><b>Observações</b></legend>
      <div style="height: 100%;" class="bordasCor1"><?=nl2br($rh01_observacao)?></div>
      </fieldset>
    </td>
  </tr>
</table>
<? 
    }
  }
  }elseif ($solicitacao == "Vale"){

    db_sel_cfpess($ano, $mes);

    $arr_SorN = array('t'=>'Sim','f'=>'Não');
    $bordas = "bordasCor1";

    $campo_quantidade = "";
    if($r11_vtprop == "t"){
      $campo_quantidade = "quantvale_afas(r17_codigo,r17_regist,r17_anousu,r17_mesusu,0,r17_difere,'".$r11_vtfer."',".db_dias_mes(db_anofolha(),db_mesfolha()).",".db_getsession("DB_instit").") as ";
    }else{
      $campo_quantidade = "quantvale(r17_codigo,r17_regist,r17_anousu,r17_mesusu,0,r17_difere,".db_getsession("DB_instit").") as ";
    }
    $sql = $clvtffunc->sql_query(null, null, null, null, null, " r17_codigo,r16_descr,".$campo_quantidade." r17_quant,r17_difere, case when r17_situac = 'I' then 'Inativo' else 'Ativo' end as r17_situac, case when r17_tipo = 'f' then 'Diário' else 'Mensal' end as r17_tipo ", " r17_codigo ", " r17_anousu = ".$ano." and r17_mesusu = ".$mes." and r17_regist = ".$parametro);
    $result = $clvtffunc->sql_record($sql);
?>
<table  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td>
      <fieldset>
        <legend><strong>Vale transporte</strong></legend>
	<table border=0>
	  <tr>
            <td align="center" class="bordasAqui" nowrap title="<?=$Tr17_codigo?>"><strong><?=$RLr17_codigo?></strong></td>
            <td align="center" class="bordasAqui" nowrap title="<?=$Tr16_descr ?>"><strong><?=$RLr16_descr ?></strong></td>
            <td align="center" class="bordasAqui" nowrap title="<?=$Tr17_quant ?>"><strong><?=$RLr17_quant ?></strong></td>
            <td align="center" class="bordasAqui" nowrap title="<?=$Tr17_difere?>"><strong><?=$RLr17_difere?></strong></td>
            <td align="center" class="bordasAqui" nowrap title="<?=$Tr17_situac?>"><strong><?=$RLr17_situac?></strong></td>
            <td align="center" class="bordasAqui" nowrap title="<?=$Tr17_tipo  ?>"><strong><?=$RLr17_tipo  ?></strong></td>
	  </tr>
          <?
          for ($i = 0;$i < $clvtffunc->numrows;$i++){
            db_fieldsmemory($result,$i);
          ?>
	  <tr>
            <td align="center" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$r17_codigo?>
            </td>
            <td align="left" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$r16_descr?>
            </td>
            <td align="right" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$r17_quant?>
            </td>
            <td align="left" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC">
              <?=$arr_SorN[$r17_difere]?>
            </td>
            <td align="left" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$r17_situac?>
            </td>
            <td align="left" class="<?=$bordas?>" nowrap bgcolor="#CCCCCC"> 
              <?=$r17_tipo?>
            </td>
	  </tr>
          <?}?>
	</table>
      </fieldset>
    </td>
  </tr>
</table>
<?php
}
if($solicitacao == 'Variaveis'){
  
  $oServidor = new Servidor($parametro, $ano, $mes);
  $oVariaveis = DBPessoal::getVariaveisCalculo($oServidor);

  if(!empty($oVariaveis)){
?>

<table  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td>
      <fieldset>
        <legend><strong>Variáveis para Cálculo</strong></legend>

      	<table border=0>

      	  <tr>
            <td align="left" class="bordasAqui" nowrap title="">
      	      <strong>F001 - Salário Hora (F007/F008)</strong>
      	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f001 + 0),"f"); ?>
            </td>
            <td align="left" class="bordasAqui" nowrap title="">
              <strong>F011 - Salário hora</strong>
            </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f011 + 0),"f"); ?>
            </td>
      	  </tr>

      	  <tr>
            <td align="left" class="bordasAqui" nowrap title="">
      	      <strong>F002 - Horas semanais</strong>
      	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f002 + 0),"f"); ?>
            </td>
            <td align="left" class="bordasAqui" nowrap title="">
              <strong>F012 - Anos trabalhados</strong>
            </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f012 + 0),"f"); ?>
            </td>
      	  </tr>

      	  <tr>
            <td align="left" class="bordasAqui" nowrap title="">
      	      <strong>F003 - Data de admissão</strong>
      	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar($oVariaveis->f003,"d"); ?>
            </td>
            <td align="left" class="bordasAqui" nowrap title="">
              <strong>F013 - Qtd. de triênios</strong>
            </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f013 + 0),"f"); ?>
            </td>
      	  </tr>

      	  <tr>
            <td align="left" class="bordasAqui" nowrap title="">
      	      <strong>F004 - Idade</strong>
      	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f004 + 0),"f"); ?>
            </td>
            <td align="left" class="bordasAqui" nowrap title="">
              <strong>F014 - Qtd. de progressões</strong>
            </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f014 + 0),"f"); ?>
            </td>
      	  </tr>

      	  <tr>
            <td align="left" class="bordasAqui" nowrap title="">
      	      <strong>F005 - Dependentes IRRF</strong>
      	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f005 + 0),"f"); ?>
            </td>
            <td align="left" class="bordasAqui" nowrap title="">
              <strong>F015 - % de progressão</strong>
            </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f015 + 0),"f"); ?>
            </td>
      	  </tr>

      	  <tr>
            <td align="left" class="bordasAqui" nowrap title="">
      	      <strong>F006 - Dependentes Sal. Famí­lia</strong>
      	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f006 + 0),"f"); ?>
            </td>
            <td align="left" class="bordasAqui" nowrap title="">
              <strong>F022 - Qtd. de quinquênios</strong>
            </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f022 + 0),"f"); ?>
            </td>
      	  </tr>

      	  <tr>
            <td align="left" class="bordasAqui" nowrap title="">
      	      <strong>F007 - Sal. base sem progressão</strong>
      	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f007 + 0),"f"); ?>
            </td>
            <td align="left" class="bordasAqui" nowrap title="">
              <strong>F024 - Meses para progressão</strong>
            </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f024 + 0),"f"); ?>
            </td>
      	  </tr>

      	  <tr>
            <td align="left" class="bordasAqui" nowrap title="">
              <strong>F008 - Horas mensais</strong>
      	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f008 + 0),"f"); ?>
            </td>
            <td align="left" class="bordasAqui" nowrap title="">
              <strong>F025 - Dias no mês</strong>
            </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f025 + 0),"f"); ?>
            </td>
      	  </tr>

      	  <tr>
            <td align="left" class="bordasAqui" nowrap title="">
      	      <strong>F009 - Meses 13o. Salário</strong>
      	    </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f009 + 0),"f"); ?>
            </td>
            <td align="left" class="bordasAqui" nowrap title="">
              <strong>F030 - Padrão base de previdência</strong>
            </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f030 + 0),"f"); ?>
            </td>
      	  </tr>

          <tr>
            <td align="left" class="bordasAqui" nowrap title="">
              <strong>F010 - Salário base com progressão</strong>
            </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo db_formatar(($oVariaveis->f010 + 0),"f"); ?>
            </td>
            <td align="left" class="bordasAqui" nowrap title="">
              <strong>Padrão atual</strong>
            </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo $oVariaveis->padrao; ?>
            </td>
          </tr>
          
          <tr>
            <td align="left" class="bordasAqui" nowrap>
              <strong>F031 - Domingos no mês</strong>
            </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo $oVariaveis->f031 ?>
            </td>
            <td align="left" class="bordasAqui" nowrap>
              <strong>F032 - Dias úteis do mês</strong>
            </td>
            <td align="right" class="bordasCor1" nowrap bgcolor="#CCCCCC">
              <?php echo $oVariaveis->f032 ?>
            </td>
          </tr>

      	</table>
      </fieldset>
    </td>
  </tr>
</table>
<?php  
    }
  }
if($solicitacao == 'Diversos'){
  $anousu = db_anofolha();
  $mesusu = db_mesfolha();
  $campos = "pesdiver.*";
  $sql = $clpesdiver->sql_query(null,null,null,null,$campos,"r07_codigo","r07_mesusu=$mesusu and r07_anousu=$anousu and r07_instit = ".db_getsession('DB_instit'));
?>

  <table  border="0" align="center" cellpadding="0" cellspacing="2">
    <tr>
      <td>
        <fieldset>
          <legend><strong>Diversos para Cálculo</strong></legend>
        	<table border=0>
                <?
                 db_lovrot($sql,20,"()","");
                ?>
        	</table>
        </fieldset>
      </td>
    </tr>
  </table>

<?php
}
  
if($solicitacao == 'Bases'){
  $campos = "rh32_base,rh32_descr,rh32_calqua,rh32_mesant,rh32_pfixo";
  $sql = $clrhbases->sql_query(null,$campos);
?>

  <table  border="0" align="center" cellpadding="0" cellspacing="2">
    <tr>
      <td>
        <fieldset>
          <legend><strong>Bases para Cálculo</strong></legend>
        	<table border=0>
                <?
                 db_lovrot($sql,20,"()","");
                ?>
        	</table>
        </fieldset>
      </td>
    </tr>
  </table>

<?php
}

if( $solicitacao == 'ChequesEmitidos' ){
	
  $sCampos = "r15_sequencial,r15_descricao,r18_anousu,r18_mesusu,r18_numcheque,r18_valor";
  
  $sWhere   = "     r18_regist = {$parametro}";
  $sWhere  .= " and r18_anousu = {$ano}";
  $sWhere  .= " and r18_mesusu = {$mes}";
  
  $sSql    = $clrhemitechequeitem->sql_query(null,$sCampos,"r18_sequencial",$sWhere);
?>

<table  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td>
      <fieldset>
        <legend align="center"><strong>Cheques Emitidos</strong></legend>
        <table border=0>
              <?
               db_lovrot($sSql,20,"()","");
              ?>
        </table>
      </fieldset>
    </td>
  </tr>
</table>

<?php
}
if ($solicitacao == "temposervico") {
  
  $sSqlTempoServicoAnterior  = "SELECT h16_assent,                                                                                ";
  $sSqlTempoServicoAnterior .= "       h12_descr,                                                                                 ";
  $sSqlTempoServicoAnterior .= "       (date_part('year', age(max(h16_dtterm), min(h16_dtconc)))||' anos '||                      ";
  $sSqlTempoServicoAnterior .= "       date_part('month', age(max(h16_dtterm), min(h16_dtconc)))||' meses '||                     ";
  $sSqlTempoServicoAnterior .= "       date_part('day', age(max(h16_dtterm+1), min(h16_dtconc)))||' dias ')::varchar as dl_tempo, ";
  $sSqlTempoServicoAnterior .= "       sum(h16_quant) as dl_quantidade_dias                                                       ";
  $sSqlTempoServicoAnterior .= "  from assenta                                                                                    ";
  $sSqlTempoServicoAnterior .= "       inner join tipoasse on h16_assent = h12_codigo                                             ";
  $sSqlTempoServicoAnterior .= " where h16_regist = {$parametro} AND H12_reltot >= 1                                              ";
  $sSqlTempoServicoAnterior .= " group by h16_assent,                                                                             ";
  $sSqlTempoServicoAnterior .= "       h12_descr                                                                                  ";

?>
  <table  border="0" align="center" cellpadding="0" cellspacing="2">
    <tr>
      <td>
        <fieldset>
          <legend><strong>Consulta Tempo Anterior</strong></legend>
          <?php
            $aTotalizacao["dl_quantidade_dias"] = "dl_quantidade_dias";
            $aTotalizacao["totalgeral"]         = "dl_tempo";
            db_lovrot($sSqlTempoServicoAnterior, 20, "", "", "" , "", "NoMe", array(), false, $aTotalizacao);
          ?>
        </fieldset>
      </td>
    </tr>
  </table>    

<?php
}
?>

<center>
  <input type="button" name="fechar" value="Fechar" onClick="(window.CurrentWindow || parent.CurrentWindow).corpo.func_pesquisa.hide();">
</center>
</body>
</html>