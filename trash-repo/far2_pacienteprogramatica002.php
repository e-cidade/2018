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

function novo_programa($pdf, $nome)
{
  $cor = '0';
  $pdf->setfont('arial','B',11);
  
  $pdf->ln(5);
  $pdf->cell(280,15,$nome,0,1,'C',$cor);
}

function novo_cabecalho($pdf)
{
  $cor = '0';
  $pdf->setfont('arial','B',7);

  $pdf->cell(15,5,'CGS',1,0,'C',$cor);
  $pdf->cell(65,5,'Nome do Usuario',1,0,'C',$cor);
  $pdf->cell(22,5,'Cartao SUS',1,0,'C',$cor);
  $pdf->cell(20,5,'CPF',1,0,'C',$cor);
  $pdf->cell(35,5,'Endereco',1,0,'C',$cor);
  $pdf->cell(15,5,'Num',1,0,'C',$cor);
  $pdf->cell(25,5,'Complemento',1,0,'C',$cor);
  $pdf->cell(30,5,'Bairro',1,0,'C',$cor);
  $pdf->cell(35,5,'Municipio',1,0,'C',$cor);
  $pdf->cell(18,5,'Identidade',1,1,'C',$cor);
}

function nova_linha($pdf, $cgs, $usuario, $sus, $cpf, $endereco, $numero, $complemento, $bairro, $municipio, $identidade)
{
  $cor = '0';
  $pdf->setfont('arial','',7);

  $pdf->cell(15,5,$cgs,1,0,'C',$cor);
  $pdf->cell(65,5,$usuario,1,0,'L',$cor);
  $pdf->cell(22,5,$sus,1,0,'C',$cor);
  $pdf->cell(20,5,$cpf,1,0,'L',$cor);
  $pdf->cell(35,5,$endereco,1,0,'L',$cor);
  $pdf->cell(15,5,$numero,1,0,'C',$cor);
  $pdf->cell(25,5,$complemento,1,0,'L',$cor);
  $pdf->cell(30,5,$bairro,1,0,'L',$cor);
  $pdf->cell(35,5,$municipio,1,0,'L',$cor);
  $pdf->cell(18,5,$identidade,1,1,'L',$cor);
}



$programas = explode(',',$programas);
$where_programas = '';

if($ordem == 2)
  $order = 'z01_i_cgsund';
else
  $order = 'z01_v_nome';

for($i = 0; $i < count($programas) - 1 ; $i++)
  $where_programas .= 'fa10_i_programa = '.$programas[$i].' or ';
$where_programas .= 'fa10_i_programa = '.$programas[$i];

$sql = "select z01_i_cgsund, s115_c_cartaosus, z01_v_nome, z01_v_cgccpf, z01_v_ender, z01_i_numero, z01_v_compl,
               z01_v_bairro, z01_v_munic, z01_v_ident, programa
          from (select distinct fa11_i_cgsund, fa12_c_descricao as programa
                  from far_controlemed
                    inner join far_controle on fa11_i_codigo = fa10_i_controle
                    inner join far_programa on fa10_i_programa = fa12_i_codigo
                      where $where_programas) as xx
            inner join cgs_und on xx.fa11_i_cgsund = z01_i_cgsund
            inner join cgs on z01_i_numcgs = z01_i_cgsund
            left join cgs_cartaosus on s115_i_cgs= z01_i_numcgs and s115_c_tipo= 'D' order by $order;";


//echo $sql;

$result = pg_query($sql);
$linhas = pg_num_rows($result);

if($linhas == 0 || count($programas) <= 0)
{
?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Nenhum registro encontrado.<br>
            <input type='button' value='Fechar' onclick='window.close()'>
          </b>
        </font>
      </td>
    </tr>
  </table>
<?
  exit;
}
//echo 'Retornou '.$linhas.' linhas';
//exit;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

$head1 = 'Lista Pacientes por Programa';
$head2 = '';
$head3 = 'Ordem:';
$head4 = $ordem == 2 ? '1 - CGS' : '1 - Nome';
$head5 = '';
$head6 = '';

$pdf->Addpage('L'); // L deitado
$cor = '0';
$pdf->setfillcolor(223);
$pdf->setfont('arial','',11);

$count_medicamentos = 1;
$count_linhas_na_pagina = 0;
$medicamento2 = '';
$programa2 = '';

for($count_linhas = 0; $count_linhas < $linhas; $count_linhas++)
{
  db_fieldsmemory($result,$count_linhas);
  
  if($programa2 != $programa)
  {
    $count_linhas_na_pagina += 5;
    if(($count_linhas_na_pagina + 4) >= 29)
    {
      $pdf->AddPage('L');
      $count_linhas_na_pagina = 5;
    }
    $programa2 = $programa;
    novo_programa($pdf,$programa);
    novo_cabecalho($pdf);
  }
  if($count_linhas_na_pagina >= 29)
  {
    $pdf->AddPage('L');
    novo_cabecalho($pdf);
    $count_linhas_na_pagina = 1;
  }

  while($pdf->GetStringWidth($z01_v_nome) > 62.6)
    $z01_v_nome = substr($z01_v_nome,0,strlen($z01_v_nome) - 2);
  while($pdf->GetStringWidth($z01_v_ender) > 32.8)
    $z01_v_ender = substr($z01_v_ender,0,strlen($z01_v_ender) - 2);
  while($pdf->GetStringWidth($z01_v_compl) > 23.6)
    $z01_v_compl = substr($z01_v_compl,0,strlen($z01_v_compl) - 2);
  while($pdf->GetStringWidth($z01_v_munic) > 33)
    $z01_v_munic = substr($z01_v_munic,0,strlen($z01_v_munic) - 2);
  while($pdf->GetStringWidth($z01_v_bairro) > 28.26)
    $z01_v_bairro = substr($z01_v_bairro,0,strlen($z01_v_bairro) - 2);
  if(strlen($z01_v_ident) > 12)
    $z01_v_ident = substr($z01_v_ident,0,11);

  nova_linha($pdf,$z01_i_cgsund,$z01_v_nome,$s115_c_cartaosus,$z01_v_cgccpf,$z01_v_ender,$z01_i_numero,$z01_v_compl,$z01_v_bairro,$z01_v_munic,$z01_v_ident);
  $count_linhas_na_pagina++;
}
$pdf->Output();
?>