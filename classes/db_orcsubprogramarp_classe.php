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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcsubprogramarp
class cl_orcsubprogramarp { 
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
   var $o32_anousu = 0; 
   var $o32_subprog = 0; 
   var $o32_descr = null; 
   var $o32_finali = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o32_anousu = int8 = Exercício 
                 o32_subprog = int8 = Subprograma 
                 o32_descr = varchar(40) = Descrição do Subprograma 
                 o32_finali = text = Descrição Completa do Subprograma 
                 ";
   //funcao construtor da classe 
   function cl_orcsubprogramarp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcsubprogramarp"); 
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
       $this->o32_anousu = ($this->o32_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o32_anousu"]:$this->o32_anousu);
       $this->o32_subprog = ($this->o32_subprog == ""?@$GLOBALS["HTTP_POST_VARS"]["o32_subprog"]:$this->o32_subprog);
       $this->o32_descr = ($this->o32_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o32_descr"]:$this->o32_descr);
       $this->o32_finali = ($this->o32_finali == ""?@$GLOBALS["HTTP_POST_VARS"]["o32_finali"]:$this->o32_finali);
     }else{
       $this->o32_anousu = ($this->o32_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o32_anousu"]:$this->o32_anousu);
       $this->o32_subprog = ($this->o32_subprog == ""?@$GLOBALS["HTTP_POST_VARS"]["o32_subprog"]:$this->o32_subprog);
     }
   }
   // funcao para inclusao
   function incluir ($o32_anousu,$o32_subprog){ 
      $this->atualizacampos();
     if($this->o32_descr == null ){ 
       $this->erro_sql = " Campo Descrição do Subprograma nao Informado.";
       $this->erro_campo = "o32_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o32_finali == null ){ 
       $this->erro_sql = " Campo Descrição Completa do Subprograma nao Informado.";
       $this->erro_campo = "o32_finali";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o32_anousu = $o32_anousu; 
       $this->o32_subprog = $o32_subprog; 
     if(($this->o32_anousu == null) || ($this->o32_anousu == "") ){ 
       $this->erro_sql = " Campo o32_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o32_subprog == null) || ($this->o32_subprog == "") ){ 
       $this->erro_sql = " Campo o32_subprog nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcsubprogramarp(
                                       o32_anousu 
                                      ,o32_subprog 
                                      ,o32_descr 
                                      ,o32_finali 
                       )
                values (
                                $this->o32_anousu 
                               ,$this->o32_subprog 
                               ,'$this->o32_descr' 
                               ,'$this->o32_finali' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Subprogramas dos anos anteriores a 2005 ($this->o32_anousu."-".$this->o32_subprog) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Subprogramas dos anos anteriores a 2005 já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Subprogramas dos anos anteriores a 2005 ($this->o32_anousu."-".$this->o32_subprog) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o32_anousu."-".$this->o32_subprog;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o32_anousu,$this->o32_subprog));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6419,'$this->o32_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,6420,'$this->o32_subprog','I')");
       $resac = db_query("insert into db_acount values($acount,1054,6419,'','".AddSlashes(pg_result($resaco,0,'o32_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1054,6420,'','".AddSlashes(pg_result($resaco,0,'o32_subprog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1054,6421,'','".AddSlashes(pg_result($resaco,0,'o32_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1054,6422,'','".AddSlashes(pg_result($resaco,0,'o32_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o32_anousu=null,$o32_subprog=null) { 
      $this->atualizacampos();
     $sql = " update orcsubprogramarp set ";
     $virgula = "";
     if(trim($this->o32_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o32_anousu"])){ 
       $sql  .= $virgula." o32_anousu = $this->o32_anousu ";
       $virgula = ",";
       if(trim($this->o32_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o32_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o32_subprog)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o32_subprog"])){ 
       $sql  .= $virgula." o32_subprog = $this->o32_subprog ";
       $virgula = ",";
       if(trim($this->o32_subprog) == null ){ 
         $this->erro_sql = " Campo Subprograma nao Informado.";
         $this->erro_campo = "o32_subprog";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o32_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o32_descr"])){ 
       $sql  .= $virgula." o32_descr = '$this->o32_descr' ";
       $virgula = ",";
       if(trim($this->o32_descr) == null ){ 
         $this->erro_sql = " Campo Descrição do Subprograma nao Informado.";
         $this->erro_campo = "o32_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o32_finali)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o32_finali"])){ 
       $sql  .= $virgula." o32_finali = '$this->o32_finali' ";
       $virgula = ",";
       if(trim($this->o32_finali) == null ){ 
         $this->erro_sql = " Campo Descrição Completa do Subprograma nao Informado.";
         $this->erro_campo = "o32_finali";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o32_anousu!=null){
       $sql .= " o32_anousu = $this->o32_anousu";
     }
     if($o32_subprog!=null){
       $sql .= " and  o32_subprog = $this->o32_subprog";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o32_anousu,$this->o32_subprog));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6419,'$this->o32_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,6420,'$this->o32_subprog','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o32_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1054,6419,'".AddSlashes(pg_result($resaco,$conresaco,'o32_anousu'))."','$this->o32_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o32_subprog"]))
           $resac = db_query("insert into db_acount values($acount,1054,6420,'".AddSlashes(pg_result($resaco,$conresaco,'o32_subprog'))."','$this->o32_subprog',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o32_descr"]))
           $resac = db_query("insert into db_acount values($acount,1054,6421,'".AddSlashes(pg_result($resaco,$conresaco,'o32_descr'))."','$this->o32_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o32_finali"]))
           $resac = db_query("insert into db_acount values($acount,1054,6422,'".AddSlashes(pg_result($resaco,$conresaco,'o32_finali'))."','$this->o32_finali',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Subprogramas dos anos anteriores a 2005 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o32_anousu."-".$this->o32_subprog;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Subprogramas dos anos anteriores a 2005 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o32_anousu."-".$this->o32_subprog;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o32_anousu."-".$this->o32_subprog;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o32_anousu=null,$o32_subprog=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o32_anousu,$o32_subprog));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6419,'$o32_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,6420,'$o32_subprog','E')");
         $resac = db_query("insert into db_acount values($acount,1054,6419,'','".AddSlashes(pg_result($resaco,$iresaco,'o32_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1054,6420,'','".AddSlashes(pg_result($resaco,$iresaco,'o32_subprog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1054,6421,'','".AddSlashes(pg_result($resaco,$iresaco,'o32_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1054,6422,'','".AddSlashes(pg_result($resaco,$iresaco,'o32_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcsubprogramarp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o32_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o32_anousu = $o32_anousu ";
        }
        if($o32_subprog != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o32_subprog = $o32_subprog ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Subprogramas dos anos anteriores a 2005 nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o32_anousu."-".$o32_subprog;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Subprogramas dos anos anteriores a 2005 nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o32_anousu."-".$o32_subprog;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o32_anousu."-".$o32_subprog;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcsubprogramarp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>