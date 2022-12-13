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
include("libs/db_liborcamento.php");
include("classes/db_orcreceita_classe.php");

include("fpdf151/assinatura.php");
$classinatura = new cl_assinatura;
$clorcreceita = new cl_orcreceita;

// pesquisa a conta mae da receita

$tipo_mesini = 1;
$tipo_mesfim = 1;


$tipo_impressao = 1;
// 1 = orcamento
// 2 = balanco



parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    if (strlen(trim($nomeinstabrev)) > 0){
         $descr_inst .= $xvirg.$nomeinstabrev;
         $flag_abrev  = true;
    } else {
         $descr_inst .= $xvirg.$nomeinst;
    }

    $xvirg = ', ';
}

if($origem == "O"){
    $xtipo = "ORÇAMENTO";
}else{
    $xtipo = "BALANÇO";
    if($opcao == 3)
      $head6 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d') ;
    else
      $head6 = "PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));
}
$db_filtro = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
if($recurso==0){
  $head3 = "BALANCETE DA RECEITA ";
}else{
  $head2 = "BALANCETE DA RECEITA ";
  $resrec = pg_exec("select o15_descr from orctiporec where o15_codigo = $recurso");
  $head3 = "Recurso: ".$recurso."-".substr(pg_result($resrec,0,0),0,30);
  $db_filtro .= " and o70_codigo = $recurso";
}
$head4 = "EXERCÍCIO: ".db_getsession("DB_anousu")." - ".$xtipo;

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head5 = "INSTITUIÇÕES : ".$descr_inst;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$pdf->setleftmargin(3);
$troca = 1;
$alt = 4;

//$sql = "select * from work order by elemento";
//$result = pg_exec($sql);
$anousu  = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;

$result = db_receitasaldo(11,1,$opcao,true,$db_filtro,$anousu,$dataini,$datafin);
 

//db_criatabela($result);exit;


//  echo($formato_arq);exit;
//  if($formato_arq == 'C'){ 
     $fp = fopen("tmp/balrec.csv","w");
     fputs($fp,"Receita;Descrição;Reduz;Rec;Previsto;Prev.Adic;Arrecadado;Arrec.Ano;Diferença;Caract.Peculiar\n");
     while($ln = pg_fetch_array($result)){
       $res_orcreceita = $clorcreceita->sql_record($clorcreceita->sql_query(null,null,"c58_descr",null,"o70_anousu = $anousu and o57_fonte = '".$ln["o57_fonte"]."'"));
       if ($clorcreceita->numrows > 0){
         db_fieldsmemory($res_orcreceita,0);
       }
//        fputs($fp,$ln["o57_fonte"]." - ".$ln["o57_descr"].";".$ln["o57_unidade"]." - ".$ln["o41_descr"].";");
        fputs($fp,$ln["o57_fonte"].";".$ln["o57_descr"].";".$ln["o70_codrec"].";".$ln["o70_codigo"].";");
        fputs($fp,db_formatar($ln["saldo_inicial"],'f').";".db_formatar($ln["saldo_prevadic_acum"],'f').";".db_formatar($ln["saldo_arrecadado"],'f').";".db_formatar($ln["saldo_arrecadado_acumulado"],'f').";");
        fputs($fp,db_formatar($ln["saldo_a_arrecadar"],'f').";".@$c58_descr."\n");
     }	
     echo "<html><body bgcolor='#cccccc'><center><a href='tmp/balrec.csv'>Clique com botão direito para Salvar o arquivo <b>balrec.csv</b></a></body></html>";
     fclose($fp);
     exit;
 
  pg_exec("commit");
//


?>