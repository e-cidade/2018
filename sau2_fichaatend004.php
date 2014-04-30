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

include("fpdf151/scpdf.php");

include("libs/db_sql.php");
include("libs/db_stdlibwebseller.php");
include("classes/db_prontuarios_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

set_time_limit(0);

$clprontuarios = new cl_prontuarios;

try {
  $result_und = $clprontuarios->sql_record( "select unidades.*, cgm.z01_nome as estabelecimento,
                                                cgm.z01_ender as est_ender,
                                                cgm.z01_bairro as est_bairro,
                                                cgm.z01_munic as est_munic,
                                                cgm.z01_uf as est_uf
                                           from unidades
                                          inner join db_depart on db_depart.coddepto = unidades.sd02_i_codigo
                                          inner join cgm on cgm.z01_numcgm = unidades.sd02_i_numcgm
                                          where unidades.sd02_i_codigo = $unidade
                                        "
                                      );
}catch ( Exception $e ){
  die("erro");
}

if( $clprontuarios->numrows == 0 ){
 echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Verifique se o cï¿½digo $unidade esta cadastrada como unidade.<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
 exit;
}

$pdf = new SCPDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(243);
$pdf->addpage('P');
$pdf->ln(0);
$pdf->SetLineWidth(0.5);

db_inicio_transacao();


for( $intQtd = 1; $intQtd <= $qtd; $intQtd++ ){
  if( $intQtd > 1){
    $pdf->AddPage();
  }

  //Gerar número prontuário automático
    //gera numatend
    $sql_fc    = "select fc_numatend()";
    $query_fc  = db_query($sql_fc) or die(pg_errormessage().$sql_fc);
    $fc_numatend = explode(",",pg_result($query_fc,0,0));

    $clprontuarios->sd24_i_ano      = trim($fc_numatend[0]);
    $clprontuarios->sd24_i_mes      = trim($fc_numatend[1]);
    $clprontuarios->sd24_i_seq      = trim($fc_numatend[2]);
    $clprontuarios->sd24_i_login    = DB_getsession("DB_id_usuario");
    $clprontuarios->sd24_i_unidade  = $unidade;
    $clprontuarios->sd24_i_numcgs   = null;
    $clprontuarios->sd24_d_cadastro = date("Y-m-d",db_getsession("DB_datausu"));
    $clprontuarios->sd24_c_cadastro = date("H",db_getsession("DB_datausu")).":".date("m",db_getsession("DB_datausu"));
    $clprontuarios->incluir("");
    if($clprontuarios->erro_status=="0"){
     echo "<table width='100%'>
            <tr>
             <td align='center'><font color='#FF0000' face='arial'><b>".$clprontuarios->erro_msg."<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
            </tr>
           </table>";
    db_fim_transacao();
    exit;
  }

    $result_pront = $clprontuarios->sql_record( $clprontuarios->sql_query( $clprontuarios->sd24_i_codigo ) );
  db_fieldsmemory( $result_pront, 0 );

  //Posiï¿½ï¿½es
  $alt = $pdf->getY();
  $lar = $pdf->getX();
  $setY = 0;

  for( $y=1; $y<=2; $y++ ){
    $alt = $pdf->setY($setY);
    $lar = $pdf->setX(0);

    //Linha 1 - 1ï¿½ Retangulo SIA/SUA
    $pdf->rect( $pdf->getX(), $pdf->getY(), 56, 16, "D");
    $pdf->rect( $pdf->getX()+6, $pdf->getY()+1, 5, 14, "D");
    $pdf->rect( $pdf->getX()+1, $pdf->getY()+6, 15, 5, "D");
    $pdf->setfont('arial','b',8);
    $pdf->text( $pdf->getX()+17,$pdf->getY()+3,"SIAA/SUS-RS");//nome biblioteca
    $pdf->setfont('arial','b',6);
    $pdf->text( $pdf->getX()+17,$pdf->getY()+8,"SISTEMA DE INFORMAÇÕES");//nome biblioteca
    $pdf->text( $pdf->getX()+17,$pdf->getY()+11,"AMBULATORIAIS");//nome biblioteca
    $pdf->text( $pdf->getX()+17,$pdf->getY()+14,"DO SISTEMA ÚNICO DE SAÚDE");//nome biblioteca
    //Linha 1 - 2ï¿½ Retangulo
    $pdf->setfont('arial','b',7);
    $pdf->rect( $pdf->getX()+58, $pdf->getY(), 56, 16, "D");
    $pdf->setfont('arial','b',7);
// $pdf->text( $pdf->getX()+60,$pdf->getY()+3,"NÚMERO DO ATENDIMENTO");
    $pdf->text( $pdf->getX()+70,$pdf->getY()+5,"ATENDIMENTO Nro :   ".$sd24_i_codigo);
    $t1 = str_pad($sd24_i_codigo,10,0, STR_PAD_LEFT);//numero codigo barras
    $pdf->setfont('arial','b',8);
  $pdf->SetFillColor(000);//fundo codbarras
//$pdf->text($pdf->getX()+59,$pdf->getY()+15,str_pad($sd24_i_codigo,10," ",'str_pad_left').' - ');
  $pdf->int25($pdf->getX()+72,$pdf->getY()+7,$t1,5,0.341);//codbarras
    $pdf->setfont('arial','b',7);
//    $pdf->text( $pdf->getX()+60,$pdf->getY()+11,"FAMÍLIA PSF:");
//    $pdf->text( $pdf->getX()+60,$pdf->getY()+14,"MICROÁREA:");
    //Linha 1 - 3ï¿½ Retangulo
    $pdf->rect( $pdf->getX()+116, $pdf->getY(), 94, 7, "D");
    $pdf->setfont('arial','b',10);
    $pdf->text( $pdf->getX()+116+10, $pdf->getY()+5, "FICHA DE ATENDIMENTO AMBULATORIAL");
    $pdf->rect( $pdf->getX()+116, $pdf->getY()+9, 94, 7, "D");
    $pdf->setfont('arial','b',6);
    $pdf->text( $pdf->getX()+116+1, $pdf->getY()+12, "MOTIVO DO ATENDIMENTO");
    //$pdf->text( $pdf->getX()+116+1, $pdf->getY()+14, $sd92_c_nome);
    $pdf->text( $pdf->getX()+116+1, $pdf->getY()+14, "");


    //Linha 2 - 1ï¿½ Retangulo Unidade prestadora
    db_fieldsmemory($result_und,0);
    $pdf->rect( $pdf->getX(), $pdf->getY()+18, 56, 48, "D");
    $pdf->setfont('arial','b',6);
    $pdf->text( $pdf->getX()+2, $pdf->getY()+21, "UNIDADE PRESTADORA DE ATENDIMENTO");
    $alt = $pdf->getY()+5;
    $pdf->setfont('arial','b',7);


    $pdf->setfont('arial','b',7);
    $pdf->text( $pdf->getX()+2, $alt+24, "NOME DA UNIDADE: ");
    $pdf->setfont('arial','b',6);
    $pdf->text( $pdf->getX()+2, $alt+27,  substr($descrdepto,0,40));
    $pdf->setfont('arial','b',7);
    $pdf->text( $pdf->getX()+2, $alt+34, "ENDEREÇO: ");
    $pdf->setfont('arial','b',6);
    $pdf->text( $pdf->getX()+2, $alt+37, substr($est_ender,0,40));
    $pdf->setfont('arial','b',7);
    $pdf->text( $pdf->getX()+2, $alt+42, "MUNICÍPIO: ");
    $pdf->setfont('arial','b',6);
    $pdf->text( $pdf->getX()+2, $alt+44, substr($est_munic,0,40));
    $pdf->setfont('arial','b',6);
    $pdf->text( $pdf->getX()+2, $alt+50, "UF:".$est_uf);
    $pdf->setfont('arial','b',6);
    $pdf->text( $pdf->getX()+2, $alt+55, "CÓDIGO SIA/SUS:".$sd02_c_siasus);





    //Linha 2 - 2ï¿½ Retangulo CBO
    $pdf->setfont('arial','b',6);
    $pdf->rect( $pdf->getX()+58, $pdf->getY()+18, 19, 99, "D");
    $pdf->text( $pdf->getX()+60, $pdf->getY()+21, "CBO");
    $pdf->line( $pdf->getX()+58, $pdf->getY()+24, $pdf->getX()+58+19, $pdf->getY()+24 );
    //Linha 2 - 3ï¿½ Retangulo Procedimentos
    $pdf->rect( $pdf->getX()+79, $pdf->getY()+18, 35, 99, "D");
    $pdf->text( $pdf->getX()+80, $pdf->getY()+21, "CÓDIGO TABELA DE");
    $pdf->text( $pdf->getX()+80, $pdf->getY()+23, "PROCEDIMENTO SIA/SUS");
    $pdf->line( $pdf->getX()+79, $pdf->getY()+24, $pdf->getX()+74+40, $pdf->getY()+24 );
    //Linha 2 - 4ï¿½ Retangulo Procedimentos
    $pdf->rect( $pdf->getX()+116, $pdf->getY()+18, 94, 99, "D");
    $pdf->text( $pdf->getX()+117, $pdf->getY()+21, "PROFISSIONAL");
    $pdf->text( $pdf->getX()+117, $pdf->getY()+23, "SETOR");
    $pdf->line( $pdf->getX()+116, $pdf->getY()+24, $pdf->getX()+116+94, $pdf->getY()+24 );
    //Linha 2 - 5ï¿½ Retangulo Procedimentos
    $pdf->rect( $pdf->getX()+134, $pdf->getY()+18, 53, 99, "D");
    $pdf->text( $pdf->getX()+135, $pdf->getY()+21, "TRATAMENTO DESCRIÇÃO");
    $pdf->text( $pdf->getX()+135, $pdf->getY()+23, "DOS PROCEDIMENTOS");
    $pdf->text( $pdf->getX()+188, $pdf->getY()+21, "ASSIN. E CARIMBO");
    $pdf->text( $pdf->getX()+188, $pdf->getY()+23, "DO PROFISSIONAL");
    $pdf->setfont('arial','b',8);

    $pdf->text( $pdf->getX()+135, $pdf->getY()+27, "PRESSÃO:         TEMP.:         PESO:" );
    $pdf->line( $pdf->getX()+58, $pdf->getY()+30, $pdf->getX()+58+19, $pdf->getY()+30 );
    $pdf->line( $pdf->getX()+79, $pdf->getY()+30, $pdf->getX()+74+40, $pdf->getY()+30 );
    $pdf->line( $pdf->getX()+116, $pdf->getY()+30, $pdf->getX()+116+94, $pdf->getY()+30 );

    //Prontuï¿½rio / Procedimentos
    $alt=$pdf->getY();
    $lar=$pdf->getX();
    $pdf->setY($alt+30);
    $pdf->setY($alt);
    $pdf->setX($lar);



    //Linha 3 - 1ï¿½ Retangulo Unidade prestadora
    $pdf->rect( $pdf->getX(), $pdf->getY()+68, 56, 61, "D");
    $pdf->setfont('arial','b',6);
    $pdf->text( $pdf->getX()+2, $pdf->getY()+71, "IDENTIFICAÇÃO DO PACIENTE");
    $pdf->setfont('arial','b',7);
    $alt = $pdf->getY()+50+5;

    $alt=$pdf->getY();
    $lar=$pdf->getX();
    $pdf->setY($alt+73);
    $pdf->setX($lar+1);
    $pdf->MultiCell(55,4,"NOME: ",0,"L");
    $pdf->setY($alt);
    $pdf->setX($lar);

    $alt = $pdf->getY()+50+5;

    $pdf->text( $pdf->getX()+2, $alt+29, "ENDEREÇO: ");
    $pdf->text( $pdf->getX()+2, $alt+32, "");
    $pdf->text( $pdf->getX()+2, $alt+35, "BAIRRO: ");
    $pdf->text( $pdf->getX()+2, $alt+41, "MUNICÍPIO:");
    $pdf->text( $pdf->getX()+2, $alt+44, "");
    $pdf->text( $pdf->getX()+2, $alt+47, "UF:     IDADE:" );
    $pdf->text( $pdf->getX()+2, $alt+53, "SEXO: ");
    $pdf->text( $pdf->getX()+2, $alt+59, "DATA NASC:" );
    $pdf->text( $pdf->getX()+2 ,$alt+65, "FAMÍLIA PSF:");
    $pdf->text( $pdf->getX()+2 ,$alt+71, "MICROÁREA:");

    //Linha 3 - 2ï¿½ Retangulo Diagnï¿½stico
    $pdf->rect( $pdf->getX()+58, $pdf->getY()+119, 129, 10, "D");
    $pdf->setfont('arial','b',6);
    $pdf->text( $pdf->getX()+59, $pdf->getY()+122, "DIAGNÓSTICO:");
    $pdf->text( $pdf->getX()+59, $pdf->getY()+128, "CID: ");
    $pdf->setfont('arial','b',7);
    $alt=$pdf->getY();
    $lar=$pdf->getX();
    $sd24_t_diagnostico1=substr($sd24_t_diagnostico,0,46);
    $sd24_t_diagnostico2=substr($sd24_t_diagnostico,46,46);
    $sd24_t_diagnostico3=substr($sd24_t_diagnostico,92,46);
    $pdf->setY($alt+119);
    $pdf->setX($lar+76);
    $pdf->text($lar+78,$alt+122,$sd24_t_diagnostico1);
    $pdf->text( $lar+78,$alt+124,$sd24_t_diagnostico2);
    $pdf->text( $lar+78,$alt+126,$sd24_t_diagnostico3);
    $pdf->setY($alt);
    $pdf->setX($lar);
    $pdf->text( $pdf->getX()+65, $pdf->getY()+128, $sd70_c_cid."   ".$sd70_c_nome);
    //Linha 3 - 2ï¿½ Retangulo polegar
    $pdf->rect( $pdf->getX()+65+124, $pdf->getY()+119, 21, 22, "D");
    $pdf->setfont('arial','b',6);
    $pdf->text( $pdf->getX()+66+124, $pdf->getY()+122, "POLEGAR DIREITO:");
    //Linha 4 - 1ï¿½ Retangulo data do atendimento
    $pdf->rect( $pdf->getX(), $pdf->getY()+131, 27, 10, "D");
    $pdf->text( $pdf->getX(), $pdf->getY()+134, "DATA DO ATENDIMENTO:");
    $pdf->setfont('arial','b',7);
    $sd24_d_cadastro2 = substr($sd24_d_cadastro,8,2)."/".substr($sd24_d_cadastro,5,2)."/".substr($sd24_d_cadastro,0,4);
    $pdf->text( $pdf->getX()+5, $pdf->getY()+137, $sd24_d_cadastro2);
    //Linha 4 - 2ï¿½ Retangulo HORA
    $pdf->rect( $pdf->getX()+29, $pdf->getY()+131, 27, 10, "D");
    $pdf->setfont('arial','b',6);
    $pdf->text( $pdf->getX()+29, $pdf->getY()+134, "HORA DO ATENDIMENTO:");
    $pdf->setfont('arial','b',7);
    $pdf->text( $pdf->getX()+29+5, $pdf->getY()+137, $sd24_c_cadastro);
    //Linha 4 - 3ï¿½ Retangulo assinatura
    $pdf->rect( $pdf->getX()+58, $pdf->getY()+131, 129, 10, "D");
    $pdf->setfont('arial','b',6);
    $pdf->text( $pdf->getX()+59, $pdf->getY()+134, "ASSINATURA DO PACIENTE OU RESPONSÁVEL:");
    $setY = 150;
  }
}
db_fim_transacao();
$pdf->Output();
?>