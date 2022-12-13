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

//MODULO: pessoal
//CLASSE DA ENTIDADE eventofinanceiroautomatico
class cl_eventofinanceiroautomatico { 
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
   var $rh181_sequencial = 0; 
   var $rh181_descricao = null; 
   var $rh181_rubrica = null; 
   var $rh181_mes = 0; 
   var $rh181_selecao = 0; 
   var $rh181_instituicao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh181_sequencial = int4 = Sequêncial 
                 rh181_descricao = varchar(56) = Descrição 
                 rh181_rubrica = varchar(4) = Rubrica 
                 rh181_mes = int4 = Mês 
                 rh181_selecao = int4 = Seleção 
                 rh181_instituicao = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_eventofinanceiroautomatico() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("eventofinanceiroautomatico"); 
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
       $this->rh181_sequencial = ($this->rh181_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh181_sequencial"]:$this->rh181_sequencial);
       $this->rh181_descricao = ($this->rh181_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh181_descricao"]:$this->rh181_descricao);
       $this->rh181_rubrica = ($this->rh181_rubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh181_rubrica"]:$this->rh181_rubrica);
       $this->rh181_mes = ($this->rh181_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh181_mes"]:$this->rh181_mes);
       $this->rh181_selecao = ($this->rh181_selecao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh181_selecao"]:$this->rh181_selecao);
       $this->rh181_instituicao = ($this->rh181_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh181_instituicao"]:$this->rh181_instituicao);
     }else{
       $this->rh181_sequencial = ($this->rh181_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh181_sequencial"]:$this->rh181_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh181_sequencial){ 
      $this->atualizacampos();
     if($this->rh181_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "rh181_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh181_rubrica == null ){ 
       $this->erro_sql = " Campo Rubrica não informado.";
       $this->erro_campo = "rh181_rubrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh181_mes == null ){ 
       $this->erro_sql = " Campo Mês não informado.";
       $this->erro_campo = "rh181_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh181_selecao == null ){ 
       $this->erro_sql = " Campo Seleção não informado.";
       $this->erro_campo = "rh181_selecao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh181_instituicao == null ){ 
       $this->erro_sql = " Campo Intituição não informado.";
       $this->erro_campo = "rh181_instituicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh181_sequencial == "" || $rh181_sequencial == null ){
       $result = db_query("select nextval('eventofinanceiroautomatico_rh181_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: eventofinanceiroautomatico_rh181_sequencial_seq do campo: rh181_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh181_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from eventofinanceiroautomatico_rh181_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh181_sequencial)){
         $this->erro_sql = " Campo rh181_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh181_sequencial = $rh181_sequencial; 
       }
     }
     if(($this->rh181_sequencial == null) || ($this->rh181_sequencial == "") ){ 
       $this->erro_sql = " Campo rh181_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into eventofinanceiroautomatico(
                                       rh181_sequencial 
                                      ,rh181_descricao 
                                      ,rh181_rubrica 
                                      ,rh181_mes 
                                      ,rh181_selecao 
                                      ,rh181_instituicao 
                       )
                values (
                                $this->rh181_sequencial 
                               ,'$this->rh181_descricao' 
                               ,'$this->rh181_rubrica' 
                               ,$this->rh181_mes 
                               ,$this->rh181_selecao 
                               ,$this->rh181_instituicao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Evento Financeiro Automatico ($this->rh181_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Evento Financeiro Automatico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Evento Financeiro Automatico ($this->rh181_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh181_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh181_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21972,'$this->rh181_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3955,21972,'','".AddSlashes(pg_result($resaco,0,'rh181_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3955,21973,'','".AddSlashes(pg_result($resaco,0,'rh181_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3955,21974,'','".AddSlashes(pg_result($resaco,0,'rh181_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3955,21975,'','".AddSlashes(pg_result($resaco,0,'rh181_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3955,21976,'','".AddSlashes(pg_result($resaco,0,'rh181_selecao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3955,21977,'','".AddSlashes(pg_result($resaco,0,'rh181_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh181_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update eventofinanceiroautomatico set ";
     $virgula = "";
     if(trim($this->rh181_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh181_sequencial"])){ 
       $sql  .= $virgula." rh181_sequencial = $this->rh181_sequencial ";
       $virgula = ",";
       if(trim($this->rh181_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequêncial não informado.";
         $this->erro_campo = "rh181_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh181_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh181_descricao"])){ 
       $sql  .= $virgula." rh181_descricao = '$this->rh181_descricao' ";
       $virgula = ",";
       if(trim($this->rh181_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "rh181_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh181_rubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh181_rubrica"])){ 
       $sql  .= $virgula." rh181_rubrica = '$this->rh181_rubrica' ";
       $virgula = ",";
       if(trim($this->rh181_rubrica) == null ){ 
         $this->erro_sql = " Campo Rubrica não informado.";
         $this->erro_campo = "rh181_rubrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh181_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh181_mes"])){ 
       $sql  .= $virgula." rh181_mes = $this->rh181_mes ";
       $virgula = ",";
       if(trim($this->rh181_mes) == null ){ 
         $this->erro_sql = " Campo Mês não informado.";
         $this->erro_campo = "rh181_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh181_selecao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh181_selecao"])){ 
       $sql  .= $virgula." rh181_selecao = $this->rh181_selecao ";
       $virgula = ",";
       if(trim($this->rh181_selecao) == null ){ 
         $this->erro_sql = " Campo Seleção não informado.";
         $this->erro_campo = "rh181_selecao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh181_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh181_instituicao"])){ 
       $sql  .= $virgula." rh181_instituicao = $this->rh181_instituicao ";
       $virgula = ",";
       if(trim($this->rh181_instituicao) == null ){ 
         $this->erro_sql = " Campo Intituição não informado.";
         $this->erro_campo = "rh181_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh181_sequencial!=null){
       $sql .= " rh181_sequencial = $this->rh181_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh181_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21972,'$this->rh181_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh181_sequencial"]) || $this->rh181_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3955,21972,'".AddSlashes(pg_result($resaco,$conresaco,'rh181_sequencial'))."','$this->rh181_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh181_descricao"]) || $this->rh181_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3955,21973,'".AddSlashes(pg_result($resaco,$conresaco,'rh181_descricao'))."','$this->rh181_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh181_rubrica"]) || $this->rh181_rubrica != "")
             $resac = db_query("insert into db_acount values($acount,3955,21974,'".AddSlashes(pg_result($resaco,$conresaco,'rh181_rubrica'))."','$this->rh181_rubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh181_mes"]) || $this->rh181_mes != "")
             $resac = db_query("insert into db_acount values($acount,3955,21975,'".AddSlashes(pg_result($resaco,$conresaco,'rh181_mes'))."','$this->rh181_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh181_selecao"]) || $this->rh181_selecao != "")
             $resac = db_query("insert into db_acount values($acount,3955,21976,'".AddSlashes(pg_result($resaco,$conresaco,'rh181_selecao'))."','$this->rh181_selecao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh181_instituicao"]) || $this->rh181_instituicao != "")
             $resac = db_query("insert into db_acount values($acount,3955,21977,'".AddSlashes(pg_result($resaco,$conresaco,'rh181_instituicao'))."','$this->rh181_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Evento Financeiro Automatico não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh181_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Evento Financeiro Automatico não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh181_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh181_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh181_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh181_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21972,'$rh181_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3955,21972,'','".AddSlashes(pg_result($resaco,$iresaco,'rh181_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3955,21973,'','".AddSlashes(pg_result($resaco,$iresaco,'rh181_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3955,21974,'','".AddSlashes(pg_result($resaco,$iresaco,'rh181_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3955,21975,'','".AddSlashes(pg_result($resaco,$iresaco,'rh181_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3955,21976,'','".AddSlashes(pg_result($resaco,$iresaco,'rh181_selecao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3955,21977,'','".AddSlashes(pg_result($resaco,$iresaco,'rh181_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from eventofinanceiroautomatico
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh181_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh181_sequencial = $rh181_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Evento Financeiro Automatico não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh181_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Evento Financeiro Automatico não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh181_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh181_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:eventofinanceiroautomatico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh181_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from eventofinanceiroautomatico ";
     $sql .= "      inner join db_config  on  db_config.codigo = eventofinanceiroautomatico.rh181_instituicao";
     $sql .= "      inner join selecao  on  selecao.r44_selec = eventofinanceiroautomatico.rh181_selecao and  selecao.r44_instit = eventofinanceiroautomatico.rh181_instituicao";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = eventofinanceiroautomatico.rh181_rubrica and  rhrubricas.rh27_instit = eventofinanceiroautomatico.rh181_instituicao";
     
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh181_sequencial)) {
         $sql2 .= " where eventofinanceiroautomatico.rh181_sequencial = $rh181_sequencial "; 
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
   public function sql_query_file ($rh181_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from eventofinanceiroautomatico ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh181_sequencial)){
         $sql2 .= " where eventofinanceiroautomatico.rh181_sequencial = $rh181_sequencial "; 
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

}
