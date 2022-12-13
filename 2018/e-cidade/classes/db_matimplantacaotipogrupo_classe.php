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

//MODULO: material
//CLASSE DA ENTIDADE matimplantacaotipogrupo
class cl_matimplantacaotipogrupo { 
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
   var $m93_sequencial = 0; 
   var $m93_db_usuarios = 0; 
   var $m93_dataimplantacao_dia = null; 
   var $m93_dataimplantacao_mes = null; 
   var $m93_dataimplantacao_ano = null; 
   var $m93_dataimplantacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m93_sequencial = int4 = Sequencial 
                 m93_db_usuarios = int4 = Codigo Usuário 
                 m93_dataimplantacao = date = Data de Implantação 
                 ";
   //funcao construtor da classe 
   function cl_matimplantacaotipogrupo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matimplantacaotipogrupo"); 
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
       $this->m93_sequencial = ($this->m93_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m93_sequencial"]:$this->m93_sequencial);
       $this->m93_db_usuarios = ($this->m93_db_usuarios == ""?@$GLOBALS["HTTP_POST_VARS"]["m93_db_usuarios"]:$this->m93_db_usuarios);
       if($this->m93_dataimplantacao == ""){
         $this->m93_dataimplantacao_dia = ($this->m93_dataimplantacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m93_dataimplantacao_dia"]:$this->m93_dataimplantacao_dia);
         $this->m93_dataimplantacao_mes = ($this->m93_dataimplantacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m93_dataimplantacao_mes"]:$this->m93_dataimplantacao_mes);
         $this->m93_dataimplantacao_ano = ($this->m93_dataimplantacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m93_dataimplantacao_ano"]:$this->m93_dataimplantacao_ano);
         if($this->m93_dataimplantacao_dia != ""){
            $this->m93_dataimplantacao = $this->m93_dataimplantacao_ano."-".$this->m93_dataimplantacao_mes."-".$this->m93_dataimplantacao_dia;
         }
       }
     }else{
       $this->m93_sequencial = ($this->m93_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m93_sequencial"]:$this->m93_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m93_sequencial){ 
      $this->atualizacampos();
     if($this->m93_db_usuarios == null ){ 
       $this->erro_sql = " Campo Codigo Usuário nao Informado.";
       $this->erro_campo = "m93_db_usuarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m93_dataimplantacao == null ){ 
       $this->erro_sql = " Campo Data de Implantação nao Informado.";
       $this->erro_campo = "m93_dataimplantacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m93_sequencial == "" || $m93_sequencial == null ){
       $result = db_query("select nextval('matimplantacaotipogrupo_m93_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matimplantacaotipogrupo_m93_sequencial_seq do campo: m93_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m93_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matimplantacaotipogrupo_m93_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m93_sequencial)){
         $this->erro_sql = " Campo m93_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m93_sequencial = $m93_sequencial; 
       }
     }
     if(($this->m93_sequencial == null) || ($this->m93_sequencial == "") ){ 
       $this->erro_sql = " Campo m93_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matimplantacaotipogrupo(
                                       m93_sequencial 
                                      ,m93_db_usuarios 
                                      ,m93_dataimplantacao 
                       )
                values (
                                $this->m93_sequencial 
                               ,$this->m93_db_usuarios 
                               ,".($this->m93_dataimplantacao == "null" || $this->m93_dataimplantacao == ""?"null":"'".$this->m93_dataimplantacao."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Implantação do Tipo de Grupo ($this->m93_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Implantação do Tipo de Grupo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Implantação do Tipo de Grupo ($this->m93_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m93_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m93_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19183,'$this->m93_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3407,19183,'','".AddSlashes(pg_result($resaco,0,'m93_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3407,19181,'','".AddSlashes(pg_result($resaco,0,'m93_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3407,19182,'','".AddSlashes(pg_result($resaco,0,'m93_dataimplantacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m93_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update matimplantacaotipogrupo set ";
     $virgula = "";
     if(trim($this->m93_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m93_sequencial"])){ 
       $sql  .= $virgula." m93_sequencial = $this->m93_sequencial ";
       $virgula = ",";
       if(trim($this->m93_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "m93_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m93_db_usuarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m93_db_usuarios"])){ 
       $sql  .= $virgula." m93_db_usuarios = $this->m93_db_usuarios ";
       $virgula = ",";
       if(trim($this->m93_db_usuarios) == null ){ 
         $this->erro_sql = " Campo Codigo Usuário nao Informado.";
         $this->erro_campo = "m93_db_usuarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m93_dataimplantacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m93_dataimplantacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m93_dataimplantacao_dia"] !="") ){ 
       $sql  .= $virgula." m93_dataimplantacao = '$this->m93_dataimplantacao' ";
       $virgula = ",";
       if(trim($this->m93_dataimplantacao) == null ){ 
         $this->erro_sql = " Campo Data de Implantação nao Informado.";
         $this->erro_campo = "m93_dataimplantacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m93_dataimplantacao_dia"])){ 
         $sql  .= $virgula." m93_dataimplantacao = null ";
         $virgula = ",";
         if(trim($this->m93_dataimplantacao) == null ){ 
           $this->erro_sql = " Campo Data de Implantação nao Informado.";
           $this->erro_campo = "m93_dataimplantacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($m93_sequencial!=null){
       $sql .= " m93_sequencial = $this->m93_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m93_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19183,'$this->m93_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m93_sequencial"]) || $this->m93_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3407,19183,'".AddSlashes(pg_result($resaco,$conresaco,'m93_sequencial'))."','$this->m93_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m93_db_usuarios"]) || $this->m93_db_usuarios != "")
           $resac = db_query("insert into db_acount values($acount,3407,19181,'".AddSlashes(pg_result($resaco,$conresaco,'m93_db_usuarios'))."','$this->m93_db_usuarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m93_dataimplantacao"]) || $this->m93_dataimplantacao != "")
           $resac = db_query("insert into db_acount values($acount,3407,19182,'".AddSlashes(pg_result($resaco,$conresaco,'m93_dataimplantacao'))."','$this->m93_dataimplantacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Implantação do Tipo de Grupo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m93_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Implantação do Tipo de Grupo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m93_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m93_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m93_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m93_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19183,'$m93_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3407,19183,'','".AddSlashes(pg_result($resaco,$iresaco,'m93_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3407,19181,'','".AddSlashes(pg_result($resaco,$iresaco,'m93_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3407,19182,'','".AddSlashes(pg_result($resaco,$iresaco,'m93_dataimplantacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matimplantacaotipogrupo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m93_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m93_sequencial = $m93_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Implantação do Tipo de Grupo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m93_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Implantação do Tipo de Grupo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m93_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m93_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matimplantacaotipogrupo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m93_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matimplantacaotipogrupo ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matimplantacaotipogrupo.m93_db_usuarios";
     $sql2 = "";
     if($dbwhere==""){
       if($m93_sequencial!=null ){
         $sql2 .= " where matimplantacaotipogrupo.m93_sequencial = $m93_sequencial "; 
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
   function sql_query_file ( $m93_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matimplantacaotipogrupo ";
     $sql2 = "";
     if($dbwhere==""){
       if($m93_sequencial!=null ){
         $sql2 .= " where matimplantacaotipogrupo.m93_sequencial = $m93_sequencial "; 
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