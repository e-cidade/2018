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

$clvac_calendario->rotulo->label();

function find_vacinadose($chave_vacina,$chave_dose,$rsVacinaDose) {

   for($iX = 0; $iX < pg_num_rows($rsVacinaDose); $iX++) {

     $oVacinaDose =  db_utils::fieldsmemory($rsVacinaDose,$iX);
     if (($chave_vacina == $oVacinaDose->codigo_vacina) && ($chave_dose == $oVacinaDose->codigo_dose)) {
       return $iX; 
     } 

   }
   return -1;

}

function verificaEstado($oCgs, $oVacinaDose) {

  $oDados->iEstado     = 0;
  $oDados->sIdadeIdeal = "";

  $aNasc               = explode("-", $oCgs->z01_d_nasc);
  $aVet                = explode("-", $oVacinaDose->faixa_final);

  /* Cálculo da data de vencimento (último dia em que é permitido tomar a vacina)*/
  $dVencimento         = somaDataDiaMesAno($aNasc[2], $aNasc[1], $aNasc[0],
                                           $aVet[0] + $oVacinaDose->atrasado,
                                           $aVet[1], $aVet[2]
                                          );
  $aIni                = explode("-", $oVacinaDose->faixa_inicial);
  $oDados->sIdadeIdeal = $aIni[2]." ano  ".$aIni[1]." mes  ".$aIni[0]." dia ";

  /* Cálculo do primeiro dia em que é possível tomar a vacina */
  $dInicio             = somaDataDiaMesAno($aNasc[2], $aNasc[1], $aNasc[0],
                                           $aIni[0] - $oVacinaDose->antecipado,
                                           $aIni[1], $aIni[2]
                                          );

  /* Verifica se a pessoa já podia ter tomado a vacina, ou seja, se a data atual
     é maior ou igual a data de início do periodo para a pessoa tomar a vacina */
  $aInicio             = explode('/', $dInicio);
  $aVencimento         = explode('/', $dVencimento);
  $aAtual              = explode(",",date("m,d,Y",db_getsession("DB_datausu")));
  $tAtual              = adodb_mktime(0, 0, 0, $aAtual[0], $aAtual[1], $aAtual[2]);
  $tVencimento         = adodb_mktime(0, 0, 0, $aVencimento[1], $aVencimento[0], $aVencimento[2]);
  $tInicio             = adodb_mktime(0, 0, 0, $aInicio[1], $aInicio[0], $aInicio[2]);

  if ($tAtual < $tInicio) {
    $oDados->iEstado = 1;
  } elseif ( ($tAtual > $tVencimento) && ($oVacinaDose->faixa_final != '0-0-0')) {
    $oDados->iEstado = 2;
  }
  
  if ($oVacinaDose->tipo_calculo == 3) {

    //seleciona ultima aplicação
    $oVacAplica       = db_utils::getdao('vac_aplica'); 
    $sSqlultimaaplica = $oVacAplica->sql_query_file("",
                                                    "vc16_d_dataaplicada",
                                                    " vc16_i_codigo desc",
                                                    " vc16_i_cgs=".$oCgs->z01_i_cgsund.
                                                    " and vc16_i_dosevacina=".$oVacinaDose->codigo
                                                   );
    $rsUtimaAplicada  = $oVacAplica->sql_record($sSqlultimaaplica);

    if ($oVacAplica->numrows > 0) {
      
      $oUltimaAplicada     = db_utils::fieldsmemory($rsUtimaAplicada, 0);
      $aAplicada           = explode("-", $oUltimaAplicada->vc16_d_dataaplicada);
      $aVet                = explode("-", $oVacinaDose->faixa_periodica);

      //Data aplicada mais o periodo até a proxima aplicação
      $dValiadeAplicacao   = somaDataDiaMesAno($aAplicada[2], $aAplicada[1], $aAplicada[0],
                                               $aVet[0] - $oVacinaDose->antecipado,
                                               $aVet[1], $aVet[2]
                                              );
      $aValAplicacao       = explode("/", $dValiadeAplicacao);

      //  (data ultima aplicalção + periodo) -> aaa-mm-d -> x dias  x mes  x ano
      $sDataIdeal          = calcage($aNasc[2], $aNasc[1], $aNasc[0], $aValAplicacao[0], 
                                     $aValAplicacao[1], $aValAplicacao[2], 2
                                    );
      $aDataIdeal          = explode(" || ",$sDataIdeal); 
      $oDados->sIdadeIdeal = $aDataIdeal[0]." ano  ".$aDataIdeal[1]." mes  ".$aDataIdeal[2]." ano ";

      //se o hoje for menor que a validade da aplicação então está adinatado 
      if ($tAtual < adodb_mktime(0, 0, 0, $aValAplicacao[1], $aValAplicacao[0], $aValAplicacao[2])) {
        $oDados->iEstado = 1;
      }

    }

  }
  if (($oVacinaDose->tipo_calculo == 2) && ($oVacinaDose->ordem > 1)) {

    //seleciona ultima aplicação da Dose anterior
    $oVacAplica       = db_utils::getdao('vac_aplica');
    $sSqlultimaaplica = $oVacAplica->sql_query2("",
                                                "vc16_d_dataaplicada,".
                                                " vc07_i_faixainidias||'-'||vc07_i_faixainimes||'-'".
                                                "||vc07_i_faixainiano as faixa_inicial, ".
                                                " vc07_i_faixafimdias||'-'||vc07_i_faixafimmes||'-'".
                                                "||vc07_i_faixafimano as faixa_final ",
                                                " vc16_i_codigo desc",
                                                " vc16_i_cgs=".$oCgs->z01_i_cgsund.
                                                " and vc07_i_vacina = ".$oVacinaDose->codigo_vacina.
                                                " and vc03_i_ordem = ".($oVacinaDose->ordem-1).
                                                " and not exists (select * from vac_aplicaanula ".
                                                " Where vc18_i_aplica = vac_aplica.vc16_i_codigo) "
                                               );
    $rsUtimaAplicada  = $oVacAplica->sql_record($sSqlultimaaplica);
    if ($oVacAplica->numrows > 0) {

      $oUltimaAplicada     = db_utils::fieldsmemory($rsUtimaAplicada, 0);

      //Calcula o timestamp da Data ideal da aplicação da dose anterior
      $aIniAnt             = explode("-", $oUltimaAplicada->faixa_inicial);
      $dIdealAnt           = somaDataDiaMesAno($aNasc[2], $aNasc[1], $aNasc[0],
                                               $aIniAnt[0], $aIniAnt[1], $aIniAnt[2]
                                              );
      $aIdealAnt           = explode("/", $dIdealAnt);
      $tIdealAnt           = adodb_mktime(0, 0, 0, $aIdealAnt[1], $aIdealAnt[0], $aIdealAnt[2]);

      //Calcula o timestamp da data que realmente foi aplicada a dose anterior
      $aDataAplicada       = explode("-",$oUltimaAplicada->vc16_d_dataaplicada);
      $tDataAplicada       = adodb_mktime(0, 0, 0, $aDataAplicada[1], $aDataAplicada[2], $aDataAplicada[0]);

      //Calcula a diferença da data da aplicação menos a data que deveria ser aplicada
      $tDif                = $tDataAplicada - $tIdealAnt;

      //Datada ideal atual diferença igual a data ideal em relação a ultima retirada 
      $tDataIdeal          = $tInicio + $tDif;
      $dDataIdeal          = adodb_date("d/m/Y", $tDataIdeal);
      $aDataIdeal          = explode("/", $dDataIdeal);
      $sDataIdeal          = calcage($aNasc[2], $aNasc[1], $aNasc[0], 
                                     $aDataIdeal[0], $aDataIdeal[1], $aDataIdeal[2], 2);
      $aDataIdeal          = explode(" || ", $sDataIdeal);
      $oDados->sIdadeIdeal = $aDataIdeal[0]." ano  ".$aDataIdeal[1]." mes  ".$aDataIdeal[2]." dia ";

      if ($tAtual < $tDataIdeal) {
        $oDados->iEstado = 1;
      } else if (($tAtual > ($tVencimento+$tDif)) && ($oVacinaDose->faixa_final != '0-0-0')) {
        $oDados->iEstado = 2;
      }

    }

  }

  return $oDados;
}


