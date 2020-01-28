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

require_once("fpdf151/pdfwebseller.php");
require_once("libs/db_utils.php");
$oDaoEfetividade   = db_utils::getdao('efetividade');
$oDaoEfetividadeRh = db_utils::getdao('efetividaderh');
$sSql              = $oDaoEfetividadeRh->sql_query_file("",  "*", "ed98_d_datafim desc, ed98_c_tipo", 
                                                        "    ed98_i_codigo in ($sRegistros)"
                                                       );
$rs                = $oDaoEfetividadeRh->sql_record($sSql);

if ($oDaoEfetividadeRh->numrows == 0) {
	
  echo " <table width='100%'>";
  echo "  <tr>";
  echo "   <td align='center'>";
  echo "    <font color='#FF0000' face='arial'>";
  echo "     <b>Nenhum registro encontrado<br>";
  echo "     <input type='button' value='Fechar' onclick='window.close()'></b>";
  echo "    </font>";
  echo "   </td>";
  echo "  </tr>";
  echo " </table>";
  exit;
  
}

$oPdf = new Pdf();
$oPdf->Open();
$oPdf->AliasNbPages();
for ($iCont = 0; $iCont < $oDaoEfetividadeRh->numrows; $iCont++) {
	
  $oDadosEfetvRh = db_utils::fieldsmemory($rs, $iCont);
  $head1         = "RELATÓRIO DE EFETIVIDADE";
  $head2         = "Tipo de Efetividade: ".(trim($oDadosEfetvRh->ed98_c_tipo) == "P" ? "PROFESSORES":"FUNCIONÁRIOS");
  $head3         = "Tipo de Competência: ".(trim($oDadosEfetvRh->ed98_c_tipocomp) == "M" ? "MENSAL":"PERIÓDICA");
  
  if (trim($oDadosEfetvRh->ed98_c_tipocomp) == "M") {
    $head4 = "Mês/Ano: ".db_mes($oDadosEfetvRh->ed98_i_mes, 1)." / ".$oDadosEfetvRh->ed98_i_ano;
  } else {
    $head4 = db_formatar($oDadosEfetvRh->ed98_d_dataini, 'd')." à ".db_formatar($oDadosEfetvRh->ed98_d_datafim, 'd');
  }
  
  $lTroca   = true;  
  $lCor     = true;
  $sCampos  = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,  ";
  $sCampos .= " case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal ";
  $sCampos .= " else rechumanocgm.ed285_i_cgm end as identificacao,  efetividade.*";
  $sWhere   = " ed97_i_efetividaderh = $oDadosEfetvRh->ed98_i_codigo AND ed20_c_efetividade = 'S'";
  $sSqlEf   = $oDaoEfetividade->sql_query("", $sCampos, "z01_nome", $sWhere);
  $rsEf     = $oDaoEfetividade->sql_record($sSqlEf);
  
  for ($iContEf = 0; $iContEf < $oDaoEfetividade->numrows; $iContEf++) {
  	
    $oDadosEfetividade = db_utils::fieldsmemory($rsEf, $iContEf);
    
    if (($oPdf->gety() > $oPdf->h - 30 || $lTroca != 0)) {
    	
      $iPosY = $oPdf->getY();
      
      if ($iContEf != 0) {
      	
        $oPdf->line(10, $iPosY, 290, $iPosY);
        
         if (trim($oDadosEfetvRh->ed98_c_tipo) == "P") {
         	
           $oPdf->line(25, 48, 25, $iPosY);
           $oPdf->line(85, 48, 85, $iPosY);
           $oPdf->line(105, 48, 105, $iPosY);
           $oPdf->line(125, 48, 125, $iPosY);
           $oPdf->line(145, 48, 145, $iPosY);
           $oPdf->line(185, 48, 185, $iPosY);
           $oPdf->line(235, 48, 235, $iPosY);
           
        } else {
        	
          $oPdf->line(25, 48, 25, $iPosY);
          $oPdf->line(95, 48, 95, $iPosY);
          $oPdf->line(115, 48, 115, $iPosY);
          $oPdf->line(135, 48, 135, $iPosY);
          $oPdf->line(160, 48, 160, $iPosY);
          $oPdf->line(185, 48, 185, $iPosY);
          $oPdf->line(235, 48, 235, $iPosY);
          
        }
        
      }
      
      $oPdf->addpage('L');
      $oPdf->ln(5);
      $oPdf->setfillcolor(210);
      $oPdf->setfont('arial', 'B', 8);
      $iPosY = $oPdf->getY();
      
      if (trim($oDadosEfetvRh->ed98_c_tipo) == "P") {
      	
        $oPdf->cell(15, 8, "Matr./CGM", 1, 0, "C", 1);
        $oPdf->cell(60, 8, "Nome", 1, 0, "C", 1);
        $oPdf->cell(20, 8, "Dias Letivos", 1, 0, "C", 1);
        $oPdf->cell(20, 4, "Faltas", "LRT", 2, "C", 1);
        $oPdf->cell(20, 4, "Abonadas", "LRB", 0, "C", 1);
        $oPdf->setY($iPosY);
        $oPdf->setX(125);
        $oPdf->cell(20, 4, "Faltas Não", "LRT", 2, "C", 1);
        $oPdf->cell(20, 4, "Justificadas", "LRB", 0, "C", 1);
        $oPdf->setY($iPosY);
        $oPdf->setX(145);
        $oPdf->cell(40, 8, "Horário", 1, 0, "C", 1);
        $oPdf->cell(50, 8, "Licença Saúde", 1, 0, "C", 1);
        $oPdf->cell(55, 8, "Observações", 1, 1, "C", 1);
        
      } else {
      	
        $oPdf->cell(15, 8, "Matr./CGM", 1, 0, "C", 1);
        $oPdf->cell(70, 8, "Nome", 1, 0, "C", 1);
        $oPdf->cell(20, 4, "Faltas", "LRT", 2, "C", 1);
        $oPdf->cell(20, 4, "Abonadas", "LRB", 0, "C", 1);
        $oPdf->setY($iPosY);
        $oPdf->setX(115);
        $oPdf->cell(20, 4, "Faltas Não", "LRT", 2, "C", 1);
        $oPdf->cell(20, 4, "Justificadas", "LRB", 0, "C", 1);
        $oPdf->setY($iPosY);
        $oPdf->setX(135);
        $oPdf->cell(25, 8, "Hora Extra 50%", 1, 0, "C", 1);
        $oPdf->cell(25, 8, "Hora Extra 100%", 1, 0, "C", 1);
        $oPdf->cell(50, 8, "Licença Saúde", 1, 0, "C", 1);
        $oPdf->cell(55, 8, "Observações", 1, 1, "C", 1);
        
      } 
      
      $lTroca = false;
      
    }
    
    if ($lCor) {    	
      $lCor = false;
    } else{
      $lCor = true;
    }
    $oPdf->setfillcolor(240);
    $oPdf->setfont('arial', '', 7);
    if (trim($oDadosEfetvRh->ed98_c_tipo) == "P") {
    	
      $oPdf->cell(15, 4, $oDadosEfetividade->identificacao, "T", 0, "C", $lCor);
      $oPdf->cell(60, 4, $oDadosEfetividade->z01_nome, "T", 0, "L", $lCor);
      $oPdf->cell(20, 4, $oDadosEfetividade->ed97_i_diasletivos == 0 ? "-" : 
                  $oDadosEfetividade->ed97_i_diasletivos, "T", 0, "C", $lCor
                 );
      $oPdf->cell(20, 4, $oDadosEfetividade->ed97_i_faltaabon == 0 ? "-" :
                  $oDadosEfetividade->ed97_i_faltaabon, "T", 0, "C", $lCor
                 );
      $oPdf->cell(20, 4, $oDadosEfetividade->ed97_i_faltanjust == 0 ? "-" : 
                  $oDadosEfetividade->ed97_i_faltanjust, "T", 0, "C", $lCor
                 );
      $oPdf->cell(40, 4, $oDadosEfetividade->ed97_t_horario, "T", 0, "L", $lCor);
      $oPdf->cell(50, 4, $oDadosEfetividade->ed97_t_licenca, "T", 0, "L", $lCor);
      $oPdf->multicell(55, 4, $oDadosEfetividade->ed97_t_obs, "T", "L", $lCor);
      
    } else {
    	
      $oPdf->cell(15, 4, $oDadosEfetividade->identificacao, "T", 0, "C", $lCor);
      $oPdf->cell(70, 4, $oDadosEfetividade->z01_nome, "T", 0, "L", $lCor);
      $oPdf->cell(20, 4, $oDadosEfetividade->ed97_i_faltaabon == 0 ? "-" :
                  $oDadosEfetividade->ed97_i_faltaabon, "T", 0, "C", $lCor
                 );
      $oPdf->cell(20, 4, $oDadosEfetividade->ed97_i_faltanjust == 0 ? "-" : 
                  $oDadosEfetividade->ed97_i_faltanjust, "T", 0, "C", $lCor
                 );
      $oPdf->cell(25, 4, $oDadosEfetividade->ed97_i_horacinq == 0 ? "-" : 
                  $oDadosEfetividade->ed97_i_horacinq, "T", 0, "C", $lCor
                 );
      $oPdf->cell(25, 4, $oDadosEfetividade->ed97_i_horacem == 0 ? "-" : 
                  $oDadosEfetividade->ed97_i_horacem, "T", 0, "C", $lCor
                 );
      $oPdf->cell(50, 4, $oDadosEfetividade->ed97_t_licenca, "T", 0, "L", $lCor);
      $oPdf->multicell(55, 4, $oDadosEfetividade->ed97_t_obs, "T", "L", $lCor);
      
    }

  }
  $iPosY = $oPdf->getY();
  if (trim($oDadosEfetvRh->ed98_c_tipo) == "P") {
  	
    $oPdf->line(25, 48, 25, $iPosY);
    $oPdf->line(85, 48, 85, $iPosY);
    $oPdf->line(105, 48, 105, $iPosY);
    $oPdf->line(125, 48, 125, $iPosY);
    $oPdf->line(145, 48, 145, $iPosY);
    $oPdf->line(185, 48, 185, $iPosY);
    $oPdf->line(235, 48, 235, $iPosY);
    
  } else {
  	
    $oPdf->line(25, 48, 25, $iPosY);
    $oPdf->line(95, 48, 95, $iPosY);
    $oPdf->line(115, 48, 115, $iPosY);
    $oPdf->line(135, 48, 135, $iPosY);
    $oPdf->line(160, 48, 160, $iPosY);
    $oPdf->line(185, 48, 185, $iPosY);
    $oPdf->line(235, 48, 235, $iPosY);
    
  }
  
  $oPdf->setfont('arial', '', 6);
  $oPdf->cell(280, 4, "Assumo inteira responsaboilidade, para todos os fins legais, com as alterações registradas na ".
              "presente efetividade.", "T", 1, "C", 0);
  $oPdf->cell(280, 8, "", 0, 1, "C", 0);
  $oPdf->cell(80, 4, substr($oDadosEfetvRh->ed98_d_datafim, 8, 2)." de ".
              db_mes(substr($oDadosEfetvRh->ed98_d_datafim, 5, 2), 1).
              " de ".substr($oDadosEfetvRh->ed98_d_datafim, 0, 4), 0, 0, "L", 0
             );
  $oPdf->cell(120, 4, "__________________________________________", 0, 0, "C", 0);
  $oPdf->cell(80, 4, date("d")." de ".db_mes(date("m"), 1)." de ".date("Y"), 0, 1, "R", 0);
  $oPdf->cell(280, 6, "Responsável", 0, 1, "C", 0);
  
}
$oPdf->Output();
?>