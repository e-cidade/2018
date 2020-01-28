<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("fpdf151/pdfwebseller2.php");
require_once("libs/db_sql.php");
require_once("classes/db_prontuariomedico_classe.php");
require_once("classes/db_prontcid_classe.php");
require_once("classes/db_cgs_und_classe.php");
require_once("classes/db_prontuarios_classe.php");
require_once("classes/db_prontproced_ext_classe.php");
require_once('libs/db_utils.php');

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

set_time_limit(0);
$oDaoProntuarios            = db_utils::getdao('prontuarios');
$oDaoProntproced            = db_utils::getdao('prontproced_ext');
$oDaoProntcid               = db_utils::getdao('prontcid');
$oDaoProntuariomedico       = db_utils::getdao('prontuariomedico');
$oDaoCgsUnd                 = db_utils::getdao('cgs_und');
$oDaoSauReceitaMedica       = db_utils::getdao('sau_receitamedica');
$oDaoSauMedicamentosReceita = db_utils::getdao('sau_medicamentosreceita');

//Seleciona os dados
$sWhere = '';
$sSep   = '';
if (isset($dIni) && !empty($dIni) && isset($dFim) && !empty($dFim)) {

  $sWhere = "sd29_d_data between '".formataData($dIni)."' and '".formataData($dFim)."'";
  $sSep   = ' and ';

} elseif (isset($dFim) && !empty($dFim)) {

  $sWhere = "sd29_d_data <= '".formataData($dFim)."'";
  $sSep   = ' and ';

}

$sSql      = $oDaoCgsUnd->sql_query($cgs);
$result    = $oDaoCgsUnd->sql_record($sSql);
$oDadosCGS = db_utils::fieldsmemory($result, 0);

$sCampos   = "*, a.z01_nome as profissional";
$sSql      = $oDaoProntuariomedico->sql_query("", $sCampos, "sd32_d_atendimento desc", "sd32_i_numcgs = $cgs" );
$query     = $oDaoProntuariomedico->sql_record($sSql);
$linhas    = $oDaoProntuariomedico->numrows;

$sCampos   = '*, sau_cid.sd70_c_cid, sau_cid.sd70_c_nome, sau_procedimento.sd63_c_procedimento, ';
$sCampos  .= 'sau_procedimento.sd63_c_nome, sd29_t_tratamento, m.z01_nome as profissional';
$sWhere1   = "sd24_i_numcgs = $cgs $sSep $sWhere";
$sSql      = $oDaoProntproced->sql_query_prontuario('', $sCampos, 'sd29_d_data desc ', $sWhere1);
$query1    = $oDaoProntproced->sql_record($sSql);
$linhas1   = $oDaoProntproced->numrows;

if($linhas == 0 && $linhas1 == 0 ){

 echo "<table width='100%'>";
 echo "  <tr>";
 echo "  <td align='center'>";
 echo "  <font color='#FF0000' face='arial'>";
 echo "  <b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b>";
 echo "  </font>";
 echo "  </td>";
 echo " </tr>";
 echo "</table>";
 exit;
 
}


$pdf = new pdf();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "                                  Prontuário Eletrônico";
if (isset($dIni) && !empty($dIni) && isset($dFim) && !empty($dFim)) {
  $head4 = "Período: $dIni a $dFim";
} elseif (isset($dFim) && !empty($dFim)) {
  $head4 = "Período: até $dFim";
}
$head5 = "Família......: ".$oDadosCGS->sd33_v_descricao;
$head7 = "Micro Área:  ".$oDadosCGS->sd34_v_descricao;

//Pré Condições do For
$pri          = true;
$retorna_obs  = 0;
$ec_cgs       = array("1"=>"Solteiro",
                      "2"=>"Casado",
                      "3"=>"Viúvo",
                      "4"=>"Separado Judicialmente",
                      "5"=>"União Consensual",
                      "9"=>"Ignorado"
                     );

