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


include("fpdf151/pdfwebseller2.php");
//include("fpdf151/fpdf.php");
include("libs/db_sql.php");
include("classes/db_prontuariomedico_classe.php");
include("classes/db_prontcid_classe.php");
include("classes/db_cgs_und_classe.php");
include("classes/db_prontuarios_classe.php");
include("classes/db_prontproced_ext_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

set_time_limit(0);
$clprontuarios      = new cl_prontuarios;
$clprontproced      = new cl_prontproced_ext;
$clprontcid         = new cl_prontcid;
$clprontuariomedico = new cl_prontuariomedico;
$clcgs_und = new cl_cgs_und;


$result = $clcgs_und->sql_record( $clcgs_und->sql_query( $cgs ) );
db_fieldsmemory($result,0);
$query = @pg_query($clprontuariomedico->sql_query("","*, a.z01_nome as profissional","sd32_d_atendimento desc","sd32_i_numcgs = $cgs" ));
$linhas = @pg_num_rows($query);
//die($clprontuariomedico->sql_query("","*","sd32_d_atendimento desc ","sd32_i_numcgs = $cgs" ));

//die($clprontproced->sql_query_prontuario("","*,sau_cid.sd70_c_cid,sau_cid.sd70_c_nome,sau_procedimento.sd63_c_nome,sd29_t_tratamento,m.z01_nome as profissional","sd24_d_cadastro desc ","sd24_i_numcgs = $cgs" ));
$sSql = $clprontproced->sql_query_prontuario("", "sd24_v_motivo, sd24_v_pressao, sd24_f_temperatura, ".
                                             " sd24_c_cadastro, sd24_f_peso, sd29_i_prontuario, sd24_d_cadastro, ".
                                             " a.login,sau_cid.sd70_c_cid, sau_cid.sd70_c_nome, ".
                                             " sau_procedimento.sd63_c_procedimento,sau_procedimento.sd63_c_nome, ".
                                             " sd29_t_tratamento,m.z01_nome as profissional","sd24_d_cadastro desc ",
                                             "sd24_i_numcgs = $cgs" );
$query1 = pg_query($sSql);
$linhas1 = pg_num_rows($query1);
//die($clprontuarios->sql_query("","*","sd24_d_cadastro desc ","sd24_i_numcgs = $cgs" ));

if($linhas == 0 && $linhas1 == 0 ){
 echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
 exit;
}


$pdf = new pdf();
$pdf->Open();
$pdf->AliasNbPages();
$head3 = "                                  Atendimentos";
$head5 = "Família......: ".$sd33_v_descricao;
$head7 = "Micro Área:  ".$sd34_v_descricao;

//$head3 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
$pri = true;

$ec_cgs = array("1"=>"Solteiro",
           "2"=>"Casado",
           "3"=>"Viúvo",
           "4"=>"Separado Judicialmente",
           "5"=>"União Consensual",
           "9"=>"Ignorado");
           
$sexo_cgs = array("F"=>"Feminino",
          "M"=>"Masculino");
          
          
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

$retorna_obs = 0;
for($p=0; $p<$linhas1; $p++){

   $xlin=$pdf->Gety();
   $pdf->rect(10,$xlin,192,0, 2, 'DF', '12');

   if ($retorna_obs == 0) {
      db_fieldsmemory($query1,$p);
      $pri=false;
      $pdf->setfont('arial','',7);
      $nbx="";
      $sd29_i_prontuario_ = $sd29_i_prontuario; 
      $sd29_t_tratamento_ = '';
      while($sd29_i_prontuario_ == $sd29_i_prontuario){
        $sd29_t_tratamento_ .= "\n".$sd29_t_tratamento; 
        $p++;
        if($p==$linhas1){
          break;
        }  
        db_fieldsmemory($query1,$p);
      }
      if($p>$linhas1){
        break;
      }  
      $p--;
      db_fieldsmemory($query1,$p);

      $sd70_c_nome_ = trim("    Principal : ".$sd70_c_cid." - ".$sd70_c_nome); 
      $query3 = pg_query($clprontcid->sql_query("","sd70_c_cid,sd70_c_nome,sd55_b_principal","","sd55_b_principal = 'f' and prontcid.sd55_i_prontuario = $sd29_i_prontuario"));
      $linhas3 = pg_num_rows($query3);
      $qtde_cid = pg_num_rows($query3);
      for($p1=0; $p1<$qtde_cid; $p1++){
        db_fieldsmemory($query3,$p1);
        $sd70_c_nome_ .= "\n"."                                          ".trim($sd70_c_cid." - ".$sd70_c_nome); 
      }
      $sd24_d_cadastro = substr($sd24_d_cadastro,8,2)."/".substr($sd24_d_cadastro,5,2)."/".substr($sd24_d_cadastro,0,4);
      $sd29_t_tratamento_ = trim(stripslashes($sd29_t_tratamento_));
    
      $data = array("$sd24_d_cadastro  $sd24_c_cadastro",
                    "$profissional \n\n\n", 
                    "FAA                  ".$sd29_i_prontuario."          Atendente: $login".
                    "\nMotivo              ".$sd24_v_motivo.
                    "\nPressão           ".$sd24_v_pressao.
                    "      Peso ".$sd24_f_peso.
                    "     Temperatura ".$sd24_f_temperatura.
                    "\nCID                   ".$sd70_c_nome_.
                    "\nProcedimento   ".trim($sd63_c_procedimento)." - ".str_pad($sd63_c_nome,60)
                    );
    
       //Draw the border
//     if($borda == true)
//     $this->Rect($x,$y,$w,$h);
   }else{
      $sd29_t_tratamento_ = $descricaoitemimprime;
      $retorna_obs = 0;
      $data = array("$sd24_d_cadastro  $sd24_c_cadastro",
                    "$profissional \n\n\n", 
                    "\n$sd29_t_tratamento_"
                    );
   }
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
      $p--;
      $xlin=$pdf->Gety();
      $pdf->text(120,$xlin+15,"Continua na próxima página ");
    }
    cabecalho($pdf,$result,$pri);

}  
 
