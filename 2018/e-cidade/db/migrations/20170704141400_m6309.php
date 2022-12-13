<?php

use Classes\PostgresMigration;

class M6309 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

insert into db_layouttxt select 264, 'TCE/RS - LICITACON - LICITACAO 1.3', 0 ,'', 6 where not exists (select 1 from db_layouttxt where db50_codigo = 264);
insert into db_layoutlinha select 874 ,264 ,'CABEÇALHO' ,1 ,0 ,0 ,0 ,'' ,'' ,'0' where not exists (select 1 from db_layoutlinha where db51_codigo = 874);
insert into db_layoutlinha select 875 ,264 ,'REGISTRO' ,3 ,0 ,0 ,0 ,'' ,'' ,'0' where not exists (select 1 from db_layoutlinha where db51_codigo = 875);
insert into db_layoutcampos select 14991 ,874 ,'CNPJ' ,'CNPJ' ,1 ,1 ,'' ,14 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 14991);
insert into db_layoutcampos select 14992 ,874 ,'DATA_INICIAL' ,'DATA_INICIAL' ,1 ,15 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 14992);
insert into db_layoutcampos select 14993 ,874 ,'DATA_FINAL' ,'DATA_FINAL' ,1 ,25 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 14993);
insert into db_layoutcampos select 14994 ,874 ,'DATA_GERACAO' ,'DATA_GERACAO' ,1 ,35 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 14994);
insert into db_layoutcampos select 14995 ,874 ,'NOME_SETOR' ,'NOME_SETOR' ,1 ,45 ,'' ,150 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 14995);
insert into db_layoutcampos select 14996 ,874 ,'TOTAL_REGISTROS' ,'TOTAL_REGISTROS' ,1 ,195 ,'' ,15 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 14996);
insert into db_layoutcampos select 14997 ,875 ,'NR_LICITACAO' ,'NR_LICITACAO' ,1 ,1 ,'' ,20 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 14997);
insert into db_layoutcampos select 14998 ,875 ,'ANO_LICITACAO' ,'ANO_LICITACAO' ,1 ,21 ,'' ,4 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 14998);
insert into db_layoutcampos select 14999 ,875 ,'CD_TIPO_MODALIDADE' ,'CD_TIPO_MODALIDADE' ,1 ,25 ,'' ,3 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 14999);
insert into db_layoutcampos select 15000 ,875 ,'NR_COMISSAO' ,'NR_COMISSAO' ,1 ,28 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15000);
insert into db_layoutcampos select 15001 ,875 ,'ANO_COMISSAO' ,'ANO_COMISSAO' ,1 ,38 ,'' ,4 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15001);
insert into db_layoutcampos select 15002 ,875 ,'TP_COMISSAO' ,'TP_COMISSAO' ,1 ,42 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15002);
insert into db_layoutcampos select 15003 ,875 ,'NR_PROCESSO' ,'NR_PROCESSO' ,1 ,43 ,'' ,20 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15003);
insert into db_layoutcampos select 15004 ,875 ,'ANO_PROCESSO' ,'ANO_PROCESSO' ,1 ,63 ,'' ,4 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15004);
insert into db_layoutcampos select 15005 ,875 ,'TP_OBJETO' ,'TP_OBJETO' ,1 ,67 ,'' ,3 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15005);
insert into db_layoutcampos select 15006 ,875 ,'CD_TIPO_FASE_ATUAL' ,'CD_TIPO_FASE_ATUAL' ,1 ,70 ,'' ,3 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15006);
insert into db_layoutcampos select 15007 ,875 ,'TP_LICITACAO' ,'TP_LICITACAO' ,1 ,73 ,'' ,3 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15007);
insert into db_layoutcampos select 15008 ,875 ,'TP_NIVEL_JULGAMENTO' ,'TP_NIVEL_JULGAMENTO' ,1 ,76 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15008);
insert into db_layoutcampos select 15009 ,875 ,'DT_AUTORIZACAO_ADESAO' ,'DT_AUTORIZACAO_ADESAO' ,1 ,77 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15009);
insert into db_layoutcampos select 15010 ,875 ,'TP_CARACTERISTICA_OBJETO' ,'TP_CARACTERISTICA_OBJETO' ,1 ,87 ,'' ,2 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15010);
insert into db_layoutcampos select 15011 ,875 ,'TP_NATUREZA' ,'TP_NATUREZA' ,1 ,89 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15011);
insert into db_layoutcampos select 15012 ,875 ,'TP_REGIME_EXECUCAO' ,'TP_REGIME_EXECUCAO' ,1 ,90 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15012);
insert into db_layoutcampos select 15013 ,875 ,'BL_PERMITE_SUBCONTRATACAO' ,'BL_PERMITE_SUBCONTRATACAO' ,1 ,91 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15013);
insert into db_layoutcampos select 15014 ,875 ,'TP_BENEFICIO_MICRO_EPP' ,'TP_BENEFICIO_MICRO_EPP' ,1 ,92 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15014);
insert into db_layoutcampos select 15015 ,875 ,'TP_FORNECIMENTO' ,'TP_FORNECIMENTO' ,1 ,93 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15015);
insert into db_layoutcampos select 15016 ,875 ,'TP_ATUACAO_REGISTRO' ,'TP_ATUACAO_REGISTRO' ,1 ,94 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15016);
insert into db_layoutcampos select 15017 ,875 ,'NR_LICITACAO_ORIGINAL' ,'NR_LICITACAO_ORIGINAL' ,1 ,95 ,'' ,20 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15017);
insert into db_layoutcampos select 15018 ,875 ,'ANO_LICITACAO_ORIGINAL' ,'ANO_LICITACAO_ORIGINAL' ,1 ,115 ,'' ,4 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15018);
insert into db_layoutcampos select 15019 ,875 ,'NR_ATA_REGISTRO_PRECO' ,'NR_ATA_REGISTRO_PRECO' ,1 ,119 ,'' ,20 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15019);
insert into db_layoutcampos select 15020 ,875 ,'DT_ATA_REGISTRO_PRECO' ,'DT_ATA_REGISTRO_PRECO' ,1 ,139 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15020);
insert into db_layoutcampos select 15021 ,875 ,'PC_TAXA_RISCO' ,'PC_TAXA_RISCO' ,1 ,149 ,'' ,6 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15021);
insert into db_layoutcampos select 15022 ,875 ,'TP_EXECUCAO' ,'TP_EXECUCAO' ,1 ,155 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15022);
insert into db_layoutcampos select 15023 ,875 ,'TP_DISPUTA' ,'TP_DISPUTA' ,1 ,156 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15023);
insert into db_layoutcampos select 15024 ,875 ,'TP_PREQUALIFICACAO' ,'TP_PREQUALIFICACAO' ,1 ,157 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15024);
insert into db_layoutcampos select 15025 ,875 ,'BL_INVERSAO_FASES' ,'BL_INVERSAO_FASES' ,1 ,158 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15025);
insert into db_layoutcampos select 15026 ,875 ,'TP_RESULTADO_GLOBAL' ,'TP_RESULTADO_GLOBAL' ,1 ,159 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15026);
insert into db_layoutcampos select 15027 ,875 ,'TP_RESULTADO_LICITACAO' ,'TP_RESULTADO_LICITACAO' ,1 ,160 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15027);
insert into db_layoutcampos select 15028 ,875 ,'CNPJ_ORGAO_GERENCIADOR' ,'CNPJ_ORGAO_GERENCIADOR' ,1 ,161 ,'' ,14 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15028);
insert into db_layoutcampos select 15029 ,875 ,'NM_ORGAO_GERENCIADOR' ,'NM_ORGAO_GERENCIADOR' ,1 ,175 ,'' ,60 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15029);
insert into db_layoutcampos select 15030 ,875 ,'DS_OBJETO' ,'DS_OBJETO' ,1 ,235 ,'' ,500 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15030);
insert into db_layoutcampos select 15031 ,875 ,'CD_TIPO_FUNDAMENTACAO' ,'CD_TIPO_FUNDAMENTACAO' ,1 ,735 ,'' ,8 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15031);
insert into db_layoutcampos select 15032 ,875 ,'NR_ARTIGO' ,'NR_ARTIGO' ,1 ,743 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15032);
insert into db_layoutcampos select 15033 ,875 ,'DS_INCISO' ,'DS_INCISO' ,1 ,753 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15033);
insert into db_layoutcampos select 15034 ,875 ,'DS_LEI' ,'DS_LEI' ,1 ,763 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15034);
insert into db_layoutcampos select 15035 ,875 ,'DT_INICIO_INSCR_CRED' ,'DT_INICIO_INSCR_CRED' ,1 ,773 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15035);
insert into db_layoutcampos select 15036 ,875 ,'DT_FIM_INSCR_CRED' ,'DT_FIM_INSCR_CRED' ,1 ,783 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15036);
insert into db_layoutcampos select 15037 ,875 ,'DT_INICIO_VIGEN_CRED' ,'DT_INICIO_VIGEN_CRED' ,1 ,793 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15037);
insert into db_layoutcampos select 15038 ,875 ,'DT_FIM_VIGEN_CRED' ,'DT_FIM_VIGEN_CRED' ,1 ,803 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15038);
insert into db_layoutcampos select 15039 ,875 ,'VL_LICITACAO' ,'VL_LICITACAO' ,1 ,813 ,'' ,16 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15039);
insert into db_layoutcampos select 15040 ,875 ,'BL_ORCAMENTO_SIGILOSO' ,'BL_ORCAMENTO_SIGILOSO' ,1 ,829 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15040);
insert into db_layoutcampos select 15041 ,875 ,'BL_RECEBE_INSCRICAO_PER_VIG' ,'BL_RECEBE_INSCRICAO_PER_VIG' ,1 ,830 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15041);
insert into db_layoutcampos select 15042 ,875 ,'BL_PERMITE_CONSORCIO' ,'BL_PERMITE_CONSORCIO' ,1 ,831 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15042);
insert into db_layoutcampos select 15043 ,875 ,'DT_ABERTURA' ,'DT_ABERTURA' ,1 ,832 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15043);
insert into db_layoutcampos select 15044 ,875 ,'DT_HOMOLOGACAO' ,'DT_HOMOLOGACAO' ,1 ,842 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15044);
insert into db_layoutcampos select 15045 ,875 ,'DT_ADJUDICACAO' ,'DT_ADJUDICACAO' ,1 ,852 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15045);
insert into db_layoutcampos select 15046 ,875 ,'BL_LICIT_PROPRIA_ORGAO' ,'BL_LICIT_PROPRIA_ORGAO' ,1 ,862 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15046);
insert into db_layoutcampos select 15047 ,875 ,'TP_DOCUMENTO_FORNECEDOR' ,'TP_DOCUMENTO_FORNECEDOR' ,1 ,863 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15047);
insert into db_layoutcampos select 15048 ,875 ,'NR_DOCUMENTO_FORNECEDOR' ,'NR_DOCUMENTO_FORNECEDOR' ,1 ,864 ,'' ,14 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15048);
insert into db_layoutcampos select 15049 ,875 ,'TP_DOCUMENTO_VENCEDOR' ,'TP_DOCUMENTO_VENCEDOR' ,1 ,878 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15049);
insert into db_layoutcampos select 15050 ,875 ,'NR_DOCUMENTO_VENCEDOR' ,'NR_DOCUMENTO_VENCEDOR' ,1 ,879 ,'' ,14 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15050);
insert into db_layoutcampos select 15051 ,875 ,'VL_HOMOLOGADO' ,'VL_HOMOLOGADO' ,1 ,893 ,'' ,16 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15051);
insert into db_layoutcampos select 15052 ,875 ,'BL_GERA_DESPESA' ,'BL_GERA_DESPESA' ,1 ,909 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15052);
insert into db_layoutcampos select 15053 ,875 ,'DS_OBSERVACAO' ,'DS_OBSERVACAO' ,1 ,910 ,'' ,500 ,'f' ,'t' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15053);

