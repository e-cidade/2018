<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

$campospessoal = "rhpessoal.rh01_regist,
                  rhpessoal.rh01_numcgm,
                  cgmrh.z01_nome,
                  rhpessoal.rh01_nasc,
                  rhpessoal.rh01_sexo,
                  rhpessoal.rh01_estciv,
                  rechumano.ed20_i_codigoinep,
                  rechumano.ed20_i_escolaridade,
                  rechumano.ed20_i_raca,
                  cgmrh.z01_ident,
                  cgmrh.z01_cgccpf,
                  rhpesdoc.rh16_titele,
                  rhpesdoc.rh16_zonael,
                  rhpesdoc.rh16_secaoe,
                  rhpesdoc.rh16_ctps_n,
                  rhpesdoc.rh16_ctps_s,
                  rhpesdoc.rh16_ctps_uf,
                  rhpesdoc.rh16_pis,
                  rhpesdoc.rh16_carth_n,
                  rhpesdoc.r16_carth_cat,
                  rhpesdoc.rh16_carth_val,
                  rechumano.ed20_c_nis,
                  rechumano.ed20_i_censoorgemiss,
                  rechumano.ed20_i_censoufident,
                  rechumano.ed20_d_dataident,
                  rechumano.ed20_c_identcompl,
                  rechumano.ed20_i_certidaotipo,
                  rechumano.ed20_c_certidaonum,
                  rechumano.ed20_c_certidaofolha,
                  rechumano.ed20_c_certidaolivro,
                  rechumano.ed20_c_certidaodata,
                  rechumano.ed20_c_certidaocart,
                  rechumano.ed20_i_censoufcert,
                  rechumano.ed20_c_passaporte,
                  rechumano.ed20_i_nacionalidade,
                  rechumano.ed20_i_pais,
                  rechumano.ed20_i_censoufnat,
                  rechumano.ed20_i_censomunicnat,
                  rechumano.ed20_c_efetividade,
                  rechumano.ed20_i_censocartorio,
                  cgmrh.z01_ender,
                  cgmrh.z01_numero,
                  cgmrh.z01_compl,
                  cgmrh.z01_bairro,
                  cgmrh.z01_cep,
                  rechumano.ed20_i_censoufender,
                  rechumano.ed20_i_censomunicender,
                  rechumano.ed20_i_codigo,
                  regimerh.rh30_codreg,
                  regimerh.rh30_descr,
                  rechumano.ed20_i_zonaresidencia
                 ";
$camposcgm = "cgmcgm.z01_nome,
              cgmcgm.z01_nasc as rh01_nasc,
              cgmcgm.z01_sexo as rh01_sexo,
              cgmcgm.z01_estciv as rh01_estciv,
              rechumano.ed20_i_codigoinep,
              rechumano.ed20_i_escolaridade,
              rechumano.ed20_i_raca,
              cgmcgm.z01_ident,
              cgmcgm.z01_cgccpf,
              '' as rh16_titele,
              '' as rh16_zonael,
              '' as rh16_secaoe,
              cgmdoc.z02_c_ctpsnum as rh16_ctps_n,
              cgmdoc.z02_c_ctpsserie as rh16_ctps_s,
              cgmdoc.z02_c_ctpsuf as rh16_ctps_uf,
              cgmdoc.z02_i_pis as rh16_pis,
              cgmcgm.z01_cnh as rh16_carth_n,
              cgmcgm.z01_categoria as r16_carth_cat,
              cgmcgm.z01_dtvencimento as rh16_carth_val,
              rechumano.ed20_c_nis,
              rechumano.ed20_i_censoorgemiss,
              rechumano.ed20_i_censoufident,
              rechumano.ed20_d_dataident,
              rechumano.ed20_c_identcompl,
              rechumano.ed20_i_certidaotipo,
              rechumano.ed20_c_certidaonum,
              rechumano.ed20_c_certidaofolha,
              rechumano.ed20_c_certidaolivro,
              rechumano.ed20_c_certidaodata,
              rechumano.ed20_c_certidaocart,
              rechumano.ed20_i_censoufcert,
              rechumano.ed20_c_passaporte,
              rechumano.ed20_i_nacionalidade,
              rechumano.ed20_i_pais,
              rechumano.ed20_i_censoufnat,
              rechumano.ed20_i_censomunicnat,
              rechumano.ed20_c_efetividade,
              rechumano.ed20_i_censocartorio,
              cgmcgm.z01_ender,
              cgmcgm.z01_numero,
              cgmcgm.z01_compl,
              cgmcgm.z01_bairro,
              cgmcgm.z01_cep,
              rechumano.ed20_i_censoufender,
              rechumano.ed20_i_censomunicender,
              rechumano.ed20_i_codigo,
              regimecgm.rh30_codreg,
              regimecgm.rh30_descr,
              rechumano.ed20_i_zonaresidencia
              ";
