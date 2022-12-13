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

require("fpdf151/scpdf.php");
include("fpdf151/impfarmacia.php");
include("libs/db_sql.php");
include("classes/db_lab_requisicao_classe.php");
include("classes/db_lab_requiitem_classe.php");
$cllab_requisicao = new cl_lab_requisicao;
$cllab_requiitem = new cl_lab_requiitem;
$sqlpref  = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = pg_exec($sqlpref);
db_fieldsmemory($resultpref,0);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$result=$cllab_requisicao->sql_record($cllab_requisicao->sql_query("","*","","la22_i_codigo=$la22_i_codigo"));
 if($cllab_requisicao->numrows>0){
   db_fieldsmemory($result,0);
 }else{
   db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Registro Encontrado ! ");
 }
$numrows_labrequisicao = $cllab_requisicao->numrows;

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,887);
$pdf1->objpdf->SetTextColor(0,0,0);
$pdf1->Snumero_ant = "";
for($contador=0;$contador<$numrows_labrequisicao;$contador++){
  db_fieldsmemory($result,$contador);
  $pdf1->logo			  = $logo; 
  $pdf1->prefeitura = $nomeinst;
  $pdf1->enderpref  = $ender;
  $pdf1->municpref  = $munic;
  $pdf1->telefpref  = $telef;
  $pdf1->emailpref  = $email;
  $pdf1->emissao    = date("Y-m-d",db_getsession("DB_datausu"));
  $pdf1->cgcpref    = $cgc;

  $result_itens     = $cllab_requisicao->sql_record($cllab_requisicao->sql_query_requiitem("","la20_t_descr as requisito",""," la22_i_codigo=$la22_i_codigo and la21_i_setorexame in($sListaExames)",true));
  $sRequisitos = "";
  for($x=0;$x<$cllab_requisicao->numrows;$x++){
      db_fieldsmemory($result_itens,$x);
      $sRequisitos .= " | $x-".$requisito;
  }

  $sSql=$cllab_requisicao->sql_query_requiitem("","*",""," la22_i_codigo=$la22_i_codigo and la21_i_setorexame in($sListaExames)",false);
  $result_itens     = $cllab_requisicao->sql_record($sSql);
  db_fieldsmemory($result_itens,0);

  $pdf1->Rlaboratorio = $la02_c_descr;
  $pdf1->Rnumero    = $la22_i_codigo;
  $pdf1->Rdepart    = $descrdepto;
  $pdf1->Rdata      = $la21_d_data;
  $pdf1->Rusuario   = $la22_i_usuario;
  $pdf1->Rmedico    = $la22_c_medico;
  $pdf1->Rhora      = $la21_c_hora;
  $pdf1->Rpaciente  = $la22_i_cgs." ".$z01_v_nome;
  $pdf1->Rresumo    = $la22_t_observacao;
  $pdf1->emissao    = date("Y-m-d",db_getsession("DB_datausu"));
  $pdf1->Rresponsavel = $la22_c_responsavel;
  $pdf1->Rcontato = $la22_c_contato;
  $pdf1->Rnomeusuario = $nome;
  $pdf1->Rrequisito = $sRequisitos;
  
  $numrows_itens = $cllab_requisicao->numrows;

  $pdf1->rcodrequisicao  = "la22_i_codigo";
  $pdf1->rsetor          = "la23_c_descr";
  $pdf1->rexame          = "la08_c_descr";
  $pdf1->rcodigoexame    = "la08_i_codigo";
  $pdf1->rdata           = "la21_d_data";
  $pdf1->rhora           = "la21_c_hora";
  $pdf1->rentrega        = "la21_d_entrega";


  $pdf1->recorddositens = $result_itens;
  $pdf1->linhasdositens = $numrows_itens;

  $pdf1->imprime();
  $pdf1->Snumero_ant = $la22_i_codigo;
}
if(isset($argv[1])){
  $pdf1->objpdf->Output("/tmp/teste.pdf");
}else{
  $pdf1->objpdf->Output();
}
?>