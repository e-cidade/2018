<?
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
//CLASSE DA ENTIDADE rhhistoricopensao
class cl_rhhistoricopensao { 
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
   var $rh145_sequencial = 0; 
   var $rh145_pensao = 0; 
   var $rh145_valor = 0; 
   var $rh145_rhfolhapagamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh145_sequencial = int4 = Sequencial 
                 rh145_pensao = int4 = Pensão 
                 rh145_valor = float4 = Valor 
                 rh145_rhfolhapagamento = int4 = Folha de Pagamento 
                 ";
   //funcao construtor da classe 
   function cl_rhhistoricopensao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhhistoricopensao"); 
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
       $this->rh145_sequencial = ($this->rh145_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh145_sequencial"]:$this->rh145_sequencial);
       $this->rh145_pensao = ($this->rh145_pensao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh145_pensao"]:$this->rh145_pensao);
       $this->rh145_valor = ($this->rh145_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh145_valor"]:$this->rh145_valor);
       $this->rh145_rhfolhapagamento = ($this->rh145_rhfolhapagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh145_rhfolhapagamento"]:$this->rh145_rhfolhapagamento);
     }else{
       $this->rh145_sequencial = ($this->rh145_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh145_sequencial"]:$this->rh145_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh145_sequencial){ 
      $this->atualizacampos();
     if($this->rh145_pensao == null ){ 
       $this->erro_sql = " Campo Pensão não informado.";
       $this->erro_campo = "rh145_pensao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh145_valor == null ){ 
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "rh145_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh145_rhfolhapagamento == null ){ 
       $this->erro_sql = " Campo Folha de Pagamento não informado.";
       $this->erro_campo = "rh145_rhfolhapagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh145_sequencial == "" || $rh145_sequencial == null ){
       $result = db_query("select nextval('rhhistoricopensao_rh145_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhhistoricopensao_rh145_sequencial_seq do campo: rh145_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh145_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhhistoricopensao_rh145_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh145_sequencial)){
         $this->erro_sql = " Campo rh145_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh145_sequencial = $rh145_sequencial; 
       }
     }
     if(($this->rh145_sequencial == null) || ($this->rh145_sequencial == "") ){ 
       $this->erro_sql = " Campo rh145_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhhistoricopensao(
                                       rh145_sequencial 
                                      ,rh145_pensao 
                                      ,rh145_valor 
                                      ,rh145_rhfolhapagamento 
                       )
                values (
                                $this->rh145_sequencial 
                               ,$this->rh145_pensao 
                               ,$this->rh145_valor 
                               ,$this->rh145_rhfolhapagamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhhistoricopensao ($this->rh145_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhhistoricopensao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhhistoricopensao ($this->rh145_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh145_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh145_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20830,'$this->rh145_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3748,20830,'','".AddSlashes(pg_result($resaco,0,'rh145_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3748,20832,'','".AddSlashes(pg_result($resaco,0,'rh145_pensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3748,20833,'','".AddSlashes(pg_result($resaco,0,'rh145_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3748,20838,'','".AddSlashes(pg_result($resaco,0,'rh145_rhfolhapagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh145_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhhistoricopensao set ";
     $virgula = "";
     if(trim($this->rh145_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh145_sequencial"])){ 
       $sql  .= $virgula." rh145_sequencial = $this->rh145_sequencial ";
       $virgula = ",";
       if(trim($this->rh145_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh145_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh145_pensao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh145_pensao"])){ 
       $sql  .= $virgula." rh145_pensao = $this->rh145_pensao ";
       $virgula = ",";
       if(trim($this->rh145_pensao) == null ){ 
         $this->erro_sql = " Campo Pensão não informado.";
         $this->erro_campo = "rh145_pensao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh145_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh145_valor"])){ 
       $sql  .= $virgula." rh145_valor = $this->rh145_valor ";
       $virgula = ",";
       if(trim($this->rh145_valor) == null ){ 
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "rh145_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh145_rhfolhapagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh145_rhfolhapagamento"])){ 
       $sql  .= $virgula." rh145_rhfolhapagamento = $this->rh145_rhfolhapagamento ";
       $virgula = ",";
       if(trim($this->rh145_rhfolhapagamento) == null ){ 
         $this->erro_sql = " Campo Folha de Pagamento não informado.";
         $this->erro_campo = "rh145_rhfolhapagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh145_sequencial!=null){
       $sql .= " rh145_sequencial = $this->rh145_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh145_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20830,'$this->rh145_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh145_sequencial"]) || $this->rh145_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3748,20830,'".AddSlashes(pg_result($resaco,$conresaco,'rh145_sequencial'))."','$this->rh145_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh145_pensao"]) || $this->rh145_pensao != "")
             $resac = db_query("insert into db_acount values($acount,3748,20832,'".AddSlashes(pg_result($resaco,$conresaco,'rh145_pensao'))."','$this->rh145_pensao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh145_valor"]) || $this->rh145_valor != "")
             $resac = db_query("insert into db_acount values($acount,3748,20833,'".AddSlashes(pg_result($resaco,$conresaco,'rh145_valor'))."','$this->rh145_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh145_rhfolhapagamento"]) || $this->rh145_rhfolhapagamento != "")
             $resac = db_query("insert into db_acount values($acount,3748,20838,'".AddSlashes(pg_result($resaco,$conresaco,'rh145_rhfolhapagamento'))."','$this->rh145_rhfolhapagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhhistoricopensao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh145_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhhistoricopensao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh145_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh145_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh145_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh145_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20830,'$rh145_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3748,20830,'','".AddSlashes(pg_result($resaco,$iresaco,'rh145_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3748,20832,'','".AddSlashes(pg_result($resaco,$iresaco,'rh145_pensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3748,20833,'','".AddSlashes(pg_result($resaco,$iresaco,'rh145_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3748,20838,'','".AddSlashes(pg_result($resaco,$iresaco,'rh145_rhfolhapagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhhistoricopensao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh145_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh145_sequencial = $rh145_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhhistoricopensao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh145_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhhistoricopensao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh145_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh145_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhhistoricopensao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh145_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhhistoricopensao ";
     $sql .= "      inner join pensao  on  pensao.r52_sequencial = rhhistoricopensao.rh145_sequencial";
     $sql .= "      inner join rhfolhapagamento  on  rhfolhapagamento.rh141_sequencial = rhhistoricopensao.rh145_rhfolhapagamento";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pensao.r52_numcgm";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = pensao.r52_anousu and  pessoal.r01_mesusu = pensao.r52_mesusu and  pessoal.r01_regist = pensao.r52_regist";
     $sql .= "      inner join rhtipofolha  on  rhtipofolha.rh142_sequencial = rhfolhapagamento.rh141_tipofolha";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh145_sequencial)) {
         $sql2 .= " where rhhistoricopensao.rh145_sequencial = $rh145_sequencial "; 
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
   public function sql_query_file ($rh145_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhhistoricopensao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh145_sequencial)){
         $sql2 .= " where rhhistoricopensao.rh145_sequencial = $rh145_sequencial "; 
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
