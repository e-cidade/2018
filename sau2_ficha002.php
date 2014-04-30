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
include("classes/db_agendamentos_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clagendamentos = new cl_agendamentos;
$sql = "select sd01_c_siasus,
               sd01_i_familia,
               sd02_i_codigo,
               sd02_c_razao,
               sd02_c_endereco,
               sd02_c_cidade,
               sd02_i_numero,
               sd02_c_bairro,
               sd02_c_cep,
               sd02_c_siasus,
               cgm.z01_numcgm,
               cgm.z01_nome,
               cgm.z01_ender,
               cgm.z01_numero,
               cgm.z01_bairro,
               cgm.z01_munic,
               cgm.z01_cep,
               case
                when cgm.z01_sexo = 'M'
                then 'MASCULINO'
                when cgm.z01_sexo = 'F'
                then 'FEMININO'
               end as z01_sexo,
               sd03_i_id,
               cgm1.z01_nome as sd03_c_nome,
               sd03_i_crm,
               sd23_c_atendimento,
               to_char(sd23_d_consulta,'dd/mm/yyyy') as sd23_d_consulta,
               sd23_c_hora,
               sd23_c_hora2
               from agendamentos
               inner join cgm            on z01_numcgm = sd23_i_cgm
               left  join cgs            on z01_numcgm = sd01_i_cgm
               inner join db_usuarios    on id_usuario = sd23_i_usuario
               inner join especialidades on sd05_i_codigo = sd23_i_especialidade
               inner join unidades       on sd02_i_codigo = sd23_i_unidade
               inner join medicos        on sd03_i_id = sd23_i_medico
               inner join cgm as cgm1    on  sd03_i_codigo  = cgm1.z01_numcgm
               where sd23_c_atendimento = '$Agenda'";
$query = $clagendamentos->sql_record($sql);
db_fieldsmemory($query,0);
echo "<html>";
echo "<body onload=\"alert('Antes de Imprimir certifique-se que\\nO cabeçalho,o radapé e as margens da página estejam zeradas\\n\\nPara configura-las:\\n1. Abra seu Browser\\n2. Va na guia Arquivo/Configurar Página.../Margens/... \\n3. Mude as margens para zero,o cabeçalho e o radapé para -- em branco -- '); print();\" leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>";
echo "<pre>";
echo "+------------------------------------------------------------------------------+<br>";
echo "|DBseller - Sistema de Saúde / PREFEITURA MUNICIPAL DE ALEGRETE                |<br>";
echo "|FICHA DE ATENDIMENTO                                                          |<br>";
echo "|Atendimento: ".substr($sd23_c_atendimento,0,4)." | ".substr($sd23_c_atendimento,4,2)." | ".substr($sd23_c_atendimento,6,5)?> Data/Hora: <?=$sd23_d_consulta." /".$sd23_c_hora."                   |<br>";
echo "+------------------------------------------------------------------------------+<br>";
echo "|1. UNIDADE PRESTADORA DE ATENDIMENTO                                          |<br>";
echo "|".str_pad($sd02_i_codigo." - ".$sd02_c_razao,78," ")."|<br>";
echo "|Endereço .:".str_pad(trim($sd02_i_codigo)." - ".trim($sd02_c_razao),67," ")."|<br>";
echo "|CEP ......:".str_pad(trim($sd02_c_cep),67," ")."|<br>";
echo "|Bairro ...:".str_pad(trim($sd02_c_bairro),67," ")."|<br>";
echo "|Cidade ...:".str_pad(trim($sd02_c_cidade),67," ")."|<br>";
echo "|Sia/Sus ..:".str_pad(trim($sd02_c_siasus),67," ")."|<br>";
echo "|2. IDENTIFICAÇAO DO PACIENTE                                                  |<br>";
echo "|".str_pad($z01_numcgm." - ".$z01_nome,78," ")."|<br>";
echo "|Endereço .:".str_pad(trim($z01_ender).",".trim($z01_numero),67," ")."|<br>";
echo "|CEP ......:".str_pad(trim($z01_cep),67," ")."|<br>";
echo "|Bairro ...:".str_pad(trim($z01_bairro),67," ")."|<br>";
echo "|Cidade ...:".str_pad(trim($z01_munic),67," ")."|<br>";
echo "|Sia/Sus ..:".str_pad(trim($sd01_c_siasus),67," ")."|<br>";
echo "|Familia ..:".str_pad(trim($sd01_i_familia),67," ")."|<br>";
echo "|3. IDENTIFICAÇAO DO PROFISSIONAL                                              |<br>";
echo "|".str_pad($sd03_i_id." - ".$sd03_c_nome,78," ")."|<br>";
echo "+------------------------------------------------------------------------------+<br>";
echo "</pre>";
echo "</body>";
echo "</html>";
?>