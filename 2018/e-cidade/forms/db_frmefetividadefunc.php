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

//MODULO: educação
$clefetividade->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed20_i_codigo");
$clrotulo->label("z01_nome");
$clrotulo->label("ed97_i_horacinq");
$clrotulo->label("ed97_i_horacem");
$ed97_i_horacinq = "";
$ed97_i_horacem  = "";

$sql = "SELECT DISTINCT ed20_i_codigo as ed97_i_rechumano,
                        case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,
                        case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal else rechumanocgm.ed285_i_cgm end as identificacao
        FROM rechumano
         left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo
         left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal
         left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm
         left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo
         left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm
         inner join rechumanoescola on ed75_i_rechumano = ed20_i_codigo
         inner join rechumanoativ on ed22_i_rechumanoescola = ed75_i_codigo
         inner join atividaderh on ed01_i_codigo = ed22_i_atividade
        WHERE ed75_i_escola = $escola
        AND ed01_c_efetividade = 'FUNC'
        AND ed20_c_efetividade = 'S'
        AND (ed75_i_saidaescola is null or ed75_i_saidaescola >= '$ed98_d_dataini')

        ORDER BY z01_nome
       ";
$result = db_query($sql);
$linhas = pg_num_rows($result);

?>
<form name="form1" method="post" action="">
  <b>Informe os dados da efetividade - Mês: <?=db_mes($ed98_i_mes,1)?> Ano: <?=$ed98_i_ano?> Tipo:
    <?=$ed98_c_tipo=="P"?"PROFESSORES":"FUNCIONÁRIOS"?></b>
  <br />

<?php
  if ($linhas == 0) {
  ?>
    <div class="subcontainer">
        <h3>Nenhum recurso humano cadastrado como funcionário.</h3>
    </div>
  <?php
  } else {
  ?>
    <div class="subcontainer">
      <input name="salvar" type="button" value="Salvar" <?=($db_botao==false?"disabled":"")?>
             onclick="Salvar(<?=$linhas?>);" />
      <input name="restart" type="button" value="Restaurar" <?=($db_botao==false?"disabled":"")?>
             onclick="location.href='edu1_efetividade001.php?efetividaderh=<?=$efetividaderh?>';" />
    </div>
  <?php
  }
  ?>
<table border="1" width="97%" cellspacing="0" cellpading="1">
  <tr>
    <td class="cabec" align="center">
      <?if($linhas>1){?>
      <input type="checkbox" name="geral" onclick="MarcaTudo(<?=$linhas?>);">
      <input type="hidden" name="status" value="D">
      <?}?>
    </td>
    <td class="cabec" align="center">
      <b>Matr./CGM</b>
    </td>
    <td class="cabec" align="center">
      <b>Nome</b>
    </td>
    <td width="10%" class="cabec" align="center">
      <b>Faltas<br>Abonadas</b>
    </td>
    <td width="10%" class="cabec" align="center">
      <b>Faltas não<br>Justificadas</b>
    </td>
    <td width="8%" class="cabec" align="center">
      <b>Hora Extra<br>50%</b>
    </td>
    <td width="8%" class="cabec" align="center">
      <b>Hora Extra<br>100%</b>
    </td>
    <td width="12%" class="cabec" align="center">
      <b>Licenças</b>
    </td>
    <td width="10%" class="cabec" align="center">
      <b>Observações</b>
    </td>
  </tr>
 <?
 $cor1 = "#f3f3f3";
 $cor2 = "#dbdbdb";
 $cor = "";