$sexo_cgs     = array("F"=>"Feminino", "M"=>"Masculino");
$altura       = 3;
$borda        = false;
$espaco       = 2;
$preenche     = 0;
$naousaespaco = true;
$usar_quebra  = true;
$campo_testar = 2;
$lagurafixa   = 0;
cabecalho($pdf,$result,$pri);
$pdf->SetWidths(array(20,45,127));
$pdf->SetAligns(array("C","L","L"));

//Percorre todos os registroa na 'prontproced' para o CGS escolhido
for ($p=0; $p < $linhas1; $p++) {

   $xlin=$pdf->Gety();
   $pdf->rect(10,$xlin,192,0, 2, 'DF', '12');

   if ($retorna_obs == 0) {

      db_fieldsmemory($query1, $p);
      $pdf->setfont('arial', '', 7);
      $pri                = false;
      $nbx                = "";
      $sd29_i_prontuario_ = $sd29_i_prontuario; 
      $sd29_t_tratamento_ = '';
      while ($sd29_i_prontuario_ == $sd29_i_prontuario) {

        $sd29_t_tratamento_ .= "\n".$sd29_t_tratamento; 
        $p++;
        if ($p == $linhas1) {
          break;
        }  
        db_fieldsmemory($query1, $p);

      }
      
      if ($p > $linhas1) {
        break;
      }  
      $p--;
      db_fieldsmemory($query1,$p);

      $sd70_c_nome_ = trim("    Principal : ".$sd70_c_cid." - ".$sd70_c_nome);
      $sCampos      = "sd70_c_cid, sd70_c_nome, sd55_b_principal";
      $sWhere       = "sd55_b_principal = 'f' and prontcid.sd55_i_prontuario = $sd29_i_prontuario";
      $sSql         = $oDaoProntcid->sql_query("", $sCampos, "", $sWhere); 
      $query3       = $oDaoProntcid->sql_record($sSql);
      $iQuanttCid   = $oDaoProntcid->numrows;
      for ($p1=0; $p1 < $iQuanttCid; $p1++) {

        db_fieldsmemory($query3, $p1);
        $sd70_c_nome_ .= "\n                                          ".trim($sd70_c_cid." - ".$sd70_c_nome);

      }
      $sd29_d_data        = substr($sd29_d_data,8,2)."/".substr($sd29_d_data,5,2)."/".substr($sd29_d_data,0,4);
      $sd29_t_tratamento_ = trim(stripslashes($sd29_t_tratamento_));

      if ($sd24_v_pressao == 0){
        $sd24_v_pressao = '';
      }
      if ($sd24_f_temperatura == 0){
        $sd24_f_temperatura = '';
      }
      if ($sd24_f_peso == 0){
        $sd24_f_peso = '';
      }
      
      if ($s152_i_cintura == 0){
        $s152_i_cintura = '';
      }
      if (isset($s152_i_pressaosistolica) && !empty($s152_i_pressaosistolica)) {
        $sPressao = $s152_i_pressaosistolica.'/'.$s152_i_pressaodiastolica;
      } else {
        $sPressao = $sd24_v_pressao;
      }
      if (isset($s152_n_temperatura) && !empty($s152_n_temperatura)) {

        $aTmp         = explode('.', $s152_n_temperatura);
        $sTemperatura = $aTmp[0].','.$aTmp[1][0];

      } else {
        $sTemperatura = $sd24_f_temperatura;
      }

      if (isset($s152_n_peso) && !empty($s152_n_peso)) {

        $aTmp  = explode('.', $s152_n_peso);
        $sPeso = $aTmp[0].','.$aTmp[1][0];

      } else {
        $sPeso = $sd24_f_peso;
      }

      $iIMC = '';

      if( ($sPeso > 0 && $sPeso != '') && ($s152_i_altura > 0 && $s152_i_altura != '')  ){
        $iIMC = $sPeso / ($s152_i_altura * $s152_i_altura / 10000); 
      }

      $sConteudo  = "FAA.............: $sd29_i_prontuario Lote: $sd58_i_codigo ";
      $sConteudo .= "\nUPS.............: $sd02_i_codigo - $descrdepto ";
      $sConteudo .= "\nMotivo..........: ".$s144_c_descr;
      $sConteudo .= "\nPressão.......: ".$sPressao;
      $sConteudo .= "  Temperatura: ".$sTemperatura;
      $sConteudo .= "  Cintura: ".$s152_i_cintura;
      $sConteudo .= "  Peso: ".$sPeso;
      $sConteudo .= "  Altura: ".$s152_i_altura;
      $sConteudo .= "  IMC: ".( $iIMC == ''? '' : trim(db_formatar($iIMC,'f'))   );
      $sConteudo .= "  Glicemia: ".$s152_i_glicemia;
      $sConteudo .= "\n---------------------------------------------------------------------------------------------";
      $sConteudo .= "---------------------------------------------------------";
      if (db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 8813) == 'true') {
        $sConteudo .= "\nDiagnóstico...: ".$sd24_t_diagnostico;
      }
      //For que ira imprimir varios registros de atendimento
      $sCampos  = 'sd63_c_procedimento,';
      $sCampos .= 'sd63_c_nome,';
      $sCampos .= 'sd70_c_cid,';
      $sCampos .= 'sd70_c_nome,';
      $sCampos .= 'sd03_i_codigo,';
      $sCampos .= 'z01_nome,';
      $sCampos .= 'sd29_d_data,';
      $sCampos .= 'sd29_c_hora,';
      $sCampos .= 'sd29_t_tratamento';
      $sSql = $oDaoProntproced->sql_query_consulta_geral(null, $sCampos, null,"sd29_i_prontuario = $sd24_i_codigo");
      $rs   = $oDaoProntproced->sql_record($sSql);
      for($iCont = 0; $iCont < $oDaoProntproced->numrows; $iCont++){

        $oDados     = db_utils::fieldsmemory($rs, $iCont, true);
        $sConteudo .= "\nAtendimento..: ".$oDados->sd29_d_data." Hora:".$oDados->sd29_c_hora;
        $sConteudo .= "\nProfissional...: ".$oDados->sd03_i_codigo." - ".$oDados->z01_nome;
        if (db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 8813) == 'true') {
          $sConteudo .= "\nCID................: ".$oDados->sd70_c_cid." - ".$oDados->sd70_c_nome;
        }
        $sConteudo .= "\nProcedimento: ".$oDados->sd63_c_procedimento." - ".str_pad($oDados->sd63_c_nome,60);
        $sConteudo .= "\nExecutado.....: ".$oDados->sd29_t_tratamento."\n";

      }
      $sConteudo .= "\n---------------------------------------------------------------------------------------------";
      $sConteudo .= "---------------------------------------------------------";
      $sCampos    = 'm60_descr,';
      $sCampos   .= 's158_i_codigo,';
      $sCampos   .= 'fa03_c_descr,';
      $sCampos   .= 's158_d_validade,';
      $sCampos   .= 's160_c_descr,';
      $sCampos   .= 's159_t_posologia,';
      $sCampos   .= 's159_n_quant';
      $sWhere     = " s162_i_prontuario = $sd24_i_codigo";
      $sSql       = $oDaoSauReceitaMedica->sql_query_medicamentos(null, $sCampos, null, $sWhere);
      $rs         = $oDaoSauReceitaMedica->sql_record($sSql);
      if ($oDaoSauReceitaMedica->numrows > 0) {

       $oDados     = db_utils::fieldsmemory($rs, 0, true);
       $sConteudo .= "\nReceita.......: ".$oDados->s158_i_codigo;
       $sConteudo .= "Tipo Receita....: ".$oDados->fa03_c_descr;
       $sConteudo .= "Validade........: ".$oDados->s158_d_validade;
       $sConteudo .= "\nMedicamento                         Forma de Administração";
       $sConteudo .= '           Posologia                         Qtde.';
       $sConteudo .= "\n".substr(str_pad($oDados->m60_descr,30),0,30).'   ';
       $sConteudo .= substr(str_pad($oDados->s160_c_descr,20,'K'),0,20);
       $sConteudo .= '   '.substr(str_pad($oDados->s159_t_posologia,20,'K'),0,20);
       $sConteudo .= '   '.substr(str_pad($oDados->s159_n_quant,10,'K'),0,10);

      }

      $data = array("$sd29_d_data",
                    "$profissional \n\nAssinatura :\n", 
                    $sConteudo
                    );

   }else{

     $sd29_t_tratamento_ = $descricaoitemimprime;
     $retorna_obs        = 0;
     $data               = array("$sd24_d_cadastro  $sd24_c_cadastro",
                                 "$profissional \n\nAssinatura :\n", 
                                 "\n$sd29_t_tratamento_"
                                );

   }
   $pdf->Setfont('Arial', '', 7);
   $set_altura_row       = $pdf->h - 30;
   $descricaoitemimprime = $pdf->Row_multicell($data,
                                               $altura,
                                               $borda,
                                               $espaco,
                                               $preenche,
                                               $naousaespaco,
                                               $usar_quebra,
                                               $campo_testar,
                                               $set_altura_row,
                                               $lagurafixa
                                              );
    if (trim($descricaoitemimprime) != "") {

      $retorna_obs = 1;
      $xlin        = $pdf->Gety();
      $p--;
      $pdf->text(120, $xlin+15, "Continua na próxima página ");

    }
    cabecalho($pdf,$result,$pri);

}  
 
