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

include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
include ("classes/db_corrente_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clcorrente = new cl_corrente;
$clrotulo   = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("z01_ender");
$clrotulo->label("z01_numero");
$clrotulo->label("z01_munic");
$clrotulo->label("pc63_banco");
$clrotulo->label("pc63_agencia");

$dbwhere  = "where 1=1 and k12_instit = ".db_getsession("DB_instit");
$data     = str_replace("/","-",$lista_data);

$vet_data = split(",",$data);
$virgula  = "";
$data     = "";
for($i=0; $i < sizeof($vet_data); $i++){
     $datas   = split("-",$vet_data[$i]);
     $data   .= $virgula."'".trim($datas[2])."-".trim($datas[1])."-".trim($datas[0])."'";
     $virgula = ", ";
}

$vet_estorn = split(",",$lista_estorn);
$virgula    = "";
$estorno    = "";
for($i=0; $i < sizeof($vet_estorn); $i++){
     $estorno .= $virgula."'".trim($vet_estorn[$i])."'";
     $virgula  = ", ";
}

$lista_estorn = $estorno;

if (isset($lista_nfs)&&trim($lista_nfs)!=""){
     $vet_nfs = split(",",$lista_nfs);
     $virgula = "";
     $notas   = "";
     for($i=0; $i < sizeof($vet_nfs); $i++){
          $notas   .= $virgula."'".trim($vet_nfs[$i])."'";
          $virgula  = ", ";
     }

     $lista_nfs = $notas;
}     

$dbwhere .= " and z01_numcgm          in ($lista_cgm)"; 
$dbwhere .= " and corrente.k12_data   in ($data)";
$dbwhere .= " and coremp.k12_empen    in ($lista_empen)";
$dbwhere .= " and corrente.k12_estorn in ($lista_estorn)";
$dbwhere .= " and coremp.k12_codord   in ($lista_ordens)";
$dbwhere .= " and corrente.k12_conta  in ($lista_contas)";
$dbwhere .= " and corrente.k12_valor  in ($lista_valores)";

if (isset($lista_nfs)&&trim($lista_nfs)!=""){
//     $dbwhere .= " and empnota.e69_numero  in ($lista_nfs)";
}

$sql      = "select distinct
                    z01_numcgm,
                    z01_nome,
                    z01_cgccpf,
                    z01_ender,
                    z01_numero,
                    z01_munic,
                    pc63_banco,
                    pc63_agencia,
                    pc63_agencia_dig,
                    pc63_conta,
                    pc63_conta_dig,
                    corrente.k12_data,
                    corrente.k12_conta,
                    conplano.c60_descr,
                    corrente.k12_valor,
                    empempenho.e60_codemp,
                    coremp.k12_cheque,
                    coremp.k12_codord,
                    empnota.e69_numero,
                    case
                        when corrente.k12_estorn is false then
                        case
                            when empageforma.e96_descr = 'DIN' and coremp.k12_cheque = 0 then 'DINHEIRO'
                            when empageforma.e96_descr = 'CHE' or  coremp.k12_cheque > 0 then 'CHEQUE'
                            when empageforma.e96_descr = 'TRA' then 'TRANSMISSAO'
                        end
                    else
                          'ESTORNO'
                    end as e96_descr,
                    empageconfgera.e90_codgera
             from corrente 
                  inner join coremp         on coremp.k12_id              = corrente.k12_id   and
                                               coremp.k12_data            = corrente.k12_data and
                                               coremp.k12_autent          = corrente.k12_autent

                  inner join empempenho     on empempenho.e60_numemp      = coremp.k12_empen

                  inner join cgm            on cgm.z01_numcgm             = empempenho.e60_numcgm 

                  inner join conplanoreduz  on conplanoreduz.c61_reduz    = corrente.k12_conta and
                                               conplanoreduz.c61_anousu   = ".db_getsession("DB_anousu")."

                  inner join conplano       on conplano.c60_codcon        = conplanoreduz.c61_codcon and
                                               conplano.c60_anousu        = conplanoreduz.c61_anousu

                  inner join pagordem       on pagordem.e50_numemp        = coremp.k12_empen and
                                               pagordem.e50_codord        = coremp.k12_codord

                  inner join empord        on empord.e82_codord           = coremp.k12_codord                            

                  left  join pagordemnota   on pagordemnota.e71_codord    = pagordem.e50_codord

                  left  join corempagemov on corempagemov.k12_id     = corrente.k12_id   and
                                             corempagemov.k12_data   = corrente.k12_data and
                                             corempagemov.k12_autent = corrente.k12_autent 
                                                                                                     
                  left  join empagemov     on empagemov.e81_codmov        = corempagemov.k12_codmov

                  left  join empageconfgera on empageconfgera.e90_codmov  = empagemov.e81_codmov                            
                  
                  left  join empagemovconta on empagemovconta.e98_codmov  = empagemov.e81_codmov

                  left  join pcfornecon     on pcfornecon.pc63_numcgm     = cgm.z01_numcgm and 
                                               pcfornecon.pc63_contabanco = empagemovconta.e98_contabanco 

                  left  join empnota        on empnota.e69_numemp         = coremp.k12_empen and
                                               empnota.e69_codnota        = pagordemnota.e71_codnota

                  left  join empagemovforma on empagemovforma.e97_codmov  = empagemov.e81_codmov

                  left  join empageforma    on empageforma.e96_codigo     = empagemovforma.e97_codforma ".$dbwhere."
             order by z01_numcgm, corrente.k12_data desc";

//echo $sql; exit;

$resultado = $clcorrente->sql_record($sql);

//db_criatabela($resultado);

$head3 = "DEMONSTRATIVO DE PAGAMENTO A FORNECEDOR";
if(isset($periodo)&&trim($periodo)!=""){
  $head4 = "Periodo a ser demonstrado de ".$periodo;
}  

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 8);
$alt        = 6;
$p          = 0;
$numcgm_ant = "";

