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

try {

   if ( !db_utils::inTransaction() ) {
   	 throw new Exception("[ 5 ] - Nenhuma transação com o banco de dados encontrada!");
   }

   // Pega Numpre/Numpar
   $numpre = substr($arq_array[$i], substr($k15_numpre, 0, 3) - 1, substr($k15_numpre, 3, 3));
   $numpar = substr($arq_array[$i], substr($k15_numpar, 0, 3) - 1, substr($k15_numpar, 3, 3));

   echo "<script>js_termometro(".$i.");</script>";
   flush();

   $dia_venc = (int) substr($arq_array[$i], 56, 2);
   $mes_venc = (int) substr($arq_array[$i], 58, 2);
   $ano_venc = 2000 + (int) substr($arq_array[$i], 60, 2);

   $dtvenc = "$ano_venc-$mes_venc-$dia_venc";

   $clDisBancoTXT->k34_numpremigra = $numpre;
   $clDisBancoTXT->k34_valor       = $vlrpago;
   $clDisBancoTXT->k34_dtvenc      = $dtvenc;
   $clDisBancoTXT->k34_dtpago      = $dtpago;
   $clDisBancoTXT->k34_diferenca   = "0";
   $clDisBancoTXT->k34_codret      = $codret;
   $clDisBancoTXT->incluir(null);
   if ($clDisBancoTXT->erro_status == "0") {
     $sMsg  = "Operação Abortada!\\n";
     $sMsg .= "[ 1 ] - Erro incluindo registros na disbancotxt\\n";
     $sMsg .= "Erro: {$clDisBancoTXT->erro_msg}";
     throw new DBException($sMsg);
   }
   $k34_sequencial = $clDisBancoTXT->k34_sequencial;

   $funcaoNumpre = 'fc_numpre_daeb';
   $sql = "select $funcaoNumpre('$numpre') as ehnumpre";
   $res = db_query($sql);

   db_fieldsmemory($res, 0);

   // Verifica se eh ou nao um numpre do DBPortal
   if ($ehnumpre == 'f') {
     // procura antigo e grava na disbancotxt, disbancotxtreg, disbanco

     // Separa informacoes do Processamento antigo
     $matric = (int) substr($numpre, 0, 6);
     $exerc = (int) substr($numpre, 8, 2);
     $parc = (int) substr($numpre, 10, 2);

     $sqlarrecad = "select arrecad.k00_numpre,                                                                          ";
     $sqlarrecad .= "       arrecad.k00_numpar,                                                                         ";
     $sqlarrecad .= "       arrecad.k00_valor as k00_valor,                                                             ";
     $sqlarrecad .= "       round(fc_corre(arrecad.k00_receit, arrecad.k00_dtvenc, arrecad.k00_valor, '$dtvenc',           99999, arrecad.k00_dtvenc),2)::float8 as k00_vlrcor, ";
     $sqlarrecad .= "       round(fc_juros(arrecad.k00_receit, arrecad.k00_dtvenc, '$dtvenc',          arrecad.k00_dtoper, false, 99999)             ,2)::float8 as k00_vlrjur, ";
     $sqlarrecad .= "       round(fc_multa(arrecad.k00_receit, arrecad.k00_dtvenc, '$dtvenc',          arrecad.k00_dtoper, 99999)                    ,2)::float8 as k00_vlrmul  ";
     $sqlarrecad .= "  from arrecad                                                                                     ";
     $sqlarrecad .= "       inner join arrematric  on arrematric.k00_numpre = arrecad.k00_numpre                        ";
     $sqlarrecad .= "       inner join arreinstit  on arreinstit.k00_numpre = arrecad.k00_numpre                        ";
     $sqlarrecad .= "       inner join arretipo    on arretipo.k00_tipo     = arrecad.k00_tipo                          ";
     $sqlarrecad .= "                             and arretipo.k00_instit   = arreinstit.k00_instit                     ";
     $sqlarrecad .= " where arrematric.k00_matric = $matric                                                             ";
     $sqlarrecad .= "   and arreinstit.k00_instit = $iInstitSessao";

     $sqlarrecant  = " select arrecant.k00_numpre,                                                                      ";
     $sqlarrecant .= "        arrecant.k00_numpar,                                                                      ";
     $sqlarrecant .= "        arrecant.k00_valor as k00_valor,                                                          ";
     $sqlarrecant .= "        round(fc_corre(arrecant.k00_receit, arrecant.k00_dtvenc, arrecant.k00_valor, '$dtvenc',           99999, arrecant.k00_dtvenc),2)::float8 as k00_vlrcor,";
     $sqlarrecant .= "        round(fc_juros(arrecant.k00_receit, arrecant.k00_dtvenc, '$dtvenc',          arrecant.k00_dtoper, false, 99999)              ,2)::float8 as k00_vlrjur,";
     $sqlarrecant .= "        round(fc_multa(arrecant.k00_receit, arrecant.k00_dtvenc, '$dtvenc',          arrecant.k00_dtoper, 99999)                     ,2)::float8 as k00_vlrmul ";
     $sqlarrecant .= "   from arrecant                                                                                  ";
     $sqlarrecant .= "        inner join arrematric  on arrematric.k00_numpre = arrecant.k00_numpre                     ";
     $sqlarrecant .= "        inner join arreinstit  on arreinstit.k00_numpre = arrecant.k00_numpre                     ";
     $sqlarrecant .= "        inner join arretipo    on arretipo.k00_tipo     = arrecant.k00_tipo                       ";
     $sqlarrecant .= "                              and arretipo.k00_instit   = arreinstit.k00_instit                   ";
     $sqlarrecant .= "  where arrematric.k00_matric = $matric                                                           ";
     $sqlarrecant .= "    and arrecant.k00_dtvenc   = '$dtvenc'                                                         ";
     $sqlarrecant .= "    and arreinstit.k00_instit = $iInstitSessao";

     switch ($exerc) {
       // Divida Ativa
       case 66 :
         $sqlarrecad  .= " and arretipo.k03_tipo = 5 "; // Cadtipo = Divida Ativa
         $sqlarrecant .= " and arretipo.k03_tipo = 5 "; // Cadtopo = Divida Ativa

         if ($parc <> 0) {
           if ($parc > 80) {
             $ano = 1900 + $parc;
           } else {
             $ano = 2000 + $parc;
           }

           $sqlarrecad .= "and   extract(year from arrecad.k00_dtoper) = $ano ";
           $sqlarrecant .= "and   extract(year from arrecant.k00_dtoper) = $ano ";
         } else {
           $ano = 0;
         }
         break;

       // Parcelamento
       case 77 :
         $parc = ($parc == 0) ? 100 : $parc;

         $sqlarrecad  .= " and arretipo.k03_tipo = 6 and arrecad.k00_numpar = $parc ";
         $sqlarrecant .= " and arretipo.k03_tipo = 6 and arrecant.k00_numpar = $parc ";

         $ano = 0;
         $parc = 0;
         break;

       default :

         $anousu = (int) db_getsession("DB_anousu");

         if ($exerc > 80) {
           $ano = 1900 + $exerc;
         } else {
           $ano = 2000 + $exerc;
         }

         if ($ano == $anousu) {
           $sqlarrecad  .= "and arretipo.k03_tipo = 20 "; // Cadtipo Saneamento Básico
           $sqlarrecant .= "and arretipo.k03_tipo = 20 "; // Cadtipo Saneamento Básico
           $ano = $anousu;
         } else {
           $sqlarrecad  .= "and arretipo.k03_tipo = 5 "; // Cadtipo Divida
           $sqlarrecad  .= "and extract(year from arrecad.k00_dtoper) = $ano ";
           $sqlarrecant .= "and arretipo.k03_tipo = 5 "; // Cadtipo Divida
           $sqlarrecant .= "and extract(year from arrecant.k00_dtoper) = $ano ";
         }

         if ($parc > 0) {
           $sqlarrecad  .= " and arrecad.k00_numpar = $parc  ";
           $sqlarrecant .= " and arrecant.k00_numpar = $parc ";
         }

         break;

     }

     $sqltaxas = "select * from migra_stm070_taxas where matricula = $matric and ano = $ano and mes = $parc";
     $restaxas = db_query($sqltaxas) or die($sqltaxas);

     $sqlparc = "";

     for ($w = 0; $w < pg_num_rows($restaxas); $w++) {
       db_fieldsmemory($restaxas, $w);

       $tipo = ($cod_hist == 30) ? 6 : $cod_hist;

       $sqlparc .= " union
                    select arrecad.k00_numpre,
                           arrecad.k00_numpar,
                           arrecad.k00_valor,
                           round(fc_corre(arrecad.k00_receit, arrecad.k00_dtvenc, arrecad.k00_valor, '$dtvenc', 99999, arrecad.k00_dtvenc),2)::float8 as k00_vlrcor,
                           round(fc_juros(arrecad.k00_receit, arrecad.k00_dtvenc, '$dtvenc', arrecad.k00_dtoper, false, 99999),2)::float8 as k00_vlrjur,
                           round(fc_multa(arrecad.k00_receit, arrecad.k00_dtvenc, '$dtvenc', arrecad.k00_dtoper, 99999),2)::float8 as k00_vlrmul
                      from arrecad
                           inner join arrematric  on arrematric.k00_numpre = arrecad.k00_numpre
                           inner join arreinstit  on arreinstit.k00_numpre = arrecad.k00_numpre
                           inner join arretipo    on arretipo.k00_tipo     = arrecad.k00_tipo
                                                 and arretipo.k00_instit   = arreinstit.k00_instit
                     where arrematric.k00_matric = $matric
                       and arrecad.k00_tipo      = $tipo
                       and arrecad.k00_numpar    = $parcela
                       and arreinstit.k00_instit = $iInstitSessao ";

     }

     $sqlprocessa = "select x.k00_numpre,
                            x.k00_numpar,
                            sum(x.k00_valor)  as k00_valor,
                            sum(x.k00_vlrcor) as k00_vlrcor,
                            sum(x.k00_vlrjur) as k00_vlrjur,
                            sum(x.k00_vlrmul) as k00_vlrmul
                       from (".$sqlarrecad." union ".$sqlarrecant.$sqlparc.") x
                      group by x.k00_numpre, x.k00_numpar";
     $resprocessa = db_query($sqlprocessa);

     $soma = 0;

     for ($indx = 0; $indx < pg_num_rows($resprocessa); $indx ++) {
       db_fieldsmemory($resprocessa, $indx);

       // Proximo IDRET
       $result = db_query("select nextval('disbanco_idret_seq') as nextidret");
       db_fieldsmemory($result, 0);

       $valor_total = $k00_vlrcor + $k00_vlrjur + $k00_vlrmul;

       $soma += $valor_total;

      /**
       * Habilita variavel de sessao para permitir numpre's de outras instituições
       */
       permiteNumpreOutraInstituicao( true );

       $clDisBanco->codret     = $codret;
       $clDisBanco->k15_codbco = $k15_codbco;
       $clDisBanco->k15_codage = $k15_codage;
       $clDisBanco->k00_numbco = $numbco;
       $clDisBanco->dtarq      = $dtarq;
       $clDisBanco->dtpago     = $dtpago;
       $clDisBanco->dtcredito  = $dtcredito;
       $clDisBanco->vlrpago    = $valor_total;
       $clDisBanco->vlrjuros   = "0";
       $clDisBanco->vlrmulta   = "0";
       $clDisBanco->vlracres   = "0";
       $clDisBanco->vlrdesco   = "0";
       $clDisBanco->vlrcalc    = "0";
       $clDisBanco->cedente    = $cedente;
       $clDisBanco->vlrtot     = $valor_total;
       $clDisBanco->classi     = "false";
       $clDisBanco->k00_numpre = $k00_numpre;
       $clDisBanco->k00_numpar = "".($k00_numpar+0)."";
       $clDisBanco->convenio   = $convenio;
       $clDisBanco->instit     = $iInstitSessao;
       $clDisBanco->incluir(null);

       if ($clDisBanco->erro_status == "0") {
         $sMsg  = "Operação Abortada!\\n";
         $sMsg .= "[ 2 ] - Erro incluindo registros na disbanco\\n";
         $sMsg .= "Erro: {$clDisBanco->erro_msg}";
         throw new DBException($sMsg);
       }
       $idRet = $clDisBanco->idret;

       /**
        * Desabilita variavel de sessao para permitir numpre's de outras instituições
        */
       permiteNumpreOutraInstituicao( false );

       $clDisBancoTXTReg->k35_disbancotxt = $k34_sequencial;
       $clDisBancoTXTReg->k35_idret       = $idRet;
       $clDisBancoTXTReg->incluir(null);
       if ($clDisBancoTXTReg->erro_status == "0") {
         $sMsg  = "Operação Abortada!\\n";
         $sMsg .= "[ 3 ] - Erro incluindo registros na disbancotxtreg\\n";
         $sMsg .= "Erro: {$clDisBancoTXTReg->erro_msg}";
         throw new DBException($sMsg);
       }

     }

     // calcula diferenca do valor do TXT e valor encontrado
     $diferenca = $vlrpago - $soma;

     // Caso seja uma diferenca < 1.00 entao soma a ultima
     if (abs($diferenca) <= 1) {

       $clDisBanco->vlrpago = "vlrpago + $diferenca";
       $clDisBanco->vlrtot  = "vlrtot + $diferenca";
       $clDisBanco->idret   = $idRet;
       $clDisBanco->alterar($idRet);
       if($clDisBanco->erro_status == "0") {
         $sMsg  = "Operação Abortada!\\n";
         $sMsg .= "[ 4 ] - Erro alterando registros da Disbanco!\\n";
         $sMsg .= "Erro: {$clDisBanco->erro_msg}";
         throw new DBException($sMsg);
       }
     }

     // e casou houve alguma diferenca guarda na disbancotxt
     if ($diferenca <> 0) {

       // guarda diferenca na disbancotxt
       $clDisBancoTXT->k34_sequencial = $k34_sequencial;
       $clDisBancoTXT->k34_diferenca  = $diferenca;
       $clDisBancoTXT->alterar($k34_sequencial);
       if($clDisBancoTXT->erro_status == "0") {
         $sMsg  = "Operação Abortada!\\n";
         $sMsg .= "[ 5 ] - Erro alterando registros da DisbancoTxt!\\n";
         $sMsg .= "Erro: {$clDisBancoTXT->erro_msg}";
         throw new DBException($sMsg);
       }

     }

   } else {

     $k00_numpre = (int) $numpre;
     $sqlwork  = "select k00_numpre_dst ";
     $sqlwork .= "  from work_arreinstit ";
     $sqlwork .= " where k00_numpre_ori = {$k00_numpre} ";

     $rsWork = db_query($sqlwork);

     if(pg_numrows($rsWork)>0) {
       db_fieldsmemory($rsWork, 0);

       $k00_numpre = $k00_numpre_dst;
     } else {
       $k00_numpre = (int) $numpre;
     }

     $sql = "select * from recibopaga where k00_numnov = $k00_numpre";
     $res = db_query($sql);

     if (pg_num_rows($res) > 0) {
       $numpar = 0;
     }

     /**
      * Habilita variavel de sessao para permitir numpre's de outras instituições
      */
     permiteNumpreOutraInstituicao( true );

     $clDisBanco->codret     = $codret;
     $clDisBanco->k15_codbco = $k15_codbco;
     $clDisBanco->k15_codage = $k15_codage;
     $clDisBanco->k00_numbco = $numbco;
     $clDisBanco->dtarq      = $dtarq;
     $clDisBanco->dtpago     = $dtpago;
     $clDisBanco->dtcredito  = $dtcredito;
     $clDisBanco->vlrpago    = $vlrpago;
     $clDisBanco->vlrjuros   = "0";
     $clDisBanco->vlrmulta   = "0";
     $clDisBanco->vlracres   = "0";
     $clDisBanco->vlrdesco   = "0";
     $clDisBanco->vlrcalc    = "0";
     $clDisBanco->cedente    = $cedente;
     $clDisBanco->vlrtot     = $vlrpago;
     $clDisBanco->classi     = "false";
     $clDisBanco->k00_numpre = $k00_numpre;
     $clDisBanco->k00_numpar = "".($numpar+0)."";
     $clDisBanco->convenio   = $convenio;
     $clDisBanco->instit     = $iInstitSessao;
     $clDisBanco->incluir(null);
     if ($clDisBanco->erro_status == "0") {
       $sMsg  = "Operação Abortada!\\n";
       $sMsg .= "[ 6 ] - Erro incluindo registros na disbanco\\n";
       $sMsg .= "Erro: {$clDisBanco->erro_msg}";
       throw new DBException($sMsg);
     }
     $idRet = $clDisBanco->idret;

     /**
       * Desabilita variavel de sessao para permitir numpre's de outras instituições
       */
      permiteNumpreOutraInstituicao( false );

     $clDisBancoTXTReg->k35_disbancotxt = $k34_sequencial;
     $clDisBancoTXTReg->k35_idret       = $idRet;
     $clDisBancoTXTReg->incluir(null);
     if ($clDisBancoTXTReg->erro_status == "0") {
       $sMsg  = "Operação Abortada!\\n";
       $sMsg .= "[ 7 ] - Erro incluindo registros na disbancotxtreg\\n";
       $sMsg .= "Erro: {$clDisBancoTXTReg->erro_msg}";
       throw new DBException($sMsg);
     }

   }

} catch (DBException $eErro){          // DB Exception

  throw new DBException($eErro->getMessage());

} catch (BusinessException $eErro){     // Business Exception

  throw new BusinessException($eErro->getMessage());

} catch (ParameterException $eErro){     // Parameter Exception

  throw new ParameterException($eErro->getMessage());

} catch (Exception $eErro){

  throw new Exception($eErro->getMessage());
}