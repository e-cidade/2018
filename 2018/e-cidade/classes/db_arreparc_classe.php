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

//MODULO: caixa
//CLASSE DA ENTIDADE arreparc
class cl_arreparc { 
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
   var $k26_numpre = 0; 
   var $k26_receit = 0; 
   var $k26_perc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k26_numpre = int4 = Numpre 
                 k26_receit = int4 = codigo da receita 
                 k26_perc = float8 = Percentual da receita 
                 ";
   //funcao construtor da classe 
   function cl_arreparc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arreparc"); 
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
       $this->k26_numpre = ($this->k26_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k26_numpre"]:$this->k26_numpre);
       $this->k26_receit = ($this->k26_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k26_receit"]:$this->k26_receit);
       $this->k26_perc = ($this->k26_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["k26_perc"]:$this->k26_perc);
     }else{
       $this->k26_numpre = ($this->k26_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k26_numpre"]:$this->k26_numpre);
       $this->k26_receit = ($this->k26_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k26_receit"]:$this->k26_receit);
     }
   }
   // funcao para inclusao
   function incluir ($k26_numpre,$k26_receit){ 
      $this->atualizacampos();
     if($this->k26_perc == null ){ 
       $this->erro_sql = " Campo Percentual da receita nao Informado.";
       $this->erro_campo = "k26_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k26_numpre = $k26_numpre; 
       $this->k26_receit = $k26_receit; 
     if(($this->k26_numpre == null) || ($this->k26_numpre == "") ){ 
       $this->erro_sql = " Campo k26_numpre nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k26_receit == null) || ($this->k26_receit == "") ){ 
       $this->erro_sql = " Campo k26_receit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arreparc(
                                       k26_numpre 
                                      ,k26_receit 
                                      ,k26_perc 
                       )
                values (
                                $this->k26_numpre 
                               ,$this->k26_receit 
                               ,$this->k26_perc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Percentual da receita em relação ao numpre ($this->k26_numpre."-".$this->k26_receit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Percentual da receita em relação ao numpre já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Percentual da receita em relação ao numpre ($this->k26_numpre."-".$this->k26_receit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k26_numpre."-".$this->k26_receit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k26_numpre,$this->k26_receit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5190,'$this->k26_numpre','I')");
       $resac = db_query("insert into db_acountkey values($acount,5191,'$this->k26_receit','I')");
       $resac = db_query("insert into db_acount values($acount,747,5190,'','".AddSlashes(pg_result($resaco,0,'k26_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,747,5191,'','".AddSlashes(pg_result($resaco,0,'k26_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,747,5192,'','".AddSlashes(pg_result($resaco,0,'k26_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k26_numpre=null,$k26_receit=null) { 
      $this->atualizacampos();
     $sql = " update arreparc set ";
     $virgula = "";
     if(trim($this->k26_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k26_numpre"])){ 
       $sql  .= $virgula." k26_numpre = $this->k26_numpre ";
       $virgula = ",";
       if(trim($this->k26_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k26_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k26_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k26_receit"])){ 
       $sql  .= $virgula." k26_receit = $this->k26_receit ";
       $virgula = ",";
       if(trim($this->k26_receit) == null ){ 
         $this->erro_sql = " Campo codigo da receita nao Informado.";
         $this->erro_campo = "k26_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k26_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k26_perc"])){ 
       $sql  .= $virgula." k26_perc = $this->k26_perc ";
       $virgula = ",";
       if(trim($this->k26_perc) == null ){ 
         $this->erro_sql = " Campo Percentual da receita nao Informado.";
         $this->erro_campo = "k26_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k26_numpre!=null){
       $sql .= " k26_numpre = $this->k26_numpre";
     }
     if($k26_receit!=null){
       $sql .= " and  k26_receit = $this->k26_receit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k26_numpre,$this->k26_receit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5190,'$this->k26_numpre','A')");
         $resac = db_query("insert into db_acountkey values($acount,5191,'$this->k26_receit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k26_numpre"]))
           $resac = db_query("insert into db_acount values($acount,747,5190,'".AddSlashes(pg_result($resaco,$conresaco,'k26_numpre'))."','$this->k26_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k26_receit"]))
           $resac = db_query("insert into db_acount values($acount,747,5191,'".AddSlashes(pg_result($resaco,$conresaco,'k26_receit'))."','$this->k26_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k26_perc"]))
           $resac = db_query("insert into db_acount values($acount,747,5192,'".AddSlashes(pg_result($resaco,$conresaco,'k26_perc'))."','$this->k26_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Percentual da receita em relação ao numpre nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k26_numpre."-".$this->k26_receit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Percentual da receita em relação ao numpre nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k26_numpre."-".$this->k26_receit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k26_numpre."-".$this->k26_receit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k26_numpre=null,$k26_receit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k26_numpre,$k26_receit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5190,'$k26_numpre','E')");
         $resac = db_query("insert into db_acountkey values($acount,5191,'$k26_receit','E')");
         $resac = db_query("insert into db_acount values($acount,747,5190,'','".AddSlashes(pg_result($resaco,$iresaco,'k26_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,747,5191,'','".AddSlashes(pg_result($resaco,$iresaco,'k26_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,747,5192,'','".AddSlashes(pg_result($resaco,$iresaco,'k26_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from arreparc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k26_numpre != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k26_numpre = $k26_numpre ";
        }
        if($k26_receit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k26_receit = $k26_receit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Percentual da receita em relação ao numpre nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k26_numpre."-".$k26_receit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Percentual da receita em relação ao numpre nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k26_numpre."-".$k26_receit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k26_numpre."-".$k26_receit;
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
        $this->erro_sql   = "Record Vazio na Tabela:arreparc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>