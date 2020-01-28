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
//CLASSE DA ENTIDADE perimp
class cl_perimp { 
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
   var $d51_usuario = 0; 
   var $d51_impres = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d51_usuario = int4 = Codigo do usuario 
                 d51_impres = int4 = codigo da impressora 
                 ";
   //funcao construtor da classe 
   function cl_perimp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("perimp"); 
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
       $this->d51_usuario = ($this->d51_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["d51_usuario"]:$this->d51_usuario);
       $this->d51_impres = ($this->d51_impres == ""?@$GLOBALS["HTTP_POST_VARS"]["d51_impres"]:$this->d51_impres);
     }else{
       $this->d51_usuario = ($this->d51_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["d51_usuario"]:$this->d51_usuario);
       $this->d51_impres = ($this->d51_impres == ""?@$GLOBALS["HTTP_POST_VARS"]["d51_impres"]:$this->d51_impres);
     }
   }
   // funcao para inclusao
   function incluir ($d51_usuario,$d51_impres){ 
      $this->atualizacampos();
       $this->d51_usuario = $d51_usuario; 
       $this->d51_impres = $d51_impres; 
     if(($this->d51_usuario == null) || ($this->d51_usuario == "") ){ 
       $this->erro_sql = " Campo d51_usuario nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->d51_impres == null) || ($this->d51_impres == "") ){ 
       $this->erro_sql = " Campo d51_impres nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into perimp(
                                       d51_usuario 
                                      ,d51_impres 
                       )
                values (
                                $this->d51_usuario 
                               ,$this->d51_impres 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Impressoras por usuarios ($this->d51_usuario."-".$this->d51_impres) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Impressoras por usuarios j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Impressoras por usuarios ($this->d51_usuario."-".$this->d51_impres) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d51_usuario."-".$this->d51_impres;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d51_usuario,$this->d51_impres));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1948,'$this->d51_usuario','I')");
       $resac = db_query("insert into db_acountkey values($acount,1949,'$this->d51_impres','I')");
       $resac = db_query("insert into db_acount values($acount,322,1948,'','".AddSlashes(pg_result($resaco,0,'d51_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,322,1949,'','".AddSlashes(pg_result($resaco,0,'d51_impres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d51_usuario=null,$d51_impres=null) { 
      $this->atualizacampos();
     $sql = " update perimp set ";
     $virgula = "";
     if(trim($this->d51_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d51_usuario"])){ 
       $sql  .= $virgula." d51_usuario = $this->d51_usuario ";
       $virgula = ",";
       if(trim($this->d51_usuario) == null ){ 
         $this->erro_sql = " Campo Codigo do usuario nao Informado.";
         $this->erro_campo = "d51_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d51_impres)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d51_impres"])){ 
       $sql  .= $virgula." d51_impres = $this->d51_impres ";
       $virgula = ",";
       if(trim($this->d51_impres) == null ){ 
         $this->erro_sql = " Campo codigo da impressora nao Informado.";
         $this->erro_campo = "d51_impres";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($d51_usuario!=null){
       $sql .= " d51_usuario = $this->d51_usuario";
     }
     if($d51_impres!=null){
       $sql .= " and  d51_impres = $this->d51_impres";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d51_usuario,$this->d51_impres));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1948,'$this->d51_usuario','A')");
         $resac = db_query("insert into db_acountkey values($acount,1949,'$this->d51_impres','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d51_usuario"]))
           $resac = db_query("insert into db_acount values($acount,322,1948,'".AddSlashes(pg_result($resaco,$conresaco,'d51_usuario'))."','$this->d51_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d51_impres"]))
           $resac = db_query("insert into db_acount values($acount,322,1949,'".AddSlashes(pg_result($resaco,$conresaco,'d51_impres'))."','$this->d51_impres',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Impressoras por usuarios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d51_usuario."-".$this->d51_impres;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Impressoras por usuarios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d51_usuario."-".$this->d51_impres;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d51_usuario."-".$this->d51_impres;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d51_usuario=null,$d51_impres=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d51_usuario,$d51_impres));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1948,'$d51_usuario','E')");
         $resac = db_query("insert into db_acountkey values($acount,1949,'$d51_impres','E')");
         $resac = db_query("insert into db_acount values($acount,322,1948,'','".AddSlashes(pg_result($resaco,$iresaco,'d51_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,322,1949,'','".AddSlashes(pg_result($resaco,$iresaco,'d51_impres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from perimp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d51_usuario != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d51_usuario = $d51_usuario ";
        }
        if($d51_impres != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d51_impres = $d51_impres ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Impressoras por usuarios nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d51_usuario."-".$d51_impres;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Impressoras por usuarios nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d51_usuario."-".$d51_impres;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d51_usuario."-".$d51_impres;
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
        $this->erro_sql   = "Record Vazio na Tabela:perimp";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>