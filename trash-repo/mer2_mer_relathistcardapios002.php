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
include("libs/db_usuariosonline.php");
include("classes/db_mer_desper_und_classe.php");
include("classes/db_mer_cardapioaluno_classe.php");
include("classes/db_mer_cardapioturma_classe.php");
include("classes/db_mer_cardapiodia_classe.php");
include("classes/db_mer_cardapiodata_classe.php");
include("classes/db_mer_cardapioitem_classe.php");
include("classes/db_mer_subitem_classe.php");
require_once('libs/db_stdlibwebseller.php');
require_once('libs/db_utils.php');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$clrotulo            = new rotulocampo;
$clmer_desper_und    = new cl_mer_desper_und;
$clmer_cardapioaluno = new cl_mer_cardapioaluno;
$clmer_cardapioturma = new cl_mer_cardapioturma;
$clmer_cardapiodia   = new cl_mer_cardapiodia;
$clmer_cardapiodata  = new cl_mer_cardapiodata;
$clmer_cardapioitem  = new cl_mer_cardapioitem;
$clmer_subitem       = new cl_mer_subitem;
$iUsuarioNutri       = VerNutricionista(db_getsession('DB_id_usuario'));
$escola              = db_getsession("DB_coddepto");
$descrdepto          = db_getsession("DB_nomedepto");
$hoje                = date("Y-m-d");

if ($opcao == 1) {
	
  if ($periodo == 1) { // Semana
  	
    $ano    = date('Y', db_getsession('DB_datausu'));
    $mes    = date('m', db_getsession('DB_datausu'));
    $dia    = date('d', db_getsession('DB_datausu'));
    $weeke  = date('w', mktime(0, 0, 0, $mes, $dia, $ano));
    $inicio = date('Y-m-d', mktime(0, 0, 0, $mes, $dia + (2 - ($weeke + 1)), $ano));
    $fim    = date('Y-m-d', mktime(0, 0, 0, $mes, $dia + (6 - ($weeke + 1)), $ano));
    
  } else { // Mês
  	
    $ano    = date('Y', db_getsession('DB_datausu'));
    $mes    = date('m', db_getsession('DB_datausu'));
    $inicio = $ano.'-'.$mes.'-01';
    $fim    = date('Y-m-t', mktime(0, 0, 0, $mes, 1, $ano));
    
  }

}


$sCampos  = ' me27_c_nome, me12_i_codigo, me03_c_tipo, me01_i_codigo, me01_c_nome, me01_f_versao, me12_d_data, ';
$sCampos .= ' ed18_i_codigo, ed18_c_nome, me37_i_codigo, ';
$sCampos .= ' (select mer_cardapiodata.me13_d_data from mer_cardapiodata ';
$sCampos .= ' where me13_i_cardapiodiaescola = me37_i_codigo) as me13_d_data';

$sOrderBy = ' ed18_c_nome, me12_d_data, me03_i_orden';
$sWhere   = " me12_d_data between '$inicio' and '$fim' ";
if ($iUsuarioNutri != '') {
  
  // Obtenho todas as escolas atendidas pelo usuário nutricionista
  $oDaoMerNutricionistaEscola = db_utils::getdao('mer_nutricionistaescola');
  $sSql                       = $oDaoMerNutricionistaEscola->sql_query_nutricionistausuario(null, 'me31_i_escola', '',
                                                                                            'db_usuarios.id_usuario = '.
                                                                                            $iUsuarioNutri
                                                                                           );
  $rs                         = $oDaoMerNutricionistaEscola->sql_record($sSql);
  if ($oDaoMerNutricionistaEscola->numrows > 0) {
    
    $sCodEscolas = '';
    $sSepEscolas = '';
    for ($iCont = 0; $iCont < $oDaoMerNutricionistaEscola->numrows; $iCont++) {
      
      $sCodEscolas .= $sSepEscolas.db_utils::fieldsmemory($rs, $iCont)->me31_i_escola;
      $sSepEscolas  = ', ';

    }
    $sWhere .= " and me32_i_escola in ($sCodEscolas)";

  } else {
    $sWhere .= " and me32_i_escola = $escola";
  }

} else {
  $sWhere .= " and me32_i_escola = $escola";	
}

$sSql = $clmer_cardapiodia->sql_query_cardapiodiaescola(null, $sCampos, $sOrderBy, $sWhere);
$rs   = $clmer_cardapiodia->sql_record($sSql);

