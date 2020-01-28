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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_leitor_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clleitor = new cl_leitor;
$clleitor->rotulo->label();
$clrotulo = new rotulocampo;
$db_opcao = 3;
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  </head>
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <?
    if (isset($chavepesquisa)) {
    
      if ($tipo == "ALUNO") {
        
        $campos  = "ed47_i_codigo as codigo, ed47_v_nome as nome, ed47_v_ender as endereco, ed47_c_numero as numero";
        $campos .= " , ed47_v_compl as complemento, ed47_v_bairro as bairro, censomunicend.ed261_c_nome as municipio";
        $campos .= " , censoufend.ed260_c_sigla as uf, ed47_v_cep as cep, ed47_v_ident as identidade, ed47_v_cpf as cpf";
        $campos .= " , ed47_v_pai as pai, ed47_v_mae as mae, ed47_v_sexo as sexo, ed47_v_telef as telefone";
        $campos .= " , ed47_d_nasc as data_nascimento, ed47_i_estciv as estado_civil, ed47_v_telcel as celular";
        
        $sql  = "SELECT $campos                                                                                                \n";
        $sql .= "  FROM aluno                                                                                                  \n";
        $sql .= "       inner join pais                         on pais.ed228_i_codigo           = aluno.ed47_i_pais           \n";
        $sql .= "       left  join censouf as censoufident      on censoufident.ed260_i_codigo   = aluno.ed47_i_censoufident   \n";
        $sql .= "       left  join censouf as censoufnat        on censoufnat.ed260_i_codigo     = aluno.ed47_i_censoufnat     \n";
        $sql .= "       left  join censouf as censoufcert       on censoufcert.ed260_i_codigo    = aluno.ed47_i_censoufcert    \n";
        $sql .= "       left  join censouf as censoufend        on censoufend.ed260_i_codigo     = aluno.ed47_i_censoufend     \n";
        $sql .= "       left  join censomunic as censomunicnat  on censomunicnat.ed261_i_codigo  = aluno.ed47_i_censomunicnat  \n";
        $sql .= "       left  join censomunic as censomuniccert on censomuniccert.ed261_i_codigo = aluno.ed47_i_censomuniccert \n";
        $sql .= "       left  join censomunic as censomunicend  on censomunicend.ed261_i_codigo  = aluno.ed47_i_censomunicend  \n";
        $sql .= " WHERE ed47_i_codigo = $chavepesquisa";
        
      } else if ($tipo == "FUNCIONARIO") {

        $campos = "case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end as codigo,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as nome,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_ender else cgmcgm.z01_ender end as endereco,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_numero else cgmcgm.z01_numero end as numero,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_compl else cgmcgm.z01_compl end as complemento,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_bairro else cgmcgm.z01_bairro end as bairro,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_munic else cgmcgm.z01_munic end as municipio,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_uf else cgmcgm.z01_uf end as uf,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_cep else cgmcgm.z01_cep end as cep,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_ident else cgmcgm.z01_ident end as identidade,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_cgccpf else cgmcgm.z01_cgccpf end as cpf,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_pai else cgmcgm.z01_pai end as pai,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_mae else cgmcgm.z01_mae end as mae,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_sexo else cgmcgm.z01_sexo end as sexo,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_telef else cgmcgm.z01_telef end as telefone,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_nasc else cgmcgm.z01_nasc end as data_nascimento,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_estciv else cgmcgm.z01_estciv end as estado_civil,
                   case when ed20_i_tiposervidor = 1 then cgmrh.z01_telcel else cgmcgm.z01_telcel end as celular ";
        
        $sql  = "SELECT $campos                                                                                     \n";
        $sql .= "  FROM rechumano                                                                                   \n";
        $sql .= "       left join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo  \n";
        $sql .= "       left join rhpessoal        on rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal    \n";
        $sql .= "       left join cgm as cgmrh     on cgmrh.z01_numcgm = rhpessoal.rh01_numcgm                      \n";
        $sql .= "       left join rechumanocgm     on rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo      \n";
        $sql .= "       left join cgm as cgmcgm    on cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm                  \n";
        $sql .= " WHERE ed20_i_codigo = $chavepesquisa                                                              \n";
      } else if ($tipo == "PUBLICO") {

        $campos  = "z01_numcgm as codigo, z01_nome as nome, z01_ender as endereco, z01_numero as numero";
        $campos .= " , z01_compl as complemento, z01_bairro as bairro, z01_munic as municipio, z01_uf as uf";
        $campos .= " , z01_cep as cep, z01_ident as identidade, z01_cgccpf as cpf, z01_pai as pai, z01_mae as mae";
        $campos .= " , z01_sexo as sexo, z01_telef as telefone, z01_nasc as data_nascimento, z01_estciv as estado_civil";
        $campos .= " , z01_telcel as celular ";
        
        $sql  = "SELECT $campos                     \n";
        $sql .= "  FROM cgm                         \n";
        $sql .= " WHERE z01_numcgm = $chavepesquisa \n";
      } else if ($tipo == "CIDADAO") {

        $campos  = "ov02_sequencial as codigo, ov02_nome as nome, ov02_endereco as endereco, ov02_numero as numero";
        $campos .= " , ov02_compl as complemento, ov02_bairro as bairro, ov02_munic as municipio ,ov02_uf as uf";
        $campos .= " , ov02_cep as cep, ov02_ident as identidade, ov02_cnpjcpf as cpf, '' as pai, '' as mae";
        $campos .= ", '' as sexo, '' as telefone, '' as data_nascimento, '' as estado_civil, '' as celular ";
        
        $sql  = " SELECT $campos                           \n";
        $sql .= "   from cidadao                           \n";
        $sql .= "  WHERE ov02_sequencial = $chavepesquisa  \n";
      }
   
      $result = pg_query($sql);
      db_fieldsmemory($result, 0);
  ?>
      <table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
        <tr>
          <td width="55%" align="center" valign="top">
            <table width="100%" border="1" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap title="<?=@$Tcpf?>">
                  <?="<b>CPF: </b>"?>
                </td>
                <td>
                  <?db_input('cpf', 15, @$cpf, true, 'text', $db_opcao, "");?>
                  <?="<b>Identidade: </b>"?>
                  <?db_input('identidade', 15, @$identidade, true, 'text', $db_opcao);?>
                </td>
              </tr>
              <tr>
                <td title='<?=$codigo?>' nowrap>
                  <?="<b>Código: </b>"?>
                </td>
                <td nowrap>
                  <?db_input('codigo', 10, $codigo, true, 'text', 3);?>
                </td>
              </tr>
              <tr>
                <td nowrap title=<?=@$nome?>>
                  <?="<b>Nome: </b>"?>
                </td>
                <td nowrap title="<?=@$nome?>">
                  <?db_input('nome', 50, $nome, true, 'text', $db_opcao, "");?>
                </td>
              </tr>
              <tr>
                <td nowrap title=<?=@$pai?>>
                  <?="<b>Nome do Pai: </b>"?>
                </td>
                <td nowrap title="<?=@$Ted47_v_pai?>">
                  <?db_input('pai', 50, $pai, true, 'text', $db_opcao, "");?>
                </td>
              </tr>
              <tr>
                <td nowrap title=<?=@$mae?>>
                  <?="<b>Nome da Mãe: </b>"?>
                </td>
                <td nowrap title="<?=@$mae?>">
                  <?db_input('mae', 50, $mae, true, 'text', $db_opcao, "");?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=$data_nascimento?>">
                  <?="<b>Data de Nascimento: </b>"?>
                </td>
                <td nowrap title="<?=$data_nascimento?>">
                  <?db_inputdata('data_nascimento', @$ed47_d_nasc_dia,@$ed47_d_nasc_mes,@$ed47_d_nasc_ano,true,'text',$db_opcao);?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=$estado_civil?>">
                  <?="<b>Estado Civil: </b>"?>
                </td>
                <td nowrap title="<?=$estado_civil?>">
                  <?
                    $x = array("" => "", "1"=>"Solteiro","2"=>"Casado","3"=>"Viúvo","4"=>"Divorciado");
                    db_select('estado_civil', $x, true, $db_opcao);
                  ?>
                  <?="<b>Sexo: </b>"?>
                  <?
                    $sex = array("" => "", "M"=>"Masculino","F"=>"Feminino");
                    db_select('sexo', $sex, true, $db_opcao);
                  ?>
                </td>
              </tr>
            </table>
          </td>
          <td width="45%" align="center" valign="top">
            <table width="100%" border="1" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap title="<?=@$endereco?>">
                  <?db_ancora("<b>Endereço: </b>", "", $db_opcao);?>
                </td>
                <td nowrap>
                  <?db_input('endereco', 40, $endereco, true, 'text', 3);?>
                </td>
              </tr>
              <tr>
                <td width="29%" nowrap title="<?=@$numero?>">
                  <?="<b>Número: </b>"?>
                </td>
                <td width="71%" nowrap>
                  <?db_input('numero', 8, $numero, true, 'text', $db_opcao);?>
                  &nbsp;
                  <?="<b>Complemento: </b>"?>
                  <?db_input('complemento', 10, $complemento, true, 'text', $db_opcao);?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$bairro?>">
                  <?db_ancora("<b>Bairro: </b>", "", $db_opcao);?>
                </td>
                <td nowrap>
                  <?db_input('bairro', 25, $bairro, true, 'text', 3);?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$municipio?>">
                  <?="<b>Munícipio: </b>"?>
                </td>
                <td nowrap>
                  <?db_input('municipio', 30, @$municipio, true, 'text', $db_opcao);?>
                  <?="<b>UF: </b>"?>
                  <?db_input('uf', 2, @$uf, true, 'text', $db_opcao);?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$cep?>">
                  <?="<b>CEP: </b>"?>
                </td>
                <td nowrap>
                  <?db_input('cep', 9, $cep, true, 'text', $db_opcao);?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$telefone?>">
                  <?="<b>Telefone: </b>"?>
                </td>
                <td nowrap>
                  <?db_input('telefone', 12, $telefone, true, 'text', $db_opcao);?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$celular?>">
                  <?="<b>Celular: </b>"?>
                </td>
                <td nowrap>
                  <?db_input('celular', 12, $celular, true, 'text', $db_opcao);?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    <script>
      function js_dados_leitor(){
        js_OpenJanelaIframe('parent','db_iframe_alteradados','bib1_alunoabas000.php?leitor&chavepesquisa=<?=$chavepesquisa?>','Alterar Dados do Aluno',true);
      }
    </script>
    <?}?>
  </body>
</html>