$sSql      = $clvac_calendario->sql_query($iCalendario);
$rsResult  = $clvac_calendario->sql_record($sSql);
if ($clvac_calendario->numrows > 0) {
  
  $oCalendario      = db_utils::fieldsmemory($rsResult,0);

  $sSql         = $clcgs_und->sql_query("","*",""," z01_i_cgsund=$iCgs ");
  $rsCgs        = $clcgs_und->sql_record($sSql);
  if ($clcgs_und->numrows > 0) {
    $oCgs = db_utils::fieldsmemory($rsCgs,0);
  } else {

    echo"<br><br><center><b>CGS($iCgs) não encontrado</b></center>";
    exit;

  }


  $sSqlultimaaplica = $clvac_aplica->sql_query_file("", "vc16_d_data", "vc16_i_codigo limit 1", 
                                                    " vc16_i_cgs=$iCgs and vc16_i_dosevacina=vc07_i_codigo ".
                                                    " and not exists (select * from vac_aplicaanula ".
                                                    " Where vc18_i_aplica = vac_aplica.vc16_i_codigo) ");
  $sSqlultimaaplicaCodigo = $clvac_aplica->sql_query_file("", "vc16_i_codigo", "vc16_i_codigo limit 1",
                                                          " vc16_i_cgs=$iCgs and vc16_i_dosevacina=vc07_i_codigo ".
                                                          " and not exists (select * from vac_aplicaanula ".
                                                          " Where vc18_i_aplica = vac_aplica.vc16_i_codigo) ");
  $sSqlrestricao    = $clvac_vacinadoserestricao->sql_query_file("", " vc08_i_codigo ", "", 
                                                                 " vc08_i_vacinadose = vc07_i_codigo limit 1");

  //Dados referente a vacina dose que serão utilizados no calculo do estado
  $sCampos      = " vc07_i_codigo             as codigo,";
  $sCampos     .= " vc07_i_dose               as codigo_dose,";
  $sCampos     .= " vc07_i_vacina             as codigo_vacina,";
  $sCampos     .= " vc07_c_nome               as nome,";
  $sCampos     .= " vc07_c_descr              as descr,";
  $sCampos     .= " vc07_i_tipocalculo        as tipo_calculo,";
  $sCampos     .= " ($sSqlultimaaplica)       as data_ultima_aplica,";
  $sCampos     .= " coalesce(($sSqlultimaaplicaCodigo),0) as codigo_ultima_aplica,";
  $sCampos     .= " vc07_i_faixainidias||'-'||vc07_i_faixainimes||'-'||vc07_i_faixainiano as faixa_inicial, ";
  $sCampos     .= " vc07_i_faixafimdias||'-'||vc07_i_faixafimmes||'-'||vc07_i_faixafimano as faixa_final, ";
  $sCampos     .= " vc07_i_sexo               as sexo, ";
  $sCampos     .= " vc07_n_quant              as quantidade, ";
  $sCampos     .= " vc07_i_diasantecipacao    as antecipado, ";
  $sCampos     .= " vc07_i_diasatraso         as atrasado, ";
  $sCampos     .= " vc14_i_faixadia||'-'||vc14_i_faixames||'-'||vc14_i_faixaano as faixa_periodica, ";
  $sCampos     .= " vc03_i_ordem              as ordem, ";
  $sCampos     .= " ($sSqlrestricao)          as restricao ";
  
  $sOrderby     = " vc06_i_codigo ";
  
  $sSexo="( vc07_i_sexo=3 or ";
  if ($oCgs->z01_v_sexo == 'F') {
    $sSexo .= " vc07_i_sexo=2 )";
  } else {
    $sSexo .= " vc07_i_sexo=1 )";
  }
  $sSql         = $clvac_vacinadose->sql_query2("",
                                                   $sCampos,
                                                   $sOrderby,
                                                   " vc07_i_calendario = $iCalendario and vc07_i_situacao=1 and $sSexo");
  $rsVacinaDose = $clvac_vacinadose->sql_record($sSql);
   
  $sSql         = $clvac_vacinadose->sql_query2("",
                                                   "distinct vac_vacina.*",
                                                   " vc06_i_orden ",
                                                   "vc07_i_calendario = $iCalendario");
  $rsVacina     = $clvac_vacina->sql_record($sSql);
  $iNumVacinas  = $clvac_vacina->numrows;
  
  $sSql         = $clvac_vacinadose->sql_query2("",
                                                   "distinct vac_dose.*",
                                                   " vc03_i_ordem ",
                                                   "vc07_i_calendario = $iCalendario");
  $rsDose       = $clvac_dose->sql_record($sSql);
  $iNumDoses    = $clvac_dose->numrows;

} else {

  echo"<br><br><center><b>Calendario($iCalendario) não encontrado</b></center>";
  exit;

}