if ($clmer_cardapiodia->numrows == 0) {?>

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
function VerificaQuebra($primeiro, $pdf, $lQuebraEscola = false) {
	
  if ($pdf->gety() > $pdf->h - 35 || $primeiro == 0 || $lQuebraEscola) {
  	
    $pdf->setfillcolor(235);
    $pdf->cell(20,4,"",0,0,"C",0);
    $pdf->cell(260,1,"",1,1,"C",1);
    $pdf->setfillcolor(220);
    $pdf->ln(5);
    $pdf->addpage('L');
    $pdf->setfont('arial','b',9);
    $pdf->cell(20,4,"Código ",1,0,"C",1);
    $pdf->cell(50,4,"Refeição",1,0,"C",1);
    $pdf->cell(20,4,"Versão",1,0,"C",1);
    $pdf->cell(40,4,"Tipo de Refeição",1,0,"C",1);
    $pdf->cell(40,4,"Cardápio",1,0,"C",1);
    $pdf->cell(30,4,"Qtde. de Alunos",1,0,"C",1);
    $pdf->cell(20,4,"Repetições",1,0,"C",1);
    $pdf->cell(30,4,"Data Consumo",1,0,"C",1);
    $pdf->cell(30,4,"Data Baixa",1,1,"C",1);
    $pdf->setfont('arial','',8);
    
  }
}
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "RELATÓRIO DE HISTÓRICO DE REFEIÇÕES";
$head3 = "Período: ".db_formatar($inicio,'d')." à ".db_formatar($fim,'d');
$pdf->ln(5);
$iEscola = -1;
for ($s=0; $s < $clmer_cardapiodia->numrows; $s++) {
	
  db_fieldsmemory($rs,$s);

  $lQuebraEscola = false;
  if ($iEscola != $ed18_i_codigo) {

    $head5         = 'Escola: '.$ed18_c_nome;
    $lQuebraEscola = true;
    $iEscola       = $ed18_i_codigo;

  }

  $qtde_alunos = "0";
  $qtde_repet = "0";
  $campos555 = " count(*) as qtde_alunos,(select sum(me40_i_repeticao) from mer_cardapioalunorepet where me40_i_cardapiodia = me12_i_codigo) as qtde_repet ";
  $result555 = $clmer_cardapioaluno->sql_record($clmer_cardapioaluno->sql_query("",
                                                                              $campos555,
                                                                              "",
                                                                              "me11_i_cardapiodia = $me12_i_codigo 
                                                                              and ed18_i_codigo = $ed18_i_codigo 
                                                                              GROUP BY me12_i_codigo"
                                                                             ));
  $linhas555 = $clmer_cardapioaluno->numrows;
  if ($linhas555>0) {
    db_fieldsmemory($result555,0);
  } else {

    $campos556 = " sum(me39_i_quantidade) as qtde_alunos,sum(me39_i_repeticao) as qtde_repet ";
    $result556 = $clmer_cardapioturma->sql_record($clmer_cardapioturma->sql_query("",
                                                                                $campos556,
                                                                                "",
                                                                                "me39_i_cardapiodia = $me12_i_codigo
                                                                                 and ed18_i_codigo = $ed18_i_codigo"
                                                                               ));
    $linhas556 = $clmer_cardapioturma->numrows;
    if ($linhas556>0) {
      db_fieldsmemory($result556,0);
    }

  }

  VerificaQuebra($s, $pdf, $lQuebraEscola);
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','',8);
  $pdf->cell(20,4,$me01_i_codigo,1,0,"C",1);
  $pdf->cell(50,4,trim($me01_c_nome),1,0,"L",1);
  $pdf->cell(20,4,$me01_f_versao,1,0,"C",1);
  $pdf->cell(40,4,trim($me03_c_tipo),1,0,"L",1);
  $pdf->cell(40,4,trim($me27_c_nome),1,0,"L",1);
  $pdf->cell(30,4,$qtde_alunos,1,0,"C",1);
  $pdf->cell(20,4,$qtde_repet,1,0,"C",1);  
  $pdf->cell(30,4,db_formatar($me12_d_data,'d'),1,0,"C",1);
  $pdf->cell(30,4,db_formatar($me13_d_data,'d'),1,1,"C",1);

  if ($s>0) {
  	VerificaQuebra($s,$pdf);
  }

  $pdf->setfont('arial','b',8);
  $pdf->cell(20,4,"",0,0,"C",0);
  $pdf->cell(260,4,"ÍTENS",1,1,"L",0);
  $pdf->cell(20,4,"",0,0,"C",0);
  $pdf->cell(20,4,"Código","L",0,"L",0);
  $pdf->cell(70,4,"Material",0,0,"L",0);
  $pdf->cell(170,4,"Quantidade","R",1,"L",0);
  $campos =" me35_i_codigo,me35_c_nomealimento,m61_descr,me07_c_medida,me07_f_quantidade ";
  $result2 = $clmer_cardapioitem->sql_record($clmer_cardapioitem->sql_query("",
                                                                            $campos,
                                                                            "me35_c_nomealimento",
                                                                            " me07_i_cardapio = $me01_i_codigo"
                                                                           ));
  for ($y=0;$y<$clmer_cardapioitem->numrows;$y++) {
  	
    db_fieldsmemory($result2,$y);
    if ($s>0) {
      VerificaQuebra($s,$pdf);
    }
    $pdf->setfont('arial','',8);
    $pdf->cell(20,4,"",0,0,"C",0);
    $pdf->cell(20,4,$me35_i_codigo,"L",0,"L",0);
    $pdf->cell(70,4,substr($me35_c_nomealimento,0,30),0,0,"L",0);
    $pdf->cell(170,4,$me07_f_quantidade,"R",1,"L",0);
    
  }
  $campos  = " me29_i_alimentoorig,mer_alimento.me35_c_nomealimento as alimentoorig, ";
  $campos .= " alimento.me35_c_nomealimento as alimentonovo,me29_i_alimentonovo,me29_f_quantidade ";
  $result3 = $clmer_subitem->sql_record($clmer_subitem->sql_query("",
                                                                  $campos,
                                                                  "alimentoorig",
                                                                  "me29_i_refeicao = $me01_i_codigo 
                                                                   AND '$me12_d_data' BETWEEN me29_d_inicio 
                                                                   AND me29_d_fim"
                                                                 ));
  if ($clmer_subitem->numrows>0) {
  	
    if ($s>0) {
      VerificaQuebra($s,$pdf);
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(20,4,"",0,0,"C",0);
    $pdf->cell(260,4,"SUBSTITUIÇÕES",1,1,"L",0);
    $pdf->cell(20,4,"",0,0,"C",0);
    $pdf->cell(20,4,"Código","L",0,"L",0);
    $pdf->cell(70,4,"Substituido",0,0,"L",0);
    $pdf->cell(20,4,"Código",0,0,"L",0);
    $pdf->cell(70,4,"Substituto",0,0,"L",0);
    $pdf->cell(80,4,"Quantidade","R",1,"L",0);
    for ($y=0;$y<$clmer_subitem->numrows;$y++) {
    	
      db_fieldsmemory($result3,$y);
      if ($s>0) {
      	VerificaQuebra($s,$pdf);
      }
      $pdf->setfont('arial','',8);
      $pdf->cell(20,4,"",0,0,"C",0);
      $pdf->cell(20,4,$me29_i_alimentoorig,"L",0,"L",0);
      $pdf->cell(70,4,substr($alimentoorig,0,30),0,0,"L",0);
      $pdf->cell(20,4,$me29_i_alimentonovo,0,0,"L",0);
      $pdf->cell(70,4,substr($alimentonovo,0,30),0,0,"L",0);
      $pdf->cell(80,4,$me29_f_quantidade,"R",1,"L",0);
      
    }    
  }
  $result4 = $clmer_desper_und->sql_record($clmer_desper_und->sql_query("",
                                                                        "m61_descr,me23_f_quant,me23_t_obs",
                                                                        "",
                                                                        " me22_i_cardapiodiaescola = $me37_i_codigo"
                                                                       ));
  if ($clmer_desper_und->numrows>0) {
  	
    if ($s>0) {
      VerificaQuebra($s,$pdf);
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(20,4,"",0,0,"C",0);
    $pdf->cell(260,4,"DESPERDÍCIO",1,1,"L",0);
    $pdf->cell(20,4,"",0,0,"C",0);
    $pdf->cell(20,4,"Quantidade","L",0,"L",0);
    $pdf->cell(40,4,"Unidade",0,0,"L",0);
    $pdf->cell(200,4,"Observação","R",1,"L",0);
    for($y=0;$y<$clmer_desper_und->numrows;$y++){
    	
      db_fieldsmemory($result4,$y);
      if ($s>0) {
      	VerificaQuebra($s,$pdf);
      }
      $pdf->setfont('arial','',8);
      $pdf->cell(20,4,"",0,0,"C",0);
      $pdf->cell(20,4,$me23_f_quant,"L",0,"L",0);
      $pdf->cell(40,4,$m61_descr,0,0,"L",0);
      $pdf->cell(200,4,substr(trim($me23_t_obs),0,140),"R",1,"L",0);
      
    }    
  }
  $campos = " ed47_i_codigo,ed47_v_nome,ed57_c_descr,ed11_c_descr ";
  $order  = " ed11_i_sequencia,ed57_c_descr,ed47_v_nome ";
  $result5 = $clmer_cardapioaluno->sql_record($clmer_cardapioaluno->sql_query("",
                                                                              $campos,
                                                                              $order,
                                                                              "me11_i_cardapiodia = $me12_i_codigo
                                                                               and ed18_i_codigo = $ed18_i_codigo"
                                                                             ));
  if ($clmer_cardapioaluno->numrows>0) {
  	
   if ($s>0) {
   	 VerificaQuebra($s,$pdf);
   }
   $pdf->setfont('arial','b',8);
   $pdf->cell(20,4,"",0,0,"C",0);
   $pdf->cell(260,4,"CONSUMO POR ALUNOS",1,1,"L",0);
   $pdf->cell(20,4,"",0,0,"C",0);
   $pdf->cell(20,4,"Código","L",0,"L",0);
   $pdf->cell(120,4,"Nome",0,0,"L",0);
   $pdf->cell(120,4,"Turma / Etapa","R",1,"L",0);
   for($y=0;$y<$clmer_cardapioaluno->numrows;$y++){
   	
     db_fieldsmemory($result5,$y);
     if ($s>0) {
       VerificaQuebra($s,$pdf);
     }
     $pdf->setfont('arial','',8);
     $pdf->cell(20,4,"",0,0,"C",0);
     $pdf->cell(20,4,$ed47_i_codigo,"L",0,"L",0);
     $pdf->cell(120,4,$ed47_v_nome,0,0,"L",0);
     $pdf->cell(120,4,trim($ed57_c_descr)." / ".trim($ed11_c_descr),"R",1,"L",0);
     
   }   
  }  
  $campos = " ed57_c_descr,ed11_c_descr,me39_i_quantidade,me39_i_repeticao ";
  $order  = " ed11_i_sequencia,ed57_c_descr ";
  $result5 = $clmer_cardapioturma->sql_record($clmer_cardapioturma->sql_query("",
                                                                              $campos,
                                                                              $order,
                                                                              "me39_i_cardapiodia = $me12_i_codigo
                                                                               and ed18_i_codigo = $ed18_i_codigo "
                                                                             ));
  if ($clmer_cardapioturma->numrows>0) {
    
   if ($s>0) {
     VerificaQuebra($s,$pdf);
   }
   $pdf->setfont('arial','b',8);
   $pdf->cell(20,4,"",0,0,"C",0);
   $pdf->cell(260,4,"CONSUMO POR TURMAS",1,1,"L",0);
   $pdf->cell(20,4,"",0,0,"C",0);
   $pdf->cell(100,4,"Turma / Etapa","L",0,"L",0);
   $pdf->cell(80,4,"Quantidade",0,0,"L",0);
   $pdf->cell(80,4,"Repetições","R",1,"L",0);
   for($y=0;$y<$clmer_cardapioturma->numrows;$y++){
    
     db_fieldsmemory($result5,$y);
     if ($s>0) {
       VerificaQuebra($s,$pdf);
     }
     $pdf->setfont('arial','',8);
     $pdf->cell(20,4,"",0,0,"C",0);
     $pdf->cell(100,4,trim($ed57_c_descr)." / ".trim($ed11_c_descr),"L",0,"L",0);
     $pdf->cell(80,4,$me39_i_quantidade,0,0,"L",0);
     $pdf->cell(80,4,$me39_i_repeticao,"R",1,"L",0);
     
   }   
  }
}
$pdf->cell(20,4,"",0,0,"C",0);
$pdf->cell(260,1,"",1,1,"C",1);
$pdf->Output();
?>