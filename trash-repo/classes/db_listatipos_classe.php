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

//MODULO: caixa
//CLASSE DA ENTIDADE listatipos
class cl_listatipos { 
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
   var $k62_lista = 0; 
   var $k62_tipodeb = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k62_lista = int4 = Número da Lista 
                 k62_tipodeb = int4 = Tipo de débito 
                 ";
   //funcao construtor da classe 
   function cl_listatipos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("listatipos"); 
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
       $this->k62_lista = ($this->k62_lista == ""?@$GLOBALS["HTTP_POST_VARS"]["k62_lista"]:$this->k62_lista);
       $this->k62_tipodeb = ($this->k62_tipodeb == ""?@$GLOBALS["HTTP_POST_VARS"]["k62_tipodeb"]:$this->k62_tipodeb);
     }else{
       $this->k62_lista = ($this->k62_lista == ""?@$GLOBALS["HTTP_POST_VARS"]["k62_lista"]:$this->k62_lista);
       $this->k62_tipodeb = ($this->k62_tipodeb == ""?@$GLOBALS["HTTP_POST_VARS"]["k62_tipodeb"]:$this->k62_tipodeb);
     }
   }
   // funcao para inclusao
   function incluir ($k62_lista,$k62_tipodeb){ 
      $this->atualizacampos();
       $this->k62_lista = $k62_lista; 
       $this->k62_tipodeb = $k62_tipodeb; 
     if(($this->k62_lista == null) || ($this->k62_lista == "") ){ 
       $this->erro_sql = " Campo k62_lista nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k62_tipodeb == null) || ($this->k62_tipodeb == "") ){ 
       $this->erro_sql = " Campo k62_tipodeb nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into listatipos(
                                       k62_lista 
                                      ,k62_tipodeb 
                       )
                values (
                                $this->k62_lista 
                               ,$this->k62_tipodeb 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipos de débitos da lista ($this->k62_lista."-".$this->k62_tipodeb) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipos de débitos da lista já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipos de débitos da lista ($this->k62_lista."-".$this->k62_tipodeb) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k62_lista."-".$this->k62_tipodeb;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k62_lista,$this->k62_tipodeb));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4753,'$this->k62_lista','I')");
       $resac = db_query("insert into db_acountkey values($acount,4754,'$this->k62_tipodeb','I')");
       $resac = db_query("insert into db_acount values($acount,634,4753,'','".AddSlashes(pg_result($resaco,0,'k62_lista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,634,4754,'','".AddSlashes(pg_result($resaco,0,'k62_tipodeb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k62_lista=null,$k62_tipodeb=null) { 
      $this->atualizacampos();
     $sql = " update listatipos set ";
     $virgula = "";
     if(trim($this->k62_lista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k62_lista"])){ 
       $sql  .= $virgula." k62_lista = $this->k62_lista ";
       $virgula = ",";
       if(trim($this->k62_lista) == null ){ 
         $this->erro_sql = " Campo Número da Lista nao Informado.";
         $this->erro_campo = "k62_lista";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k62_tipodeb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k62_tipodeb"])){ 
       $sql  .= $virgula." k62_tipodeb = $this->k62_tipodeb ";
       $virgula = ",";
       if(trim($this->k62_tipodeb) == null ){ 
         $this->erro_sql = " Campo Tipo de débito nao Informado.";
         $this->erro_campo = "k62_tipodeb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k62_lista!=null){
       $sql .= " k62_lista = $this->k62_lista";
     }
     if($k62_tipodeb!=null){
       $sql .= " and  k62_tipodeb = $this->k62_tipodeb";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k62_lista,$this->k62_tipodeb));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4753,'$this->k62_lista','A')");
         $resac = db_query("insert into db_acountkey values($acount,4754,'$this->k62_tipodeb','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k62_lista"]))
           $resac = db_query("insert into db_acount values($acount,634,4753,'".AddSlashes(pg_result($resaco,$conresaco,'k62_lista'))."','$this->k62_lista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k62_tipodeb"]))
           $resac = db_query("insert into db_acount values($acount,634,4754,'".AddSlashes(pg_result($resaco,$conresaco,'k62_tipodeb'))."','$this->k62_tipodeb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de débitos da lista nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k62_lista."-".$this->k62_tipodeb;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de débitos da lista nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k62_lista."-".$this->k62_tipodeb;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k62_lista."-".$this->k62_tipodeb;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k62_lista=null,$k62_tipodeb=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k62_lista,$k62_tipodeb));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4753,'$k62_lista','E')");
         $resac = db_query("insert into db_acountkey values($acount,4754,'$k62_tipodeb','E')");
         $resac = db_query("insert into db_acount values($acount,634,4753,'','".AddSlashes(pg_result($resaco,$iresaco,'k62_lista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,634,4754,'','".AddSlashes(pg_result($resaco,$iresaco,'k62_tipodeb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from listatipos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k62_lista != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k62_lista = $k62_lista ";
        }
        if($k62_tipodeb != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k62_tipodeb = $k62_tipodeb ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de débitos da lista nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k62_lista."-".$k62_tipodeb;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de débitos da lista nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k62_lista."-".$k62_tipodeb;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k62_lista."-".$k62_tipodeb;
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
        $this->erro_sql   = "Record Vazio na Tabela:listatipos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function excluir_lista ($k62_lista=null,$k62_tipodeb=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->k62_lista,$this->k62_tipodeb));
     for ($iresaco=0; $iresaco < $this->numrows; $iresaco++ ) {
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4753,'".pg_result($resaco,$iresaco,'k62_lista')."','E')");
         $resac = db_query("insert into db_acountkey values($acount,4754,'".pg_result($resaco,$iresaco,'k62_tipodeb')."','E')");
         $resac = db_query("insert into db_acount values($acount,634,4753,'','".pg_result($resaco,0,'k62_lista')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,634,4754,'','".pg_result($resaco,0,'k62_tipodeb')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from listatipos
                    where ";
     $sql2 = "";
      if($this->k62_lista != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " k62_lista = $this->k62_lista ";
}
      if($this->k62_tipodeb != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " k62_tipodeb = $this->k62_tipodeb ";
}
     $result = @db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de débitos da lista nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->k62_lista."-".$this->k62_tipodeb;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de débitos da lista nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->k62_lista."-".$this->k62_tipodeb;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k62_lista."-".$this->k62_tipodeb;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   }
   function sql_query ( $k62_lista=null,$k62_tipodeb=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from listatipos ";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = listatipos.k62_tipodeb";
     $sql .= "      inner join lista  on  lista.k60_codigo = listatipos.k62_lista";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($k62_lista!=null ){
         $sql2 .= " where listatipos.k62_lista = $k62_lista "; 
       } 
       if($k62_tipodeb!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " listatipos.k62_tipodeb = $k62_tipodeb "; 
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
   function sql_query_file ( $k62_lista=null,$k62_tipodeb=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from listatipos ";
     $sql2 = "";
     if($dbwhere==""){
       if($k62_lista!=null ){
         $sql2 .= " where listatipos.k62_lista = $k62_lista "; 
       } 
       if($k62_tipodeb!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " listatipos.k62_tipodeb = $k62_tipodeb "; 
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