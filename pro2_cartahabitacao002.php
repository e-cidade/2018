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
include("fpdf151/pdf1.php");
include("classes/db_obrashabite_classe.php");
include("classes/db_obraspropri_classe.php");
include("classes/db_obrasender_classe.php");
include("classes/db_obraslote_classe.php");
include("classes/db_obraslotei_classe.php");
$clobrashabite=new cl_obrashabite;
$clobraspropri=new cl_obraspropri;
$clobrasender=new cl_obrasender;
$clobraslote=new cl_obraslote;
$clobraslotei=new cl_obraslotei;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//echo $codigo;exit;
if(!isset($codigo) || $codigo==''){
  db_redireciona('db_erros.php?fechar=true&db_erro=Carta de habitação não encontrada!');
}
$borda = 1; 
$bordat = 1;
$preenc = 0;
$TPagina = 57;
$xnumpre = '';
$result_obrashabite=$clobrashabite->sql_record($clobrashabite->sql_query($codigo,"ob08_codconstr,ob08_area,ob01_codobra,ob01_nomeobra"));
if($clobrashabite->numrows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Carta de habitação no. '.$codigo. ' não Encontrada.');
  exit; 
}
db_fieldsmemory($result_obrashabite,0);

$result_obraspropri=$clobraspropri->sql_record($clobraspropri->sql_query($ob01_codobra,"z01_nome"));
if($clobraspropri->numrows>0){
  db_fieldsmemory($result_obraspropri,0);
}

$result_obrasender=$clobrasender->sql_record($clobrasender->sql_query($ob08_codconstr,"ob07_numero,j13_descr,j14_nome"));
if($clobrasender->numrows>0){
  db_fieldsmemory($result_obrasender,0);
}

$result_obraslote=$clobraslote->sql_record($clobraslote->sql_query($ob01_codobra,"j34_lote as lote,j34_quadra as quadra"));
if($clobraslote->numrows>0){
  db_fieldsmemory($result_obraslote,0);
}else{
  $result_obraslotei=$clobraslotei->sql_record($clobraslotei->sql_query($ob01_codobra,"ob06_quadra as quadra,ob06_lote as lote"));
  if($clobraslotei->numrows>0){
    db_fieldsmemory($result_obraslotei,0);
  }
}
$data = date("d/m/Y",DB_getsession("DB_datausu"));

$head1 = 'Departamento de Cadastro Imobiliário';
$pdf = new PDF1(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
/////// TEXTOS E ASSINATURAS

$instit = db_getsession("DB_instit");
$sqltexto = "select * from db_textos where id_instit = $instit and ( descrtexto like 'habite%' or descrtexto like 'ass_habite%')";
$resulttexto = pg_exec($sqltexto);
for( $xx = 0;$xx < pg_numrows($resulttexto);$xx ++ ){
  db_fieldsmemory($resulttexto,$xx);
  $text  = $descrtexto;
  $$text = db_geratexto($conteudotexto);
}
$alt=4;
////////relatorio
$pdf->SetFont('Arial','B',15);
$pdf->MultiCell(0,$alt,$habite_tit,0,"C",0,0);
$pdf->Ln(4);
$pdf->SetFont('Arial','',11);
$pdf->MultiCell(0,$alt,$habite_p1,0,"J",0,40);

$pdf->Ln(2);
$pdf->MultiCell(0,5,$habite_p2,0,"J",0,40);

$pdf->Ln(30);
$pdf->MultiCell(0,$alt,$ass_habite,0,"C",0);

$pdf->Output();
?>