if ($linhas > 0) {

  for ($x = 0; $x < $linhas; $x++) {

    db_fieldsmemory($result,$x);

    if ($cor == $cor1) {
      $cor = $cor2;
    } else {
      $cor = $cor1;
    }

    $sql1 = "SELECT ed97_i_codigo,ed97_i_faltaabon,ed97_i_faltanjust,ed97_i_horacinq,ed97_i_horacem,ed97_t_licenca,ed97_t_obs
            FROM efetividade
             inner join efetividaderh on ed98_i_codigo = ed97_i_efetividaderh
            WHERE ed98_i_codigo = $efetividaderh
            AND ed97_i_rechumano = $ed97_i_rechumano
          ";
   $result1 = db_query($sql1);
   $linhas1 = pg_num_rows($result1);

   if ($linhas1 > 0) {
      db_fieldsmemory($result1, 0);
    }

    $oDataInicioEfetividade  = new DBDate( $ed98_d_dataini );
    $oDataTerminoEfetividade = new DBDate( $ed98_d_datafim );

   /**
     * Verifica se o funcionário possui alguma ausência cadastrado para o periodo da efetividade informada
     * @var $oDaoRecHumanoAusente
     */
    $oDaoRecHumanoAusente    = new cl_rechumanoausente();
    $sCamposRecHumanoAusente = " ed348_sequencial, ed348_inicio, ed348_final, ed320_descricao, ed320_tipo ";
    $sWhereRecHumanoAusente  = "     ed348_rechumano = {$ed97_i_rechumano} AND ed348_escola = {$escola}";
    $sWhereRecHumanoAusente .= " AND                                                                    ";
    $sWhereRecHumanoAusente .= "    (    ed348_inicio between '{$oDataInicioEfetividade->getDate()}' AND '{$oDataTerminoEfetividade->getDate()}' ";
    $sWhereRecHumanoAusente .= "      OR ed348_final between '{$oDataInicioEfetividade->getDate()}' AND '{$oDataTerminoEfetividade->getDate()}' ";
    $sWhereRecHumanoAusente .= "      OR ( ed348_inicio < '{$oDataInicioEfetividade->getDate()}' AND ed348_final > '{$oDataTerminoEfetividade->getDate()}' ) ";
    $sWhereRecHumanoAusente .= "      OR ( ed348_inicio < '{$oDataInicioEfetividade->getDate()}' AND ed348_final is null )";
    $sWhereRecHumanoAusente .= "    )";
    $sOrdemRecHumanoAusente  = " ed348_inicio ";
    $sSqlRecHumanoAusente    = $oDaoRecHumanoAusente->sql_query_tipo_ausencia(null, $sCamposRecHumanoAusente, $sOrdemRecHumanoAusente, $sWhereRecHumanoAusente);
    $rsRecHumanoAusente      = db_query($sSqlRecHumanoAusente);
    $iTotalAusencias         = pg_num_rows($rsRecHumanoAusente);

    if ( !$rsRecHumanoAusente ) {
      throw new DBException('Falha ao consultar as ausências do funcionário.');
    }

    $iFaltasAbonadas        = "";
    $iFaltasNaoJustificadas = "";
    $sLicenca               = "";

    if ($iTotalAusencias > 0) {

      for ($iContador = 0; $iContador < $iTotalAusencias; $iContador++) {

        $oDadosAusencia       = db_utils::fieldsMemory($rsRecHumanoAusente, $iContador);
        $oDataInicioAusencia  = new DBDate($oDadosAusencia->ed348_inicio);
        $oDataTerminoAusencia = empty($oDadosAusencia->ed348_final) ? null : new DBDate($oDadosAusencia->ed348_final);

        switch( $oDadosAusencia->ed320_tipo ) {

         /**
          * 1 - Nenhum
          */
         case 1:
           break;

         /**
          * 2 - Licença
          */
         case 2:

            if ( empty($oDataTerminoAusencia) ) {

              if ( DBDate::dataEstaNoIntervalo( $oDataInicioAusencia, $oDataInicioEfetividade, $oDataTerminoEfetividade ) ) {

                $sLicenca .= "{$oDadosAusencia->ed320_descricao} - {$oDataInicioAusencia->convertTo(DBDate::DATA_PTBR)}";
                continue;
              } elseif ( $oDataInicioEfetividade->getTimeStamp() >= $oDataInicioAusencia->getTimeStamp() ) {

                $sLicenca .= "{$oDadosAusencia->ed320_descricao} - {$oDataInicioAusencia->convertTo(DBDate::DATA_PTBR)}";
                continue;
              }
            } else {

              $aPeriodoEfetividadeAux = DBDate::getDatasNoIntervalo( $oDataInicioEfetividade, $oDataTerminoEfetividade );
              $aPeriodoEfetividade    = array();

              foreach ( $aPeriodoEfetividadeAux as $oDataEfetividade ) {
                $aPeriodoEfetividade[] = $oDataEfetividade->convertTo(DBDate::DATA_PTBR);
              }

              $aPeriodoLicencaAux = DBDate::getDatasNoIntervalo( $oDataInicioAusencia, $oDataTerminoAusencia );
              $aPeriodoLicenca    = array();

              foreach ( $aPeriodoLicencaAux as $oDataLicenca ) {
                $aPeriodoLicenca[] = $oDataLicenca->convertTo(DBDate::DATA_PTBR);
              }

              $aPeriodosConflitantes = array_intersect($aPeriodoEfetividade, $aPeriodoLicenca);

              if ( count($aPeriodosConflitantes) > 0) {

                $sLicenca .= "{$oDadosAusencia->ed320_descricao} - {$oDataInicioAusencia->convertTo(DBDate::DATA_PTBR)}";
                $sLicenca .= " à {$oDataTerminoAusencia->convertTo(DBDate::DATA_PTBR)}";

                continue;
              }
            }

         break;

         /**
          * 3 - Falta Abonada
          */
         case 3:

           $oRecursoHumanoAusente = new RecursoHumanoAusente( $oDadosAusencia->ed348_sequencial );
           $iFaltasAbonadas       = $oRecursoHumanoAusente->getTotalFaltas($oDataInicioEfetividade, $oDataTerminoEfetividade );
           break;

         /**
          * 4 - Falta Não Justificada
          */
         case 4:

            $oRecursoHumanoAusente  = new RecursoHumanoAusente( $oDadosAusencia->ed348_sequencial );
            $iFaltasNaoJustificadas = $oRecursoHumanoAusente->getTotalFaltas($oDataInicioEfetividade, $oDataTerminoEfetividade );
            break;
        }
      }
    }

    $sBackground = '#DEB887';
    $sDisabled   = 'disabled=disabled';
    $sChecked    = "";
    if ( !empty($iFaltasAbonadas) || !empty($iFaltasNaoJustificadas) || !empty($sLicenca) || !empty($ed97_i_codigo)) {

      $sBackground = '#E6E4F1';
      $sChecked    = "checked";
      $sDisabled   = '';
    }
    $sStyle    = "text-transform: uppercase; text-align: center; color:#000; background: {$sBackground};";

  ?>
  <tr bgcolor="<?=$cor?>">
    <td align="center">
      <input type="checkbox" name="individual" value="true" <?=$sChecked?>
              onclick="MarcaIndividual(this.value,<?=$x?>,<?=$linhas?>)">
      <input type="hidden" name="ed97_i_codigo" value="<?=@$ed97_i_codigo?>" />
      <input type="hidden" name="efetividaderh" value="<?=$efetividaderh?>" />
      <input type="hidden" name="ed98_c_tipo" value="<?=$ed98_c_tipo?>" />
    </td>
    <td class="aluno" align="center">
      <?=@$identificacao?>
      <input type="hidden" name="ed97_i_rechumano" id="ed97_i_rechumano" value="<?=@$ed97_i_rechumano?>" />
    </td>
    <td class="aluno">
      <?=$z01_nome?>
    </td>
    <td align="center">
      <input type="text" size="3" name="ed97_i_faltaabon" id="ed97_i_faltaabon" value="<?=$iFaltasAbonadas?>"
             style="<?=$sStyle?>" disabled='disabled'
             onKeyUp="js_ValidaCampos(this,1,'Faltas Abonadas','f','f',event);" />
    </td>
    <td align="center">
      <input type="text" size="3" name="ed97_i_faltanjust" id="ed97_i_faltanjust" value="<?=$iFaltasNaoJustificadas?>"
             style="<?=$sStyle?>" disabled='disabled'
             onKeyUp="js_ValidaCampos(this,1,'Faltas não Justificadas','f','f',event);">
    </td>
    <td align="center">
      <input type="text" size="3" name="ed97_i_horacinq" id="ed97_i_horacinq" value="<?=$ed97_i_horacinq?>"
             style="<?=$sStyle?>" <?=$sDisabled?>
             onKeyUp="js_ValidaCampos(this,4,'Hora Extra 50%','t','f',event);">
    </td>
    <td align="center">
      <input type="text" size="3" name="ed97_i_horacem" id="ed97_i_horacem" value="<?=$ed97_i_horacem?>"
             style="<?=$sStyle?>" <?=$sDisabled?>
             onKeyUp="js_ValidaCampos(this,4,'Hora Extra 100%','t','f',event);">
    </td>
    <td align="center">
      <textarea name="ed97_t_licenca" id="ed97_t_licenca" rows="1" cols="30" style="<?=$sStyle?>" disabled='disabled'>
        <?=$sLicenca?>
      </textarea>
    </td>
    <td align="center">
      <textarea name="ed97_t_obs" id="ed97_t_obs" rows="1" cols="30" style="<?=$sStyle?>" <?=$sDisabled?>>
        <?= (isset($ed97_t_obs)) ? $ed97_t_obs : '' ?>
      </textarea>
    </td>
  </tr>

   <?php
    $ed97_i_codigo     = "";
    $ed97_i_faltaabon  = "";
    $ed97_i_faltanjust = "";
    $ed97_i_horacinq   = "";
    $ed97_i_horacem    = "";
    $ed97_t_licenca    = "";
    $ed97_t_obs        = "";
  }

}
?>
</table>
<br>
</form>
<script>
function MarcaTudo(linhas) {

  F      = document.form1;
  status = F.status.value;

  if (status=="D") {

    for (i = 0; i < linhas; i++) {

      F.ed97_i_faltaabon[i].style.background  = '#E6E4F1';
      F.ed97_i_faltanjust[i].style.background = '#E6E4F1';
      F.ed97_t_licenca[i].style.background    = '#E6E4F1';
      F.individual[i].checked                 = true;
      F.ed97_i_horacinq[i].style.background   = "#E6E4F1";
      F.ed97_i_horacinq[i].disabled           = false;
      F.ed97_i_horacem[i].style.background    = "#E6E4F1";
      F.ed97_i_horacem[i].disabled            = false;
      F.ed97_t_obs[i].style.background        = "#E6E4F1";
      F.ed97_t_obs[i].disabled                = false;
   }
   F.status.value = "M";
  } else {

    for (i = 0; i < linhas; i++) {

      F.ed97_i_faltaabon[i].style.background  = '#DEB887';
      F.ed97_i_faltanjust[i].style.background = '#DEB887';
      F.ed97_t_licenca[i].style.background    = '#DEB887';
      F.individual[i].checked                 = false;
      F.ed97_i_horacinq[i].style.background   = "#DEB887";
      F.ed97_i_horacinq[i].disabled           = true;
      F.ed97_i_horacinq[i].value              = "";
      F.ed97_i_horacem[i].style.background    = "#DEB887";
      F.ed97_i_horacem[i].disabled            = true;
      F.ed97_i_horacem[i].value               = "";
      F.ed97_t_obs[i].style.background        = "#DEB887";
      F.ed97_t_obs[i].disabled                = true;
      F.ed97_t_obs[i].value                   = "";
    }
    F.status.value = "D";
  }
}

