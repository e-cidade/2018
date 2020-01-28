<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: prefeitura
//CLASSE DA ENTIDADE configdbprefagua
class cl_configdbprefagua { 
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
   var $w16_sequencial = 0; 
   var $w16_instit = 0; 
   var $w16_aguacortesituacao = 0; 
   var $w16_recibodbpref = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 w16_sequencial = int4 = Cód Sequencial 
                 w16_instit = int4 = Cód Instituíção 
                 w16_aguacortesituacao = int4 = Cód Situação 
                 w16_recibodbpref = int4 = Exibe Débitos 
                 ";
   //funcao construtor da classe 
   function cl_configdbprefagua() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("configdbprefagua"); 
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
       $this->w16_sequencial = ($this->w16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w16_sequencial"]:$this->w16_sequencial);
       $this->w16_instit = ($this->w16_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["w16_instit"]:$this->w16_instit);
       $this->w16_aguacortesituacao = ($this->w16_aguacortesituacao == ""?@$GLOBALS["HTTP_POST_VARS"]["w16_aguacortesituacao"]:$this->w16_aguacortesituacao);
       $this->w16_recibodbpref = ($this->w16_recibodbpref == ""?@$GLOBALS["HTTP_POST_VARS"]["w16_recibodbpref"]:$this->w16_recibodbpref);
     }else{
       $this->w16_sequencial = ($this->w16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w16_sequencial"]:$this->w16_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($w16_sequencial){ 
      $this->atualizacampos();
     if($this->w16_instit == null ){ 
       $this->erro_sql = " Campo Cód Instituíção nao Informado.";
       $this->erro_campo = "w16_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w16_aguacortesituacao == null ){ 
       $this->erro_sql = " Campo Cód Situação nao Informado.";
       $this->erro_campo = "w16_aguacortesituacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w16_recibodbpref == null ){ 
       $this->erro_sql = " Campo Exibe Débitos nao Informado.";
       $this->erro_campo = "w16_recibodbpref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($w16_sequencial == "" || $w16_sequencial == null ){
       $result = db_query("select nextval('configdbprefagua_w16_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: configdbprefagua_w16_sequencial_seq do campo: w16_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->w16_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from configdbprefagua_w16_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $w16_sequencial)){
         $this->erro_sql = " Campo w16_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->w16_sequencial = $w16_sequencial; 
       }
     }
     if(($this->w16_sequencial == null) || ($this->w16_sequencial == "") ){ 
       $this->erro_sql = " Campo w16_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into configdbprefagua(
                                       w16_sequencial 
                                      ,w16_instit 
                                      ,w16_aguacortesituacao 
                                      ,w16_recibodbpref 
                       )
                values (
                                $this->w16_sequencial 
                               ,$this->w16_instit 
                               ,$this->w16_aguacortesituacao 
                               ,$this->w16_recibodbpref 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Conf. DBPref Água ($this->w16_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Conf. DBPref Água já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Conf. DBPref Água ($this->w16_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w16_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->w16_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14578,'$this->w16_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2565,14578,'','".AddSlashes(pg_result($resaco,0,'w16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2565,14580,'','".AddSlashes(pg_result($resaco,0,'w16_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2565,14579,'','".AddSlashes(pg_result($resaco,0,'w16_aguacortesituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2565,14581,'','".AddSlashes(pg_result($resaco,0,'w16_recibodbpref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($w16_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update configdbprefagua set ";
     $virgula = "";
     if(trim($this->w16_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w16_sequencial"])){ 
       $sql  .= $virgula." w16_sequencial = $this->w16_sequencial ";
       $virgula = ",";
       if(trim($this->w16_sequencial) == null ){ 
         $this->erro_sql = " Campo Cód Sequencial nao Informado.";
         $this->erro_campo = "w16_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w16_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w16_instit"])){ 
       $sql  .= $virgula." w16_instit = $this->w16_instit ";
       $virgula = ",";
       if(trim($this->w16_instit) == null ){ 
         $this->erro_sql = " Campo Cód Instituíção nao Informado.";
         $this->erro_campo = "w16_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w16_aguacortesituacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w16_aguacortesituacao"])){ 
       $sql  .= $virgula." w16_aguacortesituacao = $this->w16_aguacortesituacao ";
       $virgula = ",";
       if(trim($this->w16_aguacortesituacao) == null ){ 
         $this->erro_sql = " Campo Cód Situação nao Informado.";
         $this->erro_campo = "w16_aguacortesituacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w16_recibodbpref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w16_recibodbpref"])){ 
       $sql  .= $virgula." w16_recibodbpref = $this->w16_recibodbpref ";
       $virgula = ",";
       if(trim($this->w16_recibodbpref) == null ){ 
         $this->erro_sql = " Campo Exibe Débitos nao Informado.";
         $this->erro_campo = "w16_recibodbpref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($w16_sequencial!=null){
       $sql .= " w16_sequencial = $this->w16_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->w16_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14578,'$this->w16_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w16_sequencial"]) || $this->w16_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2565,14578,'".AddSlashes(pg_result($resaco,$conresaco,'w16_sequencial'))."','$this->w16_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w16_instit"]) || $this->w16_instit != "")
           $resac = db_query("insert into db_acount values($acount,2565,14580,'".AddSlashes(pg_result($resaco,$conresaco,'w16_instit'))."','$this->w16_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w16_aguacortesituacao"]) || $this->w16_aguacortesituacao != "")
           $resac = db_query("insert into db_acount values($acount,2565,14579,'".AddSlashes(pg_result($resaco,$conresaco,'w16_aguacortesituacao'))."','$this->w16_aguacortesituacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w16_recibodbpref"]) || $this->w16_recibodbpref != "")
           $resac = db_query("insert into db_acount values($acount,2565,14581,'".AddSlashes(pg_result($resaco,$conresaco,'w16_recibodbpref'))."','$this->w16_recibodbpref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Conf. DBPref Água nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->w16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Conf. DBPref Água nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->w16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($w16_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($w16_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14578,'$w16_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2565,14578,'','".AddSlashes(pg_result($resaco,$iresaco,'w16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2565,14580,'','".AddSlashes(pg_result($resaco,$iresaco,'w16_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2565,14579,'','".AddSlashes(pg_result($resaco,$iresaco,'w16_aguacortesituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2565,14581,'','".AddSlashes(pg_result($resaco,$iresaco,'w16_recibodbpref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from configdbprefagua
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($w16_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w16_sequencial = $w16_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Conf. DBPref Água nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$w16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Conf. DBPref Água nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$w16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$w16_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:configdbprefagua";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $w16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from configdbprefagua ";
     $sql .= "      inner join db_config  on  db_config.codigo = configdbprefagua.w16_instit";
     $sql .= "      inner join aguacortesituacao  on  aguacortesituacao.x43_codsituacao = configdbprefagua.w16_aguacortesituacao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($w16_sequencial!=null ){
         $sql2 .= " where configdbprefagua.w16_sequencial = $w16_sequencial "; 
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
   function sql_query_file ( $w16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from configdbprefagua ";
     $sql2 = "";
     if($dbwhere==""){
       if($w16_sequencial!=null ){
         $sql2 .= " where configdbprefagua.w16_sequencial = $w16_sequencial "; 
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