?>
<fieldset style='width: 75%;'> <legend><b><?=$oCalendario->vc05_c_descr?></b></legend>
   <?
   if ($clvac_vacinadose->numrows == 0) {

     echo"<br><br><center><b>Calendario($iCalendario) sem Vacinas!</b></center>";
     exit;

   }
   ?>
   <table border='1px' bgcolor="#cccccc" style="" cellspacing="0px">
     <tr class='cabec' >
       <?
       echo"<td align=\"center\" class='cabec' > ".$oCalendario->vc05_i_idadeini." ano(s) até ".
            $oCalendario->vc05_i_idadefim." anos </td>";
       for ($iX = 0; $iX < $iNumDoses; $iX++) {
   
         $oDose = db_utils::fieldsmemory($rsDose,$iX);
         echo"<td align=\"center\" class='cabec'> ".$oDose->vc03_c_descr." </td>";
   
       }
       ?>
     </tr>
       <?
       for ($iY = 0; $iY < $iNumVacinas; $iY++) {

         $oVacina = db_utils::fieldsmemory($rsVacina,$iY);
         echo"<tr>";
         echo"<td class='cabec' >".$oVacina->vc06_c_descr."</td>";
         for ($iX = 0; $iX < $iNumDoses; $iX++) {
        
           $oDose  = db_utils::fieldsmemory($rsDose, $iX);
           $iChave = find_vacinadose($oVacina->vc06_i_codigo, $oDose->vc03_i_codigo, $rsVacinaDose);
           if ($iChave != -1) {
        
             $oVacinaDose  = db_utils::fieldsmemory($rsVacinaDose, $iChave);
             $sFontini     = "";
             $sFontfim     = "";
             $sCor         = "yellow";
             $sBlokeio     = "";
             
             $oSituacao    = verificaEstado($oCgs,$oVacinaDose);
             if ($oSituacao->iEstado == 1) {
               
               $sCor     = "green";
               $sBlokeio = "disabled";
        
             }
             if ($oSituacao->iEstado == 2) {
             
               $sCor     = "red";
               $sBlokeio = "disabled";
        
             }
             
             if ($oVacinaDose->restricao != "") {
        
               $sFontini = "<font color=\"white\">";
               $sFontfim = "</font>";
               $sCor     = "black";
        
             }
             if ($oVacinaDose->tipo_calculo != 3 && $oVacinaDose->data_ultima_aplica != "") {
        
               $sFontini = "<font color=\"white\">";
               $sFontfim = "</font>";
               $sCor     = "blue";
        
             }
             
             if ($oVacinaDose->data_ultima_aplica != "") {

               $iIframeOpcao = 2;
               $sBlokeio = "";

             } else {
               $iIframeOpcao = 1;
             }

             echo "<td align=\"center\" bgcolor=\"$sCor\">";
             echo "  <input type=\"button\" value=\"".$oSituacao->sIdadeIdeal.
                  "\" onclick=\"js_aplica($iCgs,$oVacinaDose->codigo,".
                  "$oVacinaDose->codigo_vacina,'$oVacinaDose->nome',$iIframeOpcao,$oVacinaDose->codigo_ultima_aplica)\" $sBlokeio >";

             if ($oVacinaDose->data_ultima_aplica != "") {

               $aVet = explode("-", $oVacinaDose->data_ultima_aplica);
               echo" $sFontini <br>Data ult.:".$aVet[2]."/".$aVet[1]."/".$aVet[0]."$sFontfim ";

             } else {
               echo" $sFontini <br>Data ult.: - - $sFontfim ";
             }
             echo"</td>";
        
           } else {
             echo"<td align=\"center\"> - - </td>";
           }

         }
         echo"</tr>";

       }   
       ?> 
   </table>
   <br>
   <table align="center" border="1">
     <tr align="center">
       <td bgcolor="red" width="60">Atrasada</td>
       <td bgcolor="yellow" width="60">Aptas</td>
       <td bgcolor="green" width="60">Adiantadas</td>
       <td bgcolor="black" width="60"><font color="white">Restritas</font></td>
       <td bgcolor="blue" width="60"><font color="white">Aplicadas</font></td>
     </tr>
   </table>
