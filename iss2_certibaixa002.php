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

require('fpdf151/pdf1.php');
db_postmemory($HTTP_POST_VARS);
$sql = "select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".@$GLOBALS["DB_instit"];
$result = pg_query($sql);
db_fieldsmemory($result,0);
if(isset($inscr)){
  $tipocer="Certidão de Baixa de Alvará";
  $codtipo = 28;
  $sql_issbase = "select * from issbase where q02_inscr=$inscr";
  $result_issbase = pg_query($sql_issbase);
  if (pg_numrows($result_issbase)!=0){
  db_fieldsmemory($result_issbase,0,true);
  }

  
  $sqldescr = "select * from tabativ inner join ativid on q03_ativ = q07_ativ where q07_inscr=$inscr";
  $result_descr=pg_exec($sqldescr);
  $descr = "";
  for($y=0;$y<pg_numrows($result_descr);$y++){
    db_fieldsmemory($result_descr,$y);
    $descr .= $q03_descr . " - " . db_formatar($q07_datain,'d') . "\n";
  }
  
  $sqlprocbaix = "select q11_inscr,q11_seq,q11_processo,case when q11_oficio = 'f' then 'Normal' when 't' then 'Ofício' end as tipo_baixa,q11_login,q11_data,q11_hora,q11_numero from tabativbaixa where q11_inscr=$inscr";
  $result_procbaix = pg_query($sqlprocbaix);
  if (pg_numrows($result_procbaix)!=0){
  db_fieldsmemory($result_procbaix,0);
  }
  $sql = "select * from empresa where q02_inscr = $inscr";
  $result = pg_query($sql);
  db_fieldsmemory($result,0,true);
  /////// TEXTOS E ASSINATURAS
  $instit = db_getsession("DB_instit");
  $sqltexto = "select * from db_textos where id_instit = $instit and ( descrtexto like 'baixa%')";
  $resulttexto = pg_exec($sqltexto);
  for( $xx = 0;$xx < pg_numrows($resulttexto);$xx ++ ){
      db_fieldsmemory($resulttexto,$xx);
      $text  = $descrtexto;
      $$text = db_geratexto($conteudotexto); 
  }  
  
  $texto1 = @$baixa_ins1;
  $texto2 = @$baixa_ins2;
  $texto3 = @$baixa_ins3;
  $texto4  = '';

}	 
  

       
//echo $codproc;
$head1 = "DEPARTAMENTO DE FAZENDA";
//$head4 = "CERTIDÃO No. ".$codproc;
//$head6 = $tipocer;
$pdf = new PDF1(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$Letra = 'Times';
$pdf->MultiCell(0,4,$tipocer.' N'.chr(176).' '.@$q11_numero,0,"C",0);
$pdf->SetFont($Letra,'',11);
$pdf->Ln(15);

$pdf->Cell(3,1,"",0,0,"L",0).$pdf->MultiCell(0,6,$texto1,0,"J",0,30);
$pdf->Ln(5);
$pdf->Cell(3,1,"",0,0,"L",0).$pdf->MultiCell(0,6,$texto2,0,"J",0,30);
$pdf->Ln(5);
$pdf->Cell(3,1,"",0,0,"L",0).$pdf->MultiCell(0,6,$texto3,0,"J",0,30);
$pdf->Ln(5);
$pdf->Cell(3,1,"",0,0,"L",0).$pdf->MultiCell(0,6,$texto4,0,"J",0,30);

$pdf->Cell(10,4,"",0,1,"L",0);
$pdf->MultiCell(0,8,$nomeinst.', '.date('d')." de ".db_mes(date('m'))." de ".date('Y').'.',0,0,"R",0);
$pdf->Cell(10,20,"",0,1,"L",0);
$pdf->SetY(150);
//$pdf->Ln(25);
$pdf->SetY(252);
//$pdf->MultiCell(0,4,"\n\n\n".'Funcionário'.$ass_ageadm1,0,"C",0);
$y = $pdf->GetY();
$pdf->MultiCell(90,5,'_________________________________',0,"C",0);
$pdf->SetXY(110,$y);
$pdf->MultiCell(90,5,'_________________________________',0,"C",0);

$resultnomelogin = pg_exec("select nome from db_usuarios where id_usuario = " . db_getsession("DB_id_usuario"));
$resultmatric = pg_exec(" select  'Matricula:'|| rh01_regist  from db_usuarios left join db_usuacgm on db_usuarios.id_usuario = db_usuacgm.id_usuario left join  rhpessoal on cgmlogin = rh01_numcgm left join rhpessoalmov on rh02_regist = rh01_regist left join rhpesrescisao on rh05_seqpes = rh02_seqpes where rh05_seqpes is null and rh05_recis is null and db_usuarios.id_usuario = " . db_getsession("DB_id_usuario")." order by rh01_regist desc limit 1");
$pdf->MultiCell(90,4,pg_result($resultnomelogin,0,0),0,"C",0);
$pdf->MultiCell(90,4,pg_result($resultmatric,0,0),0,"C",0);
$y = $pdf->GetY();
$pdf->SetXY(110,$y);

$pdf->Output();
?>