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

require_once ("fpdf151/pdf.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("classes/db_turma_classe.php");

$clturma   = new cl_turma;
$escola    = db_getsession("DB_coddepto");
$resultedu = eduparametros(db_getsession("DB_coddepto"));
$decimais  = $resultedu == "N" ? 0 : 2;

$sCamposTurma = "ed57_i_codigo as turma,ed223_i_serie,ed37_i_maiorvalor,ed57_i_calendario,ed57_c_descr,ed57_i_base,ed11_c_descr";
$sSqlTurma    = $clturma->sql_query_turmaserie( "", $sCamposTurma, "" , " ed220_i_codigo = $turma" );
$result       = $clturma->sql_record( $sSqlTurma );

if ( $clturma->numrows > 0 ) {
  
 db_fieldsmemory($result,0);
 //seleciona medias do aluno escolhido
 $sql1 = "SELECT round(ed72_i_valornota,$decimais) as media,
                 ed232_c_descr,
                 ed09_c_descr as periodos,
                 ed47_v_nome,
                 ed232_i_codigo
            FROM matricula
                 inner join aluno            on ed47_i_codigo  = ed60_i_aluno
                 inner join diario           on ed95_i_aluno   = ed47_i_codigo
                 left  join diarioavaliacao  on ed72_i_diario  = ed95_i_codigo
                 left  join procavaliacao    on ed41_i_codigo  = ed72_i_procavaliacao
                 left  join periodoavaliacao on ed09_i_codigo  = ed41_i_periodoavaliacao
                 left  join regencia         on ed59_i_codigo  = ed95_i_regencia
                 left  join disciplina       on ed12_i_codigo  = ed59_i_disciplina
                 left  join caddisciplina    on ed232_i_codigo = ed12_i_caddisciplina
           WHERE ed59_i_turma     = $turma
             AND ed59_i_serie     = $ed223_i_serie
             AND ed72_c_amparo    = 'N'
             AND ed59_c_freqglob != 'F'
             AND ed59_i_turma     = ed60_i_turma
             AND ed60_c_ativa     = 'S'
             AND ed95_i_aluno     = $aluno
           ORDER BY ed41_i_sequencia,ed59_i_ordenacao, ed232_i_codigo
         ";
 $result1 = db_query($sql1);
 $linhas1 = pg_num_rows($result1);
 
 $aCadDisciplina = array();
 for ( $iContador = 0; $iContador < $linhas1; $iContador++ ) {
    
   $oDadosAluno      = db_utils::fieldsMemory( $result1, $iContador );
   
   if ( !in_array( $oDadosAluno->ed232_i_codigo, $aCadDisciplina ) ) {
    $aCadDisciplina[] = $oDadosAluno->ed232_i_codigo;
   }
 }
 $sCadDisciplina = implode( ", ", $aCadDisciplina );
 
 //seleciona media final do aluno escolhido
 $notaparcial = NotaParcial();
 $sql4 = "SELECT round($notaparcial,$decimais) as media,
                 caddisciplina.ed232_c_descr,
                 ed59_i_ordenacao
            FROM matricula
                 inner join matriculaserie       on matriculaserie.ed221_i_matricula   = matricula.ed60_i_codigo
                 inner join turma                on turma.ed57_i_codigo                = matricula.ed60_i_turma
                 inner join regencia             on regencia.ed59_i_turma              = turma.ed57_i_codigo
                                                and regencia.ed59_i_turma              = matricula.ed60_i_turma
                                                and regencia.ed59_i_serie              = matriculaserie.ed221_i_serie
                 inner join disciplina           on disciplina.ed12_i_codigo           = regencia.ed59_i_disciplina
                 inner join caddisciplina        on caddisciplina.ed232_i_codigo       = disciplina.ed12_i_caddisciplina
                 inner join turmaserieregimemat  on turmaserieregimemat.ed220_i_turma  = turma.ed57_i_codigo
                 inner join serieregimemat       on serieregimemat.ed223_i_codigo      = turmaserieregimemat.ed220_i_serieregimemat
                                                and serieregimemat.ed223_i_serie       = matriculaserie.ed221_i_serie
                 inner join procedimento         on procedimento.ed40_i_codigo         = turmaserieregimemat.ed220_i_procedimento
                 inner join procresultado        on procresultado.ed43_i_procedimento  = procedimento.ed40_i_codigo
                                                and procresultado.ed43_c_geraresultado = 'S'
                 inner join formaavaliacao as formaavaliacaores on formaavaliacaores.ed37_i_codigo = procresultado.ed43_i_formaavaliacao
                 left  join diario               on diario.ed95_i_aluno                  = matricula.ed60_i_aluno
                                                and diario.ed95_i_regencia               = regencia.ed59_i_codigo
                 left  join diarioresultado      on diarioresultado.ed73_i_diario        = diario.ed95_i_codigo
                                                and diarioresultado.ed73_i_procresultado = procresultado.ed43_i_codigo
           WHERE matricula.ed60_i_turma         = $turma
             AND matricula.ed60_i_aluno         = $aluno
             AND matriculaserie.ed221_i_serie   = $ed223_i_serie
             AND matricula.ed60_c_ativa         = 'S'
             AND matriculaserie.ed221_c_origem  = 'S'
             AND regencia.ed59_c_freqglob      != 'F'
             AND ed232_i_codigo                in ({$sCadDisciplina})
           ORDER BY ed59_i_ordenacao
         ";
 $result4 = db_query($sql4);
 $linhas4 = pg_num_rows($result4);
 
 //seleciona disciplinas da turma escolhida
 $sql2 = "SELECT DISTINCT substr(ed232_c_descr,0,20) as disciplina,
                 ed232_c_abrev as abrev,
                 ed59_i_ordenacao
            FROM matricula
                 inner join aluno           on ed47_i_codigo  = ed60_i_aluno
                 inner join diario          on ed95_i_aluno   = ed47_i_codigo
                 left  join diarioavaliacao on ed72_i_diario  = ed95_i_codigo
                 left  join regencia        on ed59_i_codigo  = ed95_i_regencia
                 left  join turma           on ed57_i_codigo  = ed59_i_turma
                 left  join disciplina      on ed12_i_codigo  = ed59_i_disciplina
                 left  join caddisciplina   on ed232_i_codigo = ed12_i_caddisciplina
           WHERE ed59_i_turma     = $turma
             AND ed59_i_serie     = $ed223_i_serie
             AND ed72_c_amparo    = 'N'
             AND ed60_c_ativa     = 'S'
             AND ed59_c_freqglob != 'F'
           ORDER BY ed59_i_ordenacao, disciplina
         ";
 $result2 = db_query($sql2);
 $linhas2 = pg_num_rows($result2);
 
 if ( $linhas1 == 0 ) {?>
  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Nenhuma registro encontrado<br>
     </font>
    </td>
   </tr>
  </table>
  <?
  exit;
 } else {
   
  $disc_turma    = "";
  $media_turma   = "";
  $periodo_turma = "";
  $sep           = "";
  
  for ( $x = 0; $x < $linhas1; $x++ ) {
    
    db_fieldsmemory($result1,$x);
    if ( !strstr( $periodo_turma, $periodos ) ) {
      $periodo_turma  .= $sep.$periodos;
    }
    $media_turma  .= $sep.$media;
    $sep           = ",";
  }
  
  for ( $x = 0; $x < $linhas4; $x++ ) {
    
    db_fieldsmemory($result4,$x);
    $media_turma  .= $sep.$media;
    $sep           = ",";
  }
  
  $sep = "";
  for ( $x = 0; $x < $linhas2; $x++ ) {
    
    db_fieldsmemory($result2,$x);
    $disc_turma .= $sep.$abrev."|".$disciplina;
    $sep         = ",";
  }
  
  $periodo_turma  .= ",M�dia Final";
  $max = $ed37_i_maiorvalor;
  // ------ configura��es do gr�fico ----------
  $titulo = "Gr�fico de Aproveitamento Geral - Turma $ed57_c_descr";
  $subtitulo = "Aluno: $aluno - $ed47_v_nome";
  $largura = $larg_pagina;
  $altura = 400;
  $largura_eixo_x = $largura*70/100;
  $largura_eixo_y = 300;
  $inicio_grafico_x = 40;
  $inicio_grafico_y = 360;

  // ------ configura��es da legenda ----------
  $exibir_legenda = "sim";
  $fonte = 2;
  $largura_fonte = 8; // largura em pixels (2=6,3=8,4=10)
  $altura_fonte = 10; // altura em pixels (2=8,3=10,4=12)
  $espaco_entre_linhas = 10;
  $margem_vertical = 5;

  // canto superior direito da legenda
  $lx = $largura-10;
  $ly = 10;

  $imagem = ImageCreate($largura, $altura);
  $fundo = ImageColorAllocate($imagem, 255, 255, 255);
  $preto = ImageColorAllocate($imagem, 0, 0, 0);
  $cinza = ImageColorAllocate($imagem, 192, 192, 192);
  $azul = ImageColorAllocate($imagem, 0, 0, 255);
  $verde = ImageColorAllocate($imagem, 0, 191, 96);
  $vermelho = ImageColorAllocate($imagem, 255, 0, 0);
  $laranja = ImageColorAllocate($imagem, 255, 128, 0);
  $rosa = ImageColorAllocate($imagem, 255, 0, 255);
  $amarelo = ImageColorAllocate($imagem, 232, 232, 0);
  $marrom = ImageColorAllocate($imagem, 128, 64, 64);
  $cores_colunas = array($azul,$verde,$amarelo,$vermelho,$rosa,$laranja,$cinza,$marrom);

  $texto_linha = explode(",",$periodo_turma);
  for($x=0;$x<sizeof($texto_linha);$x++){
   $cores_linha[] = $cores_colunas[$x];
  }
  $texto_coluna = explode (",",$disc_turma);

  $valores = explode (",",$media_turma);

  $numero_linhas = sizeof($texto_linha);
  $numero_colunas = sizeof($texto_coluna);
  $numero_valores = sizeof($valores);

  // ------ obt�m o valor m�ximo de y ----------
  $y_maximo = $max-5;

  // ------ calcula o intervalo de varia��o entre os pontos de y ----------

  $fator = pow (10, strlen(intval($y_maximo))-1);

  if($y_maximo<1)
      $variacao=0.1;
  elseif($y_maximo<10)
      $variacao=1;
  elseif($y_maximo<2*$fator)
      $variacao=$fator/5;
  elseif($y_maximo<5*$fator)
      $variacao=$fator/2;
  elseif($y_maximo<10*$fator)
      $variacao=$fator;
  $variacao = 5;
  // ------ calcula o n�mero de pontos no eixo y ----------
  $num_pontos_eixo_y = 0;
  $valor = 0;
  while ($y_maximo>=$valor)
  {
      $valor+=$variacao;
      $num_pontos_eixo_y++;
  }

  $valor_topo = $valor;
  $dist_entre_pontos = $largura_eixo_y / $num_pontos_eixo_y;

  // ------- Titulo ---------
  ImageString($imagem, 3, 10, 3, $titulo, $preto);
  ImageString($imagem, 3, 10, 15, $subtitulo, $preto);

  // ------- Eixos x e y ---------
  ImageLine($imagem, $inicio_grafico_x, $inicio_grafico_y, $inicio_grafico_x+$largura_eixo_x, $inicio_grafico_y, $preto);
  ImageLine($imagem, $inicio_grafico_x, $inicio_grafico_y, $inicio_grafico_x, $inicio_grafico_y-$largura_eixo_y, $preto);

  // ------- Pontos no eixo y ---------
  $posy = $inicio_grafico_y;
  $valor = 0;

  for($i=0 ; $i<=$num_pontos_eixo_y; $i++)
  {
      $posx = $inicio_grafico_x - (strlen($valor)+2)*6; // 6 da largura da fonte + 2 espa�os

      ImageString($imagem, 2, $posx, $posy-7, $valor, $preto);
      ImageLine($imagem, $inicio_grafico_x-6, $posy, $inicio_grafico_x+$largura_eixo_x, $posy, $cinza);
      $valor += $variacao;
      $posy -= $dist_entre_pontos;
  }

  // ------- Colunas no eixo x ---------
  $num_barras = $numero_linhas * $numero_colunas;
  $largura_barra = floor($largura_eixo_x / ($num_barras+$numero_colunas+1));
  $posx = $inicio_grafico_x + $largura_barra;
  ImageString($imagem, 3, $largura_eixo_x/2, $inicio_grafico_y+20, "Disciplinas", $preto);
  ImageStringUp($imagem, 3, 0, $inicio_grafico_y/2+20,"Notas", $preto);
  $tam_stringup = 2;
  for($i=0 ; $i<$numero_colunas; $i++)
  {
      // label da coluna
      $pos_label_x = $posx + ($largura_barra*$numero_linhas/2) - (strlen($texto_coluna[$i])*6/2);
      $pos_label_y = $inicio_grafico_y+5;
      $legenda = explode("|",$texto_coluna[$i]);
      ImageString($imagem, 2, $posx+($largura_barra*$numero_linhas/3), $pos_label_y, $legenda[0], $preto);

      // imprime as barras
      ImageLine($imagem, $posx, $inicio_grafico_y, $posx, $inicio_grafico_y+5, $preto);
      for($j=$i ; $j<$numero_valores; $j+=$numero_colunas)
      {
          ImageLine($imagem, $posx, $inicio_grafico_y+5, $posx+$largura_barra, $inicio_grafico_y+5, $preto);
          $altura_barra = $valores[$j]/$valor_topo * $largura_eixo_y;
          ImageStringUp($imagem, $tam_stringup,$posx ,$inicio_grafico_y-$altura_barra-5,$valores[$j], $vermelho);
          $indice_cor = intval ($j/$numero_colunas);
          ImageFilledRectangle($imagem, $posx, $inicio_grafico_y-$altura_barra, $posx+$largura_barra, $inicio_grafico_y, $cores_linha[$indice_cor]);
          ImageRectangle($imagem, $posx, $inicio_grafico_y-$altura_barra, $posx+$largura_barra, $inicio_grafico_y, $preto);
          $posx += $largura_barra;
      }
      ImageLine($imagem, $posx, $inicio_grafico_y, $posx, $inicio_grafico_y+5, $preto);
      $posx += $largura_barra;
  }

  // *********** CRIA��O DA LEGENDA *********************
  if($exibir_legenda=="sim")
  {
      //1� legenda
      // acha a maior string
      $maior_tamanho = 0;
      for($i=0 ; $i<$numero_colunas; $i++)
          if(strlen($texto_coluna[$i])>$maior_tamanho)
              $maior_tamanho = strlen($texto_coluna[$i]);

      // calcula os pontos de in�cio e fim do quadrado
      $x_inicio_legenda = $lx - $largura_fonte * ($maior_tamanho);
      $y_inicio_legenda = $ly;

      $x_fim_legenda = $lx;
      $y_fim_legenda = $ly + $numero_linhas * ($altura_fonte + $espaco_entre_linhas) + 2*$margem_vertical;
      ImageRectangle($imagem, $x_inicio_legenda, $y_inicio_legenda,$x_fim_legenda, $y_fim_legenda, $preto);

      // come�a a desenhar os dados
      for($i=0 ; $i<$numero_linhas; $i++)
      {
          $x_pos = $x_inicio_legenda + $largura_fonte*3;
          $y_pos = $y_inicio_legenda + $i * ($altura_fonte + $espaco_entre_linhas) + $margem_vertical;
          ImageString($imagem, $fonte, $x_pos, $y_pos, $texto_linha[$i], $preto);
          ImageFilledRectangle ($imagem, $x_pos-2*$largura_fonte, $y_pos, $x_pos-$largura_fonte, $y_pos+$altura_fonte, $cores_linha[$i]);
          ImageRectangle ($imagem, $x_pos-2*$largura_fonte, $y_pos, $x_pos-$largura_fonte, $y_pos+$altura_fonte, $preto);
      }
      //2� legenda
      // acha a maior string
      $maior_tamanho = 0;
      for($i=0 ; $i<$numero_colunas; $i++)
          if(strlen($texto_coluna[$i])>$maior_tamanho)
              $maior_tamanho = strlen($texto_coluna[$i]);

      // calcula os pontos de in�cio e fim do quadrado
      $x_inicio_legenda = $lx - $largura_fonte * ($maior_tamanho);
      $y_inicio_legenda = $ly;

      $x_fim_legenda = $lx;
      $y_fim_legenda = $ly + $numero_colunas * ($altura_fonte + $espaco_entre_linhas) + 2*$margem_vertical;
      ImageRectangle($imagem, $x_inicio_legenda, 150+$y_inicio_legenda,$x_fim_legenda, 150+$y_fim_legenda, $preto);

      // come�a a desenhar os dados
      for($i=0 ; $i<$numero_colunas; $i++)
      {
          $x_pos = $x_inicio_legenda + $largura_fonte;
          $y_pos = 150+$y_inicio_legenda + $i * ($altura_fonte + $espaco_entre_linhas) + $margem_vertical;
          $legenda = explode("|",$texto_coluna[$i]);
          ImageString($imagem, $fonte, $x_pos, $y_pos, $legenda[0]." - ".substr($legenda[1],0,20), $preto);
      }
  }
  $nome_arquivo = "tmp/".$turma."_".db_getsession("DB_id_usuario").".png";
  ImagePng($imagem,$nome_arquivo);
  echo "<img src='$nome_arquivo'><br><br>";
  echo "<form name='form1'>
         <input type='button' value='Imprimir' onclick='js_imprimir()'>
        </form>
        <script>
         function js_imprimir(){
          jan = window.open('edu2_gfcoaprovalugeral003.php?aluno=$aluno - $ed47_v_nome&turma=$turma&nome_arquivo=$nome_arquivo','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
          jan.moveTo(0,0);
          parent.location.href='edu2_gfcoaprovalugeral001.php';
         }
        </script>
       ";
  ImageDestroy($imagem);
 }
}
?>