$camposrechumano = "case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_nome
                     else cgmcgm.z01_nome
                    end as z01_nome,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_numcgm
                     else cgmcgm.z01_numcgm
                    end as z01_numcgm,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_nasc
                     else cgmcgm.z01_nasc
                    end as rh01_nasc,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_sexo
                     else cgmcgm.z01_sexo
                    end as rh01_sexo,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_estciv
                     else cgmcgm.z01_estciv
                    end as rh01_estciv,
                    rechumano.ed20_i_codigoinep,
                    rechumano.ed20_i_escolaridade,
                    rechumano.ed20_i_raca,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_ident
                     else cgmcgm.z01_ident
                    end as z01_ident,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_cgccpf
                     else cgmcgm.z01_cgccpf
                    end as z01_cgccpf,
                    case when ed20_i_tiposervidor = 1
                     then rhpesdoc.rh16_titele
                     else ''
                    end as rh16_titele,
                    case when ed20_i_tiposervidor = 1
                     then rhpesdoc.rh16_zonael
                     else ''
                    end as rh16_zonael,
                    case when ed20_i_tiposervidor = 1
                     then rhpesdoc.rh16_secaoe
                     else ''
                    end as rh16_secaoe,
                    case when ed20_i_tiposervidor = 1
                     then rhfuncao.rh37_descr
                     else ''
                    end as rh37_descr,
                    case when ed20_i_tiposervidor = 1
                     then rhlota.r70_descr
                     else ''
                    end as r70_descr,
                    case when ed20_i_tiposervidor = 1
                     then rhpessoal.rh01_admiss
                     else null
                    end as rh01_admiss,
                    case when ed20_i_tiposervidor = 1
                     then rhpessoal.rh01_tipadm
                     else null
                    end as rh01_tipadm,
                    case when ed20_i_tiposervidor = 1
                     then rhpesdoc.rh16_ctps_n::char
                     else cgmdoc.z02_c_ctpsnum
                    end as rh16_ctps_n,
                    case when ed20_i_tiposervidor = 1
                     then rhpessoal.rh01_natura
                     else cgmdoc.z02_c_naturalidade
                    end as rh01_natura,
                    case when ed20_i_tiposervidor = 1
                     then rhpesdoc.rh16_ctps_s::char
                     else cgmdoc.z02_c_ctpsserie
                    end as rh16_ctps_s,
                    case when ed20_i_tiposervidor = 1
                     then rhpesdoc.rh16_reserv
                     else null
                    end as rh16_reserv,
                    case when ed20_i_tiposervidor = 1
                     then rhpesdoc.rh16_catres
                     else null
                    end as rh16_catres,
                    case when ed20_i_tiposervidor = 1
                     then rhpesdoc.rh16_ctps_d
                     else null
                    end as rh16_ctps_d,
                    case when ed20_i_tiposervidor = 1
                     then rhpesdoc.rh16_ctps_uf
                     else cgmdoc.z02_c_ctpsuf
                    end as rh16_ctps_uf,
                    case when ed20_i_tiposervidor = 1
                     then case when trim(rhpesdoc.rh16_pis) = '' then  null else rhpesdoc.rh16_pis::bigint end
                     else cgmdoc.z02_i_pis
                    end as rh16_pis,
                    case when ed20_i_tiposervidor = 1
                     then rhpesdoc.rh16_carth_n::char
                     else cgmcgm.z01_cnh
                    end as rh16_carth_n,
                    case when ed20_i_tiposervidor = 1
                     then rhpesdoc.r16_carth_cat
                     else cgmcgm.z01_categoria
                    end as r16_carth_cat,
                    case when ed20_i_tiposervidor = 1
                     then rhpesdoc.rh16_carth_val
                     else cgmcgm.z01_dtvencimento
                    end as rh16_carth_val,
                    rechumano.ed20_c_nis,
                    rechumano.ed20_i_censoorgemiss,
                    rechumano.ed20_i_censoufident,
                    rechumano.ed20_d_dataident,
                    rechumano.ed20_c_identcompl,
                    censoufident.ed260_c_sigla,
                    censoorgemissrg.ed132_c_descr,
                    rechumano.ed20_i_certidaotipo,
                    rechumano.ed20_c_certidaonum,
                    rechumano.ed20_c_certidaofolha,
                    rechumano.ed20_c_certidaolivro,
                    rechumano.ed20_c_certidaodata,
                    rechumano.ed20_c_certidaocart,
                    rechumano.ed20_i_censoufcert,
                    rechumano.ed20_c_passaporte,
                    rechumano.ed20_i_nacionalidade,
                    rechumano.ed20_i_pais,
                    rechumano.ed20_i_censoufnat,
                    rechumano.ed20_i_censomunicnat,
                    rechumano.ed20_c_efetividade,
                    rechumano.ed20_i_censocartorio,
                    rechumano.ed20_i_zonaresidencia,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_ender
                     else cgmcgm.z01_ender
                    end as z01_ender,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_numero
                     else cgmcgm.z01_numero
                    end as z01_numero,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_compl
                     else cgmcgm.z01_compl
                    end as z01_compl,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_bairro
                     else cgmcgm.z01_bairro
                    end as z01_bairro,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_cep
                     else cgmcgm.z01_cep
                    end as z01_cep,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_uf
                     else cgmcgm.z01_uf
                    end as z01_uf,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_estciv
                     else cgmcgm.z01_estciv
                    end as z01_estciv,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_sexo
                     else cgmcgm.z01_sexo
                    end as z01_sexo,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_munic
                     else cgmcgm.z01_munic
                    end as z01_munic,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_telef
                     else cgmcgm.z01_telef
                    end as z01_telef,
                    case when ed20_i_tiposervidor = 1
                     then cgmrh.z01_telcel
                     else cgmcgm.z01_telcel
                    end as z01_telcel,
                    rechumano.ed20_i_censoufender,
                    rechumano.ed20_i_censomunicender,
                    rechumano.ed20_i_codigo,
                    rechumano.ed20_i_tiposervidor,
                    rechumanopessoal.ed284_i_rhpessoal,
                    rechumanocgm.ed285_i_cgm,
                    rhpessoal.rh01_regist,
                    rhpessoal.rh01_numcgm,
                    case when ed20_i_tiposervidor = 1
                     then regimerh.rh30_codreg
                     else regimecgm.rh30_codreg
                    end as rh30_codreg,
                    case when ed20_i_tiposervidor = 1
                     then regimerh.rh30_descr
                     else regimecgm.rh30_descr
                    end as rh30_descr,
                    case when ed20_i_tiposervidor = 1
                     then rechumanopessoal.ed284_i_rhpessoal
                     else rechumanocgm.ed285_i_cgm
                    end as identificacao
                    ";
?>