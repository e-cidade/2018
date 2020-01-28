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

//MODULO: patrimonio
//CLASSE DA ENTIDADE bensmater
class cl_bensmater { 
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
   var $t53_codbem = 0; 
   var $t53_ntfisc = null; 
   var $t53_empen = null; 
   var $t53_ordem = 0; 
   var $t53_garant_dia = null; 
   var $t53_garant_mes = null; 
   var $t53_garant_ano = null; 
   var $t53_garant = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t53_codbem = int8 = Código do bem 
                 t53_ntfisc = varchar(50) = Nota fiscal 
                 t53_empen = varchar(20) = Número do empenho 
                 t53_ordem = int8 = Ordem de compra 
                 t53_garant = date = Garantia 
                 ";
   //funcao construtor da classe 
   function cl_bensmater() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bensmater"); 
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
       $this->t53_codbem = ($this->t53_codbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t53_codbem"]:$this->t53_codbem);
       $this->t53_ntfisc = ($this->t53_ntfisc == ""?@$GLOBALS["HTTP_POST_VARS"]["t53_ntfisc"]:$this->t53_ntfisc);
       $this->t53_empen = ($this->t53_empen == ""?@$GLOBALS["HTTP_POST_VARS"]["t53_empen"]:$this->t53_empen);
       $this->t53_ordem = ($this->t53_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["t53_ordem"]:$this->t53_ordem);
       if($this->t53_garant == ""){
         $this->t53_garant_dia = ($this->t53_garant_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t53_garant_dia"]:$this->t53_garant_dia);
         $this->t53_garant_mes = ($this->t53_garant_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t53_garant_mes"]:$this->t53_garant_mes);
         $this->t53_garant_ano = ($this->t53_garant_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t53_garant_ano"]:$this->t53_garant_ano);
         if($this->t53_garant_dia != ""){
            $this->t53_garant = $this->t53_garant_ano."-".$this->t53_garant_mes."-".$this->t53_garant_dia;
         }
       }
     }else{
       $this->t53_codbem = ($this->t53_codbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t53_codbem"]:$this->t53_codbem);
     }
   }
   // funcao para inclusao
   function incluir ($t53_codbem){ 
      $this->atualizacampos();
     if($this->t53_ntfisc == null ){ 
       $this->erro_sql = " Campo Nota fiscal nao Informado.";
       $this->erro_campo = "t53_ntfisc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t53_empen == null ){ 
       $this->t53_empen = "0";
     }
     if($this->t53_ordem == null ){ 
       $this->t53_ordem = "0";
     }
     if($this->t53_garant == null ){ 
       $this->erro_sql = " Campo Garantia nao Informado.";
       $this->erro_campo = "t53_garant_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->t53_codbem = $t53_codbem; 
     if(($this->t53_codbem == null) || ($this->t53_codbem == "") ){ 
       $this->erro_sql = " Campo t53_codbem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bensmater(
                                       t53_codbem 
                                      ,t53_ntfisc 
                                      ,t53_empen 
                                      ,t53_ordem 
                                      ,t53_garant 
                       )
                values (
                                $this->t53_codbem 
                               ,'$this->t53_ntfisc' 
                               ,'$this->t53_empen' 
                               ,$this->t53_ordem 
                               ,".($this->t53_garant == "null" || $this->t53_garant == ""?"null":"'".$this->t53_garant."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Material do bem ($this->t53_codbem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Material do bem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Material do bem ($this->t53_codbem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t53_codbem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t53_codbem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5776,'$this->t53_codbem','I')");
       $resac = db_query("insert into db_acount values($acount,915,5776,'','".AddSlashes(pg_result($resaco,0,'t53_codbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,915,5777,'','".AddSlashes(pg_result($resaco,0,'t53_ntfisc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,915,5778,'','".AddSlashes(pg_result($resaco,0,'t53_empen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,915,5779,'','".AddSlashes(pg_result($resaco,0,'t53_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,915,5780,'','".AddSlashes(pg_result($resaco,0,'t53_garant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t53_codbem=null) { 
      $this->atualizacampos();
     $sql = " update bensmater set ";
     $virgula = "";
     if(trim($this->t53_codbem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t53_codbem"])){ 
       $sql  .= $virgula." t53_codbem = $this->t53_codbem ";
       $virgula = ",";
       if(trim($this->t53_codbem) == null ){ 
         $this->erro_sql = " Campo Código do bem nao Informado.";
         $this->erro_campo = "t53_codbem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t53_ntfisc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t53_ntfisc"])){ 
       $sql  .= $virgula." t53_ntfisc = '$this->t53_ntfisc' ";
       $virgula = ",";
       if(trim($this->t53_ntfisc) == null ){ 
         $this->erro_sql = " Campo Nota fiscal nao Informado.";
         $this->erro_campo = "t53_ntfisc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t53_empen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t53_empen"])){ 
       $sql  .= $virgula." t53_empen = '$this->t53_empen' ";
       $virgula = ",";
     }
     if(trim($this->t53_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t53_ordem"])){ 
        if(trim($this->t53_ordem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t53_ordem"])){ 
           $this->t53_ordem = "0" ; 
        } 
       $sql  .= $virgula." t53_ordem = $this->t53_ordem ";
       $virgula = ",";
     }
     if(trim($this->t53_garant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t53_garant_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t53_garant_dia"] !="") ){ 
       $sql  .= $virgula." t53_garant = '$this->t53_garant' ";
       $virgula = ",";
       if(trim($this->t53_garant) == null ){ 
         $this->erro_sql = " Campo Garantia nao Informado.";
         $this->erro_campo = "t53_garant_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t53_garant_dia"])){ 
         $sql  .= $virgula." t53_garant = null ";
         $virgula = ",";
         if(trim($this->t53_garant) == null ){ 
           $this->erro_sql = " Campo Garantia nao Informado.";
           $this->erro_campo = "t53_garant_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($t53_codbem!=null){
       $sql .= " t53_codbem = $this->t53_codbem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t53_codbem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5776,'$this->t53_codbem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t53_codbem"]) || $this->t53_codbem != "")
           $resac = db_query("insert into db_acount values($acount,915,5776,'".AddSlashes(pg_result($resaco,$conresaco,'t53_codbem'))."','$this->t53_codbem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t53_ntfisc"]) || $this->t53_ntfisc != "")
           $resac = db_query("insert into db_acount values($acount,915,5777,'".AddSlashes(pg_result($resaco,$conresaco,'t53_ntfisc'))."','$this->t53_ntfisc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t53_empen"]) || $this->t53_empen != "")
           $resac = db_query("insert into db_acount values($acount,915,5778,'".AddSlashes(pg_result($resaco,$conresaco,'t53_empen'))."','$this->t53_empen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t53_ordem"]) || $this->t53_ordem != "")
           $resac = db_query("insert into db_acount values($acount,915,5779,'".AddSlashes(pg_result($resaco,$conresaco,'t53_ordem'))."','$this->t53_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t53_garant"]) || $this->t53_garant != "")
           $resac = db_query("insert into db_acount values($acount,915,5780,'".AddSlashes(pg_result($resaco,$conresaco,'t53_garant'))."','$this->t53_garant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Material do bem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t53_codbem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Material do bem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t53_codbem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t53_codbem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t53_codbem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t53_codbem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5776,'$t53_codbem','E')");
         $resac = db_query("insert into db_acount values($acount,915,5776,'','".AddSlashes(pg_result($resaco,$iresaco,'t53_codbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,915,5777,'','".AddSlashes(pg_result($resaco,$iresaco,'t53_ntfisc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,915,5778,'','".AddSlashes(pg_result($resaco,$iresaco,'t53_empen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,915,5779,'','".AddSlashes(pg_result($resaco,$iresaco,'t53_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,915,5780,'','".AddSlashes(pg_result($resaco,$iresaco,'t53_garant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bensmater
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t53_codbem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t53_codbem = $t53_codbem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Material do bem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t53_codbem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Material do bem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t53_codbem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t53_codbem;
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
        $this->erro_sql   = "Record Vazio na Tabela:bensmater";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t53_codbem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensmater ";
     $sql .= "      inner join bens  on  bens.t52_bem = bensmater.t53_codbem";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = bens.t52_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      inner join bensmarca  on  bensmarca.t65_sequencial = bens.t52_bensmarca";
     $sql .= "      inner join bensmodelo  on  bensmodelo.t66_sequencial = bens.t52_bensmodelo";
     $sql .= "      inner join bensmedida  on  bensmedida.t67_sequencial = bens.t52_bensmedida";
     $sql2 = "";
     if($dbwhere==""){
       if($t53_codbem!=null ){
         $sql2 .= " where bensmater.t53_codbem = $t53_codbem "; 
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
   function sql_query_file ( $t53_codbem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensmater ";
     $sql2 = "";
     if($dbwhere==""){
       if($t53_codbem!=null ){
         $sql2 .= " where bensmater.t53_codbem = $t53_codbem "; 
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
   function sql_query_bensmater ( $t53_codbem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from bensmater ";
     $sql .= "      left join empempenho  on  empempenho.e60_numemp = bensmater.t53_empen";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($t53_codbem!=null ){
         $sql2 .= " where bensmater.t53_codbem = $t53_codbem ";
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