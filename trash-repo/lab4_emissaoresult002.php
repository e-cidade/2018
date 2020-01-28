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

$cllab_requisicao = new cl_lab_requisicao;
$cllab_requiitem = new cl_lab_requiitem;
$cllab_emissao = new cl_lab_emissao;
$cllab_atributo_componente = new cl_lab_atributo_componente;
$usuario= DB_getsession("DB_id_usuario");

$dDataAtual = date('Y-m-d', db_getsession('DB_datausu'));
$where = " la22_i_codigo = $requisicao and la21_i_codigo= $requiitem";
$result = $cllab_requiitem->sql_record($cllab_requiitem->sql_query_nova("","*,fc_idade(z01_d_nasc, '$dDataAtual') as idade,la24_c_nomearq as imagem","",$where));
if ($cllab_requiitem->numrows==0) {

  db_msgbox("Nenhum registro encontrado!");

}else{

  db_fieldsmemory($result,0);
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->settopmargin(1);
  $pdf->SetAutoPageBreak('on',0);
  $pdf->setfillcolor(243);
  $head2 = "Emissão de Resultado";
  $head3 = "Exame : $la08_c_descr"; 
  $pdf->addpage('P');
  $pdf->ln(0);
  $alt = $pdf->getY();
  $larg= $pdf->getX();

  $pdf->roundedrect(8,35,$larg+185,$alt-10,3,'','1234');
  $pdf->setfont('arial','b',8);
  $pdf->setY(40);
  $pdf->cell(100,6,"Paciente : $la22_i_cgs - $z01_v_nome",0,0,"L",0);
  $pdf->cell(170,6,"Requisição : $la22_i_codigo",0,1,"L",0);
  $pdf->cell(100,6,"Idade : $idade",0,0,"L",0);
  if ($z01_v_sexo=='F') {
    $sexo="FEMININO";
  } else {
    $sexo="MASCULINO";
  }
  $pdf->cell(100,6,"Exame  :  $la08_c_descr",0,1,"L",0);  
  $pdf->cell(100,6,"Médico : $la22_c_medico",0,0,"L",0);
  $pdf->cell(100,6,"Sexo : $sexo",0,1,"L",0);
  $pdf->roundedrect(8,62,$larg+185,$alt+150,3,'','1234');
  $pdf->setfont('arial','b',8);
  $alt = $pdf->getY();
  $larg= $pdf->getX();
  $pdf->roundedrect(8,250,$larg+185,$alt-30,3,'','1234');  
  $pdf->setfont('arial','b',8);  
  $pdf->setY(65);  
  if (isset($requiitem)) {
   	$sSql=$cllab_requiitem->sql_query_nova($requiitem,"la08_i_codigo,la42_i_atributo");
   	$rResult=$cllab_requiitem->sql_record($sSql);
   	if ($cllab_requiitem->numrows>0) {
   	    db_fieldsmemory($rResult,0);
   	 }
   	 
  }
  if ((isset($la08_i_codigo))&&(isset($la42_i_atributo))) { 
      $cllab_atributo_componente->atributosPDF($pdf,$la08_i_codigo,$la42_i_atributo,$requiitem,1);      
  }   
  $pdf->cell(50,5,"",0,1,"L",0);
  $pdf->cell(10,5,"Diagnóstico:",0,1,"L",0);
  $pdf->multicell(0,3,"$la08_t_diagnostico",0,1,"J",0);
  if ($la24_o_assinatura) {
  	
   $arquivo = "tmp/".$la24_c_nomearq;
   pg_exec("begin");
   pg_loexport($la24_o_assinatura,$arquivo);
   pg_exec("end");
   
  } else {
      $arquivo = "";
  }
  $pdf->setY(260);
  $pdf->Image($arquivo,90,$alt+198,25);
  $pdf->cell(110,6," Profissional : $la24_i_resp - $z01_nome",0,1,"L",0);
  $pdf->cell(160,6," Órgão Classe : $la06_c_orgaoclasse ",0,1,"L",0);
  $sNome = "Resultado($la22_i_cgs)".$la22_i_codigo."_".date("d-m-Y",db_getsession("DB_datausu")).".pdf";    
  $pdf->Output("tmp/$sNome",false,true);
  db_inicio_transacao();
  $oidgrava = db_geraArquivoOidfarmacia("tmp/$sNome","",1,$conn); 
  $cllab_emissao->la34_o_laudo = $oidgrava;
  $cllab_emissao->la34_c_nomearq    = "tmp/$sNome";
  $cllab_emissao->la34_d_data       = date("Y-m-d",db_getsession("DB_datausu"));
  $cllab_emissao->la34_c_hora       = db_hora();
  $cllab_emissao->la34_i_requiitem  = $requiitem;
  $cllab_emissao->la34_i_usuario    = $usuario; 
  $cllab_emissao->la34_i_forma      = 1;
  $cllab_emissao->incluir(null);
  db_fim_transacao() ;
  ?>
  <script>
  jan = window.open('tmp/<?=$sNome?>',
                    '',
                    'width='+(screen.availWidth-5)+
                    ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  </script>
  <?
}
?>