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

function novo_nome($pdf, $nome)
{
  $cor = '0';
  $pdf->setfont('arial','B',11);
  
  $pdf->ln(5);
  $pdf->cell(190,15,$nome,0,1,'L',$cor);
}

function novo_cabecalho($pdf)
{
  $cor = '0';
  $pdf->setfont('arial','B',9);

  $pdf->cell(80,5,'Medicamento',1,0,'C',$cor);
  $pdf->cell(17,5,'Req.',1,0,'C',$cor);
  $pdf->cell(20,5,'Lote',1,0,'C',$cor);
  $pdf->cell(22,5,'Data',1,0,'C',$cor);
  $pdf->cell(26,5,'Quantidade',1,0,'C',$cor);
  $pdf->cell(25,5,'Posologia',1,1,'C',$cor);

}

function nova_linha($pdf, $medicamento, $req, $lote, $data, $quantidade, $posologia)
{
  $cor = '0';
  $pdf->setfont('arial','',9);

  $pdf->cell(80,5,$medicamento,1,0,'L',$cor);
  $pdf->cell(17,5,$req,1,0,'C',$cor); // $pdf->cell(largura,altura,texto que aparece,existe borda(booleano),
                                      // quebra linha(booleano),posicionamento do texto(L,C,R),cor)
  $pdf->cell(20,5,$lote,1,0,'C',$cor);
  $pdf->cell(22,5,$data,1,0,'C',$cor);
  $pdf->cell(26,5,$quantidade,1,0,'R',$cor);
  $pdf->cell(25,5,$posologia,1,1,'C',$cor);
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

$programas = explode(',',$programas);
$datas = explode(',',$datas);
$data_inicio = formata_data($datas[0]);
$data_fim = formata_data($datas[1]);
$nomes_programas = str_replace(',',', ',$nomes_programas);

$where_programas = '';

for($i = 0; $i < count($programas) - 1 ; $i++)
  $where_programas .= 'fa10_i_programa = '.$programas[$i].' or ';
$where_programas .= 'fa10_i_programa = '.$programas[$i];

$sql = "select trim(fa04_i_cgsund || '-' || z01_v_nome || ' CPF - ' ||coalesce(z01_v_cgccpf,' ')) as nome,
               trim(fa06_i_matersaude||' - '|| m60_descr) as medicamento, fa07_i_matrequi, fa04_d_data, m77_lote,
               substring(fa06_t_posologia,1,10) as Posologia, fa06_f_quant
          from far_retiradaitens
            inner join far_retirada        on fa06_i_retirada=fa04_i_codigo
            inner join cgs_und            on z01_i_cgsund = fa04_i_cgsund
            inner join far_matersaude on fa06_i_matersaude=fa01_i_codigo
            inner join matmater            on matmater.m60_codmater = far_matersaude.fa01_i_codmater
            inner join matunid              on matunid.m61_codmatunid = matmater.m60_codmatunid
            left join far_retiradarequi    on fa04_i_codigo=fa07_i_retirada
            left join far_retiradaitemlote on fa06_i_codigo=fa09_i_retiradaitens
            left join matestoqueitemlote on fa09_i_matestoqueitem=m77_matestoqueitem
              where fa06_i_matersaude in (select xx.fa10_i_medicamento
                                            from (select distinct fa10_i_programa, fa10_i_medicamento
                                                    from far_controlemed
                                                      where $where_programas) as xx)
               and fa04_d_data between $data_inicio and $data_fim
                 order by z01_v_nome, m60_descr, fa04_d_data desc;";


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
//echo '<br><br><b>Retornou '.$linhas.' linhas</b><br><br>';
//exit;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

$head1 = "Ultimas retiradas por Programa / Paciente";
$head2 = '';
$head3 = $nomes_programas;
$head4 = '';
$head5 = 'Ordem:';
$head6 = '  1 - Nome do usuario';
$head7 = '  2 - Medicamento';
$head8 = '  3 - Data';

$pdf->Addpage('P'); // L deitado
$cor = '0';
$pdf->setfillcolor(223);
$pdf->setfont('arial','',11);

$count_pacientes = 1;
$count_linhas_na_pagina = 0;
$nome2 = '';

for($count_linhas = 0; $count_linhas < $linhas; $count_linhas++)
{
  db_fieldsmemory($result,$count_linhas);
  
  if($nome2 != $nome)
  {
    $nome2 = $nome;
    $count_linhas_na_pagina += 6;
    $count_linhas_na_pagina = verifica_quebra($pdf, $count_linhas_na_pagina);
    novo_nome($pdf,$count_pacientes.'. '.$nome);

    if($count_linhas_na_pagina == 0)
      $count_linhas_na_pagina = 5;
    else
      $count_linhas_na_pagina -= 1;

    $count_pacientes++;

    novo_cabecalho($pdf);
  }
  
  if($count_linhas_na_pagina == 0)
  {
    $pdf->ln(5);
    novo_cabecalho($pdf);
    $count_linhas_na_pagina = 1;
  }

  nova_linha($pdf,$medicamento,$fa07_i_matrequi,$m77_lote,formata_data2($fa04_d_data),$fa06_f_quant,$posologia);
  $count_linhas_na_pagina++;
  $count_linhas_na_pagina = verifica_quebra($pdf, $count_linhas_na_pagina);
  
  //echo "Nome: $nome<br>Medicamento: $medicamento<br>Req: $fa07_i_matrequi<br>Data: $fa04_d_data<br>
  //Lote: $m77_lote<br>Posologia: $posologia<br>Quantidade: $fa06_f_quant<br><br><br>";
//  if($nome2 != )
}
$pdf->Output();
?>