<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_renovacoes_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clrenovacoes = new cl_renovacoes;
$Sql = "select cgm.z01_nome, 
               cgm1.z01_nome as nome,
               cm19_c_descr,
               cm22_c_quadra,
               to_char(cm01_d_falecimento,'dd/mm/yyyy') as obito, 
               cm07_c_motivo,
               to_char(cm07_d_vencimento,'dd/mm/yyyy') as vencto,
	       cm23_i_lotecemit
          from renovacoes 
         inner join cgm           on cgm.z01_numcgm               = renovacoes.cm07_i_renovante 
         inner join sepultamentos on sepultamentos.cm01_i_codigo  = renovacoes.cm07_i_sepultamento
         inner join cgm as cgm1   on cgm1.z01_numcgm              = sepultamentos.cm01_i_codigo
         inner join sepulta       on sepulta.cm24_i_sepultamento  = sepultamentos.cm01_i_codigo
         inner join sepulturas    on sepulturas.cm05_i_codigo     = sepulta.cm24_i_sepultura
         inner join campas        on campas.cm19_i_codigo         = sepulturas.cm05_i_campa
         inner join lotecemit     on sepulturas.cm05_i_lotecemit  = lotecemit.cm23_i_codigo
         inner join quadracemit   on quadracemit.cm22_i_codigo    = lotecemit.cm23_i_quadracemit
         where cm07_i_codigo = $cod";
  $Query = $clrenovacoes->sql_record($Sql);
  db_fieldsmemory($Query,0);
  
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $head2 = "RENOVAÇÃO DE SEPULTURAS";
  $pdf->addpage();
  $pdf->setfillcolor(235);
//  $variavel = "teste";
  $pdf->setfont('arial','b',10);
  $pdf->cell(190,30,"Renovação de Sepultura",0,1,"C",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(190,4,"Autorizo, através deste o(a) Sr.(a) ".$z01_nome,0,1,"L",0);
  $pdf->cell(190,4,"A efetuar a renovação da campa onde esta sepultado o(a) Sr.(a) ".trim($nome),0,1,"L",0);
  $pdf->cell(190,4,"Identificação da campa: ".trim($cm19_c_descr).", quadra: ".$cm22_c_quadra.", lote: ".$cm23_i_lotecemit.". Falecido em ".$obito,0,1,"L",0);
  if(trim($cm07_c_motivo)!=""){
   $pdf->cell(190,4,"Motivo desta renovacao: ".trim($cm07_c_motivo),0,1,"L",0);
  }
  $pdf->cell(190,10,"",0,1,"L",0);
  $pdf->cell(190,4,"Próximo vencimento: ".$vencto,0,1,"L",0);
  $pdf->cell(190,20,"",0,1,"L",0);
  $pdf->cell(95,20,"",0,0,"L",0);
  $pdf->cell(55,4,"Responsável pela liberacao da Renovacao","T",0,"R",0);
  $pdf->Output();
?>