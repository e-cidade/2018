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
//CLASSE DA ENTIDADE econsigmovimento
class cl_econsigmovimento { 
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
   var $rh133_sequencial = 0; 
   var $rh133_ano = 0; 
   var $rh133_mes = 0; 
   var $rh133_nomearquivo = null; 
   var $rh133_instit = 0; 
   var $rh133_relatorio = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh133_sequencial = int4 = Sequencial 
                 rh133_ano = int4 = Ano 
                 rh133_mes = int4 = Mês 
                 rh133_nomearquivo = varchar(100) = Nome do Arquivo 
                 rh133_instit = int4 = Instituição 
                 rh133_relatorio = oid = Relatório de  Importação 
                 ";
   //funcao construtor da classe 
   function cl_econsigmovimento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("econsigmovimento"); 
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
       $this->rh133_sequencial  = ($this->rh133_sequencial  == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh133_sequencial"]    : $this->rh133_sequencial);
       $this->rh133_ano         = ($this->rh133_ano         == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh133_ano"]           : $this->rh133_ano);
       $this->rh133_mes         = ($this->rh133_mes         == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh133_mes"]           : $this->rh133_mes);
       $this->rh133_nomearquivo = ($this->rh133_nomearquivo == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh133_nomearquivo"]   : $this->rh133_nomearquivo);
       $this->rh133_instit      = ($this->rh133_instit      == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh133_instit"]        : $this->rh133_instit);
       $this->rh133_relatorio   = ($this->rh133_relatorio   == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh133_relatorio"]     : $this->rh133_relatorio);

       $this->rh133_relatorio   = empty($this->rh133_relatorio) ? "NULL" : $this->rh133_relatorio;

     }else{
       $this->rh133_sequencial = ($this->rh133_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh133_sequencial"]:$this->rh133_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh133_sequencial){ 
      $this->atualizacampos();
     if($this->rh133_ano == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "rh133_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh133_mes == null ){ 
       $this->erro_sql = " Campo Mês não informado.";
       $this->erro_campo = "rh133_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh133_nomearquivo == null ){ 
       $this->erro_sql = " Campo Nome do Arquivo não informado.";
       $this->erro_campo = "rh133_nomearquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh133_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh133_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh133_sequencial == "" || $rh133_sequencial == null ){
       $result = db_query("select nextval('econsigmovimento_rh133_sequencia_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: econsigmovimento_rh133_sequencia_seq do campo: rh133_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh133_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from econsigmovimento_rh133_sequencia_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh133_sequencial)){
         $this->erro_sql = " Campo rh133_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh133_sequencial = $rh133_sequencial; 
       }
     }
     if(($this->rh133_sequencial == null) || ($this->rh133_sequencial == "") ){ 
       $this->erro_sql = " Campo rh133_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into econsigmovimento(
                                       rh133_sequencial 
                                      ,rh133_ano 
                                      ,rh133_mes 
                                      ,rh133_nomearquivo 
                                      ,rh133_instit 
                                      ,rh133_relatorio 
                       )
                values (
                                $this->rh133_sequencial 
                               ,$this->rh133_ano 
                               ,$this->rh133_mes 
                               ,'$this->rh133_nomearquivo' 
                               ,$this->rh133_instit 
                               ,$this->rh133_relatorio 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "E-CONSIG Movimento ($this->rh133_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "E-CONSIG Movimento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "E-CONSIG Movimento ($this->rh133_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh133_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh133_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20442,'$this->rh133_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3675,20442,'','".AddSlashes(pg_result($resaco,0,'rh133_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3675,20443,'','".AddSlashes(pg_result($resaco,0,'rh133_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3675,20444,'','".AddSlashes(pg_result($resaco,0,'rh133_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3675,20445,'','".AddSlashes(pg_result($resaco,0,'rh133_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3675,20446,'','".AddSlashes(pg_result($resaco,0,'rh133_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3675,20873,'','".AddSlashes(pg_result($resaco,0,'rh133_relatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh133_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update econsigmovimento set ";
     $virgula = "";
     if(trim($this->rh133_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh133_sequencial"])){ 
       $sql  .= $virgula." rh133_sequencial = $this->rh133_sequencial ";
       $virgula = ",";
       if(trim($this->rh133_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh133_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh133_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh133_ano"])){ 
       $sql  .= $virgula." rh133_ano = $this->rh133_ano ";
       $virgula = ",";
       if(trim($this->rh133_ano) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "rh133_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh133_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh133_mes"])){ 
       $sql  .= $virgula." rh133_mes = $this->rh133_mes ";
       $virgula = ",";
       if(trim($this->rh133_mes) == null ){ 
         $this->erro_sql = " Campo Mês não informado.";
         $this->erro_campo = "rh133_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh133_nomearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh133_nomearquivo"])){ 
       $sql  .= $virgula." rh133_nomearquivo = '$this->rh133_nomearquivo' ";
       $virgula = ",";
       if(trim($this->rh133_nomearquivo) == null ){ 
         $this->erro_sql = " Campo Nome do Arquivo não informado.";
         $this->erro_campo = "rh133_nomearquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh133_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh133_instit"])){ 
       $sql  .= $virgula." rh133_instit = $this->rh133_instit ";
       $virgula = ",";
       if(trim($this->rh133_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh133_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh133_relatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh133_relatorio"])){ 
       $sql  .= $virgula." rh133_relatorio = $this->rh133_relatorio ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh133_sequencial!=null){
       $sql .= " rh133_sequencial = $this->rh133_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh133_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20442,'$this->rh133_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh133_sequencial"]) || $this->rh133_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3675,20442,'".AddSlashes(pg_result($resaco,$conresaco,'rh133_sequencial'))."','$this->rh133_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh133_ano"]) || $this->rh133_ano != "")
             $resac = db_query("insert into db_acount values($acount,3675,20443,'".AddSlashes(pg_result($resaco,$conresaco,'rh133_ano'))."','$this->rh133_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh133_mes"]) || $this->rh133_mes != "")
             $resac = db_query("insert into db_acount values($acount,3675,20444,'".AddSlashes(pg_result($resaco,$conresaco,'rh133_mes'))."','$this->rh133_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh133_nomearquivo"]) || $this->rh133_nomearquivo != "")
             $resac = db_query("insert into db_acount values($acount,3675,20445,'".AddSlashes(pg_result($resaco,$conresaco,'rh133_nomearquivo'))."','$this->rh133_nomearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh133_instit"]) || $this->rh133_instit != "")
             $resac = db_query("insert into db_acount values($acount,3675,20446,'".AddSlashes(pg_result($resaco,$conresaco,'rh133_instit'))."','$this->rh133_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh133_relatorio"]) || $this->rh133_relatorio != "")
             $resac = db_query("insert into db_acount values($acount,3675,20873,'".AddSlashes(pg_result($resaco,$conresaco,'rh133_relatorio'))."','$this->rh133_relatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "E-CONSIG Movimento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh133_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "E-CONSIG Movimento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh133_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh133_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh133_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh133_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20442,'$rh133_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3675,20442,'','".AddSlashes(pg_result($resaco,$iresaco,'rh133_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3675,20443,'','".AddSlashes(pg_result($resaco,$iresaco,'rh133_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3675,20444,'','".AddSlashes(pg_result($resaco,$iresaco,'rh133_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3675,20445,'','".AddSlashes(pg_result($resaco,$iresaco,'rh133_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3675,20446,'','".AddSlashes(pg_result($resaco,$iresaco,'rh133_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3675,20873,'','".AddSlashes(pg_result($resaco,$iresaco,'rh133_relatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from econsigmovimento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh133_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh133_sequencial = $rh133_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "E-CONSIG Movimento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh133_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "E-CONSIG Movimento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh133_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh133_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:econsigmovimento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh133_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from econsigmovimento ";
     $sql .= "      inner join db_config  on  db_config.codigo = econsigmovimento.rh133_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh133_sequencial)) {
         $sql2 .= " where econsigmovimento.rh133_sequencial = $rh133_sequencial "; 
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
   public function sql_query_file ($rh133_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from econsigmovimento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh133_sequencial)){
         $sql2 .= " where econsigmovimento.rh133_sequencial = $rh133_sequencial "; 
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

  public function sql_pesquisa_servidor($iMatricula = null, $iAnoUsu, $iMesUsu, $iInstit) {

    $sSql  = "   SELECT econsigmovimentoservidor.rh134_regist as matricula,                                                                                            ";
    $sSql .= "      econsigmovimentoservidorrubrica.rh135_rubrica as rubrica,                                                                                          "; 
    $sSql .= "      econsigmovimentoservidor.rh134_nome as nome,                                                                                                       "; 
    $sSql .= "      sum(econsigmovimentoservidorrubrica.rh135_valor) as valor                                                                                          ";                                                   
    $sSql .= " FROM econsigmovimento                                                                                                                                   ";                  
    $sSql .= "INNER JOIN econsigmovimentoservidor        ON econsigmovimento.rh133_sequencial         = econsigmovimentoservidor.rh134_econsigmovimento                ";
    $sSql .= "LEFT  JOIN econsigmovimentoservidorrubrica ON econsigmovimentoservidor.rh134_sequencial = econsigmovimentoservidorrubrica.rh135_econsigmovimentoservidor ";
    $sSql .= "WHERE econsigmovimento.rh133_sequencial = (SELECT max(rh133_sequencial)                                                                                  ";
    $sSql .= "                                             FROM econsigmovimento)                                                                                      ";
    $sSql .= "  AND econsigmovimento.rh133_ano    = {$iAnoUsu}                                                                                                         ";
    $sSql .= "  AND econsigmovimento.rh133_mes    = {$iMesUsu}                                                                                                         ";
    $sSql .= "  AND econsigmovimento.rh133_instit = {$iInstit}                                                                                                         ";

    if (!is_null($iMatricula)) {
      $sSql .= "  AND econsigmovimentoservidor.rh134_regist = {$iMatricula}                                                                                            ";
    }

    $sSql .= "GROUP BY econsigmovimentoservidor.rh134_regist,                                                                                                          ";
    $sSql .= "         econsigmovimentoservidor.rh134_nome,                                                                                                          ";
    $sSql .= "         econsigmovimentoservidorrubrica.rh135_rubrica;                                                                                                  ";

    return $sSql;
  }
}
