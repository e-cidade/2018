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

//MODULO: projetos
//CLASSE DA ENTIDADE obrasconstrcaracter
class cl_obrasconstrcaracter { 
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
   var $ob34_sequencial = 0; 
   var $ob34_obrasconstr = 0; 
   var $ob34_caracter = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ob34_sequencial = int4 = Sequencial 
                 ob34_obrasconstr = int4 = Código da construção 
                 ob34_caracter = int4 = Caracteristica 
                 ";
   //funcao construtor da classe 
   function cl_obrasconstrcaracter() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("obrasconstrcaracter"); 
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
       $this->ob34_sequencial = ($this->ob34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ob34_sequencial"]:$this->ob34_sequencial);
       $this->ob34_obrasconstr = ($this->ob34_obrasconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["ob34_obrasconstr"]:$this->ob34_obrasconstr);
       $this->ob34_caracter = ($this->ob34_caracter == ""?@$GLOBALS["HTTP_POST_VARS"]["ob34_caracter"]:$this->ob34_caracter);
     }else{
       $this->ob34_sequencial = ($this->ob34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ob34_sequencial"]:$this->ob34_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ob34_sequencial){ 
      $this->atualizacampos();
     if($this->ob34_obrasconstr == null ){ 
       $this->erro_sql = " Campo Código da construção nao Informado.";
       $this->erro_campo = "ob34_obrasconstr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob34_caracter == null ){ 
       $this->erro_sql = " Campo Caracteristica nao Informado.";
       $this->erro_campo = "ob34_caracter";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ob34_sequencial == "" || $ob34_sequencial == null ){
       $result = db_query("select nextval('obrasconstrcaracter_ob34_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: obrasconstrcaracter_ob34_sequencial_seq do campo: ob34_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ob34_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from obrasconstrcaracter_ob34_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ob34_sequencial)){
         $this->erro_sql = " Campo ob34_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ob34_sequencial = $ob34_sequencial; 
       }
     }
     if(($this->ob34_sequencial == null) || ($this->ob34_sequencial == "") ){ 
       $this->erro_sql = " Campo ob34_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obrasconstrcaracter(
                                       ob34_sequencial 
                                      ,ob34_obrasconstr 
                                      ,ob34_caracter 
                       )
                values (
                                $this->ob34_sequencial 
                               ,$this->ob34_obrasconstr 
                               ,$this->ob34_caracter 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "obrasconstrcaracter ($this->ob34_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "obrasconstrcaracter já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "obrasconstrcaracter ($this->ob34_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob34_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ob34_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18657,'$this->ob34_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3303,18657,'','".AddSlashes(pg_result($resaco,0,'ob34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3303,18658,'','".AddSlashes(pg_result($resaco,0,'ob34_obrasconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3303,18659,'','".AddSlashes(pg_result($resaco,0,'ob34_caracter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ob34_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update obrasconstrcaracter set ";
     $virgula = "";
     if(trim($this->ob34_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob34_sequencial"])){ 
       $sql  .= $virgula." ob34_sequencial = $this->ob34_sequencial ";
       $virgula = ",";
       if(trim($this->ob34_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ob34_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob34_obrasconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob34_obrasconstr"])){ 
       $sql  .= $virgula." ob34_obrasconstr = $this->ob34_obrasconstr ";
       $virgula = ",";
       if(trim($this->ob34_obrasconstr) == null ){ 
         $this->erro_sql = " Campo Código da construção nao Informado.";
         $this->erro_campo = "ob34_obrasconstr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob34_caracter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob34_caracter"])){ 
       $sql  .= $virgula." ob34_caracter = $this->ob34_caracter ";
       $virgula = ",";
       if(trim($this->ob34_caracter) == null ){ 
         $this->erro_sql = " Campo Caracteristica nao Informado.";
         $this->erro_campo = "ob34_caracter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ob34_sequencial!=null){
       $sql .= " ob34_sequencial = $this->ob34_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ob34_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18657,'$this->ob34_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob34_sequencial"]) || $this->ob34_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3303,18657,'".AddSlashes(pg_result($resaco,$conresaco,'ob34_sequencial'))."','$this->ob34_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob34_obrasconstr"]) || $this->ob34_obrasconstr != "")
           $resac = db_query("insert into db_acount values($acount,3303,18658,'".AddSlashes(pg_result($resaco,$conresaco,'ob34_obrasconstr'))."','$this->ob34_obrasconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob34_caracter"]) || $this->ob34_caracter != "")
           $resac = db_query("insert into db_acount values($acount,3303,18659,'".AddSlashes(pg_result($resaco,$conresaco,'ob34_caracter'))."','$this->ob34_caracter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "obrasconstrcaracter nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob34_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "obrasconstrcaracter nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ob34_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ob34_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18657,'$ob34_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3303,18657,'','".AddSlashes(pg_result($resaco,$iresaco,'ob34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3303,18658,'','".AddSlashes(pg_result($resaco,$iresaco,'ob34_obrasconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3303,18659,'','".AddSlashes(pg_result($resaco,$iresaco,'ob34_caracter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from obrasconstrcaracter
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ob34_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ob34_sequencial = $ob34_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "obrasconstrcaracter nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob34_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "obrasconstrcaracter nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ob34_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:obrasconstrcaracter";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ob34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasconstrcaracter ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = obrasconstrcaracter.ob34_caracter";
     $sql .= "      inner join obrasconstr  on  obrasconstr.ob08_codconstr = obrasconstrcaracter.ob34_obrasconstr";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = obrasconstr.ob08_ocupacao";
     $sql .= "      inner join obras  as a on   a.ob01_codobra = obrasconstr.ob08_codobra";
     $sql2 = "";
     if($dbwhere==""){
       if($ob34_sequencial!=null ){
         $sql2 .= " where obrasconstrcaracter.ob34_sequencial = $ob34_sequencial "; 
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
   function sql_query_file ( $ob34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasconstrcaracter ";
     $sql2 = "";
     if($dbwhere==""){
       if($ob34_sequencial!=null ){
         $sql2 .= " where obrasconstrcaracter.ob34_sequencial = $ob34_sequencial "; 
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
   * Método que retorna string sql com os dados vinculados das contruções e suas caracteristicas
   * @param integer $iCodigoObra - Codigo da Obra (tabela obras)
   * @param  string $sCampos     - String com os campos da consulta sql
   * @return string              - Sql para ser executado.
   */
  function sql_query_caracteristicasConstrucao($iCodigoObra, $sCampos = "*") {
  	
  	$sSql = "select {$sCampos}                                                                                        ";
    $sSql.= "  from obrasconstr                                                                                       ";
    $sSql.= "       left join obrasconstrcaracter on obrasconstrcaracter.ob34_obrasconstr = obrasconstr.ob08_codconstr"; 
    $sSql.= "       left join caracter            on obrasconstrcaracter.ob34_caracter    = caracter.j31_codigo       ";
    $sSql.= " where ob08_codobra = {$iCodigoObra}                                                                     ";
    
    return $sSql;
  }
  
  /**
   * Método que retorna string sql com os dados das caracteristicas e se elas possuem valur setado na obra selecionada
   * @param integer $iCodigoObra
   * @return string
   */
  function sql_query_selecoesCaracteristicas($iCodigoObra) {
  	
  	$sSql = " select distinct                                                                                            ";
  	$sSql.= "        j32_grupo,                                                                                          ";
    $sSql.= "        j32_descr,                                                                                          ";
    $sSql.= "        j31_codigo,                                                                                         ";
    $sSql.= "        j31_descr,                                                                                          ";
    $sSql.= "        case when ob08_codobra is not null                                                                  ";
    $sSql.= "             then true                                                                                      ";
    $sSql.= "             else false                                                                                     ";
    $sSql.= "        end as selecionada                                                                                  ";
    $sSql.= "   from caracter                                                                                            ";
    $sSql.= "        inner join cargrup             on cargrup.j32_grupo                    = caracter.j31_grupo         ";
    $sSql.= "        left  join obrasconstrcaracter on obrasconstrcaracter.ob34_caracter    = caracter.j31_codigo        ";
    $sSql.= "        left  join obrasconstr         on obrasconstrcaracter.ob34_obrasconstr = obrasconstr.ob08_codconstr ";
    $sSql.= "                                      and obrasconstr.ob08_codobra             = {$iCodigoObra}             ";
    $sSql.= "  where j32_tipo = 'C'                                                                                      ";
    $sSql.= "  order by j32_grupo                                                                                        ";
  	
  	return $sSql;
  }
}
?>