-- Ajustando leiaute do LICITACAO.TXT 1.3
update db_layoutcampos set db52_posicao = db52_posicao-1 where db52_layoutlinha = 875 and db52_posicao >= 160 and db52_codigo <> 15027;
delete from db_layoutcampos where db52_codigo = 15027;
update db_layoutlinha set db51_codigo = 874 , db51_layouttxt = 264 , db51_descr = 'CABEÇALHO' , db51_tipolinha = 1 , db51_tamlinha = 0 , db51_linhasantes = 0 , db51_linhasdepois = 0 , db51_obs = '' , db51_separador = '|' , db51_compacta = '1' where db51_codigo = 874;
update db_layoutlinha set db51_codigo = 875 , db51_layouttxt = 264 , db51_descr = 'REGISTRO' , db51_tipolinha = 3 , db51_tamlinha = 0 , db51_linhasantes = 0 , db51_linhasdepois = 0 , db51_obs = '' , db51_separador = '|' , db51_compacta = '1' where db51_codigo = 875;

-- Cadastrando novas opções para o tipo de fundamentação
insert into db_cadattdinamicoatributosopcoes select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), (select db109_sequencial from db_cadattdinamicoatributos where db109_nome = 'codigofundamentacao'), 'A24I', 'Art. 24, inc. I, da Lei nº 8.666/93' where not exists (select 1 from db_cadattdinamicoatributosopcoes where db18_cadattdinamicoatributos = (select db109_sequencial from db_cadattdinamicoatributos where db109_nome = 'codigofundamentacao') and db18_opcao = 'A24I');
insert into db_cadattdinamicoatributosopcoes select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), (select db109_sequencial from db_cadattdinamicoatributos where db109_nome = 'codigofundamentacao'), 'A24II', 'Art. 24, inc. II, da Lei nº 8.666/93' where not exists (select 1 from db_cadattdinamicoatributosopcoes where db18_cadattdinamicoatributos = (select db109_sequencial from db_cadattdinamicoatributos where db109_nome = 'codigofundamentacao') and db18_opcao = 'A24II');