//for de prontuariomedico
$retorna_obs = 0;
for($x=0; $x < $linhas; $x++){

   $xlin=$pdf->Gety();
   $pdf->setfont('arial', '', 7);
   $pdf->rect(10, $xlin, 192, 0,  2, 'DF', '12');

   if ($retorna_obs == 0) {

     db_fieldsmemory($query, $x);
     $pri                = false;
     $z01_d_nasc         = substr($z01_d_nasc,8,2)."/".substr($z01_d_nasc,5,2)."/".substr($z01_d_nasc,0,4);
     $sd32_d_atendimento = substr($sd32_d_atendimento,8,2)."/".substr($sd32_d_atendimento,5,2)."/".
                           substr($sd32_d_atendimento,0,4);

   } else {

      $sd32_t_descricao = $descricaoitemimprime;
      $retorna_obs = 0;

   }
   $data = array("$sd32_d_atendimento $sd32_c_horaatend",
                 "$profissional \n\nAssinatura :\n", 
                 "\nExecutado        ".$sd32_t_descricao);
   $pdf->Setfont('Arial', '', 7);

   $set_altura_row = $pdf->h - 30;

   $descricaoitemimprime = $pdf->Row_multicell($data,
                                               $altura,
                                               $borda,
                                               $espaco,
                                               $preenche,
                                               $naousaespaco,
                                               $usar_quebra,
                                               $campo_testar,
                                               $set_altura_row,
                                               $lagurafixa);
    if (trim($descricaoitemimprime) != "") {

      $retorna_obs = 1;
      $x--;
      $xlin=$pdf->Gety();
      $pdf->text(120,$xlin+15,"Continua na próxima página ");

    }
    cabecalho($pdf,$result,$pri);

}


  $pdf->Output(); 

  function cabecalho($pdf, $result, $pri) {

    global $z01_i_numcgs,$z01_v_nome,$z01_v_sexo,$z01_d_nasc,$z01_c_naturalidade,$z01_v_pai,$z01_v_mae,
           $z01_v_ender,$z01_i_numero,$z01_v_compl;
    global $z01_v_telef,$z01_v_bairro,$ec,$z01_v_ident,$z01_c_cartaosus,$ec_cgs,$z01_i_estciv,$sexo_cgs,$z01_v_sexo;

    if (  ($pdf->gety() > $pdf->h -30) || $pri){
    	  db_fieldsmemory($result,0);
          $pdf->addpage();
          $pdf->header();
          $pdf->setfillcolor(235);
          $pdf->setfont('arial','b',12);
		      $pdf->roundedrect(10,43,192,24.5,2,'DF','1234');
          $pdf->cell(192,8,"PRONTUÁRIO ELETRÔNICO",0,1,"C",0);
          $pdf->setfont('arial','b',7);
          $pdf->cell(30,4,"Nome : ",0,0,"R",0);
          $pdf->cell(66,4,$z01_i_numcgs."-".trim($z01_v_nome),0,0,"L",0);
          $pdf->cell(30,4,"Sexo :",0,0,"R",0);
          $pdf->cell(66,4,@$sexo_cgs[$z01_v_sexo],0,1,"L",0);
          $pdf->cell(30,4,"Data de Nasc : ",0,0,"R",0);
          $pdf->cell(66,4,db_formatar($z01_d_nasc,'d'),0,0,"T",0);
          $pdf->cell(30,4,"Munic. Nasc : ",0,0,"R",0);
          $pdf->cell(66,4,$z01_c_naturalidade,0,1,"L",0);
          $pdf->cell(30,4,"Nome do Pai : ",0,0,"R",0);
          $pdf->cell(66,4,$z01_v_pai,0,0,"L",0);
          $pdf->cell(30,4,"Nome da Mãe : ",0,0,"R",0);
          $pdf->cell(66,4,$z01_v_mae,0,1,"L",0);
          $pdf->cell(30,4,"Endereço : ",0,0,"R",0);
          $pdf->cell(66,4,$z01_v_ender.", ".$z01_i_numero.", ".$z01_v_compl,0,0,"L",0);
          $pdf->cell(30,4,"Telefone : ",0,0,"R",0);
          $pdf->cell(66,4,$z01_v_telef,0,1,"L",0);
          $pdf->cell(30,4,"Bairro : ",0,0,"R",0);
          $pdf->cell(66,4,$z01_v_bairro,0,0,"L",0);
          $pdf->cell(30,4,"Estado Civil : ",0,0,"R",0);
          $pdf->cell(66,4,@$ec_cgs[$z01_i_estciv],0,1,"L",0);
          $pdf->cell(30,4,"Doc. RG : ",0,0,"R",0);
          $pdf->cell(66,4,$z01_v_ident,0,0,"L",0);
          $pdf->cell(30,4,"Cartão SUS : ",0,0,"R",0);
          $pdf->cell(66,4,$z01_c_cartaosus,0,1,"L",0);
          $head1 = str_pad("Continuação", 50, " ", STR_PAD_LEFT);
          $head7 = "Nome: ".$z01_i_numcgs."-".$z01_v_nome;

          $pdf->cell(30,8,"IMUNIZAÇÃO: ",0,0,"L",0);
          $pdf->cell(162,8,"C.N.S.: ",0,1,"L",0);

          $pdf->cell(20,4,"DATA",1,0,"C",1);
//          $pdf->cell(20,4,"HORA",1,0,"C",1);
          $pdf->cell(45,4,"PROFISSIONAL",1,0,"C",1);
          $pdf->cell(127,4,"CONSULTAS - VISITAS - EXAMES DE LABORATÓRIO - PRESCRIÇÕES",1,1,"C",1);
//        $pdf->cell(17,4,"ASSINATURA",1,1,"C",1);
          $pdf->Setfont('Arial', 'B', 7);
          $xcol = 10;
          $xlin = 21;
          $pdf->rect($xcol, $xlin +54, 20, 210, 2, 'DF', '12');
          $pdf->rect($xcol +20, $xlin +54, 45, 210, 2, 'DF', '12');
          $pdf->rect($xcol +65, $xlin +54, 127, 210, 2, 'DF', '12');
          
          $pri = false;
          
    }
  }

function formataData($dData, $iTipo = 1) {

  if (empty($dData)) {
    return '';
  }

  if ($iTipo == 1) {

    $dData = explode('/', $dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
  $dData = explode('-', $dData);
  $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];

  return $dData;

}

?>