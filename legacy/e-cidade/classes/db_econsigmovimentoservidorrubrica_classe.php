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
//CLASSE DA ENTIDADE econsigmovimentoservidorrubrica
class cl_econsigmovimentoservidorrubrica { 
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
   var $rh135_sequencial = 0; 
   var $rh135_econsigmovimentoservidor = 0; 
   var $rh135_rubrica = null; 
   var $rh135_instit = 0; 
   var $rh135_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh135_sequencial = int4 = Código Sequencial 
                 rh135_econsigmovimentoservidor = int4 = E-CONSIG Movimento Servidor 
                 rh135_rubrica = varchar(4) = Rubrica 
                 rh135_instit = int4 = Instituição 
                 rh135_valor = float4 = Valor Rubrica 
                 ";
   //funcao construtor da classe 
   function cl_econsigmovimentoservidorrubrica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("econsigmovimentoservidorrubrica"); 
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
       $this->rh135_sequencial = ($this->rh135_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh135_sequencial"]:$this->rh135_sequencial);
       $this->rh135_econsigmovimentoservidor = ($this->rh135_econsigmovimentoservidor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh135_econsigmovimentoservidor"]:$this->rh135_econsigmovimentoservidor);
       $this->rh135_rubrica = ($this->rh135_rubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh135_rubrica"]:$this->rh135_rubrica);
       $this->rh135_instit = ($this->rh135_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh135_instit"]:$this->rh135_instit);
       $this->rh135_valor = ($this->rh135_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh135_valor"]:$this->rh135_valor);
     }else{
       $this->rh135_sequencial = ($this->rh135_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh135_sequencial"]:$this->rh135_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh135_sequencial){ 
      $this->atualizacampos();
     if($this->rh135_econsigmovimentoservidor == null ){ 
       $this->erro_sql = " Campo E-CONSIG Movimento Servidor não informado.";
       $this->erro_campo = "rh135_econsigmovimentoservidor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh135_rubrica == null ){ 
       $this->erro_sql = " Campo Rubrica não informado.";
       $this->erro_campo = "rh135_rubrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh135_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh135_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh135_valor == null ){ 
       $this->erro_sql = " Campo Valor Rubrica não informado.";
       $this->erro_campo = "rh135_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh135_sequencial == "" || $rh135_sequencial == null ){
       $result = db_query("select nextval('econsigmovimentoservidorrubrica_rh135_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: econsigmovimentoservidorrubrica_rh135_sequencial_seq do campo: rh135_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh135_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from econsigmovimentoservidorrubrica_rh135_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh135_sequencial)){
         $this->erro_sql = " Campo rh135_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh135_sequencial = $rh135_sequencial; 
       }
     }
     if(($this->rh135_sequencial == null) || ($this->rh135_sequencial == "") ){ 
       $this->erro_sql = " Campo rh135_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into econsigmovimentoservidorrubrica(
                                       rh135_sequencial 
                                      ,rh135_econsigmovimentoservidor 
                                      ,rh135_rubrica 
                                      ,rh135_instit 
                                      ,rh135_valor 
                       )
                values (
                                $this->rh135_sequencial 
                               ,$this->rh135_econsigmovimentoservidor 
                               ,'$this->rh135_rubrica' 
                               ,$this->rh135_instit 
                               ,$this->rh135_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "E-CONSIG Movimento Servidor Rubrica ($this->rh135_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "E-CONSIG Movimento Servidor Rubrica já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "E-CONSIG Movimento Servidor Rubrica ($this->rh135_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh135_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh135_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20450,'$this->rh135_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3677,20450,'','".AddSlashes(pg_result($resaco,0,'rh135_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3677,20451,'','".AddSlashes(pg_result($resaco,0,'rh135_econsigmovimentoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3677,20452,'','".AddSlashes(pg_result($resaco,0,'rh135_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3677,20453,'','".AddSlashes(pg_result($resaco,0,'rh135_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3677,20456,'','".AddSlashes(pg_result($resaco,0,'rh135_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh135_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update econsigmovimentoservidorrubrica set ";
     $virgula = "";
     if(trim($this->rh135_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh135_sequencial"])){ 
       $sql  .= $virgula." rh135_sequencial = $this->rh135_sequencial ";
       $virgula = ",";
       if(trim($this->rh135_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "rh135_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh135_econsigmovimentoservidor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh135_econsigmovimentoservidor"])){ 
       $sql  .= $virgula." rh135_econsigmovimentoservidor = $this->rh135_econsigmovimentoservidor ";
       $virgula = ",";
       if(trim($this->rh135_econsigmovimentoservidor) == null ){ 
         $this->erro_sql = " Campo E-CONSIG Movimento Servidor não informado.";
         $this->erro_campo = "rh135_econsigmovimentoservidor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh135_rubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh135_rubrica"])){ 
       $sql  .= $virgula." rh135_rubrica = '$this->rh135_rubrica' ";
       $virgula = ",";
       if(trim($this->rh135_rubrica) == null ){ 
         $this->erro_sql = " Campo Rubrica não informado.";
         $this->erro_campo = "rh135_rubrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh135_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh135_instit"])){ 
       $sql  .= $virgula." rh135_instit = $this->rh135_instit ";
       $virgula = ",";
       if(trim($this->rh135_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh135_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh135_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh135_valor"])){ 
       $sql  .= $virgula." rh135_valor = $this->rh135_valor ";
       $virgula = ",";
       if(trim($this->rh135_valor) == null ){ 
         $this->erro_sql = " Campo Valor Rubrica não informado.";
         $this->erro_campo = "rh135_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh135_sequencial!=null){
       $sql .= " rh135_sequencial = $this->rh135_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh135_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20450,'$this->rh135_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh135_sequencial"]) || $this->rh135_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3677,20450,'".AddSlashes(pg_result($resaco,$conresaco,'rh135_sequencial'))."','$this->rh135_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh135_econsigmovimentoservidor"]) || $this->rh135_econsigmovimentoservidor != "")
             $resac = db_query("insert into db_acount values($acount,3677,20451,'".AddSlashes(pg_result($resaco,$conresaco,'rh135_econsigmovimentoservidor'))."','$this->rh135_econsigmovimentoservidor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh135_rubrica"]) || $this->rh135_rubrica != "")
             $resac = db_query("insert into db_acount values($acount,3677,20452,'".AddSlashes(pg_result($resaco,$conresaco,'rh135_rubrica'))."','$this->rh135_rubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh135_instit"]) || $this->rh135_instit != "")
             $resac = db_query("insert into db_acount values($acount,3677,20453,'".AddSlashes(pg_result($resaco,$conresaco,'rh135_instit'))."','$this->rh135_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh135_valor"]) || $this->rh135_valor != "")
             $resac = db_query("insert into db_acount values($acount,3677,20456,'".AddSlashes(pg_result($resaco,$conresaco,'rh135_valor'))."','$this->rh135_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "E-CONSIG Movimento Servidor Rubrica nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh135_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "E-CONSIG Movimento Servidor Rubrica nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh135_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh135_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh135_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh135_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20450,'$rh135_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3677,20450,'','".AddSlashes(pg_result($resaco,$iresaco,'rh135_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3677,20451,'','".AddSlashes(pg_result($resaco,$iresaco,'rh135_econsigmovimentoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3677,20452,'','".AddSlashes(pg_result($resaco,$iresaco,'rh135_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3677,20453,'','".AddSlashes(pg_result($resaco,$iresaco,'rh135_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3677,20456,'','".AddSlashes(pg_result($resaco,$iresaco,'rh135_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from econsigmovimentoservidorrubrica
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh135_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh135_sequencial = $rh135_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "E-CONSIG Movimento Servidor Rubrica nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh135_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "E-CONSIG Movimento Servidor Rubrica nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh135_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh135_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:econsigmovimentoservidorrubrica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh135_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from econsigmovimentoservidorrubrica ";
     $sql .= "      inner join db_config  on  db_config.codigo = econsigmovimentoservidorrubrica.rh135_instit";
     $sql .= "      inner join econsigmovimentoservidor  on  econsigmovimentoservidor.rh134_sequencial = econsigmovimentoservidorrubrica.rh135_econsigmovimentoservidor";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join econsigmovimento  as a on   a.rh133_sequencial = econsigmovimentoservidor.rh134_econsigmovimento";
     $sql .= "      left  join econsigmotivo  on  econsigmotivo.rh147_sequencial = econsigmovimentoservidor.rh134_econsigmotivo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh135_sequencial)) {
         $sql2 .= " where econsigmovimentoservidorrubrica.rh135_sequencial = $rh135_sequencial "; 
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
   public function sql_query_file ($rh135_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from econsigmovimentoservidorrubrica ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh135_sequencial)){
         $sql2 .= " where econsigmovimentoservidorrubrica.rh135_sequencial = $rh135_sequencial "; 
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