-- Cadastrando nova opção para o tipo de benefício para mepp.
insert into db_cadattdinamicoatributosopcoes select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), (select db109_sequencial from db_cadattdinamicoatributos where db109_nome = 'tipobeneficiomicroepp'), 'C', 'Cotas para ME/EPP' where not exists (select 1 from db_cadattdinamicoatributosopcoes where db18_cadattdinamicoatributos = (select db109_sequencial from db_cadattdinamicoatributos where db109_nome = 'tipobeneficiomicroepp') and db18_opcao = 'C');

alter table liccomissao alter COLUMN l30_nomearquivo type text;
alter table acordodocumento alter COLUMN ac40_nomearquivo type text;

-- Dicionário de dados
update db_syscampo set conteudo = 'text', tamanho = 1 where codcam = 21705;
update db_syscampo set conteudo = 'text', tamanho = 1 where codcam = 18492;

-- Cadastro do leiaute ITEM.TXT 1.3
insert into db_layouttxt select 273 , 'TCE/RS - LICITACON - ITEM 1.3' ,0 ,'', 6 where not exists (select 1 from db_layouttxt where db50_codigo = 273);
insert into db_layoutlinha select 884 ,273 ,'CABEÇALHO' ,1 ,0 ,0 ,0 ,'' ,'|' ,'1' where not exists (select 1 from db_layoutlinha where db51_codigo = 884);
insert into db_layoutcampos select 15130, 884 ,'CNPJ' ,'CNPJ' ,1 ,1 ,'' ,14 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15130);
insert into db_layoutcampos select 15131, 884 ,'DATA_INICIAL' ,'DATA_INICIAL' ,1 ,15 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15131);
insert into db_layoutcampos select 15132, 884 ,'DATA_FINAL' ,'DATA_FINAL' ,1 ,25 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15132);
insert into db_layoutcampos select 15133, 884 ,'DATA_GERACAO' ,'DATA_GERACAO' ,1 ,35 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15133);
insert into db_layoutcampos select 15134, 884 ,'NOME_SETOR' ,'NOME_SETOR' ,1 ,45 ,'' ,150 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15134);
insert into db_layoutcampos select 15135, 884 ,'TOTAL_REGISTROS' ,'TOTAL_REGISTROS' ,1 ,195 ,'' ,15 ,'false' ,'true' ,'d' ,'' ,0 where not exists (select 1 from db_layoutcampos where db52_codigo = 15135);
insert into db_layoutlinha select 885, 273 ,'REGISTRO' ,3 ,0 ,0 ,0 ,'' ,'|' ,'1' where not exists (select 1 from db_layoutlinha where db51_codigo = 885);
insert into db_layoutcampos select 15136, 885 ,'NR_LICITACAO' ,'NR_LICITACAO' ,1 ,1 ,'' ,20 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15136);
insert into db_layoutcampos select 15137, 885 ,'ANO_LICITACAO' ,'ANO_LICITACAO' ,1 ,21 ,'' ,4 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15137);
insert into db_layoutcampos select 15138, 885 ,'CD_TIPO_MODALIDADE' ,'CD_TIPO_MODALIDADE' ,1 ,25 ,'' ,3 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15138);
insert into db_layoutcampos select 15139, 885 ,'NR_LOTE' ,'NR_LOTE' ,1 ,28 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15139);
insert into db_layoutcampos select 15140, 885 ,'NR_ITEM' ,'NR_ITEM' ,1 ,38 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15140);
insert into db_layoutcampos select 15141, 885 ,'NR_ITEM_ORIGINAL' ,'NR_ITEM_ORIGINAL' ,1 ,48 ,'' ,20 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15141);
insert into db_layoutcampos select 15142, 885 ,'DS_ITEM' ,'DS_ITEM' ,13 ,68 ,'' ,300 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15142);
insert into db_layoutcampos select 15143, 885 ,'QT_ITENS' ,'QT_ITENS' ,1 ,368 ,'' ,12 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15143);
insert into db_layoutcampos select 15144, 885 ,'SG_UNIDADE_MEDIDA' ,'SG_UNIDADE_MEDIDA' ,1 ,380 ,'' ,5 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15144);
insert into db_layoutcampos select 15145, 885 ,'VL_UNITARIO_ESTIMADO' ,'VL_UNITARIO_ESTIMADO' ,1 ,385 ,'' ,16 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15145);
insert into db_layoutcampos select 15146, 885 ,'VL_TOTAL_ESTIMADO' ,'VL_TOTAL_ESTIMADO' ,1 ,401 ,'' ,16 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15146);
insert into db_layoutcampos select 15147, 885 ,'DT_REF_VALOR_ESTIMADO' ,'DT_REF_VALOR_ESTIMADO' ,1 ,417 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15147);
insert into db_layoutcampos select 15148, 885 ,'PC_BDI_ESTIMADO' ,'PC_BDI_ESTIMADO' ,1 ,427 ,'' ,6 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15148);
insert into db_layoutcampos select 15149, 885 ,'PC_ENCARGOS_SOCIAIS_ESTIMADO' ,'PC_ENCARGOS_SOCIAIS_ESTIMADO' ,1 ,433 ,'' ,7 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15149);
insert into db_layoutcampos select 15150, 885 ,'CD_FONTE_REFERENCIA' ,'CD_FONTE_REFERENCIA' ,1 ,440 ,'' ,20 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15150);
insert into db_layoutcampos select 15151, 885 ,'DS_FONTE_REFERENCIA' ,'DS_FONTE_REFERENCIA' ,13 ,460 ,'' ,60 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15151);
insert into db_layoutcampos select 15152, 885 ,'TP_RESULTADO_ITEM' ,'TP_RESULTADO_ITEM' ,1 ,520 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15152);
insert into db_layoutcampos select 15153, 885 ,'VL_UNITARIO_HOMOLOGADO' ,'VL_UNITARIO_HOMOLOGADO' ,1 ,521 ,'' ,16 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15153);
insert into db_layoutcampos select 15154, 885 ,'VL_TOTAL_HOMOLOGADO' ,'VL_TOTAL_HOMOLOGADO' ,1 ,537 ,'' ,16 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15154);
insert into db_layoutcampos select 15155, 885 ,'PC_BDI_HOMOLOGADO' ,'PC_BDI_HOMOLOGADO' ,1 ,553 ,'' ,6 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15155);
insert into db_layoutcampos select 15156, 885 ,'PC_ENCARGOS_SOCIAIS_HOMOLOGADO' ,'PC_ENCARGOS_SOCIAIS_HOMOLOGADO' ,1 ,559 ,'' ,6 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15156);
insert into db_layoutcampos select 15157, 885 ,'TP_ORCAMENTO' ,'TP_ORCAMENTO' ,1 ,565 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15157);
insert into db_layoutcampos select 15158, 885 ,'CD_TIPO_FAMILIA' ,'CD_TIPO_FAMILIA' ,1 ,566 ,'' ,3 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15158);
insert into db_layoutcampos select 15159, 885 ,'CD_TIPO_SUBFAMILIA' ,'CD_TIPO_SUBFAMILIA' ,1 ,569 ,'' ,3 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15159);
insert into db_layoutcampos select 15160, 885 ,'TP_DOCUMENTO_VENCEDOR' ,'TP_DOCUMENTO_VENCEDOR' ,1 ,572 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15160);
insert into db_layoutcampos select 15161, 885 ,'NR_DOCUMENTO_VENCEDOR' ,'NR_DOCUMENTO_VENCEDOR' ,1 ,573 ,'' ,14 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15161);
insert into db_layoutcampos select 15164, 885 ,'TP_DOCUMENTO_VENCEDOR' ,'TP_DOCUMENTO_VENCEDOR' ,1 ,587 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15164);
update db_layoutcampos set db52_codigo = 15164 , db52_layoutlinha = 885 , db52_nome = 'TP_DOCUMENTO_FORNECEDOR' , db52_descr = 'TP_DOCUMENTO_FORNECEDOR' , db52_layoutformat = 1 , db52_posicao = 587 , db52_default = '' , db52_tamanho = 1 , db52_ident = 'f' , db52_imprimir = 't' , db52_alinha = 'd' , db52_obs = '' , db52_quebraapos = 0 where db52_codigo = 15164;
insert into db_layoutcampos select 15168 ,885 ,'NR_DOCUMENTO_FORNECEDOR' ,'NR_DOCUMENTO_FORNECEDOR' ,1 ,588 ,'' ,14 ,'f' ,'t' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo = 15168);

