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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_itensfilho
class cl_db_itensfilho { 
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
   var $id_item = 0; 
   var $codfilho = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 id_item = int4 = C�digo do �tem 
                 codfilho = int4 = C�digo Arquivo 
                 ";
   //funcao construtor da classe 
   function cl_db_itensfilho() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_itensfilho"); 
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
       $this->id_item = ($this->id_item == ""?@$GLOBALS["HTTP_POST_VARS"]["id_item"]:$this->id_item);
       $this->codfilho = ($this->codfilho == ""?@$GLOBALS["HTTP_POST_VARS"]["codfilho"]:$this->codfilho);
     }else{
       $this->id_item = ($this->id_item == ""?@$GLOBALS["HTTP_POST_VARS"]["id_item"]:$this->id_item);
       $this->codfilho = ($this->codfilho == ""?@$GLOBALS["HTTP_POST_VARS"]["codfilho"]:$this->codfilho);
     }
   }
   // funcao para inclusao
   function incluir ($id_item,$codfilho){ 
      $this->atualizacampos();
       $this->id_item = $id_item; 
       $this->codfilho = $codfilho; 
     if(($this->id_item == null) || ($this->id_item == "") ){ 
       $this->erro_sql = " Campo id_item nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->codfilho == null) || ($this->codfilho == "") ){ 
       $this->erro_sql = " Campo codfilho nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_itensfilho(
                                       id_item 
                                      ,codfilho 
                       )
                values (
                                $this->id_item 
                               ,$this->codfilho 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens e seus Arquivos ($this->id_item."-".$this->codfilho) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens e seus Arquivos j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens e seus Arquivos ($this->id_item."-".$this->codfilho) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_item."-".$this->codfilho;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->id_item,$this->codfilho));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,821,'$this->id_item','I')");
       $resac = db_query("insert into db_acountkey values($acount,4861,'$this->codfilho','I')");
       $resac = db_query("insert into db_acount values($acount,658,821,'','".AddSlashes(pg_result($resaco,0,'id_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,658,4861,'','".AddSlashes(pg_result($resaco,0,'codfilho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($id_item=null,$codfilho=null) { 
      $this->atualizacampos();
     $sql = " update db_itensfilho set ";
     $virgula = "";
     if(trim($this->id_item)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_item"])){ 
       $sql  .= $virgula." id_item = $this->id_item ";
       $virgula = ",";
       if(trim($this->id_item) == null ){ 
         $this->erro_sql = " Campo C�digo do �tem nao Informado.";
         $this->erro_campo = "id_item";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codfilho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codfilho"])){ 
       $sql  .= $virgula." codfilho = $this->codfilho ";
       $virgula = ",";
       if(trim($this->codfilho) == null ){ 
         $this->erro_sql = " Campo C�digo Arquivo nao Informado.";
         $this->erro_campo = "codfilho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($id_item!=null){
       $sql .= " id_item = $this->id_item";
     }
     if($codfilho!=null){
       $sql .= " and  codfilho = $this->codfilho";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->id_item,$this->codfilho));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,821,'$this->id_item','A')");
         $resac = db_query("insert into db_acountkey values($acount,4861,'$this->codfilho','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_item"]))
           $resac = db_query("insert into db_acount values($acount,658,821,'".AddSlashes(pg_result($resaco,$conresaco,'id_item'))."','$this->id_item',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codfilho"]))
           $resac = db_query("insert into db_acount values($acount,658,4861,'".AddSlashes(pg_result($resaco,$conresaco,'codfilho'))."','$this->codfilho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens e seus Arquivos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_item."-".$this->codfilho;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens e seus Arquivos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_item."-".$this->codfilho;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_item."-".$this->codfilho;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($id_item=null,$codfilho=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($id_item,$codfilho));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,821,'$id_item','E')");
         $resac = db_query("insert into db_acountkey values($acount,4861,'$codfilho','E')");
         $resac = db_query("insert into db_acount values($acount,658,821,'','".AddSlashes(pg_result($resaco,$iresaco,'id_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,658,4861,'','".AddSlashes(pg_result($resaco,$iresaco,'codfilho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_itensfilho
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($id_item != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " id_item = $id_item ";
        }
        if($codfilho != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codfilho = $codfilho ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens e seus Arquivos nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$id_item."-".$codfilho;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens e seus Arquivos nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$id_item."-".$codfilho;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$id_item."-".$codfilho;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:db_itensfilho";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>