function MarcaIndividual(valor,linha,linhas) {

  F = document.form1;
  if (linhas > 1) {

    if (F.individual[linha].checked == true) {

      F.ed97_i_faltaabon[linha].style.background  = '#E6E4F1';
      F.ed97_i_faltanjust[linha].style.background = '#E6E4F1';
      F.ed97_t_licenca[linha].style.background    = '#E6E4F1';
      F.ed97_i_horacinq[linha].style.background   = "#E6E4F1";
      F.ed97_i_horacinq[linha].disabled           = false;
      F.ed97_i_horacem[linha].style.background    = "#E6E4F1";
      F.ed97_i_horacem[linha].disabled            = false;
      F.ed97_t_obs[linha].style.background        = "#E6E4F1";
      F.ed97_t_obs[linha].disabled                = false;
    } else {

      F.ed97_i_faltaabon[linha].style.background  = "#DEB887";
      F.ed97_i_faltaabon[linha].disabled          = true;
      F.ed97_i_faltanjust[linha].style.background = "#DEB887";
      F.ed97_i_faltanjust[linha].disabled         = true;
      F.ed97_i_horacinq[linha].style.background   = "#DEB887";
      F.ed97_i_horacinq[linha].disabled           = true;
      F.ed97_i_horacinq[linha].value              = "";
      F.ed97_i_horacem[linha].style.background    = "#DEB887";
      F.ed97_i_horacem[linha].disabled            = true;
      F.ed97_i_horacem[linha].value               = "";
      F.ed97_t_licenca[linha].style.background    = '#DEB887';
      F.ed97_t_obs[linha].style.background        = "#DEB887";
      F.ed97_t_obs[linha].disabled                = true;
      F.ed97_t_obs[linha].value                   = "";
    }
  } else {

    if (F.individual.checked==true) {

      F.ed97_i_faltaabon.style.background  = "#E6E4F1";
      F.ed97_i_faltaabon.disabled          = false;
      F.ed97_i_faltanjust.style.background = "#E6E4F1";
      F.ed97_i_faltanjust.disabled         = false;
      F.ed97_i_horacinq.style.background   = "#E6E4F1";
      F.ed97_i_horacinq.disabled           = false;
      F.ed97_i_horacem.style.background    = "#E6E4F1";
      F.ed97_i_horacem.disabled            = false;
      F.ed97_t_licenca.style.background    = "#E6E4F1";
      F.ed97_t_licenca.disabled            = false;
      F.ed97_t_obs.style.background        = "#E6E4F1";
      F.ed97_t_obs.disabled                = false;
    } else {

      F.ed97_i_horacinq.style.background   = "#DEB887";
      F.ed97_i_horacinq.disabled           = true;
      F.ed97_i_horacinq.value              = "";
      F.ed97_i_horacem.style.background    = "#DEB887";
      F.ed97_i_horacem.disabled            = true;
      F.ed97_i_horacem.value               = "";
      F.ed97_t_obs.style.background        = "#DEB887";
      F.ed97_t_obs.disabled                = true;
      F.ed97_t_obs.value                   = "";
    }
  }
  alguem = false;
  for (i=0;i<F.individual.length;i++) {

    if (F.individual[i].checked==true) {

      alguem = true;
      break;
    }
  }
  if (alguem==false) {

    F.status.value = "D";
    F.geral.checked = false;
  }
}
function Salvar(linhas){
 F = document.form1;
 alguem = false;
 for(i=0;i<linhas;i++){
  if(linhas==1){
   if(F.individual.checked==true){
    alguem = true;
    break;
   }
  }else{
   if(F.individual[i].checked==true){
    alguem = true;
    break;
   }
  }
 }
 if(alguem==false){
  alert("Escolha algum recurso humano para salvar!");
 }else{
  sep = "";
  registrofunc = "";
  if(linhas==1){
   if(F.individual.checked==true){
    marcado = "true";
   }else{
    marcado = "false";
   }
   registrofunc += sep+marcado+";"+F.ed97_i_codigo.value+";"+F.ed97_i_rechumano.value+";"+F.ed97_i_faltaabon.value+";"+F.ed97_i_faltanjust.value+";"+F.ed97_i_horacinq.value+";"+F.ed97_i_horacem.value+";"+F.ed97_t_licenca.value+";"+F.ed97_t_obs.value;
  }else{
   for(i=0;i<linhas;i++){
    if(F.individual[i].checked==true){
     marcado = "true";
    }else{
     marcado = "false";
    }
    registrofunc += sep+marcado+";"+F.ed97_i_codigo[i].value+";"+F.ed97_i_rechumano[i].value+";"+F.ed97_i_faltaabon[i].value+";"+F.ed97_i_faltanjust[i].value+";"+F.ed97_i_horacinq[i].value+";"+F.ed97_i_horacem[i].value+";"+F.ed97_t_licenca[i].value+";"+F.ed97_t_obs[i].value;
    sep = "|";
   }
  }
  location.href = "edu1_efetividade001.php?ed98_c_tipo=<?=$ed98_c_tipo?>&efetividaderh=<?=$efetividaderh?>&registrofunc="+registrofunc+"&salvar";
 }
}
</script>