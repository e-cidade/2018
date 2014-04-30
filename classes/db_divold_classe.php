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

//MODULO: dividaativa
//CLASSE DA ENTIDADE divold
class cl_divold { 
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
   var $k10_sequencial = 0; 
   var $k10_coddiv = 0; 
   var $k10_numpre = 0; 
   var $k10_numpar = 0; 
   var $k10_receita = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k10_sequencial = int4 = Sequencial 
                 k10_coddiv = int4 = codigo da divida 
                 k10_numpre = int4 = Numpre Antigo(arreold) 
                 k10_numpar = int4 = Parcela Antiga(arreold) 
                 k10_receita = int4 = codigo da receita(antes) 
                 ";
   //funcao construtor da classe 
   function cl_divold() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("divold"); 
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
       $this->k10_sequencial = ($this->k10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k10_sequencial"]:$this->k10_sequencial);
       $this->k10_coddiv = ($this->k10_coddiv == ""?@$GLOBALS["HTTP_POST_VARS"]["k10_coddiv"]:$this->k10_coddiv);
       $this->k10_numpre = ($this->k10_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k10_numpre"]:$this->k10_numpre);
       $this->k10_numpar = ($this->k10_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k10_numpar"]:$this->k10_numpar);
       $this->k10_receita = ($this->k10_receita == ""?@$GLOBALS["HTTP_POST_VARS"]["k10_receita"]:$this->k10_receita);
     }else{
       $this->k10_sequencial = ($this->k10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k10_sequencial"]:$this->k10_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k10_sequencial){ 
      $this->atualizacampos();
     if($this->k10_coddiv == null ){ 
       $this->erro_sql = " Campo codigo da divida nao Informado.";
       $this->erro_campo = "k10_coddiv";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k10_numpre == null ){ 
       $this->erro_sql = " Campo Numpre Antigo(arreold) nao Informado.";
       $this->erro_campo = "k10_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k10_numpar == null ){ 
       $this->erro_sql = " Campo Parcela Antiga(arreold) nao Informado.";
       $this->erro_campo = "k10_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k10_receita == null ){ 
       $this->erro_sql = " Campo codigo da receita(antes) nao Informado.";
       $this->erro_campo = "k10_receita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k10_sequencial == "" || $k10_sequencial == null ){
       $result = db_query("select nextval('divold_k10_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: divold_k10_sequencial_seq do campo: k10_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k10_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from divold_k10_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k10_sequencial)){
         $this->erro_sql = " Campo k10_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k10_sequencial = $k10_sequencial; 
       }
     }
     if(($this->k10_sequencial == null) || ($this->k10_sequencial == "") ){ 
       $this->erro_sql = " Campo k10_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into divold(
                                       k10_sequencial 
                                      ,k10_coddiv 
                                      ,k10_numpre 
                                      ,k10_numpar 
                                      ,k10_receita 
                       )
                values (
                                $this->k10_sequencial 
                               ,$this->k10_coddiv 
                               ,$this->k10_numpre 
                               ,$this->k10_numpar 
                               ,$this->k10_receita 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "divold ($this->k10_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "divold já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "divold ($this->k10_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k10_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k10_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9208,'$this->k10_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1215,9208,'','".AddSlashes(pg_result($resaco,0,'k10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1215,7316,'','".AddSlashes(pg_result($resaco,0,'k10_coddiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1215,7317,'','".AddSlashes(pg_result($resaco,0,'k10_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1215,7318,'','".AddSlashes(pg_result($resaco,0,'k10_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1215,7319,'','".AddSlashes(pg_result($resaco,0,'k10_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k10_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update divold set ";
     $virgula = "";
     if(trim($this->k10_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k10_sequencial"])){ 
       $sql  .= $virgula." k10_sequencial = $this->k10_sequencial ";
       $virgula = ",";
       if(trim($this->k10_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k10_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k10_coddiv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k10_coddiv"])){ 
       $sql  .= $virgula." k10_coddiv = $this->k10_coddiv ";
       $virgula = ",";
       if(trim($this->k10_coddiv) == null ){ 
         $this->erro_sql = " Campo codigo da divida nao Informado.";
         $this->erro_campo = "k10_coddiv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k10_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k10_numpre"])){ 
       $sql  .= $virgula." k10_numpre = $this->k10_numpre ";
       $virgula = ",";
       if(trim($this->k10_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre Antigo(arreold) nao Informado.";
         $this->erro_campo = "k10_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k10_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k10_numpar"])){ 
       $sql  .= $virgula." k10_numpar = $this->k10_numpar ";
       $virgula = ",";
       if(trim($this->k10_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela Antiga(arreold) nao Informado.";
         $this->erro_campo = "k10_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k10_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k10_receita"])){ 
       $sql  .= $virgula." k10_receita = $this->k10_receita ";
       $virgula = ",";
       if(trim($this->k10_receita) == null ){ 
         $this->erro_sql = " Campo codigo da receita(antes) nao Informado.";
         $this->erro_campo = "k10_receita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k10_sequencial!=null){
       $sql .= " k10_sequencial = $this->k10_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k10_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9208,'$this->k10_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k10_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1215,9208,'".AddSlashes(pg_result($resaco,$conresaco,'k10_sequencial'))."','$this->k10_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k10_coddiv"]))
           $resac = db_query("insert into db_acount values($acount,1215,7316,'".AddSlashes(pg_result($resaco,$conresaco,'k10_coddiv'))."','$this->k10_coddiv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k10_numpre"]))
           $resac = db_query("insert into db_acount values($acount,1215,7317,'".AddSlashes(pg_result($resaco,$conresaco,'k10_numpre'))."','$this->k10_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k10_numpar"]))
           $resac = db_query("insert into db_acount values($acount,1215,7318,'".AddSlashes(pg_result($resaco,$conresaco,'k10_numpar'))."','$this->k10_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k10_receita"]))
           $resac = db_query("insert into db_acount values($acount,1215,7319,'".AddSlashes(pg_result($resaco,$conresaco,'k10_receita'))."','$this->k10_receita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "divold nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "divold nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k10_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k10_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9208,'$k10_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1215,9208,'','".AddSlashes(pg_result($resaco,$iresaco,'k10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1215,7316,'','".AddSlashes(pg_result($resaco,$iresaco,'k10_coddiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1215,7317,'','".AddSlashes(pg_result($resaco,$iresaco,'k10_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1215,7318,'','".AddSlashes(pg_result($resaco,$iresaco,'k10_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1215,7319,'','".AddSlashes(pg_result($resaco,$iresaco,'k10_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from divold
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k10_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k10_sequencial = $k10_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "divold nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "divold nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k10_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:divold";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from divold ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = divold.k10_receita";
     $sql .= "      inner join divida  on  divida.v01_coddiv = divold.k10_coddiv";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = divida.v01_numcgm";
     $sql .= "      inner join proced  on  proced.v03_codigo = divida.v01_proced";
     $sql2 = "";
     if($dbwhere==""){
       if($k10_sequencial!=null ){
         $sql2 .= " where divold.k10_sequencial = $k10_sequencial "; 
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
   function sql_query_file ( $k10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from divold ";
     $sql2 = "";
     if($dbwhere==""){
       if($k10_sequencial!=null ){
         $sql2 .= " where divold.k10_sequencial = $k10_sequencial "; 
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
   function sql_query_old ( $k10_coddiv=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from divold ";
     $sql .= "      inner join arreold on k10_numpre=k00_numpre and k10_numpar=k00_numpar and k10_receita=k00_receit ";
     $sql2 = "";
     if($dbwhere==""){
       if($k10_coddiv!=null ){
         $sql2 .= " where divold.k10_coddiv = $k10_coddiv ";
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