select fc_executa_ddl(
$$
insert into db_layouttxt( db50_codigo ,db50_layouttxtgrupo ,db50_descr ,db50_quantlinhas ,db50_obs ) values ( 271 ,6 ,'TCE/RS - LICITACON - LOTE V1.3' ,0 ,'' );
insert into db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 881 ,271 ,'CABEÇALHO' ,1 ,0 ,0 ,0 ,'' ,'|' , true );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15080 ,881 ,'CNPJ' ,'CNPJ' ,1 ,1 ,'' ,14 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15081 ,881 ,'DATA_INICIAL' ,'DATA_INICIAL' ,1 ,15 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15082 ,881 ,'DATA_FINAL' ,'DATA_FINAL' ,1 ,25 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15083 ,881 ,'DATA_GERACAO' ,'DATA_GERACAO' ,1 ,35 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15084 ,881 ,'NOME_SETOR' ,'NOME_SETOR' ,1 ,45 ,'' ,150 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15085 ,881 ,'TOTAL_REGISTROS' ,'TOTAL_REGISTROS' ,1 ,195 ,'' ,15 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 882 ,271 ,'REGISTRO' ,3 ,0 ,0 ,0 ,'' ,'|' , true);
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15086 ,882 ,'NR_LICITACAO' ,'NR_LICITACAO' ,1 ,1 ,'' ,20 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15087 ,882 ,'ANO_LICITACAO' ,'ANO_LICITACAO' ,1 ,21 ,'' ,4 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15088 ,882 ,'CD_TIPO_MODALIDADE' ,'CD_TIPO_MODALIDADE' ,1 ,25 ,'' ,3 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15089 ,882 ,'NR_LOTE' ,'NR_LOTE' ,1 ,28 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15090 ,882 ,'DS_LOTE' ,'DS_LOTE' ,13 ,38 ,'' ,500 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15091 ,882 ,'VL_ESTIMADO' ,'VL_ESTIMADO' ,1 ,538 ,'' ,16 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15092 ,882 ,'VL_HOMOLOGADO' ,'VL_HOMOLOGADO' ,1 ,554 ,'' ,16 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15093 ,882 ,'TP_RESULTADO_LOTE' ,'TP_RESULTADO_LOTE' ,1 ,570 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15094 ,882 ,'TP_DOCUMENTO_VENCEDOR' ,'TP_DOCUMENTO_VENCEDOR' ,1 ,571 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15095 ,882 ,'NR_DOCUMENTO_VENCEDOR' ,'NR_DOCUMENTO_VENCEDOR' ,1 ,572 ,'' ,14 ,'false' ,'true' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15096 ,882 ,'TP_DOCUMENTO_FORNECEDOR' ,'TP_DOCUMENTO_FORNECEDOR' ,1 ,586 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0 );
insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 15097 ,882 ,'NR_DOCUMENTO_FORNECEDOR' ,'NR_DOCUMENTO_FORNECEDOR' ,1 ,587 ,'' ,14 ,'f' ,'t' ,'d' ,'' ,0 );
$$
);


