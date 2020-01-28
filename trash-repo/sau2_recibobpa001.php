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

  /* 
 * Rotina que gera o recibo dos Arquivos BPA do Laboratório e do Ambulatório 
 * @author Adriano Quilião de Oliveira
 * */
require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("classes/db_sau_fecharquivo_classe.php");
require_once("classes/db_lab_bpamagnetico_classe.php");
require_once("classes/db_tfd_bpamagnetico_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clsau_fecharquivo  = new cl_sau_fecharquivo;
$cllab_bpamagnetico = new cl_lab_bpamagnetico;
$cltfd_bpamagnetico = new cl_tfd_bpamagnetico;
$sd99_d_data_dia    = date("d",db_getsession("DB_datausu"));
$sd99_d_data_mes    = date("m",db_getsession("DB_datausu"));
$sd99_d_data_ano    = date("Y",db_getsession("DB_datausu"));
$login = DB_getsession("DB_login");

/*
 * OBS:
 * Mes e Ano de competência são passadas como parâmetro em todos os módulos 
 * com os seguintes nomes:
 * MES = sd97_i_compmes
 * ANO = sd97_i_compano
 */

/* RECIBO BPA DO LABORATORIO  */
if (isset($iLab)) {
	
  $sCampos  = "la55_i_codigo as sd99_i_codigo, ";
  $sCampos .= "la55_i_usuario as sd99_i_login, ";
  $sCampos .= "la55_d_data as sd99_d_data ";
  $sSql     = $cllab_bpamagnetico->sql_query("", $sCampos, "la55_i_codigo desc",
                                             "la54_i_compmes=$sd97_i_compmes and la54_i_compano=$sd97_i_compano"
                                            );
    
  $result = $cllab_bpamagnetico->sql_record($sSql);
  if ($cllab_bpamagnetico->numrows > 0) {
    
  	db_fieldsmemory($result, 0);
    $clsau_fecharquivo->numrows = $cllab_bpamagnetico->numrows;
  
  }
  
/* RECIBO BPA DO TFD */  
} else if (isset($iTFD)) {

  
  $sCampos  = "tf33_i_codigo as sd99_i_codigo, ";
  $sCampos .= "tf33_i_login as sd99_i_login, ";
  $sCampos .= "tf33_d_datasistema as sd99_d_data ";
  $sSql     = $cltfd_bpamagnetico->sql_query("", $sCampos, "tf33_i_codigo desc",
                                             "tf32_i_mescompetencia = $sd97_i_compmes and".
                                             " tf32_i_anocompetencia = $sd97_i_compano"
                                            );
  
  $result = $cltfd_bpamagnetico->sql_record($sSql);
  if ($cltfd_bpamagnetico->numrows > 0) {
    
  	db_fieldsmemory($result, 0);
    $clsau_fecharquivo->numrows = $cltfd_bpamagnetico->numrows;

  }

/* RECIBO BPA DO AMBULATORIO */
} else {
  
  $sSql = $clsau_fecharquivo->sql_query("","*","",
                                        "sd97_i_compmes=$sd97_i_compmes and sd97_i_compano=$sd97_i_compano"
                                       );  
  
  $result = $clsau_fecharquivo->sql_record($sSql);                                                                          
  if ($clsau_fecharquivo->numrows > 0) {
    db_fieldsmemory($result, 0);
  }	  
  	
}

/* 
 *  clsau_fecharquivo->numrows é setada com o numero de registros
 *  retornados no BPA de todos os modulos apesar de ser uma variavel
 *  pertencente a classe do Ambulatorio.
 */ 
if ($clsau_fecharquivo->numrows == 0) {

  ?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Nenhum registro encontrado.<br>
          <input type='button' value='Fechar' onclick='window.close()'></b>
        </font>
      </td>
    </tr>
  </table>
  <?
  exit;

}

/* GERAÇÃO DO ARQUIVO PDF DO RECIBO */
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "Recibo da Emissão do BPA";
$pdf->ln(5);
$pdf->addpage('P');
$pdf->rect( $pdf->getX() + 7, $pdf->getY() + 5, 180, 150, "D");
$pdf->rect( $pdf->getX() + 8, $pdf->getY() + 6, 178, 12, "D");
$pdf->rect( $pdf->getX() + 8, $pdf->getY() + 19, 178, 135, "D");
$pdf->setfont('arial','',10);
$pdf->text( $pdf->getX() + 9, 
            $pdf->getY() + 10, 
            "MS / SAS / DATASUS SIA         SISTEMA DE INFORMAÇÕES AMBULATORIAIS           DATA COMPETÊNCIA"
          );
$mes = substr(db_mes($sd97_i_compmes, 1), 0, 3);
$pdf->text( $pdf->getX() + 9, 
            $pdf->getY() + 16,
            db_formatar($sd99_d_data,'d')."                                     ".
               "RELATÓRIO DE CONTROLE DE REMESSA                                    ".
               $mes."/".$sd97_i_compano
          );
$pdf->setXY($pdf->getX() + 9, $pdf->getY() + 24);
$pdf->setfont('arial','U',10);
$pdf->text( $pdf->getX(), $pdf->getY(), "ÓRGÃO RESPONSÁVEL PELA INFORMAÇÃO");
$pdf->setfont('arial','', 9);
$pdf->text( $pdf->getX(), $pdf->getY() + 6, "NOME: $sNomeorg");
$pdf->text( $pdf->getX(), $pdf->getY() + 12, "SIGLA: $sSigla");
$pdf->text( $pdf->getX(), $pdf->getY() + 18, "CGS/CPF: $iCnpj");
$pdf->text( $pdf->getX(), $pdf->getY() + 30, "                                                             ".
                                             " _______________________________________"
           );
$pdf->text( $pdf->getX(), $pdf->getY() + 34, "                                                             ".
                                             "     (Carimbo e assinatura do Responsável)"
           );
$pdf->Line( $pdf->getX(), $pdf->getY() + 42,  $pdf->getX() + 176, $pdf->getY() + 42);
$pdf->setXY($pdf->getX(), $pdf->getY() + 48);
$pdf->setfont('arial','U',10);
$pdf->text( $pdf->getX(), $pdf->getY(), "SECRETARIA DE SAÚDE DESTINO DOS BPA's");
$pdf->setfont('arial','', 9);
$pdf->text( $pdf->getX(), $pdf->getY() + 6, "NOME: $sDestino");
$pdf->text( $pdf->getX(), $pdf->getY() + 12, "ÓRGÃO: ".(($iOrgao == 1) ? "M" : "E"));
$pdf->text( $pdf->getX(), $pdf->getY() + 18, "SETOR DO RECEBIMENTO:");
$pdf->text( $pdf->getX(), $pdf->getY() + 24, "DATA DO RECEBIMENTO:  ___/___/___");
$pdf->text( $pdf->getX(), $pdf->getY() + 36, "                                                             ".
                                             " _______________________________________"
           );
$pdf->text( $pdf->getX(), $pdf->getY() + 40, "                                                             ".
                                             "     (Carimbo e assinatura do Responsável)"
           );
$pdf->Line( $pdf->getX(), $pdf->getY() + 48,  $pdf->getX() + 176, $pdf->getY() + 48);
$pdf->setXY($pdf->getX(), $pdf->getY() + 54);
$pdf->setfont('arial','U',10);
$pdf->text( $pdf->getX(), $pdf->getY(), "DADOS DO ARQUIVO DE BPA's GERADO");
$pdf->setfont('arial','', 9);
$pdf->text( $pdf->getX(), $pdf->getY() + 6, "NOME: PA".$sNomearq.".".$mes);
$pdf->text( $pdf->getX(), $pdf->getY() + 12, "REGISTROS GRAVADOS: ".($linhas+1));
$pdf->text( $pdf->getX(), $pdf->getY() + 18, "BPA's: $linhas");
$pdf->text( $pdf->getX(), $pdf->getY() + 24, "CAMPO DE CONTROLE: $iCntrl");
$pdf->Output();
?>