for($x=0; $x < $clcorrente->numrows; $x++){
     db_fieldsmemory($resultado,$x);

     if($numcgm_ant!=$z01_numcgm){
	       $pdf->AddPage();
         $pdf->setfont('arial', 'b', 8);
         if($x==0){
             $pdf->cell(30, $alt, "Documento impresso em:     ".date("d/m/Y",db_getsession("DB_datausu")), 0, 1, "L", 0);
         }
	     	 $pdf->cell(15, $alt, "Dados do credor:", 0, 1, "L", 0);
     		 $pdf->cell(15, $alt, $RLz01_numcgm.": ",      0, 0, "L", 0);
         $pdf->setfont('arial', '', 8);
     		 $pdf->cell(15, $alt, $z01_numcgm,        0, 0, "L", 0);
         $pdf->setfont('arial', 'b', 8);
     		 $pdf->cell(70, $alt, $RLz01_cgccpf.": ",      0, 0, "R", 0);
         $pdf->setfont('arial', '', 8);
         if(strlen($z01_cgccpf) < 14){
             $cgccpf = db_formatar($z01_cgccpf,"cpf");
         } else {
             $cgccpf = db_formatar($z01_cgccpf,"cnpj");
         }
     		 $pdf->cell(30, $alt, $cgccpf, 0, 1, "R", 0);
         $pdf->setfont('arial', 'b', 8);
     		 $pdf->cell(15, $alt, $RLz01_nome.": ", 0, 0, "L", 0);
         $pdf->setfont('arial', '', 8);
         $pdf->cell(60, $alt, $z01_nome, 0, 1, "L", 0);
         $pdf->setfont('arial', 'b', 8);
     		 $pdf->cell(15, $alt, $RLz01_ender.": ", 0, 0, "L", 0);
         $pdf->setfont('arial', '', 8);
         $pdf->cell(52, $alt, substr($z01_ender,0,50), 0, 0, "L", 0);
         $pdf->setfont('arial', 'b', 8);
     		 $pdf->cell(30, $alt, $RLz01_numero.": ", 0, 0, "R", 0);
         $pdf->setfont('arial', '', 8);
         $pdf->cell(15, $alt, $z01_numero, 0, 1, "R", 0);
         $pdf->setfont('arial', 'b', 8);
     		 $pdf->cell(15, $alt, $RLz01_munic.": ", 0, 0, "L", 0);
         $pdf->setfont('arial', '', 8);
         $pdf->cell(45, $alt, $z01_munic,  0, 1, "L", 0);
         $pdf->setfont('arial', 'b', 8);
         $pdf->cell(15, $alt, "Banco: ",   0, 0, "L", 0);
         $pdf->setfont('arial', '', 8);
         $pdf->cell(15, $alt, $pc63_banco, 0, 0, "L", 0);
         $pdf->setfont('arial', 'b', 8);
         $pdf->cell(15, $alt, "Agencia: ", 0, 0, "L", 0);
         $pdf->setfont('arial', '', 8);
         $pdf->cell(15, $alt, $pc63_agencia."-".$pc63_agencia_dig, 0, 0, "L", 0);
         $pdf->setfont('arial', 'b', 8);
         $pdf->cell(15, $alt, "Conta: ", 0, 0, "L", 0);
         $pdf->setfont('arial', '', 8);
         $pdf->cell(15, $alt, $pc63_conta."-".$pc63_conta_dig, 0, 1, "L", 0);
         $pdf->cell(195, ($alt-3), "", "T", 1, "R", 0);

         $numcgm_ant = $z01_numcgm;
         $p          = 0;
     }

     $pdf->setfont('arial', 'b', 8);
   	 $pdf->cell(195, $alt, "Dados do Empenho pago:",   0, 1, "L", $p);
     $pdf->setfont('arial', 'b', 8);
   	 $pdf->cell(20, $alt, "Ordem pgto.: ",             0, 0, "L", $p);
     $pdf->setfont('arial', '', 8);
   	 $pdf->cell(15, $alt, $k12_codord,                 0, 0, "L", $p);
     $pdf->setfont('arial', 'b', 8);
   	 $pdf->cell(15, $alt, "Empenho: ",                 0, 0, "L", $p);
     $pdf->setfont('arial', '', 8);
   	 $pdf->cell(15, $alt, $e60_codemp,                 0, 0, "L", $p);
     $pdf->setfont('arial', 'b', 8);
   	 $pdf->cell(10, $alt, "Nota: ",                    0, 0, "L", $p);
     $pdf->setfont('arial', '', 8);
   	 $pdf->cell(15, $alt, $e69_numero,                 0, 0, "L", $p);
     $pdf->cell(105, $alt, "",                         0, 1, "L", $p);
     $pdf->setfont('arial', 'b', 8);
   	 $pdf->cell(28, $alt, "Valor pago R$: ",           0, 0, "L", $p);
     $pdf->setfont('arial', '', 8);
   	 $pdf->cell(20, $alt, db_formatar($k12_valor,"f"), 0, 0, "L", $p);
     $pdf->setfont('arial', 'b', 8);
   	 $pdf->cell(10, $alt, "Data: ",                    0, 0, "L", $p);
     $pdf->setfont('arial', '', 8);
   	 $pdf->cell(20, $alt, db_formatar($k12_data,"d"),  0, 0, "L", $p);
     $pdf->setfont('arial', 'b', 8);
   	 $pdf->cell(25, $alt, "Conta pagadora: ",          0, 0, "L", $p);
     $pdf->setfont('arial', '', 8);
   	 $pdf->cell(10, $alt, $k12_conta,                  0, 0, "L", $p);
     $pdf->cell(82, $alt, $c60_descr,                  0, 1, "L", $p);
     $pdf->setfont('arial', 'b', 8);
   	 $pdf->cell(15, $alt, "Forma: ",                   0, 0, "L", $p);
     $pdf->setfont('arial', '', 8);
   	 $pdf->cell(82, $alt, $e96_descr, 0, 0, "L", $p);

     if ($e96_descr == "CHEQUE"){
          $pdf->setfont('arial', 'b', 8);
   	      $pdf->cell(15, $alt, "Cheque: ", 0, 0, "L", $p);
          $pdf->setfont('arial', '', 8);
   	      $pdf->cell(15, $alt, $k12_cheque, 0, 0, "L", $p);

          $tam = 68;
     } else {
          $tam = 37;
     }

     if ($e90_codgera > 0 && $e96_descr == "TRANSMISSAO"){
          $pdf->setfont('arial', 'b', 8);
   	      $pdf->cell(20, $alt, "Codigo arq.: ", 0, 0, "L", $p);
          $pdf->setfont('arial', '', 8);
   	      $pdf->cell(15, $alt, $e90_codgera, 0, 0, "L", $p);

          $tam = 63;
     }

     $pdf->cell($tam, $alt, "", 0, 1, "L", $p);

     if($p==0){
         $p = 1;
     } else {
         $p = 0;
     }
}

$pdf->Output();
?>