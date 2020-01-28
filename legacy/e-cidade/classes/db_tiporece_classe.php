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

//MODULO: protocolo
//CLASSE DA ENTIDADE tiporece
class cl_tiporece { 
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
   var $p08_tipo = 0; 
   var $p08_receit = 0; 
   var $p08_descr = null; 
   var $p08_valor = 0; 
   var $p08_qtufir = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p08_tipo = int4 = Tipo 
                 p08_receit = int4 = Receita 
                 p08_descr = varchar(60) = Descrição 
                 p08_valor = float8 = Valor 
                 p08_qtufir = float8 = Quantidade de UFIR 
                 ";
   //funcao construtor da classe 
   function cl_tiporece() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tiporece"); 
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
       $this->p08_tipo = ($this->p08_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["p08_tipo"]:$this->p08_tipo);
       $this->p08_receit = ($this->p08_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["p08_receit"]:$this->p08_receit);
       $this->p08_descr = ($this->p08_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["p08_descr"]:$this->p08_descr);
       $this->p08_valor = ($this->p08_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["p08_valor"]:$this->p08_valor);
       $this->p08_qtufir = ($this->p08_qtufir == ""?@$GLOBALS["HTTP_POST_VARS"]["p08_qtufir"]:$this->p08_qtufir);
     }else{
       $this->p08_tipo = ($this->p08_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["p08_tipo"]:$this->p08_tipo);
       $this->p08_receit = ($this->p08_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["p08_receit"]:$this->p08_receit);
     }
   }
   // funcao para inclusao
   function incluir ($p08_tipo,$p08_receit){ 
      $this->atualizacampos();
     if($this->p08_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "p08_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p08_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "p08_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p08_qtufir == null ){ 
       $this->erro_sql = " Campo Quantidade de UFIR nao Informado.";
       $this->erro_campo = "p08_qtufir";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->p08_tipo = $p08_tipo; 
       $this->p08_receit = $p08_receit; 
     if(($this->p08_tipo == null) || ($this->p08_tipo == "") ){ 
       $this->erro_sql = " Campo p08_tipo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->p08_receit == null) || ($this->p08_receit == "") ){ 
       $this->erro_sql = " Campo p08_receit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tiporece(
                                       p08_tipo 
                                      ,p08_receit 
                                      ,p08_descr 
                                      ,p08_valor 
                                      ,p08_qtufir 
                       )
                values (
                                $this->p08_tipo 
                               ,$this->p08_receit 
                               ,'$this->p08_descr' 
                               ,$this->p08_valor 
                               ,$this->p08_qtufir 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipos de Receitas ($this->p08_tipo."-".$this->p08_receit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipos de Receitas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipos de Receitas ($this->p08_tipo."-".$this->p08_receit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p08_tipo."-".$this->p08_receit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p08_tipo,$this->p08_receit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4755,'$this->p08_tipo','I')");
       $resac = db_query("insert into db_acountkey values($acount,4756,'$this->p08_receit','I')");
       $resac = db_query("insert into db_acount values($acount,637,4755,'','".AddSlashes(pg_result($resaco,0,'p08_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,637,4756,'','".AddSlashes(pg_result($resaco,0,'p08_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,637,4757,'','".AddSlashes(pg_result($resaco,0,'p08_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,637,4758,'','".AddSlashes(pg_result($resaco,0,'p08_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,637,4759,'','".AddSlashes(pg_result($resaco,0,'p08_qtufir'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p08_tipo=null,$p08_receit=null) { 
      $this->atualizacampos();
     $sql = " update tiporece set ";
     $virgula = "";
     if(trim($this->p08_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p08_tipo"])){ 
       $sql  .= $virgula." p08_tipo = $this->p08_tipo ";
       $virgula = ",";
       if(trim($this->p08_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "p08_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p08_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p08_receit"])){ 
       $sql  .= $virgula." p08_receit = $this->p08_receit ";
       $virgula = ",";
       if(trim($this->p08_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "p08_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p08_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p08_descr"])){ 
       $sql  .= $virgula." p08_descr = '$this->p08_descr' ";
       $virgula = ",";
       if(trim($this->p08_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "p08_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p08_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p08_valor"])){ 
       $sql  .= $virgula." p08_valor = $this->p08_valor ";
       $virgula = ",";
       if(trim($this->p08_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "p08_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p08_qtufir)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p08_qtufir"])){ 
       $sql  .= $virgula." p08_qtufir = $this->p08_qtufir ";
       $virgula = ",";
       if(trim($this->p08_qtufir) == null ){ 
         $this->erro_sql = " Campo Quantidade de UFIR nao Informado.";
         $this->erro_campo = "p08_qtufir";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p08_tipo!=null){
       $sql .= " p08_tipo = $this->p08_tipo";
     }
     if($p08_receit!=null){
       $sql .= " and  p08_receit = $this->p08_receit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p08_tipo,$this->p08_receit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4755,'$this->p08_tipo','A')");
         $resac = db_query("insert into db_acountkey values($acount,4756,'$this->p08_receit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p08_tipo"]))
           $resac = db_query("insert into db_acount values($acount,637,4755,'".AddSlashes(pg_result($resaco,$conresaco,'p08_tipo'))."','$this->p08_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p08_receit"]))
           $resac = db_query("insert into db_acount values($acount,637,4756,'".AddSlashes(pg_result($resaco,$conresaco,'p08_receit'))."','$this->p08_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p08_descr"]))
           $resac = db_query("insert into db_acount values($acount,637,4757,'".AddSlashes(pg_result($resaco,$conresaco,'p08_descr'))."','$this->p08_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p08_valor"]))
           $resac = db_query("insert into db_acount values($acount,637,4758,'".AddSlashes(pg_result($resaco,$conresaco,'p08_valor'))."','$this->p08_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p08_qtufir"]))
           $resac = db_query("insert into db_acount values($acount,637,4759,'".AddSlashes(pg_result($resaco,$conresaco,'p08_qtufir'))."','$this->p08_qtufir',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Receitas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p08_tipo."-".$this->p08_receit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Receitas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p08_tipo."-".$this->p08_receit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p08_tipo."-".$this->p08_receit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p08_tipo=null,$p08_receit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p08_tipo,$p08_receit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4755,'$p08_tipo','E')");
         $resac = db_query("insert into db_acountkey values($acount,4756,'$p08_receit','E')");
         $resac = db_query("insert into db_acount values($acount,637,4755,'','".AddSlashes(pg_result($resaco,$iresaco,'p08_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,637,4756,'','".AddSlashes(pg_result($resaco,$iresaco,'p08_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,637,4757,'','".AddSlashes(pg_result($resaco,$iresaco,'p08_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,637,4758,'','".AddSlashes(pg_result($resaco,$iresaco,'p08_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,637,4759,'','".AddSlashes(pg_result($resaco,$iresaco,'p08_qtufir'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tiporece
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p08_tipo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p08_tipo = $p08_tipo ";
        }
        if($p08_receit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p08_receit = $p08_receit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Receitas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p08_tipo."-".$p08_receit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Receitas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p08_tipo."-".$p08_receit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p08_tipo."-".$p08_receit;
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
        $this->erro_sql   = "Record Vazio na Tabela:tiporece";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>