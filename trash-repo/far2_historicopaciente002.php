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


function nova_linha($pdf,$req, $lote, $data, $quantidade, $posologia)
{
  $cor = '0';
  $pdf->setfont('arial','',9);

  $pdf->cell(38,5,$req,1,0,'C',$cor); // $pdf->cell(largura,altura,texto que aparece,existe borda(booleano),
                                      // quebra linha(booleano),posicionamento do texto(L,C,R),cor)
  $pdf->cell(38,5,$lote,1,0,'C',$cor);
  $pdf->cell(38,5,$data,1,0,'C',$cor);
  $pdf->cell(38,5,$quantidade,1,0,'R',$cor);
  $pdf->cell(38,5,$posologia,1,1,'C',$cor);
}

function novo_nome($pdf, $nome)
{
  $cor = '0';
  $pdf->setfont('arial','B',11);
  
  $pdf->ln(5);
  $pdf->cell(190,15,$nome,0,1,'L',$cor);
}

function novo_medicamento($pdf, $medicamento)
{
  $cor = '0';
  $pdf->setfont('arial','B',10);
  
  $pdf->ln(5);
  $pdf->cell(190,10,$medicamento,1,1,'C',$cor);

  $pdf->setfont('arial','B',9);

  $pdf->cell(38,5,'Req.',1,0,'C',$cor);
  $pdf->cell(38,5,'Lote',1,0,'C',$cor);
  $pdf->cell(38,5,'Data',1,0,'C',$cor);
  $pdf->cell(38,5,'Quantidade',1,0,'C',$cor);
  $pdf->cell(38,5,'Posologia',1,1,'C',$cor);

}

function verifica_quebra($pdf, $count_linhas_na_pagina)
{
  if($count_linhas_na_pagina >= 47)
  {
    $pdf->AddPage('P');
    return 0;
  }
  return $count_linhas_na_pagina;
}

function formata_data($data)
{
  $data = explode('/',$data);
  $data = '\''.$data[2].'-'.$data[1].'-'.$data[0].'\'';
  return $data;
}

function formata_data2($data)
{
  $data = explode('-',$data);
  $data = $data[2].'/'.$data[1].'/'.$data[0];
  return $data;
}

$cgss = explode(',',$cgss);
$datas = explode(',',$datas);
$data_inicio = formata_data($datas[0]);
$data_fim = formata_data($datas[1]);

$where_cgss = '( ';
$where_medicamentos = '';

for($i = 0; $i < count($cgss) - 1 ; $i++)
  $where_cgss .= 'z01_i_cgsund = '.$cgss[$i].' or ';
$where_cgss .= 'z01_i_cgsund = '.$cgss[$i].' ) and ';

if(!empty($medicamentos))
{
  $where_medicamentos = '( ';
  $medicamentos = explode(',',$medicamentos);
  for($i = 0; $i < count($medicamentos) - 1; $i++)
    $where_medicamentos .= 'fa01_i_codigo = '.$medicamentos[$i].' or ';
  $where_medicamentos .= 'fa01_i_codigo = '.$medicamentos[$i].' ) and ';
}

$sql = "select trim(fa04_i_cgsund || '-' || z01_v_nome || ' CPF - ' || z01_v_cgccpf ) as nome,
               trim(fa06_i_matersaude||' - '|| m60_descr) as medicamento, fa07_i_matrequi,
               fa04_d_data, m77_lote, substring(fa06_t_posologia,1,10) as Posologia, fa06_f_quant
          from far_retiradaitens
            inner join far_retirada          on fa06_i_retirada=fa04_i_codigo
            inner join cgs_und               on z01_i_cgsund = fa04_i_cgsund
            inner join far_matersaude        on fa06_i_matersaude=fa01_i_codigo
            inner join matmater              on matmater.m60_codmater = far_matersaude.fa01_i_codmater
            inner join matunid               on matunid.m61_codmatunid = matmater.m60_codmatunid
            left join far_listacontroladomed on far_listacontroladomed.fa35_i_codigo = far_matersaude.fa01_i_listacontroladomed
            left join far_listacontrolado    on far_listacontrolado.fa15_i_codigo = far_listacontroladomed.fa35_i_listacontrolado
            left join far_retiradarequi      on fa04_i_codigo=fa07_i_retirada
            left join far_retiradaitemlote   on fa06_i_codigo=fa09_i_retiradaitens
            left join matestoqueitemlote     on fa09_i_matestoqueitem=m77_matestoqueitem
              where $where_cgss $where_medicamentos fa04_d_data between $data_inicio and $data_fim
                order by z01_v_nome, m60_descr, fa04_d_data desc;";

//echo $sql;
$result = pg_query($sql);
$linhas = pg_num_rows($result);

if($linhas == 0 || count($cgss) <= 0)
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
//echo '<br><br><b>Retornou '.$linhas.' linhas</b><br><br>';
//exit;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

$head1 = "Ultimas retiradas do Paciente";
$head2 = '';
$head3 = 'Ordem:';
$head4 = '  1 - Nome do usuario';
$head5 = '  2 - Medicamento';
$head6 = '  3 - Data';

$pdf->Addpage('P'); // L deitado
$cor = '0';
$pdf->setfillcolor(223);
$pdf->setfont('arial','',11);

$count_pacientes = 1;
$count_medicamentos = 1;
$count_linhas_na_pagina = 0;
$nome2 = '';
$medicamento2 = '';

for($count_linhas = 0; $count_linhas < $linhas; $count_linhas++)
{
  db_fieldsmemory($result,$count_linhas);
  
  if($nome2 != $nome)
  {
    $nome2 = $nome;
    $count_linhas_na_pagina += 8;
    $count_linhas_na_pagina = verifica_quebra($pdf, $count_linhas_na_pagina);
    novo_nome($pdf,$count_pacientes.'. '.$nome);

    if($count_linhas_na_pagina == 0)
      $count_linhas_na_pagina = 4;
    else
      $count_linhas_na_pagina -= 4;

    $count_pacientes++;
    $count_medicamentos = 1;
  }

  if($medicamento2 != $medicamento)
  {
    $medicamento2 = $medicamento;
    $count_linhas_na_pagina += 4;
    $count_linhas_na_pagina = verifica_quebra($pdf, $count_linhas_na_pagina);
    novo_medicamento($pdf,($count_pacientes - 1).'.'.$count_medicamentos.'. '.$medicamento);

    if($count_linhas_na_pagina == 0)
      $count_linhas_na_pagina = 4;
    
    $count_medicamentos++;
  }

  if($medicamento2 == $medicamento && $count_linhas_na_pagina == 0)
  {
    $count_linhas_na_pagina += 4;
    novo_medicamento($pdf,($count_pacientes - 1).'.'.($count_medicamentos - 1).'. '.$medicamento);
  }

  nova_linha($pdf,$fa07_i_matrequi,$m77_lote,formata_data2($fa04_d_data),$fa06_f_quant,$posologia);
  $count_linhas_na_pagina++;
  $count_linhas_na_pagina = verifica_quebra($pdf, $count_linhas_na_pagina);
  

  //echo "Nome: $nome<br>Medicamento: $medicamento<br>Req: $fa07_i_matrequi<br>Data: $fa04_d_data<br>
  //Lote: $m77_lote<br>Posologia: $posologia<br>Quantidade: $fa06_f_quant<br><br><br>";
//  if($nome2 != )
}
$pdf->Output();
?>