</fieldset>
<script>
function  js_aplica(iCgs,iVacinaDoseCodigo,iVacina,sVacinaDose,iIframeOpcao,iCodigoUltimaAplica) {
  
  sExtra  = "?vc16_i_cgs="+iCgs;
  sExtra += "&vc16_i_dosevacina="+iVacinaDoseCodigo;
  sExtra += "&iVacina="+iVacina;
  sExtra += "&sVacinaDose="+sVacinaDose;
  sExtra += "&db_opcao="+iIframeOpcao;
  if (iCodigoUltimaAplica != 0) {
    sExtra += "&chavepesquisa="+iCodigoUltimaAplica;
  }
  
  top     = ( screen.availHeight-700 ) / 2;
  left    = ( screen.availWidth-600 ) / 2;   
  js_OpenJanelaIframe("","db_iframe_aplica","vac4_aplica.iframe.php"+sExtra,"Aplicação",true,top, left, 450, 300);

}

function js_pesquisavc17_i_sala(mostra) {

  oFormFrame =  document.getElementById('IFdb_iframe_aplica').contentDocument.form1; 
  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_vac_sala', 'func_vac_sala.php?funcao_js=parent.js_mostravac_sala1|'+
                        'vc01_i_codigo|vc01_c_nome', 'Pesquisa', true
                       );

  } else {

    if (oFormFrame.vc17_i_sala.value != '') { 

      js_OpenJanelaIframe('', 'db_iframe_vac_sala', 'func_vac_sala.php?pesquisa_chave='+
                          oFormFrame.vc17_i_sala.value+'&funcao_js=parent.js_mostravac_sala', 
                          'Pesquisa', false
                        );

    } else {
       oFormFrame.vc01_c_nome.value = ''; 
     }

  }

}
function js_mostravac_sala(chave, erro) {

  oFormFrame                   =  document.getElementById('IFdb_iframe_aplica').contentDocument.form1;
  oFormFrame.vc01_c_nome.value = chave; 
  if (erro == true) { 

    oFormFrame.vc17_i_sala.focus(); 
    oFormFrame.vc17_i_sala.value = ''; 

  }

}
function js_mostravac_sala1(chave1, chave2) {

  oFormFrame.vc17_i_sala.value = chave1;
  oFormFrame.vc01_c_nome.value = chave2;
  db_iframe_vac_sala.hide();

}

