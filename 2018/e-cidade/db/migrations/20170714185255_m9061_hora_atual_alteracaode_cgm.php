<?php

use Classes\PostgresMigration;

class M9061HoraAtualAlteracaodeCgm extends PostgresMigration
{
    public function up()
    {
      $this->execute("CREATE OR REPLACE FUNCTION fc_cgm_altexc() RETURNS TRIGGER AS $$

                          DECLARE
                          
                              iSizeCnpjCpf   integer     default 0;
                                             
                              sCnpj          varchar(14) default '';
                              sCpf           varchar(14) default '';
                              sHoraAtual     varchar(5)  default (to_char(now(), 'HH24:MI'));
                          
                              sSqlMatriculas text        default '';
                              rMatriculas    record;
                          
                              BEGIN
                              
                              select char_length(z01_cgccpf)
                                into iSizeCnpjCpf
                                from cgm 
                               where z01_numcgm = OLD.z01_numcgm;
                              
                              if iSizeCnpjCpf > 11  then 
                                  
                                sCnpj := OLD.z01_numcgm;
                              else 
                          
                                sCpf := OLD.z01_numcgm; 
                              end if;
                          
                          
                                  IF (TG_OP = 'DELETE') THEN    
                                    
                                    INSERT 
                                      INTO cgmalt (
                                                   z05_sequencia,
                                                   z05_ufcon,
                                                   z05_uf,
                                                   z05_tipcre,
                                                   z05_telef,
                                                   z05_telcon,
                                                   z05_telcel,
                                                   z05_profis,
                                                   z05_numero,
                                                   z05_numcon,
                                                   z05_numcgm,
                                                   z05_nome,
                                                   z05_nacion,
                                                   z05_munic,
                                                   z05_muncon,
                                                   z05_login,
                                                   z05_incest,
                                                   z05_ident,
                                                   z05_estciv,
                                                   z05_ender,
                                                   z05_endcon,
                                                   z05_emailc,
                                                   z05_email,
                                                   z05_cxpostal,
                                                   z05_cxposcon,
                                                   z05_cpf,
                                                   z05_compl,
                                                   z05_comcon,
                                                   z05_cgccpf,
                                                   z05_cgc,
                                                   z05_cepcon,
                                                   z05_cep,
                                                   z05_celcon,
                                                   z05_cadast,
                                                   z05_bairro,
                                                   z05_baicon,
                                                   z05_tipo_alt,
                                                   z05_hora,
                                                   z05_login_alt,
                                                   z05_data_alt,
                                                   z05_hora_alt,
                                                   z05_ultalt,
                                                   z05_mae,
                                                   z05_pai,
                                                   z05_nomefanta,
                                                   z05_contato,
                                                   z05_sexo,
                                                   z05_nasc,
                                                   z05_fax             
                                        
                                                   ) values (
                                                                 
                                                   nextval('cgmalt_z05_sequencia_seq'),
                                                   OLD.z01_ufcon,
                                                   OLD.z01_uf,
                                                   OLD.z01_tipcre,
                                                   OLD.z01_telef,
                                                   OLD.z01_telcon,
                                                   OLD.z01_telcel,
                                                   OLD.z01_profis,
                                                   OLD.z01_numero,
                                                   OLD.z01_numcon,
                                                   OLD.z01_numcgm,
                                                   OLD.z01_nome,
                                                   OLD.z01_nacion,
                                                   OLD.z01_munic,
                                                   OLD.z01_muncon,
                                                   OLD.z01_login,
                                                   OLD.z01_incest,
                                                   OLD.z01_ident,
                                                   OLD.z01_estciv,
                                                   OLD.z01_ender,
                                                   OLD.z01_endcon,
                                                   OLD.z01_emailc,
                                                   OLD.z01_email,
                                                   OLD.z01_cxpostal,
                                                   OLD.z01_cxposcon,
                                                   sCpf,
                                                   OLD.z01_compl,
                                                   OLD.z01_comcon,
                                                   OLD.z01_cgccpf,
                                                   sCnpj,
                                                   OLD.z01_cepcon,
                                                   OLD.z01_cep,
                                                   OLD.z01_celcon,
                                                   OLD.z01_cadast,
                                                   OLD.z01_bairro,
                                                   OLD.z01_baicon,
                                                   'E',
                                                   OLD.z01_hora,
                                                   cast((select fc_getsession('DB_id_usuario')) as integer),
                                                   cast((select fc_getsession('DB_datausu')) as date),
                                                   sHoraAtual,
                                                   OLD.z01_ultalt,
                                                   OLD.z01_mae,
                                                   OLD.z01_pai,
                                                   OLD.z01_nomefanta,
                                                   OLD.z01_contato,
                                                   OLD.z01_sexo,
                                                   OLD.z01_nasc,
                                                   OLD.z01_fax
                                                   );
                                      
                                      RETURN OLD;
                                      ELSIF (TG_OP = 'UPDATE') THEN
                                                  INSERT 
                                      INTO cgmalt (
                                                   z05_sequencia,
                                                   z05_ufcon,
                                                   z05_uf,
                                                   z05_tipcre,
                                                   z05_telef,
                                                   z05_telcon,
                                                   z05_telcel,
                                                   z05_profis,
                                                   z05_numero,
                                                   z05_numcon,
                                                   z05_numcgm,
                                                   z05_nome,
                                                   z05_nacion,
                                                   z05_munic,
                                                   z05_muncon,
                                                   z05_login,
                                                   z05_incest,
                                                   z05_ident,
                                                   z05_estciv,
                                                   z05_ender,
                                                   z05_endcon,
                                                   z05_emailc,
                                                   z05_email,
                                                   z05_cxpostal,
                                                   z05_cxposcon,
                                                   z05_cpf,
                                                   z05_compl,
                                                   z05_comcon,
                                                   z05_cgccpf,
                                                   z05_cgc,
                                                   z05_cepcon,
                                                   z05_cep,
                                                   z05_celcon,
                                                   z05_cadast,
                                                   z05_bairro,
                                                   z05_baicon,
                                                   z05_tipo_alt,
                                                   z05_hora,
                                                   z05_login_alt,
                                                   z05_data_alt,
                                                   z05_hora_alt,
                                                   z05_ultalt,
                                                   z05_mae,
                                                   z05_pai,
                                                   z05_nomefanta,
                                                   z05_contato,
                                                   z05_sexo,
                                                   z05_nasc,
                                                   z05_fax             
                                        
                                                   ) values (
                                                   nextval('cgmalt_z05_sequencia_seq'),
                                                   OLD.z01_ufcon,
                                                   OLD.z01_uf,
                                                   OLD.z01_tipcre,
                                                   OLD.z01_telef,
                                                   OLD.z01_telcon,
                                                   OLD.z01_telcel,
                                                   OLD.z01_profis,
                                                   OLD.z01_numero,
                                                   OLD.z01_numcon,
                                                   OLD.z01_numcgm,
                                                   OLD.z01_nome,
                                                   OLD.z01_nacion,
                                                   OLD.z01_munic,
                                                   OLD.z01_muncon,
                                                   OLD.z01_login,
                                                   OLD.z01_incest,
                                                   OLD.z01_ident,
                                                   OLD.z01_estciv,
                                                   OLD.z01_ender,
                                                   OLD.z01_endcon,
                                                   OLD.z01_emailc,
                                                   OLD.z01_email,
                                                   OLD.z01_cxpostal,
                                                   OLD.z01_cxposcon,
                                                   sCpf,
                                                   OLD.z01_compl,
                                                   OLD.z01_comcon,
                                                   OLD.z01_cgccpf,
                                                   sCnpj,
                                                   OLD.z01_cepcon,
                                                   OLD.z01_cep,
                                                   OLD.z01_celcon,
                                                   OLD.z01_cadast,
                                                   OLD.z01_bairro,
                                                   OLD.z01_baicon,
                                                   'A',
                                                   OLD.z01_hora,
                                                   cast((select fc_getsession('DB_id_usuario')) as integer),
                                                   cast((select fc_getsession('DB_datausu')) as date),
                                                   sHoraAtual,
                                                   OLD.z01_ultalt,
                                                   OLD.z01_mae,
                                                   OLD.z01_pai,
                                                   OLD.z01_nomefanta,
                                                   OLD.z01_contato,
                                                   OLD.z01_sexo,
                                                   OLD.z01_nasc,
                                                   OLD.z01_fax
                                                   );
                                     
                          
                                        -- Verifica se CGM contém alguma matrícula no sistema em que o código do PIS esteja diferente 
                                        -- do atual cadastrado na tabela CGM, caso encontre algum registros, então é alterado o PIS 
                          
                                        sSqlMatriculas := ' select rh01_regist
                                                              from rhpessoal
                                                                   inner join rhpesdoc on rhpesdoc.rh16_regist = rhpessoal.rh01_regist
                                                             where rh01_numcgm = '||new.z01_numcgm||'
                                                               and trim(coalesce(rhpesdoc.rh16_pis,\'\')) != \''||trim(coalesce(new.z01_pis,''))||'\'';
                                        
                          
                                        for rMatriculas in execute sSqlMatriculas loop
                                      
                                          update rhpesdoc
                                             set rh16_pis = new.z01_pis
                                           where rh16_regist = rMatriculas.rh01_regist;
                          
                                        end  loop;
                          
                                        RETURN OLD;                                                     
                                      END IF;
                                  RETURN NEW; 
                              END;
                          $$ LANGUAGE plpgsql;");
    }

    public function down()
    {
      $this->execute("CREATE OR REPLACE FUNCTION fc_cgm_altexc() RETURNS TRIGGER AS $$

                          DECLARE
                          
                              iSizeCnpjCpf   integer     default 0;
                                             
                              sCnpj          varchar(14) default '';
                              sCpf           varchar(14) default '';
                          
                              sSqlMatriculas text        default '';
                              rMatriculas    record;
                          
                              BEGIN
                              
                              select char_length(z01_cgccpf)
                                into iSizeCnpjCpf
                                from cgm 
                               where z01_numcgm = OLD.z01_numcgm;
                              
                              if iSizeCnpjCpf > 11  then 
                                  
                                sCnpj := OLD.z01_numcgm;
                              else 
                          
                                sCpf := OLD.z01_numcgm; 
                              end if;
                          
                          
                                  IF (TG_OP = 'DELETE') THEN    
                                    
                                    INSERT 
                                      INTO cgmalt (
                                                   z05_sequencia,
                                                   z05_ufcon,
                                                   z05_uf,
                                                   z05_tipcre,
                                                   z05_telef,
                                                   z05_telcon,
                                                   z05_telcel,
                                                   z05_profis,
                                                   z05_numero,
                                                   z05_numcon,
                                                   z05_numcgm,
                                                   z05_nome,
                                                   z05_nacion,
                                                   z05_munic,
                                                   z05_muncon,
                                                   z05_login,
                                                   z05_incest,
                                                   z05_ident,
                                                   z05_estciv,
                                                   z05_ender,
                                                   z05_endcon,
                                                   z05_emailc,
                                                   z05_email,
                                                   z05_cxpostal,
                                                   z05_cxposcon,
                                                   z05_cpf,
                                                   z05_compl,
                                                   z05_comcon,
                                                   z05_cgccpf,
                                                   z05_cgc,
                                                   z05_cepcon,
                                                   z05_cep,
                                                   z05_celcon,
                                                   z05_cadast,
                                                   z05_bairro,
                                                   z05_baicon,
                                                   z05_tipo_alt,
                                                   z05_hora,
                                                   z05_login_alt,
                                                   z05_data_alt,
                                                   z05_hora_alt,
                                                   z05_ultalt,
                                                   z05_mae,
                                                   z05_pai,
                                                   z05_nomefanta,
                                                   z05_contato,
                                                   z05_sexo,
                                                   z05_nasc,
                                                   z05_fax             
                                        
                                                   ) values (
                                                                 
                                                   nextval('cgmalt_z05_sequencia_seq'),
                                                   OLD.z01_ufcon,
                                                   OLD.z01_uf,
                                                   OLD.z01_tipcre,
                                                   OLD.z01_telef,
                                                   OLD.z01_telcon,
                                                   OLD.z01_telcel,
                                                   OLD.z01_profis,
                                                   OLD.z01_numero,
                                                   OLD.z01_numcon,
                                                   OLD.z01_numcgm,
                                                   OLD.z01_nome,
                                                   OLD.z01_nacion,
                                                   OLD.z01_munic,
                                                   OLD.z01_muncon,
                                                   OLD.z01_login,
                                                   OLD.z01_incest,
                                                   OLD.z01_ident,
                                                   OLD.z01_estciv,
                                                   OLD.z01_ender,
                                                   OLD.z01_endcon,
                                                   OLD.z01_emailc,
                                                   OLD.z01_email,
                                                   OLD.z01_cxpostal,
                                                   OLD.z01_cxposcon,
                                                   sCpf,
                                                   OLD.z01_compl,
                                                   OLD.z01_comcon,
                                                   OLD.z01_cgccpf,
                                                   sCnpj,
                                                   OLD.z01_cepcon,
                                                   OLD.z01_cep,
                                                   OLD.z01_celcon,
                                                   OLD.z01_cadast,
                                                   OLD.z01_bairro,
                                                   OLD.z01_baicon,
                                                   'E',
                                                   OLD.z01_hora,
                                                   cast((select fc_getsession('DB_id_usuario')) as integer),
                                                   cast((select fc_getsession('DB_datausu')) as date),
                                                   cast((select fc_getsession('DB_hora')) as text),
                                                   OLD.z01_ultalt,
                                                   OLD.z01_mae,
                                                   OLD.z01_pai,
                                                   OLD.z01_nomefanta,
                                                   OLD.z01_contato,
                                                   OLD.z01_sexo,
                                                   OLD.z01_nasc,
                                                   OLD.z01_fax
                                                   );
                                      
                                      RETURN OLD;
                                      ELSIF (TG_OP = 'UPDATE') THEN
                                                  INSERT 
                                      INTO cgmalt (
                                                   z05_sequencia,
                                                   z05_ufcon,
                                                   z05_uf,
                                                   z05_tipcre,
                                                   z05_telef,
                                                   z05_telcon,
                                                   z05_telcel,
                                                   z05_profis,
                                                   z05_numero,
                                                   z05_numcon,
                                                   z05_numcgm,
                                                   z05_nome,
                                                   z05_nacion,
                                                   z05_munic,
                                                   z05_muncon,
                                                   z05_login,
                                                   z05_incest,
                                                   z05_ident,
                                                   z05_estciv,
                                                   z05_ender,
                                                   z05_endcon,
                                                   z05_emailc,
                                                   z05_email,
                                                   z05_cxpostal,
                                                   z05_cxposcon,
                                                   z05_cpf,
                                                   z05_compl,
                                                   z05_comcon,
                                                   z05_cgccpf,
                                                   z05_cgc,
                                                   z05_cepcon,
                                                   z05_cep,
                                                   z05_celcon,
                                                   z05_cadast,
                                                   z05_bairro,
                                                   z05_baicon,
                                                   z05_tipo_alt,
                                                   z05_hora,
                                                   z05_login_alt,
                                                   z05_data_alt,
                                                   z05_hora_alt,
                                                   z05_ultalt,
                                                   z05_mae,
                                                   z05_pai,
                                                   z05_nomefanta,
                                                   z05_contato,
                                                   z05_sexo,
                                                   z05_nasc,
                                                   z05_fax             
                                        
                                                   ) values (
                                                   nextval('cgmalt_z05_sequencia_seq'),
                                                   OLD.z01_ufcon,
                                                   OLD.z01_uf,
                                                   OLD.z01_tipcre,
                                                   OLD.z01_telef,
                                                   OLD.z01_telcon,
                                                   OLD.z01_telcel,
                                                   OLD.z01_profis,
                                                   OLD.z01_numero,
                                                   OLD.z01_numcon,
                                                   OLD.z01_numcgm,
                                                   OLD.z01_nome,
                                                   OLD.z01_nacion,
                                                   OLD.z01_munic,
                                                   OLD.z01_muncon,
                                                   OLD.z01_login,
                                                   OLD.z01_incest,
                                                   OLD.z01_ident,
                                                   OLD.z01_estciv,
                                                   OLD.z01_ender,
                                                   OLD.z01_endcon,
                                                   OLD.z01_emailc,
                                                   OLD.z01_email,
                                                   OLD.z01_cxpostal,
                                                   OLD.z01_cxposcon,
                                                   sCpf,
                                                   OLD.z01_compl,
                                                   OLD.z01_comcon,
                                                   OLD.z01_cgccpf,
                                                   sCnpj,
                                                   OLD.z01_cepcon,
                                                   OLD.z01_cep,
                                                   OLD.z01_celcon,
                                                   OLD.z01_cadast,
                                                   OLD.z01_bairro,
                                                   OLD.z01_baicon,
                                                   'A',
                                                   OLD.z01_hora,
                                                   cast((select fc_getsession('DB_id_usuario')) as integer),
                                                   cast((select fc_getsession('DB_datausu')) as date),
                                                   cast((select fc_getsession('DB_hora')) as text),
                                                   OLD.z01_ultalt,
                                                   OLD.z01_mae,
                                                   OLD.z01_pai,
                                                   OLD.z01_nomefanta,
                                                   OLD.z01_contato,
                                                   OLD.z01_sexo,
                                                   OLD.z01_nasc,
                                                   OLD.z01_fax
                                                   );
                                     
                          
                                        -- Verifica se CGM contém alguma matrícula no sistema em que o código do PIS esteja diferente 
                                        -- do atual cadastrado na tabela CGM, caso encontre algum registros, então é alterado o PIS 
                          
                                        sSqlMatriculas := ' select rh01_regist
                                                              from rhpessoal
                                                                   inner join rhpesdoc on rhpesdoc.rh16_regist = rhpessoal.rh01_regist
                                                             where rh01_numcgm = '||new.z01_numcgm||'
                                                               and trim(coalesce(rhpesdoc.rh16_pis,\'\')) != \''||trim(coalesce(new.z01_pis,''))||'\'';
                                        
                          
                                        for rMatriculas in execute sSqlMatriculas loop
                                      
                                          update rhpesdoc
                                             set rh16_pis = new.z01_pis
                                           where rh16_regist = rMatriculas.rh01_regist;
                          
                                        end  loop;
                          
                                        RETURN OLD;                                                     
                                      END IF;
                                  RETURN NEW; 
                              END;
                          $$ LANGUAGE plpgsql;");
    }

}
