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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhcadregimefaltasperiodoaquisitivo
class cl_rhcadregimefaltasperiodoaquisitivo { 
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
   var $rh125_sequencial = 0; 
   var $rh125_rhcadregime = 0; 
   var $rh125_faixainicial = 0; 
   var $rh125_faixafinal = 0; 
   var $rh125_diasdesconto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh125_sequencial = int4 = Sequencial da Tabela 
                 rh125_rhcadregime = int4 = Regime 
                 rh125_faixainicial = int4 = Mínimo Faltas 
                 rh125_faixafinal = int4 = Máximo de Faltas 
                 rh125_diasdesconto = int4 = Dias descontados 
                 ";
   //funcao construtor da classe 
   function cl_rhcadregimefaltasperiodoaquisitivo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhcadregimefaltasperiodoaquisitivo"); 
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
       $this->rh125_sequencial = ($this->rh125_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh125_sequencial"]:$this->rh125_sequencial);
       $this->rh125_rhcadregime = ($this->rh125_rhcadregime == ""?@$GLOBALS["HTTP_POST_VARS"]["rh125_rhcadregime"]:$this->rh125_rhcadregime);
       $this->rh125_faixainicial = ($this->rh125_faixainicial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh125_faixainicial"]:$this->rh125_faixainicial);
       $this->rh125_faixafinal = ($this->rh125_faixafinal == ""?@$GLOBALS["HTTP_POST_VARS"]["rh125_faixafinal"]:$this->rh125_faixafinal);
       $this->rh125_diasdesconto = ($this->rh125_diasdesconto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh125_diasdesconto"]:$this->rh125_diasdesconto);
     }else{
       $this->rh125_sequencial = ($this->rh125_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh125_sequencial"]:$this->rh125_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh125_sequencial){ 
      $this->atualizacampos();
     if($this->rh125_rhcadregime == null ){ 
       $this->rh125_rhcadregime = "0";
     }
     if($this->rh125_faixainicial == null ){ 
       $this->erro_sql = " Campo Mínimo Faltas não informado.";
       $this->erro_campo = "rh125_faixainicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh125_faixafinal == null ){ 
       $this->erro_sql = " Campo Máximo de Faltas não informado.";
       $this->erro_campo = "rh125_faixafinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh125_diasdesconto == null ){ 
       $this->erro_sql = " Campo Dias descontados não informado.";
       $this->erro_campo = "rh125_diasdesconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh125_sequencial == "" || $rh125_sequencial == null ){
       $result = db_query("select nextval('rhcadregimefaltasperiodoaquisitivo_rh125_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhcadregimefaltasperiodoaquisitivo_rh125_sequencial_seq do campo: rh125_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh125_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhcadregimefaltasperiodoaquisitivo_rh125_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh125_sequencial)){
         $this->erro_sql = " Campo rh125_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh125_sequencial = $rh125_sequencial; 
       }
     }
     if(($this->rh125_sequencial == null) || ($this->rh125_sequencial == "") ){ 
       $this->erro_sql = " Campo rh125_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhcadregimefaltasperiodoaquisitivo(
                                       rh125_sequencial 
                                      ,rh125_rhcadregime 
                                      ,rh125_faixainicial 
                                      ,rh125_faixafinal 
                                      ,rh125_diasdesconto 
                       )
                values (
                                $this->rh125_sequencial 
                               ,$this->rh125_rhcadregime 
                               ,$this->rh125_faixainicial 
                               ,$this->rh125_faixafinal 
                               ,$this->rh125_diasdesconto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Faltas Periodo Aquisitivo ($this->rh125_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Faltas Periodo Aquisitivo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Faltas Periodo Aquisitivo ($this->rh125_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh125_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh125_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20223,'$this->rh125_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3633,20223,'','".AddSlashes(pg_result($resaco,0,'rh125_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3633,20224,'','".AddSlashes(pg_result($resaco,0,'rh125_rhcadregime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3633,20225,'','".AddSlashes(pg_result($resaco,0,'rh125_faixainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3633,20226,'','".AddSlashes(pg_result($resaco,0,'rh125_faixafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3633,20227,'','".AddSlashes(pg_result($resaco,0,'rh125_diasdesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh125_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhcadregimefaltasperiodoaquisitivo set ";
     $virgula = "";
     if(trim($this->rh125_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh125_sequencial"])){ 
       $sql  .= $virgula." rh125_sequencial = $this->rh125_sequencial ";
       $virgula = ",";
       if(trim($this->rh125_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial da Tabela não informado.";
         $this->erro_campo = "rh125_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh125_rhcadregime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh125_rhcadregime"])){ 
        if(trim($this->rh125_rhcadregime)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh125_rhcadregime"])){ 
           $this->rh125_rhcadregime = "0" ; 
        } 
       $sql  .= $virgula." rh125_rhcadregime = $this->rh125_rhcadregime ";
       $virgula = ",";
     }
     if(trim($this->rh125_faixainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh125_faixainicial"])){ 
       $sql  .= $virgula." rh125_faixainicial = $this->rh125_faixainicial ";
       $virgula = ",";
       if(trim($this->rh125_faixainicial) == null ){ 
         $this->erro_sql = " Campo Mínimo Faltas não informado.";
         $this->erro_campo = "rh125_faixainicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh125_faixafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh125_faixafinal"])){ 
       $sql  .= $virgula." rh125_faixafinal = $this->rh125_faixafinal ";
       $virgula = ",";
       if(trim($this->rh125_faixafinal) == null ){ 
         $this->erro_sql = " Campo Máximo de Faltas não informado.";
         $this->erro_campo = "rh125_faixafinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh125_diasdesconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh125_diasdesconto"])){ 
       $sql  .= $virgula." rh125_diasdesconto = $this->rh125_diasdesconto ";
       $virgula = ",";
       if(trim($this->rh125_diasdesconto) == null ){ 
         $this->erro_sql = " Campo Dias descontados não informado.";
         $this->erro_campo = "rh125_diasdesconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh125_sequencial!=null){
       $sql .= " rh125_sequencial = $this->rh125_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh125_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20223,'$this->rh125_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh125_sequencial"]) || $this->rh125_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3633,20223,'".AddSlashes(pg_result($resaco,$conresaco,'rh125_sequencial'))."','$this->rh125_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh125_rhcadregime"]) || $this->rh125_rhcadregime != "")
             $resac = db_query("insert into db_acount values($acount,3633,20224,'".AddSlashes(pg_result($resaco,$conresaco,'rh125_rhcadregime'))."','$this->rh125_rhcadregime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh125_faixainicial"]) || $this->rh125_faixainicial != "")
             $resac = db_query("insert into db_acount values($acount,3633,20225,'".AddSlashes(pg_result($resaco,$conresaco,'rh125_faixainicial'))."','$this->rh125_faixainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh125_faixafinal"]) || $this->rh125_faixafinal != "")
             $resac = db_query("insert into db_acount values($acount,3633,20226,'".AddSlashes(pg_result($resaco,$conresaco,'rh125_faixafinal'))."','$this->rh125_faixafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh125_diasdesconto"]) || $this->rh125_diasdesconto != "")
             $resac = db_query("insert into db_acount values($acount,3633,20227,'".AddSlashes(pg_result($resaco,$conresaco,'rh125_diasdesconto'))."','$this->rh125_diasdesconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Faltas Periodo Aquisitivo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh125_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Faltas Periodo Aquisitivo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh125_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh125_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh125_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh125_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20223,'$rh125_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3633,20223,'','".AddSlashes(pg_result($resaco,$iresaco,'rh125_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3633,20224,'','".AddSlashes(pg_result($resaco,$iresaco,'rh125_rhcadregime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3633,20225,'','".AddSlashes(pg_result($resaco,$iresaco,'rh125_faixainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3633,20226,'','".AddSlashes(pg_result($resaco,$iresaco,'rh125_faixafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3633,20227,'','".AddSlashes(pg_result($resaco,$iresaco,'rh125_diasdesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhcadregimefaltasperiodoaquisitivo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh125_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh125_sequencial = $rh125_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Faltas Periodo Aquisitivo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh125_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Faltas Periodo Aquisitivo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh125_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh125_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhcadregimefaltasperiodoaquisitivo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh125_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhcadregimefaltasperiodoaquisitivo ";
     $sql .= "      left  join rhcadregime  on  rhcadregime.rh52_regime = rhcadregimefaltasperiodoaquisitivo.rh125_rhcadregime";
     $sql2 = "";
     if($dbwhere==""){
       if($rh125_sequencial!=null ){
         $sql2 .= " where rhcadregimefaltasperiodoaquisitivo.rh125_sequencial = $rh125_sequencial "; 
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
   function sql_query_file ( $rh125_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhcadregimefaltasperiodoaquisitivo ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh125_sequencial!=null ){
         $sql2 .= " where rhcadregimefaltasperiodoaquisitivo.rh125_sequencial = $rh125_sequencial "; 
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
    * Query que Valida se existe falta cadastrada para a faixa informada
    */
   function sql_query_faixas_periodos( $rh125_sequencial=null ){

     $sSql  = "select count( rh109_regist ) as total_registros																										 ";
     $sSql .= "  from rhferias             																																				 ";
     $sSql .= " where exists ( select 1 																																					 ";
		 $sSql .= "						 		   from rhcadregimefaltasperiodoaquisitivo																					 "; 
		 $sSql .= "								  where rh109_faltasperiodoaquisitivo between rh125_faixainicial and rh125_faixafinal"; 
		 $sSql .= "								    and rh125_sequencial = {$rh125_sequencial})																			 ";
   	
		 return $sSql;
   }
   
}
?>