-- Atualizando DS_ITEM da ITEM.TXT 1.2 e 1.3
update db_layoutcampos set db52_codigo = 15142 , db52_layoutlinha = 885 , db52_nome = 'DS_ITEM' , db52_descr = 'DS_ITEM' , db52_layoutformat = 13 , db52_posicao = 68 , db52_default = '' , db52_tamanho = 500 , db52_ident = 'f' , db52_imprimir = 't' , db52_alinha = 'd' , db52_obs = '' , db52_quebraapos = 0 where db52_codigo = 15142;
update db_layoutcampos set db52_posicao = db52_posicao+200 where db52_layoutlinha = 885 and db52_posicao >= 68 and db52_codigo <> 15142;
update db_layoutcampos set db52_codigo = 12970 , db52_layoutlinha = 811 , db52_nome = 'DS_ITEM' , db52_descr = 'DS_ITEM' , db52_layoutformat = 13 , db52_posicao = 68 , db52_default = '' , db52_tamanho = 500 , db52_ident = 'f' , db52_imprimir = 't' , db52_alinha = 'd' , db52_obs = '' , db52_quebraapos = 0 where db52_codigo = 12970;
update db_layoutcampos set db52_posicao = db52_posicao+200 where db52_layoutlinha = 811 and db52_posicao >= 68 and db52_codigo <> 12970;

