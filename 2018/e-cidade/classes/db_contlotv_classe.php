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

//MODULO: contrib
//CLASSE DA ENTIDADE contlotv
class cl_contlotv { 
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
   var $d06_contri = 0; 
   var $d06_idbql = 0; 
   var $d06_tipos = 0; 
   var $d06_fracao = 0; 
   var $d06_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d06_contri = int4 = Contribuicao 
                 d06_idbql = int4 = Codigo do lote 
                 d06_tipos = int4 = Tipo de Serviço 
                 d06_fracao = float8 = Fracao Ideal 
                 d06_valor = float8 = Valor do serviço 
                 ";
   //funcao construtor da classe 
   function cl_contlotv() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("contlotv"); 
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
       $this->d06_contri = ($this->d06_contri == ""?@$GLOBALS["HTTP_POST_VARS"]["d06_contri"]:$this->d06_contri);
       $this->d06_idbql = ($this->d06_idbql == ""?@$GLOBALS["HTTP_POST_VARS"]["d06_idbql"]:$this->d06_idbql);
       $this->d06_tipos = ($this->d06_tipos == ""?@$GLOBALS["HTTP_POST_VARS"]["d06_tipos"]:$this->d06_tipos);
       $this->d06_fracao = ($this->d06_fracao == ""?@$GLOBALS["HTTP_POST_VARS"]["d06_fracao"]:$this->d06_fracao);
       $this->d06_valor = ($this->d06_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["d06_valor"]:$this->d06_valor);
     }else{
       $this->d06_contri = ($this->d06_contri == ""?@$GLOBALS["HTTP_POST_VARS"]["d06_contri"]:$this->d06_contri);
       $this->d06_idbql = ($this->d06_idbql == ""?@$GLOBALS["HTTP_POST_VARS"]["d06_idbql"]:$this->d06_idbql);
       $this->d06_tipos = ($this->d06_tipos == ""?@$GLOBALS["HTTP_POST_VARS"]["d06_tipos"]:$this->d06_tipos);
     }
   }
   // funcao para inclusao
   function incluir ($d06_contri,$d06_idbql,$d06_tipos){ 
      $this->atualizacampos();
     if($this->d06_fracao == null ){ 
       $this->d06_fracao = "0";
     }
     if($this->d06_valor == null ){ 
       $this->d06_valor = "0";
     }
       $this->d06_contri = $d06_contri; 
       $this->d06_idbql = $d06_idbql; 
       $this->d06_tipos = $d06_tipos; 
     if(($this->d06_contri == null) || ($this->d06_contri == "") ){ 
       $this->erro_sql = " Campo d06_contri nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->d06_idbql == null) || ($this->d06_idbql == "") ){ 
       $this->erro_sql = " Campo d06_idbql nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->d06_tipos == null) || ($this->d06_tipos == "") ){ 
       $this->erro_sql = " Campo d06_tipos nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into contlotv(
                                       d06_contri 
                                      ,d06_idbql 
                                      ,d06_tipos 
                                      ,d06_fracao 
                                      ,d06_valor 
                       )
                values (
                                $this->d06_contri 
                               ,$this->d06_idbql 
                               ,$this->d06_tipos 
                               ,$this->d06_fracao 
                               ,$this->d06_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->d06_contri."-".$this->d06_idbql."-".$this->d06_tipos) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->d06_contri."-".$this->d06_idbql."-".$this->d06_tipos) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d06_contri."-".$this->d06_idbql."-".$this->d06_tipos;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d06_contri,$this->d06_idbql,$this->d06_tipos));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,702,'$this->d06_contri','I')");
       $resac = db_query("insert into db_acountkey values($acount,703,'$this->d06_idbql','I')");
       $resac = db_query("insert into db_acountkey values($acount,704,'$this->d06_tipos','I')");
       $resac = db_query("insert into db_acount values($acount,131,702,'','".AddSlashes(pg_result($resaco,0,'d06_contri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,131,703,'','".AddSlashes(pg_result($resaco,0,'d06_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,131,704,'','".AddSlashes(pg_result($resaco,0,'d06_tipos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,131,705,'','".AddSlashes(pg_result($resaco,0,'d06_fracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,131,706,'','".AddSlashes(pg_result($resaco,0,'d06_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d06_contri=null,$d06_idbql=null,$d06_tipos=null) { 
      $this->atualizacampos();
     $sql = " update contlotv set ";
     $virgula = "";
     if(trim($this->d06_contri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d06_contri"])){ 
       $sql  .= $virgula." d06_contri = $this->d06_contri ";
       $virgula = ",";
       if(trim($this->d06_contri) == null ){ 
         $this->erro_sql = " Campo Contribuicao nao Informado.";
         $this->erro_campo = "d06_contri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d06_idbql)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d06_idbql"])){ 
       $sql  .= $virgula." d06_idbql = $this->d06_idbql ";
       $virgula = ",";
       if(trim($this->d06_idbql) == null ){ 
         $this->erro_sql = " Campo Codigo do lote nao Informado.";
         $this->erro_campo = "d06_idbql";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d06_tipos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d06_tipos"])){ 
       $sql  .= $virgula." d06_tipos = $this->d06_tipos ";
       $virgula = ",";
       if(trim($this->d06_tipos) == null ){ 
         $this->erro_sql = " Campo Tipo de Serviço nao Informado.";
         $this->erro_campo = "d06_tipos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d06_fracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d06_fracao"])){ 
        if(trim($this->d06_fracao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d06_fracao"])){ 
           $this->d06_fracao = "0" ; 
        } 
       $sql  .= $virgula." d06_fracao = $this->d06_fracao ";
       $virgula = ",";
     }
     if(trim($this->d06_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d06_valor"])){ 
        if(trim($this->d06_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d06_valor"])){ 
           $this->d06_valor = "0" ; 
        } 
       $sql  .= $virgula." d06_valor = $this->d06_valor ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($d06_contri!=null){
       $sql .= " d06_contri = $this->d06_contri";
     }
     if($d06_idbql!=null){
       $sql .= " and  d06_idbql = $this->d06_idbql";
     }
     if($d06_tipos!=null){
       $sql .= " and  d06_tipos = $this->d06_tipos";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d06_contri,$this->d06_idbql,$this->d06_tipos));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,702,'$this->d06_contri','A')");
         $resac = db_query("insert into db_acountkey values($acount,703,'$this->d06_idbql','A')");
         $resac = db_query("insert into db_acountkey values($acount,704,'$this->d06_tipos','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d06_contri"]))
           $resac = db_query("insert into db_acount values($acount,131,702,'".AddSlashes(pg_result($resaco,$conresaco,'d06_contri'))."','$this->d06_contri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d06_idbql"]))
           $resac = db_query("insert into db_acount values($acount,131,703,'".AddSlashes(pg_result($resaco,$conresaco,'d06_idbql'))."','$this->d06_idbql',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d06_tipos"]))
           $resac = db_query("insert into db_acount values($acount,131,704,'".AddSlashes(pg_result($resaco,$conresaco,'d06_tipos'))."','$this->d06_tipos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d06_fracao"]))
           $resac = db_query("insert into db_acount values($acount,131,705,'".AddSlashes(pg_result($resaco,$conresaco,'d06_fracao'))."','$this->d06_fracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d06_valor"]))
           $resac = db_query("insert into db_acount values($acount,131,706,'".AddSlashes(pg_result($resaco,$conresaco,'d06_valor'))."','$this->d06_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d06_contri."-".$this->d06_idbql."-".$this->d06_tipos;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d06_contri."-".$this->d06_idbql."-".$this->d06_tipos;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d06_contri."-".$this->d06_idbql."-".$this->d06_tipos;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d06_contri=null,$d06_idbql=null,$d06_tipos=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d06_contri,$d06_idbql,$d06_tipos));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,702,'$d06_contri','E')");
         $resac = db_query("insert into db_acountkey values($acount,703,'$d06_idbql','E')");
         $resac = db_query("insert into db_acountkey values($acount,704,'$d06_tipos','E')");
         $resac = db_query("insert into db_acount values($acount,131,702,'','".AddSlashes(pg_result($resaco,$iresaco,'d06_contri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,131,703,'','".AddSlashes(pg_result($resaco,$iresaco,'d06_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,131,704,'','".AddSlashes(pg_result($resaco,$iresaco,'d06_tipos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,131,705,'','".AddSlashes(pg_result($resaco,$iresaco,'d06_fracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,131,706,'','".AddSlashes(pg_result($resaco,$iresaco,'d06_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from contlotv
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d06_contri != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d06_contri = $d06_contri ";
        }
        if($d06_idbql != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d06_idbql = $d06_idbql ";
        }
        if($d06_tipos != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d06_tipos = $d06_tipos ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d06_contri."-".$d06_idbql."-".$d06_tipos;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d06_contri."-".$d06_idbql."-".$d06_tipos;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d06_contri."-".$d06_idbql."-".$d06_tipos;
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
        $this->erro_sql   = "Record Vazio na Tabela:contlotv";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $d06_contri=null,$d06_idbql=null,$d06_tipos=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contlotv ";
     $sql .= "      inner join editaltipo  on  editaltipo.d03_tipos = contlotv.d06_tipos";
     $sql .= "      inner join contlot  on  contlot.d05_contri = contlotv.d06_contri and  contlot.d05_idbql = contlotv.d06_idbql";
     $sql .= "      inner join lote  on  lote.j34_idbql = contlot.d05_idbql";
     $sql .= "      inner join editalrua  on  editalrua.d02_contri = contlot.d05_contri";
     $sql .= "      inner join lote  as a on   a.j34_idbql = contlot.d05_idbql";
     $sql .= "      inner join editalrua  as b on   b.d02_contri = contlot.d05_contri";
     $sql2 = "";
     if($dbwhere==""){
       if($d06_contri!=null ){
         $sql2 .= " where contlotv.d06_contri = $d06_contri "; 
       } 
       if($d06_idbql!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " contlotv.d06_idbql = $d06_idbql "; 
       } 
       if($d06_tipos!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " contlotv.d06_tipos = $d06_tipos "; 
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
   function sql_query_file ( $d06_contri=null,$d06_idbql=null,$d06_tipos=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contlotv ";
     $sql2 = "";
     if($dbwhere==""){
       if($d06_contri!=null ){
         $sql2 .= " where contlotv.d06_contri = $d06_contri "; 
       } 
       if($d06_idbql!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " contlotv.d06_idbql = $d06_idbql "; 
       } 
       if($d06_tipos!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " contlotv.d06_tipos = $d06_tipos "; 
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