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
//include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;

/*if($clfar_retirada->numrows == 0){
	echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
   exit;
   }
db_fieldsmemory($result,0);*/

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "RELAÇÃO MENSAL DE NOTIFICAÇÃO";
$head2 = "";

 $pdf->ln(5);
 $pdf->addpage('L');
 $total=0;
 $cont=0;
 $pdf->setfont('arial','b',8);
 
 $pdf->cell(120,5,"SECRETARIA DE SAÚDE:________________________________",0,1,"C",0);
 $pdf->cell(120,5,"AUTORIDADE SANITÁRIA:________________________________",0,1,"C",0);
 $pdf->cell(270,5,"BALANÇO DE MEDICAMENTOS PSICOATIVOS E OUTROS SUJEITOS A CONTROLE ESPECIAL - BMPO",0,1,"C",0);
 $pdf->rect( 10, 50, 280, 140, "D"); //RETANGULO PRINCIPAL
 $pdf->setY(40);
 $pdf->cell(65,25,"IDENTIFICAÇÃO DO ESTABELECIMENTO",0,1,"C",0);
 $pdf->rect( 13, 55, 273, 55, "D"); // PRIMEIRO RETANGULO 
 $pdf->rect( 15, 57, 55, 50, "D");  //CARIMBRO
 $pdf->setY(40);
 $pdf->setX(45);
 $pdf->line(75,64,275,64);
 $pdf->setY(55);
 $pdf->setX(65);
 $pdf->cell(65,25,"RAZÃO SOCIAL",0,1,"C",0);
 $pdf->setY(65);
 $pdf->setX(45);
 $pdf->line(75,75,275,75);
 $pdf->setY(65);
 $pdf->setX(65);
 $pdf->cell(65,25,"ENDEREÇO",0,1,"C",0);
 //CNPJ
 $pdf->setY(90);
 $pdf->setX(85);
 $pdf->cell(80,25,"TELEFONE: (   )___________________________________________________",0,0,"C",0);
 $pdf->cell(120,25,"FAX: (   )________________________________________________________",0,0,"C",0);
 $pdf->setY(100);
 $pdf->cell(55,25,"IDENTIFICAÇÃO DO FORMULÁRIO",0,1,"C",0);
 $pdf->setY(110);
 $pdf->cell(40,25,"BALANÇO: Exercício",0,0,"C",0);
 $pdf->cell(40,25,"Anual",0,0,"C",0);
 $pdf->cell(40,25,"Trimestral",0,0,"C",0);
 $pdf->cell(40,25,"Período:__/__/____ A __/__/____",0,0,"C",0);
 $pdf->rect( 13, 140, 273, 20, "D"); //IDENTIFICACAO DO FORMULARIO
 $pdf->setY(125);
 $pdf->cell(90,25,"IDENTIFICAÇÃO DO RESPONSÁVEL PELA INFORMAÇÃO",0,0,"C",0);
 $pdf->setY(135);
 $pdf->cell(40,25,"PREENCHIDO POR:",0,0,"C",0);
 $pdf->cell(40,25,"C.R.F",0,0,"C",0);
 $pdf->cell(40,25,"REGIÃO",0,0,"C",0);
 $pdf->cell(40,25,"DATA:__/__/____",0,1,"C",0);
 $pdf->setY(145);
 $pdf->setx(50);
 $pdf->cell(40,25,"ASSINATURA:__________________________________________________________",0,0,"C",0);
 $pdf->rect( 13, 115, 273, 20, "D");// IDENTIFICACAO DO REPSONSAVEL PELA INFORMACAO
 $pdf->setY(150);
 $pdf->setX(55);
 $pdf->cell(80,25,"IDENTIFICAÇÃO DO RESPONSÁVEL PELO PREENCHIMENTO (USO EXCLUSIVO DA AUTORIDADE SANITÁRIA LOCAL)",0,0,"C",0); 
 $pdf->rect( 13, 165, 273, 20, "D"); //USO EXCLUSIVO DA SANITARIA
 $pdf->setY(159);
 $pdf->cell(40,25,"RECEBIDO POR:",0,0,"C",0);
 $pdf->cell(40,25,"R.G",0,0,"C",0);
 $pdf->cell(40,25,"CARGO",0,0,"C",0);
 $pdf->cell(40,25,"DATA:__/__/____",0,1,"C",0);
 $pdf->setY(164);
 $pdf->cell(40,25,"CONFERIDO POR:",0,0,"C",0);
 $pdf->cell(40,25,"R.G",0,0,"C",0);
 $pdf->cell(40,25,"CARGO",0,0,"C",0);
 $pdf->cell(40,25,"DATA:__/__/____",0,0,"C",0);
$pdf->Output();
?>