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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_cardapio_classe.php");
include("classes/db_mer_tipocardapio_classe.php");
include("classes/db_mer_cardapioescola_classe.php");
include("classes/db_mer_tpcardapioturma_classe.php");
include("classes/db_mer_cardapiodia_classe.php");
include("classes/db_mer_cardapioitem_classe.php");
include("classes/db_mer_cardapionutri_classe.php");
include("classes/db_mer_cardapiotipo_classe.php");
include("classes/db_mer_caractpreparo_classe.php");
include("classes/db_mer_modpreparo_classe.php");
include("classes/db_mer_subitem_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmer_cardapio        = new cl_mer_cardapio;
$clmer_tipocardapio    = new cl_mer_tipocardapio;
$clmer_cardapioescola  = new cl_mer_cardapioescola;
$clmer_tpcardapioturma = new cl_mer_tpcardapioturma;
$clmer_cardapiodia   = new cl_mer_cardapiodia;
$clmer_cardapioitem  = new cl_mer_cardapioitem;
$clmer_cardapionutri = new cl_mer_cardapionutri;
$clmer_cardapiotipo  = new cl_mer_cardapiotipo;
$clmer_caractpreparo = new cl_mer_caractpreparo;
$clmer_modpreparo    = new cl_mer_modpreparo;
$clmer_subitem       = new cl_mer_subitem;
$escola                = db_getsession("DB_coddepto");
if (isset($cod_cardapio)) {
	
  db_inicio_transacao();
  $antigo = $cod_cardapio;
  //Seleciona o cardapio antigo
  $campos = " me27_c_nome,me27_c_ativo,me27_d_inicio,me27_d_fim,me27_f_versao,me27_i_id,me27_i_ano";
  $result = $clmer_tipocardapio->sql_record($clmer_tipocardapio->sql_query_file("",$campos,"","me27_i_codigo = $antigo"));
  db_fieldsmemory($result,0);
  //Inclui uma nova versão do cardapio antigo aumentando o numero da versão
  $clmer_tipocardapio->me27_c_nome   = $me27_c_nome;
  $clmer_tipocardapio->me27_c_ativo  = $me27_c_ativo;
  $clmer_tipocardapio->me27_d_inicio = $me27_d_inicio;
  $clmer_tipocardapio->me27_d_fim    = $me27_d_fim;  
  $clmer_tipocardapio->me27_f_versao = $me27_f_versao+0.1;
  $clmer_tipocardapio->me27_i_id     = $me27_i_id;
  $clmer_tipocardapio->me27_i_ano    = $me27_i_ano;  
  $clmer_tipocardapio->incluir(null);
  $novaversao     = $clmer_tipocardapio->me27_i_codigo;
  $num_novaversao = $clmer_tipocardapio->me27_f_versao;
  //refeições vinculadas ao antigo cardápio
  $result_ver00 = $clmer_cardapio->sql_record($clmer_cardapio->sql_query_file("","distinct on (me01_i_id) me01_i_codigo as cod_refeicao","me01_i_id,me01_f_versao desc","me01_i_tipocardapio = $antigo"));
  $linhas_ver00 = $clmer_cardapio->numrows;
  for ($tt=0;$tt<$linhas_ver00;$tt++) {
  	
    db_fieldsmemory($result_ver00,$tt);
    
    
  $antigaref = $cod_refeicao;
  //Seleciona o cardapio antigo
  $campos = " me01_c_nome,me01_i_percapita,me01_i_tipocardapio,me01_f_versao,me01_i_id";
  $result = $clmer_cardapio->sql_record($clmer_cardapio->sql_query("",$campos,"","me01_i_codigo = $antigaref"));
  db_fieldsmemory($result,0);
  //Inclui uma nova versão do cardapio antigo aumentando o numero da versão
  $clmer_cardapio->me01_c_nome         = $me01_c_nome;
  $clmer_cardapio->me01_i_percapita    = $me01_i_percapita;
  $clmer_cardapio->me01_f_versao       = $me01_f_versao+0.1;
  $clmer_cardapio->me01_i_id           = $me01_i_id;
  $clmer_cardapio->me01_i_tipocardapio = $novaversao;  
  $clmer_cardapio->incluir(null);
  $novaversaoref                      = $clmer_cardapio->me01_i_codigo;
  $num_novaversaoref                  = $clmer_cardapio->me01_f_versao;
  
  $dataatual                          = date("Y-m-d",db_getsession("DB_datausu"));
  $horaatual                          = date("H:i");
  //Tabela de horario mer_cardapiodia
  //alterar para o codigo do novo cardapio se o codigo for o antigo
  $sql_ver    = " SELECT me12_i_codigo as cod_carddia ";
  $sql_ver   .= " FROM mer_cardapiodia ";
  $sql_ver   .= "  inner join mer_tprefeicao on me03_i_codigo = me12_i_tprefeicao ";
  $sql_ver   .= " WHERE me12_i_cardapio = $antigaref ";
  $sql_ver   .= " AND (me12_d_data > '$dataatual' ";
  $sql_ver   .= "     OR (me12_d_data = '$dataatual' AND me03_c_fim > '$horaatual'))";
  $sql_ver   .= " AND not exists(select * from mer_cardapiodata 
                                  inner join mer_cardapiodiaescola on me37_i_codigo = me13_i_cardapiodiaescola 
                                 where me12_i_codigo = me37_i_cardapiodia) ";
  $result_ver = pg_query($sql_ver);
  for ($t=0;$t<pg_num_rows($result_ver);$t++) {
    
    db_fieldsmemory($result_ver,$t);
    pg_query("update mer_cardapiodia set
               me12_i_cardapio    = $novaversaoref
               where me12_i_codigo = $cod_carddia"
            );
    
  }
  //seleciona os tprefeição associados ao antigo cardapio
  $result = $clmer_cardapiotipo->sql_record(
                                            $clmer_cardapiotipo->sql_query_file("",
                                                                                "me21_i_tprefeicao",
                                                                                "",
                                                                                " me21_i_cardapio = $antigaref"
                                                                               )
                                           );
  $linhas = $clmer_cardapiotipo->numrows;
  //Adiciona os tprefeicao do antigo cardapio na nova versão
  for ($x=0; $x<$linhas; $x++) {
    
    db_fieldsmemory($result,$x);
    $clmer_cardapiotipo->me21_i_tprefeicao = $me21_i_tprefeicao;
    $clmer_cardapiotipo->me21_i_cardapio   = $novaversaoref;
    $clmer_cardapiotipo->incluir(null);
    
  }

  //seleciona nutricionistas associados ao antigo cardapio
  $result = $clmer_cardapionutri->sql_record(
                                             $clmer_cardapionutri->sql_query_file("",
                                                                                  "me04_i_nutricionista",
                                                                                  "",
                                                                                  " me04_i_cardapio = $antigaref"
                                                                                 )
                                            );
  $linhas = $clmer_cardapionutri->numrows;
  //Adiciona nutricionistas do antigo cardapio na nova versão
  for ($x=0;$x<$linhas;$x++) {
    
    db_fieldsmemory($result,$x);
    $clmer_cardapionutri->me04_i_nutricionista = $me04_i_nutricionista;
    $clmer_cardapionutri->me04_i_cardapio      = $novaversaoref;
    $clmer_cardapionutri->incluir(null);
    
  }
  //seleciona modo preparo associados ao antigo cardapio
  $campos = " me05_t_obs,me05_c_natureza,me05_f_porcao,me05_i_alimento ";
  $result = $clmer_modpreparo->sql_record($clmer_modpreparo->sql_query_file("",$campos,""," me05_i_cardapio = $antigaref"));
  $linhas = $clmer_modpreparo->numrows;
  //Adiciona modo de preparo do antigo cardapio na nova versão
  for ($x=0;$x<$linhas;$x++) {
    
    db_fieldsmemory($result,$x);
    $clmer_modpreparo->me05_t_obs       = $me05_t_obs;
    $clmer_modpreparo->me05_c_natureza  = $me05_c_natureza;
    $clmer_modpreparo->me05_f_porcao    = $me05_f_porcao;
    $clmer_modpreparo->me05_i_alimento  = $me05_i_alimento;
    $clmer_modpreparo->me05_i_cardapio  = $novaversaoref;
    $clmer_modpreparo->incluir(null);
    
  }
  //seleciona caracteristica de preparo associados ao antigo cardapio
  $campos = " me06_c_tempopreparo,me06_c_graudificuldade,me06_c_descr ";
  $result = $clmer_caractpreparo->sql_record($clmer_caractpreparo->sql_query_file("",
                                                                                  $campos,
                                                                                  "",
                                                                                  " me06_i_cardapio = $antigaref"
                                                                                 ));
  $linhas = $clmer_caractpreparo->numrows;
  //Adiciona caracteristica de preparo do antigo cardapio na nova versão
  if ($linhas>0) {
    
    db_fieldsmemory($result,0);
    $clmer_caractpreparo->me06_c_tempopreparo    = $me06_c_tempopreparo;
    $clmer_caractpreparo->me06_c_graudificuldade = $me06_c_graudificuldade;
    $clmer_caractpreparo->me06_c_descr           = $me06_c_descr;
    $clmer_caractpreparo->me06_i_cardapio        = $novaversaoref;
    $clmer_caractpreparo->incluir(null);
    
  }
  //seleciona os itens do antigo cardapio
  $campos = " me07_f_quantidade,me07_i_alimento,me07_c_medida,me07_i_unidade ";
  $result = $clmer_cardapioitem->sql_record($clmer_cardapioitem->sql_query_file("",
                                                                                $campos,
                                                                                "",
                                                                                " me07_i_cardapio = $antigaref"
                                                                               ));
  $linhas = $clmer_cardapioitem->numrows;
  //Adiciona os itens do antigo cardapio na nova versão
  for ($x=0;$x<$linhas;$x++) {
    
    db_fieldsmemory($result,$x);
    $clmer_cardapioitem->me07_c_medida     = $me07_c_medida;
    $clmer_cardapioitem->me07_f_quantidade = $me07_f_quantidade;
    $clmer_cardapioitem->me07_i_alimento   = $me07_i_alimento;
    $clmer_cardapioitem->me07_i_unidade   = $me07_i_unidade;    
    $clmer_cardapioitem->me07_i_cardapio   = $novaversaoref;
    $clmer_cardapioitem->incluir(null);
    
  }
  //seleciona as substituições do antigo cardapio
  $campos  = " me29_i_alimentoorig,me29_i_alimentonovo,me29_f_quantidade, ";
  $campos .= " me29_c_medidacaseira,me29_d_inicio,me29_d_fim,me29_t_obs ";
  $result = $clmer_subitem->sql_record($clmer_subitem->sql_query_file("",
                                                                      $campos,
                                                                      "",
                                                                      " me29_i_refeicao = $antigaref"
                                                                     ));
  $linhas = $clmer_subitem->numrows;
  //Adiciona as substituições do antigo cardapio na nova versão
  for ($x=0;$x<$linhas;$x++) {
    
    db_fieldsmemory($result,$x);
    $clmer_subitem->me29_i_alimentoorig  = $me29_i_alimentoorig;
    $clmer_subitem->me29_i_alimentonovo  = $me29_i_alimentonovo;
    $clmer_subitem->me29_f_quantidade    = $me29_f_quantidade;
    $clmer_subitem->me29_c_medidacaseira = $me29_c_medidacaseira;
    $clmer_subitem->me29_d_inicio        = $me29_d_inicio;
    $clmer_subitem->me29_d_fim           = $me29_d_fim;
    $clmer_subitem->me29_t_obs           = $me29_t_obs;
    $clmer_subitem->me29_i_refeicao      = $novaversaoref;
    $clmer_subitem->incluir(null);
    
  }
    
    
    
  }
  //seleciona as escolas associados ao antigo cardapio
  $result = $clmer_cardapioescola->sql_record($clmer_cardapioescola->sql_query_file("","me32_i_codigo as me32_i_codigo_old,me32_i_escola,me32_i_ordem","","me32_i_tipocardapio = $antigo"));
  $linhas = $clmer_cardapioescola->numrows;
  //Adiciona as escolas do antigo cardapio na nova versão
  for ($x=0; $x<$linhas; $x++) {
  	
    db_fieldsmemory($result,$x);
    $clmer_cardapioescola->me32_i_escola       = $me32_i_escola;
    $clmer_cardapioescola->me32_i_tipocardapio = $novaversao;
    $clmer_cardapioescola->me32_i_ordem        = $me32_i_ordem;    
    $clmer_cardapioescola->incluir(null);
    $me32_i_codigo_new = $clmer_cardapioescola->me32_i_codigo;
    //seleciona as etapas associados a escola do antigo cardapio
    $result_tp = $clmer_tpcardapioturma->sql_record($clmer_tpcardapioturma->sql_query_file("","me28_i_serie","","me28_i_cardapioescola = $me32_i_codigo_old"));
    $linhas_tp = $clmer_tpcardapioturma->numrows;
    //Adiciona as etapas da escola do antigo cardapio na nova versão
    for ($y=0; $y<$linhas_tp; $y++) {
    	
      db_fieldsmemory($result_tp,$y);	
      $clmer_tpcardapioturma->me28_i_serie          = $me28_i_serie;
      $clmer_tpcardapioturma->me28_i_cardapioescola = $me32_i_codigo_new;
      $clmer_tpcardapioturma->incluir(null);
      
    }
    
  }
  db_fim_transacao();
  ?>
  <script>
   alert("Criada nova versão n° <?=$num_novaversao?> para o cardápio <?=$me27_c_nome?>");
   parent.location.href = "mer1_mer_tipocardapio002.php?chavepesquisa=<?=$novaversao?>";
  </script>
  <?
  
}
?>