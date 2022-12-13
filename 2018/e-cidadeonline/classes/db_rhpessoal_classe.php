<?php

/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhpessoal
class cl_rhpessoal {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $rh01_regist = 0;
   var $rh01_numcgm = 0;
   var $rh01_funcao = 0;
   var $rh01_lotac = 0;
   var $rh01_admiss_dia = null;
   var $rh01_admiss_mes = null;
   var $rh01_admiss_ano = null;
   var $rh01_admiss = null;
   var $rh01_nasc_dia = null;
   var $rh01_nasc_mes = null;
   var $rh01_nasc_ano = null;
   var $rh01_nasc = null;
   var $rh01_nacion = 0;
   var $rh01_anoche = 0;
   var $rh01_instru = 0;
   var $rh01_sexo = null;
   var $rh01_estciv = 0;
   var $rh01_tipadm = 0;
   var $rh01_natura = null;
   var $rh01_raca = 0;
   var $rh01_clas1 = null;
   var $rh01_clas2_dia = null;
   var $rh01_clas2_mes = null;
   var $rh01_clas2_ano = null;
   var $rh01_clas2 = null;
   var $rh01_trienio_dia = null;
   var $rh01_trienio_mes = null;
   var $rh01_trienio_ano = null;
   var $rh01_trienio = null;
   var $rh01_progres_dia = null;
   var $rh01_progres_mes = null;
   var $rh01_progres_ano = null;
   var $rh01_progres = null;
   var $rh01_instit = 0;
   var $rh01_vale = null;
   var $rh01_ponto = 0;
   var $rh01_depirf = 0;
   var $rh01_depsf = 0;
   var $rh01_observacao = null;
   var $rh01_rhsindicato = 0;
   var $rh01_reajusteparidade = 0;  
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 rh01_regist = int4 = Matrícula
                 rh01_numcgm = int4 = Numcgm
                 rh01_funcao = int4 = Cargo
                 rh01_lotac = int4 = Lotação
                 rh01_admiss = date = Admissão
                 rh01_nasc = date = Nascimento
                 rh01_nacion = int4 = Nacionalidade
                 rh01_anoche = int4 = Ano de Chegada
                 rh01_instru = int4 = Grau de Instrução
                 rh01_sexo = char(1) = Sexo
                 rh01_estciv = int4 = Estado Civil
                 rh01_tipadm = int4 = Tipo de Admissão
                 rh01_natura = varchar(40) = naturalidade
                 rh01_raca = int4 = Raça/Cor
                 rh01_clas1 = varchar(5) = Opção Livre
                 rh01_clas2 = date = Opção Livre
                 rh01_trienio = date = Triênio
                 rh01_progres = date = Progressão
                 rh01_instit = int4 = codigo da instituicao
                 rh01_vale = varchar(1) = Adiantamento
                 rh01_ponto = int4 = Nro ponto
                 rh01_depirf = int4 = Dependentes IRRF
                 rh01_depsf = int4 = Dependentes Sal.Família
                 rh01_observacao = text = Observações
                 rh01_rhsindicato = int4 = Sindicato
                 rh01_reajusteparidade = int4 = Reajuste de Paridade
                 ";
   //funcao construtor da classe
   function cl_rhpessoal() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpessoal");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->rh01_regist = ($this->rh01_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_regist"]:$this->rh01_regist);
       $this->rh01_numcgm = ($this->rh01_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_numcgm"]:$this->rh01_numcgm);
       $this->rh01_funcao = ($this->rh01_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_funcao"]:$this->rh01_funcao);
       $this->rh01_lotac = ($this->rh01_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_lotac"]:$this->rh01_lotac);
       if($this->rh01_admiss == ""){
         $this->rh01_admiss_dia = ($this->rh01_admiss_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_admiss_dia"]:$this->rh01_admiss_dia);
         $this->rh01_admiss_mes = ($this->rh01_admiss_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_admiss_mes"]:$this->rh01_admiss_mes);
         $this->rh01_admiss_ano = ($this->rh01_admiss_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_admiss_ano"]:$this->rh01_admiss_ano);
         if($this->rh01_admiss_dia != ""){
            $this->rh01_admiss = $this->rh01_admiss_ano."-".$this->rh01_admiss_mes."-".$this->rh01_admiss_dia;
         }
       }
       if($this->rh01_nasc == ""){
         $this->rh01_nasc_dia = ($this->rh01_nasc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_nasc_dia"]:$this->rh01_nasc_dia);
         $this->rh01_nasc_mes = ($this->rh01_nasc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_nasc_mes"]:$this->rh01_nasc_mes);
         $this->rh01_nasc_ano = ($this->rh01_nasc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_nasc_ano"]:$this->rh01_nasc_ano);
         if($this->rh01_nasc_dia != ""){
            $this->rh01_nasc = $this->rh01_nasc_ano."-".$this->rh01_nasc_mes."-".$this->rh01_nasc_dia;
         }
       }
       $this->rh01_nacion = ($this->rh01_nacion == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_nacion"]:$this->rh01_nacion);
       $this->rh01_anoche = ($this->rh01_anoche == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_anoche"]:$this->rh01_anoche);
       $this->rh01_instru = ($this->rh01_instru == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_instru"]:$this->rh01_instru);
       $this->rh01_sexo = ($this->rh01_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_sexo"]:$this->rh01_sexo);
       $this->rh01_estciv = ($this->rh01_estciv == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_estciv"]:$this->rh01_estciv);
       $this->rh01_tipadm = ($this->rh01_tipadm == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_tipadm"]:$this->rh01_tipadm);
       $this->rh01_natura = ($this->rh01_natura == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_natura"]:$this->rh01_natura);
       $this->rh01_raca = ($this->rh01_raca == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_raca"]:$this->rh01_raca);
       $this->rh01_clas1 = ($this->rh01_clas1 == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_clas1"]:$this->rh01_clas1);
       if($this->rh01_clas2 == ""){
         $this->rh01_clas2_dia = ($this->rh01_clas2_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_clas2_dia"]:$this->rh01_clas2_dia);
         $this->rh01_clas2_mes = ($this->rh01_clas2_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_clas2_mes"]:$this->rh01_clas2_mes);
         $this->rh01_clas2_ano = ($this->rh01_clas2_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_clas2_ano"]:$this->rh01_clas2_ano);
         if($this->rh01_clas2_dia != ""){
            $this->rh01_clas2 = $this->rh01_clas2_ano."-".$this->rh01_clas2_mes."-".$this->rh01_clas2_dia;
         }
       }
       if($this->rh01_trienio == ""){
         $this->rh01_trienio_dia = ($this->rh01_trienio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_trienio_dia"]:$this->rh01_trienio_dia);
         $this->rh01_trienio_mes = ($this->rh01_trienio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_trienio_mes"]:$this->rh01_trienio_mes);
         $this->rh01_trienio_ano = ($this->rh01_trienio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_trienio_ano"]:$this->rh01_trienio_ano);
         if($this->rh01_trienio_dia != ""){
            $this->rh01_trienio = $this->rh01_trienio_ano."-".$this->rh01_trienio_mes."-".$this->rh01_trienio_dia;
         }
       }
       if($this->rh01_progres == ""){
         $this->rh01_progres_dia = ($this->rh01_progres_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_progres_dia"]:$this->rh01_progres_dia);
         $this->rh01_progres_mes = ($this->rh01_progres_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_progres_mes"]:$this->rh01_progres_mes);
         $this->rh01_progres_ano = ($this->rh01_progres_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_progres_ano"]:$this->rh01_progres_ano);
         if($this->rh01_progres_dia != ""){
            $this->rh01_progres = $this->rh01_progres_ano."-".$this->rh01_progres_mes."-".$this->rh01_progres_dia;
         }
       }
       $this->rh01_instit = ($this->rh01_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_instit"]:$this->rh01_instit);
       $this->rh01_vale = ($this->rh01_vale == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_vale"]:$this->rh01_vale);
       $this->rh01_ponto = ($this->rh01_ponto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_ponto"]:$this->rh01_ponto);
       $this->rh01_depirf = ($this->rh01_depirf == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_depirf"]:$this->rh01_depirf);
       $this->rh01_depsf = ($this->rh01_depsf == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_depsf"]:$this->rh01_depsf);
       $this->rh01_observacao = ($this->rh01_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_observacao"]:$this->rh01_observacao);
       $this->rh01_rhsindicato = ($this->rh01_rhsindicato == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_rhsindicato"]:$this->rh01_rhsindicato);
       $this->rh01_reajusteparidade = ($this->rh01_reajusteparidade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_reajusteparidade"]:$this->rh01_reajusteparidade);
     }else{
       $this->rh01_regist = ($this->rh01_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh01_regist"]:$this->rh01_regist);
     }
   }
   // funcao para inclusao
   function incluir ($rh01_regist){
      $this->atualizacampos();
     if($this->rh01_numcgm == null ){
       $this->erro_sql = " Campo Numcgm não Informado.";
       $this->erro_campo = "rh01_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh01_funcao == null ){
       $this->rh01_funcao = "null";
     }
     if($this->rh01_lotac == null ){
       $this->rh01_lotac = "0";
     }
     if($this->rh01_admiss == null ){
       $this->erro_sql = " Campo Admissão não Informado.";
       $this->erro_campo = "rh01_admiss_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh01_nasc == null ){
       $this->erro_sql = " Campo Nascimento não Informado.";
       $this->erro_campo = "rh01_nasc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh01_nacion == null ){
       $this->erro_sql = " Campo Nacionalidade não Informado.";
       $this->erro_campo = "rh01_nacion";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh01_anoche == null ){
       $this->rh01_anoche = "0";
     }
     if($this->rh01_instru == null ){
       $this->erro_sql = " Campo Grau de Instrução não Informado.";
       $this->erro_campo = "rh01_instru";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh01_sexo == null ){
       $this->erro_sql = " Campo Sexo não Informado.";
       $this->erro_campo = "rh01_sexo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh01_estciv == null ){
       $this->erro_sql = " Campo Estado Civil não Informado.";
       $this->erro_campo = "rh01_estciv";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh01_tipadm == null ){
       $this->erro_sql = " Campo Tipo de Admissão não Informado.";
       $this->erro_campo = "rh01_tipadm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh01_raca == null ){
       $this->erro_sql = " Campo Raça/Cor não Informado.";
       $this->erro_campo = "rh01_raca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh01_clas2 == null ){
       $this->rh01_clas2 = "null";
     }
     if($this->rh01_trienio == null ){
       $this->rh01_trienio = "null";
     }
     if($this->rh01_progres == null ){
       $this->rh01_progres = "null";
     }
     if($this->rh01_instit == null ){
       $this->erro_sql = " Campo codigo da instituicao não Informado.";
       $this->erro_campo = "rh01_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh01_vale == null ){
       $this->erro_sql = " Campo Adiantamento não Informado.";
       $this->erro_campo = "rh01_vale";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh01_ponto == null ){
       $this->rh01_ponto = "0";
     }
     if($this->rh01_depirf == null ){
       $this->rh01_depirf = "0";
     }
     if($this->rh01_depsf == null ){
       $this->rh01_depsf = "0";
     }
     if($this->rh01_rhsindicato == null ){
       $this->rh01_rhsindicato = "0";
     }
     if($this->rh01_reajusteparidade == null ){ 
       $this->erro_sql = " Campo Reajuste de Paridade não informado.";
       $this->erro_campo = "rh01_reajusteparidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }  
       $this->rh01_regist = $rh01_regist;
     if(($this->rh01_regist == null) || ($this->rh01_regist == "") ){
       $this->erro_sql = " Campo rh01_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoal(
                                       rh01_regist
                                      ,rh01_numcgm
                                      ,rh01_funcao
                                      ,rh01_lotac
                                      ,rh01_admiss
                                      ,rh01_nasc
                                      ,rh01_nacion
                                      ,rh01_anoche
                                      ,rh01_instru
                                      ,rh01_sexo
                                      ,rh01_estciv
                                      ,rh01_tipadm
                                      ,rh01_natura
                                      ,rh01_raca
                                      ,rh01_clas1
                                      ,rh01_clas2
                                      ,rh01_trienio
                                      ,rh01_progres
                                      ,rh01_instit
                                      ,rh01_vale
                                      ,rh01_ponto
                                      ,rh01_depirf
                                      ,rh01_depsf
                                      ,rh01_observacao
                                      ,rh01_rhsindicato
                                      ,rh01_reajusteparidade
                       )
                values (
                                $this->rh01_regist
                               ,$this->rh01_numcgm
                               ,$this->rh01_funcao
                               ,$this->rh01_lotac
                               ,".($this->rh01_admiss == "null" || $this->rh01_admiss == ""?"null":"'".$this->rh01_admiss."'")."
                               ,".($this->rh01_nasc == "null" || $this->rh01_nasc == ""?"null":"'".$this->rh01_nasc."'")."
                               ,$this->rh01_nacion
                               ,$this->rh01_anoche
                               ,$this->rh01_instru
                               ,'$this->rh01_sexo'
                               ,$this->rh01_estciv
                               ,$this->rh01_tipadm
                               ,'$this->rh01_natura'
                               ,$this->rh01_raca
                               ,'$this->rh01_clas1'
                               ,".($this->rh01_clas2 == "null" || $this->rh01_clas2 == ""?"null":"'".$this->rh01_clas2."'")."
                               ,".($this->rh01_trienio == "null" || $this->rh01_trienio == ""?"null":"'".$this->rh01_trienio."'")."
                               ,".($this->rh01_progres == "null" || $this->rh01_progres == ""?"null":"'".$this->rh01_progres."'")."
                               ,$this->rh01_instit
                               ,'$this->rh01_vale'
                               ,$this->rh01_ponto
                               ,$this->rh01_depirf
                               ,$this->rh01_depsf
                               ,'$this->rh01_observacao'
                               ,$this->rh01_rhsindicato
                               ,$this->rh01_reajusteparidade
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de funcionários. ($this->rh01_regist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de funcionários. já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de funcionários. ($this->rh01_regist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh01_regist;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh01_regist));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6964,'$this->rh01_regist','I')");
       $resac = db_query("insert into db_acount values($acount,1153,6964,'','".AddSlashes(pg_result($resaco,0,'rh01_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6965,'','".AddSlashes(pg_result($resaco,0,'rh01_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6979,'','".AddSlashes(pg_result($resaco,0,'rh01_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6980,'','".AddSlashes(pg_result($resaco,0,'rh01_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6966,'','".AddSlashes(pg_result($resaco,0,'rh01_admiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6967,'','".AddSlashes(pg_result($resaco,0,'rh01_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6968,'','".AddSlashes(pg_result($resaco,0,'rh01_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6969,'','".AddSlashes(pg_result($resaco,0,'rh01_anoche'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6970,'','".AddSlashes(pg_result($resaco,0,'rh01_instru'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6971,'','".AddSlashes(pg_result($resaco,0,'rh01_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6972,'','".AddSlashes(pg_result($resaco,0,'rh01_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6973,'','".AddSlashes(pg_result($resaco,0,'rh01_tipadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6974,'','".AddSlashes(pg_result($resaco,0,'rh01_natura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6976,'','".AddSlashes(pg_result($resaco,0,'rh01_raca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6977,'','".AddSlashes(pg_result($resaco,0,'rh01_clas1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,6978,'','".AddSlashes(pg_result($resaco,0,'rh01_clas2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,7635,'','".AddSlashes(pg_result($resaco,0,'rh01_trienio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,7636,'','".AddSlashes(pg_result($resaco,0,'rh01_progres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,7471,'','".AddSlashes(pg_result($resaco,0,'rh01_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,7825,'','".AddSlashes(pg_result($resaco,0,'rh01_vale'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,7848,'','".AddSlashes(pg_result($resaco,0,'rh01_ponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,10036,'','".AddSlashes(pg_result($resaco,0,'rh01_depirf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,10035,'','".AddSlashes(pg_result($resaco,0,'rh01_depsf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,17368,'','".AddSlashes(pg_result($resaco,0,'rh01_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,19596,'','".AddSlashes(pg_result($resaco,0,'rh01_rhsindicato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1153,20685,'','".AddSlashes(pg_result($resaco,0,'rh01_reajusteparidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($rh01_regist=null) {
      $this->atualizacampos();
     $sql = " update rhpessoal set ";
     $virgula = "";
     if(trim($this->rh01_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_regist"])){
       $sql  .= $virgula." rh01_regist = $this->rh01_regist ";
       $virgula = ",";
       if(trim($this->rh01_regist) == null ){
         $this->erro_sql = " Campo Matrícula não Informado.";
         $this->erro_campo = "rh01_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh01_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_numcgm"])){
       $sql  .= $virgula." rh01_numcgm = $this->rh01_numcgm ";
       $virgula = ",";
       if(trim($this->rh01_numcgm) == null ){
         $this->erro_sql = " Campo Numcgm não Informado.";
         $this->erro_campo = "rh01_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh01_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_funcao"])){
        if(trim($this->rh01_funcao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh01_funcao"])){
           $this->rh01_funcao = "0" ;
        }
       $sql  .= $virgula." rh01_funcao = $this->rh01_funcao ";
       $virgula = ",";
     }
     if(trim($this->rh01_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_lotac"])){
        if(trim($this->rh01_lotac)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh01_lotac"])){
           $this->rh01_lotac = "0" ;
        }
       $sql  .= $virgula." rh01_lotac = $this->rh01_lotac ";
       $virgula = ",";
     }
     if(trim($this->rh01_admiss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_admiss_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh01_admiss_dia"] !="") ){
       $sql  .= $virgula." rh01_admiss = '$this->rh01_admiss' ";
       $virgula = ",";
       if(trim($this->rh01_admiss) == null ){
         $this->erro_sql = " Campo Admissão não Informado.";
         $this->erro_campo = "rh01_admiss_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_admiss_dia"])){
         $sql  .= $virgula." rh01_admiss = null ";
         $virgula = ",";
         if(trim($this->rh01_admiss) == null ){
           $this->erro_sql = " Campo Admissão não Informado.";
           $this->erro_campo = "rh01_admiss_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh01_nasc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_nasc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh01_nasc_dia"] !="") ){
       $sql  .= $virgula." rh01_nasc = '$this->rh01_nasc' ";
       $virgula = ",";
       if(trim($this->rh01_nasc) == null ){
         $this->erro_sql = " Campo Nascimento não Informado.";
         $this->erro_campo = "rh01_nasc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_nasc_dia"])){
         $sql  .= $virgula." rh01_nasc = null ";
         $virgula = ",";
         if(trim($this->rh01_nasc) == null ){
           $this->erro_sql = " Campo Nascimento não Informado.";
           $this->erro_campo = "rh01_nasc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh01_nacion)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_nacion"])){
       $sql  .= $virgula." rh01_nacion = $this->rh01_nacion ";
       $virgula = ",";
       if(trim($this->rh01_nacion) == null ){
         $this->erro_sql = " Campo Nacionalidade não Informado.";
         $this->erro_campo = "rh01_nacion";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh01_anoche)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_anoche"])){
        if(trim($this->rh01_anoche)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh01_anoche"])){
           $this->rh01_anoche = "0" ;
        }
       $sql  .= $virgula." rh01_anoche = $this->rh01_anoche ";
       $virgula = ",";
     }
     if(trim($this->rh01_instru)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_instru"])){
       $sql  .= $virgula." rh01_instru = $this->rh01_instru ";
       $virgula = ",";
       if(trim($this->rh01_instru) == null ){
         $this->erro_sql = " Campo Grau de Instrução não Informado.";
         $this->erro_campo = "rh01_instru";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh01_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_sexo"])){
       $sql  .= $virgula." rh01_sexo = '$this->rh01_sexo' ";
       $virgula = ",";
       if(trim($this->rh01_sexo) == null ){
         $this->erro_sql = " Campo Sexo não Informado.";
         $this->erro_campo = "rh01_sexo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh01_estciv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_estciv"])){
       $sql  .= $virgula." rh01_estciv = $this->rh01_estciv ";
       $virgula = ",";
       if(trim($this->rh01_estciv) == null ){
         $this->erro_sql = " Campo Estado Civil não Informado.";
         $this->erro_campo = "rh01_estciv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh01_tipadm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_tipadm"])){
       $sql  .= $virgula." rh01_tipadm = $this->rh01_tipadm ";
       $virgula = ",";
       if(trim($this->rh01_tipadm) == null ){
         $this->erro_sql = " Campo Tipo de Admissão não Informado.";
         $this->erro_campo = "rh01_tipadm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh01_natura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_natura"])){
       $sql  .= $virgula." rh01_natura = '$this->rh01_natura' ";
       $virgula = ",";
     }
     if(trim($this->rh01_raca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_raca"])){
       $sql  .= $virgula." rh01_raca = $this->rh01_raca ";
       $virgula = ",";
       if(trim($this->rh01_raca) == null ){
         $this->erro_sql = " Campo Raça/Cor não Informado.";
         $this->erro_campo = "rh01_raca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh01_clas1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_clas1"])){
       $sql  .= $virgula." rh01_clas1 = '$this->rh01_clas1' ";
       $virgula = ",";
     }
     if(trim($this->rh01_clas2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_clas2_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh01_clas2_dia"] !="") ){
       $sql  .= $virgula." rh01_clas2 = '$this->rh01_clas2' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_clas2_dia"])){
         $sql  .= $virgula." rh01_clas2 = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh01_trienio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_trienio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh01_trienio_dia"] !="") ){
       $sql  .= $virgula." rh01_trienio = '$this->rh01_trienio' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_trienio_dia"])){
         $sql  .= $virgula." rh01_trienio = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh01_progres)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_progres_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh01_progres_dia"] !="") ){
       $sql  .= $virgula." rh01_progres = '$this->rh01_progres' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_progres_dia"])){
         $sql  .= $virgula." rh01_progres = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh01_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_instit"])){
       $sql  .= $virgula." rh01_instit = $this->rh01_instit ";
       $virgula = ",";
       if(trim($this->rh01_instit) == null ){
         $this->erro_sql = " Campo codigo da instituicao não Informado.";
         $this->erro_campo = "rh01_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh01_vale)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_vale"])){
       $sql  .= $virgula." rh01_vale = '$this->rh01_vale' ";
       $virgula = ",";
       if(trim($this->rh01_vale) == null ){
         $this->erro_sql = " Campo Adiantamento nao Informado.";
         $this->erro_campo = "rh01_vale";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh01_ponto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_ponto"])){
        if(trim($this->rh01_ponto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh01_ponto"])){
           $this->rh01_ponto = "0" ;
        }
       $sql  .= $virgula." rh01_ponto = $this->rh01_ponto ";
       $virgula = ",";
     }
     if(trim($this->rh01_depirf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_depirf"])){
        if(trim($this->rh01_depirf)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh01_depirf"])){
           $this->rh01_depirf = "0" ;
        }
       $sql  .= $virgula." rh01_depirf = $this->rh01_depirf ";
       $virgula = ",";
     }
     if(trim($this->rh01_depsf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_depsf"])){
        if(trim($this->rh01_depsf)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh01_depsf"])){
           $this->rh01_depsf = "0" ;
        }
       $sql  .= $virgula." rh01_depsf = $this->rh01_depsf ";
       $virgula = ",";
     }
     if(trim($this->rh01_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_observacao"])){
       $sql  .= $virgula." rh01_observacao = '$this->rh01_observacao' ";
       $virgula = ",";
     }
     if(trim($this->rh01_rhsindicato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_rhsindicato"])){
        if(trim($this->rh01_rhsindicato)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh01_rhsindicato"])){
           $this->rh01_rhsindicato = "0" ;
        }
       $sql  .= $virgula." rh01_rhsindicato = $this->rh01_rhsindicato ";
       $virgula = ",";
     }
     if(trim($this->rh01_reajusteparidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh01_reajusteparidade"])){     
       $sql  .= $virgula." rh01_reajusteparidade = '$this->rh01_reajusteparidade' ";
       $virgula = ",";            
       if(trim($this->rh01_reajusteparidade) == null ){
         $this->erro_sql = " Campo Reajuste de Paridade não informado.";
         $this->erro_campo = "rh01_reajusteparidade";
         $this->erro_banco = "";  
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;            
       }                          
     }
     $sql .= " where ";
     if($rh01_regist!=null){
       $sql .= " rh01_regist = $this->rh01_regist";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh01_regist));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6964,'$this->rh01_regist','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_regist"]) || $this->rh01_regist != "")
           $resac = db_query("insert into db_acount values($acount,1153,6964,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_regist'))."','$this->rh01_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_numcgm"]) || $this->rh01_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,1153,6965,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_numcgm'))."','$this->rh01_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_funcao"]) || $this->rh01_funcao != "")
           $resac = db_query("insert into db_acount values($acount,1153,6979,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_funcao'))."','$this->rh01_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_lotac"]) || $this->rh01_lotac != "")
           $resac = db_query("insert into db_acount values($acount,1153,6980,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_lotac'))."','$this->rh01_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_admiss"]) || $this->rh01_admiss != "")
           $resac = db_query("insert into db_acount values($acount,1153,6966,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_admiss'))."','$this->rh01_admiss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_nasc"]) || $this->rh01_nasc != "")
           $resac = db_query("insert into db_acount values($acount,1153,6967,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_nasc'))."','$this->rh01_nasc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_nacion"]) || $this->rh01_nacion != "")
           $resac = db_query("insert into db_acount values($acount,1153,6968,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_nacion'))."','$this->rh01_nacion',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_anoche"]) || $this->rh01_anoche != "")
           $resac = db_query("insert into db_acount values($acount,1153,6969,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_anoche'))."','$this->rh01_anoche',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_instru"]) || $this->rh01_instru != "")
           $resac = db_query("insert into db_acount values($acount,1153,6970,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_instru'))."','$this->rh01_instru',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_sexo"]) || $this->rh01_sexo != "")
           $resac = db_query("insert into db_acount values($acount,1153,6971,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_sexo'))."','$this->rh01_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_estciv"]) || $this->rh01_estciv != "")
           $resac = db_query("insert into db_acount values($acount,1153,6972,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_estciv'))."','$this->rh01_estciv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_tipadm"]) || $this->rh01_tipadm != "")
           $resac = db_query("insert into db_acount values($acount,1153,6973,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_tipadm'))."','$this->rh01_tipadm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_natura"]) || $this->rh01_natura != "")
           $resac = db_query("insert into db_acount values($acount,1153,6974,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_natura'))."','$this->rh01_natura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_raca"]) || $this->rh01_raca != "")
           $resac = db_query("insert into db_acount values($acount,1153,6976,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_raca'))."','$this->rh01_raca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_clas1"]) || $this->rh01_clas1 != "")
           $resac = db_query("insert into db_acount values($acount,1153,6977,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_clas1'))."','$this->rh01_clas1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_clas2"]) || $this->rh01_clas2 != "")
           $resac = db_query("insert into db_acount values($acount,1153,6978,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_clas2'))."','$this->rh01_clas2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_trienio"]) || $this->rh01_trienio != "")
           $resac = db_query("insert into db_acount values($acount,1153,7635,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_trienio'))."','$this->rh01_trienio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_progres"]) || $this->rh01_progres != "")
           $resac = db_query("insert into db_acount values($acount,1153,7636,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_progres'))."','$this->rh01_progres',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_instit"]) || $this->rh01_instit != "")
           $resac = db_query("insert into db_acount values($acount,1153,7471,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_instit'))."','$this->rh01_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_vale"]) || $this->rh01_vale != "")
           $resac = db_query("insert into db_acount values($acount,1153,7825,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_vale'))."','$this->rh01_vale',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_ponto"]) || $this->rh01_ponto != "")
           $resac = db_query("insert into db_acount values($acount,1153,7848,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_ponto'))."','$this->rh01_ponto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_depirf"]) || $this->rh01_depirf != "")
           $resac = db_query("insert into db_acount values($acount,1153,10036,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_depirf'))."','$this->rh01_depirf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_depsf"]) || $this->rh01_depsf != "")
           $resac = db_query("insert into db_acount values($acount,1153,10035,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_depsf'))."','$this->rh01_depsf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_observacao"]) || $this->rh01_observacao != "")
           $resac = db_query("insert into db_acount values($acount,1153,17368,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_observacao'))."','$this->rh01_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_rhsindicato"]) || $this->rh01_rhsindicato != "")
           $resac = db_query("insert into db_acount values($acount,1153,19596,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_rhsindicato'))."','$this->rh01_rhsindicato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh01_reajusteparidade"]) || $this->rh01_reajusteparidade != "")
           $resac = db_query("insert into db_acount values($acount,1153,20685,'".AddSlashes(pg_result($resaco,$conresaco,'rh01_reajusteparidade'))."','$this->rh01_reajusteparidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");

       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de funcionários. nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh01_regist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de funcionários. nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh01_regist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh01_regist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($rh01_regist=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh01_regist));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6964,'$rh01_regist','E')");
         $resac = db_query("insert into db_acount values($acount,1153,6964,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6965,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6979,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6980,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6966,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_admiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6967,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6968,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6969,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_anoche'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6970,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_instru'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6971,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6972,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6973,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_tipadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6974,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_natura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6976,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_raca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6977,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_clas1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,6978,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_clas2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,7635,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_trienio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,7636,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_progres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,7471,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,7825,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_vale'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,7848,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_ponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,10036,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_depirf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,10035,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_depsf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,17368,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,19596,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_rhsindicato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1153,20685,'','".AddSlashes(pg_result($resaco,$iresaco,'rh01_reajusteparidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
     }
     $sql = " delete from rhpessoal
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh01_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh01_regist = $rh01_regist ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de funcionários. nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh01_regist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de funcionários. nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh01_regist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh01_regist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rhpessoal ";
     $sql .= "      left  join rhpessoalmov on  rh02_anousu = ".db_anofolha()."
                                           and  rh02_mesusu = ".db_mesfolha()."
                                           and  rh02_regist = rh01_regist
                                           and  rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left  join rhlota       on  rhlota.r70_codigo = rhpessoalmov.rh02_lota
                                           and  rhlota.r70_instit = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join db_config    on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left  join rhfuncao     on  rhfuncao.rh37_funcao = rhpessoalmov.rh02_funcao
                                           and  rhfuncao.rh37_instit = rhpessoalmov.rh02_instit  ";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql .= "      left  join rhpesrescisao on rh02_seqpes = rh05_seqpes";
     $sql .= "      left  join rhsindicato   on rh01_rhsindicato = rh116_sequencial";
     $sql .= "      inner join rhreajusteparidade  on  rhreajusteparidade.rh148_sequencial = rhpessoal.rh01_reajusteparidade";
     $sql2 = "";
     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql
   function sql_query_file ( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rhpessoal ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_cgm ( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rhpessoal ";
     $sql .= "      inner join rhpessoalmov   on  rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                                           and  rh02_mesusu = ".db_mesfolha()."
                                           and  rh02_anousu = ".db_anofolha()."
                                           and  rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo = rhpessoalmov.rh02_lota
                                           and  rhlota.r70_instit = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

   function sql_query_cgm_mov_periodo( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere="",$ano,$mes){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }

     $sql .= " from rhpessoal                                                                              ";
     $sql .= "      inner join rhpessoalmov   on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist          ";
     $sql .= "                               and rhpessoalmov.rh02_mesusu = $mes                           ";
     $sql .= "                               and rhpessoalmov.rh02_anousu = $ano                           ";
     $sql .= "                               and rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join rhlota         on rhlota.r70_codigo        = rhpessoalmov.rh02_lota         ";
     $sql .= "                               and rhlota.r70_instit        = rhpessoalmov.rh02_instit       ";
     $sql .= "      inner join cgm            on cgm.z01_numcgm           = rhpessoal.rh01_numcgm          ";


     $sql2 = "";

     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }

     return $sql;

  }

   function sql_query_rescisao ( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rhpessoal ";
     $sql .= "      inner join rhpessoalmov   on  rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                                           and  rh02_mesusu = ".db_mesfolha()."
                                           and  rh02_anousu = ".db_anofolha()."
                                           and  rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join cgm            on  cgm.z01_numcgm = rhpessoal.rh01_numcgm ";
     $sql .= "      left  join rhpesrescisao  on  rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_pesdoc ( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rhpessoal ";
     $sql .= "      inner join rhpesdoc  on rhpesdoc.rh16_regist =  rhpessoal.rh01_regist";
     $sql .= "      left  join rhpessoalmov on  rh02_anousu = ".db_anofolha()."
                                            and rh02_mesusu = ".db_mesfolha()."
                                            and rh02_regist = rh01_regist
                                            and  rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left  join rhpesrescisao on  rh02_seqpes = rh05_seqpes";
     $sql2 = "";
     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_ferias ( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rhpessoal ";
     $sql .= "      inner join rhpessoalmov   on  rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                                           and  rh02_mesusu = ".db_mesfolha()."
                                           and  rh02_anousu = ".db_anofolha()."
                                           and  rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo = rhpessoalmov.rh02_lota
                                      and  rhlota.r70_instit = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join cgm            on  cgm.z01_numcgm = rhpessoal.rh01_numcgm ";
     $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoalmov.rh02_funcao
                                        and  rhfuncao.rh37_instit = rhpessoalmov.rh02_instit ";
     $sql .= "      left  join rhregime       on  rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
                                             and  rhregime.rh30_instit = rhpessoalmov.rh02_instit ";
     $sql .= "      left  join rhpesrescisao  on  rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql .= "      left  join cadferia       on  cadferia.r30_anousu = rhpessoalmov.rh02_anousu
                                             and  cadferia.r30_mesusu = rhpessoalmov.rh02_mesusu
                                             and  cadferia.r30_regist = rhpessoalmov.rh02_regist ";
     $sql .= "      left  join rhiperegist    on  rhiperegist.rh62_regist = rhpessoal.rh01_regist ";
     $sql .= "      left  join rhipe          on  rhipe.rh14_sequencia    = rhiperegist.rh62_sequencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_pesquisa ( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere="",$ano,$mes){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rhpessoal ";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist
                                           and  rh02_mesusu = ".db_mesfolha()."
                                           and  rh02_anousu = ".db_anofolha()."
                                           and  rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join rhlota        on  rhlota.r70_codigo         = rhpessoalmov.rh02_lota
                                            and  rhlota.r70_instit         = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join cgm           on  cgm.z01_numcgm            = rhpessoal.rh01_numcgm ";
     $sql .= "      left  join rhfuncao      on  rhfuncao.rh37_funcao      = rhpessoalmov.rh02_funcao
                                            and  rhfuncao.rh37_instit      = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join rhregime      on  rhregime.rh30_codreg      = rhpessoalmov.rh02_codreg
                                            and  rhregime.rh30_instit      = rhpessoalmov.rh02_instit ";
     $sql .= "      left  join rhpescargo    on  rhpescargo.rh20_seqpes    = rhpessoalmov.rh02_seqpes
                                            and  rhpescargo.rh20_instit    = rhpessoalmov.rh02_instit ";
     $sql .= "      left  join rhcargo       on  rhcargo.rh04_codigo       = rhpescargo.rh20_cargo
                                            and  rhcargo.rh04_instit       = rhpescargo.rh20_instit ";
     $sql .= "      left  join rhpespadrao   on  rhpespadrao.rh03_seqpes   = rhpessoalmov.rh02_seqpes ";
     $sql .= "      left  join padroes       on  r02_anousu                = rh02_anousu
                                            and  r02_mesusu                = rh02_mesusu
                                            and  r02_regime                = rh30_regime
                                            and  r02_codigo                = rh03_padrao";
     $sql .= "      left  join rhpesrescisao on  rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql .= "      left  join tpcontra      on  tpcontra.h13_codigo       = rhpessoalmov.rh02_tpcont ";
     $sql .= "
                    left  outer join (
                                      select distinct r33_codtab,
                                                      r33_nome
                                      from inssirf
                                      where     r33_anousu = $ano
                                            and r33_mesusu = $mes
                                            and r33_instit = ".db_getsession("DB_instit")."
                                     ) as x on r33_codtab = rhpessoalmov.rh02_tbprev+2
             ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_afasta ( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rhpessoal ";
     $sql .= "      inner join rhpessoalmov   on  rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                                           and  rh02_mesusu = ".db_mesfolha()."
                                           and  rh02_anousu = ".db_anofolha()."
                                           and  rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join rhlota         on  rhlota.r70_codigo = rhpessoalmov.rh02_lota
                                            and  rhlota.r70_instit         = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join cgm            on  cgm.z01_numcgm = rhpessoal.rh01_numcgm ";
     $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoalmov.rh02_funcao
                                        and  rhfuncao.rh37_instit = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join rhregime       on  rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
                                             and  rhregime.rh30_instit = rhpessoalmov.rh02_instit ";
     $sql .= "      left  join rhpesrescisao  on  rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql .= "      left  join cadferia       on  cadferia.r30_anousu = rhpessoalmov.rh02_anousu
                                             and  cadferia.r30_mesusu = rhpessoalmov.rh02_mesusu
                                             and  cadferia.r30_regist = rhpessoalmov.rh02_regist ";
     $sql .= "      left  join afasta         on  afasta.r45_anousu = rhpessoalmov.rh02_anousu
                                             and  afasta.r45_mesusu = rhpessoalmov.rh02_mesusu
                                             and  afasta.r45_regist = rhpessoalmov.rh02_regist ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_cgmmov ( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rhpessoal ";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                                            and  rhpessoalmov.rh02_anousu = ".db_anofolha()."
                                            and  rhpessoalmov.rh02_mesusu = ".db_mesfolha()."
                                            and  rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")." ";
                                            
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo = rhpessoalmov.rh02_lota
                                      and  rhlota.r70_instit = rhpessoalmov.rh02_instit  ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm ";
     $sql .= "      inner join rhregime on rh30_codreg = rh02_codreg ";
     
     $sql2 = "";

     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_relPREVID ( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere="",$ano=null,$mes=null,$arr_dados=null){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     if($ano == null || trim($ano) == ""){
       $ano = db_anofolha();
     }
     if($mes == null || trim($mes) == ""){
       $mes = db_mesfolha();
     }

     $tipo   = $arr_dados[0];
     $rubricbase = $arr_dados[1];
     $rubricdesc = $arr_dados[2];
     $siglas = $arr_dados[3];
     $siglac = "";
     if(isset($arr_dados[4])){
       $siglac = $arr_dados[4];
     }

     $arquivos = $this->retorna_arquivo($siglas);
     $arquivoc = $this->retorna_arquivo($siglac);

     $sql .= " from rhpessoal ";
     $sql .= "      inner join cgm           on cgm.z01_numcgm            = rhpessoal.rh01_numcgm ";
     $sql .= "      inner join rhpessoalmov  on rhpessoalmov.rh02_anousu  = ".$ano."
                                            and rhpessoalmov.rh02_mesusu  = ".$mes."
                                            and rhpessoalmov.rh02_regist  = rh01_regist
                                            and rhpessoalmov.rh02_instit  = ".db_getsession("DB_instit")." ";
     $sql .= "      left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql .= "      left  join rhregime      on rhregime.rh30_codreg      = rhpessoalmov.rh02_codreg
                                            and rhregime.rh30_instit      = rhpessoalmov.rh02_instit ";
     $sql .= "      left outer join (
                                     select ".$siglas."_regist as regist, ".$siglas."_valor, ".$siglas."_quant
                                     from ".$arquivos."
                                     where ".$siglas."_anousu = ".$ano." and
                                           ".$siglas."_mesusu = ".$mes." and
                                           ".$siglas."_rubric = '".$rubricbase."' ";
     if($siglas != "r60" && $siglas != "r61"){
        $sql .= " and ".$siglas."_instit = ".db_getsession("DB_instit")." ";
     }
     $sql .= "                       ) as prev on prev.regist = rhpessoal.rh01_regist ";
     $sql .= "      left outer join (
                                     select ".$siglas."_regist as regist, ".$siglas."_valor, ".$siglas."_quant
                                     from ".$arquivos."
                                     where ".$siglas."_anousu = ".$ano." and
                                           ".$siglas."_mesusu = ".$mes." and
                                           ".$siglas."_rubric = '".$rubricdesc."' ";
     if($siglas != "r60" && $siglas != "r61"){
        $sql .= " and ".$siglas."_instit = ".db_getsession("DB_instit")." ";
     }
     $sql .= "                       ) as descon on descon.regist = rhpessoal.rh01_regist ";
     if($tipo == "s"){
       $sql .= "      left outer join (
                                       select ".$siglac."_regist as regist, ".$siglac."_valor, ".$siglac."_quant
                                       from ".$arquivoc."
                                       where ".$siglac."_anousu = ".$ano." and
                                             ".$siglac."_mesusu = ".$mes." and
                                             ".$siglac."_rubric = '".$rubricbase."' ";
       if($siglas != "r60" && $siglas != "r61"){
          $sql .= " and ".$siglas."_instit = ".db_getsession("DB_instit")." ";
       }
       $sql .= "                       ) as prevc on prevc.regist = rhpessoal.rh01_regist ";
       $sql .= "      left outer join (
                                       select ".$siglac."_regist as regist, ".$siglac."_valor, ".$siglac."_quant
                                       from ".$arquivoc."
                                       where ".$siglac."_anousu = ".$ano." and
                                             ".$siglac."_mesusu = ".$mes." and
                                             ".$siglac."_rubric = '".$rubricdesc."' ";
       if($siglas != "r60" && $siglas != "r61"){
          $sql .= " and ".$siglas."_instit = ".db_getsession("DB_instit")." ";
       }
       $sql .= "                      ) as desconc on desconc.regist = rhpessoal.rh01_regist ";
     }
     $sql2 = "";
     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function retorna_arquivo($sigla){
    $arquivo = "";
    if($sigla == 'r14'){
      $arquivo = ' gerfsal';
    }elseif($sigla == 'r20'){
      $arquivo = ' gerfres';
    }elseif($sigla == 'r35'){
      $arquivo = ' gerfs13';
    }elseif($sigla == 'r22'){
      $arquivo = ' gerfadi';
    }elseif($sigla == 'r48'){
      $arquivo = ' gerfcom';
    }elseif($sigla == 'r53'){
      $arquivo = ' gerffx';
    }elseif($sigla == 'r31'){
      $arquivo = ' gerffer';
    }elseif($sigla == 'r47'){
      $arquivo = ' pontocom';
    }elseif($sigla == 'r34'){
      $arquivo = ' pontof13';
    }elseif($sigla == 'r21'){
      $arquivo = ' pontofa';
    }elseif($sigla == 'r29'){
      $arquivo = ' pontofe';
    }elseif($sigla == 'r19'){
      $arquivo = ' pontofr';
    }elseif($sigla == 'r10'){
      $arquivo = ' pontofs';
    }elseif($sigla == 'r90'){
      $arquivo = ' pontofx';
    }elseif($sigla == 'r60'){
      $arquivo = ' previden';
    }elseif($sigla == 'r61'){
      $arquivo = ' ajusteir';
    }
    return $arquivo;
  }
   function sql_query_lotafuncres ( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rhpessoal ";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist and rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join rhlota        on  rhlota.r70_codigo         = rhpessoalmov.rh02_lota and r70_instit = ".db_getsession("DB_instit")."  ";
     $sql .= "      inner join cgm           on  cgm.z01_numcgm            = rhpessoal.rh01_numcgm ";
     $sql .= "      left  join rhfuncao      on  rhfuncao.rh37_funcao      = rhpessoalmov.rh02_funcao and rh37_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join rhregime      on  rhregime.rh30_codreg      = rhpessoalmov.rh02_codreg
                                            and  rhregime.rh30_instit      = rhpessoalmov.rh02_instit ";
     $sql .= "      left  join rhpesrescisao on  rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql .= "      left  join rhpespadrao   on  rhpespadrao.rh03_seqpes  = rhpessoalmov.rh02_seqpes ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_cgmmovpad ( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rhpessoal ";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist and rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join rhlota        on  rhlota.r70_codigo         = rhpessoalmov.rh02_lota and r70_instit = ".db_getsession("DB_instit")."  ";
     $sql .= "      inner join cgm           on  cgm.z01_numcgm           = rhpessoal.rh01_numcgm ";
     $sql .= "      inner join rhpespadrao   on  rhpespadrao.rh03_seqpes  = rhpessoalmov.rh02_seqpes ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_cargo ( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rhpessoal ";
     $sql .= "      left  join rhpessoalmov  on  rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                                            and  rhpessoalmov.rh02_anousu = ".db_anofolha()."
                                            and  rhpessoalmov.rh02_mesusu = ".db_mesfolha()."
                                            and  rhpessoalmov.rh02_instit = ".db_getsession("DB_instit");
     $sql .= "      left join rhfuncao  on  rhfuncao.rh37_funcao          = rhpessoalmov.rh02_funcao
                                            and  rhfuncao.rh37_instit     = rhpessoalmov.rh02_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_func_rhpessoal( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere="", $sQuery = null){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rhpessoal";
     $sql .= "      left  join rhpessoalmov  on  rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                                            and  rhpessoalmov.rh02_anousu = ".db_anofolha()."
                                           and rhpessoalmov.rh02_mesusu = ".db_mesfolha();
     $sql .= "      left  join rhlota       on  rhlota.r70_codigo = rhpessoalmov.rh02_lota
                                           and  rhlota.r70_instit = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join cgm          on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left  join rhregime     on rh30_codreg = rh02_codreg ";
     $sql .= "      left  join rhfuncao     on  rhfuncao.rh37_funcao = rhpessoalmov.rh02_funcao
                                           and  rhfuncao.rh37_instit = rhpessoalmov.rh02_instit  ";
     $sql .= "      left  join rhpesrescisao on  rh02_seqpes = rh05_seqpes";

     switch ($sQuery) {
      // Parâmetro da manutenção de períodos aquisitivos
      // Só traz períodos que podem ser alterados
      case 1:
        $sql .= " inner join rhferias on (rhferias.rh109_regist = rhpessoal.rh01_regist and rhferias.rh109_diasdireito > 0)";
        break;
      // Parâmentro da inclusão de período de gozo
      // Só traz servidores que possuem periodo a gozar
      case 2:
        $sql .= " inner join rhferias on (rh109_regist = rh01_regist                                     ";
        $sql .= " and (rh109_diasdireito - ( select coalesce(sum(rh110_dias + rh110_diasabono ),0)       ";
        $sql .= "                                 from rhferiasperiodo                                   ";
        $sql .= "                                where rh110_rhferias = rh109_sequencial)::integer) > 0) ";
      break;
      // Parâmetro da exclusão de período de gozo
      // Só traz servidores que possuem periodos de gozo que podem ser excluidos
      case 3:
        require_once('model/pessoal/ferias/PeriodoGozoFerias.model.php');
        $sql .= " inner join rhferias on (rh109_regist = rh01_regist  ";
        $sql .= "        and exists(select 1 from rhferiasperiodo where rh110_rhferias = rh109_sequencial and rh110_situacao = " . PeriodoGozoFerias::SITUACAO_AGENDADO . ")";
        $sql .= "        )";
      break;

      /**
       * Retorna somente os servidores que tem 1 ou mais periodos aquisitivos sem ferias cadastradas
       */
      case 4:

        $sql .= " inner join rhferias on (rh109_regist = rh01_regist                                       ";
        $sql .= " and not exists ( select 1 from rhferiasperiodo where rh109_sequencial = rh110_rhferias )) ";

      break;
      default:
        // Por padrão, nada aqui
      break;
     }

     $sql2 = "";
     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   /**
    * Sql base para os relatórios da folha de pagamento
    *
    * @param  string $sTabela      - tabela de cálculo do ponto escolhido ex.: gerfsal
    * @param  string $sSigla       - sigla da tabela escolhida            ex.: r14
    * @param  string $iAno         - ano competencia
    * @param  string $iMes         - mes competencia
    * @param  string $iInstituicao - instituição do relatorio
    * @param  string $sWhere       - Adicional where do sql
    * @param  string $sOrderBy     - Condição de Ordem do sql
    */
   function sql_query_baseRelatorios($sTabela, $sSigla, $iAno = null, $iMes = null, $iInstituicao = null, $sCampos = "*", $sWhere = "", $sOrderBy = "", $sCadAtual = null) {

     $sSQLBase = "select {$sCampos}                        ,                                                                     \n";
     $sSQLBase.= "       cast('{$sTabela}' as char)     as tabela_ponto ,                                                        \n";
     $sSQLBase.= "       cast('{$sSigla}' as char)      as sigla_tabela ,                                                        \n";

     if ( !empty($iAno) ) {
       $sSQLBase.= "       cast('{$iAno}' as int)         as base_anousu  ,                                                        \n";
     }

     if ( !empty($iMes) ) {
       $sSQLBase.= "       cast('{$iMes}' as int)         as base_mesusu  ,                                                        \n";
     }

     if ( !empty($iInstituicao) ) {
       $sSQLBase.= "       cast('{$iInstituicao}' as int) as base_instit  ,                                                        \n";
     }

     $sSQLBase.= "       {$sTabela}.{$sSigla}_pd as base_pd,                                                                     \n";
     $sSQLBase.= "       case when {$sSigla}_pd <> 3                                                                             \n";
     $sSQLBase.= "            then 'PD'                                                                                          \n";
     $sSQLBase.= "            else 'N'                                                                                           \n";
     $sSQLBase.= "       end as provdesc,                                                                                        \n";
     $sSQLBase.= "       case rh30_vinculo                                                                                       \n";
     $sSQLBase.= "            when 'A' then 'Ativo'                                                                              \n";
     $sSQLBase.= "            when 'P' then 'Pensionista'                                                                        \n";
     $sSQLBase.= "            else          'Inativo'                                                                            \n";
     $sSQLBase.= "       end as vinculo                                                                                          \n";
     $sSQLBase.= "  from rhpessoal                                                                                               \n";
     $sSQLBase.= "       inner join rhpessoalmov         on rhpessoalmov.rh02_regist      = rhpessoal.rh01_regist                \n";

     if ( !empty($iInstituicao) ) {
       $sSQLBase.= "                                      and rhpessoalmov.rh02_instit      = {$iInstituicao}                      \n";
     }

     if ( !empty($iAno) ) {
       $sSQLBase.= "                                      and rhpessoalmov.rh02_anousu      = {$iAno}                              \n";
     }

     if ( !empty($iMes) ) {
       $sSQLBase.= "                                      and rhpessoalmov.rh02_mesusu      = {$iMes}                              \n";
     }

     $sSQLBase.= "       inner join {$sTabela}           on {$sTabela}.{$sSigla}_regist   = rhpessoal.rh01_regist                \n";
     $sSQLBase.= "                                      and {$sTabela}.{$sSigla}_instit   = {$iInstituicao}                      \n";
     if($sCadAtual == null){
       $sSQLBase.= "                                    and {$sTabela}.{$sSigla}_anousu   = rhpessoalmov.rh02_anousu             \n";
       $sSQLBase.= "                                    and {$sTabela}.{$sSigla}_mesusu   = rhpessoalmov.rh02_mesusu             \n";
     }
     $sSQLBase.= "       left  join rhrubricas           on rhrubricas.rh27_rubric        = {$sTabela}.{$sSigla}_rubric::varchar \n";
     $sSQLBase.= "                                      and rhrubricas.rh27_instit        = {$iInstituicao}                      \n";
     $sSQLBase.= "       left  join rhrubelemento        on rh23_rubric                   = rh27_rubric                          \n";
     $sSQLBase.= "                                      and rh23_instit                   = rh27_instit                          \n";
     $sSQLBase.= "       left  join rhrubretencao        on rh75_rubric                   = rh27_rubric                          \n";
     $sSQLBase.= "                                      and rh75_instit                   = rh27_instit                          \n";
     $sSQLBase.= "       left  join retencaotiporec      on e21_sequencial                = rh75_retencaotiporec                 \n";
     $sSQLBase.= "       left  join retencaotipocalc     on e32_sequencial                = e21_retencaotipocalc                 \n";
     $sSQLBase.= "       left  join retencaotiporecgrupo on e01_sequencial                = e21_retencaotiporecgrupo             \n";
     $sSQLBase.= "       left  join rhpescargo           on rhpescargo.rh20_seqpes        = rhpessoalmov.rh02_seqpes             \n";
     $sSQLBase.= "       left  join rhcargo              on rhcargo.rh04_codigo           = rhpescargo.rh20_cargo                \n";
     $sSQLBase.= "                                      and rhcargo.rh04_instit           = rhpessoalmov.rh02_instit             \n";
     $sSQLBase.= "       left  join cgm                  on cgm.z01_numcgm                = rhpessoal.rh01_numcgm                \n";
     $sSQLBase.= "       left  join rhfuncao             on rhfuncao.rh37_funcao          = rhpessoalmov.rh02_funcao                \n";
     $sSQLBase.= "                                      and rhfuncao.rh37_instit          = rhpessoalmov.rh02_instit             \n";
     $sSQLBase.= "       left  join rhlota               on rhlota.r70_codigo             = rhpessoalmov.rh02_lota               \n";
     $sSQLBase.= "                                      and rhlota.r70_instit             = rhpessoalmov.rh02_instit             \n";
     $sSQLBase.= "       left  join rhlotaexe            on rh26_codigo                   = r70_codigo                           \n";
     $sSQLBase.= "                                      and rh26_anousu                   = rh02_anousu                          \n";
     $sSQLBase.= "       left  join orcorgao             on o40_orgao                     = rh26_orgao                           \n";
     $sSQLBase.= "                                      and o40_anousu                    = rhpessoalmov.rh02_anousu             \n";
     $sSQLBase.= "                                      and o40_instit                    = rhpessoalmov.rh02_instit             \n";
     $sSQLBase.= "       left  join rhlotavinc           on rh25_codigo                   = r70_codigo                           \n";
     $sSQLBase.= "                                      and rh25_anousu                   = rhpessoalmov.rh02_anousu             \n";
     $sSQLBase.= "       left  join orctiporec           on o15_codigo                    = rh25_recurso                         \n";
     $sSQLBase.= "       left  join rhregime             on rhregime.rh30_codreg          = rhpessoalmov.rh02_codreg             \n";
     $sSQLBase.= "                                      and rhregime.rh30_instit          = rhpessoalmov.rh02_instit             \n";
     $sSQLBase.= "       left  join rhpesrescisao        on rhpesrescisao.rh05_seqpes     = rhpessoalmov.rh02_seqpes             \n";
     $sSQLBase.= "       left  join rhpespadrao          on rhpespadrao.rh03_seqpes       = rhpessoalmov.rh02_seqpes             \n";
     $sSQLBase.= "                                      and rhpespadrao.rh03_anousu       = rhpessoalmov.rh02_anousu             \n";
     $sSQLBase.= "                                      and rhpespadrao.rh03_mesusu       = rhpessoalmov.rh02_mesusu             \n";
     $sSQLBase.= "       left  join padroes              on padroes.r02_anousu            = rhpespadrao.rh03_anousu              \n";
     $sSQLBase.= "                                      and padroes.r02_mesusu            = rhpespadrao.rh03_mesusu              \n";
     $sSQLBase.= "                                      and padroes.r02_regime            = rhpespadrao.rh03_regime              \n";
     $sSQLBase.= "                                      and padroes.r02_codigo            = rhpespadrao.rh03_padrao              \n";
     $sSQLBase.= "                                      and padroes.r02_instit            = rhpessoalmov.rh02_instit             \n";
     $sSQLBase.= "       left  join rhpeslocaltrab       on rhpeslocaltrab.rh56_seqpes    = rhpessoalmov.rh02_seqpes             \n";
     $sSQLBase.= "                                      and rhpeslocaltrab.rh56_princ     = 't'                                  \n";
     $sSQLBase.= "       left  join rhlocaltrab          on rhpeslocaltrab.rh56_localtrab = rhlocaltrab.rh55_codigo              \n";
     $sSQLBase.= " where true                                                                                                       \n";

     if ( !empty($iAno) ) {
       $sSQLBase.= "   and rhpessoalmov.rh02_anousu  = {$iAno}                                                                     \n";
     }

     if ( !emptY($iMes) ) {
       $sSQLBase.= "   and rhpessoalmov.rh02_mesusu  = {$iMes}                                                                     \n";
     }

     if (!empty($sWhere)) {
       $sSQLBase.= " and {$sWhere} \n";
     }
     $sSQLBase.= "   {$sOrderBy}                                                                                                 \n";

     return $sSQLBase;
   }

   function sql_queryInsntituicoesServidoresVinculo($iMesCompetencia = null, $iAnoCompetencia = null){

     require_once 'model/pessoal/std/DBPessoal.model.php';

     /**
      * Se não for informado MesCOmpetencia e/ou AnoCompetencia,
      * busca os dados da competência atual
      */
     if (!$iMesCompetencia) {
       $iMesCompetencia = DBPessoal::getMesFolha();
     }

     if (!$iAnoCompetencia) {
       $iAnoCompetencia = DBPessoal::getAnoFolha();
     }

     $sql  = "select codigo ,";
     $sql .= "       codigo  || ' - ' || nomeinst as nomeinst,";
     $sql .= "       rh30_vinculo,";
     $sql .= "       (rh30_vinculo = 'A') as ativo ";
     $sql .= "from db_config";
     $sql .= "     inner join rhpessoal     on rhpessoal.rh01_instit    = db_config.codigo";
     $sql .= "     inner join rhpessoalmov  on rhpessoalmov.rh02_instit = rhpessoal.rh01_instit";
     $sql .= "                             and rhpessoalmov.rh02_regist = rhpessoal.rh01_regist";
     $sql .= "                             and rhpessoalmov.rh02_anousu = {$iAnoCompetencia}";
     $sql .= "                             and rhpessoalmov.rh02_mesusu = {$iMesCompetencia}";
     $sql .= "     inner join rhregime      on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg";
     $sql .= "                             and rhregime.rh30_instit = rhpessoalmov.rh02_instit ";
     $sql .= "where db_config.db21_ativo = 1 ";
     $sql .= "group by codigo, ";
     $sql .= "rh30_vinculo ";
     $sql .= "order by codigo;";

     return $sql;
   }


   /**
    * Retorna os dados das margens para o E-consig, de todos os servidores ativos e rescindidos na competência informada
    * @param  Integer $iAnousu        Ano competência
    * @param  Integer $iMesusu        Mês competência
    * @param  string $sBaseProvendos Base de rubricas para proventos
    * @param  string $sBaseDescontos Base de rubricas para descontos
    * @return String
    */
   function sql_queryMargemEConsig( $iAnousu, $iMesusu, $iInstit, $sBaseProvendos = 'B105', $sBaseDescontos = 'B106' ){

     $sSql = "   select  rh02_regist,                                                                           ";
     $sSql .= "          z01_cgccpf,                                                                            ";
     $sSql .= "          z01_nome,                                                                              ";
     $sSql .= "          nomeinst,                                                                              ";
     $sSql .= "          o40_descr,                                                                             ";
     $sSql .= "          rh30_descr,                                                                            ";
     $sSql .= "          coalesce( ( select round( sum( r14_valor ), 2 )                                        ";
     $sSql .= "              from gerfsal                                                                       ";
     $sSql .= "             where r14_anousu = rh02_anousu                                                      ";
     $sSql .= "               and r14_mesusu = rh02_mesusu                                                      ";
     $sSql .= "               and r14_regist = rh02_regist                                                      ";
     $sSql .= "               and r14_rubric in (select r09_rubric                                              ";
     $sSql .= "                                    from basesr                                                  ";
     $sSql .= "                                   where r09_anousu = rh02_anousu                                ";
     $sSql .= "                                     and r09_mesusu = rh02_mesusu                                ";
     $sSql .= "                                     and r09_instit = rh02_instit                                ";
     $sSql .= "                                     and r09_base   = '{$sBaseProvendos}') ), 0) -               ";
     $sSql .= "          coalesce( ( select round( sum( r14_valor ), 2 )                                        ";
     $sSql .= "               from gerfsal                                                                      ";
     $sSql .= "              where r14_anousu = rh02_anousu                                                     ";
     $sSql .= "                and r14_mesusu = rh02_mesusu                                                     ";
     $sSql .= "                and r14_regist = rh02_regist                                                     ";
     $sSql .= "                and r14_rubric in (select r09_rubric                                             ";
     $sSql .= "                                     from basesr                                                 ";
     $sSql .= "                                    where r09_anousu = rh02_anousu                               ";
     $sSql .= "                                      and r09_mesusu = rh02_mesusu                               ";
     $sSql .= "                                      and r09_instit = rh02_instit                               ";
     $sSql .= "                                      and r09_base   = '$sBaseDescontos') ), 0) as margem,       ";
     $sSql .= "          coalesce( ( select round( sum( r20_valor ), 2 )                                        ";
     $sSql .= "              from gerfres                                                                       ";
     $sSql .= "             where r20_anousu = rh02_anousu                                                      ";
     $sSql .= "               and r20_mesusu = rh02_mesusu                                                      ";
     $sSql .= "               and r20_regist = rh02_regist                                                      ";
     $sSql .= "               and r20_rubric in (select r09_rubric                                              ";
     $sSql .= "                                    from basesr                                                  ";
     $sSql .= "                                   where r09_anousu = rh02_anousu                                ";
     $sSql .= "                                     and r09_mesusu = rh02_mesusu                                ";
     $sSql .= "                                     and r09_instit = rh02_instit                                ";
     $sSql .= "                                     and r09_base   = '{$sBaseProvendos}') ), 0) -               ";
     $sSql .= "          coalesce( ( select round( sum( r20_valor ), 2 )                                        ";
     $sSql .= "             from gerfres                                                                        ";
     $sSql .= "            where r20_anousu = rh02_anousu                                                       ";
     $sSql .= "              and r20_mesusu = rh02_mesusu                                                       ";
     $sSql .= "              and r20_regist = rh02_regist                                                       ";
     $sSql .= "              and r20_rubric in (select r09_rubric                                               ";
     $sSql .= "                                   from basesr                                                   ";
     $sSql .= "                                  where r09_anousu = rh02_anousu                                 ";
     $sSql .= "                                    and r09_mesusu = rh02_mesusu                                 ";
     $sSql .= "                                    and r09_instit = rh02_instit                                 ";
     $sSql .= "                                    and r09_base   = '{$sBaseDescontos}') ), 0) as margem_rescisao, ";
     $sSql .= "          z01_nasc,                                                                              ";
     $sSql .= "          rh01_admiss,                                                                           ";
     $sSql .= "          rh05_recis,                                                                            ";
     $sSql .= "          r70_descr,                                                                             ";
     $sSql .= "          z01_ident,                                                                             ";
     $sSql .= "          (  select case r45_situac when 2 then 'Afastado sem remuneração'                       ";
     $sSql .= "                                    when 3 then 'Afastado acidente de trabalho +15 dias'         ";
     $sSql .= "                                    when 4 then 'Afastado serviço militar'                       ";
     $sSql .= "                                    when 5 then 'Afastado licença gestante'                      ";
     $sSql .= "                                    when 6 then 'Afastado doença +15 dias'                       ";
     $sSql .= "                                    when 7 then 'Licença sem vencimento, cessão sem ônus'        ";
     $sSql .= "                    end as r45_situac                                                            ";
     $sSql .= "               from afasta                                                                       ";
     $sSql .= "              where r45_anousu = rh02_anousu                                                     ";
     $sSql .= "                and r45_mesusu = rh02_mesusu                                                     ";
     $sSql .= "                and r45_regist = rh02_regist                                                     ";
     $sSql .= "                and r45_dtreto is null limit 1 ) as afastamento                                  ";
     $sSql .= "     from rhpessoalmov                                                                           ";
     $sSql .= "          inner join rhpessoal      on rh02_regist = rh01_regist                                 ";
     $sSql .= "          inner join cgm            on z01_numcgm  = rh01_numcgm                                 ";
     $sSql .= "          inner join rhregime       on rh30_codreg = rh02_codreg                                 ";
     $sSql .= "          inner join db_config      on rh02_instit = codigo                                      ";
     $sSql .= "          left  join rhlota         on r70_codigo  = rh02_lota                                   ";
     $sSql .= "                                   and r70_instit  = rh02_instit                                 ";
     $sSql .= "          left  join rhlotaexe      on rh26_codigo = r70_codigo                                  ";
     $sSql .= "                                   and rh26_anousu = rh02_anousu                                 ";
     $sSql .= "          left  join orcorgao       on o40_orgao   = rh26_orgao                                  ";
     $sSql .= "                                   and o40_anousu  = rh02_anousu                                 ";
     $sSql .= "                                   and o40_instit  = rh02_instit                                 ";
     $sSql .= "          left  join rhpesrescisao  on rh05_seqpes = rh02_seqpes                                 ";
     $sSql .= "   where rh02_anousu = {$iAnousu}                                                                ";
     $sSql .= "     and rh02_mesusu = {$iMesusu}                                                                ";
     $sSql .= "     and rh02_instit = {$iInstit}                                                                ";
     $sSql .= "     and ( rh05_recis is null                                                                    ";
     $sSql .= "           or ( extract( year from rh05_recis  )       = rh02_anousu                             ";
     $sSql .= "                and extract( month from rh05_recis  )  = rh02_mesusu                             ";
     $sSql .= "              )                                                                                  ";
     $sSql .= "         )                                                                                       ";
     $sSql .= "order by 3                                                                                       ";

     return $sSql;
   }

   /**
    * Retorna os dados das margens para o E-consig, de todos os servidores ativos e rescindidos na competência informada
    * @param  Integer $iAnousu        Ano competência
    * @param  Integer $iMesusu        Mês competência
    * @param  string $sBaseProvendos Base de rubricas para proventos
    * @param  string $sBaseDescontos Base de rubricas para descontos
    * @return String
    */
   function sql_queryServidoresConsignar( $iAnousu, $iMesusu, $iInstit, $lEstagiario = null ){

     $sSql = "   select  rh02_regist,                                                                           ";
     $sSql .= "          z01_cgccpf,                                                                            ";
     $sSql .= "          z01_nome,                                                                              ";
     $sSql .= "          nomeinst,                                                                              ";
     $sSql .= "          o40_descr,                                                                             ";
     $sSql .= "          rh30_descr,                                                                            ";
     $sSql .= "          z01_nasc,                                                                              ";
     $sSql .= "          rh01_admiss,                                                                           ";
     $sSql .= "          rh05_recis,                                                                            ";
     $sSql .= "          r70_descr,                                                                             ";
     $sSql .= "          z01_ident,                                                                             ";
     $sSql .= "          (  select case r45_situac when 2 then 'Afastado sem remuneração'                       ";
     $sSql .= "                                    when 3 then 'Afastado acidente de trabalho +15 dias'         ";
     $sSql .= "                                    when 4 then 'Afastado serviço militar'                       ";
     $sSql .= "                                    when 5 then 'Afastado licença gestante'                      ";
     $sSql .= "                                    when 6 then 'Afastado doença +15 dias'                       ";
     $sSql .= "                                    when 7 then 'Licença sem vencimento, cessão sem ônus'        ";
     $sSql .= "                    end as r45_situac                                                            ";
     $sSql .= "               from afasta                                                                       ";
     $sSql .= "              where r45_anousu = rh02_anousu                                                     ";
     $sSql .= "                and r45_mesusu = rh02_mesusu                                                     ";
     $sSql .= "                and r45_regist = rh02_regist                                                     ";
     $sSql .= "                and r45_dtreto is null limit 1 ) as afastamento,                                 ";
     $sSql .= "           rh02_instit as codigo_instituicao                                                     ";
     $sSql .= "     from rhpessoalmov                                                                           ";
     $sSql .= "          inner join rhpessoal      on rh02_regist = rh01_regist                                 ";
     $sSql .= "          inner join cgm            on z01_numcgm  = rh01_numcgm                                 ";
     $sSql .= "          inner join rhregime       on rh30_codreg = rh02_codreg                                 ";
     $sSql .= "          inner join db_config      on rh02_instit = codigo                                      ";
     $sSql .= "          left  join rhlota         on r70_codigo  = rh02_lota                                   ";
     $sSql .= "                                   and r70_instit  = rh02_instit                                 ";
     $sSql .= "          left  join rhlotaexe      on rh26_codigo = r70_codigo                                  ";
     $sSql .= "                                   and rh26_anousu = rh02_anousu                                 ";
     $sSql .= "          left  join orcorgao       on o40_orgao   = rh26_orgao                                  ";
     $sSql .= "                                   and o40_anousu  = rh02_anousu                                 ";
     $sSql .= "                                   and o40_instit  = rh02_instit                                 ";
     $sSql .= "          left  join rhpesrescisao  on rh05_seqpes = rh02_seqpes                                 ";
     $sSql .= "   where rh02_anousu = {$iAnousu}                                                                ";
     $sSql .= "     and rh02_mesusu = {$iMesusu}                                                                ";
     $sSql .= "     and rh02_instit = {$iInstit}                                                                ";
     $sSql .= "     and ( rh05_recis is null                                                                    ";
     $sSql .= "           or ( extract( year from rh05_recis  )       = rh02_anousu                             ";
     $sSql .= "                and extract( month from rh05_recis  )  = rh02_mesusu                             ";
     $sSql .= "              )                                                                                  ";
     $sSql .= "         )                                                                                       ";
     
     if ( $lEstagiario === false ) {
       $sSql .= "     and rh30_descr not ilike '%estagiario%'                                                   ";
     }

     if ( $lEstagiario === true ) {
       $sSql .= "     and rh30_descr ilike '%estagiario%'                                                       ";
     }

     $sSql .= "order by 3                                                                                       ";

     return $sSql;
   }

   /**
    * Retorna o aposentados com o tipo de reajuste passado para fazer reajuste do salario.
    * @param $iTipoReajuste - Tipo de reajuste - (0 - NENHUM, 1 - REAL, 2 - PARIDADE)
    * @param $sCampos - Campos da query
    * @param $sOrdem  - Ordem dos registro
    * @param $sWhere - Condição da query
    * @return string - Sql dos aposentados
    */
   function sql_queryReajuste($iTipoReajuste, $sCampos="*", $sOrdem=null, $sWhere=""){

   	$sSql  = "select distinct $sCampos                                                             ";
   	$sSql .= "  from rhpessoal                                                                     ";
   	$sSql .= "       inner join rhpessoalmov  on  rh02_regist      = rh01_regist                   ";
   	$sSql .= "                               and  rh02_anousu      = ".db_anofolha()                ;
   	$sSql .= "                               and  rh02_mesusu      = ".db_mesfolha()                ;
   	$sSql .= "                               and  rh02_instit      = ".db_getsession("DB_instit")   ;
   	$sSql .= "       inner join rhlota        on  r70_codigo       = rh02_lota                     ";
   	$sSql .= "                               and  r70_instit       = rh02_instit                   ";
   	$sSql .= "       inner join cgm           on  z01_numcgm       = rh01_numcgm                   ";
   	$sSql .= "       left join assenta       on  h16_regist       = rh02_regist                    ";
   	$sSql .= "       left join tipoasse      on  h12_codigo       = h16_assent                     ";
   	$sSql .= "                               and  h12_tiporeajuste = {$iTipoReajuste}              ";


    if ($iTipoReajuste <> 0 ) {

      $sSql .= "   where (                                                                   ";
      $sSql .= "         select count(*)                                                     ";
      $sSql .= "           from assenta                                                      ";
      $sSql .= "                inner join tipoasse  on h12_codigo        = h16_assent       ";
      $sSql .= "                                    and h12_tiporeajuste  = {$iTipoReajuste} ";
      $sSql .= "         where h16_regist = rh02_regist                                      ";
      $sSql .= "     ) > 0                                                                   ";
    } else  {

      $sSql .= " where ( (                                                                 ";
      $sSql .= "       select count(*)                                                     ";
      $sSql .= "         from assenta                                                      ";
      $sSql .= "              inner join tipoasse on h12_codigo = h16_assent               ";
      $sSql .= "                                 and h12_tiporeajuste <> 0                 ";
      $sSql .= "       where h16_regist = rh02_regist                                      ";
      $sSql .= "      ) = 0                                                                ";
      $sSql .= " OR (                                                                      ";
      $sSql .= "       select count(*)                                                     ";
      $sSql .= "         from assenta                                                      ";
      $sSql .= "              left join tipoasse on  h12_codigo = h16_assent               ";
      $sSql .= "       where h16_regist = rh02_regist                                      ";
      $sSql .= "     ) is null )                                                           ";
    }

   	if (!empty($sWhere)) {
   		$sSql .= " and ". $sWhere;
   	}

   	if (!empty($sOrdem)) {
   		$sSql .= "order by " . $sOrdem;
   	}

   	return $sSql;
   }

  /**
   * Verifica a situacao do servidor
   *
   * @param Integer $iMatriculaServidor
   * @param Integer $iAnoCompetencia
   * @param Integer $iMesCompetencia
   * @return String
   */
  function sql_verificaSituacaoServidor($iMatriculaServidor, $iAnoCompetencia, $iMesCompetencia) {

    $sSql  = " select rh30_vinculo, rh05_recis                                                         \n";
    $sSql .= "   from rhpessoal                                                                        \n";
    $sSql .= "        inner join rhpessoalmov  on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist     \n";
    $sSql .= "                                and rhpessoalmov.rh02_anousu = {$iAnoCompetencia}        \n";
    $sSql .= "                                and rhpessoalmov.rh02_mesusu = {$iMesCompetencia}        \n";
    $sSql .= "        inner join rhregime      on rhpessoalmov.rh02_codreg  = rhregime.rh30_codreg     \n";
    $sSql .= "        left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes \n";
    $sSql .= "  where rh01_regist = {$iMatriculaServidor}                                                ";

    return $sSql;
  }

   // funcao do sql
   function sql_query_com_temporarios ( $rh01_regist=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rhpessoal ";
     $sql .= "      left  join rhpessoalmov on  rh02_anousu = ".db_anofolha()."
                                           and  rh02_mesusu = ".db_mesfolha()."
                                           and  rh02_regist = rh01_regist
                                           and  rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left  join rhlota       on  rhlota.r70_codigo = rhpessoalmov.rh02_lota
                                           and  rhlota.r70_instit = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join db_config    on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left  join rhfuncao     on  rhfuncao.rh37_funcao = rhpessoalmov.rh02_funcao
                                           and  rhfuncao.rh37_instit = rhpessoalmov.rh02_instit  ";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql .= "      left  join rhpesrescisao on rh02_seqpes = rh05_seqpes";
     $sql .= "      left  join rhsindicato   on rh01_rhsindicato = rh116_sequencial";
     $sql .= "      inner join rhreajusteparidade  on  rhreajusteparidade.rh148_sequencial = rhpessoal.rh01_reajusteparidade";
     $sql .= "      left  join rhcontratoemergencial on rh163_matricula = rh01_regist";
     $sql .= "      left  join rhcontratoemergencialrenovacao on rh164_contratoemergencial = rh163_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($rh01_regist!=null ){
         $sql2 .= " where rhpessoal.rh01_regist = $rh01_regist ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