-- Atualizando tamanho do campo NOME_ARQUIVO_DOCUMENTO do arquivo DOCUMENTO_LIC.TXT
update db_layoutcampos set db52_codigo = 12937 , db52_layoutlinha = 807 , db52_nome = 'NOME_ARQUIVO_DOCUMENTO' , db52_descr = 'NOME_ARQUIVO_DOCUMENTO' , db52_layoutformat = 13 , db52_posicao = 31 , db52_default = '' , db52_tamanho = 200 , db52_ident = 'f' , db52_imprimir = 't' , db52_alinha = 'd' , db52_obs = '' , db52_quebraapos = 0 where db52_codigo = 12937;
update db_layoutcampos set db52_posicao = db52_posicao+100 where db52_layoutlinha = 807 and db52_posicao >= 31 and db52_codigo <> 12937;

update db_layoutcampos set db52_tamanho = 200 where db52_codigo in (12797,13148);
alter table acordodocumento alter COLUMN ac40_descricao type varchar(300);

-- Cadastro do leiaute CONTRATO.TXT 1.3
insert into db_layouttxt select 275 ,'TCE/RS - LICITACON - CONTRATOS 1.3' ,0 ,'', 6 where not exists (select 1 from db_layouttxt where db50_codigo  = 275 );
insert into db_layoutlinha select 886 ,275 ,'CABEÇALHO' ,1 ,0 ,0 ,0 ,'' ,'|' ,'1' where not exists (select 1 from db_layoutlinha where db51_codigo  = 886);
insert into db_layoutlinha select 887 ,275 ,'REGISTRO' ,3 ,0 ,0 ,0 ,'' ,'|' ,'1' where not exists (select 1 from db_layoutlinha where db51_codigo  = 887);
insert into db_layoutcampos select 15251 ,886 ,'CNPJ' ,'CNPJ' ,1 ,1 ,'' ,14 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15251);
insert into db_layoutcampos select 15252 ,886 ,'DATA_INICIAL' ,'DATA_INICIAL' ,1 ,15 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15252);
insert into db_layoutcampos select 15253 ,886 ,'DATA_FINAL' ,'DATA_FINAL' ,1 ,25 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15253);
insert into db_layoutcampos select 15254 ,886 ,'DATA_GERACAO' ,'DATA_GERACAO' ,1 ,35 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15254);
insert into db_layoutcampos select 15255 ,886 ,'NOME_SETOR' ,'NOME_SETOR' ,1 ,45 ,'' ,150 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15255);
insert into db_layoutcampos select 15256 ,886 ,'TOTAL_REGISTROS' ,'TOTAL_REGISTROS' ,1 ,195 ,'' ,15 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15256);
insert into db_layoutcampos select 15257 ,887 ,'NR_LICITACAO' ,'NR_LICITACAO' ,1 ,1 ,'' ,20 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15257);
insert into db_layoutcampos select 15258 ,887 ,'ANO_LICITACAO' ,'ANO_LICITACAO' ,1 ,21 ,'' ,4 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15258);
insert into db_layoutcampos select 15259 ,887 ,'CD_TIPO_MODALIDADE' ,'CD_TIPO_MODALIDADE' ,1 ,25 ,'' ,3 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15259);
insert into db_layoutcampos select 15260 ,887 ,'NR_CONTRATO' ,'NR_CONTRATO' ,1 ,28 ,'' ,20 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15260);
insert into db_layoutcampos select 15261 ,887 ,'ANO_CONTRATO' ,'ANO_CONTRATO' ,1 ,48 ,'' ,4 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15261);
insert into db_layoutcampos select 15262 ,887 ,'TP_INSTRUMENTO' ,'TP_INSTRUMENTO' ,1 ,52 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15262);
insert into db_layoutcampos select 15263 ,887 ,'NR_PROCESSO' ,'NR_PROCESSO' ,1 ,53 ,'' ,20 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15263);
insert into db_layoutcampos select 15264 ,887 ,'ANO_PROCESSO' ,'ANO_PROCESSO' ,1 ,73 ,'' ,4 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15264);
insert into db_layoutcampos select 15265 ,887 ,'TP_DOCUMENTO_CONTRATADO' ,'TP_DOCUMENTO_CONTRATADO' ,1 ,77 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15265);
insert into db_layoutcampos select 15266 ,887 ,'NR_DOCUMENTO_CONTRATADO' ,'NR_DOCUMENTO_CONTRATADO' ,1 ,78 ,'' ,14 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15266);
insert into db_layoutcampos select 15267 ,887 ,'DT_INICIO_VIGENCIA' ,'DT_INICIO_VIGENCIA' ,1 ,92 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15267);
insert into db_layoutcampos select 15268 ,887 ,'DT_FINAL_VIGENCIA' ,'DT_FINAL_VIGENCIA' ,1 ,102 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15268);
insert into db_layoutcampos select 15269 ,887 ,'VL_CONTRATO' ,'VL_CONTRATO' ,1 ,112 ,'' ,16 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15269);
insert into db_layoutcampos select 15270 ,887 ,'DT_ASSINATURA' ,'DT_ASSINATURA' ,1 ,128 ,'' ,10 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15270);
insert into db_layoutcampos select 15271 ,887 ,'BL_GARANTIA' ,'BL_GARANTIA' ,1 ,138 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15271);
insert into db_layoutcampos select 15272 ,887 ,'NR_DIAS_PRAZO' ,'NR_DIAS_PRAZO' ,1 ,139 ,'' ,5 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15272);
insert into db_layoutcampos select 15273 ,887 ,'DS_OBJETO' ,'DS_OBJETO' ,1 ,144 ,'' ,500 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15273);
insert into db_layoutcampos select 15274 ,887 ,'NR_CONTRATO_ORIGINAL' ,'NR_CONTRATO_ORIGINAL' ,1 ,644 ,'' ,20 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15274);
insert into db_layoutcampos select 15275 ,887 ,'BL_INICIO_DEPENDE_OI' ,'BL_INICIO_DEPENDE_OI' ,1 ,664 ,'' ,1 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15275);
insert into db_layoutcampos select 15276 ,887 ,'DS_JUSTIFICATIVA' ,'DS_JUSTIFICATIVA' ,1 ,665 ,'' ,300 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15276);
insert into db_layoutcampos select 15277 ,887 ,'CNPJ_CONSORCIO' ,'CNPJ_CONSORCIO' ,1 ,965 ,'' ,14 ,'false' ,'true' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15277);
insert into db_layoutcampos select 15278 ,887 ,'CNPJ_ORGAO_GERENCIADOR' ,'CNPJ_ORGAO_GERENCIADOR' ,1 ,979 ,'' ,14 ,'f' ,'t' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15278);
insert into db_layoutcampos select 15279 ,887 ,'BL_GERA_DESPESA' ,'BL_GERA_DESPESA' ,1 ,993 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15279);
insert into db_layoutcampos select 15280 ,887 ,'DS_OBSERVACAO' ,'DS_OBSERVACAO' ,1 ,994 ,'' ,500 ,'f' ,'t' ,'d' ,'' ,0  where not exists (select 1 from db_layoutcampos where db52_codigo  = 15280);

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}
