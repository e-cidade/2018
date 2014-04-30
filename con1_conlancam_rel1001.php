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


 include_once("fpdf151/pdf.php");

// db_postmemory($HTTP_SERVER_VARS,2);
// db_msgbox($dt1);
// exit();


   //--
   $sql=base64_decode($sql);
   $resultsql = pg_exec(str_replace('\\','',$sql));
   if($resultsql==false){
       db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado para o gerar o relatório. Verifique');  
   }
  //-- 
  $dt = date("d/m/Y");
  $head1 = "Relatorio de Lançamentos $dt";
    switch ($pesquisa){
          case 1: $head2="Numero do Lote/Chave: $codigo"; break;
          case 2: $head2="Codigo do Lançamento: $codigo"; break;
          case 3: $head2="Codigo da Suplementação: $codigo"; break;
	  case 4: $head2="Codigo da Receita: $codigo"; break;
          case 5: $head2="Codigo da Dotação: $codigo"; break;
  	  case 6: $head2="Codigo do Empenho: $codigo"; break;
	  case 7: $head2="Codigo do Documento: $codigo"; break;
	  case 8: $head2="Codigo do CGM: $codigo"; break;
    }	
  $head3 = "de : $dt1";
  $head4 = "àte :  $dt2";
  $head5 = "";
  $head6 = "";
  $head7 = "";
  $head8 = "";
  $head9 = "";
  
//$DB_instit = 1;
 
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->AddPage("P");

  // monta cabecalho do relatório    
  $pdf->SetFillColor(235);
  $pdf->SetFont('Arial','B',9);
  $pdf->setY(40);
  $pdf->setX(5);

  $clrotulolov = new rotulolov; 
  $fm_numfields = pg_numfields($resultsql); //campos de cada linha do resultser
   
  $linha = 0;
  for ($xi=0;$xi<pg_numrows($resultsql);$xi++)
  {     
       //-- cria label e tamanho para todos os campos da linha $xi   
       for ($i = 0;$i < $fm_numfields;$i++)
       {    /* $campo_titulo["nome do campo"] - label do campo 
               $tamanho["nome do campo "] - tamanho do campo
            */
	    $clrotulolov->label(pg_fieldname($resultsql,$i));
            $campo_titulo[pg_fieldname($resultsql,$i)] = $clrotulolov->titulo ;
            if($clrotulolov->tamanho==""){
   	         $tamanho[pg_fieldname($resultsql,$i)] = 20;
            }else{
  	         $tamanho[pg_fieldname($resultsql,$i)] = (($clrotulolov->tamanho>strlen($clrotulolov->titulo)?$clrotulolov->tamanho:strlen($clrotulolov->titulo))*2)+2;
            }
        }	
      //--
       $y=$pdf->getY();
       $pdf->setY($y+5);
       $pdf->setX(35);
      //-- imprime colunas
      // $pdf->Cell($tamanho["c78_chave"],4,$campo_titulo["c78_chave"],"LRBT",0,"L",0);
       $pdf->Cell($tamanho["c69_codlan"],4,$campo_titulo["c69_codlan"],"LRBT",0,"L",0);
       $pdf->Cell($tamanho["c69_data"],4,$campo_titulo["c69_data"],"LRBT",0,"L",0);
       $pdf->Cell($tamanho["c69_credito"],4,$campo_titulo["c69_credito"],"LRBT",0,"C",0);
       $pdf->Cell($tamanho["c69_debito"],4,$campo_titulo["c69_debito"],"LRBT",0,"C",0);
       $pdf->Cell($tamanho["c69_valor"],4,$campo_titulo["c69_valor"],"LRBT",1,"L",0);
      // conteúdo dos campos
       $pdf->setX(35);
      // $pdf->Cell($tamanho["c78_chave"],4,pg_result($resultsql,$xi,"c78_chave"),"LRBT",0,"L",1);
       $pdf->Cell($tamanho["c69_codlan"],4,pg_result($resultsql,$xi,"c69_codlan"),"LRBT",0,"L",1);
       $pdf->Cell($tamanho["c69_data"],4,pg_result($resultsql,$xi,"c69_data"),"LRBT",0,"L",1);
       $pdf->Cell($tamanho["c69_credito"],4,pg_result($resultsql,$xi,"c69_credito"),"LRBT",0,"C",1);
       $pdf->Cell($tamanho["c69_debito"],4,pg_result($resultsql,$xi,"c69_debito"),"LRBT",0,"C",1);
       $pdf->Cell($tamanho["c69_valor"],4,pg_result($resultsql,$xi,"c69_valor"),"LRBT",1,"L",1);
      // --
       $itens="S";
       if (isset($itens) and ($itens =="S")) { 
	   $c_debito =pg_result($resultsql,$xi,"c69_debito");
	   $c_credito=pg_result($resultsql,$xi,"c69_credito");
	   $sql_c="select c60_descr as credito_descr
                     from conplano 
	                     inner join conplanoreduz on conplanoreduz.c61_codcon=conplano.c60_codcon and 
                                                                      conplanoreduz.c61_anousu=conplano.c60_anousu and 
		                                                      conplanoreduz.c61_reduz=$c_credito
                    where    conplano.c60_anousu=".db_getsesson("DB_anousu");
	   $sql_d="select c60_descr as debito_descr  
                     from conplano 
	                     inner join conplanoreduz on conplanoreduz.c61_codcon=conplano.c60_codcon and
                                                                      conplanoreduz.c61_anousu=conplano.c60_anousu and 
		                                                        conplanoreduz.c61_reduz=$c_debito
                                            conplano.c60_anousu=".db_getsesson("DB_anousu");
           $res_debito=pg_exec($sql_d);
	   $res_credito=pg_exec($sql_c);
           $pdf->setX(35); // credito_descr
           $pdf->Cell( 40,4,'Credito  ',"LRBT",0,"L",0);
              $pdf->Cell($tamanho["c50_descr"],4,pg_result($res_credito,0,"credito_descr"),"0",1,"L",0);
           $pdf->setX(35); // credito_descr
           $pdf->Cell( 40,4,'Debito  ',"LRBT",0,"L",0);
              $pdf->Cell($tamanho["c50_descr"],4,pg_result($res_debito,0,"debito_descr"),"0",1,"L",0);

      // -- contagem linhas
        $linha += 4;
      } else {
        $linha += 2;
       
      }
      //-- quebra de pagina
      //    $linha += 7;
      if($linha>27)
      {
         $linha = 0;
         $pdf->AddPage("P");
      }
   }
   ///////
 
//  $pdf->Cell(200,2,"","",1,"L",0);
//  $pdf->Cell(200,0,"","LRBT",1,"L",0);

// $tmpfile=tempnam("tmp","tmp.pdf");

  $pdf->Output();


?>