//for de prontuariomedico
$retorna_obs = 0;
for($x=0; $x< $linhas; $x++){
   $pdf->setfont('arial','',7);

   $xlin=$pdf->Gety();
   $pdf->rect(10,$xlin,192,0, 2, 'DF', '12');

   if ($retorna_obs == 0) {
     db_fieldsmemory($query,$x);
     $pri=false;
     $z01_d_nasc = substr($z01_d_nasc,8,2)."/".substr($z01_d_nasc,5,2)."/".substr($z01_d_nasc,0,4);
     $sd32_d_atendimento = substr($sd32_d_atendimento,8,2)."/".substr($sd32_d_atendimento,5,2)."/".substr($sd32_d_atendimento,0,4);
     
   }else{
      $sd32_t_descricao = $descricaoitemimprime;
      $retorna_obs = 0;
   }
   $data = array("$sd32_d_atendimento $sd32_c_horaatend",
                    "$profissional \n\n\n", 
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
  
  function cabecalho($pdf,$result,$pri){
  	global $z01_i_numcgs,$z01_v_nome,$z01_v_sexo,$z01_d_nasc,$z01_c_naturalidade,$z01_v_pai,$z01_v_mae,$z01_v_ender,$z01_i_numero,$z01_v_compl;
  	global $z01_v_telef,$z01_v_bairro,$ec,$z01_v_ident,$z01_c_cartaosus,$ec_cgs,$z01_i_estciv,$sexo_cgs,$z01_v_sexo;

    if (  ($pdf->gety() > $pdf->h -30) || $pri){
    	  db_fieldsmemory($result,0);
          $pdf->addpage();
          $pdf->header();
          $pdf->setfillcolor(235);
          $pdf->setfont('arial','b',12);
		      $pdf->roundedrect(10,43,192,24.5,2,'DF','1234');
          $pdf->cell(192,8,"ATENDIMENTOS",0,1,"C",0);
          $pdf->setfont('arial','b',7);
          $pdf->cell(30,4,"Nome : ",0,0,"R",0);
          $pdf->cell(66,4,$z01_i_numcgs."-".trim($z01_v_nome),0,0,"L",0);
          $pdf->cell(30,4,"Sexo :",0,0,"R",0);
          $pdf->cell(66,4,$sexo_cgs[$z01_v_sexo],0,1,"L",0);
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
          if(!isset($z01_i_estciv) || $z01_i_estciv == 0 ){          
             $pdf->cell(66,4,$ec_cgs[9],0,1,"L",0);
          }else{
             $pdf->cell(66,4,$ec_cgs[$z01_i_estciv],0,1,"L",0);
          }
          $pdf->cell(30,4,"Doc. RG : ",0,0,"R",0);
          $pdf->cell(66,4,$z01_v_ident,0,0,"L",0);
          $pdf->cell(30,4,"Cartão SUS : ",0,0,"R",0);
          $pdf->cell(66,4,$z01_c_cartaosus,0,1,"L",0);
          $head1 = str_pad("Continuação", 50, " ", STR_PAD_LEFT);
          $head7 = "Nome: ".$z01_i_numcgs."-".$z01_v_nome;

          $pdf->cell(30,8,"            ",0,0,"L",0);
          $pdf->cell(162,8,"        ",0,1,"L",0);

          $pdf->cell(20,4,"DATA",1,0,"C",1);
//          $pdf->cell(20,4,"HORA",1,0,"C",1);
          $pdf->cell(45,4,"PROFISSIONAL",1,0,"C",1);
          $pdf->cell(127,4,"FAA - MOTIVOS - CID - PROCEDIMENTOS",1,1,"C",1);
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
      
?>