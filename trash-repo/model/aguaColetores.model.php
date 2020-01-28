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

/**
 * Classe para gerar arquivos de exportação para os coletores
 * @author dbseller
 *
 */
class aguaColetores {
    
  public function sqlDadosMatriculas ($iRota) {
    
    $sqlDadosMatriculas = "
                          select coalesce(x06_codrota, 999999) as x07_codrota,
                                 x01_matric,
                          			 z01_nome, 
                          			 x01_codrua,
                           			 j88_sigla, 
                          			 j14_nome,
                          			 x01_numero,
                          			 x01_letra, 
                          			 case
                          			   when x32_codcorresp is not null then
                          				   x02_complemento
                          				 else
                          					 x11_complemento
                          			 end as x99_complemento,
                          			 
                                 case
                          				 when x32_codcorresp is not null then
                          					 bairro2.j13_descr
                          				 else
                          					 bairro.j13_descr
                          			 end as x99_bairro, 
                          			 x01_zona, 
                          			 x01_quadra, 
                                 x01_qtdeconomia,
                                 nextval('numpref_k03_numpre_seq') as numpre, 
                                 to_char(fc_agua_areaconstr(x01_matric), '999990.00') as x99_areaconstr
                                 
                          
                          from aguabase
                          
                          left  join aguarotarua                    on x07_codrua           = x01_codrua
                          left  join aguarota                       on x06_codrota          = x07_codrota
                          inner join cgm                            on z01_numcgm           = x01_numcgm
                          left  join aguabasecorresp                on x32_matric           = x01_matric
                          left  join aguacorresp                    on x02_codcorresp       = x32_codcorresp
                          left  join ruas                           on ruas.j14_codigo      = x01_codrua
                          left  join aguaconstr                     on x11_matric           = x01_matric
                          left  join bairro                         on bairro.j13_codi      = x01_codbairro
                          left  join bairro   as bairro2            on bairro2.j13_codi     = x02_codbairro
                          left  join ruastipo                       on ruastipo.j88_codigo  = ruas.j14_tipo
                          
                          where x01_rota in ($iRota)  
                            and fc_agua_hidrometroinstalado(x01_matric) is true
                          order by x07_codrota, x07_ordem, x01_codrua, x01_letra, x01_numero
                          ";
    
    return $sqlDadosMatriculas;
  } 
  
  public function sqlCategoria ($matricula) {
    
    $sqlCategoria = "
      							 select j31_descr from aguaconstr
      								inner join aguaconstrcar on x12_codconstr = x11_codconstr
      								inner join caracter on j31_codigo = x12_codigo and j31_grupo = 80
      							  where x11_matric = $matricula ";
    
    return $sqlCategoria;
  }
  
  public function sqlHidrometroAtivo ($matricula) {
    
    $sqlhidro = "
      select x04_codhidrometro from aguahidromatric 
      left join aguahidrotroca on x28_codhidrometro = x04_codhidrometro
      where x04_matric = {$matricula}
      and   x28_codigo is null";
    
    return $sqlhidro;
  }
  
  public function sqlArrecad($sArreMatric, $x18_arretipo, $ano_venc, $mes_venc, $parcela) {
    
    $sqlArrecad = "
    select * from (
        select arrecad.k00_receit, 
               arrecad.k00_numpre, 
               arrecad.k00_numpar, 
               arrecad.k00_numtot,
               arrecad.k00_tipo, 
               arrecad.k00_dtvenc, 
               round(arrecad.k00_valor, 2) as k00_valor, 
               tabrec.k02_descr 
          from {$sArreMatric} 
               inner join arrecad  on arrecad.k00_numpre = arrematric.k00_numpre
               inner join tabrec   on tabrec.k02_codigo  = arrecad.k00_receit
         where arrecad.k00_tipo   = {$x18_arretipo}
           and arrecad.k00_numpar = {$parcela}
           and extract(year from arrecad.k00_dtvenc) = {$ano_venc}
        union
          select min(arrecad.k00_receit) as k00_receit, 
                 arrecad.k00_numpre, 
                 arrecad.k00_numpar, 
                 arrecad.k00_numtot,
                 arrecad.k00_tipo, 
                 arrecad.k00_dtvenc, 
                 round(sum(coalesce(arrecad.k00_valor, 0)), 2) as k00_valor, 
                 'PARCELAM DIV TX' as k02_descr 
            from {$sArreMatric}  
                 inner join arrecad       on arrecad.k00_numpre       = arrematric.k00_numpre
                 inner join arretipo      on arretipo.k00_tipo        = arrecad.k00_tipo
           where arretipo.k03_tipo                      = 6
             and extract(year from arrecad.k00_dtvenc)  = {$ano_venc}
             and extract(month from arrecad.k00_dtvenc) = {$mes_venc}
             and not exists (select arrenaoagrupa.k00_numpre 
                               from arrenaoagrupa 
                              where arrenaoagrupa.k00_numpre = arrecad.k00_numpre)
        group by arrecad.k00_numpre, 
                 arrecad.k00_numpar, 
                 arrecad.k00_numtot, 
                 arrecad.k00_tipo, 
                 arrecad.k00_dtvenc 
        union 
        select arrecad.k00_receit, 
               arrecad.k00_numpre, 
               arrecad.k00_numpar, 
               arrecad.k00_numtot,
               arrecad.k00_tipo, 
               arrecad.k00_dtvenc, 
               round(arrecad.k00_valor, 2) as k00_valor, 
               tabrec.k02_descr 
          from {$sArreMatric}  
               inner join arrecad       on arrecad.k00_numpre       = arrematric.k00_numpre
               inner join arretipo      on arretipo.k00_tipo        = arrecad.k00_tipo
               inner join tabrec        on tabrec.k02_codigo        = arrecad.k00_receit
         where (arrecad.k00_tipo                      <> {$x18_arretipo} 
           and  arretipo.k03_tipo                     <> 6)
           and extract(year from arrecad.k00_dtvenc)  = {$ano_venc}
           and extract(month from arrecad.k00_dtvenc) = {$mes_venc}
           and not exists (select arrenaoagrupa.k00_numpre
                             from arrenaoagrupa
                            where arrenaoagrupa.k00_numpre = arrecad.k00_numpre) ) as x
      order by k00_numpre, 
               k00_receit";
    return $sqlArrecad;
  }
  
  public function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:aguabase";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
  }
  
  
  
}

?>