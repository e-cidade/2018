<?php
/*
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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE unidades
class cl_unidades { 
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
   var $sd02_i_codigo = 0; 
   var $sd02_i_numcgm = 0; 
   var $sd02_i_situacao = 0; 
   var $sd02_v_cnes = null; 
   var $sd02_i_cidade = 0; 
   var $sd02_i_regiao = 0; 
   var $sd02_v_microreg = null; 
   var $sd02_v_distsant = null; 
   var $sd02_v_distadmin = null; 
   var $sd02_i_distrito = 0; 
   var $sd02_c_siasus = null; 
   var $sd02_i_cod_esfadm = 0; 
   var $sd02_i_cod_ativ = 0; 
   var $sd02_i_reten_trib = 0; 
   var $sd02_i_cod_natorg = 0; 
   var $sd02_i_cod_client = 0; 
   var $sd02_v_num_alvara = null; 
   var $sd02_d_data_exped_dia = null; 
   var $sd02_d_data_exped_mes = null; 
   var $sd02_d_data_exped_ano = null; 
   var $sd02_d_data_exped = null; 
   var $sd02_v_ind_orgexp = null; 
   var $sd02_i_tp_unid_id = 0; 
   var $sd02_i_cod_turnat = 0; 
   var $sd02_i_codnivhier = 0; 
   var $sd02_i_diretor = 0; 
   var $sd02_v_diretorreg = null; 
   var $sd02_c_centralagenda = null; 
   var $sd02_cnpjcpf = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd02_i_codigo = int4 = Unidade 
                 sd02_i_numcgm = int4 = Estabelecimento 
                 sd02_i_situacao = int4 = Situação 
                 sd02_v_cnes = varchar(7) = CNES 
                 sd02_i_cidade = int4 = Cod. IBGE 
                 sd02_i_regiao = int4 = Cod. Região 
                 sd02_v_microreg = varchar(6) = Micro Região 
                 sd02_v_distsant = varchar(4) = Distrito Sanitário 
                 sd02_v_distadmin = varchar(4) = Módulo Assitencial 
                 sd02_i_distrito = int4 = Cod. Distrito 
                 sd02_c_siasus = char(6) = Sia/Sus 
                 sd02_i_cod_esfadm = int4 = Esfera Admin. 
                 sd02_i_cod_ativ = int4 = Atividade de Ensino 
                 sd02_i_reten_trib = int4 = Retenção Tributos 
                 sd02_i_cod_natorg = int4 = Natureza Organização 
                 sd02_i_cod_client = int4 = Fluxo de Clientela 
                 sd02_v_num_alvara = varchar(60) = Número do Alvará 
                 sd02_d_data_exped = date = Data expedição alvara 
                 sd02_v_ind_orgexp = varchar(2) = Órgão expedidor 
                 sd02_i_tp_unid_id = int4 = Tipo Unidade 
                 sd02_i_cod_turnat = int4 = Turno de Atendimento 
                 sd02_i_codnivhier = int4 = Nível de Hierarquia 
                 sd02_i_diretor = int4 = Diretor 
                 sd02_v_diretorreg = varchar(6) = Registro 
                 sd02_c_centralagenda = char(1) = Central de Agendamento 
                 sd02_cnpjcpf = varchar(14) = CNPJ/CPF 
                 ";
   //funcao construtor da classe 
   function cl_unidades() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("unidades"); 
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
       $this->sd02_i_codigo = ($this->sd02_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_codigo"]:$this->sd02_i_codigo);
       $this->sd02_i_numcgm = ($this->sd02_i_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_numcgm"]:$this->sd02_i_numcgm);
       $this->sd02_i_situacao = ($this->sd02_i_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_situacao"]:$this->sd02_i_situacao);
       $this->sd02_v_cnes = ($this->sd02_v_cnes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_v_cnes"]:$this->sd02_v_cnes);
       $this->sd02_i_cidade = ($this->sd02_i_cidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_cidade"]:$this->sd02_i_cidade);
       $this->sd02_i_regiao = ($this->sd02_i_regiao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_regiao"]:$this->sd02_i_regiao);
       $this->sd02_v_microreg = ($this->sd02_v_microreg == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_v_microreg"]:$this->sd02_v_microreg);
       $this->sd02_v_distsant = ($this->sd02_v_distsant == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_v_distsant"]:$this->sd02_v_distsant);
       $this->sd02_v_distadmin = ($this->sd02_v_distadmin == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_v_distadmin"]:$this->sd02_v_distadmin);
       $this->sd02_i_distrito = ($this->sd02_i_distrito == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_distrito"]:$this->sd02_i_distrito);
       $this->sd02_c_siasus = ($this->sd02_c_siasus == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_c_siasus"]:$this->sd02_c_siasus);
       $this->sd02_i_cod_esfadm = ($this->sd02_i_cod_esfadm == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_esfadm"]:$this->sd02_i_cod_esfadm);
       $this->sd02_i_cod_ativ = ($this->sd02_i_cod_ativ == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_ativ"]:$this->sd02_i_cod_ativ);
       $this->sd02_i_reten_trib = ($this->sd02_i_reten_trib == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_reten_trib"]:$this->sd02_i_reten_trib);
       $this->sd02_i_cod_natorg = ($this->sd02_i_cod_natorg == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_natorg"]:$this->sd02_i_cod_natorg);
       $this->sd02_i_cod_client = ($this->sd02_i_cod_client == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_client"]:$this->sd02_i_cod_client);
       $this->sd02_v_num_alvara = ($this->sd02_v_num_alvara == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_v_num_alvara"]:$this->sd02_v_num_alvara);
       if($this->sd02_d_data_exped == ""){
         $this->sd02_d_data_exped_dia = ($this->sd02_d_data_exped_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_d_data_exped_dia"]:$this->sd02_d_data_exped_dia);
         $this->sd02_d_data_exped_mes = ($this->sd02_d_data_exped_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_d_data_exped_mes"]:$this->sd02_d_data_exped_mes);
         $this->sd02_d_data_exped_ano = ($this->sd02_d_data_exped_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_d_data_exped_ano"]:$this->sd02_d_data_exped_ano);
         if($this->sd02_d_data_exped_dia != ""){
            $this->sd02_d_data_exped = $this->sd02_d_data_exped_ano."-".$this->sd02_d_data_exped_mes."-".$this->sd02_d_data_exped_dia;
         }
       }
       $this->sd02_v_ind_orgexp = ($this->sd02_v_ind_orgexp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_v_ind_orgexp"]:$this->sd02_v_ind_orgexp);
       $this->sd02_i_tp_unid_id = ($this->sd02_i_tp_unid_id == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_tp_unid_id"]:$this->sd02_i_tp_unid_id);
       $this->sd02_i_cod_turnat = ($this->sd02_i_cod_turnat == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_turnat"]:$this->sd02_i_cod_turnat);
       $this->sd02_i_codnivhier = ($this->sd02_i_codnivhier == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_codnivhier"]:$this->sd02_i_codnivhier);
       $this->sd02_i_diretor = ($this->sd02_i_diretor == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_diretor"]:$this->sd02_i_diretor);
       $this->sd02_v_diretorreg = ($this->sd02_v_diretorreg == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_v_diretorreg"]:$this->sd02_v_diretorreg);
       $this->sd02_c_centralagenda = ($this->sd02_c_centralagenda == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_c_centralagenda"]:$this->sd02_c_centralagenda);
       $this->sd02_cnpjcpf = ($this->sd02_cnpjcpf == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_cnpjcpf"]:$this->sd02_cnpjcpf);
     }else{
       $this->sd02_i_codigo = ($this->sd02_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd02_i_codigo"]:$this->sd02_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($sd02_i_codigo){ 
      $this->atualizacampos();
     if($this->sd02_i_numcgm == null ){ 
       $this->erro_sql = " Campo Estabelecimento não informado.";
       $this->erro_campo = "sd02_i_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd02_i_situacao == null ){ 
       $this->sd02_i_situacao = "null";
     }
     if($this->sd02_i_cidade == null ){ 
       $this->sd02_i_cidade = "null";
     }
     if($this->sd02_i_regiao == null ){ 
       $this->sd02_i_regiao = "null";
     }
     if($this->sd02_i_distrito == null ){ 
       $this->sd02_i_distrito = "null";
     }
     if($this->sd02_i_cod_esfadm == null ){ 
       $this->sd02_i_cod_esfadm = "null";
     }
     if($this->sd02_i_cod_ativ == null ){ 
       $this->sd02_i_cod_ativ = "null";
     }
     if($this->sd02_i_reten_trib == null ){ 
       $this->sd02_i_reten_trib = "null";
     }
     if($this->sd02_i_cod_natorg == null ){ 
       $this->sd02_i_cod_natorg = "null";
     }
     if($this->sd02_i_cod_client == null ){ 
       $this->sd02_i_cod_client = "null";
     }
     if($this->sd02_d_data_exped == null ){ 
       $this->sd02_d_data_exped = "null";
     }
     if($this->sd02_i_tp_unid_id == null ){ 
       $this->sd02_i_tp_unid_id = "null";
     }
     if($this->sd02_i_cod_turnat == null ){ 
       $this->sd02_i_cod_turnat = "null";
     }
     if($this->sd02_i_codnivhier == null ){ 
       $this->sd02_i_codnivhier = "null";
     }
     if($this->sd02_i_diretor == null ){ 
       $this->sd02_i_diretor = "null";
     }
     if($this->sd02_c_centralagenda == null ){ 
       $this->sd02_c_centralagenda = "N";
     }
       $this->sd02_i_codigo = $sd02_i_codigo; 
     if(($this->sd02_i_codigo == null) || ($this->sd02_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd02_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into unidades(
                                       sd02_i_codigo 
                                      ,sd02_i_numcgm 
                                      ,sd02_i_situacao 
                                      ,sd02_v_cnes 
                                      ,sd02_i_cidade 
                                      ,sd02_i_regiao 
                                      ,sd02_v_microreg 
                                      ,sd02_v_distsant 
                                      ,sd02_v_distadmin 
                                      ,sd02_i_distrito 
                                      ,sd02_c_siasus 
                                      ,sd02_i_cod_esfadm 
                                      ,sd02_i_cod_ativ 
                                      ,sd02_i_reten_trib 
                                      ,sd02_i_cod_natorg 
                                      ,sd02_i_cod_client 
                                      ,sd02_v_num_alvara 
                                      ,sd02_d_data_exped 
                                      ,sd02_v_ind_orgexp 
                                      ,sd02_i_tp_unid_id 
                                      ,sd02_i_cod_turnat 
                                      ,sd02_i_codnivhier 
                                      ,sd02_i_diretor 
                                      ,sd02_v_diretorreg 
                                      ,sd02_c_centralagenda 
                                      ,sd02_cnpjcpf 
                       )
                values (
                                $this->sd02_i_codigo 
                               ,$this->sd02_i_numcgm 
                               ,$this->sd02_i_situacao 
                               ,'$this->sd02_v_cnes' 
                               ,$this->sd02_i_cidade 
                               ,$this->sd02_i_regiao 
                               ,'$this->sd02_v_microreg' 
                               ,'$this->sd02_v_distsant' 
                               ,'$this->sd02_v_distadmin' 
                               ,$this->sd02_i_distrito 
                               ,'$this->sd02_c_siasus' 
                               ,$this->sd02_i_cod_esfadm 
                               ,$this->sd02_i_cod_ativ 
                               ,$this->sd02_i_reten_trib 
                               ,$this->sd02_i_cod_natorg 
                               ,$this->sd02_i_cod_client 
                               ,'$this->sd02_v_num_alvara' 
                               ,".($this->sd02_d_data_exped == "null" || $this->sd02_d_data_exped == ""?"null":"'".$this->sd02_d_data_exped."'")." 
                               ,'$this->sd02_v_ind_orgexp' 
                               ,$this->sd02_i_tp_unid_id 
                               ,$this->sd02_i_cod_turnat 
                               ,$this->sd02_i_codnivhier 
                               ,$this->sd02_i_diretor 
                               ,'$this->sd02_v_diretorreg' 
                               ,'$this->sd02_c_centralagenda' 
                               ,'$this->sd02_cnpjcpf' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Unidades ($this->sd02_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Unidades já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Unidades ($this->sd02_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd02_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd02_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,100033,'$this->sd02_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,100011,100033,'','".AddSlashes(pg_result($resaco,0,'sd02_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11420,'','".AddSlashes(pg_result($resaco,0,'sd02_i_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11389,'','".AddSlashes(pg_result($resaco,0,'sd02_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11388,'','".AddSlashes(pg_result($resaco,0,'sd02_v_cnes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,100046,'','".AddSlashes(pg_result($resaco,0,'sd02_i_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,100045,'','".AddSlashes(pg_result($resaco,0,'sd02_i_regiao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11390,'','".AddSlashes(pg_result($resaco,0,'sd02_v_microreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11391,'','".AddSlashes(pg_result($resaco,0,'sd02_v_distsant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11392,'','".AddSlashes(pg_result($resaco,0,'sd02_v_distadmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,100044,'','".AddSlashes(pg_result($resaco,0,'sd02_i_distrito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,100047,'','".AddSlashes(pg_result($resaco,0,'sd02_c_siasus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11393,'','".AddSlashes(pg_result($resaco,0,'sd02_i_cod_esfadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11394,'','".AddSlashes(pg_result($resaco,0,'sd02_i_cod_ativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11395,'','".AddSlashes(pg_result($resaco,0,'sd02_i_reten_trib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11396,'','".AddSlashes(pg_result($resaco,0,'sd02_i_cod_natorg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11397,'','".AddSlashes(pg_result($resaco,0,'sd02_i_cod_client'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11398,'','".AddSlashes(pg_result($resaco,0,'sd02_v_num_alvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11399,'','".AddSlashes(pg_result($resaco,0,'sd02_d_data_exped'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11400,'','".AddSlashes(pg_result($resaco,0,'sd02_v_ind_orgexp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11401,'','".AddSlashes(pg_result($resaco,0,'sd02_i_tp_unid_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11402,'','".AddSlashes(pg_result($resaco,0,'sd02_i_cod_turnat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11403,'','".AddSlashes(pg_result($resaco,0,'sd02_i_codnivhier'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11729,'','".AddSlashes(pg_result($resaco,0,'sd02_i_diretor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,11730,'','".AddSlashes(pg_result($resaco,0,'sd02_v_diretorreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,13760,'','".AddSlashes(pg_result($resaco,0,'sd02_c_centralagenda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100011,21136,'','".AddSlashes(pg_result($resaco,0,'sd02_cnpjcpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($sd02_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update unidades set ";
     $virgula = "";
     if(trim($this->sd02_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_codigo"])){ 
       $sql  .= $virgula." sd02_i_codigo = $this->sd02_i_codigo ";
       $virgula = ",";
       if(trim($this->sd02_i_codigo) == null ){ 
         $this->erro_sql = " Campo Unidade não informado.";
         $this->erro_campo = "sd02_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd02_i_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_numcgm"])){ 
       $sql  .= $virgula." sd02_i_numcgm = $this->sd02_i_numcgm ";
       $virgula = ",";
       if(trim($this->sd02_i_numcgm) == null ){ 
         $this->erro_sql = " Campo Estabelecimento não informado.";
         $this->erro_campo = "sd02_i_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd02_i_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_situacao"])){ 
        if(trim($this->sd02_i_situacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_situacao"])){ 
           $this->sd02_i_situacao = "0" ; 
        } 
       $sql  .= $virgula." sd02_i_situacao = $this->sd02_i_situacao ";
       $virgula = ",";
     }
     if(trim($this->sd02_v_cnes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_v_cnes"])){ 
       $sql  .= $virgula." sd02_v_cnes = '$this->sd02_v_cnes' ";
       $virgula = ",";
     }
     if(trim($this->sd02_i_cidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cidade"])){ 
        if(trim($this->sd02_i_cidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cidade"])){ 
           $this->sd02_i_cidade = "null" ; 
        } 
       $sql  .= $virgula." sd02_i_cidade = $this->sd02_i_cidade ";
       $virgula = ",";
     }
     if(trim($this->sd02_i_regiao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_regiao"])){ 
        if(trim($this->sd02_i_regiao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_regiao"])){ 
           $this->sd02_i_regiao = "null" ; 
        } 
       $sql  .= $virgula." sd02_i_regiao = $this->sd02_i_regiao ";
       $virgula = ",";
     }
     if(trim($this->sd02_v_microreg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_v_microreg"])){ 
       $sql  .= $virgula." sd02_v_microreg = '$this->sd02_v_microreg' ";
       $virgula = ",";
     }
     if(trim($this->sd02_v_distsant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_v_distsant"])){ 
       $sql  .= $virgula." sd02_v_distsant = '$this->sd02_v_distsant' ";
       $virgula = ",";
     }
     if(trim($this->sd02_v_distadmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_v_distadmin"])){ 
       $sql  .= $virgula." sd02_v_distadmin = '$this->sd02_v_distadmin' ";
       $virgula = ",";
     }
     if(trim($this->sd02_i_distrito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_distrito"])){ 
        if(trim($this->sd02_i_distrito)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_distrito"])){ 
           $this->sd02_i_distrito = "null" ; 
        } 
       $sql  .= $virgula." sd02_i_distrito = $this->sd02_i_distrito ";
       $virgula = ",";
     }
     if(trim($this->sd02_c_siasus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_c_siasus"])){ 
       $sql  .= $virgula." sd02_c_siasus = '$this->sd02_c_siasus' ";
       $virgula = ",";
     }
     if(trim($this->sd02_i_cod_esfadm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_esfadm"])){ 
        if(trim($this->sd02_i_cod_esfadm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_esfadm"])){ 
           $this->sd02_i_cod_esfadm = "0" ; 
        } 
       $sql  .= $virgula." sd02_i_cod_esfadm = $this->sd02_i_cod_esfadm ";
       $virgula = ",";
     }
     if(trim($this->sd02_i_cod_ativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_ativ"])){ 
        if(trim($this->sd02_i_cod_ativ)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_ativ"])){ 
           $this->sd02_i_cod_ativ = "0" ; 
        } 
       $sql  .= $virgula." sd02_i_cod_ativ = $this->sd02_i_cod_ativ ";
       $virgula = ",";
     }
     if(trim($this->sd02_i_reten_trib)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_reten_trib"])){ 
        if(trim($this->sd02_i_reten_trib)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_reten_trib"])){ 
           $this->sd02_i_reten_trib = "0" ; 
        } 
       $sql  .= $virgula." sd02_i_reten_trib = $this->sd02_i_reten_trib ";
       $virgula = ",";
     }
     if(trim($this->sd02_i_cod_natorg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_natorg"])){ 
        if(trim($this->sd02_i_cod_natorg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_natorg"])){ 
           $this->sd02_i_cod_natorg = "0" ; 
        } 
       $sql  .= $virgula." sd02_i_cod_natorg = $this->sd02_i_cod_natorg ";
       $virgula = ",";
     }
     if(trim($this->sd02_i_cod_client)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_client"])){ 
        if(trim($this->sd02_i_cod_client)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_client"])){ 
           $this->sd02_i_cod_client = "0" ; 
        } 
       $sql  .= $virgula." sd02_i_cod_client = $this->sd02_i_cod_client ";
       $virgula = ",";
     }
     if(trim($this->sd02_v_num_alvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_v_num_alvara"])){ 
       $sql  .= $virgula." sd02_v_num_alvara = '$this->sd02_v_num_alvara' ";
       $virgula = ",";
     }
     if(trim($this->sd02_d_data_exped)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_d_data_exped_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd02_d_data_exped_dia"] !="") ){ 
       $sql  .= $virgula." sd02_d_data_exped = '$this->sd02_d_data_exped' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd02_d_data_exped_dia"])){ 
         $sql  .= $virgula." sd02_d_data_exped = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd02_v_ind_orgexp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_v_ind_orgexp"])){ 
       $sql  .= $virgula." sd02_v_ind_orgexp = '$this->sd02_v_ind_orgexp' ";
       $virgula = ",";
     }
     if(trim($this->sd02_i_tp_unid_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_tp_unid_id"])){ 
        if(trim($this->sd02_i_tp_unid_id)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_tp_unid_id"])){ 
           $this->sd02_i_tp_unid_id = "null" ; 
        } 
       $sql  .= $virgula." sd02_i_tp_unid_id = $this->sd02_i_tp_unid_id ";
       $virgula = ",";
     }
     if(trim($this->sd02_i_cod_turnat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_turnat"])){ 
        if(trim($this->sd02_i_cod_turnat)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_turnat"])){ 
           $this->sd02_i_cod_turnat = "0" ; 
        } 
       $sql  .= $virgula." sd02_i_cod_turnat = $this->sd02_i_cod_turnat ";
       $virgula = ",";
     }
     if(trim($this->sd02_i_codnivhier)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_codnivhier"])){ 
        if(trim($this->sd02_i_codnivhier)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_codnivhier"])){ 
           $this->sd02_i_codnivhier = "0" ; 
        } 
       $sql  .= $virgula." sd02_i_codnivhier = $this->sd02_i_codnivhier ";
       $virgula = ",";
     }
     if(trim($this->sd02_i_diretor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_diretor"])){ 
        if(trim($this->sd02_i_diretor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_diretor"])){ 
           $this->sd02_i_diretor = "null" ; 
        } 
       $sql  .= $virgula." sd02_i_diretor = $this->sd02_i_diretor ";
       $virgula = ",";
     }
     if(trim($this->sd02_v_diretorreg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_v_diretorreg"])){ 
       $sql  .= $virgula." sd02_v_diretorreg = '$this->sd02_v_diretorreg' ";
       $virgula = ",";
     }
     if(trim($this->sd02_c_centralagenda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_c_centralagenda"])){ 
       $sql  .= $virgula." sd02_c_centralagenda = '$this->sd02_c_centralagenda' ";
       $virgula = ",";
       if(trim($this->sd02_c_centralagenda) == null ){ 
         $this->erro_sql = " Campo Central de Agendamento não informado.";
         $this->erro_campo = "sd02_c_centralagenda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd02_cnpjcpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd02_cnpjcpf"])){ 
       $sql  .= $virgula." sd02_cnpjcpf = '$this->sd02_cnpjcpf' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($sd02_i_codigo!=null){
       $sql .= " sd02_i_codigo = $this->sd02_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd02_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,100033,'$this->sd02_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_codigo"]) || $this->sd02_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,100011,100033,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_codigo'))."','$this->sd02_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_numcgm"]) || $this->sd02_i_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,100011,11420,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_numcgm'))."','$this->sd02_i_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_situacao"]) || $this->sd02_i_situacao != "")
             $resac = db_query("insert into db_acount values($acount,100011,11389,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_situacao'))."','$this->sd02_i_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_v_cnes"]) || $this->sd02_v_cnes != "")
             $resac = db_query("insert into db_acount values($acount,100011,11388,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_v_cnes'))."','$this->sd02_v_cnes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cidade"]) || $this->sd02_i_cidade != "")
             $resac = db_query("insert into db_acount values($acount,100011,100046,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_cidade'))."','$this->sd02_i_cidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_regiao"]) || $this->sd02_i_regiao != "")
             $resac = db_query("insert into db_acount values($acount,100011,100045,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_regiao'))."','$this->sd02_i_regiao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_v_microreg"]) || $this->sd02_v_microreg != "")
             $resac = db_query("insert into db_acount values($acount,100011,11390,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_v_microreg'))."','$this->sd02_v_microreg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_v_distsant"]) || $this->sd02_v_distsant != "")
             $resac = db_query("insert into db_acount values($acount,100011,11391,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_v_distsant'))."','$this->sd02_v_distsant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_v_distadmin"]) || $this->sd02_v_distadmin != "")
             $resac = db_query("insert into db_acount values($acount,100011,11392,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_v_distadmin'))."','$this->sd02_v_distadmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_distrito"]) || $this->sd02_i_distrito != "")
             $resac = db_query("insert into db_acount values($acount,100011,100044,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_distrito'))."','$this->sd02_i_distrito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_c_siasus"]) || $this->sd02_c_siasus != "")
             $resac = db_query("insert into db_acount values($acount,100011,100047,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_c_siasus'))."','$this->sd02_c_siasus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_esfadm"]) || $this->sd02_i_cod_esfadm != "")
             $resac = db_query("insert into db_acount values($acount,100011,11393,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_cod_esfadm'))."','$this->sd02_i_cod_esfadm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_ativ"]) || $this->sd02_i_cod_ativ != "")
             $resac = db_query("insert into db_acount values($acount,100011,11394,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_cod_ativ'))."','$this->sd02_i_cod_ativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_reten_trib"]) || $this->sd02_i_reten_trib != "")
             $resac = db_query("insert into db_acount values($acount,100011,11395,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_reten_trib'))."','$this->sd02_i_reten_trib',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_natorg"]) || $this->sd02_i_cod_natorg != "")
             $resac = db_query("insert into db_acount values($acount,100011,11396,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_cod_natorg'))."','$this->sd02_i_cod_natorg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_client"]) || $this->sd02_i_cod_client != "")
             $resac = db_query("insert into db_acount values($acount,100011,11397,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_cod_client'))."','$this->sd02_i_cod_client',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_v_num_alvara"]) || $this->sd02_v_num_alvara != "")
             $resac = db_query("insert into db_acount values($acount,100011,11398,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_v_num_alvara'))."','$this->sd02_v_num_alvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_d_data_exped"]) || $this->sd02_d_data_exped != "")
             $resac = db_query("insert into db_acount values($acount,100011,11399,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_d_data_exped'))."','$this->sd02_d_data_exped',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_v_ind_orgexp"]) || $this->sd02_v_ind_orgexp != "")
             $resac = db_query("insert into db_acount values($acount,100011,11400,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_v_ind_orgexp'))."','$this->sd02_v_ind_orgexp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_tp_unid_id"]) || $this->sd02_i_tp_unid_id != "")
             $resac = db_query("insert into db_acount values($acount,100011,11401,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_tp_unid_id'))."','$this->sd02_i_tp_unid_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_cod_turnat"]) || $this->sd02_i_cod_turnat != "")
             $resac = db_query("insert into db_acount values($acount,100011,11402,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_cod_turnat'))."','$this->sd02_i_cod_turnat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_codnivhier"]) || $this->sd02_i_codnivhier != "")
             $resac = db_query("insert into db_acount values($acount,100011,11403,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_codnivhier'))."','$this->sd02_i_codnivhier',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_i_diretor"]) || $this->sd02_i_diretor != "")
             $resac = db_query("insert into db_acount values($acount,100011,11729,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_i_diretor'))."','$this->sd02_i_diretor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_v_diretorreg"]) || $this->sd02_v_diretorreg != "")
             $resac = db_query("insert into db_acount values($acount,100011,11730,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_v_diretorreg'))."','$this->sd02_v_diretorreg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_c_centralagenda"]) || $this->sd02_c_centralagenda != "")
             $resac = db_query("insert into db_acount values($acount,100011,13760,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_c_centralagenda'))."','$this->sd02_c_centralagenda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd02_cnpjcpf"]) || $this->sd02_cnpjcpf != "")
             $resac = db_query("insert into db_acount values($acount,100011,21136,'".AddSlashes(pg_result($resaco,$conresaco,'sd02_cnpjcpf'))."','$this->sd02_cnpjcpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidades não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd02_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Unidades não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($sd02_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd02_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,100033,'$sd02_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,100011,100033,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11420,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11389,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11388,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_v_cnes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,100046,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,100045,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_regiao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11390,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_v_microreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11391,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_v_distsant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11392,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_v_distadmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,100044,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_distrito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,100047,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_c_siasus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11393,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_cod_esfadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11394,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_cod_ativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11395,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_reten_trib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11396,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_cod_natorg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11397,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_cod_client'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11398,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_v_num_alvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11399,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_d_data_exped'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11400,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_v_ind_orgexp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11401,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_tp_unid_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11402,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_cod_turnat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11403,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_codnivhier'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11729,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_i_diretor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,11730,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_v_diretorreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,13760,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_c_centralagenda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,100011,21136,'','".AddSlashes(pg_result($resaco,$iresaco,'sd02_cnpjcpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from unidades
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd02_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd02_i_codigo = $sd02_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidades não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd02_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Unidades não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:unidades";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($sd02_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from unidades ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = unidades.sd02_i_numcgm ";
     $sql .= "      left join cgm diretorcgm on  diretorcgm.z01_numcgm = unidades.sd02_i_diretor ";
     $sql .= "      left join sau_esferaadmin  on  sau_esferaadmin.sd37_i_cod_esfadm = unidades.sd02_i_cod_esfadm";
     $sql .= "      left join sau_atividadeensino  on  sau_atividadeensino.sd38_i_cod_ativid = unidades.sd02_i_cod_ativ";
     $sql .= "      left join sau_retentributo  on  sau_retentributo.sd39_i_cod_reten = unidades.sd02_i_reten_trib";
     $sql .= "      left join sau_natorg  on  sau_natorg.sd40_i_cod_natorg = unidades.sd02_i_cod_natorg";
     $sql .= "      left join sau_fluxocliente  on  sau_fluxocliente.sd41_i_cod_cliente = unidades.sd02_i_cod_client";
     $sql .= "      left join sau_tipounidade  on  sau_tipounidade.sd42_i_tp_unid_id = unidades.sd02_i_tp_unid_id";
     $sql .= "      left join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = unidades.sd02_i_cod_turnat";
     $sql .= "      left join sau_nivelhier  on  sau_nivelhier.sd44_i_codnivhier = unidades.sd02_i_codnivhier";
     $sql .= "      left  join sau_distritosanitario  on  sau_distritosanitario.s153_i_codigo = unidades.sd02_i_distrito";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd02_i_codigo)) {
         $sql2 .= " where unidades.sd02_i_codigo = $sd02_i_codigo "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($sd02_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from unidades ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd02_i_codigo)){
         $sql2 .= " where unidades.sd02_i_codigo = $sd02_i_codigo "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

   function sql_query_model ($sd02_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {
    
    $sql = "select ";
    if ($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula    = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from unidades ";
    $sql .= "      inner join sau_tipounidade  on  sau_tipounidade.sd42_i_tp_unid_id = unidades.sd02_i_tp_unid_id";
    $sql2 = "";
    if ($dbwhere == "") {
      
      if ($sd02_i_codigo != null) {
        $sql2 .= " where unidades.sd02_i_codigo = $sd02_i_codigo ";
      }
    } else if ($dbwhere != "") {
      
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      
      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
