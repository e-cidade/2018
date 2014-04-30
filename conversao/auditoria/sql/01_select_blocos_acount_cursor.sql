select id_acount,
       codarq,
       codmod,
       id_modulo,
       id_item,
       trim(nomemod) as esquema,
       trim(nomearq) as tabela,
       case actipo
         when 'A' then 'U'
         when 'E' then 'D'
         else 'I'
       end                    as operacao,
       'db_auditoria_'||
         trim(to_char(anousu, '0000')) ||
         trim(to_char(mesusu, '00')) as particao,
       fc_xid_current()       as transacao,
       datahr as datahora_sessao,
       datahr as datahora_servidor,
       anousu || mesusu as anomes,
       login,
       id_usuario,
       pkey_nome_campo,
       pkey_valor,
       mudancas_nome_campo,
       mudancas_valor_antigo,
       mudancas_valor_novo,
       codsequen,
       instit,
       anousu || '-' || mesusu ||'-01 00:00:00.000000' as datahora_ini,
       anousu || '-' || mesusu ||'-'||
         fc_ultimodiames(anousu::INTEGER, mesusu::INTEGER)::TEXT || ' 23:59:59.999999' as datahora_fim
  from (select x.id_acount,
               b.codarq,
               b.codmod,
               dp.id_modulo,
               dp.id_item,
               c.nomemod,
               a.nomearq,
               x.actipo,
               to_timestamp(min(y.datahr)) as datahr,
               extract(year  from to_timestamp(min(y.datahr))) as anousu,
               extract(month from to_timestamp(min(y.datahr))) as mesusu,
               z.login,
               y.id_usuario,
               pkey_nome_campo,
               pkey_valor,
               array_accum((trim(w.nomecam))) as mudancas_nome_campo,
               array_accum( nullif((case x.actipo when 'E' then y.contatu else y.contant end), '') ) as mudancas_valor_antigo,
               array_accum( nullif((case x.actipo when 'E' then y.contant else y.contatu end), '') ) as mudancas_valor_novo,
               l.codsequen,
               coalesce( (select min(i.id_instit)
                            from db_userinst i
                           where i.id_usuario = y.id_usuario),
                         (select codigo
                            from db_config
                           where prefeitura is true
                            limit 1) ) as instit
          from (select id_acount,
                       actipo,
                       array_accum(distinct trim(nomecam)) as pkey_nome_campo,
                       array_accum(distinct campotext)     as pkey_valor
                  from only db_acountkey{$sufixo} a
                       join db_syscampo b on b.codcam = a.id_codcam
                 where a.id_acount between {$acount_ini} and {$acount_fim}
                 group by id_acount, actipo) as x
               join only db_acount{$sufixo} y  on y.id_acount   =  x.id_acount
                                      and trim(contant) <> trim(contatu)
               join db_syscampo   w on w.codcam     = y.codcam
               join db_sysarquivo a on a.codarq     = y.codarq
               join db_sysarqmod  b on b.codarq     = y.codarq
               join db_sysmodulo  c on c.codmod     = b.codmod
               join db_usuarios   z on z.id_usuario = y.id_usuario
               left join only db_acountacesso{$sufixo} l on l.id_acount = y.id_acount
               left join db_auditoria_migracao_depara_codarq_codmod_id_modulo dp  on dp.codarq = b.codarq
                                                                                 and dp.codmod = b.codmod
         group by 1, 2, 3, 4, 5, 6, 7, 8, 12, 13, 14, 15, 19, 20) as acount;