function js_pesquisavc17_i_vacinalote(mostra) {

  oFormFrame =  document.getElementById('IFdb_iframe_aplica').contentDocument.form1; 
  if (mostra == true) {
    js_OpenJanelaIframe('', 'db_iframe_vac_vacinalote', 'func_vac_vacinalote.php?chave_vacina='+
                        oFormFrame.iVacina.value+'&funcao_js=parent.js_mostravac_vacinalote|m77_sequencial|'+
                        'm77_lote|m77_dtvalidade|dl_saldo|m61_descr', 'Pesquisa', true
                       );

  } else {

     if (oFormFrame.m77_lote.value != '') { 
       js_OpenJanelaIframe('', 'db_iframe_vac_vacinalote', 'func_vac_vacinalote.php?chave_vacina='+
                           oFormFrame.iVacina.value+'&chave_m77_lote='+oFormFrame.m77_lote.value+
                           '&nao_mostra=true&funcao_js=parent.js_mostravac_vacinalote|m77_sequencial|'+
                           'm77_lote|m77_dtvalidade|dl_saldo|m61_descr', 'Pesquisa', false
                          );

     } else {
       oFormFrame.vc01_c_nome.value = '';
     }

  }

}
function js_mostravac_vacinalote(sequencial, lote, validade, saldo, unidade) {

  oFormFrame =  document.getElementById('IFdb_iframe_aplica').contentDocument.form1;
  if (sequencial == '') {

    oFormFrame.vc17_i_matetoqueitemlote.value = '';
    oFormFrame.m77_lote.value                 = '';
    oFormFrame.saldo.value                    = '';
    oFormFrame.m77_dtvalidade.value           = '';
    oFormFrame.m61_descr.value                = '';

  } else {

    oFormFrame.vc17_i_matetoqueitemlote.value = sequencial;
    oFormFrame.m77_lote.value                 = lote;
    oFormFrame.saldo.value                    = saldo;
    aVet                                      = validade.split('-');
    oFormFrame.m77_dtvalidade.value           = aVet[2]+'/'+aVet[1]+'/'+aVet[0];
    oFormFrame.m61_descr.value                = unidade;

  }
  db_iframe_vac_vacinalote.hide();

}

function js_CancelaAplica(iAplica,iCgs) {
  
  top     = ( screen.availHeight-700 ) / 2;
  left    = ( screen.availWidth-600 ) / 2;
  sExtra  = "?iAplica="+iAplica+"&iCgs="+iCgs;   
  js_OpenJanelaIframe("","db_iframe_aplicaanula",
		                  "vac4_vac_aplicaanula.iframe.php"+sExtra,"Anular Aplicação",true,top, left, 450, 200);
  
}

function js_fechaAplicaanula(iReload) {

   db_iframe_aplicaanula.hide();
   if (iReload == 1) {
     js_fechaAplica(iReload);
   }

}

function js_fechaAplica(iReload) {

  db_iframe_aplica.hide();
  if (iReload == 1) {
    location.href = 'vac4_vac_grade004.php?iCalendario=<?=$iCalendario?>&iCgs=<?=$iCgs?>';
  }
}
</script>