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

include("libs/db_sql.php");
include("fpdf151/scpdf.php");
include("classes/db_notificacao_classe.php");
include("classes/db_db_config_classe.php");
$cldb_config     = new cl_db_config;
$clnotificacao   = new cl_notificacao;

db_postmemory($HTTP_SERVER_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if ( $contribuicao == '' ) {
     db_redireciona('db_erros.php?fechar=true&db_erro=Contribuição não encontrada!');
        exit;
}
$resultinst = $cldb_config->sql_record($cldb_config->sql_query(db_getsession("DB_instit")));
db_fieldsmemory($resultinst,0,true);

$head3 = 'Relação de Notificações';
$pdf = new SCPDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 

$contr = '';
if (isset($campo)){
   if ($tipo == 2){
       $contr = " d07_contri = ".$contribuicao." and d07_matric in (".str_replace('-',', ',$campo).") ";
   }elseif ($tipo == 3){
       $contr = " d07_contri = ".$contribuicao." and d07_matric not in (".str_replace('-',', ',$campo).") ";
   }
}


//die($clnotificacao->sql_noticontri($contribuicao,"","","contrinot.d08_notif,contrib.d07_contri as d08_contri,contricalc.d09_matric as d08_matric ,contricalc.d09_numpre,contrib.d07_valor,edital.d01_numero,ruas.j14_nome,ruas.j14_tipo,edital.d01_numtot,edital.d01_perunica,d01_privenc, proprietario.z01_nome,proprietario.z01_ender,proprietario.z01_numero,proprietario.z01_munic,proprietario.z01_uf,proprietario.z01_compl,proprietario.z01_numcgm,proprietario.z01_bairro,proprietario.z01_cep","",$contr));

$result = $clnotificacao->sql_record($clnotificacao->sql_noticontri($contribuicao,"","","contrinot.d08_notif,contrib.d07_contri as d08_contri,contricalc.d09_matric as d08_matric ,contricalc.d09_numpre,contrib.d07_valor,edital.d01_numero,ruas.j14_nome,ruas.j14_tipo,edital.d01_numtot,edital.d01_perunica,d01_privenc, proprietario.z01_nome,proprietario.z01_ender,proprietario.z01_numero,proprietario.z01_munic,proprietario.z01_uf,proprietario.z01_compl,proprietario.z01_numcgm,proprietario.z01_bairro,proprietario.z01_cep","",$contr));
if ($clnotificacao->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Sem notificações a serem geradas. Verifique!');
}
$pdf->setfillcolor(235);
$preenc = 1;
$pdf->SetFont('Arial','',8);
$linha = 0;
//for($x=0;$x < 8;$x++){
for($x=0;$x < $clnotificacao->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($x%4==0){
      $pdf->addpage();
      $linha = 1;
   }
   $pdf->Image('imagens/files/'.$logo,5,$linha,12);
   $pdf->SetFont('Arial','b',12);
   $pdf->text(30,$linha+5,$nomeinst);
   $pdf->text(50,$linha+12,"Notificação : ".db_formatar($d08_notif,'s','0',5,'e'));
   $pdf->SetFont('Arial','',10);
   $pdf->text(10,$linha+22,"Destinatário : ".$z01_nome);
   $pdf->text(10,$linha+27,"Matrícula N".chr(176)."  ".$d08_matric);
   $pdf->text(10,$linha+32,"Endereço : ".$z01_ender.", ".$z01_numero."  ".$z01_compl."       CEP: ".$z01_cep);
   $pdf->text(10,$linha+37,"Bairro : ".$z01_bairro."      Município : ".$z01_munic." - ".$z01_uf);
   $pdf->SetFont('Arial','B',10);
   $pdf->text(10,$linha+45,"NOME LEGÍVEL : ...................................................................................");
   $pdf->SetFont('Arial','',10);
   $pdf->text(10,$linha+55,"______/______/_________");
   $pdf->text(65,$linha+55,"_________________________________________");
   $pdf->text(150,$linha+55,"_________________________");
   $pdf->text(12,$linha+60,"DATA DE ENTREGA");
   $pdf->text(75,$linha+60,"ASSINATURA DO DESTINATÁRIO");
   $pdf->text(165,$linha+60,"FUNC. DA ETC");
   $linha += 76;
}
$pdf->Output();