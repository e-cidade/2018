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

include("fpdf151/pdfwebseller.php");
include("classes/db_matricula_classe.php");
include("classes/db_aluno_classe.php");
include("classes/db_alunonecessidade_classe.php");
include("classes/db_escola_classe.php");
$clmatricula = new cl_matricula;
$clescola = new cl_escola;
$claluno = new cl_aluno;
$clalunonecessidade = new cl_alunonecessidade;
$escola = db_getsession("DB_coddepto");
$result = $claluno->sql_record($claluno->sql_query("","*","ed47_v_nome"," ed47_i_codigo in ($alunos)"));
if($claluno->numrows==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhuma registro encontrado.<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
 <?
 exit;
}
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
for($x=0;$x<$claluno->numrows;$x++){
 db_fieldsmemory($result,$x);
 $pdf->setfillcolor(223);
 $head2 = "FICHA DO ALUNO";
 $pdf->addpage('P');
 $pdf->ln(5);
 $pdf->setfont('arial','',7);
 $pdf->cell(190,5,"GERAL",1,1,"C",1);
 $pdf->cell(190,5,"","LR",1,"C",0);
 $alt_geral = $pdf->getY();
 $pdf->cell(5,45,"","L",0,"C",0);
 $pdf->cell(30,5,"Cgm:",0,2,"L",0);
 $pdf->cell(30,5,"Nome:",0,2,"L",0);
 $pdf->cell(30,5,"Pai:",0,2,"L",0);
 $pdf->cell(30,5,"Mãe:",0,2,"L",0);
 $pdf->cell(30,5,"Data Nascimento:",0,2,"L",0);
 $pdf->cell(30,5,"Estado Civil:",0,2,"L",0);
 $pdf->cell(30,5,"Sexo:",0,2,"L",0);
 $pdf->cell(30,5,"Nacionalidade:",0,2,"L",0);
 $pdf->cell(30,5,"Naturalidade:",0,2,"L",0);
 $pdf->setY($alt_geral);
 $pdf->setX(40);
 $pdf->setfont('arial','b',7);
 $pdf->cell(65,5,$ed47_i_codigo,0,2,"L",0);
 $pdf->cell(65,5,$ed47_v_nome,0,2,"L",0);
 $pdf->cell(65,5,$ed47_v_pai,0,2,"L",0);
 $pdf->cell(65,5,$ed47_v_mae,0,2,"L",0);
 $pdf->cell(65,5,db_formatar($ed47_d_nasc,'d'),0,2,"L",0);
 if($ed47_i_estciv==1){
  $ed47_i_estciv = "SOLTEIRO";
 }elseif($ed47_i_estciv==2){
  $ed47_i_estciv = "CASADO";
 }elseif($ed47_i_estciv==3){
  $ed47_i_estciv = "VIÚVO";
 }else{
  $ed47_i_estciv = "DIVORCIADO";
 }
 $pdf->cell(65,5,$ed47_i_estciv,0,2,"L",0);
 $pdf->cell(65,5,$ed47_v_sexo=="M"?"MASCULINO":"FEMININO",0,2,"L",0);
 $pdf->cell(65,5,$ed47_i_nacion=="1"?"BRASILEIRO":"ESTRANGEIRO",0,2,"L",0);
 $pdf->cell(65,5,$ed47_c_naturalidade,0,2,"L",0);
 $pdf->setY($alt_geral);
 $pdf->setX(105);
 $pdf->setfont('arial','',7);
 $pdf->cell(40,5,"Raça:",0,2,"L",0);
 $pdf->cell(40,5,"E-mail:",0,2,"L",0);
 $pdf->cell(40,5,"Nome Responsável:",0,2,"L",0);
 $pdf->cell(40,5,"E-mail Responsável:",0,2,"L",0);
 $pdf->cell(40,5,"",0,2,"L",0);
 $pdf->cell(40,5,"Atendimento Especializado:",0,2,"L",0);
 $pdf->cell(40,5,"Telefone:",0,2,"L",0);
 $pdf->cell(40,5,"Celular:",0,2,"L",0);
 $pdf->cell(40,5,"Data Cadastro:",0,2,"L",0);
 $pdf->setY($alt_geral);
 $pdf->setX(145);
 $pdf->setfont('arial','b',7);
 $pdf->cell(55,5,$ed47_c_raca,"R",2,"L",0);
 $pdf->cell(55,5,strtolower($ed47_v_email),"R",2,"L",0);
 $pdf->cell(55,5,$ed47_c_nomeresp,"R",2,"L",0);
 $pdf->cell(55,5,$ed47_c_emailresp,"R",2,"L",0);
 $pdf->cell(55,5,"","R",2,"L",0);
 $pdf->cell(55,5,$ed47_c_atendesp==""?"NÃO":$ed47_c_atendesp,"R",2,"L",0);
 $pdf->cell(55,5,$ed47_v_telef,"R",2,"L",0);
 $pdf->cell(55,5,$ed47_v_telcel,"R",2,"L",0);
 $pdf->cell(55,5,db_formatar($ed47_d_cadast,'d'),"R",1,"L",0);
 $pdf->cell(190,5,"","LR",1,"C",0);
 $pdf->cell(190,5,"ENDEREÇO",1,1,"C",1);
 $pdf->cell(190,5,"","LR",1,"C",0);
 $alt_geral = $pdf->getY();
 $pdf->cell(5,20,"","L",0,"C",0);
 $pdf->cell(30,5,"Endereço:",0,2,"L",0);
 $pdf->cell(30,5,"Complemento:",0,2,"L",0);
 $pdf->cell(30,5,"Bairro:",0,2,"L",0);
 $pdf->cell(30,5,"Cep:",0,2,"L",0);
 $pdf->setY($alt_geral);
 $pdf->setX(40);
 $pdf->setfont('arial','b',7);
 $pdf->cell(65,5,$ed47_v_ender.", ".$ed47_i_numero,0,2,"L",0);
 $pdf->cell(65,5,$ed47_v_compl,0,2,"L",0);
 $pdf->cell(65,5,$ed47_v_bairro,0,2,"L",0);
 $pdf->cell(65,5,$ed47_v_cep,0,2,"L",0);
 $pdf->setY($alt_geral);
 $pdf->setX(105);
 $pdf->setfont('arial','',7);
 $pdf->cell(40,5,"Cidade:",0,2,"L",0);
 $pdf->cell(40,5,"Estado:",0,2,"L",0);
 $pdf->cell(40,5,"Trasnporte Escolar:",0,2,"L",0);
 $pdf->cell(40,5,"Zona Localização:",0,2,"L",0);
 $pdf->setY($alt_geral);
 $pdf->setX(145);
 $pdf->setfont('arial','b',7);
 $pdf->cell(55,5,$ed47_v_munic,"R",2,"L",0);
 $pdf->cell(55,5,$ed47_v_uf,"R",2,"L",0);
 $pdf->cell(55,5,$ed47_c_transporte==""?"NÃO":$ed47_c_transporte,"R",2,"L",0);
 $pdf->cell(55,5,$ed47_c_zona==""?"NÃO":$ed47_c_zona,"R",1,"L",0);
 $pdf->cell(190,5,"","LR",1,"C",0);
 $pdf->cell(190,5,"DOCUMENTOS",1,1,"C",1);
 $pdf->cell(190,5,"","LR",1,"C",0);
 $alt_geral = $pdf->getY();
 $pdf->setfont('arial','',7);
 $pdf->cell(5,30,"","L",0,"C",0);
 $pdf->cell(30,5,"CPF:",0,2,"L",0);
 $pdf->cell(30,5,"Identidade:",0,2,"L",0);
 $pdf->cell(30,5,"N° NIS:",0,2,"L",0);
 $pdf->cell(30,5,"Bolsa Família:",0,2,"L",0);
 $pdf->cell(30,5,"",0,2,"L",0);
 $pdf->cell(30,5,"",0,2,"L",0);
 $pdf->setY($alt_geral);
 $pdf->setX(40);
 $pdf->setfont('arial','b',7);
 $pdf->cell(65,5,$ed47_v_cpf,0,2,"L",0);
 $pdf->cell(65,5,$ed47_v_ident,0,2,"L",0);
 $pdf->cell(65,5,$ed47_c_nis,0,2,"L",0);
 $pdf->cell(65,5,$ed47_c_bolsafamilia=="N"?"NÃO":"SIM",0,2,"L",0);
 $pdf->cell(65,5,"",0,2,"L",0);
 $pdf->cell(65,5,"",0,2,"L",0);
 $pdf->setY($alt_geral);
 $pdf->setX(105);
 $pdf->setfont('arial','',7);
 $tipo = $ed47_c_certidaotipo=="N"?"NASCIMENTO":"CASAMENTO";
 $pdf->cell(40,5,"CERTIDÂO DE $tipo",0,2,"L",0);
 $pdf->cell(40,5,"Número:",0,2,"L",0);
 $pdf->cell(40,5,"Livro:",0,2,"L",0);
 $pdf->cell(40,5,"Folha:",0,2,"L",0);
 $pdf->cell(40,5,"Cartório:",0,2,"L",0);
 $pdf->cell(40,5,"Data Emissão:",0,2,"L",0);
 $pdf->setY($alt_geral);
 $pdf->setX(145);
 $pdf->setfont('arial','b',7);
 $pdf->cell(55,5,"","R",2,"L",0);
 $pdf->cell(55,5,$ed47_c_certidaonum,"R",2,"L",0);
 $pdf->cell(55,5,$ed47_c_certidaolivro,"R",2,"L",0);
 $pdf->cell(55,5,$ed47_c_certidaofolha,"R",2,"L",0);
 $pdf->cell(55,5,$ed47_c_certidaocart,"R",2,"L",0);
 $pdf->cell(55,5,db_formatar($ed47_c_certidaodata,'d'),"R",1,"L",0);
 $pdf->cell(190,5,"","LR",1,"C",0);
 $pdf->cell(190,5,"NECESSIDADES ESPECIAIS",1,1,"C",1);
 $pdf->cell(190,5,"","LR",1,"C",0);
 $result1 = $clalunonecessidade->sql_record($clalunonecessidade->sql_query("","*","ed48_c_descr LIMIT 5"," ed214_i_aluno = $ed47_i_codigo"));
 $cont = 0;
 if($clalunonecessidade->numrows>0){
  $pdf->cell(5,5,"","L",0,"C",0);
  $pdf->cell(50,5,"Descrição:",0,0,"L",0);
  $pdf->cell(130,5,"Necessidade Maior:",0,0,"L",0);
  $pdf->cell(5,5,"","R",1,"C",0);
  for($y=0;$y<$clalunonecessidade->numrows;$y++){
   db_fieldsmemory($result1,$y);
   $pdf->cell(5,5,"","L",0,"C",0);
   $pdf->cell(50,5,$ed48_c_descr,0,0,"L",0);
   $pdf->cell(130,5,$ed214_c_principal,0,0,"L",0);
   $pdf->cell(5,5,"","R",1,"C",0);
   $cont++;
  }
 }else{
  $pdf->cell(5,5,"","L",0,"C",0);
  $pdf->cell(180,5,"Nenhum registro.",0,0,"L",0);
  $pdf->cell(5,5,"","R",1,"C",0);
  $cont++;
 }
 for($y=$cont;$y<5;$y++){
  $pdf->cell(5,5,"","L",0,"C",0);
  $pdf->cell(50,5,"",0,0,"C",0);
  $pdf->cell(130,5,"",0,0,"C",0);
  $pdf->cell(5,5,"","R",1,"C",0);
 }
 $pdf->cell(190,5,"","LR",1,"C",0);
 $pdf->cell(190,5,"OBSERVAÇÕES",1,1,"C",1);
 $pdf->cell(190,5,"","LR",1,"C",0);
 $alt_obs = $pdf->getY();
 $pdf->cell(5,30,"","L",0,"C",0);
 $pdf->multicell(180,5,$ed47_t_obs==""?"Nenhum registro.":$ed47_t_obs,0,"J",0,0);
 $pdf->setY($alt_obs);
 $pdf->setX(195);
 $pdf->cell(5,30,"","R",1,"C",0);
 $pdf->cell(190,5,"",1,1,"C",0);
}
$pdf->Output();
?>