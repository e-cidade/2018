<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE rhgeracaofolhaarquivoreg
class cl_rhgeracaofolhaarquivoreg { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $rh106_sequencial = 0; 
   var $rh106_rhgeracaofolhaarquivo = 0; 
   var $rh106_rhgeracaofolhareg = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh106_sequencial = int4 = Sequencial do Campo 
                 rh106_rhgeracaofolhaarquivo = int4 = Código da Geração do Arquivo 
                 rh106_rhgeracaofolhareg = int4 = Código do registro da geração 
                 ";
   //funcao construtor da classe 
   function cl_rhgeracaofolhaarquivoreg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhgeracaofolhaarquivoreg"); 
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
       $this->rh106_sequencial = ($this->rh106_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh106_sequencial"]:$this->rh106_sequencial);
       $this->rh106_rhgeracaofolhaarquivo = ($this->rh106_rhgeracaofolhaarquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh106_rhgeracaofolhaarquivo"]:$this->rh106_rhgeracaofolhaarquivo);
       $this->rh106_rhgeracaofolhareg = ($this->rh106_rhgeracaofolhareg == ""?@$GLOBALS["HTTP_POST_VARS"]["rh106_rhgeracaofolhareg"]:$this->rh106_rhgeracaofolhareg);
     }else{
       $this->rh106_sequencial = ($this->rh106_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh106_sequencial"]:$this->rh106_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh106_sequencial){ 
      $this->atualizacampos();
     if($this->rh106_rhgeracaofolhaarquivo == null ){ 
       $this->erro_sql = " Campo Código da Geração do Arquivo nao Informado.";
       $this->erro_campo = "rh106_rhgeracaofolhaarquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh106_rhgeracaofolhareg == null ){ 
       $this->erro_sql = " Campo Código do registro da geração nao Informado.";
       $this->erro_campo = "rh106_rhgeracaofolhareg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh106_sequencial == "" || $rh106_sequencial == null ){
       $result = @pg_query("select nextval('rhgeracaofolhaarquivoreg_rh106_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhgeracaofolhaarquivoreg_rh106_sequencial_seq do campo: rh106_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh106_sequencial = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from rhgeracaofolhaarquivoreg_rh106_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh106_sequencial)){
         $this->erro_sql = " Campo rh106_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh106_sequencial = $rh106_sequencial; 
       }
     }
     if(($this->rh106_sequencial == null) || ($this->rh106_sequencial == "") ){ 
       $this->erro_sql = " Campo rh106_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into rhgeracaofolhaarquivoreg(
                                       rh106_sequencial 
                                      ,rh106_rhgeracaofolhaarquivo 
                                      ,rh106_rhgeracaofolhareg 
                       )
                values (
                                $this->rh106_sequencial 
                               ,$this->rh106_rhgeracaofolhaarquivo 
                               ,$this->rh106_rhgeracaofolhareg 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhgeracaofolhaarquivoreg ($this->rh106_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhgeracaofolhaarquivoreg já Cadastrado";
         $this->erro_msg  .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhgeracaofolhaarquivoreg ($this->rh106_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg  .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh106_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->rh106_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,18143,'$this->rh106_sequencial','I')");
       $resac = pg_query("insert into db_acount values($acount,3202,18143,'','".pg_result($resaco,0,'rh106_sequencial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3202,18144,'','".pg_result($resaco,0,'rh106_rhgeracaofolhaarquivo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3202,18145,'','".pg_result($resaco,0,'rh106_rhgeracaofolhareg')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh106_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhgeracaofolhaarquivoreg set ";
     $virgula = "";
     if(trim($this->rh106_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh106_sequencial"])){ 
        if(trim($this->rh106_sequencial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh106_sequencial"])){ 
           $this->rh106_sequencial = "0" ; 
        } 
       $sql  .= $virgula." rh106_sequencial = $this->rh106_sequencial ";
       $virgula = ",";
       if(trim($this->rh106_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial do Campo nao Informado.";
         $this->erro_campo = "rh106_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh106_rhgeracaofolhaarquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh106_rhgeracaofolhaarquivo"])){ 
        if(trim($this->rh106_rhgeracaofolhaarquivo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh106_rhgeracaofolhaarquivo"])){ 
           $this->rh106_rhgeracaofolhaarquivo = "0" ; 
        } 
       $sql  .= $virgula." rh106_rhgeracaofolhaarquivo = $this->rh106_rhgeracaofolhaarquivo ";
       $virgula = ",";
       if(trim($this->rh106_rhgeracaofolhaarquivo) == null ){ 
         $this->erro_sql = " Campo Código da Geração do Arquivo nao Informado.";
         $this->erro_campo = "rh106_rhgeracaofolhaarquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh106_rhgeracaofolhareg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh106_rhgeracaofolhareg"])){ 
        if(trim($this->rh106_rhgeracaofolhareg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh106_rhgeracaofolhareg"])){ 
           $this->rh106_rhgeracaofolhareg = "0" ; 
        } 
       $sql  .= $virgula." rh106_rhgeracaofolhareg = $this->rh106_rhgeracaofolhareg ";
       $virgula = ",";
       if(trim($this->rh106_rhgeracaofolhareg) == null ){ 
         $this->erro_sql = " Campo Código do registro da geração nao Informado.";
         $this->erro_campo = "rh106_rhgeracaofolhareg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  rh106_sequencial = $this->rh106_sequencial
";
     $resaco = $this->sql_record($this->sql_query_file($this->rh106_sequencial));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,18143,'$this->rh106_sequencial','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh106_sequencial"]))
         $resac = pg_query("insert into db_acount values($acount,3202,18143,'".pg_result($resaco,0,'rh106_sequencial')."','$this->rh106_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh106_rhgeracaofolhaarquivo"]))
         $resac = pg_query("insert into db_acount values($acount,3202,18144,'".pg_result($resaco,0,'rh106_rhgeracaofolhaarquivo')."','$this->rh106_rhgeracaofolhaarquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh106_rhgeracaofolhareg"]))
         $resac = pg_query("insert into db_acount values($acount,3202,18145,'".pg_result($resaco,0,'rh106_rhgeracaofolhareg')."','$this->rh106_rhgeracaofolhareg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhgeracaofolhaarquivoreg nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh106_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhgeracaofolhaarquivoreg nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh106_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh106_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh106_sequencial=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->rh106_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,18143,'$this->rh106_sequencial','E')");
       $resac = pg_query("insert into db_acount values($acount,3202,18143,'','".pg_result($resaco,0,'rh106_sequencial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3202,18144,'','".pg_result($resaco,0,'rh106_rhgeracaofolhaarquivo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3202,18145,'','".pg_result($resaco,0,'rh106_rhgeracaofolhareg')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from rhgeracaofolhaarquivoreg
                    where ";
     $sql2 = "";
      if($this->rh106_sequencial != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " rh106_sequencial = $this->rh106_sequencial ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhgeracaofolhaarquivoreg nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->rh106_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhgeracaofolhaarquivoreg nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh106_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh106_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh106_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhgeracaofolhaarquivoreg ";
     $sql .= "      inner join rhgeracaofolhaarquivo  on  rhgeracaofolhaarquivo.rh105_sequencial = rhgeracaofolhaarquivoreg.rh106_rhgeracaofolhaarquivo";
     $sql .= "      inner join rhgeracaofolhaarquivoreg  on  rhgeracaofolhaarquivoreg.rh106_sequencial = rhgeracaofolhaarquivoreg.rh106_rhgeracaofolhareg";
     $sql .= "      inner join db_bancos  on  db_bancos.db90_codban = rhgeracaofolhaarquivo.rh105_codbcofebraban";
     $sql .= "      inner join rharqbanco  on  rharqbanco.rh34_codarq = rhgeracaofolhaarquivo.rh105_codarq and  rharqbanco.rh34_instit = rhgeracaofolhaarquivo.rh105_instit";
     $sql .= "      inner join rhgeracaofolhaarquivo  as a on   a.rh105_sequencial = rhgeracaofolhaarquivoreg.rh106_rhgeracaofolhaarquivo";
     $sql .= "      inner join rhgeracaofolhaarquivoreg  as b on   b.rh106_sequencial = rhgeracaofolhaarquivoreg.rh106_rhgeracaofolhareg";
     $sql2 = "";
     if($dbwhere==""){
       if($rh106_sequencial!=null ){
         $sql2 .= " where rhgeracaofolhaarquivoreg.rh106_sequencial = $rh106_sequencial "; 
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
   function sql_query_file ( $rh106_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhgeracaofolhaarquivoreg ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh106_sequencial!=null ){
         $sql2 .= " where rhgeracaofolhaarquivoreg.rh106_sequencial = $rh106_sequencial "; 
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
?>